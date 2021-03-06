<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Produccion</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="vendor/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="index.html">Concentrados El Gordito</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow mx-1">
        <button class="btn btn-primary"><?php echo $nombres; ?></button>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
       
      </li>
      <li class="nav-item dropdown no-arrow">
       <form><button class="btn btn-info" name="c" id="c">Cerrar Session</button></form>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="controllerPedidosIn.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Pedidos</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="controllerProduccionIn.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Produccion</span></a>
      </li>

    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Produccion</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>ID Produccion</th>
                    <th>Fecha Produccion</th>
                    <th>estado Pedido</th>
                    <th>Id Pedido</th>
                    <th>FechaPedido</th>
                    <th>Nombre Cliente</th>
                    <th>Empleado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>ID Produccion</th>
                    <th>Fecha Produccion</th>
                    <th>estado Pedido</th>
                    <th>Id Pedido</th>
                    <th>FechaPedido</th>
                    <th>Nombre Cliente</th>
                    <th>Empleado</th>
                    <th>Acciones</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php

                   foreach ($data as $key) {
                    $idProduccion =$key['idProduccion'];
                    $fechaProduccion=$key['fechaP'];
                    $estadoProduccion=$key['estadoP'];
                    $idPedido=$key['idPedido'];
                    $fechaPedido=$key['fechaPedido'];
                    $nombreCliente=$key['NombreCliente'];
                    $emp=$key['nombreEmp'];
                    echo "<tr>
                            <td>$idProduccion</td>
                            <td>$fechaProduccion</td>
                            <td>$estadoProduccion</td>
                            <td>$idPedido</td>
                            <td>$fechaPedido</td>
                            <td>$nombreCliente</td>
                            <td>$emp</td>
                            <td><form method='POST'><input type='hidden' name='produccionID' id='produccionID' value='$idProduccion'><button class='btn btn-info' id='eliminar' name='eliminar'>Cargar</button></form></td>
                        </tr>";
                   }




                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>
      </div>

        
  
       
      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © Your Website 2019</span>
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

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
<?php 
if(isset($msj,$icon)){
  echo "<script>Swal.fire('$msj','','$icon');</script>";
}

 ?>
