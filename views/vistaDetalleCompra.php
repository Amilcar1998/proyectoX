<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Detalle Compra</title>

    <link href="../controllers/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../controllers/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../controllers/vendor/sb-admin.css" rel="stylesheet" />
    <script src="../controllers/vendor/jquery/jquery.min.js"></script>
    <script src="../controllers/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../controllers/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../controllers/vendor/datatables/jquery.dataTables.js"></script>
    <script src="../controllers/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="../controllers/js/sb-admin.min.js"></script>

</head>

<body id="page-top">
   <?php echo "$nav"; ?>
   <div id="wrapper">
     <?php echo "$menu"; ?>
     <div id="content-wrapper">
       <div class="container-fluid">
         <h2 class="text-center mb-4">Detalle Compra</h2>
         <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#formModal">
           <i class="fas fa-plus"></i> Agregar Detalle Compra
         </button>
         
         <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
             <div class="modal-content">
               <div class="modal-header bg-info text-white">
                 <h5 class="modal-title" id="formModalLabel">Registro de Detalle Compra</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               </div>
               <div class="modal-body">
                 <form method="POST" name="formulario">
                   <div class="form-group">
                     <label>ID Detalle</label>
                     <input type="text" name="txtIdDetalle" value="" class="form-control" readonly>
                   </div>
                   <div class="form-group">
                     <label>ID Materia Prima</label>
                     <input type="text" name="txtIdMP" value="" class="form-control" placeholder="ID Materia Prima">
                   </div>
                   <div class="form-group">
                     <label>Cantidad</label>
                     <input type="text" name="txtCantidad" value="" class="form-control" placeholder="Cantidad">
                   </div>
                   <div class="form-group">
                     <label>Precio</label>
                     <input type="text" name="txtPrecio" value="" class="form-control" placeholder="Precio">
                   </div>
                   <div class="form-group">
                     <label>ID Factura</label>
                     <input type="text" name="txtIdFMP" value="" class="form-control" placeholder="ID Factura">
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

         <div class="card mb-3">
           <div class="card-header"><i class="fas fa-table"></i> Lista de Detalle Compra</div>
           <div class="card-body">
             <div class="table-responsive">
               <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                     <th>ID DETALLE</th>
                     <th>ID MATERIA PRIMA</th>
                     <th>CANTIDAD</th>
                     <th>PRECIO</th>
                     <th>ID FACTURA</th>
                     <th>ACCIONES</th>
                   </tr>
                 </thead>
                 <tbody>
                 <?php 
                 foreach ($tabla as $fila) {
                     echo "<tr>
                         <td>{$fila['idDetalleCompra']}</td>
                         <td>{$fila['idMateriaPrima']}</td>
                         <td>{$fila['cantidadMP']}</td>
                         <td>{$fila['precioMP']}</td>
                         <td>{$fila['idFacturaMP']}</td>
                         <td>
                         <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#formModal' onclick='cargar({$fila['idDetalleCompra']},{$fila['idMateriaPrima']},\"{$fila['cantidadMP']}\",\"{$fila['precioMP']}\",{$fila['idFacturaMP']})'><i class='fas fa-edit'></i> Editar</button>
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
     </div>
   </div>
</body>
</html>