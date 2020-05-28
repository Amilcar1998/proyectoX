<?php
include 'DAOInventario.php';
$prod=  new Inventario();
$dao= new DAOInventario();
?>

<html>
<head>
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
    function cargar(idin,idmp,ex,iddc){
        document.formulario.txtIdIn.value=idin;
        document.formulario.txtIdMP.value=idmp;
        document.formulario.txtEx.value=ex;
        document.formulario.txtIdDC.value=iddc;
    }
    </script>
</head>
<body background="recursos/yea.png">

<div align="center">
    <h4 class='table table-striped table-dark'>forulario registro de Inventario</h4><hr>
    <div class="form-group" Style="position: relative; margin: auto; width: 500px;">
        <form method="POST" action="#" name="formulario">
            <input type="text" name="txtIdIn" value="" size="30" placeholder="Id Inventario" class="form-control">
            <input type="text" name="txtIdMP" value="" size="30" placeholder="Id materia prima" class="form-control">
            <input type="text" name="txtEx" value="" size="30" placeholder="Existencias" class="form-control">
            <input type="text" name="txtIdDC" value="" size="30" placeholder="id detalle de compra" class="form-control">
            
            <br>
            <input type="submit" value="guardar" name="btnGuardar" class="btn btn-primary">
            <input type="submit" value="modificar" name="btnModificar" class="btn btn-warning">
            <input type="submit" value="eliminar" name="btnEliminar" class="btn btn-danger">
            
        </form>
        <br><br>

        <form method="POST" action="#" name="busqueda">
            <input type="text" name="txtBusqueda" value="" size="10" placeholder="Busqueda........?" class="form-control" style="width: 300px;">
            <h4 class='table table-striped table-dark'>Buscar por:</h4>
            <select class="form-control" name="txtCriterio" style="width: 300px;">
                <option value="idMateriaPrima">id Materia Prima </option>
                <option value="Existencia">Existencia </option>
                <option value="idDetalleCompra">Id detalle Compra </option>                
                
            </select><br>
            <input type="submit" value="Buscar" name="btnBuscar" class="btn btn-success">     
        </form><br><br><br>

    </div>
</div>


<div align="center" Style="position: relative; margin: auto; width: 500px;">
<?php
if(isset($_REQUEST["btnGuardar"])){
    $prod->setIdInventario($_REQUEST["txtIdIn"]);
    $prod->setIdMateriaPrima($_REQUEST["txtIdMP"]);
    $prod->setExistencias($_REQUEST["txtEx"]);
    $prod->setIdDetalleCompra($_REQUEST["txtIdDC"]);
    
    $dao->insertar($prod);
    echo $dao->getTabla();

}else if(isset($_REQUEST["btnEliminar"])){
    $id=$_REQUEST["txtIdIn"];

    $dao->eliminar($id);
    echo $dao->getTabla();

}else if(isset($_REQUEST["btnBuscar"])){
    echo $dao->getFiltro($_REQUEST["txtBusqueda"],$_REQUEST["txtCriterio"]);

}else if(isset($_REQUEST["btnModificar"])){
    $prod->setIdInventario($_REQUEST["txtIdIn"]);
    $prod->setIdMateriaPrima($_REQUEST["txtIdMP"]);
    $prod->setExistencias($_REQUEST["txtEx"]);
    $prod->setIdDetalleCompra($_REQUEST["txtIdDC"]);
    
    $dao->modificar($prod);
    echo $dao->getTabla();
}else{ 
        echo $dao->getTabla();
}



?>
</div>

</body>
</html>