<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>🧾 Factura</title>

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

     <!-- Demo scripts for this page-->
     <script src="js/translations.js"></script>
     <script src="js/demo/datatables-demo.js"></script>

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
         
          <h2 class="text-center mb-4">Factura</h2>

          <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#formModal">
            <i class="fas fa-plus"></i> Nueva Factura
         </button>
         
         <!-- Modal -->
         <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header bg-info text-white">
                 <h5 class="modal-title" id="formModalLabel">Registro de Factura</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
                 <form method="POST" name="formulario">
                   <div class="form-group">
                     <label>ID Factura</label>
                     <input type="text" name="txtIdFactura" value="" class="form-control" readonly>
                   </div>
                   <div class="form-group">
                     <label>Número Factura</label>
                     <input type="text" name="txtNumeroFac" value="" class="form-control" placeholder="Número Factura">
                   </div>
                   <div class="form-group">
                     <label>Monto</label>
                     <input type="text" name="txtMonto" value="" class="form-control" placeholder="Monto">
                   </div>
                   <div class="form-group">
                     <label>Fecha</label>
                     <input type="text" name="txtFecha" value="" class="form-control" placeholder="Fecha">
                   </div>
                    <div class="form-group">
                      <label>Proveedor</label>
                      <select name="txtIdProveedor" class="form-control">
                        <option value="">Seleccione proveedor</option>
                        <?php foreach ($proveedores as $p) {
                          echo "<option value='".$p["idProveedor"]."'>".$p["nombreProveedor"]."</option>";
                        } ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Empleado</label>
                      <select name="txtIdEmpleado" class="form-control">
                        <option value="">Seleccione empleado</option>
                        <?php foreach ($empleados as $e) {
                          echo "<option value='".$e["idEmpleado"]."'>".$e["nombreEmp"]." ".$e["apellido"]."</option>";
                        } ?>
                      </select>
                    </div>
                   
                   <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                     <button type="submit" value="guardar" name="btnGuardar" class="btn btn-primary">Guardar</button>
                     <button type="submit" value="modificar" name="btnModificar" class="btn btn-warning">Modificar</button>
                     <button type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger">Eliminar</button>
                   </form>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <script>
      $(document).ready(function() {
        $('#dataTable').DataTable({
          language: {
            url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
          },
          paging: false,
          info: false
        });
      });

      function cargar(id, numero, monto, fecha, idProv, idEmp) {
        document.formulario.txtIdFactura.value = id;
        document.formulario.txtNumeroFac.value = numero;
        document.formulario.txtMonto.value = monto;
        document.formulario.txtFecha.value = fecha;
        document.formulario.txtIdProveedor.value = idProv;
        document.formulario.txtIdEmpleado.value = idEmp;
      }
    </script>

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