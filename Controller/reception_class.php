<?php
include_once 'main_class.php';
include_once 'task_class.php';

/* User Registration */
class receptionClass
{
    public function validacionPersona($nombre, $cedula, $placa, $enlisted)
    {
        if ($nombre == $enlisted[0]['alistado_nombrePersona']) {
            if ($cedula == $enlisted[0]['alistado_cedulaPersona']) {
                if ($placa == $enlisted[0]['alistado_placaPersona']) {
                    return "true";
                } else {
                    return "placa";
                }
            } else {
                return "cedula";
            }
        } else {
            return "nombre";
        }
    }

    public function validacion($producto, $enlisted)
    {
        $als = encrypt_decrypt('decrypt', $_SESSION['alistado']);
        $bool = false;
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
                        // echo (" Producto " . $pr['Nombre'] . " cantidad exacta -");
                        $bool = true;
                    } else if ($filas[0]['producto_alistado_cantidad'] < $pr['CantAlis']) {
                        // echo (" Producto " . $pr['Nombre'] . " cantidad mayor al alistado -");
                        $bool = false;
                    } else {
                        // echo (" Producto " . $pr['Nombre'] . " cantidad menor al alistado -");
                        $bool = false;
                    }
                } else {
                    // echo (" Producto " . $pr['Nombre'] . " no está alistado -");
                    $bool = false;
                }
            }
        } else {
            echo (" Cantidad de productos no son iguales a las alistadas ");
            $bool = false;
        }
        return $bool;
    }

    // Función para registrar el ingreso de un producto con un pre-alistamiento realizado
    public function receptionRegistration($idCliente, $producto, $nombre, $cedula, $placa, $bodega, $firma)
    {
        $task = new taskClass;

        $idBodega = encrypt_decrypt('decrypt', $_SESSION['alistado']);

        try {

            // Se valida si el usuario ya cuenta con una session iniciada
            $idUser = $_SESSION['idUsuario'];
            $date = date('Y-m-d H:i:s');
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            // Condicional para validar si el usuario está logeado en el sistema
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

                        $smtp = $db->prepare("SELECT producto_ingresado_id as idIn FROM producto_ingresado WHERE producto_ingresado_idProducto=:pd AND producto_ingresado_idEntrada=:bd LIMIT 1");

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

                        // Se valida si la cantidad seleccionada es igual a la cantidad alistada
                        if ($product['CantAlis'] == $search[0]['cant']) {

                            // Si no hay un producto con una cantidad diferente al alistado ingresa acá
                            if ($search[0]['code'] == 0) {

                                if ($registro == 0) {
                                    $lastId = receptionClass::registroEntrada($idCliente, $nombre, $cedula, $placa, $bodega);
                                    $registro = $_SESSION['sessionlastId'] = $lastId;
                                }

                                $stmt = $db->prepare("INSERT INTO producto_ingresado (producto_ingresado_idProducto,producto_ingresado_idEntrada,producto_ingresado_cantidad) VALUES (:idProducto,:idEntrada,:cantidad)");
                                $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":idEntrada", $lastId, PDO::PARAM_STR);
                                $stmt->bindParam(":cantidad", $search[0]['cant'], PDO::PARAM_STR);
                                $stmt->execute();

                                $total = $product['CantAlis'] - $product['Novedades'];

                                $idUser = $_SESSION['idUsuario'];
                                $accion = "Crear";
                                $tabla = "Ingreso";
                                historial($idUser, $date, $lastId, $accion, $tabla);


                                if ($search[0]['code'] == 0) {

                                    $smtp = $db->prepare("SELECT producto_alistado_id FROM producto_alistado WHERE producto_alistado_idAlistado=:bd");

                                    $smtp->bindParam(":bd", $idBodega, PDO::PARAM_INT);

                                    $smtp->execute();

                                    $add_code = $smtp->fetchAll(PDO::FETCH_ASSOC);


                                    $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idAlistado=:bd AND producto_alistado_id=:idPd");

                                    $stmt->bindParam(":codeIn", $registro, PDO::PARAM_INT);
                                    $stmt->bindParam(":bd", $idBodega, PDO::PARAM_INT);
                                    $stmt->bindParam(":idPd", $add_code[0]['producto_alistado_id'], PDO::PARAM_INT);

                                    $stmt->execute();
                                }

                                // 
                                $smtp = $db->prepare("DELETE FROM producto_alistado WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $smtp->bindParam(":pd", $product['id'], PDO::PARAM_INT);
                                $smtp->bindParam(":bd", $idBodega, PDO::PARAM_INT);

                                $smtp->execute();
                            } else {

                                // Consulta para validar si el alistamiento ya tiene un registro de ingreso
                                $smtp = $db->prepare("SELECT producto_ingresado_idEntrada, producto_ingresado_idProducto FROM producto_ingresado WHERE producto_ingresado_idProducto=:pd AND producto_ingresado_idEntrada=:bd LIMIT 1");

                                $smtp->bindParam(":pd", $product['id'], PDO::PARAM_INT);
                                $smtp->bindParam(":bd", $search[0]['code'], PDO::PARAM_INT);
                                $smtp->execute();

                                $search2 = $smtp->fetchAll(PDO::FETCH_ASSOC);


                                if (empty($search2)) {

                                    $stmt = $db->prepare("INSERT INTO producto_ingresado (producto_ingresado_idProducto,producto_ingresado_idEntrada,producto_ingresado_cantidad) VALUES (:idProducto,:idEntrada,:cantidad)");

                                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                                    $stmt->bindParam(":idEntrada", $search[0]['code'], PDO::PARAM_INT);
                                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);

                                    $stmt->execute();
                                } else {

                                    $stmt = $db->prepare("UPDATE producto_ingresado SET producto_ingresado_cantidad=producto_ingresado_cantidad+:cantAdd WHERE producto_ingresado_idProducto=:pd AND producto_ingresado_idEntrada=:bd");

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

                            // Se actualiza la cantidad cuando se valida el ingreso
                            $total = $product['CantAlis'] - $product['Novedades'];

                            $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad+:cantAdd,producto_bodega_cantidadBlock=producto_bodega_cantidadBlock+:cantBlock  WHERE producto_bodega_idProducto=:idP AND producto_bodega_idBodega=:bodega");

                            $stmt->bindParam(":idP", $product['id'], PDO::PARAM_STR);
                            $stmt->bindParam(":cantAdd", $total, PDO::PARAM_STR);
                            $stmt->bindParam(":cantBlock", $product['Novedades'], PDO::PARAM_STR);
                            $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);

                            $stmt->execute();

                            $task->taskRegistration($product['Descripcion'], $lastId, null, $product['Prioridad'], $product['id'], $product['Novedades'], $_SESSION['bodega']);
                        }

                        // Se valida si la cantidad seleccionada es menor a la cantidad alistada
                        else if ($product['CantAlis'] < $search[0]['cant']) {

                            $total_ingre = $search[0]['cant'] - $product['CantAlis'];

                            $task = new taskClass;

                            if ($registro == 0) {

                                $lastId = receptionClass::registroEntrada($idCliente, $nombre, $cedula, $placa, $bodega);

                                $registro = $_SESSION['sessionlastId'] = $lastId;
                            }

                            if (count($idIngresado) > 0) {

                                // Parte para la creación de un nueva entrada
                                $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=producto_alistado_cantidad-:cantAdd,producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $stmt->bindParam(":cantAdd", $search[0]['cant'], PDO::PARAM_STR);
                                $stmt->bindParam(":codeIn", $lastId, PDO::PARAM_INT);
                                $stmt->bindParam(":pd", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":bd", $idBodega, PDO::PARAM_STR);

                                $stmt->execute();

                                // Parte para la creación de un nueva entrada
                                $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=:cantAdd,producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $stmt->bindParam(":cantAdd", $total_ingre, PDO::PARAM_STR);
                                $stmt->bindParam(":codeIn", $lastId, PDO::PARAM_INT);
                                $stmt->bindParam(":pd", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":bd", $idBodega, PDO::PARAM_STR);

                                $stmt->execute();


                                $stmt = $db->prepare("UPDATE producto_ingresado SET producto_ingresado_cantidad=producto_ingresado_cantidad+:cantidad WHERE producto_ingresado_idProducto=:idProduct AND producto_ingresado_idEntrada=:idEntrada");

                                $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                                $stmt->bindParam(":idProduct", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":idEntrada", $lastId, PDO::PARAM_STR);
                                $stmt->execute();
                            } else {

                                // Parte para la creación de un nueva entrada
                                $stmt = $db->prepare("UPDATE producto_alistado SET producto_alistado_cantidad=:cantAdd,producto_alistado_CodeIngreso=:codeIn WHERE producto_alistado_idProducto=:pd AND producto_alistado_idAlistado=:bd");

                                $stmt->bindParam(":cantAdd", $total_ingre, PDO::PARAM_STR);
                                $stmt->bindParam(":codeIn", $lastId, PDO::PARAM_INT);
                                $stmt->bindParam(":pd", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":bd", $idBodega, PDO::PARAM_STR);

                                $stmt->execute();

                                $stmt = $db->prepare("INSERT INTO producto_ingresado (producto_ingresado_idProducto,producto_ingresado_idEntrada,producto_ingresado_cantidad) VALUES (:idProducto,:idEntrada,:cantidad)");
                                $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                                $stmt->bindParam(":idEntrada", $lastId, PDO::PARAM_STR);
                                $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                                $stmt->execute();

                                $task->taskRegistration($product['Descripcion'], $lastId, null, $product['Prioridad'], $product['id'], $product['Novedades'], $_SESSION['bodega']);
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

                            // Se actualiza la cantidad cuando se valida el ingreso
                            $total = $product['CantAlis'] - $product['Novedades'];

                            $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad+:cantAdd,producto_bodega_cantidadBlock=producto_bodega_cantidadBlock+:cantBlock  WHERE producto_bodega_idProducto=:idP AND producto_bodega_idBodega=:bodega");

                            $stmt->bindParam(":idP", $product['id'], PDO::PARAM_STR);
                            $stmt->bindParam(":cantAdd", $total, PDO::PARAM_STR);
                            $stmt->bindParam(":cantBlock", $product['Novedades'], PDO::PARAM_STR);
                            $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);

                            $stmt->execute();
                        }
                    }
                }

                if ($registro) {

                    receptionClass::add_temp_img_product($firma, $registro);
                }

                unset($_SESSION['recepcion']);
                unset($_SESSION['recepcion2']);
                unset($_SESSION['id_client_pro']);
                unset($_SESSION['sessionlastId']);

                $db = null;

                return 'true';
                exit();
            } else {

                $db = null;

                return false;
                exit();
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    // Función para registrar el ingreso de un producto sin un pre-alistamiento realizado
    public function receptionRegistration2($idCliente, $producto, $nombre, $cedula, $placa, $bodega, $firma)
    {
        $task = new taskClass;
        if (isset($_SESSION['alistado'])) {
            $als = encrypt_decrypt('decrypt', $_SESSION['alistado']);
        }

        try {

            $tipo = "Entrada";
            $fechaEntrada = date('d-m-y');
            $fechaDespacho = "";

            // Se avalida si el usuario ya cuenta con una session iniciada
            $idUser = $_SESSION['idUsuario'];
            $date = date('Y-m-d H:i:s');
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            // Condicional para validar si el usuario está logeado en el sistema
            if ($count <= 1) {

                $stmt = $db->prepare("INSERT INTO entrada (entrada_fecha,entrada_nombrePersona,entrada_cedulaPersona,entrada_placaPersona,entrada_idCliente,entrada_idBodega) VALUES (:fecha,:nombre,:cedula,:placa,:idClient,:bodega)");
                $stmt->bindParam(":fecha", $date, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
                $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);

                $stmt->bindParam(":idClient", $idCliente, PDO::PARAM_INT);
                $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);

                $stmt->execute();
                $lastId = $db->lastInsertId();

                $task = new taskClass;
                $consecutivo = 'ING-' . $lastId;
                $stmt = $db->prepare("UPDATE entrada SET entrada_consecutivo=:consecutivo WHERE idEntrada=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();

                foreach ($producto as $product) {

                    $stmt = $db->prepare("INSERT INTO producto_ingresado (producto_ingresado_idProducto,producto_ingresado_idEntrada,producto_ingresado_cantidad) VALUES (:idProducto,:idEntrada,:cantidad)");
                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":idEntrada", $lastId, PDO::PARAM_STR);
                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);
                    $stmt->execute();

                    $total = $product['CantAlis'] - $product['Novedades'];

                    $stmt = $db->prepare("UPDATE producto_bodega SET  producto_bodega_cantidad=producto_bodega_cantidad+:cantAdd,producto_bodega_cantidadBlock=producto_bodega_cantidadBlock+:cantBlock  WHERE producto_bodega_idProducto=:idP AND producto_bodega_idBodega=:bodega");

                    $stmt->bindParam(":idP", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":cantAdd", $total, PDO::PARAM_STR);
                    $stmt->bindParam(":cantBlock", $product['Novedades'], PDO::PARAM_STR);
                    $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);

                    $stmt->execute();

                    $stmt = $db->prepare("UPDATE alistado SET alistado_estado='Recepción' WHERE idAlistado=:idP");
                    $stmt->bindParam(":idP", $als, PDO::PARAM_STR);
                    $stmt->execute();

                    $task->taskRegistration($product['Descripcion'], $lastId, null, $product['Prioridad'], $product['id'], $product['Novedades'], $_SESSION['bodega']);

                    if ($stmt->rowCount() >= 1) {

                        $idUser = $_SESSION['idUsuario'];
                        $accion = "Crear";
                        $tabla = "Ingreso";
                        historial($idUser, $date, $lastId, $accion, $tabla);
                    }
                }

                if ($lastId) {

                    receptionClass::add_temp_img_product($firma, $lastId);
                }

                unset($_SESSION['recepcion']);
                unset($_SESSION['recepcion2']);
                unset($_SESSION['id_client_pro']);
                unset($_SESSION['sessionlastId']);

                $db = null;

                return 'true';
                exit();
            } else {

                $db = null;

                return false;
                exit();
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

            $stmt = $db->prepare("INSERT INTO imagen(imagen_nombre,imagen_tipo,imagen_size,imagen_fecha,entrada_firmaId) VALUES (:imagen,:tipo,:size,:fecha,:entrada)");

            $date = date('Y-m-d H:i:s', time());
            $stmt->bindParam(":imagen", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $stmt->bindParam(":size", $size, PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $date);
            $stmt->bindParam(":entrada", $uid, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } else {
            return false;
        }
    }

    public function registroEntrada($idCliente, $nombre, $cedula, $placa, $bodega)
    {
        $db = getDB();

        $tipo = "Entrada";
        $date = date('d-m-y');
        $fechaDespacho = "";

        $stmt = $db->prepare("INSERT INTO entrada (entrada_fecha,entrada_nombrePersona,entrada_cedulaPersona,entrada_placaPersona,entrada_idCliente,entrada_idBodega) VALUES (:fecha,:nombre,:cedula,:placa,:idClient,:bodega)");
        $stmt->bindParam(":fecha", $date, PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
        $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);

        $stmt->bindParam(":idClient", $idCliente, PDO::PARAM_INT);
        $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);

        $stmt->execute();
        $lastId = $db->lastInsertId();

        $task = new taskClass;
        $consecutivo = 'ING-' . $lastId;
        $stmt = $db->prepare("UPDATE entrada SET entrada_consecutivo=:consecutivo WHERE idEntrada=:id");
        $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
        $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
        $stmt->execute();

        return $lastId;
    }

    public function deleteReception()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {

            session_start(['name' => 'SPM']);
            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {
                $id_del = encrypt_decrypt('decrypt', $_GET['id']);

                $db = getDB();
                $stmt = $db->prepare("UPDATE entrada SET entrada_estado='inactivo' WHERE idEntrada=:id");
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
                return true;
            }
        }
    }

    public function tableDetails($table, $campo, $campo_1, $bus_1)
    {
        try {
            $db = getDB();

            if ($campo_1 != "") {
                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto
                FROM producto INNER JOIN producto_bodega
                ON idProducto=producto_bodega_idProducto
                WHERE producto_bodega_estado='activo' AND producto_idCliente='$campo' AND producto_bodega_idBodega=" . $_SESSION['bodega'] . " AND $campo_1 LIKE '" . $bus_1 . "%' ORDER BY idProducto DESC");
            } else {
                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_bodega_estado='activo' AND producto_idCliente='$campo' AND producto_bodega_idBodega=" . $_SESSION['bodega'] . "  ORDER BY idProducto DESC");
            }

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Ingreso de los productos con un pre-alistamiento realizado
    public function agregarProducto($id, $cant)
    {

        session_start(['name' => 'SPM']);

        $url = BASE_URL . 'View/contenido/reception-new.php';

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
                'Novedades' => 0,
                'Descripcion' => '',
                'Prioridad' => '',
                'CantExis' => $histo['producto_bodega_cantidad'],
                'Peso' => $histo['producto_peso']
            ];

            if ($cant < 0) {
                // header("Location: $url" . '&status=cantidadMenor'); // Page redirecting to home.php 
                return 'cantidadMenor';
                exit;
            } else if (empty($_SESSION['recepcion'])) {
                $_SESSION['recepcion'][$id] = $producto;

                // header("Location:" . BASE_URL . 'View/contenido/reception-new.php?id=' . $_SESSION['alistado']);
                // echo 'nuevo';die;
                return 'agregado';
                exit();
            } else if (!empty($_SESSION['recepcion'])) {
                $indice = false;
                if (isset($_SESSION['recepcion'][$id])) {
                    $indice = true;
                    $_SESSION['recepcion'][$id]['CantAlis'] = $_SESSION['recepcion'][$id]['CantAlis'] + $producto['CantAlis'];

                    // echo 'otro';die;

                    header("Location:" . BASE_URL . 'View/contenido/reception-new.php?id=' . $_SESSION['alistado']);
                    // return 'agregado';
                    // exit();
                }
            }
            if ($indice === false) {
                $_SESSION['recepcion'][$id] = $producto;
                return 'agregado';
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function agregarProducto2($id, $cant)
    {

        session_start(['name' => 'SPM']);


        try {

            $url = BASE_URL . 'View/contenido/reception-new.php';

            $db = getDB();

            $stmt = $db->prepare("SELECT * FROM producto  WHERE idProducto='$id'");
            $stmt->execute();
            $histo =  $stmt->fetch(PDO::FETCH_ASSOC);


            $producto = [
                'id' => $histo['idProducto'],
                'Codigo' => $histo['producto_codigo'],
                'Nombre'  => $histo['producto_nombre'],
                'Cliente' => $histo['producto_idCliente'],
                'CantAlis' => $cant,
                'Novedades' => 0,
                'Descripcion' => '',
                'Prioridad' => '',
                'CantExis' => $histo['producto_cantidad'],
                'Peso' => $histo['producto_peso']
            ];

            if ($cant < 0) {
                return 'cantidadMenor';
                exit;
            } else if (empty($_SESSION['recepcion2'])) {
                $_SESSION['recepcion2'][$id] = $producto;
                header("Location: $url");
            } else if (!empty($_SESSION['recepcion2'])) {
                $indice = false;
                if (isset($_SESSION['recepcion2'][$id])) {
                    $indice = true;

                    $_SESSION['recepcion2'][$id]['CantAlis'] = $_SESSION['recepcion2'][$id]['CantAlis'] + $producto['CantAlis'];

                    // header("Location:" . BASE_URL . 'View/contenido/reception-new.php?status=existe'); // Page redirecting to home.php 
                    return 'existe';
                    exit;
                }
            }
            if ($indice === false) {
                $_SESSION['recepcion2'][$id] = $producto;
                return 'agregado';
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function deleteProducto($id)
    {

        try {
            session_start(['name' => "SPM"]);

            // var_dump($_SESSION['recepcion']);die;
            unset($_SESSION['recepcion'][$id]);

            return true;
            exit();
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    public function deleteProductoNew($id)
    {

        try {
            session_start(['name' => "SPM"]);

            unset($_SESSION['recepcion2'][$id]);

            return true;
            exit();
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function Vaciar()
    {
        session_start(['name' => "SPM"]);

        $url = BASE_URL . 'View/contenido/reception-new.php?id=' . $_SESSION['alistado'] . '&status=Vaciado';

        try {
            unset($_SESSION['recepcion']);
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function Vaciar2()
    {
        session_start(['name' => "SPM"]);

        $url = BASE_URL . 'View/contenido/reception-new.php?id=' . $_SESSION['alistado'] . '&status=Vaciado';

        try {
            unset($_SESSION['recepcion2']);
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
    public function searchEnlisted($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $db = getDB();
        $stmt = $db->prepare("SELECT producto_alistado_idAlistado,producto_alistado_cantidad,producto_alistado_idProducto,producto_idCliente,alistado_idBodega,alistado_nombrePersona,alistado_cedulaPersona,alistado_placaPersona,alistado_idCliente,bodega_nombre,idBodega,GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_idCliente,'..',producto_peso,'..', producto_bodega_cantidad  SEPARATOR '__') AS productos FROM producto_alistado 
        INNER JOIN producto ON producto_alistado_idProducto=idProducto
        INNER JOIN producto_bodega ON producto_bodega_idProducto=idProducto
        INNER JOIN alistado ON producto_alistado_idAlistado=idAlistado
        INNER JOIN bodega ON alistado_idBodega=idBodega
        WHERE producto_alistado_idAlistado=:id AND alistado_estado='activo' GROUP BY producto_alistado_idProducto ");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        $filas =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        // var_dump($filas[0]);die;
        return $filas;
    }

    public function enlistedDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {
        try {
            $db = getDB();
            $cond = $table . '_estado';

            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                $stmt = $db->prepare("SELECT entrada.*, GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_ingresado_cantidad  SEPARATOR '__') AS productos 
                FROM $table INNER JOIN producto_ingresado ON producto_ingresado_idEntrada=idEntrada 
                INNER JOIN producto ON producto_ingresado_idProducto=idProducto 
                WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND $cond='activo' 
                AND entrada_idBodega=" . $_SESSION['bodega'] . " GROUP BY idEntrada ORDER BY idEntrada DESC LIMIT $limit,$offset");
            } else {
                $stmt = $db->prepare("SELECT entrada.*, GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..',producto_ingresado_cantidad SEPARATOR '__') AS productos FROM `entrada` INNER JOIN producto_ingresado ON idEntrada=producto_ingresado_idEntrada INNER JOIN producto ON producto_ingresado_idProducto = idProducto WHERE entrada_estado = 'activo' AND entrada_idBodega=" . $_SESSION['bodega'] . " GROUP BY idEntrada ORDER BY idEntrada DESC LIMIT $limit,$offset");
            }

            $stmt->execute();


            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para crear un nuevo, alistamiento cuando hay productos que sobraron en un ingreso
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
                $stmt->bindParam(":idCliente", $cliente, PDO::PARAM_INT);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":cedula", $cedula, PDO::PARAM_INT);
                $stmt->bindParam(":bodega", $bodega, PDO::PARAM_STR);
                $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);
                $stmt->bindParam(":alConse", $entradaCons, PDO::PARAM_STR);

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

    // Función para generar un excel
    function GenerateExcel($tabla, $id)
    {
        session_start(['name' => "SPM"]);
        $db = getDB();
        $date = date('d-m-Y');
        $time = date('H:i:s');
        $estado = $tabla . '_estado';

        if ($id == '') {
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT alistado.*,cliente_nombre,cliente_apellido,bodega_nombre FROM producto_alistado 
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

            var_dump($row);
            die;
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
                <th></th>
                <th class='th'>CONDUCTOR</th>
                <th></th>
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

            header("Content-Disposition: attachment; filename=clientes_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        } else {

            $individual = 'id' . ucfirst($tabla);
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT cliente.*,bodega_nombre,
                GROUP_CONCAT(producto_consecutivo,'..',producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_bodega_cantidad,'..',producto_alerta,'..',producto_bodega_cantidadAlis,'..',producto_minimo,'..',producto_bodega_cantidadBlock  SEPARATOR '__') AS productos,
                GROUP_CONCAT(idBodega,'..',bodega_nombre,'..', bodega_estado  SEPARATOR '__') AS bodegas
                FROM cliente 
                LEFT JOIN producto ON idCliente=producto_idCliente
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                INNER JOIN bodega ON producto_bodega_idBodega=idBodega
                WHERE $individual=:id AND producto_bodega_idBodega=:bodega");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $db->prepare("SELECT idBodega,bodega_nombre, bodega_estado
                FROM cliente 
                LEFT JOIN cliente_bodega ON idCliente=cliente_bodega_cliente
                INNER JOIN bodega ON cliente_bodega_bodega=idBodega
                WHERE $individual=:id");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $bodegass = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $db->prepare("SELECT cliente.*,bodega_nombre
                GROUP_CONCAT(producto_consecutivo,'..',producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_bodega_cantidad,'..',producto_alerta,'..',producto_bodega_cantidadAlis,'..',producto_minimo,'..',producto_bodega_cantidadBlock  SEPARATOR '__') AS productos,
                GROUP_CONCAT(idBodega,'..',bodega_nombre,'..', bodega_estado  SEPARATOR '__') AS bodegas
                FROM cliente 
                LEFT JOIN producto ON idCliente=producto_idCliente
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                INNER JOIN bodega ON producto_bodega_idBodega=idBodega
                WHERE $individual=:id AND producto_bodega_idBodega=:bodega AND $estado='activo'");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $db->prepare("SELECT idBodega,bodega_nombre, bodega_estado
                FROM cliente 
                LEFT JOIN cliente_bodega ON idCliente=cliente_bodega_cliente
                INNER JOIN bodega ON cliente_bodega_bodega=idBodega
                WHERE $individual=:id AND $estado='activo'");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $bodegass = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

                </tr>
                <thead> 
                    <th class='th'>Consecutivo</th>
                    <th class='th'>Tipo de identificación</th>
                    <th class='th'># Identificación</th>
                    <th class='th'>Digito de verificación</th>
                    <th class='th'>Ciudad</th>
                    <th class='th'>Nombre / Razón Social</th>
                    <th class='th'>Apellido</th>
                    <th class='th'>Actividad económica</th>
                    <th class='th'>Dirección principal</th>
                    <th class='th'>Teléfono principal</th>
                    <th class='th'>Tipo de Cliente</th>
                    <th class='th'>Estado</th>
                </thead>";


            foreach ($row as $r) {

                $salida .= "
                        <tr>
                            <td>" . $r['cliente_consecutivo'] . "</td> 
                            <td>" . $r['cliente_tpId'] . "</td> 
                            <td>" . $r['cliente_nDocument'] . "</td>
                            <td>" . $r['cliente_dv'] . "</td>
                            <td>" . $r['cliente_estado']  . "</td>
                            <td>" . $r['cliente_nombre'] . "</td>
                            <td>" . $r['cliente_apellido'] . "</td>
                            <td>" . $r['cliente_actEco'] . "</td>
                            <td>" . $r['cliente_direccion'] . "</td>
                            <td>" . $r['cliente_telefono'] . "</td>
                            <td>" . $r['cliente_tpCliente'] . "</td>
                            <td>" . $r['cliente_estado'] . "</td>
                        </tr>
                        <tr>
                        </tr>
                        <tr>
                        </tr>
                        <tr>
                            <th class='th'>Consecutivo</th>
                            <th class='th'>Codigo</th>
                            <th class='th'>Nombre</th>
                            <th class='th'>Peso</th>
                            <th class='th'>Inventario en bodega</th>
                            <th class='th'>Peso Total</th>
                            <th class='th'>Alerta</th>
                            <th class='th'>Pedidos en Alistamiento</th>
                            <th class='th'>Inventario Disponible</th>
                            <th class='th'>Alerta Cantidad Minima</th>
                            <th class='th'>Cantidad Bloqueada</th> 
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
                    $cantidadBlock = intval($producto[8]);
                    $cantidadAlis = intval($producto[6]);
                    $pesoT = $pesoT + ($peso * $cantidad);
                    $cantidadT = $cantidadT + $peso;
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
                                        <td class='invB' scope='col'>" .
                        ($cantidad + $cantidadBlock + $cantidadAlis) .
                        "</td>
                                        <td scope='col'>" .
                        ($peso * $cantidad) . "Kg
                                        </td>
                                        <td scope='col'>" .
                        $producto[5] .
                        "</td>
                                        <td scope='col'>" .
                        $producto[6] .
                        "</td>
                                        <td class='dispo' scope='col'>" .
                        $producto[4] .
                        "</td>
                                        <td scope='col'>" .
                        $producto[7] .
                        "</td>
                                        <td class='block' scope='col'>" .
                        $producto[8] .
                        "</td>
                                        
                                    </tr>
                            
                                ";
                }
            }
            $salida .= "
            <tr>   
            </tr>
            <tr>   
            </tr>
            <tr> 
                <th></th>
                <th class='th'>Bodegas Asociadas</th>
                <th></th>  
            </tr>
            <tr>
                <th class='th'>ID</th>
                <th class='th'>Nombre</th>
                <th class='th'>Estado</th>
                
            </tr>
    
        ";
            if (!empty($bodegass)) {
                foreach ($bodegass as $bg) {

                    $salida .= "
                        <tr>
                            <td scope='col'>" .
                        $bg['idBodega'] .
                        "</td>

                            <td scope='col'>" .
                        $bg['bodega_nombre'] .
                        "</td>

                            <td scope='col'>" .
                        $bg['bodega_estado'] .
                        "</td>
                                      
                        </tr>
                                ";
                }
            }

            $salida .= "</table>";

            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=Registro_despacho" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        }
    }
}
