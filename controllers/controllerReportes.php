<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">

   <title>Reportes</title>

<!-- Custom fonts for this template-->
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
    
    <!-- Bootstrap core JavaScript-->
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../controllers/js/sb-admin.min.js"></script>

   <style>
       .report-card {
           transition: transform 0.3s ease;
       }
       .report-card:hover {
           transform: scale(1.05);
       }
       .report-icon {
           font-size: 3rem;
           margin-bottom: 15px;
       }
   </style>
</head>

<body id="page-top">
   <?php echo "$nav"; ?>

   <div id="wrapper">

<!-- Sidebar -->
      <?php 
      include '../views/configuracion.php';
      echo "$menu";
      ?>

     <div id="content-wrapper">
       <div class="container-fluid">
         <h2 class="text-center mb-4">Reportes de Concentrados El Gordito</h2>
         
         <div class="row justify-content-center">
             <!-- Reporte 1 - Inventario General -->
             <div class="col-lg-2 col-md-3 col-sm-6 mb-4">
                 <div class="card report-card shadow-sm h-100">
                     <div class="card-body text-center">
                         <div class="report-icon text-primary">
                             <i class="fas fa-boxes-stacked"></i>
                         </div>
                         <h5 class="card-title">Inventario General</h5>
                         <a href="reporteInventarioGeneral.php" class="btn btn-primary btn-sm">Ver Reporte</a>
                     </div>
                 </div>
             </div>

             <!-- Reporte 2 - Inventario Escaso -->
             <div class="col-lg-2 col-md-3 col-sm-6 mb-4">
                 <div class="card report-card shadow-sm h-100">
                     <div class="card-body text-center">
                         <div class="report-icon text-warning">
                             <i class="fas fa-exclamation-triangle"></i>
                         </div>
                         <h5 class="card-title">Inventario Escaso</h5>
                         <a href="reporteInventarioEscaso.php" class="btn btn-warning btn-sm">Ver Reporte</a>
                     </div>
                 </div>
             </div>

             <!-- Reporte 3 - Mezclas -->
             <div class="col-lg-2 col-md-3 col-sm-6 mb-4">
                 <div class="card report-card shadow-sm h-100">
                     <div class="card-body text-center">
                         <div class="report-icon text-success">
                             <i class="fas fa-blender"></i>
                         </div>
                         <h5 class="card-title">Mezclas</h5>
                         <a href="reporteMezclas.php" class="btn btn-success btn-sm">Ver Reporte</a>
                     </div>
                 </div>
             </div>

             <!-- Reporte 4 - Pedidos -->
             <div class="col-lg-2 col-md-3 col-sm-6 mb-4">
                 <div class="card report-card shadow-sm h-100">
                     <div class="card-body text-center">
                         <div class="report-icon text-danger">
                             <i class="fas fa-receipt"></i>
                         </div>
                         <h5 class="card-title">Pedidos</h5>
                         <a href="reportePedidos.php" class="btn btn-danger btn-sm">Ver Reporte</a>
                     </div>
                 </div>
             </div>

             <!-- Reporte 5 - Pedidos Proveedor -->
             <div class="col-lg-2 col-md-3 col-sm-6 mb-4">
                 <div class="card report-card shadow-sm h-100">
                     <div class="card-body text-center">
                         <div class="report-icon text-info">
                             <i class="fas fa-truck"></i>
                         </div>
                         <h5 class="card-title">Solicitud MP</h5>
                         <a href="reportePedidoProveedor.php" class="btn btn-info btn-sm">Ver Reporte</a>
                     </div>
                 </div>
             </div>
         </div>

       </div>
       <!-- /.container-fluid -->

       <!-- Sticky Footer -->
       <footer class="sticky-footer">
         <div class="container my-auto">
           <div class="copyright text-center my-auto">
             <span>Copyright © Concentrados El Gordito 2026</span>
           </div>
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

   <!-- Logout Modal-->
   <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
           <button class="close" type="button" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
           </button>
         </div>
         <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
         <div class="modal-footer">
           <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
           <a class="btn btn-primary" href="login.html">Logout</a>
         </div>
       </div>
     </div>
   </div>

</body>
</html>