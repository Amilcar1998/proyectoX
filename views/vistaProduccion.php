<?php 
include 'configuracion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Gestión de Producción - Concentrados El Gordito">
  <title>🏭 Gestión de Producción - Concentrados El Gordito</title>

  <!-- Custom fonts for this template-->
  <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Page level plugin CSS-->
  <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <style>
    body {
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background-color: #f1f5f9;
    }

    .card-custom {
      border-radius: 16px;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      border: 1px solid #e2e8f0;
      background: #ffffff;
    }

    .table-custom thead th {
      background: #0f172a;
      color: #f8fafc;
      font-weight: 600;
      border: none;
      font-size: 0.84rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      padding: 14px 16px;
    }

    .table-custom tbody tr {
      transition: all 0.15s ease-in-out;
    }

    .table-custom tbody tr:hover {
      background-color: #f8fafc !important;
    }

    .table-custom tbody td {
      vertical-align: middle;
      padding: 14px 16px;
      color: #334155;
      font-size: 0.9rem;
      border-bottom: 1px solid #f1f5f9;
    }

    /* BADGES MODERNOS DE ESTADO */
    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 9999px;
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.02em;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
      white-space: nowrap;
    }

    .status-pill-proceso {
      background-color: #f0f9ff;
      color: #0369a1;
      border: 1px solid #bae6fd;
    }
    .status-pill-proceso i { color: #0284c7; }

    .status-pill-completado {
      background-color: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }
    .status-pill-completado i { color: #059669; }

    /* FILTROS RÁPIDOS */
    .filter-btn-group .btn-filter {
      background: #ffffff;
      color: #64748b;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 7px 15px;
      font-size: 0.83rem;
      font-weight: 600;
      margin-right: 6px;
      margin-bottom: 6px;
      transition: all 0.2s ease;
    }

    .filter-btn-group .btn-filter:hover {
      background: #f8fafc;
      color: #1e293b;
      border-color: #cbd5e1;
    }

    .filter-btn-group .btn-filter.active {
      background: #0f172a;
      color: #ffffff;
      border-color: #0f172a;
      box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
    }

    .filter-count {
      display: inline-block;
      background: rgba(0, 0, 0, 0.08);
      border-radius: 999px;
      padding: 1px 7px;
      font-size: 0.72rem;
      margin-left: 4px;
    }

    .btn-filter.active .filter-count {
      background: rgba(255, 255, 255, 0.25);
      color: #ffffff;
    }
  </style>
</head>

<body id="page-top">
  <?php echo $nav; ?>

  <div id="wrapper">
    <!-- Sidebar -->
    <?php echo $menu; ?>

    <div id="content-wrapper">
      <div class="container-fluid py-4">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb bg-white shadow-sm rounded-lg mb-4 py-2 px-3 border">
          <li class="breadcrumb-item">
            <a href="controllerTablero.php" class="text-secondary"><i class="fas fa-home"></i> Inicio</a>
          </li>
          <li class="breadcrumb-item active text-dark font-weight-bold"><i class="fas fa-industry mr-1"></i> Gestión de Producción</li>
        </ol>

        <?php
        $totalProd = !empty($data) ? count($data) : 0;
        $cntProceso = 0;
        $cntCompletados = 0;
        $cntCancelados = 0;
        if (!empty($data) && is_array($data)) {
          foreach ($data as $row) {
            $st = strtolower((string)($row['estadoP'] ?? ''));
            if ($st === 'completado' || $st === 'terminado') {
              $cntCompletados++;
            } elseif ($st === 'cancelado') {
              $cntCancelados++;
            } else {
              $cntProceso++;
            }
          }
        }
        ?>

        <!-- Tarjeta Principal de Producción -->
        <div class="card card-custom mb-4">
          <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center">
            <div>
              <h5 class="m-0 font-weight-bold text-dark d-flex align-items-center">
                <span class="bg-primary text-white rounded p-2 mr-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                  <i class="fas fa-cogs"></i>
                </span>
                Control de Órdenes de Producción
              </h5>
              <small class="text-muted">Monitoreo y despacho de concentrados animales en planta de producción.</small>
            </div>
          </div>

          <!-- Filtros Rápidos -->
          <div class="px-4 pt-3 pb-2 bg-light border-bottom">
            <div class="filter-btn-group d-flex flex-wrap align-items-center mb-2">
              <span class="small font-weight-bold text-muted text-uppercase mr-2"><i class="fas fa-filter mr-1"></i>Filtrar:</span>
              <button type="button" class="btn btn-filter active" data-filter="">
                Todos <span class="filter-count"><?= $totalProd ?></span>
              </button>
              <button type="button" class="btn btn-filter" data-filter="En Proceso|Pendiente">
                <i class="fas fa-cog text-info mr-1"></i>En Planta / Proceso <span class="filter-count"><?= $cntProceso ?></span>
              </button>
              <button type="button" class="btn btn-filter" data-filter="Completado|Terminado">
                <i class="fas fa-check-circle text-success mr-1"></i>Completados <span class="filter-count"><?= $cntCompletados ?></span>
              </button>
              <button type="button" class="btn btn-filter" data-filter="Cancelado">
                <i class="fas fa-times-circle text-danger mr-1"></i>Cancelados <span class="filter-count"><?= $cntCancelados ?></span>
              </button>
            </div>
          </div>

          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-custom table-hover" id="tablaProduccion" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 90px;">N° Producción</th>
                    <th>Fecha Inicio</th>
                    <th class="text-center" style="width: 150px;">Estado</th>
                    <th>N° Pedido / Fecha</th>
                    <th>Cliente</th>
                    <th>Operario / Empleado</th>
                    <th class="text-center" style="width: 180px;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($data) && is_array($data)): ?>
                    <?php foreach ($data as $key): 
                      $idProduccion = (int)($key['idProduccion'] ?? 0);
                      $fechaProduccion = htmlspecialchars((string)($key['fechaP'] ?? ''));
                      $estadoProduccion = (string)($key['estadoP'] ?? 'activo');
                      $idPedido = (int)($key['idPedido'] ?? 0);
                      $fechaPedido = htmlspecialchars((string)($key['fechaPedido'] ?? ''));
                      $nombreCliente = htmlspecialchars((string)($key['NombreCliente'] ?? 'Cliente General'));
                      $emp = htmlspecialchars((string)($key['nombreEmp'] ?? 'Sin asignar'));

                      $isTerminado = (strtolower($estadoProduccion) === 'terminado');
                    ?>
                      <tr>
                        <td class="text-center font-weight-bold">
                          <span class="badge badge-light border px-2 py-1" style="font-size: 0.9rem;">#<?= $idProduccion ?></span>
                        </td>
                        <td>
                          <span class="text-muted"><i class="far fa-calendar-alt mr-1"></i><?= $fechaProduccion ?></span>
                        </td>
                        <td class="text-center">
                          <?php 
                            $stLower = strtolower($estadoProduccion);
                            if ($stLower === 'completado' || $stLower === 'terminado') {
                                $pillClass = 'status-pill-completado';
                                $pillIcon = 'fa-check-circle';
                                $label = 'Completado';
                                $isFinalizado = true;
                            } elseif ($stLower === 'cancelado') {
                                $pillClass = 'status-pill-cancelado';
                                $pillIcon = 'fa-times-circle';
                                $label = 'Cancelado';
                                $isFinalizado = true;
                            } elseif ($stLower === 'pendiente') {
                                $pillClass = 'status-pill-pendiente';
                                $pillIcon = 'fa-clock';
                                $label = 'Pendiente';
                                $isFinalizado = false;
                            } else {
                                $pillClass = 'status-pill-proceso';
                                $pillIcon = 'fa-cog fa-spin';
                                $label = 'En Proceso';
                                $isFinalizado = false;
                            }
                          ?>
                          <span class="status-pill <?= $pillClass ?>">
                            <i class="fas <?= $pillIcon ?>"></i> <?= htmlspecialchars($label) ?>
                          </span>
                        </td>
                        <td>
                          <span class="font-weight-bold text-dark">Pedido #<?= $idPedido ?></span>
                          <span class="small text-muted d-block"><?= $fechaPedido ?></span>
                        </td>
                        <td class="font-weight-bold text-dark">
                          <i class="fas fa-user-circle text-secondary mr-1"></i><?= $nombreCliente ?>
                        </td>
                        <td>
                          <span class="badge badge-pill badge-light border px-2 py-1"><i class="fas fa-hard-hat text-warning mr-1"></i><?= $emp ?></span>
                        </td>
                        <td class="text-center">
                          <form method="POST" class="d-inline-flex gap-1 align-items-center" onsubmit="return confirmarAccionProduccion(event, this)">
                            <input type="hidden" name="produccionID" value="<?= $idProduccion ?>">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="idPr" value="<?= $idProduccion ?>">

                            <?php if (!$isFinalizado): ?>
                              <button type="submit" class="btn btn-sm btn-success font-weight-bold shadow-sm mr-1" name="Pterminar" title="Finalizar producción de este pedido">
                                <i class="fas fa-check mr-1"></i>Terminar
                              </button>
                            <?php endif; ?>

                            <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold" name="eliminar" title="Eliminar registro">
                              <i class="fas fa-trash-alt mr-1"></i>Eliminar
                            </button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer elegante en color negro -->
      <footer class="sticky-footer bg-dark text-white mt-auto">
        <div class="container-fluid text-center">
          <span>Concentrados El Gordito &bull; Sistema de Producción &copy; <?php echo date('Y'); ?></span>
        </div>
      </footer>
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Scripts Base -->
  <script src="../controllers/vendor/jquery/jquery.min.js"></script>
  <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
  <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/sb-admin.min.js"></script>
  <script src="js/translations.js"></script>

  <script>
    let dtProduccion = null;
    $(document).ready(function() {
      dtProduccion = $('#tablaProduccion').DataTable({
        order: [[0, 'desc']],
        pageLength: 15,
        language: {
          url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
      });

      $('.btn-filter').on('click', function() {
        $('.btn-filter').removeClass('active');
        $(this).addClass('active');

        const filterVal = $(this).data('filter');
        if (filterVal) {
          dtProduccion.column(2).search(filterVal, true, false).draw();
        } else {
          dtProduccion.column(2).search('').draw();
        }
      });
    });

    function confirmarAccionProduccion(e, form) {
      const btnClicked = $(document.activeElement);
      const isTerminar = btnClicked.attr('name') === 'Pterminar';
      const isEliminar = btnClicked.attr('name') === 'eliminar';

      if (isEliminar) {
        return confirm('¿Está seguro de eliminar esta orden de producción?');
      }
      return true;
    }
  </script>
</body>
</html>
<?php 
if(isset($msj,$icon)){
  echo "<script>Swal.fire('$msj','','$icon');</script>";
}
?>
