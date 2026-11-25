<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-tight text-gray-900">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
            <div class="flex flex-col gap-1">
                <h2 class="break-words text-2xl font-semibold tracking-tight text-gray-900 sm:text-3xl">
                    Bem-vindo, {{ auth()->user()->name }}!
                </h2>
                <p class="break-words text-sm text-gray-600">{{ auth()->user()->email }}</p>
            </div>

            <div class="mt-6 rounded-lg border border-indigo-100 bg-indigo-50/40 p-4 sm:p-5">
                <h2 id="dashboard-period-heading" class="text-sm font-semibold text-gray-900">
                    Período do dashboard
                </h2>

                <form method="GET" action="{{ route('dashboard') }}" aria-labelledby="dashboard-period-heading"
                    class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-end">
                    <div class="flex flex-col gap-1 sm:w-44">
                        <x-input-label for="month" value="Mês" />
                        <select name="month" id="month"
                            class="h-10 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ([
                                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
                            ] as $number => $label)
                                <option value="{{ $number }}" @selected($month === $number)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1 sm:w-28">
                        <x-input-label for="year" value="Ano" />
                        <x-text-input type="number" name="year" id="year" :value="$year" min="1000" max="9999"
                            required class="h-10 w-full text-sm" />
                    </div>

                    <x-primary-button class="h-10 justify-center">
                        Filtrar
                    </x-primary-button>
                </form>
            </div>
        </div>
    <section class="mt-6" aria-labelledby="monthly-summary-heading">
    <h2 id="monthly-summary-heading" class="text-lg font-semibold text-gray-900">
        Resumo do mês
    </h2>
    <dl class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
            <dt class="text-sm font-medium text-gray-600">Receitas pagas</dt>
            <dd class="mt-2 text-2xl font-semibold text-gray-900">
                R$ {{ number_format($paidIncome, 2, ',', '.') }}
            </dd>
        </div>
     <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
            <dt class="text-sm font-medium text-gray-600">Despesas pagas.</dt>
            <dd class="mt-2 text-2xl font-semibold text-gray-900">
                R$ {{ number_format($paidExpenses, 2, ',', '.') }}
            </dd>
        </div>
         <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
            <dt class="text-sm font-medium text-gray-600">Saldo do mês</dt>
            <dd class="mt-2 text-2xl font-semibold text-gray-900">
                R$ {{ number_format($balance, 2, ',', '.') }}
            </dd>
        </div>
         <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
            <dt class="text-sm font-medium text-gray-600">Total pendente</dt>
            <dd class="mt-2 text-2xl font-semibold text-gray-900">
                R$ {{ number_format($pendingTotal, 2, ',', '.') }}
            </dd>
        </div>
        </dl>
</section>    
    </div>
</x-app-layout>
