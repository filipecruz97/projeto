<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
     public function index(Request $request)
    {
        $categories = $request->user()
            ->categories()
            ->latest()
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where('user_id', $request->user()->id),
            ],
            'type' => ['required', 'in:income,expense'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->categories()->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function edit(Request $request, Category $category)
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where('user_id', $request->user()->id)->ignore($category->id),
            ],
            'type' => ['required', 'in:income,expense'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Request $request, Category $category)
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria excluída com sucesso.');
    }
}