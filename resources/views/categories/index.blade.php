<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorias
            </h2>
            <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                + Nova Categoria
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensagem de Sucesso --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($categories->isEmpty())
                    <p class="text-gray-500 text-center py-6">Nenhuma categoria cadastrada ainda.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    <th class="px-4 py-3 text-left">Nome</th>
                                    <th class="px-4 py-3 text-left">Tipo</th>
                                    <th class="px-4 py-3 text-left">Descrição</th>
                                    <th class="px-4 py-3 text-left">Data de Criação</th>
                                    <th class="px-4 py-3 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                                @foreach ($categories as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $category->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $category->type === 'income' ? 'Receita' : 'Despesa' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $category->description ?: '-' }}</td>
                                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $category->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center justify-end gap-3">
                                                <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                    Editar
                                                </a>

                                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Tem a certeza que deseja excluir esta categoria?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginação --}}
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>