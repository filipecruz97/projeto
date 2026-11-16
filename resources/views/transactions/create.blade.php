<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Novo Lançamento
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($categories->isEmpty())
                    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-md text-sm">
                        Você ainda não tem nenhuma categoria cadastrada.
                        <a href="{{ route('categories.create') }}" class="underline font-semibold">Cadastre uma categoria primeiro</a>
                        antes de criar um lançamento.
                    </div>
                @endif

                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf

                    @include('transactions._form', [
                        'transaction' => null,
                        'submitLabel' => 'Salvar',
                    ])
                </form>

            </div>
        </div>
    </div>
</x-app-layout>