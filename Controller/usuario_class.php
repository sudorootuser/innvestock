
<?php

include_once 'main_class.php';

class usuarioClass
{

    // Función para obtener la data del usuario
    function tableDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {
        try {

            $db = getDB();
            $idTabla = "id" . ucfirst($table);
            $cond = $table . '_estado';

            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                $stmt = $db->prepare("SELECT*
                FROM $table INNER JOIN bodega 
                ON idBodega=usuario_idBodega  
                WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND $cond='activo'  ORDER BY $idTabla DESC LIMIT $limit,$offset ");
            } else {
                $stmt = $db->prepare("SELECT * 
                FROM $table INNER JOIN bodega 
                ON idBodega=usuario_idBodega  
                WHERE $cond='activo'
                ORDER BY $idTabla DESC LIMIT $limit,$offset ");
            }

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Crear usuario
    public function clientRegistration($tpId_new, $nDocument_new, $fecha_new, $telefono_new, $nombre_new, $apellido_new, $correo_new, $direccion_new, $tpBodega_up, $tpRol_up)
    {
        try {


            $idUser = $_SESSION['idUsuario'];

            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
            $st->execute();
            $count = $st->rowCount();
            if ($count >= 1) {

                $pass = generate_string();

                $password = encrypt_decrypt('encrypt', $pass);

                $stmt = $db->prepare("INSERT INTO usuario(usuario_tDocument,usuario_documento,usuario_fecha,usuario_telefono,usuario_nombre,usuario_apellido,usuario_correo,usuario_password,usuario_direccion,usuario_idBodega,usuario_idRol) VALUES (:tpDocument,:nDocument,:fecha,:phone,:nombre,:apellido,:correo,:pass,:direccion,:bodega,:rol)");

                $stmt->bindParam(":tpDocument", $tpId_new, PDO::PARAM_STR);
                $stmt->bindParam(":nDocument", $nDocument_new, PDO::PARAM_INT);
                $stmt->bindParam(":fecha", $fecha_new, PDO::PARAM_STR);
                $stmt->bindParam(":phone", $telefono_new, PDO::PARAM_INT);
                $stmt->bindParam(":nombre", $nombre_new, PDO::PARAM_STR);
                $stmt->bindParam(":apellido", $apellido_new, PDO::PARAM_STR);
                $stmt->bindParam(":correo", $correo_new, PDO::PARAM_STR);
                $stmt->bindParam(":pass", $password, PDO::PARAM_STR);
                $stmt->bindParam(":direccion", $direccion_new, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $tpBodega_up, PDO::PARAM_INT);
                $stmt->bindParam(":rol", $tpRol_up, PDO::PARAM_INT);

                $stmt->execute();

                $lastId = $db->lastInsertId();
                $consecutivo = 'US-' . $lastId;

                $stmt = $db->prepare("UPDATE usuario SET usuario_consecutivo=:consecutivo WHERE idUsuario=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();


                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "usuario";
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

    // Eliminar usuario
    public function deleteUser()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            session_start(['name' => 'SPM']);
            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {

                $id_del = encrypt_decrypt('decrypt', $_GET['id']);
                $db = getDB();

                $stmt = $db->prepare("UPDATE usuario SET usuario_estado='inactivo' WHERE idUsuario=:id");
                $stmt->bindParam(":id", $id_del, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Eliminar";
                    $tabla = "usuario";
                    historial($idUser, $date, $id_del, $accion, $tabla);
                }
            }
            return true;
        }
    }

    // Actualizar usuario
    public function updateUsuario($tpId, $nDocument, $fecha, $telefono, $nombre, $apellido, $correo, $direccion, $bodega, $tpRol, $password, $id)
    {
        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();
            if ($count == 1) {

                $pass = encrypt_decrypt('encrypt', $password);

                $stmt = $db->prepare("UPDATE usuario SET usuario_tDocument=:tpId,usuario_documento=:nDocument,usuario_fecha=:fecha,usuario_telefono=:telefono,usuario_nombre=:nombre,usuario_apellido=:apellido,usuario_correo=:correo,usuario_direccion=:direccion,usuario_idBodega=:bodega,usuario_idRol=:tpRol,usuario_password=:password WHERE idUsuario=:id");

                $stmt->bindParam(":tpId", $tpId, PDO::PARAM_STR);
                $stmt->bindParam(":nDocument", $nDocument, PDO::PARAM_STR);
                $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
                $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":apellido", $apellido, PDO::PARAM_STR);
                $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);
                $stmt->bindParam(":tpRol", $tpRol, PDO::PARAM_STR);
                $stmt->bindParam(":password", $pass, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);

                $stmt->execute();

                // $var = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // var_dump($var);
                // die;

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Actualizar";
                    $tabla = "usuario";

                    historial($idUser, $date, $id, $accion, $tabla);
                }
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

    // Función para registrar la bodega al usuario
    public function clientCellarRegistration($idusuario, $fechaRegistro, $bodegausuario)
    {

        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO usuario_bodega (usuario_bodega_bodega,usuario_bodega_usuario,usuario_bodega_fechaIngreso) VALUES (:idBodega,:idusuario,:fechaIn)");

                $stmt->bindParam(":idBodega", $bodegausuario, PDO::PARAM_STR);
                $stmt->bindParam(":idusuario", $idusuario, PDO::PARAM_STR);
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
    // Función para validar si ya se encuentra registrada la bodega al usuario
    public function searchClientCr($id, $dbC)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT usuario_bodega_usuario,usuario_bodega_bodega  FROM usuario_bodega WHERE usuario_bodega_usuario=:id AND usuario_bodega_bodega=:bd");
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
            $stmt = $db->prepare("DELETE FROM usuario_bodega WHERE idusuario_bodega=:id");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    function consultaSimpleAsc($tabla, $id)
    {
        $campo = "id" . ucfirst($tabla);
        $estado = $tabla . "_estado";

        $db = getDB();
        if ($id == '') {
            $stmt = $db->prepare("SELECT * FROM $tabla WHERE $estado='activo' ORDER BY $campo ASC");

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $db->prepare("SELECT * FROM $tabla WHERE $campo=:id AND $estado='activo' ORDER BY $campo ASC");

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    function consultaCedula($id)
    {
        $db = getDB();

        $stmt = $db->prepare("SELECT * FROM usuario WHERE usuario_documento=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->rowCount();

        return $data;
    }

    // Función para consultar por cédula
    function consultaBodegausuario($id)
    {
        $db = getDB();

        $stmt = $db->prepare("SELECT b.`idBodega`, b.`bodega_nombre` FROM `usuario_bodega` AS c RIGHT JOIN `bodega` AS b  ON c.`usuario_bodega_bodega` = b.`idBodega` WHERE c.`usuario_bodega_usuario`=:id");

        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    // Funcion para generar excel de usuarios
    function GenerateExcel($tabla, $id)
    {
        session_start(['name' => "SPM"]);

        $db = getDB();
        $date = date('d-m-Y');
        $time = date('H:i:s');
        $estado = $tabla . '_estado';

        if ($id == '') {

            $stmt = $db->prepare("SELECT * FROM $tabla as tb 
                 LEFT JOIN rol ON idRol=usuario_idRol
                 INNER JOIN bodega ON usuario_idBodega=idBodega");

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
                     <th class='th'>Tipo de identificación</th>
                     <th class='th'># Identificación</th>
                     <th class='th'>Fecha de naciminento</th>
                     <th class='th'>Estado</th>
                     <th class='th'>Nombre</th>
                     <th class='th'>Apellido</th>
                     <th class='th'>Correo</th>
                     <th class='th'>Dirección principal</th>
                     <th class='th'>Bodega</th>
                     <th class='th'>Tipo de rol</th>
                 </thead>";


            foreach ($row as $r) {

                $salida .= "
                 <tr>
                     <td>" . $r['usuario_consecutivo'] . "</td> 
                     <td>" . $r['usuario_tDocument'] . "</td> 
                     <td>" . $r['usuario_documento'] . "</td>
                     <td>" . $r['usuario_fecha'] . "</td>
                     <td>" . $r['usuario_estado']  . "</td>
                     <td>" . $r['usuario_nombre'] . "</td>
                     <td>" . $r['usuario_apellido'] . "</td>
                     <td>" . $r['usuario_correo'] . "</td>
                     <td>" . $r['usuario_direccion'] . "</td>
                     <td>" . $r['bodega_nombre'] . "</td>
                     <td>" . $r['rol_nombre'] . "</td>
                 </tr>";
            }

            $salida .= "</table>";

            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=usuarios_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        } else {

            $stmt = $db->prepare("SELECT *
                 FROM usuario 
                 LEFT JOIN rol ON idRol=usuario_idRol
                 INNER JOIN bodega ON usuario_idBodega=idBodega WHERE idUsuario=:id");

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

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
                     <th class='th'>Tipo de identificación</th>
                     <th class='th'># Identificación</th>
                     <th class='th'>Fecha de naciminento</th>
                     <th class='th'>Estado</th>
                     <th class='th'>Nombre</th>
                     <th class='th'>Apellido</th>
                     <th class='th'>Correo</th>
                     <th class='th'>Dirección principal</th>
                     <th class='th'>Bodega</th>
                     <th class='th'>Tipo de rol</th>
                 </thead>";


            foreach ($row as $r) {

                $salida .= "
                 <tr>
                     <td>" . $r['usuario_consecutivo'] . "</td> 
                     <td>" . $r['usuario_tDocument'] . "</td> 
                     <td>" . $r['usuario_documento'] . "</td>
                     <td>" . $r['usuario_fecha'] . "</td>
                     <td>" . $r['usuario_estado']  . "</td>
                     <td>" . $r['usuario_nombre'] . "</td>
                     <td>" . $r['usuario_apellido'] . "</td>
                     <td>" . $r['usuario_correo'] . "</td>
                     <td>" . $r['usuario_direccion'] . "</td>
                     <td>" . $r['bodega_nombre'] . "</td>
                     <td>" . $r['rol_nombre'] . "</td>
                 </tr>";
            }

            $salida .= "</table>";

            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=cliente_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        }
    }
}
