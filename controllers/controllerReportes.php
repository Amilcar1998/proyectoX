<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../models/ModelPedido.php';
include 'sesiones.php';

$pedido = new ModelPedido();
$correo = $_SESSION['s1'] ?? '';
$session = $pedido->getSessionEmp($correo);
$nombres = '';
foreach ($session as $key) {
    $nombres = $key['nombreEmp'] . ' ' . $key['apellido'];
}

include '../views/configuracion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">

   <title>Reportes | Concentrados El Gordito</title>

   <!-- Custom fonts for this template-->
   <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

   <!-- Custom styles for this template-->
   <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />

   <!-- Bootstrap core JavaScript-->
   <script src="../controllers/vendor/jquery/jquery.min.js"></script>
   <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

   <!-- Core plugin JavaScript-->
   <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

   <!-- Custom scripts for all pages-->
   <script src="../controllers/js/sb-admin.min.js"></script>

   <style>
       .report-card {
           transition: transform 0.3s ease, box-shadow 0.3s ease;
           border: none;
           border-radius: 12px;
           overflow: hidden;
       }
       .report-card:hover {
           transform: translateY(-6px);
           box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
       }
       .report-icon {
           font-size: 2.5rem;
           margin-bottom: 10px;
       }
       .card-header-custom {
           background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
           color: white;
           border-radius: 12px 12px 0 0 !important;
           padding: 20px;
       }
       .card-body-custom {
           padding: 25px;
           text-align: center;
       }
       .btn-report {
           border-radius: 25px;
           padding: 8px 25px;
           font-weight: 600;
           letter-spacing: 0.5px;
           transition: all 0.3s ease;
       }
       .btn-report:hover {
           transform: scale(1.05);
       }
       .report-title {
           color: #2c3e50;
           font-weight: 700;
           font-size: 1.1rem;
           margin-bottom: 15px;
       }
       .shadow-report {
           box-shadow: 0 2px 15px rgba(0,0,0,0.08) !important;
       }
       .container-custom {
           max-width: 1200px;
           margin: 0 auto;
           padding: 0 20px;
       }
       .page-header-custom {
           background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
           color: white;
           padding: 30px 0;
           margin-bottom: 40px;
           border-radius: 12px;
       }
   </style>
</head>

