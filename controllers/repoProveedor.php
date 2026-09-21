<?php 
require_once __DIR__ . '/sesiones.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once __DIR__ . '/../models/ReportModel.php';

use Mpdf\Mpdf;

$idEmpresaFiltro = ($esSuperUsuario ?? false) ? 0 : (int)($_SESSION['idEmpresa'] ?? 1);
$dataProv = new ReportModel();
$dataProveedor = $dataProv->dataProveedor($idEmpresaFiltro);

$fechaActual = date('d/m/Y h:i A');
$totalRegistros = count($dataProveedor);

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reporte de Proveedores</title>
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
        <div class="subtitle">Directorio Oficial de Proveedores Registrados</div>
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
        <th style="width: 26%;">Proveedor / Razón Social</th>
        <th style="width: 20%;">Contacto</th>
        <th style="width: 16%;">NIT</th>
        <th style="width: 18%;">Correo Electrónico</th>
        <th style="width: 12%;">Teléfono</th>
      </tr>
    </thead>
    <tbody>';

foreach ($dataProveedor as $fila) {
    $id = htmlspecialchars((string)($fila["idProveedor"] ?? ''));
    $nombre = htmlspecialchars((string)($fila["nombreProveedor"] ?? ''));
    $contacto = htmlspecialchars((string)($fila["contacto"] ?? '-'));
    $nit = htmlspecialchars((string)($fila["NIT"] ?? '-'));
    $correo = htmlspecialchars((string)($fila["correoP"] ?? '-'));
    $telefono = htmlspecialchars((string)($fila["telefono"] ?? '-'));

    $html .= '
      <tr>
        <td class="text-center"><strong>#' . $id . '</strong></td>
        <td><strong>' . $nombre . '</strong></td>
        <td>' . $contacto . '</td>
        <td>' . $nit . '</td>
        <td>' . $correo . '</td>
        <td>' . $telefono . '</td>
      </tr>';
}

if (empty($dataProveedor)) {
    $html .= '<tr><td colspan="6" class="text-center" style="padding: 20px; color: #94a3b8;">No hay registros de proveedores disponibles.</td></tr>';
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

$mpdf->SetTitle('Reporte de Proveedores - Concentrados El Gordito');
$mpdf->WriteHTML($html);
$mpdf->Output('Reporte_Proveedores.pdf', 'I');
?>
