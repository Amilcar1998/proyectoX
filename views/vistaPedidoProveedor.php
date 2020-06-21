<?php


//pedido Al Proveedor
//include '../models/DAOPedidoProveedor.php';

//instancias del pedido al proveedor
$prod=  new PedidoProveedor();
$dao= new DAOPedidoProveedor();



?>

<!DOCTYPE html>
<html lang="en">

<title></title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="recursos/css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="recursos/js/sweetalert.css">

    <script src="recursos/css/bootstrap/js/bootstrarp.js"></script>
    
    
    <script src="recursos/js/jquery-3.4.1.js"></script>
    <script src="recursos/js/sweetalert.min.js"></script>
    <script>
    
    </script>   


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
    </script>

</head>

<body id="page-top">

<div align="center">
    <h4 class='table table-striped table-dark'>forulario registro de Pedidos a Proveedores</h4><hr>
    <div class="form-group" Style="position: relative; margin: auto; width: 500px;">
        <form method="POST" action="#" name="formulario">

            <input type="text" name="txtIdPe" value="" size="30" placeholder="Id pedido" class="form-control">
            <input type="text" name="txtIdPro" value="" size="30" placeholder="Id proveedor" class="form-control">
            <input type="text" name="txtIdEmp" value="" size="30" placeholder="Id empleado" class="form-control">
            <select name="txtIdMp" class="form-control">Seleccionar Materia Prima
                <option value="">seleccione materia prima</option>
                <option value="1">Maiz Amarillo</option>
                <option value="2">Maiz Blanco</option>
                <option value="3">Maicillo</option>
                <option value="4">Arroz</option>
            </select>
            <input type="text" name="txtFec" value="" size="30" placeholder="fecha" class="form-control">
            <input type="text" name="txtCan" value="" size="30" placeholder="catidad qq" class="form-control">
            <input type="text" name="txtMon" value="" size="30" placeholder="monto" class="form-control">
            <input type="text" name="txtPre" value="" size="30" placeholder="precio" class="form-control">

            
            <br>
            <input type="submit" value="guardar" name="btnGuardar" class="btn btn-primary">
            <input type="submit" value="modificar" name="btnModificar" class="btn btn-warning">
            <input type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger">
        <br><br>

        </form>
        
        </div>
</div>

<div align="center" Style="position: relative; margin: 200px; width: 10px;">
<?php
if(isset($_REQUEST["btnGuardar"])){
    $prod->setIdPedido($_REQUEST["txtIdPe"]);
    $prod->setIdProveedor($_REQUEST["txtIdPro"]);
    $prod->setIdEmpleado($_REQUEST["txtIdEmp"]);
    $prod->setIdMateriaPrima($_REQUEST["txtIdMp"]);
    $prod->setFecha($_REQUEST["txtFec"]);
    $prod->setCantidadMP($_REQUEST["txtCan"]);
    $prod->setMonto($_REQUEST["txtMon"]);
    $prod->setPrecioMP($_REQUEST["txtPre"]);

    /////

    

    ////

    $dao->insertar($prod);

    echo $dao->getTabla();
}else if(isset($_REQUEST["btnEliminar"])){
    $codigo=$_REQUEST["txtIdPe"];
    $dao->eliminar($codigo);
    echo $dao->getTabla();
}else if(isset($_REQUEST["btnBuscar"])){
    echo $dao->getFiltro($_REQUEST["txtBusqueda"],$_REQUEST["txtCriterio"]);
}else if(isset($_REQUEST["btnModificar"])){
  $prod->setIdPedido($_REQUEST["txtIdPe"]);
  $prod->setIdProveedor($_REQUEST["txtIdPro"]);
  $prod->setIdEmpleado($_REQUEST["txtIdEmp"]);
  $prod->setIdMateriaPrima($_REQUEST["txtIdMp"]);
  $prod->setFecha($_REQUEST["txtFec"]);
  $prod->setNombreMP($_REQUEST["txtNombre"]);
  $prod->setCantidadMP($_REQUEST["txtCan"]);
  $prod->setMonto($_REQUEST["txtMon"]);
  $prod->setPrecioMP($_REQUEST["txtPre"]);

  $dao->modificar($prod);
    echo $dao->getTabla();
}else{ 
        echo $dao->getTabla();
}

?>

</div>

</body>

</html>
