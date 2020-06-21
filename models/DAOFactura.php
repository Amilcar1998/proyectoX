<?php

include('../pedidoproveedor/credencialesPedidoProveedor.php');
include('factura.php');

class DAOFactura{

    private $con;

    public function __construct(){

    }

    public function conectar(){
        try {
            $this->con= new mysqli(SERVIDOR,USUARIO,CONTRA,BD);
        } catch (Exception  $ex) {
            echo $exc->getTraceAsString();
        }
        
    }

    public function desconectar(){
        $this->con->close();
    }

    public function getTabla(){
        $sql="select * from factura";
        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID FACTURA</th>"
                    ."<th>NUMERO DE FACTURA</th>"
                    ."<th>MONTO</th>"
                    ."<th>FECHA</th>"                    
                    ."<th>ID PROVEEDOR</th>"
                    ."<th>ID EMPLEADO</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idFacturaMP"]."</td>"
                        ."<td>".$fila["numeroFac"]."</td>"
                        ."<td>".$fila["monto"]."</td>"
                        ."<td>".$fila["fecha"]."</td>"
                        ."<td>".$fila["idProveedor"]."</td>"
                        ."<td>".$fila["idEmpleado"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idFacturaMP"]."','".$fila["numeroFac"]."','".$fila["monto"]."','".$fila["fecha"]."','".$fila["idProveedor"]."','".$fila["idEmpleado"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }


    public function insertarFactura($obj){
        $prod = new Factura();
        $prod = $obj;        
        $sql = "insert into factura value(".$prod->getIdFacturaMP().",'".$prod->getNumeroFac()."',".$prod->getMonto().",'".$prod->getFecha()."',".$prod->getIdProveedor().",".$prod->getIdEmpleado().")";
        
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        $this->desconectar();
    }


    public function eliminar($codigo){
        $sql = "delete from factura where idFacturaMP=$idFacturaMP";
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
            echo "<script>swal({title:'Exito',text:'El registro fue eliminado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue eliminado',type:'error'});</script>";
        }
        $this->desconectar();

    }

    public function modificar($obj){
        $prod = new Factura();
        $prod = $obj;
        $sql = "update factura set numeroFac='".$prod->getNumeroFac()."',monto=".$prod->getMonto().",fecha='".$prod->getFecha()."',idProveedor=".$prod->getIdProveedor().",idEmpleado=".$prod->getIdEmpleado()." where idFacturaMP=".$prod->getIdFacturaMP()."";
        $this->conectar();                                                                                                                                                                                                                                        
        if($this->con->query($sql)){
            //SweetAlert
            echo "<script>swal({title:'Exito',text:'El registro fue modificado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue modificado',type:'error'});</script>";
        }
        $this->desconectar();
    }

    
    public function getFiltro($buscar, $criterio){
        $sql="select * from factura where $criterio like '%$buscar%'";

        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID FACTURA</th>"
                    ."<th>NUMERO DE FACTURA</th>"
                    ."<th>MONTO</th>"
                    ."<th>FECHA</th>"                    
                    ."<th>ID PROVEEDOR</th>"
                    ."<th>ID EMPLEADO</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idFacturaMP"]."</td>"
                        ."<td>".$fila["numeroFac"]."</td>"
                        ."<td>".$fila["monto"]."</td>"
                        ."<td>".$fila["fecha"]."</td>"
                        ."<td>".$fila["idProveedor"]."</td>"
                        ."<td>".$fila["idEmpleado"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idFacturaMP"]."','".$fila["numeroFac"]."','".$fila["monto"]."','".$fila["fecha"]."','".$fila["idProveedor"]."','".$fila["idEmpleado"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }  
    

}
    


?>