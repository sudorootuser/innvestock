<?php
include_once 'main_class.php';
if (!isset($_SESSION)) {
    session_start(['name' => "SPM"]);
}
/* User Registration */
class enlistedDsClass
{

    public function enlistedDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3, $alistado)
    {

        try {
            $db = getDB();
            $cond = $table . '_estado';
            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                $stmt = $db->prepare("SELECT producto_alistado_id,alistado_consecutivo,alistado_fechaDespacho,alistado_fechaEntrada,alistado_idCliente,alistado_tipo,idAlistado,alistado_estado,producto_uniPeso,alistado_clienteF,
                GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS productos,
                GROUP_CONCAT(kit_consecutivo,'..',kit_nombre,'..',kit_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS kit
                FROM $table INNER JOIN producto_alistado ON producto_alistado_idAlistado=idAlistado 
                LEFT JOIN producto ON producto_alistado_idProducto=idProducto 
                LEFT JOIN kit ON producto_alistado_idKit=idKit 
                WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' 
                AND $campo_3 LIKE '" . $bus_3 . "%' AND $cond='activo' AND alistado_tipo=$alistado 
                AND alistado_idBodega=" . $_SESSION['bodega'] . " GROUP BY idAlistado ORDER BY idAlistado DESC LIMIT $limit,$offset");
            } else {
                $stmt = $db->prepare("SELECT producto_alistado_id,alistado_consecutivo,alistado_fechaDespacho,alistado_fechaEntrada,alistado_idCliente,alistado_tipo,idAlistado,alistado_estado,producto_uniPeso,alistado_clienteF,
                GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS productos,
                GROUP_CONCAT(kit_consecutivo,'..',kit_nombre,'..',kit_peso,'..', producto_alistado_cantidad  SEPARATOR '__') AS kit
                FROM $table 
                INNER JOIN producto_alistado ON producto_alistado_idAlistado=idAlistado 
                LEFT JOIN producto ON producto_alistado_idProducto=idProducto
                LEFT JOIN kit ON producto_alistado_idKit=idKit 
                WHERE $cond='activo' AND alistado_tipo=$alistado AND alistado_idBodega=" . $_SESSION['bodega'] . " GROUP BY idAlistado ORDER BY idAlistado DESC LIMIT $limit,$offset");
            }
            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function enlistedRegistration($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $nombre, $cedula, $placa, $bodega, $clienteF, $codigo, $observacion)
    {

        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO alistado (alistado_tipo,alistado_fechaDespacho,alistado_fechaEntrada,alistado_idCliente,alistado_nombrePersona,alistado_cedulaPersona,alistado_placaPersona,alistado_idBodega,alistado_clienteF,alistado_codigo,alistado_observacion) VALUES (:tipo,:fechaDs,:fechaIn,:idCliente,:nombre,:cedula,:placa,:bodega,:clienteF,:codigo,:observacion)");

                $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
                $stmt->bindParam(":fechaIn", $fechaEntrada, PDO::PARAM_STR);
                $stmt->bindParam(":fechaDs", $fechaDespacho, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $cliente, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
                $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);
                $stmt->bindParam(":clienteF", $clienteF, PDO::PARAM_STR);
                $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $stmt->bindParam(":observacion", $observacion, PDO::PARAM_STR);

                $stmt->execute();
                $lastId = $db->lastInsertId();

                $consecutivo = 'ALI-' . $lastId;
                $stmt = $db->prepare("UPDATE alistado SET alistado_consecutivo=:consecutivo WHERE idAlistado=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();


                if (!empty($producto)) {
                    enlistedDsClass::producto_alistadoCreate($producto, $lastId);
                }

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "alistado";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

                unset($_SESSION['productosDs']);
                unset($_SESSION['productosKitDs']);

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
    private function producto_alistadoCreate($producto, $id)
    {
        try {
            foreach ($producto as $product) {
                $db = getDB();
                $stmt = $db->prepare("SELECT producto_bodega_cantidad FROM producto_bodega WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");
                $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                $stmt->execute();



                $cantAlis = $stmt->fetchAll(PDO::FETCH_ASSOC);


                $cantPro = $producto[$product['id']]['CantAlis'];
                $cantTo =  $cantAlis[0]['producto_bodega_cantidad'] - $cantPro;


                $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=:proCant,producto_bodega_cantidadAlis=producto_bodega_cantidadAlis+:cant WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");

                $stmt->bindParam(":proCant", $cantTo, PDO::PARAM_INT);
                $stmt->bindParam(":cant", $cantPro, PDO::PARAM_INT);
                $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_INT);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);


                $stmt->execute();

                $stmt = $db->prepare("INSERT INTO producto_alistado (producto_alistado_idProducto,producto_alistado_idAlistado,producto_alistado_cantidad) VALUES (:idProducto,:idAlistado,:cantidad)");

                $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                $stmt->bindParam(":idAlistado", $id, PDO::PARAM_STR);
                $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);

                $stmt->execute();
            }
        } catch (\Throwable $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    private function kit_alistadoCreate($kit, $id, $valKit)
    {

        try {
            foreach ($kit as $product) {
                $db = getDB();
                $stmt = $db->prepare("INSERT INTO producto_alistado (producto_alistado_idKit,producto_alistado_idAlistado,producto_alistado_cantidad) VALUES (:idKit,:idAlistado,:cantidad)");
                $stmt->bindParam(":idKit", $product['id'], PDO::PARAM_STR);
                $stmt->bindParam(":idAlistado", $id, PDO::PARAM_STR);
                $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                $stmt->execute();
            }
            enlistedDsClass::kit_productoCreate($valKit);
        } catch (\Throwable $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    private function kit_productoCreate($valKit)
    {

        try {

            foreach ($valKit as $product) {

                $db = getDB();
                $stmt = $db->prepare("SELECT producto_bodega_cantidad FROM producto_bodega WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");
                $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                $stmt->execute();
                $cantAlis = $stmt->fetchAll(PDO::FETCH_ASSOC);


                $cantPro = $valKit[$product['id']]['CantAlis'];
                $cantTo =  $cantAlis[0]['producto_bodega_cantidad'] - $cantPro;


                $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=:proCant,producto_bodega_cantidadAlis=producto_bodega_cantidadAlis+:cant WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");

                $stmt->bindParam(":proCant", $cantTo, PDO::PARAM_INT);
                $stmt->bindParam(":cant", $cantPro, PDO::PARAM_INT);
                $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_INT);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);

                $stmt->execute();
            }
        } catch (\Throwable $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    public function validacionKit($id, $cantidad)
    {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM producto_kit WHERE producto_kit_idKit=:id");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();
            $total = $stmt->rowCount();

            $stmt = $db->prepare("SELECT idKit,idProducto,producto_codigo,producto_nombre,producto_idCliente,producto_bodega_cantidad,producto_peso,producto_kit_cantidad,kit_consecutivo FROM kit  
                INNER JOIN producto_kit ON idKit=producto_kit_idKit
                INNER JOIN producto ON producto_kit_idProducto=idProducto
                INNER JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                WHERE idKit=:id AND producto_bodega_estado='activo' AND producto_bodega_idBodega=:bodega ");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
            $stmt->execute();
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count == $total) {

                foreach ($filas as $fila) {

                    $cantExis = $cantidad * $fila['producto_kit_cantidad'];
                   
                    if ($cantExis <=  intval($fila['producto_bodega_cantidad'])) {
                        $validacion = "true";
                    } else {
                        $validacion = "false";
                        return $fila['kit_consecutivo'];
                    }
                }

                if ($validacion == "true") {

                    $stmt = $db->prepare("SELECT idKit,idProducto,producto_codigo,producto_nombre,producto_idCliente,producto_bodega_cantidad,producto_peso,producto_kit_cantidad,kit_consecutivo
                        FROM kit
                        INNER JOIN producto_kit ON idKit=producto_kit_idKit
                        INNER JOIN producto ON producto_kit_idProducto=idProducto
                        INNER JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                        WHERE idKit=:id AND producto_estado='activo' ");
                    $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                    $stmt->execute();
                    $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $count = $stmt->rowCount();
                    if ($count >= 1) {

                        foreach ($filas as $fila) {

                            $cantExis = $cantidad * $fila['producto_kit_cantidad'];
                            $kit = [
                                'id' => $fila['idProducto'],
                                'Codigo' => $fila['producto_codigo'],
                                'Nombre'  => $fila['producto_nombre'],
                                'Cliente' => $fila['producto_idCliente'],
                                'CantAlis' => $cantExis,
                                'CantExis' => $fila['producto_bodega_cantidad'],
                                'Peso' => $fila['producto_peso'],
                                'Bool' => 1
                            ];
                            if (empty($productosKitDs)) {
                                $productosKitDs[$fila['idProducto']] = $kit;
                            } else {
                                if (isset($productosKitDs[$fila['idProducto']])) {
                                    $productosKitDs[$fila['idProducto']]['CantAlis'] = $productosKitDs[$fila['idProducto']]['CantAlis'] + $kit['CantAlis'];
                                } else {
                                    $productosKitDs[$fila['idProducto']] = $kit;
                                }
                            }
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return "kitNoExis";
            }

            return $productosKitDs;
        } catch (\Throwable $e) {
            //throw $th;
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

                foreach ($filas as $product) {

                    $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad+:cant,producto_bodega_cantidadAlis=producto_bodega_cantidadAlis-:cant2 WHERE producto_bodega_idProducto=:id AND producto_bodega_idBodega=:bodega");
                    $stmt->bindParam(":cant", $product['producto_alistado_cantidad'], PDO::PARAM_STR);
                    $stmt->bindParam(":cant2", $product['producto_alistado_cantidad'], PDO::PARAM_STR);
                    $stmt->bindParam(":id", $product['producto_alistado_idProducto'], PDO::PARAM_STR);
                    $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);

                    $stmt->execute();
                    $lastId = $db->lastInsertId();
                }

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
                header('Location: ../View/contenido/enlistedDs-list.php?d=1');
            }
        }
    }
    public function updateEnlisted($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $id, $nombre, $cedula, $placa, $clienteF, $codigo, $observacion)
    {
        $fechaEntrada = "0000-00-00";
        try {


            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();

            $count = $st->rowCount();

            if ($count >= 1) {
                $id = encrypt_decrypt('decrypt', $_SESSION['alistadoDsUpdate']);

                if (!empty($producto)) {
                    enlistedDsClass::producto_alistadoUp($producto, $id);
                }

                $stmt = $db->prepare("UPDATE alistado SET alistado_fechaDespacho=:fechaDs,alistado_fechaEntrada=:fechaIn,alistado_idCliente=:idCliente,alistado_nombrePersona=:nombre,alistado_cedulaPersona=:cedula,alistado_placaPersona=:placa,alistado_clienteF=:clienteF,alistado_codigo=:codigo,alistado_observacion=:observacion  WHERE idAlistado=:id");

                $stmt->bindParam(":fechaIn", $fechaEntrada, PDO::PARAM_STR);
                $stmt->bindParam(":fechaDs", $fechaDespacho, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $cliente, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
                $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);

                $stmt->bindParam(":clienteF", $clienteF, PDO::PARAM_STR);
                $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $stmt->bindParam(":observacion", $observacion, PDO::PARAM_STR);

                $stmt->execute();

                $lastId = $db->lastInsertId();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "alistado";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

                // 

                unset($_SESSION['productosDsUp']);
                unset($_SESSION['kitDsUp']);
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
    private function producto_alistadoUp($producto, $id)
    {

        try {
            $db = getDB();
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


                        $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad-:proCant, producto_bodega_cantidadAlis=producto_bodega_cantidadAlis+:cant WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");

                        $stmt->bindParam(":proCant", $cantTo, PDO::PARAM_INT);
                        $stmt->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                        $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_INT);
                        $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);

                        $stmt->execute();

                        $stmr = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=producto_alistado_cantidad+:cant WHERE producto_alistado_idProducto=:idProducto AND producto_alistado_idAlistado=:idAlistado");

                        $stmr->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                        $stmr->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                        $stmr->bindParam(":idAlistado", $id, PDO::PARAM_INT);

                        $stmr->execute();
                    } elseif ($cantPro < $cantAlis[0]['producto_alistado_cantidad']) {

                        $cantTo = $cantAlis[0]['producto_alistado_cantidad'] - $cantPro;

                        $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad+:proCant, producto_bodega_cantidadAlis=producto_bodega_cantidadAlis-:cant WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");

                        $stmt->bindParam(":proCant", $cantTo, PDO::PARAM_INT);
                        $stmt->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                        $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_INT);
                        $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);

                        $stmt->execute();


                        $stmr = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=producto_alistado_cantidad-:cant WHERE producto_alistado_idProducto=:idProducto AND producto_alistado_idAlistado=:idAlistado");

                        $stmr->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                        $stmr->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                        $stmr->bindParam(":idAlistado", $id, PDO::PARAM_INT);

                        $stmr->execute();
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    private function kit_alistadoUp($kit, $id, $valKit)
    {
        try {
            $db = getDB();
            foreach ($kit as $product) {
                $stmt = $db->prepare("SELECT * FROM producto_alistado WHERE producto_alistado_idAlistado=:id AND producto_alistado_idKit=:idPr");
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                $stmt->bindParam(":idPr", $product['id'], PDO::PARAM_STR);

                $stmt->execute();

                $conteo = $stmt->rowCount();

                if ($conteo <= 0) {

                    $stmt = $db->prepare("INSERT INTO producto_alistado (producto_alistado_idKit,producto_alistado_idAlistado,producto_alistado_cantidad) VALUES (:idKit,:idAlistado,:cantidad)");
                    $stmt->bindParam(":idKit", $product['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":idAlistado", $id, PDO::PARAM_INT);
                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=:cantidad WHERE producto_alistado_idKit=:idKit AND producto_alistado_idAlistado=:idAlistado");
                    $stmt->bindParam(":idKit", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":idAlistado", $id, PDO::PARAM_STR);
                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
            enlistedDsClass::kit_productoUp($valKit, $id);
        } catch (\Throwable $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    private function kit_productoUp($producto, $id)
    {

        try {
            $db = getDB();
            foreach ($producto as $product) {

                $stmt = $db->prepare("SELECT * FROM producto_alistado WHERE producto_alistado_idAlistado=:id AND producto_alistado_idProducto=:idPr");
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                $stmt->bindParam(":idPr", $product['id'], PDO::PARAM_STR);
                $stmt->execute();

                // $cantAlis = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $cantPro = $producto[$product['id']]['CantAlis'];


                if (empty($product['Antes'])) {

                    $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=:proCant,producto_bodega_cantidadAlis=producto_bodega_cantidadAlis+:cant WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");

                    $stmt->bindParam(":proCant", $cantTo, PDO::PARAM_INT);
                    $stmt->bindParam(":cant", $cantPro, PDO::PARAM_INT);
                    $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                    $stmt->execute();
                } elseif ($cantPro > $product['Antes']) {

                    $cantTo = $cantPro - $product['Antes'];

                    $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad-:proCant, producto_bodega_cantidadAlis=producto_bodega_cantidadAlis+:cant WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");

                    $stmt->bindParam(":proCant", $cantTo, PDO::PARAM_INT);
                    $stmt->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                    $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                    $stmt->execute();
                } elseif ($cantPro < $product['Antes']) {

                    $cantTo = $product['Antes'] - $cantPro;

                    $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad+:proCant, producto_bodega_cantidadAlis=producto_bodega_cantidadAlis-:cant WHERE producto_bodega_idProducto=:idPro AND producto_bodega_idBodega=:bodega");

                    $stmt->bindParam(":proCant", $cantTo, PDO::PARAM_INT);
                    $stmt->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                    $stmt->bindParam(":idPro", $product['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        } catch (\Throwable $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    public function tableDetails($campo, $campo_1, $bus_1)
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
    public function kitDetails($campo)
    {

        try {
            $db = getDB();

            $stmt = $db->prepare("SELECT idKit,kit_consecutivo,kit_nombre,kit_peso FROM kit
            WHERE kit_estado='activo' AND kit_idCliente='$campo' AND kit_idBodega=" . $_SESSION['bodega']);

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function agregarProducto($id, $cant)
    {
        $url = BASE_URL . 'View/contenido/enlistedDs-new.php';

        try {

            $db = getDB();

            $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_bodega_estado='activo' AND producto_bodega_cantidad>0 AND producto_bodega_idProducto=$id AND  producto_bodega_idBodega=" . $_SESSION['bodega']);
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
            $indice = false;

            if ($cant <= 0) {
                return 'menorCero';
            }
            if ($histo['producto_bodega_cantidad'] == 0) {
                return 'product_agotado';
            } else if ($cant > $histo['producto_bodega_cantidad']) {
                return 'cantMayor';
            } else if (empty($_SESSION['productosDs'])) {
                $_SESSION['productosDs'][$id] = $producto;
                // header("Location:" . BASE_URL . 'View/contenido/enlistedDs-new.php?status=Agregado');
                return "agregado";
            } else if (!empty($_SESSION['productosDs'])) {

                if (isset($_SESSION['productosDs'][$id])) {

                    $indice = true;
                    $_SESSION['productosDs'][$id]['CantAlis'] = $_SESSION['productosDs'][$id]['CantAlis'] + $producto['CantAlis'];
                    return "agregado";

                }
            }

            if ($indice === false) {
                $_SESSION['productosDs'][$id] = $producto;
                // header("Location:" . BASE_URL . 'View/contenido/enlistedDs-new.php');
                return "agregado";
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function deleteProductoUp($id)
    {
        

        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count <= 1) {

                $_SESSION['productosDsUp'][$id]['CantAlis'] = 0;

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function deleteProducto($id)
    {
        
        $up = encrypt_decrypt('encrypt', 'up');


        try {

            unset($_SESSION['productosDs'][$id]);

            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function deleteKitUp($id)
    {
        
        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count <= 1) {

                $_SESSION['kitDsUp'][$id]['CantAlis'] = 0;

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function deleteKit($id)
    {
        
        try {
            unset($_SESSION['kitDs'][$id]);
            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function VaciarUp()
    {
        

        $up = encrypt_decrypt('encrypt', 'up');

        $url = BASE_URL . 'View/contenido/enlistedDs-new.php?upS=' . $up . '&idUp=' . $_SESSION['alistadoDsUpdate'] . '&status=Vaciado';;


        try {
            if (isset($_SESSION['productosDsUp'])) {
                foreach ($_SESSION['productosDsUp'] as $row) {
                    enlistedDsClass::deleteProductoUp($row['id']);
                }
            }
            if (isset($_SESSION['kitDsUp'])) {
                foreach ($_SESSION['kitDsUp'] as $row) {
                    enlistedDsClass::deleteKitUp($row['id']);
                }
            }

            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function Vaciar()
    {

        $url = BASE_URL . 'View/contenido/enlistedDs-new.php?status=Vaciado';

        try {

            

            if (isset($_SESSION["productosDs"])) {
                unset($_SESSION['productosDs']);
            }

            if (isset($_SESSION["kitDs"])) {
                unset($_SESSION['kitDs']);
            }
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function searchEnlisted($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $db = getDB();
            $stmt = $db->prepare("SELECT producto_codigo,producto_nombre,producto_idCliente,producto_peso,producto_bodega_cantidad,producto_alistado_cantidad,producto_alistado_idAlistado,producto_alistado_idProducto 
            FROM `producto_alistado` INNER JOIN producto ON producto_alistado_idProducto=idProducto 
            INNER JOIN producto_bodega ON idProducto=producto_bodega_idProducto
            WHERE producto_alistado_idAlistado=:id AND producto_bodega_idBodega=" . $_SESSION['bodega']);
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count >= 1) {
                $filas =  $stmt->fetchAll(PDO::FETCH_ASSOC);
                enlistedDsClass::crearSesionUp($filas);
            }

            $stmt = $db->prepare("SELECT kit_consecutivo,kit_nombre,kit_idCliente,kit_peso,producto_alistado_cantidad,producto_alistado_idAlistado,producto_alistado_idKit
            FROM `producto_alistado` 
            INNER JOIN kit ON producto_alistado_idKit=idKit
            WHERE producto_alistado_idAlistado=:id AND kit_idBodega=" . $_SESSION['bodega']);

            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();

            if ($count >= 1) {
                $filas =  $stmt->fetchAll(PDO::FETCH_ASSOC);
                enlistedDsClass::crearSesionKitUp($filas);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
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
                    'Bool' => 1

                ];
                if (empty($_SESSION['productosDsUp'])) {
                    $_SESSION['productosDsUp'][$id] = $producto;
                } else if (!empty($_SESSION['productosDsUp'])) {
                    if (isset($_SESSION['productosDsUp'][$id])) {
                        if ($_SESSION['productosDsUp'][$id]['Bool'] != 1) {
                            $_SESSION['productosDsUp'][$id]['CantAlis'] = $_SESSION['productosDsUp'][$id]['CantAlis'] + $producto['CantAlis'];
                        }
                    } else {
                        $_SESSION['productosDsUp'][$id] = $producto;
                    }
                }
            } catch (Exception $e) {
                echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
            }
        }
    }

    private function crearSesionKitUp($filas)
    {
        foreach ($filas as $row) {

            $id = $row['producto_alistado_idKit'];
            try {

                $producto = [
                    'id' => $row['producto_alistado_idKit'],
                    'Codigo' => $row['kit_consecutivo'],
                    'Nombre'  => $row['kit_nombre'],
                    'Cliente' => $row['kit_idCliente'],
                    'CantAlis' => $row['producto_alistado_cantidad'],
                    'Peso' => $row['kit_peso'],
                    'Bool' => 1,
                ];
                if (empty($_SESSION['kitDsUp'])) {
                    $_SESSION['kitDsUp'][$id] = $producto;
                } else if (!empty($_SESSION['kitDsUp'])) {
                    if (isset($_SESSION['kitDsUp'][$id])) {
                        if ($_SESSION['kitDsUp'][$id]['Bool'] != 1) {
                            $_SESSION['kitDsUp'][$id]['CantAlis'] = $_SESSION['kitDsUp'][$id]['CantAlis'] + $producto['CantAlis'];
                        }
                    } else {
                        $_SESSION['kitDsUp'][$id] = $producto;
                    }
                }
            } catch (Exception $e) {
                echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
            }
        }
    }

    public function agregarProductoUp($id, $cant)
    {

        try {

            $db = getDB();

            $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_estado='activo' AND producto_bodega_idProducto='$id' AND producto_bodega_idBodega=" . $_SESSION['bodega']);
            $stmt->execute();
            $histo =  $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cant <= 0) {
                return 'menorUno';
                exit;
            }
            if ($histo['producto_bodega_cantidad'] == 0) {

                return 'product_agotado';
                exit();
            } else if ($cant > $histo['producto_bodega_cantidad']) {
                return 'cantMayor';
                exit();
            } else if (isset($_SESSION['productosDsUp'][$id])) {

                if ($cant > $histo['producto_bodega_cantidad']) {
                    return 'cantMayor';
                    exit();
                } else {
                    $res =  $_SESSION['productosDsUp'][$id]['CantAlis'] +  $cant;

                    $prodRest = $histo['producto_bodega_cantidad'] - $cant;

                    $_SESSION['productosDsUp'][$id]['CantExis'] = $prodRest;

                    $_SESSION['productosDsUp'][$id]['CantAlis'] =  $res;

                    return 'cantExist';
                    exit();
                }
            } else {

                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
                FROM producto INNER JOIN producto_bodega
                ON idProducto=producto_bodega_idProducto
                WHERE producto_estado='activo' AND idProducto='$id' AND producto_bodega_idBodega=" . $_SESSION['bodega']);
                $stmt->execute();
                $histo =  $stmt->fetch(PDO::FETCH_ASSOC);

                if (isset($_SESSION['productosDsUp'][$id])) {
                    $res =  $_SESSION['productosDsUp'][$id]['CantAlis'] +  $cant;
                } else {
                    $res = $cant;
                }

                $prodRest = $histo['producto_bodega_cantidad'] - $cant;

                $producto = [
                    'id' => $histo['idProducto'],
                    'Codigo' => $histo['producto_codigo'],
                    'Nombre'  => $histo['producto_nombre'],
                    'Cliente' => $histo['producto_idCliente'],
                    'CantAlis' => $res,
                    'CantExis' => $prodRest,
                    'Peso' => $histo['producto_peso']
                ];

                if (empty($_SESSION['productosDsUp'])) {
                    $_SESSION['productosDsUp'][$id] = $producto;
                    return 'agregado';
                    exit();
                } else if (!empty($_SESSION['productosDsUp'])) {

                    $_SESSION['productosDsUp'][$id] = $producto;
                    return 'agregado';
                    exit();
                }
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function agregarKit($id, $cantidad)
    {
        $url = BASE_URL . 'View/contenido/enlistedDs-new.php';
        try {

            if ($cantidad <= 0) {
                return 'menorCero';
            } else {
                $producto_kit = enlistedDsClass::validacionKit($id, $cantidad);
                if (is_array($producto_kit)) {
                    // var_dump($producto_kit);
                    foreach ($producto_kit as $product) {
                        $producto_kit = enlistedDsClass::agregarProducto($product['id'], $product['CantAlis']);
                    }
                    return 'agregado';
                } else {
                    return $producto_kit;
                }
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }


    public function agregarKitUp($id, $cantidad)
    {
        $url = BASE_URL . 'View/contenido/enlistedDs-new.php';
        try {

            if ($cantidad <= 0) {
                return 'menorCero';
            } else {
                $producto_kit = enlistedDsClass::validacionKit($id, $cantidad);
                if (is_array($producto_kit)) {
                    // var_dump($producto_kit);
                    foreach ($producto_kit as $product) {
                        $producto_kit = enlistedDsClass::agregarProductoUp($product['id'], $product['CantAlis']);
                    }
                    return 'agregado';
                } else {
                    return $producto_kit;
                }
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    function GenerateExcel($tabla, $id)
    {
        
        $db = getDB();
        $date = date('d-m-Y');
        $time = date('H:i:s');
        $estado = $tabla . '_estado';

        if ($id == '') {
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT alistado.*,cliente_nombre,cliente_apellido,bodega_nombre FROM alistado 
                INNER JOIN cliente ON idCliente=alistado_idCliente 
                INNER JOIN bodega ON alistado_idBodega=idBodega
                WHERE alistado_idBodega=:bodega AND alistado_tipo='Despacho'
                ORDER BY alistado_fechaDespacho");
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("SELECT * FROM $tabla  as tb 
                LEFT JOIN cliente_bodega ON idCliente=cliente_bodega_cliente 
                INNER JOIN bodega ON cliente_bodega_bodega=idBodega
                WHERE $estado='activo' AND idBodega=:bodega AND alistado_tipo='Entrada'
                ORDER BY alistado_fechaDespacho");
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
                    <th class='th'>Fecha Despacho</th>
                    <th class='th'>Cliente</th>
                    <th class='th'>Nombre</th>
                    <th class='th'>Cedula</th>
                    <th class='th'>Placa</th>
                </thead>";


            foreach ($row as $r) {

                $salida .= "
                <tr>
                    <td>" . $r['alistado_consecutivo'] . "</td> 
                    <td>" . $r['alistado_fechaDespacho'] . "</td> 
                    <td>" . $r['cliente_nombre'] . " " . $r['cliente_apellido'] . "</td>
                    <td>" . $r['alistado_nombrePersona'] . "</td>
                    <td>" . $r['alistado_cedulaPersona']  . "</td>
                    <td>" . $r['alistado_placaPersona'] . "</td>
                </tr>";
            }

            $salida .= "</table>";

            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=Alistados_" . time() . ".xls");
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
                WHERE idAlistado=:id");
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
                WHERE idAlistado=:id AND $estado='activo'");
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
                    <th class='th'>Fecha Despacho</th>
                    <th class='th'>Cliente</th>
                    <th class='th'>Nombre</th>
                    <th class='th'>Cedula</th>
                    <th class='th'>Placa</th>
                </thead>";


            foreach ($row as $r) {

                $salida .= "
                        <tr>
                            <td>" . $r['alistado_consecutivo'] . "</td> 
                            <td>" . $r['alistado_fechaDespacho'] . "</td> 
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

            header("Content-Disposition: attachment; filename=Alistado_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        }
    }
}
