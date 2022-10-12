<?php
include_once 'main_class.php';
include_once 'task_class.php';
/* Funciones para el modulo de despacho */
if (!isset($_SESSION)) {
    session_start(['name' => "SPM"]);
}
class dispatchClass
{
    // Función para validar los Kits
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
                WHERE idKit=:id AND producto_bodega_estado='activo' AND producto_bodega_idBodega=:bodega");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
            $stmt->execute();
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count == $total) {

                foreach ($filas as $fila) {

                    $cantExis = $cantidad * $fila['producto_kit_cantidad'];
                    if ($cantExis < $fila['producto_bodega_cantidad']) {
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

    // Validacion del producto a agregar
    public function validacion($producto, $enlisted)
    {
        $als = encrypt_decrypt('decrypt', $_SESSION['alistado']);
        $bool = false;
        if (!empty($producto)) {
            if (count($producto) == count($enlisted)) {

                foreach ($producto as $pr) {
                    $db = getDB();
                    $st = $db->prepare("SELECT * FROM producto_alistado WHERE producto_alistado_idProducto=:id AND  producto_alistado_idAlistado=:als");
                    $st->bindParam(":id", $pr['id'], PDO::PARAM_STR);
                    $st->bindParam(":als", $als, PDO::PARAM_STR);
                    $st->execute();
                    $filas = $st->fetchAll(PDO::FETCH_ASSOC);
                    $count = count($filas);
                    if ($count >= 1) {
                        if ($filas[0]['producto_alistado_cantidad'] == $pr['CantAlis']) {
                            $bool = "true";
                        } else if ($filas[0]['producto_alistado_cantidad'] < $pr['CantAlis']) {

                            return " cantidad mayor al alistado, Producto PR-" . $filas[0]['producto_alistado_idProducto'];
                        } else {

                            $bool = "true";
                        }
                    } else {

                        return "Producto PR-" . $pr['id'] . " no está alistado";
                    }
                }
            } else {
                // return "Cantidad de productos no son iguales a las alistadas ";
                $bool = "true";
            }
        }
        return $bool;
    }

    // Función para registrar el despacho de un producto con un pre-alistamiento realizado
    public function dispatchRegistration($idClient, $descripCorta, $producto, $nombre, $cedula, $placa, $codigo, $clienteF, $firma)
    {
        try {

            $als = encrypt_decrypt('decrypt', $_SESSION['alistado']);
            $task = new taskClass;

            $idBodega = encrypt_decrypt('decrypt', $_SESSION['alistado']);


            $idUser = $_SESSION['idUsuario'];
            $date = date('Y-m-d H:i:s');
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count <= 1) {

                $registro = 0;

                foreach ($producto as $product) {

                    // Consulta para validar si el alistamiento ya tiene un registro de ingreso
                    $smtp = $db->prepare("SELECT producto_alistado_CodeIngreso as code, producto_alistado_id,producto_alistado_idProducto,producto_alistado_idAlistado, producto_alistado_cantidad as cant FROM producto_alistado WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd LIMIT 1");

                    $smtp->bindParam(":pd", $product['id'], PDO::PARAM_INT);
                    $smtp->bindParam(":bd", $idBodega, PDO::PARAM_INT);
                    $smtp->execute();

                    $search = $smtp->fetchAll(PDO::FETCH_ASSOC);

                    if (count($search) == 0) {

                        return 'WrongProduct';
                        exit();
                    } else {

                        $smtp = $db->prepare("SELECT producto_despacho_id as idIn FROM producto_despacho WHERE producto_despacho_idProducto=:pd AND producto_despacho_idDespacho=:bd LIMIT 1");

                        $smtp->bindParam(":pd", $product['id'], PDO::PARAM_INT);
                        $smtp->bindParam(":bd", $search[0]['code'], PDO::PARAM_INT);
                        $smtp->execute();

                        $idIngresado = $smtp->fetchAll(PDO::FETCH_ASSOC);

                        // Se valida si el producto ya cuenta con un registro ya realizado
                        if ($search != 0) {
                            if (!empty($search[0]['code'])) {

                                $registro = $_SESSION['sessionlastId'] = $search[0]['code'];

                                $lastId = $registro;
                            }
                        }

                        // Se crea una consulta para buscar el consecutivo del  ultimo registro
                        if ($registro != 0) {
                            $stmt = $db->prepare("SELECT despacho_consecutivo FROM despacho WHERE idDespacho=:id");
                            $stmt->bindParam(":id", $registro, PDO::PARAM_STR);
                            $stmt->execute();

                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $consecutivo_despacho = $data;
                        }

                        // Se valida si la cantidad seleccionada es igual a la cantidad alistada
                        if ($product['CantAlis'] == $search[0]['cant']) {

                            // Si no hay un producto con una cantidad diferente al alistado ingresa acá
                            if ($search[0]['code'] == 0) {

                                if ($registro == 0) {

                                    $lastId = dispatchClass::registroDespacho($idClient, $descripCorta, $producto, $nombre, $cedula, $placa, $codigo, $clienteF);

                                    $registro = $_SESSION['sessionlastId'] = $lastId;
                                }

                                $stmt = $db->prepare("INSERT INTO producto_despacho (producto_despacho_idProducto,producto_despacho_idDespacho,producto_despacho_cantidad) VALUES (:idProducto,:idDespacho,:cantidad)");
                                $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":idDespacho", $lastId, PDO::PARAM_STR);
                                $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                                $stmt->execute();

                                $idUser = $_SESSION['idUsuario'];
                                $accion = "Crear";
                                $tabla = "Despacho";
                                historial($idUser, $date, $lastId, $accion, $tabla);

                                // Validación para asociar el codigo de registro a la tabla de despacho 

                                if ($search[0]['code'] == 0) {

                                    $smtp = $db->prepare("SELECT producto_alistado_id FROM producto_alistado WHERE producto_alistado_idAlistado=:bd");

                                    $smtp->bindParam(":bd", $idBodega, PDO::PARAM_INT);

                                    $smtp->execute();

                                    $add_code = $smtp->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($add_code as $row) {

                                        $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idAlistado=:bd AND producto_alistado_id=:idPd");

                                        $stmt->bindParam(":codeIn", $registro, PDO::PARAM_INT);
                                        $stmt->bindParam(":bd", $idBodega, PDO::PARAM_INT);
                                        $stmt->bindParam(":idPd", $row['producto_alistado_id'], PDO::PARAM_INT);

                                        $stmt->execute();
                                    }
                                }

                                // Instrucción para eliminar el producto alistado por ID
                                $smtp = $db->prepare("DELETE FROM producto_alistado WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $smtp->bindParam(":pd", $product['id'], PDO::PARAM_INT);
                                $smtp->bindParam(":bd", $idBodega, PDO::PARAM_INT);

                                $smtp->execute();
                            } else {

                                // Consulta para validar si el producto a despachar ya tiene un registro de un despacho anterior
                                $smtp = $db->prepare("SELECT producto_despacho_idProducto, producto_despacho_idDespacho FROM producto_despacho WHERE producto_despacho_idProducto=:pd AND producto_despacho_idDespacho=:bd LIMIT 1");

                                $smtp->bindParam(":pd", $product['id'], PDO::PARAM_INT);
                                $smtp->bindParam(":bd", $search[0]['code'], PDO::PARAM_INT);
                                $smtp->execute();

                                $search2 = $smtp->fetchAll(PDO::FETCH_ASSOC);

                                // Se valida si hay un registro anterior de acuerdo al resultado se actualiza
                                if (empty($search2)) {

                                    $stmt = $db->prepare("INSERT INTO producto_despacho (producto_despacho_idProducto,producto_despacho_idDespacho,producto_despacho_cantidad) VALUES (:idProducto,:idDespacho,:cantidad)");

                                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                                    $stmt->bindParam(":idDespacho", $search[0]['code'], PDO::PARAM_INT);
                                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);

                                    $stmt->execute();
                                } else {

                                    $stmt = $db->prepare("UPDATE producto_despacho SET producto_despacho_cantidad=producto_despacho_cantidad+:cantAdd WHERE producto_despacho_idProducto=:pd AND producto_despacho_idDespacho=:bd");

                                    $stmt->bindParam(":cantAdd", $search[0]['cant'], PDO::PARAM_STR);
                                    $stmt->bindParam(":pd", $product['id'], PDO::PARAM_STR);
                                    $stmt->bindParam(":bd", $search[0]['code'], PDO::PARAM_STR);

                                    $stmt->execute();
                                }

                                $smtp = $db->prepare("DELETE FROM producto_alistado WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $smtp->bindParam(":pd", $product['id'], PDO::PARAM_INT);
                                $smtp->bindParam(":bd", $idBodega, PDO::PARAM_INT);

                                $smtp->execute();
                            }
                        }

                        // Se valida si la cantidad seleccionada es menor a la cantidad alistada
                        else if ($product['CantAlis'] < $search[0]['cant']) {

                            $total_ingre = $search[0]['cant'] - $product['CantAlis'];

                            if ($registro == 0) {

                                $lastId = dispatchClass::registroDespacho($idClient, $descripCorta, $producto, $nombre, $cedula, $placa, $codigo, $clienteF);

                                $stmt = $db->prepare("SELECT despacho_consecutivo FROM despacho WHERE idDespacho=:id");
                                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                                $stmt->execute();

                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                $consecutivo_despacho = $data;

                                $registro = $_SESSION['sessionlastId'] = $lastId;
                            }

                            if (count($idIngresado) > 0) {

                                // Parte para la creación de un nueva entrada
                                $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=:cantAdd,producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $stmt->bindParam(":cantAdd", $total_ingre, PDO::PARAM_STR);
                                $stmt->bindParam(":codeIn", $lastId, PDO::PARAM_INT);
                                $stmt->bindParam(":pd", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":bd", $idBodega, PDO::PARAM_STR);

                                $stmt->execute();


                                $stmt = $db->prepare("UPDATE producto_despacho SET producto_despacho_cantidad=producto_despacho_cantidad+:cantidad WHERE producto_despacho_idProducto=:idProduct AND producto_despacho_idDespacho=:idDespacho");

                                $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                                $stmt->bindParam(":idProduct", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":idDespacho", $lastId, PDO::PARAM_STR);
                                $stmt->execute();
                            } else {

                                // Parte para la creación de un nueva entrada
                                $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=:cantAdd,producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $stmt->bindParam(":cantAdd", $total_ingre, PDO::PARAM_STR);
                                $stmt->bindParam(":codeIn", $lastId, PDO::PARAM_INT);
                                $stmt->bindParam(":pd", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":bd", $idBodega, PDO::PARAM_STR);

                                $stmt->execute();

                                $stmt = $db->prepare("INSERT INTO producto_despacho (producto_despacho_idProducto,producto_despacho_idDespacho,producto_despacho_cantidad) VALUES (:idProducto,:idDespacho,:cantidad)");
                                $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":idDespacho", $lastId, PDO::PARAM_STR);
                                $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                                $stmt->execute();
                            }

                            // Validación para relacionar el alistamiento a los productos ingresados
                            if ($search[0]['code'] == 0) {

                                $smtp = $db->prepare("SELECT producto_alistado_id FROM producto_alistado WHERE producto_alistado_idAlistado=:bd");

                                $smtp->bindParam(":bd", $idBodega, PDO::PARAM_INT);

                                $smtp->execute();

                                $add_code = $smtp->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($add_code as $row) {

                                    $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idAlistado=:bd AND producto_alistado_id=:idPd");

                                    $stmt->bindParam(":codeIn", $registro, PDO::PARAM_INT);
                                    $stmt->bindParam(":bd", $idBodega, PDO::PARAM_INT);
                                    $stmt->bindParam(":idPd", $row['producto_alistado_id'], PDO::PARAM_INT);

                                    $stmt->execute();
                                }
                            }
                        }

                        // Función para modificar el estado de la cantidad alistada
                        $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidadAlis=producto_bodega_cantidadAlis-:cant WHERE producto_bodega_idProducto=:idP AND producto_bodega_idBodega=" . $_SESSION['bodega']);
                        $stmt->bindParam(":idP", $product['id'], PDO::PARAM_STR);
                        $stmt->bindParam(":cant", $product['CantAlis'], PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }


                if ($registro) {

                    dispatchClass::add_temp_img_product($firma, $registro);
                }
                unset($_SESSION['despacho']);
                unset($_SESSION['id_client_pro']);
                unset($_SESSION['sessionlastId']);

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

    // Agregar imagen
    public function add_temp_img_product($firma, $uid)
    {

        $temp = $firma['tmp_name'];
        $nombre = $uid . $firma['name'];
        $tipo = $firma['type'];
        $size = $firma['size'];

        // Ruta donde se guardarán las imágenes que subamos
        $directorio = '../assets/img/signature/';

        // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
        move_uploaded_file($temp, $directorio . $nombre);

        $db = getDB();
        $idUser = $_SESSION['idUsuario'];

        $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
        $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
        $st->execute();
        $count = $st->rowCount();
        if ($count >= 1) {

            $stmt = $db->prepare("INSERT INTO imagen(imagen_nombre,imagen_tipo,imagen_size,imagen_fecha,despacho_firmaId) VALUES (:imagen,:tipo,:size,:fecha,:despacho)");

            $date = date('Y-m-d H:i:s', time());
            $stmt->bindParam(":imagen", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $stmt->bindParam(":size", $size, PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $date);
            $stmt->bindParam(":despacho", $uid, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } else {
            return false;
        }
    }

    // Función para registrar un despacho nuevo
    public function registroDespacho($idClient, $descripCorta, $nombre, $cedula, $placa, $codigo, $clienteF)
    {
        $db = getDB();

        $date = date('d-m-y');

        $stmt = $db->prepare("INSERT INTO despacho (despacho_fechaDs,despacho_nombrePersona,despacho_cedulaPersona,despacho_placaPersona,despacho_clienteF,despacho_codigo,despacho_observaciones,despacho_idBodega,despacho_idCliente) VALUES (:fechaDs,:nombre,:cedula,:placa,:clienteF,:codigo,:descrip,:bodega,:idCliente)");
        $stmt->bindParam(":fechaDs", $date, PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
        $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);
        $stmt->bindParam(":clienteF", $clienteF, PDO::PARAM_STR);
        $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $stmt->bindParam(":descrip", $descripCorta, PDO::PARAM_STR);
        $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
        $stmt->bindParam(":idCliente", $idClient, PDO::PARAM_STR);
        $stmt->execute();
        $lastId = $db->lastInsertId();

        $consecutivo = 'DS-' . $lastId;
        $stmt = $db->prepare("UPDATE despacho SET despacho_consecutivo=:consecutivo WHERE idDespacho=:id");
        $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
        $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);

        $stmt->execute();

        return $lastId;
    }

    // Función para crear un alistado
    public function producto_alistadoCreate($idClient, $producto, $consecutivo, $nombre, $cedula, $placa, $lastId)
    {
        try {
            $tipo = "Despacho";
            $fechaEntrada = "";
            $fechaDespacho = date('d-m-y');
            $als = encrypt_decrypt('decrypt', $_SESSION['alistado']);

            foreach ($producto as $product) {

                $db = getDB();
                $st = $db->prepare("SELECT producto_alistado_cantidad FROM producto_alistado WHERE producto_alistado_idProducto=:id AND  producto_alistado_idAlistado=:als");
                $st->bindParam(":id", $product['id'], PDO::PARAM_STR);
                $st->bindParam(":als", $als, PDO::PARAM_STR);
                $st->execute();
                $filas = $st->fetchAll(PDO::FETCH_ASSOC);

                if ($filas[0]['producto_alistado_cantidad'] != $product['CantAlis']) {

                    $resProduct = $filas[0]['producto_alistado_cantidad'] -  $product['CantAlis'];

                    dispatchClass::enlistedRegistration($tipo, $fechaEntrada, $fechaDespacho, $idClient, $producto, $resProduct, $nombre, $cedula, $_SESSION['bodega'], $placa, $consecutivo);

                    $stmt = $db->prepare("INSERT INTO producto_despacho (producto_despacho_idProducto,producto_despacho_idDespacho,producto_despacho_cantidad) VALUES (:idProducto,:idDespacho,:cantidad)");
                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":idDespacho", $lastId, PDO::PARAM_STR);
                    $stmt->bindParam(":cantidad", $resProduct, PDO::PARAM_STR);
                    $stmt->execute();

                    $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidadAlis=producto_bodega_cantidadAlis-:cant WHERE producto_bodega_idProducto=:idP AND producto_bodega_idBodega=" . $_SESSION['bodega']);
                    $stmt->bindParam(":idP", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":cant", $resProduct, PDO::PARAM_STR);
                    $stmt->execute();
                } else {

                    // Si no hay un producto con una cantidad diferente al alistado ingresa acá
                    $stmt = $db->prepare("INSERT INTO producto_despacho (producto_despacho_idProducto,producto_despacho_idDespacho,producto_despacho_cantidad) VALUES (:idProducto,:idDespacho,:cantidad)");
                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":idDespacho", $lastId, PDO::PARAM_STR);
                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                    $stmt->execute();

                    $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidadAlis=producto_bodega_cantidadAlis-:cant WHERE producto_bodega_idProducto=:idP AND producto_bodega_idBodega=" . $_SESSION['bodega']);
                    $stmt->bindParam(":idP", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":cant", $product['CantAlis'], PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Función para crear un alistado
    public function kit_alistadoCreate($kit, $lastId, $valKit)
    {
        try {

            foreach ($kit as $key) {
                $db = getDB();
                $stmt = $db->prepare("INSERT INTO producto_despacho (producto_despacho_idKit,producto_despacho_idDespacho,producto_despacho_cantidad) VALUES (:idKit,:idDespacho,:cantidad)");
                $stmt->bindParam(":idKit", $key['id'], PDO::PARAM_STR);
                $stmt->bindParam(":idDespacho", $lastId, PDO::PARAM_STR);
                $stmt->bindParam(":cantidad", $key['CantAlis'], PDO::PARAM_STR);
                $stmt->execute();
            }

            foreach ($valKit as $product) {

                $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidadAlis=producto_bodega_cantidadAlis-:cant WHERE producto_bodega_idProducto=:idP AND producto_bodega_idBodega=" . $_SESSION['bodega']);
                $stmt->bindParam(":idP", $product['id'], PDO::PARAM_STR);
                $stmt->bindParam(":cant", $product['CantAlis'], PDO::PARAM_STR);
                $stmt->execute();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Delete recorder
    public function deletedispatch()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {

            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {
                $id_del = encrypt_decrypt('decrypt', $_GET['id']);
                $db = getDB();
                $stmt = $db->prepare("UPDATE despacho SET despacho_estado='inactivo' WHERE idDespacho=:id");
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
                header('Location: ../View/contenido/dispatch-list.php?d=1');
            }
        }
    }

    public function tableDetails($campo)
    {
        try {
            $db = getDB();

            $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_bodega_estado='activo' AND producto_idCliente='$campo'  AND producto_bodega_idBodega=" . $_SESSION['bodega']);

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para agregar un producto
    public function agregarProducto($id, $cant)
    {

        try {

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

            if ($histo['producto_bodega_cantidadAlis'] == 0) {

                return "menorUno";
            } else if ($cant > $histo['producto_bodega_cantidadAlis']) {

                return "cantMayor";
            } else if (empty($_SESSION['despacho'])) {
                $_SESSION['despacho'][$id] = $producto;
                return 'agregado';
            } else if (!empty($_SESSION['despacho'])) {
                $indice = false;
                if (isset($_SESSION['despacho'][$id])) {
                    $indice = true;
                    $_SESSION['despacho'][$id]['CantAlis'] = $_SESSION['despacho'][$id]['CantAlis'] + $producto['CantAlis'];
                    return 'cantExist';
                }
            }

            if ($indice === false) {
                $_SESSION['despacho'][$id] = $producto;

                return 'agregado';
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para eliminar producto de la session
    public function deleteProducto($id)
    {
        session_start(['name' => "SPM"]);

        try {

            unset($_SESSION['despacho'][$id]);

            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para eliminar sessiones
    public function Vaciar()
    {
        session_start(['name' => "SPM"]);

        $url = BASE_URL . 'View/contenido/dispatch-new.php?id=' . $_SESSION['alistado'] . '&status=Vaciado';

        try {
            unset($_SESSION['despacho']);
            unset($_SESSION['despachoKit']);
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para buscar en un alistado
    public function searchEnlisted($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $db = getDB();
            $stmt = $db->prepare("SELECT producto_alistado_idAlistado,producto_alistado_cantidad,producto_alistado_idProducto,alistado_idBodega,alistado_nombrePersona,
            alistado_cedulaPersona,alistado_placaPersona,alistado_idCliente,alistado_clienteF,alistado_codigo,alistado_observacion,
            GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_idCliente,'..',producto_peso,'..', producto_bodega_cantidad  SEPARATOR '__') AS productos 
            FROM producto_alistado 
            INNER JOIN producto ON producto_alistado_idProducto=idProducto
            INNER JOIN producto_bodega ON producto_bodega_idProducto=idProducto
            INNER JOIN alistado ON producto_alistado_idAlistado=idAlistado
            INNER JOIN bodega ON alistado_idBodega=idBodega
            WHERE producto_alistado_idAlistado=:id GROUP BY producto_alistado_idProducto ");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();
            $filas =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para busacr en un Kit
    public function searchKit($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $db = getDB();
            $stmt = $db->prepare("SELECT producto_alistado_idAlistado,producto_alistado_cantidad,producto_alistado_idProducto,alistado_idBodega,alistado_nombrePersona,
            alistado_cedulaPersona,alistado_placaPersona,alistado_idCliente,alistado_clienteF,alistado_codigo,idKit,kit_consecutivo,kit_peso,producto_alistado_idKit,producto_alistado_cantidad,alistado_idCliente
            FROM producto_alistado 
            INNER JOIN kit ON producto_alistado_idKit=idKit
            INNER JOIN alistado ON producto_alistado_idAlistado=idAlistado
            WHERE producto_alistado_idAlistado=:id");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();
            $filas =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para obtener los detalles del alistamiento
    public function enlistedDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {

        try {
            $db = getDB();
            $cond = $table . '_estado';

            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                // $cond='activo' AND $campo
                // $stmt = $db->prepare("SELECT * FROM $table  WHERE $campo LIKE '" . $busqueda . "%' AND $cond='activo' AND alistado_tipo=$alistado LIMIT $limit,$offset");
                $stmt = $db->prepare("SELECT despacho_fechaDs,despacho_consecutivo,despacho_idCliente,idDespacho,despacho_estado, 
                GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_despacho_cantidad  SEPARATOR '__') AS productos,
                GROUP_CONCAT(kit_consecutivo,'..',kit_nombre,'..',kit_peso,'..', producto_despacho_cantidad  SEPARATOR '__') AS kit
                FROM $table INNER JOIN producto_despacho ON producto_despacho_idDespacho=idDespacho 
                LEFT JOIN producto ON producto_despacho_idProducto=idProducto 
                LEFT JOIN kit ON producto_despacho_idKit=idKit
                WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND $cond='activo' 
                AND despacho_idBodega=" . $_SESSION['bodega'] . " GROUP BY idDespacho ORDER BY idDespacho DESCLIMIT $limit,$offset");
            } else {
                $stmt = $db->prepare("SELECT despacho_fechaDs,despacho_consecutivo,despacho_idCliente,idDespacho,despacho_estado, 
                GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_despacho_cantidad  SEPARATOR '__') AS productos,
                GROUP_CONCAT(kit_consecutivo,'..',kit_nombre,'..',kit_peso,'..', producto_despacho_cantidad  SEPARATOR '__') AS kit
                FROM $table INNER JOIN producto_despacho ON producto_despacho_idDespacho=idDespacho 
                LEFT JOIN producto ON producto_despacho_idProducto=idProducto 
                LEFT JOIN kit ON producto_despacho_idKit=idKit   
                WHERE $cond='activo' AND despacho_idBodega=" . $_SESSION['bodega'] . "  GROUP BY  idDespacho ORDER BY idDespacho DESC LIMIT $limit,$offset");
            }

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Validacion de la informacion de la persona
    public function validacionPersona($nombre, $cedula, $placa, $enlisted, $clienteF, $codigo)
    {
        try {

            if ($nombre == $enlisted[0]['alistado_nombrePersona']) {
                if ($cedula == $enlisted[0]['alistado_cedulaPersona']) {
                    if ($placa == $enlisted[0]['alistado_placaPersona']) {
                        if ($clienteF == $enlisted[0]['alistado_clienteF']) {
                            if ($codigo == $enlisted[0]['alistado_codigo']) {
                                return "true";
                            } else {
                                return "El codigo no coincide con el alistado";
                            }
                        } else {
                            return "El Cliente final no coincide con el alistado";
                        }
                    } else {
                        return "La placa del vehiculo no coincide con el alistado";
                    }
                } else {
                    return "La Cedula del conductor no coincide con el alistado";
                }
            } else {
                return "EL nombre del conductor no coincide con el alistado";
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // Registro del los productos enlistados
    public function enlistedRegistration($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $resProduct, $nombre, $cedula, $bodega, $placa, $entradaCons)
    {

        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO alistado (alistado_tipo,alistado_fechaDespacho,alistado_fechaEntrada,alistado_idCliente,alistado_nombrePersona,alistado_cedulaPersona,alistado_idBodega,alistado_placaPersona,alistado_global_consecutivo) VALUES (:tipo,:fechaDs,:fechaIn,:idCliente,:nombre,:cedula,:bodega,:placa,:alConse)");

                $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
                $stmt->bindParam(":fechaIn", $fechaEntrada, PDO::PARAM_STR);
                $stmt->bindParam(":fechaDs", $fechaDespacho, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $cliente, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);
                $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);
                $stmt->bindParam(":alConse", $entradaCons, PDO::PARAM_STR);

                $stmt->execute();

                $lastId = $db->lastInsertId();
                $consecutivo = 'PRI-' . $lastId;
                $stmt = $db->prepare("UPDATE alistado SET alistado_consecutivo=:consecutivo WHERE idAlistado=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();

                foreach ($producto as $product) {
                    $stmt = $db->prepare("INSERT INTO producto_alistado (producto_alistado_idProducto,producto_alistado_idAlistado,producto_alistado_cantidad) VALUES (:idProducto,:idAlistado,:cantidad)");

                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":idAlistado", $lastId, PDO::PARAM_STR);
                    $stmt->bindParam(":cantidad", $resProduct, PDO::PARAM_STR);

                    $stmt->execute();
                }

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "alistado";
                    historial($idUser, $date, $lastId, $accion, $tabla);
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

    // Consulta la informacion de un Kit
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

    // Función paa agregare un Kit
    public function agregarKit($id, $cantidad)
    {
        try {

            if ($cantidad <= 0) {
                return 'menorCero';
            } else {
                $producto_kit = dispatchClass::validacionKit($id, $cantidad);
                if (is_array($producto_kit)) {

                    foreach ($producto_kit as $product) {
                        $producto_kit = dispatchClass::agregarProducto($product['id'], $product['CantAlis']);
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

    // Función para eliminar un Kit
    public function deleteKit($id)
    {
        session_start(['name' => "SPM"]);
        try {
            unset($_SESSION['despachoKit'][$id]);
            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
}
