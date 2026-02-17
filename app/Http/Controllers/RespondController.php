<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Participant;
use App\Models\Response;
use Illuminate\Http\Request;

class RespondController extends Controller
{
    public function show(string $token)
    {
        $participant = Participant::where('token', $token)->firstOrFail();

        if ($participant->status === 'invited') {
            $participant->update(['status' => 'active', 'last_active_at' => now()]);
        }

        if ($participant->status === 'locked') {
            return view('respond.locked', compact('participant'));
        }

        $tender = $participant->tender;
        $criteria = $tender->criteria()
            ->whereNull('parent_id')
            ->with(['children.questions', 'questions'])
            ->orderBy('sort_order')
            ->get();

        $responses = $participant->responses()->pluck('answer_text', 'question_id');

        return view('respond.show', compact('participant', 'tender', 'criteria', 'responses'));
    }

    public function save(Request $request, string $token)
    {
        $participant = Participant::where('token', $token)->firstOrFail();
        abort_if($participant->status === 'locked' || $participant->status === 'submitted', 403);

        $answers = $request->input('answers', []);

        foreach ($answers as $questionId => $answer) {
            if (empty(trim($answer))) continue;

            Response::updateOrCreate(
                ['participant_id' => $participant->id, 'question_id' => $questionId],
                ['answer_text' => trim($answer), 'completeness_score' => min(1, strlen(trim($answer)) / 200)]
            );
        }

        $participant->update(['last_active_at' => now()]);

        return back()->with('success', 'Your responses have been saved. You can continue editing until you submit.');
    }

    public function submit(string $token)
    {
        $participant = Participant::where('token', $token)->firstOrFail();
        abort_if($participant->status === 'locked' || $participant->status === 'submitted', 403);

        $participant->update(['status' => 'submitted', 'submitted_at' => now()]);

        // Simulate AI follow-ups on responses
        $this->generateFollowUps($participant);

        return view('respond.submitted', compact('participant'));
    }

    private function generateFollowUps(Participant $participant): void
    {
        $responses = $participant->responses()->with('question')->get();

        foreach ($responses->take(3) as $response) {
            if (strlen($response->answer_text) < 100) {
                FollowUp::create([
                    'response_id' => $response->id,
                    'role' => 'ai',
                    'message' => "Your response to \"{$response->question->question_text}\" is relatively brief. Could you elaborate on specific implementation details or provide a concrete example from your experience?",
                    'sequence' => 1,
                ]);
            } elseif (strlen($response->answer_text) < 300) {
                FollowUp::create([
                    'response_id' => $response->id,
                    'role' => 'ai',
                    'message' => "Thank you for your detailed response. To strengthen this answer, could you address the measurability aspect — what specific KPIs or metrics would you use to evaluate success?",
                    'sequence' => 1,
                ]);
            }
        }
    }
}
