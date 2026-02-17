<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenderController extends Controller
{
    public function index()
    {
        $tenders = Auth::user()->tenders()
            ->withCount(['criteria', 'participants'])
            ->latest()
            ->paginate(12);

        return view('tenders.index', compact('tenders'));
    }

    public function create()
    {
        return view('tenders.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'focus_themes' => 'nullable|string',
            'context_notes' => 'nullable|string',
            'deadline' => 'nullable|date|after:today',
        ]);

        $tender = Auth::user()->tenders()->create($validated);

        return redirect()->route('tenders.show', $tender)->with('success', 'Tender workspace created.');
    }

    public function show(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $tender->load([
            'criteria' => fn($q) => $q->whereNull('parent_id')->with('children')->orderBy('sort_order'),
            'criteria.questions',
            'participants',
            'documents',
            'analyses',
        ]);

        $totalQuestions = $tender->criteria->sum(fn($c) => $c->questions->count() + $c->children->sum(fn($ch) => $ch->questions->count()));

        return view('tenders.show', compact('tender', 'totalQuestions'));
    }

    public function edit(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);
        return view('tenders.form', compact('tender'));
    }

    public function update(Request $request, Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'focus_themes' => 'nullable|string',
            'context_notes' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $tender->update($validated);

        return redirect()->route('tenders.show', $tender)->with('success', 'Tender updated.');
    }

    public function destroy(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);
        $tender->delete();
        return redirect()->route('tenders.index')->with('success', 'Tender deleted.');
    }

    public function activate(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);
        $tender->update(['status' => 'active']);
        return back()->with('success', 'Tender activated. Participants can now respond.');
    }

    public function review(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);
        $tender->update(['status' => 'reviewing']);
        return back()->with('success', 'Tender moved to review phase.');
    }

    public function complete(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);
        $tender->update(['status' => 'completed']);
        return back()->with('success', 'Tender marked as completed.');
    }
}
