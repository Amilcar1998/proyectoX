<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['s1']) || isset($_SESSION['s2']) || isset($_SESSION['c1'])) {
    if ($_SESSION['s1']) {
        header('Location: controllerEmpleado.php');
    } elseif ($_SESSION['s2']) {
        header('Location: controllerPedidosIn.php');
    } else {
        header('Location: controllerIndividualC.php');
    }
    exit();
}

include '../models/AuditoriaModel.php';
include '../controllers/sesiones.php';

$auditoria = new AuditoriaModel();
$fechaInicio = $_GET['fecha_inicio'] ?? null;
$fechaFin = $_GET['fecha_fin'] ?? null;

$logs = $auditoria->getReporteSesiones($fechaInicio, $fechaFin);

include '../views/configuracion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Auditoria | Concentrados El Gordito</title>
   <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
   <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
   <script src="../controllers/vendor/jquery/jquery.min.js"></script>
   <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
   <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
   <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
   <script src="../controllers/js/sb-admin.min.js"></script>
   <script src="js/translations.js"></script>
   <script src="js/demo/datatables-demo.js"></script>
</head>
<body id="page-top">
   <?php echo "$nav"; ?>
   <div id="wrapper">
      <?php echo "$menu"; ?>
      <div id="content-wrapper">
         <div class="container-fluid">
            <h2 class="text-center mb-4"><i class="fas fa-clipboard-list"></i> Auditoria del Sistema</h2>

            <form method="GET" class="form-inline mb-3 justify-content-center">
                <div class="form-group mr-2">
                    <label>Fecha Inicio: </label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fechaInicio; ?>">
                </div>
                <div class="form-group mr-2">
                    <label>Fecha Fin: </label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fechaFin; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="reporteAuditoria.php" class="btn btn-secondary ml-2">Limpiar</a>
            </form>

            <div class="card mb-3">
               <div class="card-header"><i class="fas fa-table"></i> Registro de Actividades</div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                           <tr>
                              <th>Fecha/Hora</th>
                              <th>Usuario</th>
                              <th>Tipo Evento</th>
                              <th>Modulo</th>
                              <th>Descripcion</th>
                              <th>IP</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($logs as $log): ?>
                           <tr>
                              <td><?php echo date('d/m/Y H:i:s', strtotime($log['fecha_hora'])); ?></td>
                              <td><?php echo htmlspecialchars($log['username'] ?? 'Sistema'); ?></td>
                              <td><span class="badge badge-<?php
                                  echo $log['tipo_evento'] == 'login' ? 'success' :
                                       ($log['tipo_evento'] == 'logout' ? 'danger' :
                                       ($log['tipo_evento'] == 'vista' ? 'info' : 'warning'));
                              ?>"><?php echo ucfirst($log['tipo_evento']); ?></span></td>
                              <td><?php echo htmlspecialchars($log['modulo'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($log['descripcion'] ?? '-'); ?></td>
                              <td><?php echo htmlspecialchars($log['ip_address'] ?? 'N/A'); ?></td>
                           </tr>
                           <?php endforeach; ?>
                           <?php if (empty($logs)) echo '<tr><td colspan="6" class="text-center">Sin registros</td></tr>'; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
   <footer class="sticky-footer bg-dark mt-auto">
      <div class="container my-auto py-3">
         <div class="copyright text-center my-auto">
            <span class="text-white">Copyright &copy; Concentrados El Gordito 2026</span>
         </div>
      </div>
   </footer>
</body>
</html>
