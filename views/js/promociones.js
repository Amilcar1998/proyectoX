/**
 * promociones.js - Lógica interactiva para la gestión de promociones, modales y validaciones
 * Concentrados El Gordito
 */

$(document).ready(function() {
    const config = window.promocionesConfig || {};
    const promocionActivaActual = config.promocionActivaActual || null;

    if ($('#dataTable').length) {
        $('#dataTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
            }
        });
    }

    if ($('#dataTableHistorial').length) {
        $('#dataTableHistorial').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
            },
            order: [[0, 'desc']]
        });
    }

    if (config.mensaje) {
        Swal.fire({
            icon: config.tipoMensaje || 'info',
            title: config.tipoMensaje === 'success' ? '¡Operación Exitosa!' : 'Aviso',
            text: config.mensaje,
            confirmButtonColor: '#059669'
        });
    }

    // Interceptar envío del formulario de promoción para validar promoción única
    $('#formPromocion').on('submit', function(e) {
        e.preventDefault();
        const idActual = parseInt($('#promoIdReceta').val()) || 0;
        const nombreActual = $('#promoNombreReceta').val();
        const form = this;

        if (promocionActivaActual && parseInt(promocionActivaActual.idReceta) !== idActual) {
            Swal.fire({
                title: '⚠️ ¿Reemplazar promoción activa?',
                html: `Actualmente ya existe una promoción activa para <b>"${promocionActivaActual.nombreReceta}"</b> (Oferta: $${parseFloat(promocionActivaActual.PrecioUnitario).toFixed(2)} USD).<br><br><b>Regla del Sistema:</b> Solo puede haber <u>una única promoción activa</u> a la vez.<br><br>Si continúas, la promoción anterior será <b>cancelada automáticamente y pasará al histórico</b>, restaurando su precio regular, y se activará la nueva oferta para <b>"${nombreActual}"</b>.<br><br>¿Deseas confirmar el reemplazo?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, cancelar anterior y activar nueva',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            form.submit();
        }
    });

    $('#promoPrecioOferta, #promoPrecioRegular').on('input change', function() {
        calcularCalculoPromo();
    });
});

// Abrir Modal de Nivelación
function abrirModalNivelacion(id, nombre, precioActual) {
    $('#nivelacionIdReceta').val(id);
    $('#nivelacionNombreReceta').val(nombre);
    $('#nivelacionPrecioActual').val(Number(precioActual).toFixed(2));
    $('#nivelacionNuevoPrecio').val(Number(precioActual).toFixed(2));
    $('#modalNivelacion').modal('show');
}

// Abrir Modal de Promoción
function abrirModalPromocion(id, nombre, precioBase, precioVenta, fInicio, fFin) {
    $('#promoIdReceta').val(id);
    $('#promoNombreReceta').val(nombre);
    $('#promoPrecioRegular').val(Number(precioBase).toFixed(2));
    $('#promoPrecioOferta').val(precioVenta < precioBase ? Number(precioVenta).toFixed(2) : (precioBase * 0.85).toFixed(2));

    // Configurar fechas por defecto si vienen vacías
    const now = new Date();
    const nowFormatted = now.toISOString().slice(0, 16);
    const in7Days = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
    const in7DaysFormatted = in7Days.toISOString().slice(0, 16);

    if (fInicio) {
        $('#promoFechaInicio').val(fInicio.replace(' ', 'T').slice(0, 16));
    } else {
        $('#promoFechaInicio').val(nowFormatted);
    }

    if (fFin) {
        $('#promoFechaFin').val(fFin.replace(' ', 'T').slice(0, 16));
    } else {
        $('#promoFechaFin').val(in7DaysFormatted);
    }

    calcularCalculoPromo();
    $('#modalPromocion').modal('show');
}

// Cálculo dinámico de % de descuento y ahorro
function calcularCalculoPromo() {
    const regular = parseFloat($('#promoPrecioRegular').val()) || 0;
    const oferta = parseFloat($('#promoPrecioOferta').val()) || 0;

    if (regular > oferta && oferta > 0) {
        const ahorro = regular - oferta;
        const pct = Math.round((ahorro / regular) * 100);
        $('#badgeCalculoDescuento').text('-' + pct + '%');
        $('#montoCalculoAhorro').text('$' + ahorro.toFixed(2) + ' USD');
    } else {
        $('#badgeCalculoDescuento').text('0%');
        $('#montoCalculoAhorro').text('$0.00 USD');
    }
}

// Cancelar promoción anticipada
function cancelarPromocion(id, nombre) {
    Swal.fire({
        title: '¿Finalizar promoción?',
        text: 'La oferta de "' + nombre + '" será desactivada y volverá a su precio base regular.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, finalizar oferta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#cancelarIdReceta').val(id);
            $('#formCancelarPromo').submit();
        }
    });
}
