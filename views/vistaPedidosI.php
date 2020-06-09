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
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"></form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li>
        <button class="btn btn-warning"><?php echo $nombres; ?></button>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <form>
      <button class="btn btn-info" name="c" id="c">Cerrar Session</button>
        </form>
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
      <li class="nav-item dropdown-toggle">
        <a class="nav-link" href="controllerProduccionIn.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Produccion</span>
        </a>
      </li>
     
      
    </ul>

    <div id="content-wrapper">

        <div class="container-fluid">

         <hr>
            
                            <?php
                            if (isset($id)) {
                                echo "<div class='row'>
                                        <div class='col-md-3'>
                                        <form action='controllerProduccion.php' method='POST'>
                                        <input type='hidden' name='id' id='id' value=$id>
                                        <button class='btn btn-info' name='agregar' id='agregar'>agregar a Produccion</button></form></div>
                                        <div class='col-md-3'><a href='controllerPedidos.php' <button class='btn btn-primary' >Regresar</button></a></div>
                        
                                    </div>";
                                echo "<hr>";

                                echo "<table class='table table-striped table-dark table-hover' id='dataTable' width='100%' cellspacing='0'>
                                        <tr>'
                                        <th>ID Detalle</th>
                                        <th>Unidades</th>
                                        <th>Nombre Producto</th>
                                        </tr>";
                                foreach ($detalle as $key) {
                                    $ped=$key['idDetallePedido'];
                                    $cantidad=$key['cantidad'];
                                    $nRes=$key['nombreReceta'];

                                    echo "<tr>
                                          <td>$ped</td>
                                          <td>$cantidad</td>
                                          <td>$nRes</td>      
                                          </tr>";
                                }
                                echo "</table>";
                                echo "<hr>";
                                echo "<hr>";
                                echo "<table class='table table-bordered table-dark table-hover' id='dataTable' width='100%' cellspacing='0'>
                                        <tr>
                                        <th>ID Receta</th>
                                        <th>Materia Prima</th>
                                        <th>cantidad</th>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        </tr>
                                        ";
                                        
                                foreach ($receta as $res) {
                                    $idR=$res['idDetalleReceta'];
                                    $Mp=$res['NombreMP'];
                                    $cantida=$res['cantidaSa'];
                                    $fecha=$res['fechaSa'];
                                    $producto=$res['nombreReceta'];
                                    $id=$res['idPedido'];
                                    echo "<tr>
                                          <td>$idR</td>  
                                          <td>$Mp</td>
                                          <td>$cantida</td>
                                          <td>$fecha</td>
                                          <td>$producto</td>
                                          </tr>";
                                }echo "</table>";
                            
                        }else{
                        $tabla="<div class='card mb-3'>
                                    <div class='card-header'>
                                        <i class='fas fa-table'></i>
                                       Pedidos</div>
                                    <div class='card-body'>
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
                                                <thead>
                                                <tr>
                                                    <th>id Pedido</th>
                                                    <th>fecha Pedido</th>
                                                    <th>Cliente</th>
                                                    <th>Apellidos</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>id Pedido</th>
                                                    <th>fecha Pedido</th>
                                                    <th>Cliente</th>
                                                    <th>Apellidos</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>";
                                                echo $tabla;
                            foreach ($datos as $key){
                                $id=$key['idPedido'];
                                $fecha=$key['fechaPedido'];
                                $nombre=$key['NombreCliente'];
                                $apellidos=$key['ApellidosCliente'];
                                $estado=$key['nombreEstado'];
                                echo "<tr>
                                      <td>$id</td>
                                      <td>$fecha</td>
                                      <td>$nombre</td>
                                      <td>$apellidos</td>
                                      <td>$estado</td>
                                      <td><form method='POST'><input type='hidden' id='id' name='id' value='$id'><button class='btn btn-primary' name='detalle'id='detalle'>Detalle Pedido</button></form></td>
                                      </tr>";
                                      
                            }
                        }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

         
        </div>
        <!-- /.container-fluid -->

       
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

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

