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
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->input('type'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->when($request->filled('month'), function ($query) use ($request) {
                $query->whereMonth('date', $request->integer('month'));
            })
            ->when($request->filled('year'), function ($query) use ($request) {
                $query->whereYear('date', $request->integer('year'));
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.index', compact('transactions', 'categories'));
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
        $validated = $this->validateTransaction($request);

        $request->user()
            ->transactions()
            ->create($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Lançamento cadastrado com sucesso.');
    }

    public function edit(Request $request, Transaction $transaction)
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);

        $categories = $request->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);

        $validated = $this->validateTransaction($request);

        $transaction->update($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Lançamento atualizado com sucesso.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Lançamento excluído com sucesso.');
    }

    private function validateTransaction(Request $request): array
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

        return $validated;
    }
}