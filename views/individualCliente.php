<?php
include 'configuracion/configuracionCliente.php';
?>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header btn-info">
                    <h5 class="modal-title" id="exampleModalLabel">Detalle Pedido</h5>

                </div>
                <div class="modal-body">
                    <form method="POST" action="#" id="miForm">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Id </label>
                                    <input type="text" class="form-control" name="txtUsuario" id="txtUsuario" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Cantidad Unidades</label>
                                    <input type="text" class="form-control" name="txtcantidad" id="txtcantidad">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Rol</label>
                                    <select class="form-control" name="producto" id="producto" >
                                        <?php foreach ($receta as $res) {
                                       echo "<option value='".$res['idReceta']."'>".$res['nombreReceta']."</option>";
                                        } ?>



                                    </select>

                                </div>
                            </div>
                        </div>
                        <hr>
                        <center>
                            <?php
                            if(isset($detalleRes)){
                                echo '<button class="btn btn-warning" id="agregar" name="agregar">Agregar Nuevo</button>';
                            }else{ echo '<button class="btn btn-success" id="agregarP" name="agregarP">Agregar</button>';}
                            ?>

                            <button type="button" class="btn btn-info reset" data-dismiss="modal">Close</button>
                        </center>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!--Modal Receta-->

<?php

echo $cli;
if(isset($pedido)){
    echo "<a href='controllerIndividualC.php'><button class='btn btn-success'>Regresar</button></a>
      <button class='btn btn-info' data-toggle='modal' data-target='#exampleModal'>Agregar producto</button><br><br>";
    echo "<div class='row'>
        <div class='col-md-8 card'>";
    if(isset($detalleRes)){

        echo "<table class='table table-hover table-bordered'>
         <tr style='background-color:#1e7e34'>
         <th>Id Detalle</th>
         <th>Unidades</th>
         <th>Nombre Producto</th>
         <th>total</th>
         <th>Accion</th>
         </tr>";

        foreach ($detalleRes as $Res) {
            $idDetalle=$Res['idDetallePedido'];
            $cantidad=$Res['cantidad'];
            $idRes=$Res['idReceta'];
            $NReceta=$Res['nombreReceta'];
            $precio=$Res['total_producto'];
            echo "<tr><td>$idDetalle</td>
                      <td>$cantidad</td>
                      <td>$NReceta</td>
                      <td>$precio</td>
                      <td><form method='post'>
                      <input type='hidden' name='idRes' id='idRes' value='$idRes'>
                      <button class='btn btn-success' name='det' id='det' >Ver Receta</button>
                      <input type='hidden' name='id' id='id' value='$idDetalle'>
                      <button class='btn btn-danger' name='eliminar' id='eliminar'>Eliminar</button>
                      </form></td></tr>";

        }
        echo "</table>
               </div>";
        if(isset($data)){

            echo "<div class='col-md-4'>
                <table class='table table-hover table-dark'>
                <tr><td>KiloGramos</td><td>Materia Prima</td></tr>";
                   foreach ($data as $p){
                       $can=$p['cantidaSa'];
                       $nombre=$p['NombreMP'];
                        echo "<tr>
                                    <td>$can</td>
                                    <td>$nombre</td>
                                </tr>";
                   }


            echo "</table>
              </div>
              </div>";
        }

                echo "</div>
             </div>";



    }else{echo "<h1>No hay nada que Mostrar</h1>";}


}else{
    echo '
      <div class="container-fluid">
        
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
          <div class="row">
             <form metod="post">
               <button class="btn btn-success" name="pedidos" id="pedidos">Agregar Pedido</button></form>
          </div>
        </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>ID Pedido</th>
                    <th>Fecha Pedido</th>
                    <th>Estado</th>
                    <th>ID Cliente</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>ID Pedido</th>
                    <th>Fecha Pedido</th>
                    <th>Estado</th>
                    <th>ID Cliente</th>
                    <th>Acciones</th>
                  </tr>
                </tfoot>
                <tbody>';
    foreach ($dataPedido as $ask) {
        $idPedido=$ask['idPedido'];
        $fecha=$ask['fechaPedido'];
        $estado=$ask['nombreEstado'];
        $clienteID=$ask['idCliente'];
        echo "<tr>
                        <td>$idPedido</td>
                        <td>$fecha</td>
                        <td>$estado</td>
                        <td>$clienteID</td>
                        <td><form method='post'><input type='hidden' id='data' name='data' value='$idPedido'>
                        <button class='btn btn-danger' name='EliminarP' id='EliminarP'>Eliminar</button> 
                        <button class='btn btn-success' name='detalle' id='detalle'>Detalle Pedido</button></form></td>
                         </tr>";
    }
    echo '</tbody>
                      </table>
                    </div>
                  </div>
                </div>';
}

?>


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

    <!-- Demo scripts for this page-->
    <script src="../controllers/js/demo/datatables-demo.js"></script>
    <script src="../controllers/vendor/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="../controllers/Recursos/validaciones.js"></script>

    </body>

    </html>
<?php
if(isset($msj,$icon)){
    echo "<script>Swal.fire('$msj','','$icon');</script>";
}

?>