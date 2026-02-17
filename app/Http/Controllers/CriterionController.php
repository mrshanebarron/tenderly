<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriterionController extends Controller
{
    public function store(Request $request, Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'nullable|integer|min:1|max:100',
            'parent_id' => 'nullable|exists:criteria,id',
        ]);

        $validated['sort_order'] = $tender->criteria()->max('sort_order') + 1;

        $tender->criteria()->create($validated);

        return back()->with('success', 'Criterion added.');
    }

    public function update(Request $request, Criterion $criterion)
    {
        abort_unless($criterion->tender->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'nullable|integer|min:1|max:100',
        ]);

        $criterion->update($validated);

        return back()->with('success', 'Criterion updated.');
    }

    public function destroy(Criterion $criterion)
    {
        abort_unless($criterion->tender->user_id === Auth::id(), 403);
        $criterion->delete();
        return back()->with('success', 'Criterion removed.');
    }
}
