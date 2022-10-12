<?php
include_once 'main_class.php';
/* Clase para gestionar los alistamientos */
class enlistedClass
{

    public function enlistedDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3, $alistado)
    {

        try {
            $db = getDB();
            $cond = $table . '_estado';

            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {

                $stmt = $db->prepare("SELECT producto_alistado_id,alistado_consecutivo,alistado_fechaDespacho,alistado_fechaEntrada,alistado_idCliente,alistado_tipo,idAlistado,alistado_estado,producto_uniPeso,GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS productos FROM $table INNER JOIN producto_alistado ON producto_alistado_idAlistado=idAlistado INNER JOIN producto ON producto_alistado_idProducto=idProducto  WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND $cond='activo' AND alistado_tipo=$alistado AND alistado_idBodega=" . $_SESSION['bodega'] . " GROUP BY idAlistado ORDER BY idAlistado DESC LIMIT $limit,$offset");
            } else {
                $stmt = $db->prepare("SELECT producto_alistado_id,alistado_consecutivo,alistado_fechaDespacho,alistado_fechaEntrada,alistado_idCliente,alistado_tipo,idAlistado,alistado_estado,producto_uniPeso,GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS productos FROM $table INNER JOIN producto_alistado ON producto_alistado_idAlistado=idAlistado INNER JOIN producto ON producto_alistado_idProducto=idProducto  WHERE $cond='activo' AND alistado_tipo=$alistado AND alistado_idBodega=" . $_SESSION['bodega'] . " GROUP BY idAlistado ORDER BY idAlistado DESC LIMIT $limit,$offset");
            }
            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    
    public function enlistedRegistration($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $nombre, $cedula, $bodega, $placa)
    {

        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO alistado (alistado_tipo,alistado_fechaDespacho,alistado_fechaEntrada,alistado_idCliente,alistado_nombrePersona,alistado_cedulaPersona,alistado_idBodega,alistado_placaPersona) VALUES (:tipo,:fechaDs,:fechaIn,:idCliente,:nombre,:cedula,:bodega,:placa)");

                $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
                $stmt->bindParam(":fechaIn", $fechaEntrada, PDO::PARAM_STR);
                $stmt->bindParam(":fechaDs", $fechaDespacho, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $cliente, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);
                $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);

                $stmt->execute();

                $lastId = $db->lastInsertId();
                $consecutivo = 'PIN-' . $lastId;

                $stmt = $db->prepare("UPDATE alistado SET alistado_consecutivo=:consecutivo WHERE idAlistado=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();

                foreach ($producto as $product) {

                    $stmt = $db->prepare("INSERT INTO producto_alistado (producto_alistado_idProducto,producto_alistado_idAlistado,producto_alistado_cantidad) VALUES (:idProducto,:idAlistado,:cantidad)");

                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":idAlistado", $lastId, PDO::PARAM_STR);
                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);

                    $stmt->execute();
                }

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "alistado";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

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

    public function deleteEnlisted()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {

            session_start(['name' => 'SPM']);
            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {
                $id_del = encrypt_decrypt('decrypt', $_GET['id']);

                $db = getDB();
                $stmt = $db->prepare("SELECT producto_alistado_idProducto,producto_alistado_cantidad  FROM alistado INNER JOIN producto_alistado ON producto_alistado_idAlistado=idAlistado WHERE idAlistado=:id ");
                $stmt->bindParam(":id", $id_del, PDO::PARAM_STR);
                $stmt->execute();
                $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $db->prepare("UPDATE alistado SET alistado_estado='inactivo' WHERE idAlistado=:id");
                $stmt->bindParam(":id", $id_del, PDO::PARAM_STR);
                $stmt->execute();
                $lastId = $db->lastInsertId();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Eliminar";
                    $tabla = "producto";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }
                header('Location: ../View/contenido/enlisted-list.php?d=1');
            }
        }
    }

    public function updateEnlisted($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $id, $nombre, $cedula, $placa)
    {
        $fechaDespacho = "0000-00-00";

        try {


            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $id = encrypt_decrypt('decrypt', $_SESSION['alistadoUpdate']);


                foreach ($producto as $product) {


                    $stmt = $db->prepare("SELECT * FROM producto_alistado WHERE producto_alistado_idAlistado=:id AND producto_alistado_idProducto=:idPr");
                    $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                    $stmt->bindParam(":idPr", $product['id'], PDO::PARAM_STR);
                    $stmt->execute();

                    $conteo = $stmt->rowCount();

                    $cantAlis = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $cantPro = $producto[$product['id']]['CantAlis'];

                    if ($conteo <= 0) {

                        $stmt = $db->prepare("INSERT INTO producto_alistado (producto_alistado_idProducto,producto_alistado_idAlistado,producto_alistado_cantidad) VALUES (:idProducto,:idAlistado,:cantidad)");

                        $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                        $stmt->bindParam(":idAlistado", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_INT);

                        $stmt->execute();
                    } else {

                        if ($cantPro > $cantAlis[0]['producto_alistado_cantidad']) {

                            $cantTo = $cantPro - $cantAlis[0]['producto_alistado_cantidad'];

                            $stmr = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=producto_alistado_cantidad+:cant WHERE producto_alistado_idProducto=:idProducto AND producto_alistado_idAlistado=:idAlistado");

                            $stmr->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                            $stmr->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                            $stmr->bindParam(":idAlistado", $id, PDO::PARAM_INT);

                            $stmr->execute();
                        } elseif ($cantPro < $cantAlis[0]['producto_alistado_cantidad']) {

                            $cantTo = $cantAlis[0]['producto_alistado_cantidad'] - $cantPro;

                            $stmr = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=producto_alistado_cantidad-:cant WHERE producto_alistado_idProducto=:idProducto AND producto_alistado_idAlistado=:idAlistado");

                            $stmr->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                            $stmr->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                            $stmr->bindParam(":idAlistado", $id, PDO::PARAM_INT);

                            $stmr->execute();
                        }
                    }
                }

                $stmt = $db->prepare("SELECT producto_alistado_CodeIngreso, producto_alistado_id FROM producto_alistado WHERE producto_alistado_idAlistado=:id");
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                $stmt->execute();

                $total = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $cont = count($total);

                if ($total[0]['producto_alistado_CodeIngreso'] != 0) {

                    for ($i = 1; $i <= $cont; $i++) {

                        $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_CodeIngreso=:cod WHERE producto_alistado_idAlistado=:idList");

                        $stmt->bindParam(":cod", $total[0]['producto_alistado_CodeIngreso'], PDO::PARAM_INT);
                        $stmt->bindParam(":idList", $id, PDO::PARAM_INT);

                        $stmt->execute();
                    }
                }

                $stmt = $db->prepare("UPDATE alistado SET alistado_fechaDespacho=:fechaDs,alistado_fechaEntrada=:fechaIn,alistado_idCliente=:idCliente,alistado_nombrePersona=:nombre,alistado_cedulaPersona=:cedula, alistado_placaPersona=:placa WHERE idAlistado=:id");

                $stmt->bindParam(":fechaIn", $fechaEntrada, PDO::PARAM_STR);
                $stmt->bindParam(":fechaDs", $fechaDespacho, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $cliente, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
                $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);

                $stmt->execute();

                $lastId = $db->lastInsertId();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Actualizar";
                    $tabla = "alistado";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

                unset($_SESSION['productosUp']);

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

    public function tableDetails($table, $campo, $campo_1, $bus_1)
    {
        try {
            $db = getDB();
            if ($campo_1 != "") {
                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto
                FROM producto INNER JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                WHERE producto_bodega_estado='activo' AND producto_idCliente='$campo' AND producto_bodega_cantidad>=0 AND producto_bodega_idBodega=" . $_SESSION['bodega'] . " AND $campo_1 LIKE '" . $bus_1 . "%' ORDER BY idProducto DESC");
            } else {
                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto
                FROM producto INNER JOIN producto_bodega
                ON idProducto=producto_bodega_idProducto
                WHERE producto_bodega_estado='activo' AND producto_idCliente='$campo' AND producto_bodega_cantidad>=0 AND producto_bodega_idBodega=" . $_SESSION['bodega'] . "  ORDER BY idProducto DESC");
            }
            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Agregar productos pre-alistamiento
    public function agregarProducto($id, $cant)
    {

        try {

            session_start(['name' => "SPM"]);
            $db = getDB();

            $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_estado='activo' AND producto_bodega_idProducto='$id' AND producto_bodega_idBodega=" . $_SESSION['bodega']);
            $stmt->execute();
            $histo =  $stmt->fetch(PDO::FETCH_ASSOC);
            $producto = [
                'id' => $histo['idProducto'],
                'Codigo' => $histo['producto_codigo'],
                'Nombre'  => $histo['producto_nombre'],
                'Cliente' => $histo['producto_idCliente'],
                'CantAlis' => $cant,
                'CantExis' => $histo['producto_bodega_cantidad'],
                'Peso' => $histo['producto_peso']
            ];

            if ($cant <= 0) {
                return 'cantidadMenorQueCero';
                exit;
            } else if (empty($_SESSION['productosIg'])) {
                $_SESSION['productosIg'][$id] = $producto;

                return 'Agregado';
                exit();
            } else if (!empty($_SESSION['productosIg'])) {
                $indice = false;
                if (isset($_SESSION['productosIg'][$id])) {
                    $indice = true;
                    $_SESSION['productosIg'][$id]['CantAlis'] = $_SESSION['productosIg'][$id]['CantAlis'] + $producto['CantAlis'];

                    header('Location:' . BASE_URL . 'View/contenido/enlisted-new.php');
                }
            }
            if ($indice === false) {
                $_SESSION['productosIg'][$id] = $producto;
                return 'Agregado';
                exit();
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function deleteProducto($id)
    {

        session_start(['name' => "SPM"]);
        try {

            unset($_SESSION['productosIg'][$id]);

            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function Vaciar()
    {

        $url = BASE_URL . 'View/contenido/enlisted-new.php';

        try {

            session_start(['name' => "SPM"]);
            unset($_SESSION['productosIg']);
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function searchEnlisted($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $db = getDB();
        $stmt = $db->prepare("SELECT producto_codigo,producto_nombre,producto_idCliente,producto_peso,producto_bodega_cantidad,producto_alistado_cantidad,producto_alistado_idAlistado,producto_alistado_idProducto 
        FROM `producto_alistado` INNER JOIN producto ON producto_alistado_idProducto=idProducto 
        INNER JOIN producto_bodega ON idProducto=producto_bodega_idProducto
        WHERE producto_alistado_idAlistado=:id AND producto_bodega_idBodega=" . $_SESSION['bodega']);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        $filas =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        enlistedClass::crearSesionUp($filas);
    }

    private function crearSesionUp($filas)
    {

        foreach ($filas as $row) {

            $id = $row['producto_alistado_idProducto'];
            try {

                $producto = [
                    'id' => $row['producto_alistado_idProducto'],
                    'Codigo' => $row['producto_codigo'],
                    'Nombre'  => $row['producto_nombre'],
                    'Cliente' => $row['producto_idCliente'],
                    'CantAlis' => $row['producto_alistado_cantidad'],
                    'CantExis' => $row['producto_bodega_cantidad'],
                    'Peso' => $row['producto_peso'],
                    'Act_val' => 0,
                    'Bool' => 1

                ];

                if (empty($_SESSION['productosUp'])) {
                    $_SESSION['productosUp'][$id] = $producto;
                } else if (!empty($_SESSION['productosUp'])) {

                    if (isset($_SESSION['productosUp'][$id])) {

                        if ($_SESSION['productosUp'][$id]['Bool'] != 1) {
                            $_SESSION['productosUp'][$id]['CantAlis'] = $_SESSION['productosUp'][$id]['CantAlis'] + $producto['CantAlis'];
                        }
                    } else {
                        $_SESSION['productosUp'][$id] = $producto;
                    }
                }
            } catch (Exception $e) {
                echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
            }
        }
    }

    public function deleteProductoUp($id)
    {
        session_start(['name' => "SPM"]);
        $up = encrypt_decrypt('encrypt', 'up');
        $url = BASE_URL . 'View/contenido/enlisted-new.php?upS=' . $up . '&idUp=' . $_SESSION['alistadoUpdate'] . '&idCli=' . $_SESSION['idCliUp'];

        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count <= 1) {

                $_SESSION['productosUp'][$id]['CantAlis'] = 0;

                header("Location: $url");
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para actualizar la data del alistado de ingreso
    public function agregarProductoUp($id, $cant)
    {
        session_start(['name' => "SPM"]);

        $up = encrypt_decrypt('encrypt', 'up');
        $url = BASE_URL . 'View/contenido/' . $_SESSION['url_global_ingreso'];

        try {

            $db = getDB();

            $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_estado='activo' AND producto_bodega_idProducto='$id'AND producto_bodega_idBodega=" . $_SESSION['bodega']);
            $stmt->execute();
            $histo =  $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cant <= 0) {
                return 'menorUno';
            } else if (isset($_SESSION['productosUp'][$id])) {

                $res =  $_SESSION['productosUp'][$id]['CantAlis'] +  $cant;

                $prodRest = $histo['producto_bodega_cantidad'];

                $_SESSION['productosUp'][$id]['CantExis'] = $prodRest;

                $_SESSION['productosUp'][$id]['CantAlis'] =  $res;

                return 'cantExist';
                // header("Location:" . $url . '&status=existe');

            } else {

                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
                FROM producto INNER JOIN producto_bodega
                ON idProducto=producto_bodega_idProducto
                WHERE producto_estado='activo' AND idProducto='$id'");
                $stmt->execute();
                $histo =  $stmt->fetch(PDO::FETCH_ASSOC);

                if (isset($_SESSION['productosUp'][$id])) {
                    $res =  $_SESSION['productosUp'][$id]['CantAlis'] +  $cant;
                }else{
                    $res=$cant;
                }

                $prodRest = $histo['producto_bodega_cantidad'];

                $producto = [
                    'id' => $histo['idProducto'],
                    'Codigo' => $histo['producto_codigo'],
                    'Nombre'  => $histo['producto_nombre'],
                    'Cliente' => $histo['producto_idCliente'],
                    'CantAlis' => $res,
                    'CantExis' => $prodRest,
                    'Peso' => $histo['producto_peso']
                ];

                // echo 'aca';
                // die;
                if (empty($_SESSION['productosUp'])) {
                    $_SESSION['productosUp'][$id] = $producto;
                    // header("Location:" . $url . '&status=Agregado');

                    return 'Agregado';

                } else if (!empty($_SESSION['productosUp'])) {

                    $_SESSION['productosUp'][$id] = $producto;
                    return 'Agregado';
                    
                }
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function VaciarUp()
    {
        session_start(['name' => "SPM"]);

        $up = encrypt_decrypt('encrypt', 'up');

        $url = BASE_URL . 'View/contenido/enlisted-new.php?upS=' . $up . '&idUp=' . $_SESSION['alistadoUpdate'] . '&idCli='. $_SESSION['idCliUp'];

        try {

            foreach ($_SESSION['productosUp'] as $row) {
                enlistedClass::deleteProductoUp($row['id']);
            }
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    function GenerateExcel($tabla, $id)
    {
        session_start(['name' => "SPM"]);
        $db = getDB();
        $date = date('d-m-Y');
        $time = date('H:i:s');
        $estado = $tabla . '_estado';

        if ($id == '') {
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT alistado.*,cliente_nombre,cliente_apellido,bodega_nombre FROM alistado 
                INNER JOIN cliente ON idCliente=alistado_idCliente 
                INNER JOIN bodega ON alistado_idBodega=idBodega
                WHERE alistado_idBodega=:bodega AND alistado_tipo='Entrada'
                ORDER BY alistado_fechaEntrada");
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("SELECT * FROM $tabla  as tb 
                LEFT JOIN cliente_bodega ON idCliente=cliente_bodega_cliente 
                INNER JOIN bodega ON cliente_bodega_bodega=idBodega
                WHERE $estado='activo' AND idBodega=:bodega AND alistado_tipo='Entrada'
                ORDER BY alistado_fechaEntrada");
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
            }

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
                <th  colspan='3' class='th'>CONDUCTOR</th>
                
            </tr>
                <thead> 
                    <th class='th'>Consecutivo</th>
                    <th class='th'>Fecha Ingreso</th>
                    <th class='th'>Cliente</th>
                    <th class='th'>Nombre</th>
                    <th class='th'>Cedula</th>
                    <th class='th'>Placa</th>
                </thead>";


            foreach ($row as $r) {

                $salida .= "
                <tr>
                    <td>" . $r['alistado_consecutivo'] . "</td> 
                    <td>" . $r['alistado_fechaEntrada'] . "</td> 
                    <td>" . $r['cliente_nombre'] . " " . $r['cliente_apellido'] . "</td>
                    <td>" . $r['alistado_nombrePersona'] . "</td>
                    <td>" . $r['alistado_cedulaPersona']  . "</td>
                    <td>" . $r['alistado_placaPersona'] . "</td>
                </tr>";
            }

            $salida .= "</table>";

            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=Pre-Ingresos_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        } else {

            $individual = 'id' . ucfirst($tabla);
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT alistado.*,cliente_nombre,cliente_apellido,bodega_nombre,
                GROUP_CONCAT(producto_consecutivo,'..',producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS productos 
                FROM alistado 
                INNER JOIN producto_alistado ON producto_alistado_idAlistado=idAlistado 
                INNER JOIN producto ON producto_alistado_idProducto=idProducto
                INNER JOIN cliente  ON alistado_idCliente=idCliente
                INNER JOIN bodega ON alistado_idBodega=idBodega
                WHERE $individual=:id");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $db->prepare("SELECT alistado.*,cliente_nombre,cliente_apellido,bodega_nombre,
                GROUP_CONCAT(producto_consecutivo,'..',producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS productos 
                FROM alistado 
                INNER JOIN producto_alistado ON producto_alistado_idAlistado=idAlistado 
                INNER JOIN producto ON producto_alistado_idProducto=idProducto  
                INNER JOIN bodega ON alistado_idBodega=idBodega
                WHERE $individual=:id AND $estado='activo'");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }


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
                </tr>

                <tr>
                    <th><b>FECHA : </b></th>
                    <td>" . $date . "</td>
                </tr>

                <tr>
                    <th><b>HORA : </b></th>
                    <td>" . $time . "</td>
                </tr>
                
                <tr>
                <th></th>
                <th></th>
                <th></th>
                <th  colspan='3' class='th'>CONDUCTOR</th>
                
            </tr>
                <thead> 
                    <th class='th'>Consecutivo</th>
                    <th class='th'>Fecha Ingreso</th>
                    <th class='th'>Cliente</th>
                    <th class='th'>Nombre</th>
                    <th class='th'>Cedula</th>
                    <th class='th'>Placa</th>
                </thead>";


            foreach ($row as $r) {

                $salida .= "
                        <tr>
                            <td>" . $r['alistado_consecutivo'] . "</td> 
                            <td>" . $r['alistado_fechaEntrada'] . "</td> 
                            <td>" . $r['cliente_nombre'] . " " . $r['cliente_apellido'] . "</td>
                            <td>" . $r['alistado_nombrePersona'] . "</td>
                            <td>" . $r['alistado_cedulaPersona']  . "</td>
                            <td>" . $r['alistado_placaPersona'] . "</td>
                        </tr>
                        <tr>
                        </tr>
                        <tr>
                            <th colspan='6' class='th'>Productos Alistados</th> 
                        </tr>
                        <tr>
                            <th class='th'>Consecutivo</th>
                            <th class='th'>Codigo</th>
                            <th class='th'>Nombre</th>
                            <th class='th'>Peso</th>
                            <th class='th'>Cantidad Alistada</th>
                            <th class='th'>Peso Total</th>
                        </tr>
                        ";
            }
            $pesoT = 0;
            $cantidadT = 0;
            if (!empty($r["productos"])) {
                foreach (explode("__", $r["productos"]) as $productosConcatenados) {

                    $producto = explode("..", $productosConcatenados);
                    $peso = intval($producto[3]);
                    $cantidad = intval($producto[4]);
                    $pesoT = $pesoT + ($peso * $cantidad);
                    $cantidadT = $cantidadT + $cantidad;
                    // [0]=consecutivo
                    // [1]=codigo
                    // [2]=nombre
                    // [3]=peso
                    // [4]=cantidad
                    // [5]=alerta
                    // [6]=cantidadAlis
                    // [7]=minimo
                    // [8]=cantidadBlock
                    $salida .= "
                                    <tr>
                                        <td scope='col'>" .
                        $producto[0] .
                        "</td>
                                        <td scope='col'>" .
                        $producto[1] .
                        "</td>
                                        <td scope='col'>" .
                        $producto[2] .
                        "</td>
                                         <td scope='col'>" .
                        $producto[3] . "Kg 
                                        </td>
                                        <td scope='col'>" .
                        $cantidad .
                        "</td>
                                        <td scope='col'>" .
                        ($peso * $cantidad) . "Kg
                                        </td>
                                        
                                        
                                    </tr>
                            
                                ";
                }
                $salida .= "
                                    <tr>
                                        <td scope='col'></td>
                                        <td scope='col'></td>
                                        <td scope='col'>
                                        </td>
                                         <td scope='col'>
                                        </td>
                                        <td class='th' scope='col'>" .
                    $cantidadT .
                    "</td>
                                        <td class='th'>" .
                    $pesoT . "Kg
                                        </td>
                                        
                                        
                                    </tr>
                            
                                ";
            }


            $salida .= "</table>";

            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=Pre-Ingreso_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        }
    }
}
