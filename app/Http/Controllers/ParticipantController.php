<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function store(Request $request, Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        $validated['token'] = Participant::generateToken();
        $validated['invited_at'] = now();

        $tender->participants()->create($validated);

        return back()->with('success', 'Participant invited.');
    }

    public function destroy(Participant $participant)
    {
        abort_unless($participant->tender->user_id === Auth::id(), 403);
        $participant->delete();
        return back()->with('success', 'Participant removed.');
    }

    public function resend(Participant $participant)
    {
        abort_unless($participant->tender->user_id === Auth::id(), 403);
        $participant->update(['invited_at' => now()]);
        return back()->with('success', 'Invitation resent to ' . $participant->email);
    }

    public function lock(Participant $participant)
    {
        abort_unless($participant->tender->user_id === Auth::id(), 403);
        $participant->update(['status' => 'locked']);
        return back()->with('success', 'Participant locked.');
    }
}
