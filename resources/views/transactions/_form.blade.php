@php($transaction = $transaction ?? null)

{{-- Resumo Geral de Erros --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
        <strong class="font-bold">Corrija os erros abaixo:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Campo: Descrição --}}
<div class="mb-4">
    <label for="description" class="block font-medium text-sm text-gray-700">Descrição *</label>
    <input type="text" name="description" id="description"
        value="{{ old('description', $transaction?->description ?? '') }}" required
        placeholder="Ex.: Salário de Novembro, Supermercado..."
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('description')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Valor e Data --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
        <label for="amount" class="block font-medium text-sm text-gray-700">Valor (R$) *</label>
        <input type="number" step="0.01" min="0.01" name="amount" id="amount"
            value="{{ old('amount', $transaction?->amount ?? '') }}" required
            placeholder="0.00"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('amount')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="date" class="block font-medium text-sm text-gray-700">Data *</label>
        <input type="date" name="date" id="date"
            value="{{ old('date', $transaction?->date?->format('Y-m-d') ?? date('Y-m-d')) }}" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('date')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Tipo, Categoria e Status --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <div>
        <label for="type" class="block font-medium text-sm text-gray-700">Tipo *</label>
        <select name="type" id="type" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="expense" {{ old('type', $transaction?->type ?? 'expense') === 'expense' ? 'selected' : '' }}>Despesa</option>
            <option value="income" {{ old('type', $transaction?->type ?? 'expense') === 'income' ? 'selected' : '' }}>Receita</option>
        </select>
        @error('type')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="category_id" class="block font-medium text-sm text-gray-700">Categoria *</label>
        <select name="category_id" id="category_id" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Selecione...</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $transaction?->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }} ({{ $category->type === 'income' ? 'Receita' : 'Despesa' }})
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block font-medium text-sm text-gray-700">Status *</label>
        <select name="status" id="status" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="paid" {{ old('status', $transaction?->status ?? 'paid') === 'paid' ? 'selected' : '' }}>Pago</option>
            <option value="pending" {{ old('status', $transaction?->status ?? 'paid') === 'pending' ? 'selected' : '' }}>Pendente</option>
            <option value="cancelled" {{ old('status', $transaction?->status ?? 'paid') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
        </select>
        @error('status')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Campo: Observação --}}
<div class="mb-4">
    <label for="notes" class="block font-medium text-sm text-gray-700">Observação (Opcional)</label>
    <textarea name="notes" id="notes" rows="3"
        placeholder="Detalhes adicionais sobre o lançamento..."
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $transaction?->notes ?? '') }}</textarea>
    @error('notes')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center justify-end space-x-3 mt-6">
    <a href="{{ route('transactions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
        Voltar
    </a>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
        {{ $submitLabel ?? 'Salvar' }}
    </button>
</div>