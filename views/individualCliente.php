<?php 
include 'configuracion/configuracionCliente.php';
?>



<?php
echo $cli;


        if(isset($pedido)){
          echo "<button class='btn btn-success'>Agregar producto</button>
                <button class='btn btn-secondary'>Agregar Receta</button>
                <hr>
                <div class='row'>
                  <div class='col-md-6'>
                  <h4>Productos</h4>

                  </div>
                  <div class='col-md-6'>
                  <h4>Ingredientes</h4>
                  </div>
                </div>
                ";

          


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
                }

                   ?>
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
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
