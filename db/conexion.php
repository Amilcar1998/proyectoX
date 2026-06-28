<?php
   require_once 'parametros.php';
   if (!class_exists('Conexion')) {
       class Conexion{
          protected $con;
          function __construct(){
             $this->con=new mysqli(SERVER,USER,PASSWORD,BASE);
             if ($this->con->connect_error) {
                throw new mysqli_sql_exception("Connection failed: " . $this->con->connect_error);
             }
             $this->con->set_charset(CHAR);
          }
          public function getConnection(): mysqli {
             return $this->con;
          }
          public function getNombreUsuario(): string {
             $nombre = '';
             if (isset($_SESSION['s1'])) {
                 $correo = $this->con->real_escape_string($_SESSION['s1']);
                 $res = $this->con->query("SELECT e.nombreEmp, e.apellido FROM empleado e INNER JOIN usuarios u ON e.idUsuario = u.idUsuario WHERE u.username = '$correo' LIMIT 1");
                 if ($res && $fila = $res->fetch_assoc()) {
                     $nombre = $fila['nombreEmp'] . ' ' . $fila['apellido'];
                 }
             } elseif (isset($_SESSION['s2'])) {
                 $correo = $this->con->real_escape_string($_SESSION['s2']);
                 $res = $this->con->query("SELECT NombreCliente, apellidosCliente FROM cliente INNER JOIN usuarios ON cliente.idUsuario = usuarios.idUsuario WHERE usuarios.username = '$correo' LIMIT 1");
                 if ($res && $fila = $res->fetch_assoc()) {
                     $nombre = $fila['NombreCliente'] . ' ' . $fila['apellidosCliente'];
                 }
             } elseif (isset($_SESSION['c1'])) {
                 $correo = $this->con->real_escape_string($_SESSION['c1']);
                 $res = $this->con->query("SELECT NombreCliente, apellidosCliente FROM cliente INNER JOIN usuarios ON cliente.idUsuario = usuarios.idUsuario WHERE usuarios.username = '$correo' LIMIT 1");
                 if ($res && $fila = $res->fetch_assoc()) {
                     $nombre = $fila['NombreCliente'] . ' ' . $fila['apellidosCliente'];
                 }
             }
             return $nombre;
          }
       }
   }
?> 
