<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('changelog_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('changelog_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['changelog_id', 'category_id']);
        });

        // Migrate existing category_id data to the pivot table
        $changelogs = \DB::table('changelogs')->whereNotNull('category_id')->get();
        foreach ($changelogs as $changelog) {
            \DB::table('changelog_category')->insert([
                'changelog_id' => $changelog->id,
                'category_id' => $changelog->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the old category_id column
        Schema::table('changelogs', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add category_id column
        Schema::table('changelogs', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Migrate data back (use the first category for each changelog)
        $pivotData = \DB::table('changelog_category')
            ->select('changelog_id', \DB::raw('MIN(category_id) as category_id'))
            ->groupBy('changelog_id')
            ->get();

        foreach ($pivotData as $data) {
            \DB::table('changelogs')
                ->where('id', $data->changelog_id)
                ->update(['category_id' => $data->category_id]);
        }

        Schema::dropIfExists('changelog_category');
    }
};
