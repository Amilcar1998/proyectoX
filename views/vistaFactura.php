<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Factura</title>

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
           <i class="fas fa-plus"></i> Agregar Factura
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
                     <label>ID Proveedor</label>
                     <input type="text" name="txtIdProveedor" value="" class="form-control" placeholder="ID Proveedor">
                   </div>
                   <div class="form-group">
                     <label>ID Empleado</label>
                     <input type="text" name="txtIdEmpleado" value="" class="form-control" placeholder="ID Empleado">
                   </div>
                   
                   <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                     <button type="submit" value="guardar" name="btnGuardar" class="btn btn-primary">Guardar</button>
                     <button type="submit" value="modificar" name="btnModificar" class="btn btn-warning">Modificar</button>
                     <button type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger">Eliminar</button>
                   </form>
                   </div>
               </div>
             </div>
           </div>
         </div>

         <!-- DataTables Example -->
         <div class="card mb-3">
           <div class="card-header">
             <i class="fas fa-table"></i>
             Lista de Facturas
           </div>
           <div class="card-body">
             <div class="table-responsive">
               <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                     <th>ID FACTURA</th>
                     <th>NÚMERO FACTURA</th>
                     <th>MONTO</th>
                     <th>FECHA</th>
                     <th>ID PROVEEDOR</th>
                     <th>ID EMPLEADO</th>
                     <th>ACCIONES</th>
                   </tr>
                 </thead>
                 <tbody>
                 <?php 
                 foreach ($tabla as $fila) {
                     echo "<tr>
                         <td>{$fila['idFacturaMP']}</td>
                         <td>{$fila['numeroFac']}</td>
                         <td>{$fila['Monto']}</td>
                         <td>{$fila['Fecha']}</td>
                         <td>{$fila['idProveedor']}</td>
                         <td>{$fila['idEmpleado']}</td>
                         <td>
                         <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#formModal' onclick='cargar({$fila['idFacturaMP']},\"{$fila['numeroFac']}\",{$fila['Monto']},\"{$fila['Fecha']}\",{$fila['idProveedor']},{$fila['idEmpleado']})'><i class='fas fa-edit'></i> Editar</button>
                         </td>
                     </tr>";
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
</body>
</html>