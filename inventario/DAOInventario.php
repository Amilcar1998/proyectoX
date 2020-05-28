<?php

include('credencialesInventario.php');
include('inventario.php');

class DAOInventario{

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
        $sql="select * from inventario";
        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID INVENTARIO</th>"
                    ."<th>ID MATERIA PRIMA</th>"
                    ."<th>EXISTENCIAS</th>"
                    ."<th>ID DETALLE COMPRA</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idInventario"]."</td>"
                        ."<td>".$fila["idMateriaPrima"]."</td>"
                        ."<td>".$fila["Existencias"]."</td>"                        
                        ."<td>".$fila["idDetalleCompra"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idInventario"]."','".$fila["idMateriaPrima"]."','".$fila["Existencias"]."','".$fila["idDetalleCompra"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }


    public function insertar($obj){
        $prod = new Inventario();
        $prod = $obj;        
        $sql = "insert into inventario value(".$prod->getIdInventario().",".$prod->getIdMateriaPrima().",".$prod->getExistencias().",".$prod->getIdDetalleCompra().")";
        
        
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        $this->desconectar();
    }


    public function eliminar($idInventario){
        $sql = "delete from inventario where idInventario=$idInventario";
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
        $prod = new Inventario();
        $prod = $obj;
        $sql = "update inventario set idMateriaPrima=".$prod->getIdMateriaPrima().",Existencias=".$prod->getExistencias().",idDetalleCompra=".$prod->getIdDetalleCompra()." where idInventario=".$prod->getIdInventario()."";
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
        $sql="select * from inventario where $criterio like '%$buscar%'";

        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID INVENTARIO</th>"
                    ."<th>ID MATERIA PRIMA</th>"
                    ."<th>EXISTENCIAS</th>"
                    ."<th>ID DETALLE COMPRA</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idInventario"]."</td>"
                        ."<td>".$fila["idMateriaPrima"]."</td>"
                        ."<td>".$fila["Existencias"]."</td>"                        
                        ."<td>".$fila["idDetalleCompra"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idInventario"]."','".$fila["idMateriaPrima"]."','".$fila["Existencias"]."','".$fila["idDetalleCompra"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }  
    

}
    


?>