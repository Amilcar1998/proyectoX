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
        $sql="SELECT f.idFacturaMP, f.numeroFac, f.monto, f.fecha,
                     p.nombreProveedor,
                     CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado
              FROM factura f
              INNER JOIN proveedor p ON f.idProveedor = p.idProveedor
              INNER JOIN empleado e ON f.idEmpleado = e.idEmpleado
              ORDER BY f.fecha DESC";
        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>NUMERO DE FACTURA</th>"
                    ."<th>MONTO</th>"
                    ."<th>FECHA</th>" 
                    ."<th>PROVEEDOR</th>"                   
                    ."<th>EMPLEADO</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
                
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["numeroFac"]."</td>"
                        ."<td>".$fila["monto"]."</td>"
                        ."<td>".$fila["fecha"]."</td>"
                        ."<td>".$fila["nombreProveedor"]."</td>"
                        ."<td>".$fila["empleado"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idFacturaMP"]."','".$fila["numeroFac"]."','".$fila["monto"]."','".$fila["fecha"]."')\">select</a></td>"
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
        $sql = "delete from factura where idFacturaMP=$codigo";
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
        $sql="SELECT f.idFacturaMP, f.numeroFac, f.monto, f.fecha,
                     p.nombreProveedor,
                     CONCAT(e.nombreEmp, ' ', e.apellido) AS empleado
              FROM factura f
              INNER JOIN proveedor p ON f.idProveedor = p.idProveedor
              INNER JOIN empleado e ON f.idEmpleado = e.idEmpleado
              WHERE $criterio LIKE ?
              ORDER BY f.fecha DESC";

        $this->conectar();
        $stmt = $this->con->prepare($sql);
        $valor = "%".$buscar."%";
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        $res = $stmt->get_result();
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>NUMERO DE FACTURA</th>"
                    ."<th>MONTO</th>"
                    ."<th>FECHA</th>" 
                    ."<th>PROVEEDOR</th>"                    
                    ."<th>EMPLEADO</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
                
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["numeroFac"]."</td>"
                        ."<td>".$fila["monto"]."</td>"
                        ."<td>".$fila["fecha"]."</td>"
                        ."<td>".$fila["nombreProveedor"]."</td>"
                        ."<td>".$fila["empleado"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idFacturaMP"]."','".$fila["numeroFac"]."','".$fila["monto"]."','".$fila["fecha"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }
    

}
    


?>