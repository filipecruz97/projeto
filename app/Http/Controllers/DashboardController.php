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

        $periodTransactions = $request->user()
            ->transactions()
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        $paidIncome = (clone $periodTransactions)
            ->where('type', 'income')
            ->where('status', 'paid')
            ->sum('amount');

        $paidExpenses = (clone $periodTransactions)
            ->where('type', 'expense')
            ->where('status', 'paid')
            ->sum('amount');

        $balance = round($paidIncome - $paidExpenses, 2);

        $pendingTotal = (clone $periodTransactions)
            ->where('status', 'pending')
            ->sum('amount');

        $recentTransactions = $request->user()
            ->transactions()
            ->with('category')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();    

        return view('dashboard', compact(
            'month',
            'year',
            'paidIncome',
            'paidExpenses',
            'balance',
            'pendingTotal',
            'recentTransactions',
        ));
    }
}
