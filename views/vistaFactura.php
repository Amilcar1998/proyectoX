<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gestión de Facturas - Concentrados El Gordito">
    <meta name="author" content="">

    <title>🧾 Facturas - Concentrados El Gordito</title>

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
           <li class="breadcrumb-item active">Facturas</li>
         </ol>

         <div class="d-flex justify-content-between align-items-center mb-3">
           <h2 class="mb-0"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Gestión de Facturas</h2>
           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFactura" onclick="limpiarFormulario()">
             <i class="fas fa-plus mr-1"></i> Nueva Factura
           </button>
         </div>
         
         <!-- Modal -->
         <div class="modal fade" id="modalFactura" tabindex="-1" role="dialog" aria-labelledby="modalFacturaLabel" aria-hidden="true">
           <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
               <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="modalFacturaLabel"><i class="fas fa-receipt mr-2"></i>Registro de Factura</h5>
                 <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <form method="POST" name="formulario" id="formularioFactura">
                 <div class="modal-body">
                   <div class="form-row">
                     <div class="form-group col-md-6">
                       <label for="txtIdFactura">ID Factura</label>
                       <input type="text" name="txtIdFactura" id="txtIdFactura" value="" class="form-control" readonly placeholder="Generado automáticamente">
                     </div>
                     <div class="form-group col-md-6">
                       <label for="txtNumeroFac">Número de Factura</label>
                       <input type="text" name="txtNumeroFac" id="txtNumeroFac" value="" class="form-control" placeholder="Ej: FAC-00123" required>
                     </div>
                   </div>

                   <div class="form-row">
                     <div class="form-group col-md-6">
                       <label for="txtMonto">Monto ($)</label>
                       <input type="number" step="0.01" name="txtMonto" id="txtMonto" value="" class="form-control" placeholder="0.00" required>
                     </div>
                     <div class="form-group col-md-6">
                       <label for="txtFecha">Fecha</label>
                       <input type="date" name="txtFecha" id="txtFecha" value="" class="form-control" required>
                     </div>
                   </div>

                   <div class="form-row">
                     <div class="form-group col-md-6">
                       <label for="txtIdProveedor">Proveedor</label>
                       <select name="txtIdProveedor" id="txtIdProveedor" class="form-control" required>
                         <option value="">Seleccione proveedor...</option>
                         <?php if (!empty($proveedores)): ?>
                           <?php foreach ($proveedores as $p): ?>
                             <option value="<?php echo htmlspecialchars($p['idProveedor']); ?>">
                               <?php echo htmlspecialchars($p['nombreProveedor']); ?>
                             </option>
                           <?php endforeach; ?>
                         <?php endif; ?>
                       </select>
                     </div>
                     <div class="form-group col-md-6">
                       <label for="txtIdEmpleado">Empleado Responsable</label>
                       <select name="txtIdEmpleado" id="txtIdEmpleado" class="form-control" required>
                         <option value="">Seleccione empleado...</option>
                         <?php if (!empty($empleados)): ?>
                           <?php foreach ($empleados as $e): ?>
                             <option value="<?php echo htmlspecialchars($e['idEmpleado']); ?>">
                               <?php echo htmlspecialchars($e['nombreEmp'] . ' ' . $e['apellido']); ?>
                             </option>
                           <?php endforeach; ?>
                         <?php endif; ?>
                       </select>
                     </div>
                   </div>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                   <button type="submit" value="guardar" name="btnGuardar" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Guardar</button>
                   <button type="submit" value="modificar" name="btnModificar" class="btn btn-warning"><i class="fas fa-edit mr-1"></i>Modificar</button>
                   <button type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta factura?');"><i class="fas fa-trash-alt mr-1"></i>Eliminar</button>
                 </div>
               </form>
             </div>
           </div>
         </div>

         <!-- Tabla de Facturas -->
         <div class="card mb-3">
           <div class="card-header">
             <i class="fas fa-table mr-1"></i> Listado de Facturas Registradas
           </div>
           <div class="card-body">
             <div class="table-responsive">
               <table class="table table-bordered datatable" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                     <th>ID</th>
                     <th>N° Factura</th>
                     <th>Monto</th>
                     <th>Fecha</th>
                     <th>Proveedor</th>
                     <th>Empleado</th>
                     <th>Acciones</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if (!empty($tabla)): ?>
                     <?php foreach ($tabla as $fila): ?>
                       <tr>
                         <td><?php echo htmlspecialchars($fila['idFacturaMP']); ?></td>
                         <td><?php echo htmlspecialchars($fila['numeroFac']); ?></td>
                         <td>$<?php echo number_format((float)$fila['Monto'], 2); ?></td>
                         <td><?php echo htmlspecialchars($fila['Fecha']); ?></td>
                         <td><?php echo htmlspecialchars($fila['nombreProveedor']); ?></td>
                         <td><?php echo htmlspecialchars($fila['empleado']); ?></td>
                         <td>
                           <button type="button" class="btn btn-info btn-sm cargar" data-toggle="modal" data-target="#modalFactura" onclick="cargar('<?php echo htmlspecialchars($fila['idFacturaMP'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fila['numeroFac'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fila['Monto'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fila['Fecha'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fila['idProveedor'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($fila['idEmpleado'], ENT_QUOTES); ?>')">
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
     function cargar(id, numero, monto, fecha, idProv, idEmp) {
       $('#txtIdFactura').val(id);
       $('#txtNumeroFac').val(numero);
       $('#txtMonto').val(monto);
       $('#txtFecha').val(fecha);
       $('#txtIdProveedor').val(idProv);
       $('#txtIdEmpleado').val(idEmp);
     }

     function limpiarFormulario() {
       $('#txtIdFactura').val('');
       $('#txtNumeroFac').val('');
       $('#txtMonto').val('');
       $('#txtFecha').val('');
       $('#txtIdProveedor').val('');
       $('#txtIdEmpleado').val('');
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