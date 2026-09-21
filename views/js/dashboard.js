/**
 * dashboard.js - Lógica de renderizado de gráficas e interacciones del Dashboard
 * Concentrados El Gordito
 */

document.addEventListener('DOMContentLoaded', function () {
  const data = window.dashboardData || {};

  // 1. Gráfica de Pedidos Mensuales
  const chartPedidosElem = document.getElementById('chartPedidosMensuales');
  if (chartPedidosElem && Array.isArray(data.pedidosMensuales) && data.pedidosMensuales.length > 0) {
    const meses = data.pedidosMensuales.map(item => item.mes);
    const cantidades = data.pedidosMensuales.map(item => item.cantidad);

    new Chart(chartPedidosElem, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [{
          label: 'Pedidos',
          data: cantidades,
          backgroundColor: '#4e73df',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          yAxes: [{
            ticks: { beginAtZero: true }
          }]
        }
      }
    });
  }

  // 2. Gráfica de Existencias de Stock
  const chartStockElem = document.getElementById('chartStock');
  if (chartStockElem && Array.isArray(data.stockMaterias) && data.stockMaterias.length > 0) {
    const stockLabels = data.stockMaterias.map(item => item.NombreMP);
    const stockData = data.stockMaterias.map(item => item.Existencias);

    new Chart(chartStockElem, {
      type: 'bar',
      data: {
        labels: stockLabels,
        datasets: [{
          label: 'Existencias (lbs)',
          data: stockData,
          backgroundColor: '#1cc88a',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          xAxes: [{
            ticks: { beginAtZero: true }
          }]
        }
      }
    });
  }

  // 3. Gráfica de Producción por Empleado
  const chartProduccionElem = document.getElementById('chartProduccion');
  if (chartProduccionElem && Array.isArray(data.produccionEmpleado) && data.produccionEmpleado.length > 0) {
    const empLabels = data.produccionEmpleado.map(item => item.empleado);
    const empData = data.produccionEmpleado.map(item => item.totalProduccion);

    new Chart(chartProduccionElem, {
      type: 'doughnut',
      data: {
        labels: empLabels,
        datasets: [{
          data: empData,
          backgroundColor: [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
            '#e74a3b', '#858796', '#fd7e14', '#6610f2'
          ]
        }]
      },
      options: {
        responsive: true,
        legend: {
          position: 'bottom'
        }
      }
    });
  }

  // 4. Gráfica de Facturación Mensual
  const chartFacturacionElem = document.getElementById('chartFacturacion');
  if (chartFacturacionElem && Array.isArray(data.montoMensual) && data.montoMensual.length > 0) {
    const mesesFact = data.montoMensual.map(item => item.mes);
    const montosFact = data.montoMensual.map(item => item.monto);

    new Chart(chartFacturacionElem, {
      type: 'line',
      data: {
        labels: mesesFact,
        datasets: [{
          label: 'Monto Facturado ($)',
          data: montosFact,
          borderColor: '#36b9cc',
          backgroundColor: 'rgba(54, 185, 204, 0.1)',
          fill: true,
          tension: 0.3,
          pointRadius: 4,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function (value) {
                return '$' + Number(value).toLocaleString();
              }
            }
          }]
        }
      }
    });
  }
});
