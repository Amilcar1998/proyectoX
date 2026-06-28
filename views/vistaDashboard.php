<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>📊 Dashboard | Concentrados El Gordito</title>

    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>

</head>

<body id="page-top">
  <?php echo "$nav"; ?>
  </nav>

  <div id="wrapper">

    <?php echo "$menu"; ?>

    <div id="content-wrapper">

        <div class="container-fluid">

          <hr>

          <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pedidos Totales</div>
                      <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['totalPedidos']; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monto Total Pedidos</div>
                      <div class="h3 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($resumen['montoTotal'], 2); ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stock Crítico</div>
                      <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['stockCritico']; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Empleados</div>
                      <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo $resumen['totalEmpleados']; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-6 col-lg-6 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3"><i class="fas fa-chart-line mr-2"></i>Pedidos Mensuales</div>
                <div class="card-body">
                  <canvas id="chartPedidosMensuales"></canvas>
                </div>
              </div>
            </div>

            <div class="col-xl-6 col-lg-6 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3"><i class="fas fa-chart-bar mr-2"></i>Stock de Materias Primas</div>
                <div class="card-body">
                  <canvas id="chartStock"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-6 col-lg-6 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3"><i class="fas fa-industry mr-2"></i>Producción por Empleado</div>
                <div class="card-body">
                  <canvas id="chartProduccion"></canvas>
                </div>
              </div>
            </div>

            <div class="col-xl-6 col-lg-6 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3"><i class="fas fa-receipt mr-2"></i>Facturación</div>
                <div class="card-body">
                  <canvas id="chartFacturacion"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3"><i class="fas fa-table mr-2"></i>Pedidos Recientes</div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Proveedor</th>
                          <th>Empleado</th>
                          <th>Materia Prima</th>
                          <th>Fecha</th>
                          <th>Cantidad</th>
                          <th>Monto</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($pedidosRecientes as $fila): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($fila['nombreProveedor'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($fila['empleado'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($fila['NombreMP'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($fila['fecha'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($fila['cantidadMP'] ?? ''); ?></td>
                          <td>$<?php echo number_format($fila['monto'] ?? 0, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- /#content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="js/sb-admin.min.js"></script>

    <script>
      const meses = <?php echo json_encode(array_column($pedidosMensuales, 'mes')); ?>;
      const cantidades = <?php echo json_encode(array_column($pedidosMensuales, 'cantidad')); ?>;
      const montos = <?php echo json_encode(array_column($pedidosMensuales, 'monto')); ?>;

      new Chart(document.getElementById('chartPedidosMensuales'), {
        type: 'bar',
        data: {
          labels: meses,
          datasets: [{
            label: 'Pedidos',
            data: cantidades,
            backgroundColor: '#4e73df'
          }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
      });

      const stockLabels = <?php echo json_encode(array_column($stockMaterias, 'NombreMP')); ?>;
      const stockData = <?php echo json_encode(array_column($stockMaterias, 'Existencias')); ?>;

      new Chart(document.getElementById('chartStock'), {
        type: 'bar',
        data: {
          labels: stockLabels,
          datasets: [{
            label: 'Existencias',
            data: stockData,
            backgroundColor: '#1cc88a'
          }]
        },
        options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } } }
      });

      const empLabels = <?php echo json_encode(array_column($produccionEmpleado, 'empleado')); ?>;
      const empData = <?php echo json_encode(array_column($produccionEmpleado, 'totalProduccion')); ?>;

      new Chart(document.getElementById('chartProduccion'), {
        type: 'doughnut',
        data: {
          labels: empLabels,
          datasets: [{
            data: empData,
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#6610f2']
          }]
        },
        options: { responsive: true }
      });

      new Chart(document.getElementById('chartFacturacion'), {
        type: 'line',
        data: {
          labels: meses,
          datasets: [{
            label: 'Monto Facturado',
            data: montos,
            borderColor: '#36b9cc',
            fill: true,
            tension: 0.3
          }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
      });
    </script>

  </body>
</html>