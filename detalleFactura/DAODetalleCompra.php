<?php

include '../db/conexion.php';
include '../detalleFactura/detalleCompra.php';

class DAODetalleCompraMVC extends Conexion {
    public function __construct(){
        parent::__construct();
    }

    public function getTabla(): array {
        $sql="select * from detalleCompra";
        $res = $this->con->query($sql);
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }


    public function insertar($obj): bool {
        $stmt = $this->con->prepare("insert into detalleCompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) values (?,?,?,?,?)");
        $id = $obj->getIdDetalleCompra();
        $mp = $obj->getIdMateriaPrima();
        $cantidad = $obj->getCantidadMP();
        $precio = $obj->getPrecioMP();
        $factura = $obj->getIdFacturaMP();
        $stmt->bind_param("iissi", $id, $mp, $cantidad, $precio, $factura);
        return $stmt->execute();
    }


    public function eliminar(int $idDetalleCompra): bool {
        $stmt = $this->con->prepare("delete from detalleCompra where idDetalleCompra=?");
        $stmt->bind_param("i", $idDetalleCompra);
        return $stmt->execute();
    }

    public function modificar($obj): bool {
        $stmt = $this->con->prepare("update detalleCompra set idMateriaPrima=?, cantidadMP=?, precioMP=?, idFacturaMP=? where idDetalleCompra=?");
        $mp = $obj->getIdMateriaPrima();
        $cantidad = $obj->getCantidadMP();
        $precio = $obj->getPrecioMP();
        $factura = $obj->getIdFacturaMP();
        $id = $obj->getIdDetalleCompra();
        $stmt->bind_param("issii", $mp, $cantidad, $precio, $factura, $id);
        return $stmt->execute();
    }

    public function getFiltro(string $buscar, string $criterio): array {
        $sql="select * from detalleCompra where $criterio like ?";
        $stmt = $this->con->prepare($sql);
        $valor = "%$buscar%";
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        $result = $stmt->get_result();
        $r = [];
        while ($row = $result->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }
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
        $sql="select * from detalleCompra";
        $this->conectar();
        $res = $this->con->query($sql);
        $this->desconectar();
        //tabala con bootstrap
        $tabla = "<table class='table table-striped table-dark'>"."<thead class='table table-striped table-dark'>";
        $tabla .="<tr>"
                    ."<th>ID DETALLE COMPRA</th>"
                    ."<th>ID MATERIA PRIMA</th>"
                    ."<th>CANTIDAD MATERIA PRIMA</th>"
                    ."<th>PRECIO MATERIA PRIMA</th>"                    
                    ."<th>ID FACTURA MATERIA PRIMA</th>"             
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idDetalleCompra"]."</td>"
                        ."<td>".$fila["idMateriaPrima"]."</td>"
                        ."<td>".$fila["cantidadMP"]."</td>"
                        ."<td>".$fila["precioMP"]."</td>"
                        ."<td>".$fila["idFacturaMP"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idDetalleCompra"]."','".$fila["idMateriaPrima"]."','".$fila["cantidadMP"]."','".$fila["precioMP"]."','".$fila["idFacturaMP"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }


    public function insertar($obj){
        $prod = new DetalleCompra();
        $prod = $obj;        
        $sql = "insert into detalleCompra value(".$prod->getIdDetalleCompra().",".$prod->getIdMateriaPrima().",".$prod->getCantidadMP().",".$prod->getPrecioMP().",".$prod->getIdFacturaMP().")";
        
        $this->conectar();
        if($this->con->query($sql)){
            //SweetAlert
        echo "<script>swal({title:'Exito',text:'El registro fue insertado satisfactoriamente',type:'success'});</script>";
        }else{
            echo "<script>swal({title:'Error',text:'El registro no fue insertado',type:'error'});</script>";
        }
        $this->desconectar();
    }


    public function eliminar($idDetalleCompra){
        $sql = "delete from detalleCompra where idDetalleCompra=$idDetalleCompra";
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
        $prod = new DetalleCompra();
        $prod = $obj;
        $sql = "update detalleCompra set idMateriaPrima=".$prod->getIdMateriaPrima().",cantidadMP=".$prod->getCantidadMP().",precioMP=".$prod->getPrecioMP().",idFacturaMP=".$prod->getIdFacturaMP()." where idDetalleCompra=".$prod->getIdDetalleCompra()."";
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
                    ."<th>ID DETALLE COMPRA</th>"
                    ."<th>ID MATERIA PRIMA</th>"
                    ."<th>CANTIDAD MATERIA PRIMA</th>"
                    ."<th>PRECIO MATERIA PRIMA</th>"                    
                    ."<th>ID FACTURA MATERIA PRIMA</th>"             
                    ."<th>ACCION</th>"
               ."</tr></thead><tbody>"; 
               
        while($fila = mysqli_fetch_assoc($res)){
            $tabla .= "<tr>"
                        ."<td>".$fila["idDetalleCompra"]."</td>"
                        ."<td>".$fila["idMateriaPrima"]."</td>"
                        ."<td>".$fila["cantidadMP"]."</td>"
                        ."<td>".$fila["precioMP"]."</td>"
                        ."<td>".$fila["idFacturaMP"]."</td>"
                        ."<td><a href=\"javascript:cargar('".$fila["idDetalleCompra"]."','".$fila["idMateriaPrima"]."','".$fila["cantidadMP"]."','".$fila["precioMP"]."','".$fila["idFActuraMP"]."')\">select</a></td>"
                    ."</tr>";
        }

        $tabla .="</tbody></table>";
        $res->close();
        return $tabla;

    }  
    

}
    


?>