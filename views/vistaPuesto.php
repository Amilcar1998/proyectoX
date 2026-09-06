<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gestión de Puestos - Concentrados El Gordito">
    <meta name="author" content="">

    <title>💼 Puestos - Concentrados El Gordito</title>

    <!-- Custom fonts for this template-->
    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
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
         
         <ol class="breadcrumb">
           <li class="breadcrumb-item">
             <a href="controllerDashboard.php">Dashboard</a>
           </li>
           <li class="breadcrumb-item active">Puestos</li>
         </ol>

         <div class="d-flex justify-content-between align-items-center mb-3">
           <h2 class="mb-0"><i class="fas fa-briefcase text-primary mr-2"></i>Gestión de Puestos</h2>
           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPuesto" onclick="limpiarFormulario()">
             <i class="fas fa-plus mr-1"></i> Agregar Puesto
           </button>
         </div>
         
         <!-- Modal -->
         <div class="modal fade" id="modalPuesto" tabindex="-1" role="dialog" aria-labelledby="modalPuestoLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="modalPuestoLabel"><i class="fas fa-briefcase mr-2"></i>Registro de Puesto</h5>
                 <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <form method="POST" name="formulario" id="formularioPuesto">
                 <div class="modal-body">
                   <div class="form-group">
                     <label for="txtId">ID Puesto</label>
                     <input type="text" name="txtId" id="txtId" value="" class="form-control" readonly placeholder="Generado automáticamente">
                   </div>
                   <div class="form-group">
                     <label for="txtNombre">Nombre del Puesto</label>
                     <input type="text" name="txtNombre" id="txtNombre" value="" class="form-control" placeholder="Ingrese nombre del puesto" required>
                   </div>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                   <button type="submit" value="guardar" name="btnGuardar" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Guardar</button>
                   <button type="submit" value="modificar" name="btnModificar" class="btn btn-warning"><i class="fas fa-edit mr-1"></i>Modificar</button>
                   <button type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este puesto?');"><i class="fas fa-trash-alt mr-1"></i>Eliminar</button>
                 </div>
               </form>
             </div>
           </div>
         </div>

         <!-- Tabla de Puestos -->
         <div class="card mb-3">
           <div class="card-header">
             <i class="fas fa-table mr-1"></i> Listado de Puestos Registrados
           </div>
           <div class="card-body">
             <div class="table-responsive">
               <table class="table table-bordered datatable" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                     <th>ID Puesto</th>
                     <th>Nombre de Puesto</th>
                     <th>Acciones</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if (!empty($tabla)): ?>
                     <?php foreach ($tabla as $fila): ?>
                       <tr>
                         <td><?php echo htmlspecialchars($fila['idPuesto']); ?></td>
                         <td><?php echo htmlspecialchars($fila['nombrePuesto']); ?></td>
                         <td>
                           <button type="button" class="btn btn-info btn-sm cargar" data-toggle="modal" data-target="#modalPuesto" onclick="cargar('<?php echo htmlspecialchars($fila['idPuesto'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fila['nombrePuesto'], ENT_QUOTES); ?>')">
                             <i class="fas fa-edit mr-1"></i> Cargar
                           </button>
                         </td>
                       </tr>
                     <?php endforeach; ?>
                   <?php endif; ?>
                 </tbody>
               </table>
             </div>
           </div>
           <div class="card-footer small text-muted">Actualizado el <?php echo date('d/m/Y \a \l\a\s H:i'); ?></div>
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

   <!-- Logout Modal-->
   <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header bg-danger text-white">
           <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-sign-out-alt mr-2"></i>¿Desea cerrar sesión?</h5>
           <button class="close text-white" type="button" data-dismiss="modal" aria-label="Cerrar">
             <span aria-hidden="true">×</span>
           </button>
         </div>
         <div class="modal-body">Selecciona "Cerrar sesión" si estás listo para finalizar tu sesión actual.</div>
         <div class="modal-footer">
           <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
           <a class="btn btn-danger" href="sesiones.php?c=c">Cerrar sesión</a>
         </div>
       </div>
     </div>
   </div>

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
   <script src="../controllers/js/translations.js"></script>
   <script src="../controllers/js/demo/datatables-demo.js"></script>

   <script>
     function cargar(id, nombre) {
       $('#txtId').val(id);
       $('#txtNombre').val(nombre);
     }

     function limpiarFormulario() {
       $('#txtId').val('');
       $('#txtNombre').val('');
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