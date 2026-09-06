-- ==============================================================================
-- SENTENCIAS SQL DE ACTUALIZACIÓN: concentrados.plan_pago
-- ==============================================================================
-- Las descripciones utilizan ';' para que el sistema genere automáticamente
-- viñetas elegantes con iconos en las tarjetas de la Landing Page.
-- ==============================================================================

USE concentrados;

-- 1. Plan Básico Granjero (30 Días - $150.00)
UPDATE plan_pago 
SET 
    nombrePlan = 'Plan Básico Granjero',
    descripcion = 'Acceso al portal de pedidos 24/7; Despacho regular de concentrados; Historial de compras y facturación; Soporte técnico por correo y WhatsApp',
    monto = 150.00,
    duracion_dias = 30,
    activo = 1
WHERE idPlanPago = 1;

-- 2. Plan Premium Productores (30 Días - $300.00)
UPDATE plan_pago 
SET 
    nombrePlan = 'Plan Premium Productores',
    descripcion = 'Todo lo del Plan Básico; Descuentos del 8% por volumen programado; Asesoría nutricional y balanceo de dietas; Prioridad en cola de despacho; Soporte directo vía WhatsApp 24/7',
    monto = 300.00,
    duracion_dias = 30,
    activo = 1
WHERE idPlanPago = 2;

-- 3. Plan Enterprise Agroindustrial (30 Días - $800.00)
UPDATE plan_pago 
SET 
    nombrePlan = 'Plan Enterprise Agroindustrial',
    descripcion = 'Formulaciones y mezclas exclusivas a la medida; Entregas programadas a nivel nacional; Línea de crédito comercial preferencial; Gestor de cuenta y visitas técnicas en granja; Trazabilidad completa y control de laboratorio',
    monto = 800.00,
    duracion_dias = 30,
    activo = 1
WHERE idPlanPago = 3;

-- 10. Plan Trimestral Ahorro Avícola (90 Días - $420.00)
UPDATE plan_pago 
SET 
    nombrePlan = 'Plan Trimestral Ahorro Avícola',
    descripcion = 'Abastecimiento garantizado para ciclos de engorde y postura; Tarifa fija protegida durante 90 días; Entregas mensuales de fórmulas frescas; Asesoría técnica en conversión alimenticia',
    monto = 420.00,
    duracion_dias = 90,
    activo = 1
WHERE idPlanPago = 10;

-- 11. Plan Semestral Nutrición Porcina (180 Días - $850.00)
UPDATE plan_pago 
SET 
    nombrePlan = 'Plan Semestral Nutrición Porcina',
    descripcion = 'Programa intensivo de inicio, crecimiento y engorde; Acompañamiento zootécnico semestral; Análisis bromatológico periódico de raciones; Despacho quincenal a granja; Descuento preferencial del 12%',
    monto = 850.00,
    duracion_dias = 180,
    activo = 1
WHERE idPlanPago = 11;

-- 12. Plan Anual Cooperativas y Asociados (365 Días - $1600.00)
UPDATE plan_pago 
SET 
    nombrePlan = 'Plan Anual Cooperativas y Asociados',
    descripcion = 'Cobertura integral para múltiples centros de producción; Precios mayoristas de fábrica todo el año; Auditorías nutricionales y de rendimiento en campo; Financiamiento y crédito rotativo comercial; Gestor VIP asignado',
    monto = 1600.00,
    duracion_dias = 365,
    activo = 1
WHERE idPlanPago = 12;

-- Verificar los cambios aplicados:
SELECT idPlanPago, nombrePlan, descripcion, monto, duracion_dias, activo FROM plan_pago ORDER BY idPlanPago ASC;
