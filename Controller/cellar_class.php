<?php
include_once 'main_class.php';

/******* Clase para las bodegas *********/ 
class cellarClass
{
    // Se consulta información, de los clientes
    function tableDetails($limit, $offset, $bus_1, $bus_2, $campo_1, $campo_2)
    {
        try {

            $db = getDB();

            if ($bus_1 != '' || $bus_2 != '') {
                $stmt = $db->prepare("SELECT bodega.*, ciudad.`ciudad_nombre` 
                FROM bodega INNER JOIN ciudad 
                ON idBodega = bodega.`idBodega`= ciudad.`idCiudad` 
                WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' ORDER BY idBodega DESC LIMIT $limit,$offset ");
            } else {
                $stmt = $db->prepare("SELECT bodega.*, ciudad.`ciudad_nombre` 
                FROM bodega 
                INNER JOIN ciudad ON ciudad.`idCiudad`=`bodega`.`bodega_ciudad`
                ORDER BY idBodega DESC LIMIT $limit,$offset ");
            }

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Registrar bodega
    public function clientRegistration($nombre_new, $ciudad_new, $estado_new, $observacion_new)
    {
        try {

            $idUser = $_SESSION['idUsuario'];

            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
            $st->execute();
            $count = $st->rowCount();
            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO bodega(bodega_nombre,bodega_ciudad,bodega_estado,bodega_observacion) VALUES (:nom,:ciudad,:estado,:observacion)");

                $stmt->bindParam(":nom", $nombre_new, PDO::PARAM_STR);
                $stmt->bindParam(":ciudad", $ciudad_new, PDO::PARAM_INT);
                $stmt->bindParam(":estado", $estado_new, PDO::PARAM_STR);
                $stmt->bindParam(":observacion", $observacion_new, PDO::PARAM_STR);

                $stmt->execute();
                $lastId = $db->lastInsertId();


                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "bodega";
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

    // Eliminar Bodega
    public function deleteClient()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            session_start(['name' => 'SPM']);
            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {

                $id_del = encrypt_decrypt('decrypt', $_GET['id']);
                $db = getDB();

                $stmt = $db->prepare("UPDATE bodega SET bodega_estado='inactivo' WHERE idBodega=:id");
                $stmt->bindParam(":id", $id_del, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Eliminar";
                    $tabla = "bodega";
                    historial($idUser, $date, $id_del, $accion, $tabla);
                }
            }
            return true;
        }
    }

    // Actualizar bodega
    public function updateClient($nombre_up, $ciudad_up, $estado_up, $observacion)
    {
        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();
            if ($count == 1) {
                $stmt = $db->prepare("UPDATE bodega SET bodega_nombre=:nmbd,bodega_ciudad=:ciudad,bodega_estado=:bodega_estado,bodega_observacion=:observacion WHERE idBodega = :id");
                $stmt->bindParam(":nmbd", $nombre_up, PDO::PARAM_STR);
                $stmt->bindParam(":ciudad", $ciudad_up, PDO::PARAM_INT);
                $stmt->bindParam(":bodega_estado", $estado_up, PDO::PARAM_STR);
                $stmt->bindParam(":observacion", $observacion, PDO::PARAM_STR);
                $stmt->bindParam(":id", $_SESSION['idClienteDB'], PDO::PARAM_INT);

                $stmt->execute();

                $lastId = $db->lastInsertId();


                $idUser = $_SESSION['idUsuario'];
                $date = date('Y-m-d H:i:s', time());
                $accion = "Actualizar";
                $tabla = "bodega";

                historial($idUser, $date, $lastId, $accion, $tabla);
                unset($_SESSION['idClienteDB']);

                $db = null;
                return true;
            } else {
                $db = null;
                return false;
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    // Función para registrar la bodega al cliente
    public function clientCellarRegistration($idCliente, $fechaRegistro, $bodegaCliente)
    {

        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO cliente_bodega (cliente_bodega_bodega,cliente_bodega_cliente,cliente_bodega_fechaIngreso) VALUES (:idBodega,:idCliente,:fechaIn)");

                $stmt->bindParam(":idBodega", $bodegaCliente, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $idCliente, PDO::PARAM_STR);
                $stmt->bindParam(":fechaIn", $fechaRegistro, PDO::PARAM_STR);

                $stmt->execute();

                unset($_SESSION['id_client_pro']);
                unset($_SESSION['productosIg']);

                $db = null;

                return true;
            } else {

                $db = null;

                return false;
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    // Función para validar si ya se encuentra registrada la bodega al cliente
    public function searchClientCr($id, $dbC)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT cliente_bodega_cliente,cliente_bodega_bodega  FROM cliente_bodega WHERE cliente_bodega_cliente=:id AND cliente_bodega_bodega=:bd");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":bd", $dbC, PDO::PARAM_STR);
        $stmt->execute();

        $filas = $stmt->rowCount();
        return $filas;
    }

    // Función para eliminar la bodega
    public function deleteClientbd($id)
    {
        session_start(['name' => "SPM"]);
        try {

            $db = getDB();
            $stmt = $db->prepare("DELETE FROM cliente_bodega WHERE idCliente_bodega=:id");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Funcion para generar excel de bodega
    function GenerateExcel($tabla, $id)
    {
        session_start(['name' => "SPM"]);

        $db = getDB();
        $date = date('d-m-Y');
        $time = date('H:i:s');

        if ($id == '') {

            $stmt = $db->prepare("SELECT * FROM $tabla INNER JOIN ciudad ON bodega_ciudad=idCiudad");

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
             </tr>
                 <thead> 
                     <th class='th'>Consecutivo</th>
                     <th class='th'>Nombre</th>
                     <th class='th'>Ciudad</th>
                     <th class='th'>Estado</th>
                     <th class='th'>Observaciones</th>
                 </thead>";


            foreach ($row as $r) {

                $salida .= "
                 <tr>
                     <td> BD-" . $r['idBodega'] . "</td> 
                     <td>" . $r['bodega_nombre'] . "</td> 
                     <td>" . $r['ciudad_nombre'] . "</td> 
                     <td>" . $r['bodega_estado'] . "</td>
                     <td>" . $r['bodega_observacion'] . "</td>
                 </tr>";
            }

            $salida .= "</table>";

            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=bodegas_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        }
    }
}
