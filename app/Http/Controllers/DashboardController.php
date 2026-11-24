<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class DashboardController extends Controller

{
    public function index(Request $request)
    {
        $validated = $request->validate([
        'month' => ['sometimes', 'required', 'integer', 'between:1,12'],
        'year' => ['sometimes', 'required', 'integer', 'between:1000,9999'],
    ]);

        $currentDate = now();

        $month = (int) ($validated['month'] ?? $currentDate->month);
        $year = (int) ($validated['year'] ?? $currentDate->year);

        return view('dashboard', compact('month', 'year'));
    }
}
