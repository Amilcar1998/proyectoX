<?php 

include 'configuracion.php';

 ?>
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
  <link href="vendor/sb-admin.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <?php 
      echo "$nav";


     ?>

  <div id="wrapper">

    <?php 
      echo "$menu";


     ?>
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
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>



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
  <script src="vendor/sweetalert2.all.min.js"></script>

</body>

</html>
