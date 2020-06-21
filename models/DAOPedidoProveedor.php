<?php

include '../db/conexion.php';
include 'pedidoProveedor.php';
include 'factura.php';
include'detalleCompra.php';
/*include('../materiaPrima/MateriaPrima.php');
include('../inventario/inventario.php');*/

class DAOPedidoProveedor extends Conexion{

    public function __construct(){
        parent::__construct();
    }

    public function getTabla(){
        $res = $this->con->query("select * from pedidoproveedor");
        $tabla = "<table class='table'>"."<thead class='thead-dark'>";
        $tabla .="<tr>"
                    ."<th>CODIGO PEDIDO</th>"
                    ."<th>CODIGO PROVEEDOR</th>"
                    ."<th>CODIGO EMPLEADO</th>"
                    ."<th>CODIGO MATERIA PRIMA</th>"
                    ."<th>FECHA</th>"
                    ."<th>CANTIDAD</th>"
                    ."<th>MONTO</th>"
                    ."<th>PRECIO</th>"
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idPedido"]."</td>"
                        ."<td>".$fila["idProveedor"]."</td>"
                        ."<td>".$fila["idEmpleado"]."</td>"
                        ."<td>".$fila["idMateriaPrima"]."</td>"
                        ."<td>".$fila["fecha"]."</td>"
                        ."<td>".$fila["cantidadMP"]."</td>"
                        ."<td>".$fila["monto"]."</td>"
                        ."<td>".$fila["precioMP"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idPedido"]."','".$fila["idProveedor"]."','".$fila["idEmpleado"]."','".$fila["idMateriaPrima"]."','".$fila["fecha"]."','".$fila["cantidadMP"]."','".$fila["monto"]."','".$fila["precioMP"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }


    public function insertar($obj){
        $prod = new PedidoProveedor();
        $prod = $obj;        
        $sql = "insert into pedidoproveedor value(0,".$prod->getIdProveedor().",".$prod->getIdEmpleado().",".$prod->getIdMateriaPrima().",'".$prod->getFecha()."',".$prod->getCantidadMP().",".$prod->getMonto().",".$prod->getPrecioMP().")";                         //getIdPedido             //getFecha              //getNombreProducto            //getCantidad            //getPrecio            //getIdProveedor        
        if($this->con->query($sql)){
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        /////////////////////////////////////////////
        $prod = new Factura();
        $prod = $obj;       
        $d=mt_rand(100,7000); 
        $sql = "insert into factura value(0,".$d.",".$prod->getMonto().",'".$prod->getFecha()."',".$prod->getIdProveedor().",".$prod->getIdEmpleado().")";                            
        if($this->con->query($sql)){
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        ///////////////////////////////////////////////
        $prod = new DetalleCompra();
        $prod = $obj;        
        $sql = "insert into detalleCompra value(0,".$prod->getIdMateriaPrima().",".$prod->getCantidadMP().",".$prod->getPrecioMP().",1)";
        
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
    }

        ///////////////////////////////////////////////

/*
        $prod = new MateriaPrima();
        $prod = $obj;        
        $sql = "insert into materiaPrima value(".$prod->getIdMateriaPrima().",'".$prod->getNombreMP()."')";        
        
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        $this->desconectar();


        ///////////////////////////////////////////////

       /* $qq=$prod->getCantidadMP();
        function calculo($qq){
        $prod = new Inventario();    
        $idMP=$prod->getIdMateriaPrima();
        $sql="select  idMateriaPrima from inventario where idMateriaPrima=$idMP";
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        //echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
          //  echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }

        if($sql==$qq){
            $suma=$sql+$qq;
            return $suma;
        }else{
            return $prod->getCantidadMP();
        } 


        $this->desconectar();
        }*/

        /*

        $prod = new Inventario();
        $prod = $obj;        
        $sql = "insert into inventario value(1,".$prod->getIdMateriaPrima().",555555,1)";
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        $this->desconectar();
 

        
    }*/

    



    public function eliminar($idPedido){
        $sql = "delete from pedidoproveedor where idPedido=$idPedido";
        if($this->con->query($sql)){
            //SweetAlert
            echo "<script>swal({title:'Exito',text:'El registro fue eliminado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue eliminado',type:'error'});</script>";
        }

    }
    
    public function modificar($obj){
        $prod = new PedidoProveedor();
        $prod = $obj;
        $sql = "update pedidoproveedor set idProveedor=".$prod->getIdProveedor()." ,idEmpleado=".$prod->getIdEmpleado()." ,idMateriaPrima=".$prod->getIdMateriaPrima()." ,fecha='".$prod->getFecha()."',cantidadMP=".$prod->getCantidadMP().",monto=".$prod->getMonto()." ,precioMP=".$prod->getPrecioMP()." where idPedido=".$prod->getIdPedido();
        if($this->con->query($sql)){
            //SweetAlert
            echo "<script>swal({title:'Exito',text:'El registro fue modificado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue modificado',type:'error'});</script>";
        }
    }
        

}
    

?>