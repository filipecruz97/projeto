<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meu Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Mensagem de boas-vindas -->
                    <p class="mb-4">
                        {{ __('Bem vindo, ') . auth()->user()->name . ' ! ' . auth()->user()->email }}
                    </p>
                    
                    <!-- Link para voltar ao Dashboard -->
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 underline font-medium">
                            &larr; Voltar para o Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>