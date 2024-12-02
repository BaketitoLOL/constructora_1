// Gráfico de ingresos mensuales
const ctxIngresos = document.getElementById('chartIngresos').getContext('2d');
new Chart(ctxIngresos, {
    type: 'line',
    data: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril'], // Modificar según datos reales
        datasets: [{
            label: 'Ingresos ($)',
            data: [5000, 10000, 15000, 20000], // Datos dinámicos
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        }
    }
});

// Gráfico de obras por estatus
const ctxObras = document.getElementById('chartObras').getContext('2d');
new Chart(ctxObras, {
    type: 'pie',
    data: {
        labels: ['En Progreso', 'Finalizadas', 'Canceladas'],
        datasets: [{
            label: 'Obras',
            data: [10, 5, 2], // Datos dinámicos
            backgroundColor: ['#007bff', '#28a745', '#dc3545'],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
