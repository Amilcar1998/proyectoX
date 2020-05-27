<?php
include 'DAOProveedor.php';
$prod=  new Proveedor();
$dao= new DAOProveedor();
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
    function cargar(id,nom,con,nit,correo,tel){
        document.formulario.txtId.value=id;
        document.formulario.txtNombre.value=nom;
        document.formulario.txtContacto.value=con;
        document.formulario.txtNit.value=nit;
        document.formulario.txtCorreo.value=correo;
        document.formulario.txtTelefono.value=tel;
    }
    </script>
</head>
<body background="recursos/fond.jpg">

<div align="center">
    <h4 class='table table-striped table-dark'>forulario registro de Proveedores</h4><hr>
    <div class="form-group" Style="position: relative; margin: auto; width: 500px;">
        <form method="POST" action="#" name="formulario">
            <input type="text" name="txtId" value="" size="30" placeholder="Id Proveedor" class="form-control">
            <input type="text" name="txtNombre" value="" size="30" placeholder="Nombre de proveedor" class="form-control">
            <input type="text" name="txtContacto" value="" size="30" placeholder="contacto" class="form-control">
            <input type="text" name="txtNit" value="" size="30" placeholder="Nit" class="form-control">
            <input type="text" name="txtCorreo" value="" size="30" placeholder="correo electronico" class="form-control">
            <input type="text" name="txtTelefono" value="" size="30" placeholder="Telefono" class="form-control">
            
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
                <option value="nombreProveedor">nombre </option>
                <option value="contacto">contacto </option>
                <option value="nit">nit </option>
                <option value="correoP">correo </option>
                <option value="telefono">telefono </option>
                
            </select><br>
            <input type="submit" value="Buscar" name="btnBuscar" class="btn btn-success">     
        </form><br><br><br>

    </div>
</div>


<div align="center" Style="position: relative; margin: auto; width: 500px;">
<?php
if(isset($_REQUEST["btnGuardar"])){
    $prod->setIdProveedor($_REQUEST["txtId"]);
    $prod->setNombreProveedor($_REQUEST["txtNombre"]);
    $prod->setContacto($_REQUEST["txtContacto"]);
    $prod->setNit($_REQUEST["txtNit"]);
    $prod->setCorreoP($_REQUEST["txtCorreo"]);
    $prod->setTelefono($_REQUEST["txtTelefono"]);
    
    $dao->insertar($prod);
    echo $dao->getTabla();

}else if(isset($_REQUEST["btnEliminar"])){
    $id=$_REQUEST["txtId"];

    $dao->eliminar($id);
    echo $dao->getTabla();

}else if(isset($_REQUEST["btnBuscar"])){
    echo $dao->getFiltro($_REQUEST["txtBusqueda"],$_REQUEST["txtCriterio"]);

}else if(isset($_REQUEST["btnModificar"])){
    $prod->setIdProveedor($_REQUEST["txtId"]);
    $prod->setNombreProveedor($_REQUEST["txtNombre"]);
    $prod->setContacto($_REQUEST["txtContacto"]);
    $prod->setNit($_REQUEST["txtNit"]);
    $prod->setCorreoP($_REQUEST["txtCorreo"]);
    $prod->setTelefono($_REQUEST["txtTelefono"]);
    
    $dao->modificar($prod);
    echo $dao->getTabla();
}else{ 
        echo $dao->getTabla();
}



?>
</div>

</body>
</html>