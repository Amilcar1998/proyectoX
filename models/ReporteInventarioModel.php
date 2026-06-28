<?php
include '../db/conexion.php';
include '../controllers/vendor/autoload.php';

use Mpdf\Mpdf;

class ReporteInventarioMP extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getInventario(): array {
        $res = $this->con->query("SELECT inventario.idMateriaPrima, materiaprima.NombreMP AS NombreMAP, Existencias FROM inventario INNER JOIN materiaprima ON inventario.idMateriaPrima = materiaprima.idMateriaPrima");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function getInventarioEscaso(): array {
        $res = $this->con->query("SELECT materiaprima.nombreMP AS NombreMAP, Existencias from materiaprima inner join inventario on materiaprima.idMateriaPrima=inventario.idMateriaPrima where Existencias <= 50 order by Existencias asc");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function generarPDF(array $data, string $titulo, string $header = ''): void {
        $fecha = getdate();
        $fechaStr = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds'];
        
        $html = "<p align='center'><h3>CONCENTRADOS EL GORDITO</h3></p><br>
                <p align='center'><img src='../controllers/recursos/logo.jpg' width='150px';></p>
                Fecha del reporte: $fechaStr<hr><br>
                <h3>$titulo</h3>";
        
        if ($header !== '') {
            $html .= $header;
        } else {
            $html .= "<table border='1'><tr><th>ID MP</th><th>NOMBRE MP</th><th>EXISTENCIA</th></tr>";
            foreach ($data as $fila) {
                $id = $fila['idMateriaPrima'] ?? '';
                $html .= "<tr><td>".$id."</td><td>".$fila['NombreMAP']."</td><td>".$fila['Existencias']."</td></tr>";
            }
            $html .= "</table>";
        }
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}