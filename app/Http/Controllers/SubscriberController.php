<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\UserSegment;
use App\Notifications\SubscriberVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    /**
     * Display a listing of subscribers.
     */
    public function index()
    {
        $subscribers = Subscriber::with('segments')->latest()->paginate(20);
        return view('subscribers.index', compact('subscribers'));
    }

    /**
     * Show the form for creating a new subscriber.
     */
    public function create()
    {
        $segments = UserSegment::where('status', 'active')->get();
        return view('subscribers.create', compact('segments'));
    }

    /**
     * Store a newly created subscriber (Admin or Upload).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:subscribers,email',
            'source' => 'required|in:upload,online_subscribe,admin',
            'segments' => 'array',
            'segments.*' => 'exists:user_segments,id',
            'send_verification' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create subscriber
        $subscriber = Subscriber::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'source' => $request->source,
            'status' => 'pending_verify',
            'subscribe_date' => now(),
        ]);

        // Attach segments
        if ($request->has('segments')) {
            $subscriber->segments()->attach($request->segments);
        }

        // Send verification email if requested
        if ($request->send_verification) {
            $subscriber->generateVerificationToken();
            Notification::route('mail', $subscriber->email)
                ->notify(new SubscriberVerificationNotification($subscriber));
        }

        return redirect()->route('subscribers.index')
            ->with('success', 'Subscriber added successfully!' .
                ($request->send_verification ? ' Verification email sent.' : ''));
    }

    /**
     * Subscribe from public form (Online Subscribe).
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:subscribers,email',
            'segments' => 'array',
            'segments.*' => 'exists:user_segments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create subscriber
        $subscriber = Subscriber::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'source' => 'online_subscribe',
            'status' => 'pending_verify',
            'subscribe_date' => now(),
        ]);

        // Attach segments
        if ($request->has('segments')) {
            $subscriber->segments()->attach($request->segments);
        }

        // Generate token and send verification email
        $subscriber->generateVerificationToken();
        Notification::route('mail', $subscriber->email)
            ->notify(new SubscriberVerificationNotification($subscriber));

        return view('subscribers.subscription-pending', compact('subscriber'));
    }

    /**
     * Verify subscriber email.
     */
    public function verify($token)
    {
        $subscriber = Subscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            return view('subscribers.verification-failed');
        }

        if ($subscriber->isVerified()) {
            return view('subscribers.already-verified', compact('subscriber'));
        }

        $subscriber->markAsVerified();

        return view('subscribers.verification-success', compact('subscriber'));
    }

    /**
     * Unsubscribe via token.
     */
    public function unsubscribe($token)
    {
        $subscriber = Subscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            // Try to find by email if token doesn't exist
            return view('subscribers.unsubscribe-not-found');
        }

        if ($subscriber->status === 'unsubscribed') {
            return view('subscribers.already-unsubscribed', compact('subscriber'));
        }

        $subscriber->unsubscribe();

        return view('subscribers.unsubscribe-success', compact('subscriber'));
    }

    /**
     * Show the form for editing a subscriber.
     */
    public function edit(Subscriber $subscriber)
    {
        $segments = UserSegment::where('status', 'active')->get();
        return view('subscribers.edit', compact('subscriber', 'segments'));
    }

    /**
     * Update the specified subscriber.
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:subscribers,email,' . $subscriber->id,
            'status' => 'required|in:subscribed,pending_verify,unsubscribed,inactive',
            'segments' => 'array',
            'segments.*' => 'exists:user_segments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $subscriber->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        // Sync segments
        if ($request->has('segments')) {
            $subscriber->segments()->sync($request->segments);
        }

        return redirect()->route('subscribers.index')
            ->with('success', 'Subscriber updated successfully!');
    }

    /**
     * Resend verification email.
     */
    public function resendVerification(Subscriber $subscriber)
    {
        if ($subscriber->isVerified()) {
            return back()->with('info', 'Subscriber is already verified.');
        }

        $subscriber->generateVerificationToken();
        Notification::route('mail', $subscriber->email)
            ->notify(new SubscriberVerificationNotification($subscriber));

        return back()->with('success', 'Verification email sent successfully!');
    }

    /**
     * Remove the specified subscriber.
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('subscribers.index')
            ->with('success', 'Subscriber deleted successfully!');
    }

    /**
     * Bulk import subscribers from CSV.
     */
    public function importCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
            'send_verification' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        $handle = fopen($file->path(), 'r');

        $header = fgetcsv($handle); // Skip header row
        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            // Expecting: full_name, email, segments (comma-separated IDs)
            if (count($row) < 2) {
                $skipped++;
                continue;
            }

            $fullName = $row[0];
            $email = $row[1];
            $segmentIds = isset($row[2]) ? explode(',', $row[2]) : [];

            // Check if email already exists
            if (Subscriber::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            // Create subscriber
            $subscriber = Subscriber::create([
                'full_name' => $fullName,
                'email' => $email,
                'source' => 'upload',
                'status' => 'pending_verify',
                'subscribe_date' => now(),
            ]);

            // Attach segments
            if (!empty($segmentIds)) {
                $subscriber->segments()->attach(array_filter($segmentIds));
            }

            // Send verification email if requested
            if ($request->send_verification) {
                $subscriber->generateVerificationToken();
                Notification::route('mail', $subscriber->email)
                    ->notify(new SubscriberVerificationNotification($subscriber));
            }

            $imported++;
        }

        fclose($handle);

        return redirect()->route('subscribers.index')
            ->with('success', "Imported {$imported} subscribers. Skipped {$skipped} duplicates.");
    }
}
