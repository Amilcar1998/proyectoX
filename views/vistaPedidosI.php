<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>📦 Pedidos - Concentrados El Gordito</title>

  <!-- Custom fonts for this template-->
  <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../controllers/vendor/sb-admin.css" rel="stylesheet">
  <style>
    html, body { height: 100%; }
    #wrapper { min-height: 100vh; }
    #content-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    footer.sticky-footer {
      position: relative !important;
      margin-top: auto;
      width: 100% !important;
      height: auto !important;
      padding: 1rem 0;
    }
    .table-responsive {
      max-height: calc(100vh - 280px);
      overflow-y: auto;
    }
  </style>

</head>

<body id="page-top">

  <?php echo $nav; ?>

  <div id="wrapper">

    <?php echo $menu; ?>

    <div id="content-wrapper">

        <div class="container-fluid">

         <hr>
            
                            <?php
                            if (isset($id) && (int)$id > 0) {
                                echo "<div class='row'>";
                                if (!empty($detalle) && count($detalle) > 0) {
                                    echo "<div class='col-md-3'>
                                            <form action='controllerProduccionIn.php' method='POST'>
                                            <input type='hidden' name='id' id='id' value='$id'>
                                            <button class='btn btn-info' name='agregar' id='agregar'><i class='fas fa-industry'></i> Agregar a Producción</button></form></div>";
                                }
                                echo "<div class='col-md-3'><a href='controllerPedidosIn.php' class='btn btn-primary'><i class='fas fa-arrow-left'></i> Regresar</a></div>
                                    </div>";
                                echo "<hr>";

                                echo "<h5 class='text-white mb-3'><i class='fas fa-boxes'></i> Productos del Pedido #$id</h5>";
                                echo "<div class='table-responsive'><table class='table table-striped table-dark table-hover datatable' width='100%' cellspacing='0'>
                                        <thead>
                                        <tr>
                                        <th>ID Detalle</th>
                                        <th>Unidades</th>
                                        <th>Nombre Producto</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
                                if (!empty($detalle) && is_array($detalle)) {
                                    foreach ($detalle as $key) {
                                        $ped = htmlspecialchars((string)($key['idDetallePedido'] ?? ''));
                                        $cantidad = htmlspecialchars((string)($key['cantidad'] ?? ''));
                                        $nRes = htmlspecialchars((string)($key['nombreReceta'] ?? ''));

                                        echo "<tr>
                                              <td>$ped</td>
                                              <td>$cantidad</td>
                                              <td>$nRes</td>      
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No se encontraron detalles para este pedido.</td></tr>";
                                }
                                echo "</tbody></table></div>";
                                echo "<hr>";

                                echo "<h5 class='text-white mb-3'><i class='fas fa-flask'></i> Desglose de Materia Prima por Receta</h5>";
                                echo "<div class='table-responsive'><table class='table table-bordered table-dark table-hover datatable' width='100%' cellspacing='0'>
                                        <thead>
                                        <tr>
                                        <th>ID Receta</th>
                                        <th>Materia Prima</th>
                                        <th>Cantidad</th>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
                                if (!empty($receta) && is_array($receta)) {
                                    foreach ($receta as $res) {
                                        $idR = htmlspecialchars((string)($res['idDetalleReceta'] ?? ''));
                                        $Mp = htmlspecialchars((string)($res['NombreMP'] ?? ''));
                                        $cantida = htmlspecialchars((string)($res['cantidaSa'] ?? ''));
                                        $fecha = htmlspecialchars((string)($res['fechaSa'] ?? ''));
                                        $producto = htmlspecialchars((string)($res['nombreReceta'] ?? ''));
                                        echo "<tr>
                                              <td>$idR</td>  
                                              <td>$Mp</td>
                                              <td>$cantida</td>
                                              <td>$fecha</td>
                                              <td>$producto</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No hay detalle de materias primas para este pedido.</td></tr>";
                                }
                                echo "</tbody></table></div>";
                            
                        }else{
                        $tabla="<div class='card mb-3'>
                                    <div class='card-header'>
                                        <i class='fas fa-table'></i>
                                       Pedidos</div>
                                    <div class='card-body'>
                                        <div class='table-responsive'>
                                            <table class='table table-bordered datatable' width='100%' cellspacing='0'>
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

<script src="../controllers/vendor/jquery/jquery.min.js"></script>
<script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
<script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin.min.js"></script>

<!-- Demo scripts for this page-->
<script src="js/translations.js"></script>
<script src="js/demo/datatables-demo.js"></script>

<!-- Footer -->
<footer class="sticky-footer bg-dark mt-auto">
  <div class="container my-auto py-3">
    <div class="copyright text-center my-auto">
      <span class="text-white">Copyright &copy; Concentrados El Gordito 2026</span>
    </div>
  </div>
</footer>

</body>

</html>

<?php 
if(isset($msj,$icon)){
  echo "<script>Swal.fire('$msj','','$icon');</script>";
}

 ?>
