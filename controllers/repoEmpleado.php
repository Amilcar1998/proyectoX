<?php 
require_once __DIR__ . '/sesiones.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once __DIR__ . '/../models/ReportModel.php';

use Mpdf\Mpdf;

$idEmpresaFiltro = ($esSuperUsuario ?? false) ? 0 : (int)($_SESSION['idEmpresa'] ?? 1);
$dataEmp = new ReportModel();
$dataEmpleado = $dataEmp->dataEmpleados($idEmpresaFiltro);

$fechaActual = date('d/m/Y h:i A');
$totalRegistros = count($dataEmpleado);

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reporte de Empleados</title>
  <style>
    body {
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      color: #1e293b;
      font-size: 11px;
    }
    .header-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      border-bottom: 2px solid #2563eb;
      padding-bottom: 10px;
    }
    .title {
      font-size: 18px;
      font-weight: bold;
      color: #1e3a8a;
      margin: 0;
      text-transform: uppercase;
    }
    .subtitle {
      font-size: 12px;
      color: #64748b;
      margin-top: 4px;
    }
    .meta-info {
      text-align: right;
      font-size: 10px;
      color: #475569;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    .data-table th {
      background-color: #1e293b;
      color: #ffffff;
      font-weight: bold;
      padding: 8px 6px;
      font-size: 10px;
      text-align: left;
      border: 1px solid #1e293b;
      text-transform: uppercase;
    }
    .data-table td {
      padding: 7px 6px;
      border: 1px solid #cbd5e1;
      font-size: 10px;
    }
    .data-table tr:nth-child(even) {
      background-color: #f8fafc;
    }
    .text-center {
      text-align: center;
    }
    .badge {
      display: inline-block;
      padding: 2px 6px;
      border-radius: 4px;
      font-weight: bold;
      font-size: 9px;
    }
    .badge-user {
      background-color: #e0e7ff;
      color: #3730a3;
    }
    .badge-cargo {
      background-color: #f1f5f9;
      color: #334155;
      border: 1px solid #cbd5e1;
    }
    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 9px;
      color: #94a3b8;
      text-align: center;
      border-top: 1px solid #e2e8f0;
      padding-top: 6px;
    }
  </style>
</head>
<body>

  <table class="header-table">
    <tr>
      <td style="width: 60%; vertical-align: middle;">
        <div class="title">CONCENTRADOS EL GORDITO</div>
        <div class="subtitle">Nómina / Directorio Oficial de Empleados</div>
      </td>
      <td style="width: 40%; vertical-align: middle;" class="meta-info">
        <div><strong>Fecha de emisión:</strong> ' . $fechaActual . '</div>
        <div><strong>Total registros:</strong> ' . $totalRegistros . '</div>
      </td>
    </tr>
  </table>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 8%;" class="text-center">ID</th>
        <th style="width: 25%;">Nombre</th>
        <th style="width: 22%;">Apellidos</th>
        <th style="width: 15%;" class="text-center">Género</th>
        <th style="width: 15%;">Cargo / Puesto</th>
        <th style="width: 15%;">Usuario</th>
      </tr>
    </thead>
    <tbody>';

foreach ($dataEmpleado as $fila) {
    $id = htmlspecialchars((string)($fila["idEmpleado"] ?? ''));
    $nombre = htmlspecialchars((string)($fila["nombreEmp"] ?? ''));
    $apellido = htmlspecialchars((string)($fila["apellido"] ?? ''));
    $generoRaw = (string)($fila["genero"] ?? '');
    
    $esMasc = (strtoupper($generoRaw) === 'M' || stripos($generoRaw, 'masc') !== false || stripos($generoRaw, 'hombre') !== false);
    $esFem = (strtoupper($generoRaw) === 'F' || stripos($generoRaw, 'fem') !== false || stripos($generoRaw, 'mujer') !== false);
    $generoTexto = $esMasc ? 'Masculino' : ($esFem ? 'Femenino' : ($generoRaw ?: 'N/A'));
    
    $cargo = htmlspecialchars((string)($fila["nombrePuesto"] ?? 'Sin asignar'));
    $username = htmlspecialchars((string)($fila["username"] ?? ''));

    $html .= '
      <tr>
        <td class="text-center"><strong>#' . $id . '</strong></td>
        <td><strong>' . $nombre . '</strong></td>
        <td>' . $apellido . '</td>
        <td class="text-center">' . $generoTexto . '</td>
        <td><span class="badge badge-cargo">' . $cargo . '</span></td>
        <td><span class="badge badge-user">' . ($username ?: 'Sin cuenta') . '</span></td>
      </tr>';
}

if (empty($dataEmpleado)) {
    $html .= '<tr><td colspan="6" class="text-center" style="padding: 20px; color: #94a3b8;">No hay registros de empleados disponibles.</td></tr>';
}

$html .= '
    </tbody>
  </table>

  <div class="footer">
    Concentrados El Gordito &bull; Sistema de Gestión Administrativa &bull; Página {PAGENO} de {nbpg}
  </div>

</body>
</html>';

$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_left' => 12,
    'margin_right' => 12,
    'margin_top' => 12,
    'margin_bottom' => 15
]);

$mpdf->SetTitle('Reporte de Empleados - Concentrados El Gordito');
$mpdf->WriteHTML($html);
$mpdf->Output('Reporte_Empleados.pdf', 'I');
?>
