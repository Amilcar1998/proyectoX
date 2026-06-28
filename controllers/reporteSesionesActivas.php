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
$usuariosActivos = $auditoria->getUsuariosActivos();

include '../views/configuracion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Usuarios Activos | Concentrados El Gordito</title>
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
            <h2 class="text-center mb-4"><i class="fas fa-users"></i> Usuarios Activos en el Sistema</h2>
            <p class="text-center text-muted mb-4">Usuarios con sesion iniciada actualmente</p>

            <div class="card mb-3">
               <div class="card-header"><i class="fas fa-table"></i> Sesiones Activas</div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                           <tr>
                              <th>Usuario</th>
                              <th>Rol</th>
                              <th>Nombre</th>
                              <th>Inicio Sesion</th>
                              <th>Ultima Actividad</th>
                              <th>IP</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($usuariosActivos as $u): ?>
                           <tr>
                              <td><?php echo htmlspecialchars($u['username']); ?></td>
                              <td><?php echo htmlspecialchars($u['nombreRol'] ?? 'N/A'); ?></td>
                              <td><?php echo htmlspecialchars($u['nombre_usuario'] ?? 'N/A'); ?></td>
                              <td><?php echo date('d/m/Y H:i', strtotime($u['login_time'])); ?></td>
                              <td><?php echo date('d/m/Y H:i', strtotime($u['last_activity'])); ?></td>
                              <td><?php echo htmlspecialchars($u['ip_address'] ?? 'N/A'); ?></td>
                           </tr>
                           <?php endforeach; ?>
                           <?php if (empty($usuariosActivos)) echo '<tr><td colspan="6" class="text-center">No hay usuarios activos</td></tr>'; ?>
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
