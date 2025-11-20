<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupUserTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teams:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create teams for all users who don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('current_team_id')->get();

        if ($users->isEmpty()) {
            $this->info('All users already have teams!');
            return;
        }

        $this->info("Found {$users->count()} users without teams. Setting up teams...");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            // Create team
            $team = Team::create([
                'name' => $user->name . "'s Team",
                'slug' => Str::slug($user->name . '-team-' . $user->id),
                'owner_id' => $user->id,
            ]);

            // Set as current team
            $user->update(['current_team_id' => $team->id]);

            // Add user to team as owner
            $team->members()->attach($user->id, ['role' => 'owner']);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Successfully created teams for {$users->count()} users!");
    }
}
