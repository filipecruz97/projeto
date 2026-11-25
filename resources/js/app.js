

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const expensesCanvas = document.getElementById('expenses-by-category-chart');

if (expensesCanvas) {
    const expenses = JSON.parse(expensesCanvas.dataset.expenses);
    const currency = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });

    const chartHeight = Math.max(288, expenses.length * 32 + 64);
    expensesCanvas.parentElement.style.height = `${chartHeight}px`;

    import('chart.js/auto').then(({ default: Chart }) => {
        new Chart(expensesCanvas, {
            type: 'bar',
            data: {
                labels: expenses.map(({ label }) => label),
                datasets: [{
                    data: expenses.map(({ total }) => total),
                    backgroundColor: '#4f46e5',
                    borderRadius: 4,
                    maxBarThickness: 32,
                }],
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { callback: (value) => currency.format(value) },
                    },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ({ parsed }) => currency.format(parsed.x) },
                    },
                },
            },
        });
    });
}
