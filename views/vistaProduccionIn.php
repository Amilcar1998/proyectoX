<?php include 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>🏭 Producción - Concentrados El Gordito</title>

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
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Producción
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>ID Producción</th>
                    <th>Fecha Producción</th>
                    <th>Estado Pedido</th>
                    <th>Id Pedido</th>
                    <th>Fecha Pedido</th>
                    <th>Nombre Cliente</th>
                    <th>Empleado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                   foreach ($data as $key) {
                    $idProduccion = $key['idProduccion'];
                    $fechaProduccion = $key['fechaP'];
                    $estadoProduccion = $key['estadoP'];
                    $idPedido = $key['idPedido'];
                    $fechaPedido = $key['fechaPedido'];
                    $nombreCliente = $key['NombreCliente'];
                    $emp = $key['nombreEmp'];
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
          <div class="card-footer small text-muted">Actualizado el <?php echo date('d/m/Y \a  \l\a\s H:i'); ?></div>
        </div>
      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer bg-dark mt-auto">
        <div class="container my-auto py-3">
          <div class="copyright text-center my-auto">
            <span class="text-white">Copyright &copy; Concentrados El Gordito 2026</span>
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

  <!-- Bootstrap core JavaScript-->
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

</body>

</html>
<?php 
if(isset($msj,$icon)){
  echo "<script>Swal.fire('$msj','','$icon');</script>";
}
?>