<body id="page-top">
   <?php echo "$nav"; ?>

   <div id="wrapper">
       <!-- Sidebar -->
       <?php echo "$menu"; ?>

       <div id="content-wrapper">
           <div class="container-fluid">
               <!-- Header personalizado -->
               <div class="page-header-custom mb-4">
                   <div class="container-custom">
                       <h2 class="text-center mb-0" style="font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                           <i class="fas fa-chart-bar mr-2"></i>Reportes de Concentrados El Gordito
                       </h2>
                   </div>
               </div>

               <div class="row justify-content-center">
                   <!-- Reporte 1 - Inventario General -->
                   <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                       <div class="card report-card shadow-report h-100">
                           <div class="card-body-custom">
                               <div class="report-icon text-primary">
                                   <i class="fas fa-boxes-stacked fa-3x"></i>
                               </div>
                               <h5 class="report-title">Inventario General</h5>
                               <p class="text-muted small mb-3">Vista completa del inventario</p>
                               <a href="reporteInventarioGeneral.php" class="btn btn-primary btn-report">
                                   <i class="fas fa-eye mr-1"></i> Ver Reporte
                               </a>
                           </div>
                       </div>
                   </div>

                   <!-- Reporte 2 - Inventario Escaso -->
                   <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                       <div class="card report-card shadow-report h-100">
                           <div class="card-body-custom">
                               <div class="report-icon text-warning">
                                   <i class="fas fa-exclamation-triangle fa-3x"></i>
                               </div>
                               <h5 class="report-title">Inventario Escaso</h5>
                               <p class="text-muted small mb-3">Productos con bajo stock</p>
                               <a href="reporteInventarioEscaso.php" class="btn btn-warning btn-report">
                                   <i class="fas fa-eye mr-1"></i> Ver Reporte
                               </a>
                           </div>
                       </div>
                   </div>

                   <!-- Reporte 3 - Mezclas -->
                   <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                       <div class="card report-card shadow-report h-100">
                           <div class="card-body-custom">
                               <div class="report-icon text-success">
                                   <i class="fas fa-blender fa-3x"></i>
                               </div>
                               <h5 class="report-title">Mezclas</h5>
                               <p class="text-muted small mb-3">Reporte de recetas</p>
                               <a href="reporteMezclas.php" class="btn btn-success btn-report">
                                   <i class="fas fa-eye mr-1"></i> Ver Reporte
                               </a>
                           </div>
                       </div>
                   </div>

                   <!-- Reporte 4 - Pedidos -->
                   <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                       <div class="card report-card shadow-report h-100">
                           <div class="card-body-custom">
                               <div class="report-icon text-danger">
                                   <i class="fas fa-receipt fa-3x"></i>
                               </div>
                               <h5 class="report-title">Pedidos</h5>
                               <p class="text-muted small mb-3">Historial de pedidos</p>
                               <a href="reportePedidos.php" class="btn btn-danger btn-report">
                                   <i class="fas fa-eye mr-1"></i> Ver Reporte
                               </a>
                           </div>
                       </div>
                   </div>

                    <!-- Reporte 5 - Pedidos Proveedor -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card report-card shadow-report h-100">
                            <div class="card-body-custom">
                                <div class="report-icon text-info">
                                    <i class="fas fa-truck fa-3x"></i>
                                </div>
                                <h5 class="report-title">Solicitud MP</h5>
                                <p class="text-muted small mb-3">Pedidos a proveedores</p>
                                <a href="reportePedidoProveedor.php" class="btn btn-info btn-report">
                                    <i class="fas fa-eye mr-1"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reporte 6 - Sesiones Activas -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card report-card shadow-report h-100">
                            <div class="card-body-custom">
                                <div class="report-icon text-success">
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                                <h5 class="report-title">Usuarios Activos</h5>
                                <p class="text-muted small mb-3">Sesiones actuales en el sistema</p>
                                <a href="reporteSesionesActivas.php" class="btn btn-success btn-report">
                                    <i class="fas fa-eye mr-1"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reporte 7 - Auditoria -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card report-card shadow-report h-100">
                            <div class="card-body-custom">
                                <div class="report-icon text-warning">
                                    <i class="fas fa-clipboard-list fa-3x"></i>
                                </div>
                                <h5 class="report-title">Auditoria</h5>
                                <p class="text-muted small mb-3">Historial de actividades</p>
                                <a href="reporteAuditoria.php" class="btn btn-warning btn-report">
                                    <i class="fas fa-eye mr-1"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reporte 8 - Modulos -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card report-card shadow-report h-100">
                            <div class="card-body-custom">
                                <div class="report-icon text-primary">
                                    <i class="fas fa-th-large fa-3x"></i>
                                </div>
                                <h5 class="report-title">Actividad Modulos</h5>
                                <p class="text-muted small mb-3">Uso de modulos del sistema</p>
                                <a href="reporteActividadModulos.php" class="btn btn-primary btn-report">
                                    <i class="fas fa-eye mr-1"></i> Ver Reporte
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <!-- /.container-fluid -->

           <!-- Footer -->
           <footer class="sticky-footer bg-white mt-auto">
               <div class="container my-auto py-3">
                   <div class="copyright text-center my-auto">
                       <span class="text-muted">Copyright © Concentrados El Gordito 2026</span>
                   </div>
               </div>
           </footer>
       </div>
       <!-- /.content-wrapper -->
   </div>
   <!-- /#wrapper -->

   <!-- Logout Modal-->
   <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                   <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">×</span>
                   </button>
               </div>
               <div class="modal-body">Selecciona "Cerrar sesión" para terminar tu sesión actual.</div>
               <div class="modal-footer">
                   <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                   <a class="btn btn-primary" href="login.html">Cerrar sesión</a>
               </div>
           </div>
        </div>
    </div>
    <!-- /#wrapper -->

  </body>
  </html>