<?php

include('credencialesPuesto.php');
include('puesto.php');

class DAOPuesto{

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
        $sql="select * from puesto";
        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID PUESTO</th>"
                    ."<th>NOMBRE PUESTO</th>"
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idPuesto"]."</td>"
                        ."<td>'".$fila["nombrePuesto"]."'</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idPuesto"]."','".$fila["nombrePuesto"]."')\">select</a></td>"
                    ."</tr>";
        }
        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;
    }

    public function insertar($obj){
        $prod = new Puesto();
        $prod = $obj;        
        $sql = "insert into puesto value(".$prod->getIdPuesto().",'".$prod->getNombrePuesto()."')";
        
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        $this->desconectar();
    }


    public function eliminar($idPuesto){
        $sql = "delete from puesto where idPuesto=$idPuesto";
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
        $prod = new Puesto();
        $prod = $obj;
        $sql = "update puesto set idPuesto='".$prod->getNombrePuesto()."' where idDetalleCompra=".$prod->getId()."";
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
                    ."<th>ID PUESTO</th>"
                    ."<th>NOMBRE PUESTO</th>"
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idPuesto"]."</td>"
                        ."<td>'".$fila["nombrePuesto"]."'</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idPuesto"]."','".$fila["nombrePuesto"]."')\">select</a></td>"
                    ."</tr>";
        }
        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }  
    

}
    


?>