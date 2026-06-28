-- Seed data para Concentrados El Gordito
-- Ejecutar despues de concentrados.sql
-- IDs respetan los AUTO_INCREMENT actuales

INSERT INTO rol (id_Rol, nombreRol) VALUES 
(1, 'Gerente'),
(2, 'Empleado'),
(3, 'Cliente'),
(4, 'Admin')
ON DUPLICATE KEY UPDATE nombreRol = nombreRol;

INSERT INTO puesto (idPuesto, nombrePuesto) VALUES 
(1, 'Gerente General'),
(2, 'Jefe de Produccion'),
(3, 'Supervisor'),
(4, 'Operario'),
(5, 'Ventas'),
(6, 'Almacenista'),
(7, 'Contador'),
(8, 'Compras'),
(9, 'Calidad'),
(10, 'Logistica'),
(11, 'RRHH'),
(12, 'Sistemas')
ON DUPLICATE KEY UPDATE nombrePuesto = nombrePuesto;

INSERT INTO proveedor (idProveedor, nombreProveedor, contacto, NIT, correoP, telefono) VALUES 
(391237, 'Agroindustrial del Norte', 'Juan Perez', '1234567', 'ventas@agronorte.com', '555-0101'),
(391238, 'Distribuidora Maíz SA', 'Maria Lopez', '7654321', 'pedidos@maizsa.com', '555-0102'),
(391239, 'Proveedora de Harinas Central', 'Carlos Ruiz', '9876543', 'info@harinacentral.com', '555-0103'),
(391240, 'Soya Express', 'Ana Torres', '4567890', 'contacto@soyaexpress.com', '555-0104'),
(391241, 'Insumos Agricolas El Campo', 'Luis Mendez', '3210987', 'ventas@elcampo.com', '555-0105'),
(391242, 'Trigo y Derivados SA', 'Patricia Rojas', '6543210', 'info@trigoderivados.com', '555-0106'),
(391243, 'Distribuidora de Granos', 'Roberto Diaz', '8901234', 'ventas@granosdist.com', '555-0107'),
(391244, 'Proveedora de Empaques Unidos', 'Elena Vasquez', '2345678', 'info@empaquesunidos.com', '555-0108')
ON DUPLICATE KEY UPDATE nombreProveedor = nombreProveedor;

INSERT INTO materiaprima (idMateriaPrima, NombreMP) VALUES 
(1, 'Maiz Amarillo'),
(2, 'Maiz Blanco'),
(3, 'Soya'),
(4, 'Harina'),
(5, 'Maicillo'),
(6, 'Trigo'),
(7, 'Avena')
ON DUPLICATE KEY UPDATE NombreMP = NombreMP;

