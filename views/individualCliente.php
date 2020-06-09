<?php 
include 'configuracion/configuracionCliente.php';
?>
<div class="modal fade" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header btn-info">
                      <h5 class="modal-title" id="exampleModalLabel">Detalle Pedido</h5>
                       
                    </div>
                    <div class="modal-body">
                    <form method="GET" action="#">
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
                      <button class="btn btn-success" id="agregarP" name="agregarP">Agregar</button>
                      <button type="button" class="btn btn-info reset" data-dismiss="modal">Close</button>
                    </center>
                    </form>
                    </div>
                    
                  </div>
                </div>
              </div>

              <!--Modal Receta-->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header btn-info">
                      <h5 class="modal-title" id="exampleModalLabel">Detalle Pedido</h5>
                       
                    </div>
                    <div class="modal-body">
                    <form method="GET" action="#">
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
                      <button class="btn btn-success" id="agregarP" name="agregarP">Agregar</button>
                      <button type="button" class="btn btn-info reset" data-dismiss="modal">Close</button>
                    </center>
                    </form>
                    </div>
                    
                  </div>
                </div>
              </div>

<?php
echo $cli;
        if(isset($pedido)){
          echo "
              <a href='controllerIndividualC.php'><button class='btn btn-success'>Regresar</button></a><br><br>
                <div class='row'>
                  <div class='col-md-6 card'><hr>
                <button class='btn btn-success' data-toggle='modal' data-target='#exampleModal'>Agregar producto</button><br>";
                if(isset($detalleRes)){
                      echo "<table class='table table-hover table-bordered'>
                            <tr style='background-color:#1e7e34'><th>Id Detalle</th><th>Unidades</th><th>Nombre Producto</th></tr>";
                      foreach ($detalleRes as $Res) {
                        $idDetalle=$Res['idDetallePedido'];
                        $cantidad=$Res['cantidad'];
                        $idRes=$Res['idReceta'];
                        $NReceta=$Res['nombreReceta'];
                        echo "<tr><td>$idDetalle</td><td>$cantidad</td><td>$NReceta</td></tr>"; 

                      }
                      echo "</table>";

                    }else{echo "<h1>No hay Productos</h1> ";}
                

                      
            echo "</div>
                    <div class='col-md-6 card'><hr>
                    <button class='btn btn-secondary' data-toggle='modal' data-target='#exampleModals'>Agregar Receta</button>";

                    echo "</div>
                  </div>";
                         

          


      }else{
        echo '<div class="row">

            <div class="col-md-2">
            <form metod="post">
                <button class="btn btn-secondary" name="pedidos" id="pedidos">Agregar Pedido</button>
            </form>
            </div>
            <div class="col-md-2">
                <form method="post">
                    <button class="btn btn-danger">Pedido Terminado</button> </form>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">Imprimir</button>
            </div>
            <div class="col-md-3">

            </div>

        </div>

        </div>
        <hr>
      <div class="container-fluid">
        
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Mis Pedidos</div>
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
                        <td><form method='post'><button class='btn btn-info' name='detalle'>Detalle Pedido</button></form></td>
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

</body>

</html>
