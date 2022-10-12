<?php
include_once 'main_class.php';
/* User Registration */
class kitClass
{

    // Función para obtener los detalles de los Kits
    public function kitDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {

        try {
            $db = getDB();

            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {

                $stmt = $db->prepare("SELECT idProducto_kit,kit_consecutivo,kit_idCliente,idKit,kit_estado,producto_uniPeso,kit_nombre,
                GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_kit_cantidad  SEPARATOR '__') AS productos 
                FROM $table 
                INNER JOIN producto_kit ON idKit=producto_kit_idKit 
                INNER JOIN producto ON idProducto=producto_kit_idProducto  
                WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' 
                AND kit_estado='activo' AND kit_idBodega=" . $_SESSION['bodega'] . " GROUP BY idKit LIMIT $limit,$offset");
            } else {

                $stmt = $db->prepare("SELECT idProducto_kit,kit_consecutivo,kit_idCliente,idKit,kit_estado,producto_uniPeso,kit_nombre,GROUP_CONCAT(producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_kit_cantidad SEPARATOR '__') AS productos FROM kit
                INNER JOIN producto_kit ON idKit=producto_kit_idKit 
                INNER JOIN producto ON idProducto=producto_kit_idProducto  
                WHERE kit_estado='activo' AND kit_idBodega=" . $_SESSION['bodega'] . " GROUP BY idKit LIMIT $limit,$offset");
            }
            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para registrar los kits asociados
    public function kitRegistration($cliente, $nombre, $producto, $peso)
    {

        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO kit (kit_idCliente,kit_idBodega,kit_nombre,kit_peso) VALUES (:idCliente,:bodega,:nombre,:peso)");

                $stmt->bindParam(":idCliente", $cliente, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":peso", $peso, PDO::PARAM_STR);

                $stmt->execute();

                $lastId = $db->lastInsertId();
                $consecutivo = 'KIT-' . $lastId;

                $stmt = $db->prepare("UPDATE kit SET kit_consecutivo=:consecutivo WHERE idKit=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();

                foreach ($producto as $product) {

                    $stmt = $db->prepare("INSERT INTO producto_kit (producto_kit_idProducto,producto_kit_idKit,producto_kit_cantidad) VALUES (:idProducto,:idKit,:cantidad)");

                    $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_STR);
                    $stmt->bindParam(":idKit", $lastId, PDO::PARAM_STR);
                    $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_STR);

                    $stmt->execute();
                }

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "kit";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

                // session_start(['name' => "SPM"]);

                unset($_SESSION['id_client_pro']);
                unset($_SESSION['kitNew']);

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

    // Función para eliminar los Kits
    public function deleteEnlisted()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {

            session_start(['name' => 'SPM']);
            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {
                $id_del = encrypt_decrypt('decrypt', $_GET['id']);

                $db = getDB();

                $stmt = $db->prepare("UPDATE kit SET kit_estado='inactivo' WHERE idKit=:id");
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
                header('Location: ../View/contenido/kit-list.php?d=1');
            }
        }
    }

    // Función para actualizar los Kits
    public function updateKit($cliente, $nombre, $producto, $peso)
    {
        try {


            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $id = encrypt_decrypt('decrypt', $_SESSION['kitUpdate']);

                foreach ($producto as $product) {

                    $stmt = $db->prepare("SELECT * FROM producto_kit WHERE producto_kit_idKit=:id AND producto_Kit_idProducto=:idPr");
                    $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                    $stmt->bindParam(":idPr", $product['id'], PDO::PARAM_STR);
                    $stmt->execute();

                    $conteo = $stmt->rowCount();

                    $cantAlis = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $cantPro = $producto[$product['id']]['CantAlis'];

                    if ($conteo <= 0) {

                        $stmt = $db->prepare("INSERT INTO producto_kit(producto_kit_idProducto,producto_kit_idKit,producto_kit_cantidad) VALUES (:idProducto,:idKit,:cantidad)");

                        $stmt->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                        $stmt->bindParam(":idKit", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":cantidad", $product['CantAlis'], PDO::PARAM_INT);

                        $stmt->execute();
                    } else {

                        if ($cantPro > $cantAlis[0]['producto_kit_cantidad']) {

                            $cantTo = $cantPro - $cantAlis[0]['producto_kit_cantidad'];

                            $stmr = $db->prepare("UPDATE producto_kit SET producto_kit_cantidad=producto_kit_cantidad+:cant WHERE producto_kit_idProducto=:idProducto AND producto_kit_idKit=:idKit");

                            $stmr->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                            $stmr->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                            $stmr->bindParam(":idKit", $id, PDO::PARAM_INT);

                            $stmr->execute();
                        } elseif ($cantPro < $cantAlis[0]['producto_kit_cantidad']) {


                            $cantTo = $cantAlis[0]['producto_kit_cantidad'] - $cantPro;


                            $stmr = $db->prepare("UPDATE producto_kit SET producto_kit_cantidad=producto_kit_cantidad-:cant WHERE producto_kit_idProducto=:idProducto AND producto_kit_idKit=:idKit");
                            $stmr->bindParam(":cant", $cantTo, PDO::PARAM_INT);
                            $stmr->bindParam(":idProducto", $product['id'], PDO::PARAM_INT);
                            $stmr->bindParam(":idKit", $id, PDO::PARAM_INT);

                            $stmr->execute();
                        }
                    }
                }

                $stmt = $db->prepare("UPDATE kit SET kit_nombre=:nombre, kit_peso=:peso WHERE idKit=:id");

                $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":peso", $peso, PDO::PARAM_STR);

                $stmt->execute();

                $lastId = $db->lastInsertId();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Actualizar";
                    $tabla = "kit";
                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

                session_start(['name' => "SPM"]);

                unset($_SESSION['kitUp']);

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

    // Función para obtener los detalles de los productos por bodega
    public function tableDetails($table, $campo, $limit, $offset, $busqueda)
    {
        try {
            $db = getDB();
            $cond = $table . '_estado';

            $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_bodega_estado='activo' AND producto_idCliente='$campo' AND producto_bodega_cantidad>=0 AND producto_bodega_idBodega=" . $_SESSION['bodega']);

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
            } else if (empty($_SESSION['kitNew'])) {
                $_SESSION['kitNew'][$id] = $producto;

                return 'Agregado';
                exit();
            } else if (!empty($_SESSION['kitNew'])) {
                $indice = false;
                if (isset($_SESSION['kitNew'][$id])) {
                    $indice = true;
                    $_SESSION['kitNew'][$id]['CantAlis'] = $_SESSION['kitNew'][$id]['CantAlis'] + $producto['CantAlis'];

                    header('Location:' . BASE_URL . 'View/contenido/kit-new.php');
                }
            }
            if ($indice === false) {
                $_SESSION['kitNew'][$id] = $producto;
                return 'Agregado';
                exit();
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para eliminar el KIT
    public function deleteProducto($id)
    {
        session_start(['name' => "SPM"]);
        try {

            unset($_SESSION['kitNew'][$id]);

            return true;

            exit();
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Funcion para eliminar la data de la sesion de Nunevo Kit 
    public function Vaciar()
    {
        $url = BASE_URL . 'View/contenido/kit-new.php?status=Vaciado';

        try {

            session_start(['name' => "SPM"]);
            unset($_SESSION['kitNew']);
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para buscar un Kit
    public function searchKit($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $db = getDB();
        $stmt = $db->prepare("SELECT producto_codigo,producto_nombre,producto_idCliente,producto_peso,producto_bodega_cantidad,producto_kit_cantidad,producto_kit_idKit,producto_kit_idProducto 
        FROM producto_kit 
        INNER JOIN producto ON producto_kit_idProducto=idProducto 
        INNER JOIN producto_bodega ON idProducto=producto_bodega_idProducto
        WHERE producto_kit_idKit=:id AND producto_bodega_idBodega=" . $_SESSION['bodega']);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        $filas =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        kitClass::crearSesionUp($filas);
    }


    // Función para eliminar un kit de la session
    private function crearSesionUp($filas)
    {

        foreach ($filas as $row) {

            $id = $row['producto_kit_idProducto'];
            try {

                $producto = [
                    'id' => $row['producto_kit_idProducto'],
                    'Codigo' => $row['producto_codigo'],
                    'Nombre'  => $row['producto_nombre'],
                    'Cliente' => $row['producto_idCliente'],
                    'CantAlis' => $row['producto_kit_cantidad'],
                    'CantExis' => $row['producto_bodega_cantidad'],
                    'Peso' => $row['producto_peso'],
                    'Act_val' => 0,
                    'Bool' => 1
                ];
                if (empty($_SESSION['kitUp'])) {
                    $_SESSION['kitUp'][$id] = $producto;
                } else if (!empty($_SESSION['kitUp'])) {

                    if (isset($_SESSION['kitUp'][$id])) {

                        if ($_SESSION['kitUp'][$id]['Bool'] != 1) {
                            $_SESSION['kitUp'][$id]['CantAlis'] = $_SESSION['kitUp'][$id]['CantAlis'] + $producto['CantAlis'];
                        }
                    } else {
                        $_SESSION['kitUp'][$id] = $producto;
                    }
                }
            } catch (Exception $e) {
                echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
            }
        }
    }

    // Función para eliminar un prodcuto de un kit
    public function deleteProductoUp($id)
    {
        session_start(['name' => "SPM"]);
        $up = encrypt_decrypt('encrypt', 'up');
        $url = BASE_URL . 'View/contenido/kit-new.php?upS=' . $up . '&idUp=' . $_SESSION['kitUpdate'] . '&idCli=' . $_SESSION['idCliUp'];

        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count <= 1) {

                $_SESSION['kitUp'][$id]['CantAlis'] = 0;

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
            }
            if ($histo['producto_bodega_cantidad'] == 0) {
                // $url = $url . '&status=ProductoAgotado';
                // header("Location: $url"); // Page redirecting to home.php 

                return 'cantidadAgotado';
            } else if ($cant > $histo['producto_bodega_cantidad']) {
                // $url = $url . '&status=cantidadMayorQueExistencia';
                header("Location: $url"); // Page redirecting to home.php 
            } else if (isset($_SESSION['kitUp'][$id])) {

                if ($cant > $histo['producto_bodega_cantidad']) {

                    return 'cantidadMayor';
                    // $url = $url . '&status=cantidadMayorExitente';
                    // header("Location: $url"); // Page redirecting to home.php 
                } else {
                    $res =  $_SESSION['kitUp'][$id]['CantAlis'] +  $cant;

                    $prodRest = $histo['producto_bodega_cantidad'] - $cant;

                    $_SESSION['kitUp'][$id]['CantExis'] = $prodRest;

                    $_SESSION['kitUp'][$id]['CantAlis'] =  $res;

                    return 'cantExist';
                    // header("Location:" . $url . '&status=existe');
                }
            } else {

                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
                FROM producto INNER JOIN producto_bodega
                ON idProducto=producto_bodega_idProducto
                WHERE producto_estado='activo' AND idProducto='$id'");
                $stmt->execute();
                $histo =  $stmt->fetch(PDO::FETCH_ASSOC);

                $res =  $_SESSION['kitUp'][$id]['CantAlis'] +  $cant;

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

                // echo 'aca';
                // die;
                if (empty($_SESSION['kitUp'])) {
                    $_SESSION['kitUp'][$id] = $producto;
                    // header("Location:" . $url . '&status=Agregado');

                    header('Location:' . $url);
                } else if (!empty($_SESSION['kitUp'])) {

                    $_SESSION['kitUp'][$id] = $producto;
                    header("Location:" . $url . '&status=Agregado');
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

        $url = BASE_URL . 'View/contenido/kit-new.php?upS=' . $up . '&idUp=' . $_SESSION['kitUpdate'] . '&status=Vaciado';;


        try {

            foreach ($_SESSION['kitUp'] as $row) {
                kitClass::deleteProductoUp($row['id']);
            }
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }
}