INSERT INTO usuarios (idUsuario, username, pass, id_Rol) VALUES 
(391293, 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1),
(391294, 'jperez', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391295, 'mlopez', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391296, 'cruz', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391297, 'atorres', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391298, 'lmendez', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391299, 'proveedor1', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(391300, 'cliente1', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(391301, 'cliente2', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(391302, 'cliente3', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3)
ON DUPLICATE KEY UPDATE username = username;

INSERT INTO empleado (idEmpleado, nombreEmp, apellido, genero, idPuesto, idUsuario) VALUES 
(391309, 'Juan', 'Perez', 'M', 1, 391294),
(391310, 'Maria', 'Lopez', 'F', 2, 391295),
(391311, 'Carlos', 'Ruiz', 'M', 4, 391296),
(391312, 'Ana', 'Torres', 'F', 3, 391297),
(391313, 'Luis', 'Mendez', 'M', 5, 391298),
(391314, 'Patricia', 'Rojas', 'F', 6, 391299),
(391315, 'Roberto', 'Diaz', 'M', 4, 391300),
(391316, 'Elena', 'Vasquez', 'F', 7, 391301),
(391317, 'Diego', 'Morales', 'M', 4, 391302),
(391318, 'Sandra', 'Gomez', 'F', 8, 391303)
ON DUPLICATE KEY UPDATE nombreEmp = nombreEmp;

INSERT INTO cliente (idCliente, NombreCliente, apellidosCliente, telefono, edad, genero, idUsuario) VALUES 
(391211, 'Distribuidora', 'El Sol', '555-1001', 35, 'M', 391299),
(391212, 'Tiendas', 'La Esquina', '555-1002', 28, 'F', 391300),
(391213, 'Supermercado', 'Norte', '555-1003', 42, 'M', 391301),
(391214, 'Comercial', 'Centro', '555-1004', 31, 'F', 391302),
(391215, 'Mayorista', 'Sur', '555-1005', 38, 'M', 391303)
ON DUPLICATE KEY UPDATE NombreCliente = NombreCliente;

INSERT INTO estadopedido (idEstadoPedido, nombreEstado) VALUES 
(1, 'Pendiente'),
(2, 'En Proceso'),
(3, 'Completado'),
(4, 'Cancelado')
ON DUPLICATE KEY UPDATE nombreEstado = nombreEstado;

INSERT INTO receta (idReceta, nombreReceta, PrecioUnitario) VALUES 
(1, 'Mezcla Pollo Inicio', 1.25),
(2, 'Mezcla Pollo Engorde', 1.35),
(3, 'Mezcla Cerdo Inicio', 1.15),
(4, 'Mezcla Cerdo Engorde', 1.30)
ON DUPLICATE KEY UPDATE nombreReceta = nombreReceta;

INSERT INTO inventario (idInventario, idMateriaPrima, Existencias) VALUES 
(1, 1, 1500),
(2, 2, 2000),
(3, 3, 800),
(4, 4, 2500),
(5, 5, 1200),
(6, 6, 3000),
(7, 7, 500)
ON DUPLICATE KEY UPDATE idMateriaPrima = idMateriaPrima;

INSERT INTO pedido (idPedido, fechaPedido, idCliente, idEstadoPedido) VALUES 
(391178, '15/06/2026', 391211, 2),
(391179, '18/06/2026', 391212, 1),
(391180, '20/06/2026', 391213, 3),
(391181, '22/06/2026', 391214, 1),
(391182, '25/06/2026', 391215, 2)
ON DUPLICATE KEY UPDATE fechaPedido = fechaPedido;

INSERT INTO detallepedido (idDetallePedido, cantidad, idReceta, IdPedido) VALUES 
(391214, 100, 1, 391178),
(391215, 80, 2, 391178),
(391216, 150, 3, 391179),
(391217, 200, 1, 391180),
(391218, 120, 4, 391180),
(391219, 90, 2, 391181),
(391220, 50, 1, 391182)
ON DUPLICATE KEY UPDATE cantidad = cantidad;

INSERT INTO detallereceta (idDetalleReceta, idMateriaPrima, cantidaSa, fechaSa, idInventario, IdReceta) VALUES 
(1, 1, 500, '10/06/2026', 1, 1),
(2, 2, 300, '10/06/2026', 2, 1),
(3, 4, 200, '10/06/2026', 4, 1),
(4, 3, 400, '12/06/2026', 3, 2),
(5, 5, 250, '12/06/2026', 5, 2),
(6, 6, 600, '14/06/2026', 6, 3),
(7, 4, 150, '14/06/2026', 4, 3),
(8, 1, 350, '15/06/2026', 1, 4),
(9, 3, 200, '15/06/2026', 3, 4),
(10, 6, 400, '16/06/2026', 6, 4)
ON DUPLICATE KEY UPDATE cantidaSa = cantidaSa;

INSERT INTO produccion (idProduccion, fechaP, estadoP, idPedido, idEmpleado) VALUES 
(391040, '16/06/2026', 'Completado', 391178, 391309),
(391041, '19/06/2026', 'En Proceso', 391179, 391310),
(391042, '21/06/2026', 'Completado', 391180, 391311),
(391043, '23/06/2026', 'Pendiente', 391181, 391312),
(391044, '26/06/2026', 'En Proceso', 391182, 391313)
ON DUPLICATE KEY UPDATE fechaP = fechaP;

INSERT INTO factura (idFacturaMP, numeroFac, Monto, Fecha, idProveedor, idEmpleado) VALUES 
(23233, 'FAC-001', 2500.00, '01/06/2026', 391237, 391309),
(23234, 'FAC-002', 3200.50, '05/06/2026', 391238, 391310),
(23235, 'FAC-003', 1800.75, '08/06/2026', 391237, 391311),
(23236, 'FAC-004', 4100.00, '10/06/2026', 391239, 391312),
(23237, 'FAC-005', 950.25, '12/06/2026', 391240, 391313),
(23238, 'FAC-006', 2800.00, '14/06/2026', 391241, 391309),
(23239, 'FAC-007', 1500.50, '15/06/2026', 391242, 391310),
(23240, 'FAC-008', 3600.75, '18/06/2026', 391238, 391311)
ON DUPLICATE KEY UPDATE Monto = Monto;

INSERT INTO detallecompra (idDetalleCompra, idMateriaPrima, cantidadMP, precioMP, idFacturaMP) VALUES 
(1, 1, 500, 1.20, 23233),
(2, 4, 200, 0.85, 23233),
(3, 2, 600, 1.10, 23234),
(4, 3, 300, 1.45, 23235),
(5, 5, 400, 0.95, 23236),
(6, 6, 250, 1.30, 23237),
(7, 1, 350, 1.25, 23238),
(8, 4, 180, 0.90, 23239),
(9, 3, 150, 1.50, 23240)
ON DUPLICATE KEY UPDATE cantidadMP = cantidadMP;

INSERT INTO pedidoproveedor (idPedido, idProveedor, idEmpleado, idMateriaPrima, fecha, cantidadMP, monto, precioMP) VALUES 
(391250, 391237, 391309, 1, '02/06/2026', 500, 600.00, 1.20),
(391251, 391238, 391310, 2, '04/06/2026', 600, 660.00, 1.10),
(391252, 391237, 391311, 4, '06/06/2026', 300, 255.00, 0.85),
(391253, 391239, 391312, 3, '09/06/2026', 400, 580.00, 1.45),
(391254, 391240, 391313, 5, '11/06/2026', 350, 332.50, 0.95),
(391255, 391241, 391309, 6, '13/06/2026', 450, 585.00, 1.30),
(391256, 391242, 391310, 1, '16/06/2026', 200, 250.00, 1.25),
(391257, 391238, 391311, 4, '19/06/2026', 280, 252.00, 0.90),
(391258, 391239, 391312, 3, '21/06/2026', 150, 225.00, 1.50),
(391259, 391237, 391313, 2, '24/06/2026', 400, 440.00, 1.10)
ON DUPLICATE KEY UPDATE monto = monto;

INSERT INTO salidas (idSalida, idProduccion, NombreCliente, fecha, montoTotal, estadoSalida) VALUES 
(1, 391040, 'Distribuidora El Sol', '17/06/2026', 125.00, 'Entregado'),
(2, 391042, 'Supermercado Norte', '22/06/2026', 270.00, 'Entregado'),
(3, 391044, 'Mayorista Sur', '27/06/2026', 195.00, 'Pendiente')
ON DUPLICATE KEY UPDATE montoTotal = montoTotal;
