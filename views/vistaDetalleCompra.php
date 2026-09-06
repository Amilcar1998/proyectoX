<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Detalle de Compra - Concentrados El Gordito">
    <meta name="author" content="">

    <title>🛍️ Detalle de Compra - Concentrados El Gordito</title>

    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
</head>

<body id="page-top">
   <?php echo "$nav"; ?>
   <div id="wrapper">
     <?php echo "$menu"; ?>
     <div id="content-wrapper">
       <div class="container-fluid">
         
         <ol class="breadcrumb">
           <li class="breadcrumb-item">
             <a href="controllerDashboard.php">Dashboard</a>
           </li>
           <li class="breadcrumb-item active">Detalle de Compra</li>
         </ol>

         <div class="d-flex justify-content-between align-items-center mb-3">
           <h2 class="mb-0"><i class="fas fa-shopping-basket text-primary mr-2"></i>Detalle de Compra</h2>
           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalDetalleCompra" onclick="limpiarFormulario()">
             <i class="fas fa-plus mr-1"></i> Agregar Detalle
           </button>
         </div>
         
         <!-- Modal Detalle Compra -->
         <div class="modal fade" id="modalDetalleCompra" tabindex="-1" role="dialog" aria-labelledby="modalDetalleCompraLabel" aria-hidden="true">
           <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
               <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="modalDetalleCompraLabel"><i class="fas fa-shopping-cart mr-2"></i>Registro de Detalle de Compra</h5>
                 <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <form method="POST" name="formulario" id="formularioDetalle">
                 <div class="modal-body">
                   <div class="form-row">
                     <div class="form-group col-md-6">
                       <label for="txtIdDetalle">ID Detalle</label>
                       <input type="text" name="txtIdDetalle" id="txtIdDetalle" value="" class="form-control" readonly placeholder="Generado automáticamente">
                     </div>
                     <div class="form-group col-md-6">
                       <label for="txtIdMP">Materia Prima</label>
                       <select name="txtIdMP" id="txtIdMP" class="form-control" required>
                         <option value="">Seleccione materia prima...</option>
                         <?php if (!empty($materiasPrimas)): ?>
                           <?php foreach ($materiasPrimas as $mp): ?>
                             <option value="<?php echo htmlspecialchars($mp['idMateriaPrima']); ?>">
                               <?php echo htmlspecialchars($mp['NombreMP']); ?>
                             </option>
                           <?php endforeach; ?>
                         <?php endif; ?>
                       </select>
                     </div>
                   </div>

                   <div class="form-row">
                     <div class="form-group col-md-6">
                       <label for="txtCantidad">Cantidad</label>
                       <input type="number" step="any" name="txtCantidad" id="txtCantidad" value="" class="form-control" placeholder="Cantidad de unidades/kg" required>
                     </div>
                     <div class="form-group col-md-6">
                       <label for="txtPrecio">Precio ($)</label>
                       <input type="number" step="0.01" name="txtPrecio" id="txtPrecio" value="" class="form-control" placeholder="0.00" required>
                     </div>
                   </div>

                   <div class="form-group">
                     <label for="txtIdFMP">Factura Asociada</label>
                     <select name="txtIdFMP" id="txtIdFMP" class="form-control" required>
                       <option value="">Seleccione factura...</option>
                       <?php if (!empty($facturas)): ?>
                         <?php foreach ($facturas as $f): ?>
                           <option value="<?php echo htmlspecialchars($f['idFacturaMP']); ?>">
                             Factura N° <?php echo htmlspecialchars($f['numeroFac']); ?>
                           </option>
                         <?php endforeach; ?>
                       <?php endif; ?>
                     </select>
                   </div>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                   <button type="submit" value="guardar" name="btnGuardar" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Guardar</button>
                   <button type="submit" value="modificar" name="btnModificar" class="btn btn-warning"><i class="fas fa-edit mr-1"></i>Modificar</button>
                   <button type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este detalle de compra?');"><i class="fas fa-trash-alt mr-1"></i>Eliminar</button>
                 </div>
               </form>
             </div>
           </div>
         </div>

         <!-- Tabla Detalle Compra -->
         <div class="card mb-3">
           <div class="card-header"><i class="fas fa-table mr-1"></i> Listado de Detalles de Compra</div>
           <div class="card-body">
             <div class="table-responsive">
               <table class="table table-bordered datatable" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                     <th>Materia Prima</th>
                     <th>Cantidad</th>
                     <th>Precio Unitario</th>
                     <th>Factura N°</th>
                     <th>Acciones</th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php if (!empty($tabla)): ?>
                     <?php foreach ($tabla as $fila): ?>
                       <tr>
                         <td><?php echo htmlspecialchars($fila['NombreMP'] ?? ''); ?></td>
                         <td><?php echo htmlspecialchars($fila['cantidadMP'] ?? ''); ?></td>
                         <td>$<?php echo number_format((float)($fila['precioMP'] ?? 0), 2); ?></td>
                         <td><?php echo htmlspecialchars($fila['numeroFac'] ?? ''); ?></td>
                         <td>
                           <button type="button" class="btn btn-info btn-sm cargar" data-toggle="modal" data-target="#modalDetalleCompra" onclick='cargar(<?php echo (int)$fila['idDetalleCompra']; ?>, <?php echo (int)$fila['idMateriaPrima']; ?>, "<?php echo htmlspecialchars($fila['cantidadMP'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($fila['precioMP'] ?? '', ENT_QUOTES); ?>", <?php echo (int)$fila['idFacturaMP']; ?>)'>
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
     function cargar(id, idMP, cantidad, precio, idFactura) {
       $('#txtIdDetalle').val(id);
       $('#txtIdMP').val(idMP);
       $('#txtCantidad').val(cantidad);
       $('#txtPrecio').val(precio);
       $('#txtIdFMP').val(idFactura);
     }

     function limpiarFormulario() {
       $('#txtIdDetalle').val('');
       $('#txtIdMP').val('');
       $('#txtCantidad').val('');
       $('#txtPrecio').val('');
       $('#txtIdFMP').val('');
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