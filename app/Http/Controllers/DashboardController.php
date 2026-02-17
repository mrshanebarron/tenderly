<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $tenders = $user->tenders()->withCount(['criteria', 'participants'])->latest()->get();

        $stats = [
            'total' => $tenders->count(),
            'active' => $tenders->where('status', 'active')->count(),
            'reviewing' => $tenders->where('status', 'reviewing')->count(),
            'completed' => $tenders->where('status', 'completed')->count(),
        ];

        $recentTenders = $tenders->take(6);

        return view('dashboard', compact('stats', 'recentTenders'));
    }
}
