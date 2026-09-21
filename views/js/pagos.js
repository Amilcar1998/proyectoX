/**
 * pagos.js - Lógica interactiva del módulo de Pagos Wompi, visualizador de transacciones y comprobantes
 * Concentrados El Gordito
 */

let ultimoJsonCargado = "";
let ultimoPagoData = null;

$(document).ready(function() {
    // Manejar clic en "Detalle Wompi"
    $('.btn-ver-detalle').on('click', function() {
        const idPago = $(this).data('id');
        $('#modalDetallePago').modal('show');
        $('#modalHeaderSub').text(`Consultando registro de pago #${idPago}...`);
        $('#modalContenidoDetalle').html(`
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                <div class="mt-3 text-muted h6 font-weight-normal">Obteniendo datos de Wompi para el pago #${idPago}...</div>
            </div>
        `);

        $.ajax({
            url: 'controllerPagos.php',
            type: 'GET',
            data: { accion: 'obtenerDetalle', idPago: idPago },
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success' && resp.pago) {
                    renderizarDetallePago(resp.pago);
                } else {
                    $('#modalContenidoDetalle').html(`
                        <div class="alert alert-danger p-4 text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                            No se pudo cargar la información del pago solicitado.
                        </div>
                    `);
                }
            },
            error: function() {
                $('#modalContenidoDetalle').html(`
                    <div class="alert alert-danger p-4 text-center">
                        <i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>
                        Error al conectar con el servidor para obtener los datos de Wompi.
                    </div>
                `);
            }
        });
    });

    // Manejador de evento para Reembolsar Pago
    $(document).on('click', '.btn-reembolsar', function() {
        const idPago = $(this).data('id');
        const monto = $(this).data('monto');
        const cliente = $(this).data('cliente');

        Swal.fire({
            title: '¿Reembolsar Pago #' + idPago + '?',
            html: `
                <p class="text-muted small">Vas a procesar el reembolso de la transacción por <strong>$${monto} USD</strong> perteneciente a <strong>${cliente}</strong>.</p>
                <div class="text-left mt-3">
                    <label class="font-weight-bold text-dark small mb-1">Motivo del reembolso (obligatorio):</label>
                    <textarea id="swalMotivoReembolso" class="form-control" rows="3" placeholder="Ej: Cancelación de pedido solicitada por cliente / duplicidad de cobro"></textarea>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-undo mr-1"></i> Confirmar Reembolso',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const motivo = document.getElementById('swalMotivoReembolso').value.trim();
                if (!motivo) {
                    Swal.showValidationMessage('Por favor ingresa un motivo para el reembolso.');
                    return false;
                }
                return motivo;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Procesando reembolso...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: 'controllerPagos.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        accion: 'reembolsar',
                        idPago: idPago,
                        motivo: result.value
                    },
                    success: function(resp) {
                        if (resp.status === 'success') {
                            Swal.fire({
                                title: '¡Reembolso Procesado!',
                                text: resp.mensaje,
                                icon: 'success',
                                confirmButtonColor: '#16a34a'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', resp.mensaje || 'No se pudo procesar el reembolso.', 'error');
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Error en el servidor al procesar la solicitud.';
                        try {
                            const errJson = JSON.parse(xhr.responseText);
                            if (errJson.mensaje) msg = errJson.mensaje;
                        } catch(e) {}
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });

    // Manejador para Imprimir Directamente desde la Tabla
    $(document).on('click', '.btn-imprimir-directo-pago', function() {
        const idPago = $(this).data('id');
        if (!idPago) return;

        Swal.fire({
            title: 'Preparando Comprobante...',
            text: 'Obteniendo los datos de la transacción...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: 'controllerPagos.php',
            type: 'GET',
            dataType: 'json',
            data: {
                accion: 'obtenerDetalle',
                idPago: idPago
            },
            success: function(resp) {
                Swal.close();
                const pagoData = resp.pago || resp.data;
                if (resp.status === 'success' && pagoData) {
                    const html = generarHtmlComprobantePago(pagoData);
                    const ventana = window.open('', '_blank', 'height=800,width=900');
                    ventana.document.open();
                    ventana.document.write(html);
                    ventana.document.close();
                    ventana.focus();
                    setTimeout(() => {
                        ventana.print();
                    }, 350);
                } else {
                    Swal.fire('Error', resp.mensaje || 'No se pudieron obtener los datos de la transacción.', 'error');
                }
            },
            error: function() {
                Swal.close();
                Swal.fire('Error de Conexión', 'No se pudo comunicar con el servidor para obtener el comprobante.', 'error');
            }
        });
    });
});

function renderizarDetallePago(p) {
    ultimoPagoData = p;
    const meta = p.metadatos_array || {};
    const wompiRetorno = meta.wompi_retorno || {};
    const items = meta.items || (p.items_comprados || []);
    ultimoJsonCargado = JSON.stringify(p, null, 2);

    $('#modalHeaderSub').html(`Pago <strong>#${p.idPago}</strong> • Ref: <span class="font-monospace text-warning">${p.referencia || '-'}</span> • Fecha: ${p.fecha_hora}`);

    let estadoBadge = `<span class="badge badge-completado px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem;"><i class="fas fa-check-circle mr-1"></i>Aprobado por Wompi</span>`;
    if (p.estado === 'pendiente') {
        estadoBadge = `<span class="badge badge-pendiente px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem;"><i class="fas fa-clock mr-1"></i>Pendiente</span>`;
    } else if (p.estado === 'reembolsado') {
        estadoBadge = `<span class="badge badge-reembolsado px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem;"><i class="fas fa-undo mr-1"></i>Reembolsado</span>`;
    } else if (p.estado === 'fallido') {
        estadoBadge = `<span class="badge badge-fallido px-3 py-2 text-uppercase font-weight-bold" style="font-size: 0.85rem;"><i class="fas fa-times-circle mr-1"></i>Fallido / Declinado</span>`;
    }

    // Desglose de productos o plan
    let htmlProductos = '';
    if (items.length > 0) {
        htmlProductos = `
            <div class="table-responsive">
                <table class="table table-sm table-bordered bg-white mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">Cant.</th>
                            <th>Producto / Mezcla</th>
                            <th class="text-right" style="width: 110px;">P. Unitario</th>
                            <th class="text-right" style="width: 120px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        let totalSuma = 0;
        items.forEach(it => {
            const cant = parseInt(it.cantidad || 1);
            const prec = parseFloat(it.precio || 0);
            const sub = parseFloat(it.subtotal || (cant * prec));
            totalSuma += sub;
            htmlProductos += `
                <tr>
                    <td class="text-center font-weight-bold bg-light">${cant}</td>
                    <td>
                        <div class="font-weight-bold text-dark">${it.nombre || 'Concentrado'}</div>
                        ${it.unidad ? `<small class="text-muted">${it.unidad}</small>` : ''}
                    </td>
                    <td class="text-right text-muted">$${prec.toFixed(2)}</td>
                    <td class="text-right font-weight-bold text-dark">$${sub.toFixed(2)}</td>
                </tr>
            `;
        });
        htmlProductos += `
                    </tbody>
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="3" class="text-right text-uppercase">Total Calculado:</td>
                            <td class="text-right text-success h6 mb-0 font-monospace font-weight-bold">$${totalSuma.toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;
    } else if (p.nombrePlan) {
        htmlProductos = `
            <div class="p-3 bg-white rounded border border-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="mr-3 text-success"><i class="fas fa-certificate fa-2x"></i></div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0">${p.nombrePlan}</h6>
                        <div class="small text-muted">Suscripción comercial por 30 días de cobertura nutricional y despacho prioritario.</div>
                    </div>
                </div>
            </div>
        `;
    } else {
        htmlProductos = `
            <div class="p-3 bg-white rounded border text-muted small">
                Compra directa de productos en línea procesada exitosamente en Wompi SV.
            </div>
        `;
    }

    const idTxnMostrar = wompiRetorno.idTransaccion || p.idTransaccionWompi || 'Aprobado en Wompi SV';
    const idEnlaceMostrar = wompiRetorno.idEnlace || p.idEnlaceWompi || '-';
    const codAuthMostrar = wompiRetorno.codigoAutorizacion || 'Transacción Aprobada';
    const formaPagoMostrar = wompiRetorno.formaPago || (p.metodo_pago || 'Tarjeta Débito / Crédito');

    const html = `
        <!-- Fila Superior: 4 Métricas Clave del Pago -->
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <div class="bg-white p-3 rounded shadow-sm border-left-success h-100" style="border-left: 4px solid #10b981;">
                    <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Monto Cobrado</div>
                    <div class="h3 font-weight-bold text-success mb-0 font-monospace">$${parseFloat(p.monto).toFixed(2)} <span class="fs-6 text-muted" style="font-size: 0.9rem;">USD</span></div>
                    <div class="small text-muted">Sin recargos</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="bg-white p-3 rounded shadow-sm border-left-primary h-100" style="border-left: 4px solid #4f46e5;">
                    <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Estado en Pasarela</div>
                    <div class="mb-0 mt-1">${estadoBadge}</div>
                    <div class="small text-muted mt-1">Confirmado por Wompi</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="bg-white p-3 rounded shadow-sm border-left-info h-100" style="border-left: 4px solid #0ea5e9;">
                    <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Código de Autorización</div>
                    <div class="h5 font-weight-bold text-dark mb-0 font-monospace mt-1">${codAuthMostrar}</div>
                    <div class="small text-muted">Aprobación bancaria</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="bg-white p-3 rounded shadow-sm border-left-warning h-100" style="border-left: 4px solid #f59e0b;">
                    <div class="text-xs text-muted text-uppercase font-weight-bold mb-1">Forma de Pago</div>
                    <div class="h6 font-weight-bold text-dark mb-0 mt-1"><i class="fas fa-credit-card text-primary mr-1"></i>${formaPagoMostrar}</div>
                    <div class="small text-muted">Wompi El Salvador</div>
                </div>
            </div>
        </div>

        <!-- Cuadrícula Principal de 2 Columnas -->
        <div class="row">
            <!-- Columna Izquierda: Cliente, Pedido y Wompi IDs -->
            <div class="col-lg-6 mb-3">
                <!-- Tarjeta Datos del Cliente -->
                <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                    <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center">
                        <i class="fas fa-user-circle text-primary mr-2"></i>Información del Cliente y Despacho
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">Nombre / Granja</div>
                                <div class="detail-item-val">${p.nombreCliente || 'Cliente Web'}</div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">Teléfono de Contacto</div>
                                <div class="detail-item-val font-monospace">${p.telefonoCliente ? `<a href="tel:${p.telefonoCliente}"><i class="fas fa-phone-alt mr-1"></i>${p.telefonoCliente}</a>` : '<span class="text-muted font-italic">No especificado</span>'}</div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">Correo Electrónico</div>
                                <div class="detail-item-val">${p.correoCliente ? `<a href="mailto:${p.correoCliente}"><i class="fas fa-envelope mr-1"></i>${p.correoCliente}</a>` : '<span class="text-muted font-italic">No especificado</span>'}</div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">Usuario en Sistema</div>
                                <div class="detail-item-val">${p.username || 'Invitado (Web)'}</div>
                            </div>
                        </div>

                        ${p.idPedidoCreado ? `
                        <div class="alert alert-success d-flex align-items-center justify-content-between mb-0 mt-2 py-2 px-3 rounded">
                            <div>
                                <i class="fas fa-box-open mr-2 text-success"></i>
                                <strong>Orden de Producción Generada:</strong> Pedido #${p.idPedidoCreado}
                            </div>
                            <a href="controllerPedidos.php" class="btn btn-sm btn-success font-weight-bold">
                                Ver Pedido &rarr;
                            </a>
                        </div>` : ''}
                    </div>
                </div>

                <!-- Tarjeta Parámetros Oficiales Wompi -->
                <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                    <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center">
                        <i class="fas fa-fingerprint text-success mr-2"></i>Identificadores y Trazabilidad Wompi
                    </div>
                    <div class="card-body py-3">
                        <div class="mb-3">
                            <div class="detail-item-label">ID Transacción Oficial Wompi</div>
                            <div class="p-2 bg-light rounded font-monospace font-weight-bold text-primary d-flex align-items-center justify-content-between">
                                <span class="text-break">${idTxnMostrar}</span>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 ml-2" onclick="navigator.clipboard.writeText('${idTxnMostrar}'); alert('ID Transacción copiado');" title="Copiar ID">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">ID Enlace Wompi</div>
                                <div class="detail-item-val font-monospace">${idEnlaceMostrar}</div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">Referencia Comercio</div>
                                <div class="detail-item-val font-monospace text-dark">${p.referencia || '-'}</div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">Dirección IP Comprador</div>
                                <div class="detail-item-val font-monospace text-muted small">${p.ip_address || '127.0.0.1'}</div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="detail-item-label">Fecha y Hora Registro</div>
                                <div class="detail-item-val small">${p.fecha_hora}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Desglose de Productos y Payload JSON -->
            <div class="col-lg-6 mb-3">
                <!-- Tarjeta Desglose de Compra -->
                <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                    <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-shopping-bag text-warning mr-2"></i>Desglose de la Compra</span>
                        <span class="badge badge-light border text-muted">${items.length > 0 ? items.length + ' ítems' : (p.nombrePlan || 'Servicio')}</span>
                    </div>
                    <div class="card-body py-3">
                        ${htmlProductos}
                    </div>
                </div>

                <!-- Tarjeta Payload Completo JSON de Wompi -->
                <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                    <div class="card-header bg-white font-weight-bold py-3 text-dark d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-code text-info mr-2"></i>Datos Devueltos por Wompi (JSON Completo)</span>
                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="copiarJsonWompi()">
                            <i class="fas fa-copy mr-1"></i>Copiar JSON
                        </button>
                    </div>
                    <div class="card-body p-2">
                        <div class="json-pre-box" style="max-height: 280px;">${ultimoJsonCargado}</div>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('#modalContenidoDetalle').html(html);
}

function copiarJsonWompi() {
    if (!ultimoJsonCargado) return;
    navigator.clipboard.writeText(ultimoJsonCargado).then(() => {
        alert('¡Datos completos de la transacción copiados al portapapeles!');
    }).catch(() => {
        alert('No se pudo copiar automáticamente. Puedes seleccionarlo directamente desde el recuadro.');
    });
}

function generarHtmlComprobantePago(data) {
    const p = (data && data.pago) ? data.pago : (data || {});
    const meta = p.metadatos_array || {};
    const items = data.items || (meta.items || (p.items_comprados || []));

    // Identificar ID de transacción Wompi
    let idTxn = data.idTransaccion || (p.idTransaccionWompi || '');
    if (!idTxn && meta.wompi_retorno && meta.wompi_retorno.id) {
        idTxn = meta.wompi_retorno.id;
    }
    if (!idTxn && p.descripcion) {
        const matches = String(p.descripcion).match(/Aprobado.*?:\s*([a-zA-Z0-9\-]+)/);
        if (matches && matches[1]) {
            idTxn = matches[1];
        }
    }
    if (!idTxn) {
        idTxn = p.idTransaccion || (p.referencia || 'N/D');
    }

    const formaPago = data.formaPago || (p.metodo_pago || 'Wompi SV (Tarjeta)');
    const idPago = p.idPago || '---';
    const monto = parseFloat(p.monto || 0).toFixed(2);
    const fechaEmision = new Date().toLocaleDateString('es-SV', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    const fechaTxn = p.fecha_hora || '---';
    const nombreCliente = p.nombrePersona ? (p.nombrePersona + ' ' + (p.apellidoPersona || '')).trim() : (p.nombreCliente || (p.NombreCliente || 'Persona General'));
    const telCliente = p.telefonoCliente || (p.telefono || 'Sin teléfono');
    const correoCliente = p.correoCliente || (p.correo || (p.username || 'Sin correo'));
    const estado = (p.estado || 'completado').toUpperCase();
    const esAprobado = estado === 'COMPLETADO' || estado === 'PAGADO' || estado === 'APROBADO';

    let itemsRows = '';
    if (items.length > 0) {
        items.forEach((it, idx) => {
            const cant = parseInt(it.cantidad || 1);
            const prec = parseFloat(it.precio || 0).toFixed(2);
            const sub = parseFloat(it.subtotal || (cant * prec)).toFixed(2);
            itemsRows += `
                <tr>
                    <td style="text-align: center; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">${idx + 1}</td>
                    <td style="padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">
                        <strong style="color: #1e293b; font-size: 13.5px;">${it.nombre || 'Producto / Concentrado'}</strong>
                        <div style="font-size: 11px; color: #64748b;">Línea de Nutrición Animal</div>
                    </td>
                    <td style="text-align: center; font-weight: bold; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">${cant} unid.</td>
                    <td style="text-align: right; color: #475569; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${prec}</td>
                    <td style="text-align: right; font-weight: bold; color: #0f172a; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${sub}</td>
                </tr>
            `;
        });
    } else {
        const concepto = p.nombrePlan || (p.descripcion || 'Pago de Concentrados y Servicios');
        itemsRows = `
            <tr>
                <td style="text-align: center; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">1</td>
                <td style="padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">
                    <strong style="color: #1e293b; font-size: 13.5px;">${concepto}</strong>
                    <div style="font-size: 11px; color: #64748b;">Transacción Comercial Confirmada</div>
                </td>
                <td style="text-align: center; font-weight: bold; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">1 servicio</td>
                <td style="text-align: right; color: #475569; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${monto}</td>
                <td style="text-align: right; font-weight: bold; color: #0f172a; padding: 10px 8px; border-bottom: 1px solid #e2e8f0;">$${monto}</td>
            </tr>
        `;
    }

    return `
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Comprobante de Pago #${idPago} - Concentrados El Gordito</title>
            <style>
                @page { size: letter portrait; margin: 12mm 15mm 12mm 15mm; }
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; color: #1e293b; margin: 0; padding: 20px; font-size: 12.5px; line-height: 1.4; background: #ffffff; }
                .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
                .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; }
                .badge-status { display: inline-block; padding: 3px 8px; font-size: 10px; font-weight: bold; border-radius: 12px; text-transform: uppercase; background: ${esAprobado ? '#dcfce7' : '#fee2e2'}; color: ${esAprobado ? '#166534' : '#991b1b'}; border: 1px solid ${esAprobado ? '#bbf7d0' : '#fecaca'}; }
                .total-card { background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 8px; padding: 12px 16px; margin-top: 12px; margin-left: auto; width: 300px; }
                .signatures { width: 100%; margin-top: 35px; border-collapse: collapse; page-break-inside: avoid; }
                .signature-line { border-top: 1.5px solid #94a3b8; width: 85%; margin: 0 auto 5px auto; }
            </style>
        </head>
        <body>
            <table class="header-table">
                <tr>
                    <td style="width: 58%; vertical-align: top;">
                        <div style="font-size: 20px; font-weight: 800; color: #0f766e; text-transform: uppercase; letter-spacing: -0.5px;">
                            Concentrados El Gordito
                        </div>
                        <div style="font-size: 11px; font-weight: 600; color: #475569; margin-top: 1px;">
                            Nutrición Animal de Alta Calidad • Pasarela Oficial Wompi El Salvador
                        </div>
                        <div style="font-size: 10.5px; color: #64748b; margin-top: 4px; line-height: 1.3;">
                            📍 Km 45 Carretera Panamericana, San Salvador, El Salvador<br>
                            📞 PBX: (503) 2234-5678 • WhatsApp: (503) 7890-5678<br>
                            ✉️ pagos@concentradoselgordito.com • NRC: 245981-4 • NIT: 0614-220921-102-1
                        </div>
                    </td>
                    <td style="width: 42%; vertical-align: top; text-align: right;">
                        <div style="border: 1.5px solid #0f766e; border-radius: 8px; padding: 10px 12px; background: #f0fdfa; display: inline-block; text-align: right; min-width: 220px;">
                            <div style="font-size: 10px; font-weight: bold; color: #0f766e; text-transform: uppercase;">Comprobante Oficial de Pago</div>
                            <div style="font-size: 18px; font-weight: 800; color: #134e4a; margin: 2px 0;">#PAG-${String(idPago).padStart(6, '0')}</div>
                            <div style="font-size: 10.5px; color: #475569;"><strong>Fecha Pago:</strong> ${fechaTxn}</div>
                            <div style="font-size: 10px; color: #64748b;"><strong>Emisión:</strong> ${fechaEmision}</div>
                            <div style="margin-top: 4px;"><span class="badge-status">${esAprobado ? 'PAGO APROBADO' : estado}</span></div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="info-box">
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                            <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 2px;">Datos del Pagador:</div>
                            <div style="font-size: 14px; font-weight: bold; color: #0f172a;">${nombreCliente}</div>
                            <div style="color: #475569; margin-top: 2px;">📞 Tel: ${telCliente}</div>
                            <div style="color: #475569;">✉️ Correo: ${correoCliente}</div>
                        </td>
                        <td style="width: 50%; vertical-align: top; border-left: 1px solid #cbd5e1; padding-left: 10px;">
                            <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 2px;">Trazabilidad de la Transacción:</div>
                            <div style="color: #334155;"><strong>Pasarela:</strong> Wompi El Salvador (Banco Agrícola)</div>
                            <div style="color: #334155;"><strong>Método:</strong> ${formaPago}</div>
                            <div style="color: #334155; word-break: break-all;"><strong>ID Transacción:</strong> <span style="font-family: monospace;">${idTxn}</span></div>
                            <div style="color: #334155;"><strong>Referencia:</strong> <span style="font-family: monospace;">${p.referencia || 'N/D'}</span></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; color: #0f766e; border-bottom: 2px solid #99f6e4; padding-bottom: 3px; margin-bottom: 8px;">
                Concepto y Desglose Liquidado
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: #f1f5f9; color: #334155;">
                        <th style="padding: 7px; text-align: center; width: 35px; border-bottom: 2px solid #cbd5e1;">#</th>
                        <th style="padding: 7px; text-align: left; border-bottom: 2px solid #cbd5e1;">Descripción / Concepto</th>
                        <th style="padding: 7px; text-align: center; width: 100px; border-bottom: 2px solid #cbd5e1;">Cantidad</th>
                        <th style="padding: 7px; text-align: right; width: 110px; border-bottom: 2px solid #cbd5e1;">Precio</th>
                        <th style="padding: 7px; text-align: right; width: 110px; border-bottom: 2px solid #cbd5e1;">Total</th>
                    </tr>
                </thead>
                <tbody>${itemsRows}</tbody>
            </table>

            <div class="total-card">
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <tr>
                        <td style="color: #475569; padding: 2px 0;">Monto Liquidado:</td>
                        <td style="text-align: right; font-weight: 600; color: #1e293b;">$${monto}</td>
                    </tr>
                    <tr>
                        <td style="color: #475569; padding: 2px 0;">Comisión / Recargos:</td>
                        <td style="text-align: right; font-weight: 600; color: #1e293b;">$0.00</td>
                    </tr>
                    <tr style="border-top: 1.5px solid #86efac;">
                        <td style="padding-top: 5px; font-weight: 800; font-size: 14px; color: #14532d;">TOTAL PAGADO:</td>
                        <td style="padding-top: 5px; text-align: right; font-weight: 800; font-size: 16px; color: #15803d;">$${monto} USD</td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 20px; padding: 8px 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; font-size: 10.5px; color: #64748b; line-height: 1.35;">
                <strong>Certificación Electrónica:</strong> Este documento ampara la transacción procesada exitosamente en la plataforma de pagos electrónicos Wompi El Salvador. Conserve este comprobante para cualquier gestión o retiro de producto en planta.
            </div>

            <table class="signatures">
                <tr>
                    <td style="width: 48%; text-align: center; vertical-align: top;">
                        <div class="signature-line"></div>
                        <div style="font-weight: bold; font-size: 11.5px; color: #1e293b;">Autorizado / Tesorería y Caja</div>
                        <div style="font-size: 10px; color: #64748b;">Concentrados El Gordito S.A. de C.V.</div>
                    </td>
                    <td style="width: 4%;"></td>
                    <td style="width: 48%; text-align: center; vertical-align: top;">
                        <div class="signature-line"></div>
                        <div style="font-weight: bold; font-size: 11.5px; color: #1e293b;">Sello Digital de Transacción</div>
                        <div style="font-size: 10px; color: #64748b;">Validación en Línea Wompi SV</div>
                    </td>
                </tr>
            </table>

            <div style="text-align: center; font-size: 9.5px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #f1f5f9; padding-top: 6px;">
                Folio Electrónico Oficial: WMP-PAG-${idPago}-${Date.now().toString().slice(-6)} • Registro Seguro SSL 256-bit
            </div>
        </body>
        </html>
    `;
}

function imprimirModalDetalle() {
    if (!ultimoPagoData) {
        alert('No hay datos de pago cargados para imprimir.');
        return;
    }
    const html = generarHtmlComprobantePago(ultimoPagoData);
    const ventana = window.open('', '_blank', 'height=800,width=900');
    ventana.document.open();
    ventana.document.write(html);
    ventana.document.close();
    ventana.focus();
    setTimeout(() => {
        ventana.print();
    }, 350);
}
