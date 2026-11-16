<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = $request->user()
            ->transactions()
            ->with('category')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'date' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:paid,pending,cancelled'],
        ]);

        $categoryBelongsToUser = $request->user()
            ->categories()
            ->where('id', $validated['category_id'])
            ->exists();

        abort_unless($categoryBelongsToUser, 403);

        $request->user()
            ->transactions()
            ->create($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Lançamento cadastrado com sucesso.');
    }

    public function edit(Request $request, Transaction $transaction)
    {
        // Faremos no Dia 4
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Faremos no Dia 4
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        // Faremos no Dia 4
    }
}