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
        $sql="SELECT i.idInventario, mp.NombreMP, i.Existencias, dc.cantidadMP, dc.precioMP
              FROM inventario i
              INNER JOIN materiaprima mp ON i.idMateriaPrima = mp.idMateriaPrima
              LEFT JOIN detallecompra dc ON i.idDetalleCompra = dc.idDetalleCompra
              ORDER BY i.idInventario ASC";
        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID INVENTARIO</th>"
                    ."<th>MATERIA PRIMA</th>"
                    ."<th>EXISTENCIAS</th>"
                    ."<th>CANT. COMPRA</th>"
                    ."<th>PRECIO COMPRA</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
                
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idInventario"]."</td>"
                        ."<td>".$fila["NombreMP"]."</td>"
                        ."<td>".$fila["Existencias"]."</td>"                        
                        ."<td>".($fila["cantidadMP"] ?? '-')."</td>"
                        ."<td>".($fila["precioMP"] ?? '-')."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idInventario"]."','".$fila["NombreMP"]."','".$fila["Existencias"]."')\">select</a></td>"
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
        $sql="SELECT i.idInventario, mp.NombreMP, i.Existencias, dc.cantidadMP, dc.precioMP
              FROM inventario i
              INNER JOIN materiaprima mp ON i.idMateriaPrima = mp.idMateriaPrima
              LEFT JOIN detallecompra dc ON i.idDetalleCompra = dc.idDetalleCompra
              WHERE $criterio LIKE ?
              ORDER BY i.idInventario ASC";

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
                    ."<th>ID INVENTARIO</th>"
                    ."<th>MATERIA PRIMA</th>"
                    ."<th>EXISTENCIAS</th>"
                    ."<th>CANT. COMPRA</th>"
                    ."<th>PRECIO COMPRA</th>"                    
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
                
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idInventario"]."</td>"
                        ."<td>".$fila["NombreMP"]."</td>"
                        ."<td>".$fila["Existencias"]."</td>"                        
                        ."<td>".($fila["cantidadMP"] ?? '-')."</td>"
                        ."<td>".($fila["precioMP"] ?? '-')."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idInventario"]."','".$fila["NombreMP"]."','".$fila["Existencias"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }
    

}
    


?>