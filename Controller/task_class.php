<?php
include_once 'main_class.php';

class taskClass
{

    function tableDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {
        try {
            $db = getDB();
            $cond = $table . '_estado';

            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                $stmt = $db->prepare("SELECT * FROM $table  WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND $cond='activo' LIMIT $limit,$offset");
            } else {
                $stmt = $db->prepare("SELECT * FROM $table WHERE $cond='activo' LIMIT $limit,$offset");
            }

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    // Crear cliente

    public function taskRegistration($descripCorta, $entrada, $despacho, $prioridad, $idProducto, $cantidad, $bodega)
    {
        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO tarea (tarea_descripCorta,tarea_usuario,tarea_idEntrada,tarea_idDespacho,tarea_prioridad,tarea_idProducto,tarea_novedad,tarea_idBodega) VALUES (:corta,:usuario,:idEntrada,:idDespacho,:prioridad,:producto,:cantidad,:bodega)");
                $stmt->bindParam(":corta", $descripCorta, PDO::PARAM_STR);
                $stmt->bindParam(":usuario", $_SESSION['idUsuario'], PDO::PARAM_STR);
                $stmt->bindParam(":idEntrada", $entrada, PDO::PARAM_STR);
                $stmt->bindParam(":idDespacho", $despacho, PDO::PARAM_STR);
                $stmt->bindParam(":prioridad", $prioridad, PDO::PARAM_STR);
                $stmt->bindParam(":producto", $idProducto, PDO::PARAM_STR);
                $stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);


                $stmt->execute();
                $lastId = $db->lastInsertId();
                $consecutivo = 'TR-' . $lastId;

                $stmt = $db->prepare("UPDATE tarea SET tarea_consecutivo=:consecutivo WHERE idTarea=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "tarea";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

                $db = null;

                return $stmt;
            } else {
                $db = null;

                return false;
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }


    // Función para generar un excel
    function GenerateExcel($tabla, $id)
    {
        session_start(['name' => "SPM"]);
        $db = getDB();
        $date = date('d-m-Y');
        $time = date('H:i:s');
        $estado = $tabla . '_estado';


        $stmt = $db->prepare("SELECT * FROM $tabla as tb 
                LEFT JOIN usuario ON idUsuario=tarea_usuario 
                INNER JOIN producto ON idProducto=tarea_idProducto
                INNER JOIN bodega ON idBodega=tarea_idBodega
                WHERE $estado='activo' AND tarea_novedad>0
                ORDER BY tarea_consecutivo");

        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $salida = "";
        $salida .= "<table>";
        $salida .= "

            <style> 
                .th{
                    background-color: #dcdcdc ; 
                    color: #000000;
                }
                .invB{
                    background-color: #e3f700 ; 
                }
                .dispo{
                    background-color: #50b743 ; 
                    border:0,5px solid #fff;
                }
                .block{
                    background-color: #ea8605 ; 
                }
                td{
                    text-align: center;
                }

            </style>
            <tr>
                <th class='th' >Bodega :</th>
                <th>" . $row[0]['bodega_nombre'] . "</th>
            </tr>

            <tr>
            </tr>

            <tr>
                <td><b>FECHA : </b></td>
                <td>" . $date . "</td>
            </tr>

            <tr>
                <td><b>HORA :</b> </td>
                <td>" . $time . "</td>
            </tr>

            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class='th'>TAREAS</th>
                <th></th>
            </tr>
                <thead> 
                    <th class='th'>Consecutivo</th>
                    <th class='th'>Nombre</th>
                    <th class='th'>Código</th>
                    <th class='th'>Prioridad</th>
                    <th class='th'>Cantidad bloqueada </th>
                    <th class='th'>Descripción </th>
                    <th class='th'>Usuario</th>
                </thead>";


        foreach ($row as $r) {
            if (!empty($r['despacho_consecutivo'])) {
                $origen = $r['despacho_consecutivo'];
            } elseif (!empty($r['entrada_consecutivo'])) {
                $origen = $r['entrada_consecutivo'];
            }

            $salida .= "
                <tr>
                    <td>" . $r['tarea_consecutivo'] . "</td> 
                    <td>" . $r['producto_nombre'] . $r['usuario_apellido'] . "</td> 
                    <td>" . $r['producto_codigo'] . "</td> 
                    <td>" . $r['tarea_prioridad'] . "</td> 
                    <td>" . $r['producto_cantidadBlock'] . "</td>
                    <td>" . $r['tarea_descripCorta'] . "</td>
                    <td>" . $r['usuario_nombre']  . $r['usuario_apellido']  . "</td>
                </tr>";
        }

        $salida .= "</table>";

        header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

        header("Content-Disposition: attachment; filename=tareas_" . time() . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        return $salida;
    }
}
