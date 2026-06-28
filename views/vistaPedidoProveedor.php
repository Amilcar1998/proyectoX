<?php include '../views/configuracion.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pedidos a Proveedor</title>

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

   <script>
     function cargar(codpe,codpro,codemp,codmp,fec,can,mon,pre){
         document.formulario.txtIdPe.value=codpe;
         document.formulario.txtIdPro.value=codpro;
         document.formulario.txtIdEmp.value=codemp;
         document.formulario.txtIdMp.value=codmp;
         document.formulario.txtFec.value=fec;
         document.formulario.txtCan.value=can;
         document.formulario.txtMon.value=mon;
         document.formulario.txtPre.value=pre;
     }
     function limpiar(){
         document.formulario.txtIdPe.value='';
         document.formulario.txtIdPro.value='';
         document.formulario.txtIdEmp.value='';
         document.formulario.txtIdMp.value='';
         document.formulario.txtFec.value='';
         document.formulario.txtCan.value='';
         document.formulario.txtMon.value='';
         document.formulario.txtPre.value='';
     }
    </script>
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
         
         <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#formModal">
           <i class="fas fa-plus"></i> Agregar Pedido
         </button>
         
         <!-- Modal -->
         <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
               <div class="modal-header bg-info text-white">
                 <h5 class="modal-title" id="formModalLabel">Registro de Pedidos a Proveedor</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
                 <form method="POST" name="formulario">
                   <div class="row">
                     <div class="col-md-6">
                       <div class="form-group">
                         <label>ID Pedido</label>
                         <input type="text" name="txtIdPe" value="" class="form-control" readonly>
                       </div>
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                         <label>ID Proveedor</label>
                         <input type="text" name="txtIdPro" value="" class="form-control" placeholder="ID Proveedor">
                       </div>
                     </div>
                   </div>
                   
                   <div class="row">
                     <div class="col-md-6">
                       <div class="form-group">
                         <label>ID Empleado</label>
                         <input type="text" name="txtIdEmp" value="" class="form-control" placeholder="ID Empleado">
                       </div>
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                         <label>Materia Prima</label>
                         <select name="txtIdMp" class="form-control">
                           <option value="">Seleccione materia prima</option>
                           <option value="1">Maiz Amarillo</option>
                           <option value="2">Maiz Blanco</option>
                           <option value="3">Soya</option>
                           <option value="4">Harina</option>
                           <option value="5">Maicillo</option>
                           <option value="6">Trigo</option>
                         </select>
                       </div>
                     </div>
                   </div>
                   
                   <div class="row">
                     <div class="col-md-4">
                       <div class="form-group">
                         <label>Fecha</label>
                         <input type="text" name="txtFec" value="" class="form-control" placeholder="Fecha">
                       </div>
                     </div>
                     <div class="col-md-4">
                       <div class="form-group">
                         <label>Cantidad</label>
                         <input type="text" name="txtCan" value="" class="form-control" placeholder="Cantidad qq">
                       </div>
                     </div>
                     <div class="col-md-4">
                       <div class="form-group">
                         <label>Monto</label>
                         <input type="text" name="txtMon" value="" class="form-control" placeholder="Monto">
                       </div>
                     </div>
                   </div>
                   
                   <div class="form-group">
                     <label>Precio</label>
                     <input type="text" name="txtPre" value="" class="form-control" placeholder="Precio">
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
             Pedidos a Proveedor
           </div>
           <div class="card-body">
             <div class="table-responsive">
               <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                 <thead>
                   <tr>
                     <th>ID</th>
                     <th>ID PROVEEDOR</th>
                     <th>ID EMPLEADO</th>
                     <th>ID MATERIA PRIMA</th>
                     <th>FECHA</th>
                     <th>CANTIDAD</th>
                     <th>MONTO</th>
                     <th>PRECIO</th>
                     <th>ACCIONES</th>
                   </tr>
                 </thead>
                 <tbody>
                 <?php 
                 foreach ($tabla as $fila) {
                     echo "<tr>
                         <td>{$fila['idPedido']}</td>
                         <td>{$fila['idProveedor']}</td>
                         <td>{$fila['idEmpleado']}</td>
                         <td>{$fila['idMateriaPrima']}</td>
                         <td>{$fila['fecha']}</td>
                         <td>{$fila['cantidadMP']}</td>
                         <td>{$fila['monto']}</td>
                         <td>{$fila['precioMP']}</td>
                         <td>
                         <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#formModal' onclick='cargar({$fila['idPedido']},{$fila['idProveedor']},{$fila['idEmpleado']},\"{$fila['idMateriaPrima']}\",\"{$fila['fecha']}\",{$fila['cantidadMP']},{$fila['monto']},{$fila['precioMP']})'><i class='fas fa-edit'></i> Editar</button>
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