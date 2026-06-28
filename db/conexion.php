<?php
   require 'parametros.php';
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
   }
?> 
