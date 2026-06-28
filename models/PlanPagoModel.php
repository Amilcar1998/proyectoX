<?php
include "../db/conexion.php";

if (!class_exists('PlanPagoModel')) {
    class PlanPagoModel extends Conexion
    {
        public function __construct()
        {
            parent::__construct();
        }

        function getPlanes()
        {
            $res = $this->con->query("SELECT * FROM plan_pago ORDER BY activo DESC, monto ASC");
            $r = array();
            while ($row = $res->fetch_assoc()) {
                $r[] = $row;
            }
            return $r;
        }

        function getPlanPorId($id)
        {
            $stmt = $this->con->prepare("SELECT * FROM plan_pago WHERE idPlanPago = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row;
        }

        function insertarPlan($nombrePlan, $descripcion, $monto, $duracionDias)
        {
            $stmt = $this->con->prepare(
                "INSERT INTO plan_pago (nombrePlan, descripcion, monto, duracion_dias) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssdi", $nombrePlan, $descripcion, $monto, $duracionDias);
            $stmt->execute();
            $stmt->close();
        }

        function modificarPlan($idPlanPago, $nombrePlan, $descripcion, $monto, $duracionDias, $activo)
        {
            $stmt = $this->con->prepare(
                "UPDATE plan_pago SET nombrePlan = ?, descripcion = ?, monto = ?, duracion_dias = ?, activo = ? WHERE idPlanPago = ?"
            );
            $stmt->bind_param("ssdiii", $nombrePlan, $descripcion, $monto, $duracionDias, $activo, $idPlanPago);
            $stmt->execute();
            $stmt->close();
        }

        function eliminarPlan($idPlanPago)
        {
            $stmt = $this->con->prepare("DELETE FROM plan_pago WHERE idPlanPago = ?");
            $stmt->bind_param("i", $idPlanPago);
            $stmt->execute();
            $stmt->close();
        }
    }
}
