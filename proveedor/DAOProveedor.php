<?php

include('credencialesProveedor.php');
include('proveedor.php');

class DAOProveedor{

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
        $sql="select * from proveedor";
        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID PROV.</th>"
                    ."<th>NOMBRE PROVEEDOR</th>"
                    ."<th>CONTACTO</th>"
                    ."<th>NIT</th>"
                    ."<th>CORREO</th>"
                    ."<th>TELEFONO</th>"                                        
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idProveedor"]."</td>"
                        ."<td>".$fila["nombreProveedor"]."</td>"
                        ."<td>".$fila["contacto"]."</td>"                        
                        ."<td>".$fila["nit"]."</td>"
                        ."<td>".$fila["correoP"]."</td>"
                        ."<td>".$fila["telefono"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idProveedor"]."','".$fila["nombreProveedor"]."','".$fila["contacto"]."','".$fila["nit"]."','".$fila["correoP"]."','".$fila["telefono"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }


    public function insertar($obj){
        $prod = new Proveedor();
        $prod = $obj;        
        $sql = "insert into proveedor value(".$prod->getIdProveedor().",'".$prod->getNombreProveedor()."','".$prod->getContacto()."','".$prod->getNit()."','".$prod->getCorreoP()."','".$prod->getTelefono()."')";
        
        
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        $this->desconectar();
    }


    public function eliminar($idProveedor){
        $sql = "delete from proveedor where idProveedor=$idProveedor";
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
        $prod = new Proveedor();
        $prod = $obj;
        $sql = "update proveedor set nombreProveedor='".$prod->getNombreProveedor()."',contacto='".$prod->getContacto()."',nit='".$prod->getNit()."',correoP='".$prod->getCorreoP()."',telefono='".$prod->getTelefono()."' where idProveedor=".$prod->getIdProveedor()."";
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
        $sql="select * from proveedor where $criterio like '%$buscar%'";

        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                         ."<th>ID PROV.</th>"
                        ."<th>NOMBRE PROVEEDOR</th>"
                        ."<th>CONTACTO</th>"
                        ."<th>NIT</th>"
                        ."<th>CORREO</th>"
                        ."<th>TELEFONO</th>"                                        
                        ."<th>ACCION</th>"
                ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idProveedor"]."</td>"
                        ."<td>".$fila["nombreProveedor"]."</td>"
                        ."<td>".$fila["contacto"]."</td>"                        
                        ."<td>".$fila["nit"]."</td>"
                        ."<td>".$fila["correoP"]."</td>"
                        ."<td>".$fila["telefono"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idProveedor"]."','".$fila["nombreProveedor"]."','".$fila["contacto"]."','".$fila["nit"]."','".$fila["correoP"]."','".$fila["telefono"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }  
    

}
    


?>