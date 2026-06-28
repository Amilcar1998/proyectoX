<?php
include '../db/conexion.php';

use Mpdf\Mpdf;

class ReporteMezclasModel extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function getMezclas(): array {
        $res = $this->con->query("SELECT receta.idReceta, receta.nombreReceta, cantidaSa AS cantidad, idMateriaPrima from receta inner join detallereceta on receta.idReceta=detallereceta.idDetalleReceta order by nombreReceta");
        $r = [];
        while ($row = $res->fetch_assoc()) {
            $r[] = $row;
        }
        return $r;
    }

    public function generarPDF(array $data, string $titulo): void {
        $fecha = getdate();
        $fechaStr = $fecha['mday']."-".$fecha['mon']."-".$fecha['year']."  ".$fecha['hours'].":".$fecha['minutes'].":".$fecha['seconds'];
        
        $html = "<p align='center'><h3>CONCENTRADOS EL GORDITO</h3></p><br>
                <p align='center'><img src='../controllers/recursos/logo.jpg' width='150px';></p>
                Fecha del reporte: $fechaStr<hr><br>
                <h3>$titulo</h3>
                <table border='1'>
                    <tr>
                        <th>ID RECETA</th>
                        <th>NOMBRE RECETA</th>
                        <th>CANTIDAD</th>
                        <th>ID MATERIA PRIMA</th>
                    </tr>";
        
        foreach ($data as $fila) {
            $html .= "<tr>
                        <td style='padding-right:25px'>".$fila['idReceta']."</td>
                        <td style='padding-right:25px'>".$fila['nombreReceta']."</td>
                        <td style='padding-right:25px'>".$fila['cantidad']."</td>
                        <td style='padding-right:25px'>".$fila['idMateriaPrima']."</td>
                    </tr>";
        }
        $html .= "</table>";
        
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}