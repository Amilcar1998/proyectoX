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

    <title>Pedidos</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="vendor/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">
 <?php 
      echo "$nav";


     ?>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->

    <?php 
      echo "$menu";


     ?>

    <div id="content-wrapper">

        <div class="container-fluid">

         <hr>
            
                            <?php
                            if (isset($id)) {
                                $RecetaPollo=0;
                                $polloAdulto=0;
                                foreach ($detalle as $key) {
                                    $ped = $key['idDetallePedido'];
                                    $cantidad = $key['cantidad'];
                                    $nRes = $key['nombreReceta'];
                                    $idPedido = $key['IdPedido'];
                                    $idReceta = $key['idReceta'];
                                    if($idReceta==1) {
                                        $RecetaPollo++;
                                    }
                                    if($idReceta==2){
                                        $polloAdulto++;
                                    }
                                    var_dump($RecetaPollo);
                                    var_dump($polloAdulto);
                                }
                                echo "<div class='row'>
                                        <div class='col-md-3'>
                                        <form action='controllerProduccion.php' method='POST'>
                                        <input type='hidden' name='cantidadPollo' id='cantidadPollo' value=$RecetaPollo>
                                        <input type='hidden' name='polloAdulto' id='polloAdulto' value=$polloAdulto>
                                        <input type='hidden' name='id' id='id' value=$id>
                                        <button class='btn btn-info' name='agregar' id='agregar'>agregar a Produccion</button></form></div>
                                        <div class='col-md-3'><a href='controllerPedidos.php' <button class='btn btn-primary' >Regresar</button></a></div>
                        
                                    </div>";
                                echo "<hr>
                                       <div class='row'>
                                       <div class='col-md-6'>
                                       <table class='table table-striped table-dark table-hover' id='dataTable' width='100%' cellspacing='0'>
                                        <tr>'
                                        <th>ID</th>
                                        <th>Unidades</th>
                                        <th>Producto</th>
                                        <th>Accion</th>
                                        </tr>
                                        </div>
                                       </div> 
                                       ";

                                foreach ($detalle as $key) {
                                    $ped=$key['idDetallePedido'];
                                    $cantidad=$key['cantidad'];
                                    $nRes=$key['nombreReceta'];
                                    $idPedido=$key['IdPedido'];
                                    $idReceta=$key['idReceta'];

                                    echo "<tr>
                                          <td>$ped</td>
                                          <td>$cantidad</td>
                                          <td>$nRes</td>  
                                          <td><form method='POST'>
                                           <input type='hidden' name='contarPedido' id='contarPedido' value='$RecetaPollo' >
                                          <input type='hidden' name='idDetalle' id='idDetalle' value='$idPedido' >
                                          <input type='hidden' name='id' id='id' value='$idReceta'> 
                                          <button class='btn btn-primary' name='receta' id='receta'>Ver</button>
                                           </form></td>    
                                          </tr>";
                                }
                                echo "</table>
                                    </div>";
                                echo "<hr>";
                                if (isset($receta)){
                                echo "<div class='col-md-6'>
                                        <table class='table table-bordered table-dark table-hover' id='dataTable' width='100%' cellspacing='0'>
                                        <tr>
                                        <th>ID Receta</th>
                                        <th>Materia Prima</th>
                                        <th>cantidad</th>
                                        <th>Producto</th>
                                        </tr>
                                        ";

                                foreach ($receta as $res) {

                                    $idR=$res['idDetalleReceta'];
                                    $Mp=$res['NombreMP'];
                                    $cantida=$res['cantidaSa'];
                                    $producto=$res['NombreMP'];

                                    echo "<tr>
                                          <td>$idR</td>  
                                          <td>$Mp</td>
                                          <td>$cantida</td>
                                          <td>$producto</td>
                                          </tr>";
                                }echo "</table></div>";}
                            
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
                                      <td><form method='POST'><input type='hidden' id='id' name='id' value='$id'>
                                      <button class='btn btn-primary' name='detalle'id='detalle'>Detalle Pedido</button></form></td>
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

<?php 
if(isset($msj,$icon)){
  echo "<script>Swal.fire('$msj','','$icon');</script>";
}

 ?>
