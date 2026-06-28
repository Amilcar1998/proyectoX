# 🐾 Concentrados El Gordito

**Sistema de Gestión para Planta de Concentrados**

Sistema web de gestión empresarial para la producción y comercialización de alimentos concentrados para animales (aves, ganado y mascotas). Incluye módulos completos de producción, inventario, facturación, ventas, gestión de usuarios y auditoría.

---

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Tecnologías](#-tecnologías)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración Base de Datos](#-configuración-base-de-datos)
- [Roles de Usuario](#-roles-de-usuario)
- [Módulos del Sistema](#-módulos-del-sistema)
- [Sistema de Auditoría](#-sistema-de-auditoría)
- [Integración Stripe](#-integración-stripe)
- [Arquitectura](#-arquitectura)

---

## ✨ Características Principales

| Módulo | Capacidades |
|--------|-------------|
| **Dashboard** | KPIs en tiempo real, gráficos de pedidos mensuales, stock de materias primas, producción por empleado, facturación, pedidos recientes |
| **Empleados** | CRUD completo, vinculación a cuentas de usuario, asociación a puestos de trabajo |
| **Clientes** | CRUD de clientes, portal de autoservicio para pedidos individuales |
| **Usuarios** | Gestión de cuentas de acceso con roles (Gerente, Empleado, Cliente) |
| **Proveedores** | CRUD de proveedores de materias primas |
| **Pedidos a Proveedor** | Órdenes de compra de insumos con seguimiento |
| **Pedidos (Clientes)** | Gestión de pedidos de clientes, items por receta, cambios de estado |
| **Producción** | Registro de lotes de producción, fechas, estados, trazabilidad por empleado |
| **Inventario** | Control de existencias, detección de stock bajo, histórico de compras |
| **Materia Prima** | Catálogo de ingredientes y concentrados |
| **Facturación** | Facturas a proveedores, detalles de compra (cantidad, precio unitario) |
| **Planes de Pago** | Catálogo de planes con integración Stripe para pagos en línea |
| **Reportes** | Generación de reportes con mPDF/FPDI |
| **Auditoría** | Registro de accesos, sesiones activas, historial de actividades |
| **Recuperación de Cuenta** | Recuperación de contraseña por correo electrónico con token temporal |

---

## 🛠 Tecnologías

| Componente | Tecnología |
|-----------|-----------|
| **Backend** | PHP 7.4+ |
| **Base de Datos** | MariaDB / MySQL |
| **ORM/Driver** | mysqli (prepared statements) |
| **Frontend** | Bootstrap 5, jQuery 3.6, Font Awesome 6 |
| **Tema UI** | SB-Admin 2 |
| **PDF** | mPDF + FPDI |
| **Pagos** | Stripe (Checkout Sessions) |
| **Alertas** | SweetAlert2 |
| **Tablas** | DataTables (con paginación y scroll interno) |

---

## 📁 Estructura del Proyecto

```
proyectoX/
├── controllers/                 # Lógica de controladores (MVC)
│   ├── Sesiones.php            # Guardián de autenticación global
│   ├── controlUser.php         # Login y validación
│   ├── controllerDashboard.php # Dashboard gerencial
│   ├── controllerEmpleado.php  # Gestión de empleados
│   ├── controllerCliente.php   # Gestión de clientes
│   ├── controllerUsuarios.php  # Gestión de cuentas de usuario
│   ├── controllerProveedor.php # Gestión de proveedores
│   ├── controllerPedidos.php   # Pedidos de clientes
│   ├── controllerProduccion.php # Lotes de producción
│   ├── controllerInventario.php # Control de inventario
│   ├── controllerFactura.php   # Facturación
│   ├── controllerPlanPago.php  # Planes de pago (Stripe)
│   ├── controllerReportes.php  # Reportes del sistema
│   └── repo*.php               # Controladores de reportes individuales
│
├── models/                     # Capa de datos y entidades
│   ├── conexion.php            # Conexión singleton a MySQL
│   ├── AuditoriaModel.php      # Modelo de auditoría
│   ├── AuditoriaHelper.php     # Helpers para logging
│   ├── PlanPagoModel.php       # Modelo de planes de pago
│   ├── Entity classes:         # Entidades (Usuario, Empleado, Cliente, etc.)
│   └── Model classes:          # DAOs (UsuarioModel, ModelPedido, etc.)
│
├── views/                      # Vistas y plantillas
│   ├── configuracion.php       # Navbar, menú lateral, layout base
│   ├── login.php               # Login + recuperación de contraseña
│   ├── vista*.php              # Vistas por módulo (19 vistas)
│   └── js/                     # Recursos JS propios
│       ├── demo/datatables-demo.js # Config DataTables global
│       └── translations.js       # Traducciones centralizadas
│
├── db/                         # Scripts de base de datos
│   ├── parametros.php          # Credenciales DB (host, user, pass)
│   ├── conexion.php            # Clase de conexión
│   ├── concentrados.sql        # Schema principal + datos iniciales
│   ├── auditoria.sql           # Tablas de auditoría y sesiones
│   └── seed_data.sql           # Datos de prueba
│
├── stripe/                     # Integración Stripe
│   ├── create-checkout-session.php
│   ├── success.php
│   ├── cancel.php
│   └── webhook.php
│
├── factura/                    # Facturación (legacy)
├── detalleFactura/             # Detalle de compras (legacy)
├── inventario/                 # Lógica de inventario (legacy)
├── materiaPrima/               # Lógica materias primas
├── puesto/                     # Lógica puestos laborales
├── vendor/                     # Composer (mPDF, PSR)
├── mpdf/                       # Librería mPDF
├── index.php                   # Landing page pública
├── reset_password.php          # Controlador de reset de contraseña
└── composer.json
```

---

## 📋 Requisitos

- **PHP**: 7.4 o superior
- **MySQL**: 5.7+ o MariaDB 10.4+
- **Servidor Web**: Apache (XAMPP recomendado)
- **Extensiones PHP**: mysqli, curl (para Stripe), mbstring
- **Composer** (opcional, para dependencias vendor)

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio> proyectoX
cd proyectoX
```

### 2. Configurar la base de datos

```sql
-- En phpMyAdmin o línea de comandos:
mysql -u root -p
CREATE DATABASE IF NOT EXISTS concentrados CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE concentrados;

-- Importar schema principal
SOURCE db/concentrados.sql;

-- Importar tablas de auditoría
SOURCE db/auditoria.sql;
```

### 3. Configurar credenciales

Editar `db/parametros.php`:

```php
<?php
if (!defined('SERVER')) {
    define("SERVER","localhost");
    define("USER","root");
    define("PASSWORD",""); // Cambiar si tienes contraseña
    define("BASE","concentrados");
    define("CHAR","utf8mb4");
}

// Stripe (opcional - para pagos en línea)
define("STRIPE_SECRET_KEY", "sk_test_tu_clave_secreta");
define("STRIPE_PUBLISHABLE_KEY", "pk_test_tu_clave_publica");
define("STRIPE_WEBHOOK_SECRET", "whsec_tu_webhook_secret");
```

### 4. Acceder a la aplicación

```
http://localhost/proyectoX/          # Landing page
http://localhost/proyectoX/views/login.php  # Login
```

---

## 👤 Roles de Usuario

| Rol | ID | Descripción | Acceso |
|-----|-----|-------------|--------|
| Gerente | 3 | Administrador del sistema | Acceso total a todos los módulos |
| Empleado | 1 | Personal operativo | Producción, inventario, pedidos |
| Cliente | 2 | Cliente registrado | Portal de pedidos individuales |
| Cliente Individual | — | Acceso self-service | Portal individual para pedidos |

---

## 🔐 Módulos del Sistema

### Dashboard
- Indicadores clave: total pedidos, facturación, stock bajo, empleados
- Gráficos: pedidos mensuales, stock de materias primas, producción por empleado, facturación
- Tabla de pedidos recientes con paginación

### Producción
- Registro de lotes de producción
- Estados: No trabajado → En proceso → Terminado
- Vinculación a pedidos y empleados
- Trazabilidad completa

### Inventario
- Control de existencias por materia prima
- Alertas de stock bajo
- Histórico de compras con precios

### Pedidos
- Flujo: Cliente → Pedido → Producción → Entrega
- Estados: No trabajado / En proceso / Terminado
- Desglose por receta y materias primas

### Facturación
- Emisión de facturas a proveedores
- Detalle de compras con cantidad y precio unitario
- Integridad referencial

### Auditoría (Nuevo)
- Tabla `auditoria`: login, logout, acceso a vistas, CRUD, errores
- Tabla `sesiones_activas`: sesiones en vivo con última actividad
- Tabla `plan_pago`: catálogo de planes de pago
- Tabla `usuario_plan_pago`: historial de compras de planes

---

## 🔍 Sistema de Auditoría

El sistema registra automáticamente:

| Evento | Tabla | Campos |
|--------|-------|--------|
| Inicio de sesión | `auditoria` | usuario, IP, user-agent, fecha_hora |
| Cierre de sesión | `auditoria` | usuario, IP, módulo |
| Acceso a módulo | `auditoria` | usuario, módulo, descripción |
| CRUD | `auditoria` | tipo_evento, módulo, descripción |

**Vistas de reporte de auditoría:**
- `reporteSesionesActivas.php` — Usuarios conectados en tiempo real
- `reporteAuditoria.php` — Historial filtrado por fecha
- `reporteActividadModulos.php` — Uso de módulos del sistema

---

## 💳 Integración Stripe

Para habilitar pagos en línea:

1. Crear cuenta en [Stripe Dashboard](https://dashboard.stripe.com/)
2. Obtener las llaves desde **Developers > API Keys**
3. Configurar en `db/parametros.php`
4. Configurar Webhook en Stripe Dashboard apuntando a:
   ```
   https://tu-dominio/stripe/webhook.php
   ```
5. El evento a escuchar es `checkout.session.completed`

---

## 🗄 Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Cuentas de acceso al sistema |
| `rol` | Roles: Gerente, Empleado, Cliente |
| `empleado` | Datos de empleados |
| `cliente` | Datos de clientes |
| `proveedor` | Datos de proveedores |
| `puesto` | Puestos laborales |
| `materiaprima` | Materias primas / ingredientes |
| `receta` | Recetas de concentrados |
| `pedido` | Pedidos de clientes |
| `produccion` | Lotes de producción |
| `inventario` | Control de existencias |
| `factura` | Facturas a proveedores |
| `detallecompra` | Detalle de compras |
| `estadopedido` | Estados de pedido |
| `detallepedido` | Items de pedido |
| `detallereceta` | Consumo de materias por receta |
| `salidas` | Salidas de producción |
| `recuperacion_pass` | Tokens de recuperación de contraseña |
| `auditoria` | Log de auditoría |
| `sesiones_activas` | Sesiones activas |
| `plan_pago` | Planes de pago |
| `usuario_plan_pago` | Compras de planes por usuario |

---

## 🧹 Notas de Mantenimiento

- **Passwords**: Se usa `sha1()` en el proyecto original. Considerar migrar a `password_hash()` / `password_verify()` de PHP.
- **Sesiones**: Las claves de sesión son `s1` (empleado/gerente), `s2` (cliente), `c1` (cliente individual).
- **Formato de fechas**: El sistema maneja `DATETIME` en base de datos y formatea a `d/m/Y` para visualización.
- **Variables de sesión**: `$_SESSION['s1']`, `$_SESSION['s2']`, `$_SESSION['c1']` almacenan el username/correo.

---

## 📄 Licencia

Sistema propiedad de **Concentrados El Gordito**. Todos los derechos reservados.

---

**Desarrollado con** 💙 para la gestión eficiente de concentrados animales.
