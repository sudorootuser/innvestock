<?php
include_once 'main_class.php';
include_once 'task_class.php';
class productClass
{
    // Función para registrar los productos
    public function productRegistration($codigo, $nombre, $rotacion, $diasAviso, $minimo, $maximo, $precio, $descripcion, $uniCant, $peso, $modelo, $serial, $lote, $marca, $fechaVenc, $nContenedor, $ancho, $alto, $largo, $uniDimen, $idCliente, $rfid, $subinventario, $ubicacion_new)
    {
        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $date = date('Y-m-d H:i:s', time());

                $stmt = $db->prepare("INSERT INTO producto(producto_codigo,producto_nombre,producto_rotacion,producto_diasAviso,producto_minimo,producto_maximo,producto_precio,producto_descripcion,producto_uniCant,producto_peso,producto_modelo,producto_serial,producto_lote,producto_marca,producto_fechaVenc,producto_nContenedor,producto_ancho,producto_alto,producto_largo,producto_uniDimen,producto_idCliente,producto_RFID,producto_subInventario) 
                VALUES (:codigo,:nombre,:rotacion,:diasAviso,:minimo,:maximo,:precio,:descripcion,:uniCant,:peso,:modelo,:serial,:lote,:marca,:fechaVenc,:nContenedor,:ancho,:alto,:largo,:uniDimen,:idCliente,:rfid,:subinventario)");

                $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":rotacion", $rotacion, PDO::PARAM_STR);
                $stmt->bindParam(":diasAviso", $diasAviso, PDO::PARAM_STR);
                $stmt->bindParam(":minimo", $minimo, PDO::PARAM_STR);

                $stmt->bindParam(":maximo", $maximo, PDO::PARAM_STR);
                $stmt->bindParam(":precio", $precio, PDO::PARAM_STR);
                $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(":uniCant", $uniCant, PDO::PARAM_STR);

                $stmt->bindParam(":peso", $peso, PDO::PARAM_STR);
                $stmt->bindParam(":modelo", $modelo, PDO::PARAM_STR);
                $stmt->bindParam(":serial", $serial, PDO::PARAM_STR);

                $stmt->bindParam(":lote", $lote, PDO::PARAM_STR);
                $stmt->bindParam(":marca", $marca, PDO::PARAM_STR);
                $stmt->bindParam(":fechaVenc", $fechaVenc, PDO::PARAM_STR);
                $stmt->bindParam(":nContenedor", $nContenedor, PDO::PARAM_STR);
                $stmt->bindParam(":ancho", $ancho, PDO::PARAM_STR);

                $stmt->bindParam(":alto", $alto, PDO::PARAM_STR);
                $stmt->bindParam(":largo", $largo, PDO::PARAM_STR);
                $stmt->bindParam(":uniDimen", $uniDimen, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $idCliente, PDO::PARAM_STR);
                $stmt->bindParam(":rfid", $rfid, PDO::PARAM_STR);
                $stmt->bindParam(":subinventario", $subinventario, PDO::PARAM_STR);


                $stmt->execute();

                $lastId = $db->lastInsertId();

                $consecutivo = 'PR-' . $lastId;

                $stmt = $db->prepare("UPDATE producto SET producto_consecutivo=:consecutivo WHERE idProducto=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();

                $stmt = $db->prepare("INSERT INTO producto_bodega(producto_bodega_idProducto,producto_bodega_idBodega,producto_bodega_fechaIngreso,producto_bodega_ubicacion) VALUES (:producto,:bodega,:fecha,:ubicacion)");
                $stmt->bindParam(":producto", $lastId, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                $stmt->bindParam(":fecha", $date, PDO::PARAM_STR);
                $stmt->bindParam(":ubicacion", $ubicacion_new, PDO::PARAM_STR);

                $stmt->execute();


                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];

                    $accion = "Crear";
                    $tabla = "producto";

                    historial($idUser, $date, $lastId, $accion, $tabla);
                }

                $db = null;

                return $lastId;
            } else {

                $db = null;

                return false;
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    // Delete recorder
    public function deleteProduct()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            session_start(['name' => 'SPM']);
            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {

                $id_del = encrypt_decrypt('decrypt', $_GET['id']);
                $db = getDB();

                $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_estado='inactivo' WHERE producto_bodega_idProducto=:id AND producto_bodega_idBodega=:bodega ");
                $stmt->bindParam(":id", $id_del, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Eliminar";
                    $tabla = "producto";
                    historial($idUser, $date, $id_del, $accion, $tabla);
                }
                header('Location: ../View/contenido/product-list.php?d=1');
            }
        }
    }

    // Funcionpara consultar un usario en especifico
    public function updateProductoO($id, $ubicacion)
    {
        $idUser = $_SESSION['idUsuario'];
        $db = getDB();
        $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
        $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
        $st->execute();
        $count = $st->rowCount();
        if ($count == 1) {
            $db = getDB();
            $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_ubicacion=:ubicacion WHERE producto_bodega_idProducto=:id AND producto_bodega_idBodega=:bodega ");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
            $stmt->bindParam(":ubicacion", $ubicacion, PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() >= 1) {

                $idUser = $_SESSION['idUsuario'];
                $date = date('Y-m-d H:i:s', time());
                $accion = "Actualizar";
                $tabla = "producto";
                historial($idUser, $date, $id, $accion, $tabla);
            }
            return true;
        }
    }

    //Acualizar clente
    public function updateProducto($codigo, $nombre, $rotacion, $diasAviso, $minimo, $maximo, $precio, $descripcion, $uniCant, $peso, $modelo, $serial, $lote, $marca, $fechaVenc, $nContenedor, $ancho, $alto, $largo, $uniDimen, $idCliente, $id, $subinventario, $estado)
    {

        try {

            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();
            if ($count == 1) {

                $stmt = $db->prepare("UPDATE producto SET producto_codigo=:codigo,producto_nombre=:nombre,producto_rotacion=:rotacion,producto_diasAviso=:diasAviso,producto_minimo=:minimo,producto_maximo=:maximo,producto_precio=:precio,producto_descripcion=:descripcion,producto_uniCant=:uniCant,producto_peso=:peso,producto_modelo=:modelo,producto_serial=:serial,producto_lote=:lote,producto_marca=:marca,producto_fechaVenc=:fechaVenc,producto_nContenedor=:nContenedor,producto_ancho=:ancho,producto_alto=:alto,producto_largo=:largo,producto_uniDimen=:uniDimen,producto_idCliente=:idCliente,producto_subInventario=:subinventario WHERE idProducto = :id");

                $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":rotacion", $rotacion, PDO::PARAM_STR);
                $stmt->bindParam(":diasAviso", $diasAviso, PDO::PARAM_STR);
                $stmt->bindParam(":minimo", $minimo, PDO::PARAM_STR);

                $stmt->bindParam(":maximo", $maximo, PDO::PARAM_STR);
                $stmt->bindParam(":precio", $precio, PDO::PARAM_STR);
                $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(":uniCant", $uniCant, PDO::PARAM_STR);

                $stmt->bindParam(":peso", $peso, PDO::PARAM_STR);
                $stmt->bindParam(":modelo", $modelo, PDO::PARAM_STR);
                $stmt->bindParam(":serial", $serial, PDO::PARAM_STR);

                $stmt->bindParam(":lote", $lote, PDO::PARAM_STR);
                $stmt->bindParam(":marca", $marca, PDO::PARAM_STR);
                $stmt->bindParam(":fechaVenc", $fechaVenc, PDO::PARAM_STR);
                $stmt->bindParam(":nContenedor", $nContenedor, PDO::PARAM_STR);
                $stmt->bindParam(":ancho", $ancho, PDO::PARAM_STR);

                $stmt->bindParam(":alto", $alto, PDO::PARAM_STR);
                $stmt->bindParam(":largo", $largo, PDO::PARAM_STR);
                $stmt->bindParam(":uniDimen", $uniDimen, PDO::PARAM_STR);
                $stmt->bindParam(":idCliente", $idCliente, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);
                $stmt->bindParam(":subinventario", $subinventario, PDO::PARAM_STR);

                $stmt->execute();

                $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_estado=:estado WHERE producto_bodega_idProducto = :id AND producto_bodega_idBodega=:bodega");

                $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_STR);

                $stmt->execute();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Actualizar";
                    $tabla = "producto";
                    historial($idUser, $date, $id, $accion, $tabla);
                }
                $db = null;
                return $id;
            } else {

                $db = null;
                return false;
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    //Consultat imagenen
    public function searchImage($name)
    {

        try {
            $count = 0;
            if (!empty($name)) {
                $idImg = $name;

                $db = getDB();
                $st = $db->prepare("SELECT * FROM imagen WHERE imagen_nombre=:idImg");
                $st->bindParam(":idImg", $idImg, PDO::PARAM_STR);
                $st->execute();
                $count = $st->rowCount();
                $db = null;
            } else {
                $count = 1;
            }

            return $count;
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

    // Función para validar pero y tipo de una imagen
    public function val_img_product($tipo, $size)
    {
        $errorMsgLogin = 'true';
        if (!($tipo == 'image/gif' || $tipo == 'image/jpeg' || $tipo == 'image/jpg' || $tipo == 'image/png')) {
            $errorMsgLogin = 'El formato del documento no es admitido';
        }
        if ($size > '10485760') {
            $errorMsgLogin = 'El peso sobrepasa lo admitido!';
        }
        return  $errorMsgLogin;
    }

    // Agregar imagen
    public function add_temp_img_product($temp, $nombre, $tipo, $size, $uid)
    {
        // echo $temp, $nombre, $tipo, $size, $uid;die;
        // Ruta donde se guardarán las imágenes que subamos
        $directorio = '../assets/img/product/temp/';
        // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
        move_uploaded_file($temp, $directorio . $nombre);

        $db = getDB();
        $idUser = $_SESSION['idUsuario'];

        $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
        $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
        $st->execute();
        $count = $st->rowCount();
        if ($count >= 1) {
            $stmt = $db->prepare("INSERT INTO imagen(imagen_nombre,imagen_tipo,imagen_size,imagen_fecha,producto_id) VALUES (:imagen,:tipo,:size,:fecha,:produt)");

            $date = date('Y-m-d H:i:s', time());
            $stmt->bindParam(":imagen", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $stmt->bindParam(":size", $size, PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $date);
            $stmt->bindParam(":produt", $uid, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } else {
            return false;
        }
    }

    // Update imagen
    public function up_temp_img_product($name_old, $temp, $nombre, $tipo, $size, $uid)
    {
        productClass::remove_temp_img_product($name_old);


        // Ruta donde se guardarán las imágenes que subamos
        $directorio = '../assets/img/product/temp/';
        // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
        move_uploaded_file($temp, $directorio . $nombre);



        $db = getDB();
        $idUser = $_SESSION['idUsuario'];

        $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
        $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
        $st->execute();
        $count = $st->rowCount();

        if ($count >= 1) {

            $st = $db->prepare("SELECT * FROM imagen WHERE producto_id=:idproduct AND imagen_nombre=:nameimg");
            $st->bindParam(":idproduct", $uid, PDO::PARAM_STR);
            $st->bindParam(":nameimg", $name_old, PDO::PARAM_STR);
            $st->execute();

            $data = $st->fetch(PDO::FETCH_OBJ);

            $count_2 = $st->rowCount();
            if ($data) {
                $idImg = $data->id_imagen;
            }
        } else {
            return false;
        }

        if ($count_2 >= 1) {

            $stmt = $db->prepare("UPDATE imagen SET imagen_nombre =:imagen,imagen_tipo=:tipo,imagen_size= :size,imagen_fecha=:fecha WHERE id_imagen=:idimg");

            $date = date('Y-m-d H:i:s', time());
            $stmt->bindParam(":imagen", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $stmt->bindParam(":size", $size, PDO::PARAM_STR);
            $stmt->bindParam(":fecha", $date);
            $stmt->bindParam(":idimg", $idImg, PDO::PARAM_STR);
            $stmt->execute();


            $lastId = $db->lastInsertId();
        }
        return true;
    }

    // Remover imagen
    public function remove_temp_img_product($name_old)
    {

        $Msg = 0;

        // Ruta donde se guardarán las imágenes que subamos
        $directorio = '../assets/img/product/temp/' . $name_old;

        $db = getDB();
        $idUser = $_SESSION['idUsuario'];

        $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
        $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
        $st->execute();
        $count = $st->rowCount();

        if ($count >= 1) {
            if (file_exists($directorio)) {

                unlink($directorio);
                $Msg = 1;
            } else {
                $Msg = 2;
            }
        } else {
            $Msg = 3;
        }
        return $Msg;
    }

    // Se obtiene la data de todos los productos
    function tableDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {
        try {

            $db = getDB();
            $idTabla = "id" . ucfirst($table);
            $cond = $table . '_estado';
            if ($_SESSION['usuario_Rol'] == 1) {
                if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                    $stmt = $db->prepare("SELECT producto_bodega_estado,producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_uniCant
                    FROM $table  INNER JOIN producto_bodega 
                    ON idProducto=producto_bodega_idProducto
                    INNER JOIN cliente ON producto_idCliente=idCliente
                    WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND producto_bodega_idBodega=" . $_SESSION['bodega'] . " ORDER BY idProducto DESC LIMIT $limit,$offset");
                } else {
                    $stmt = $db->prepare("SELECT producto_bodega_estado,producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_uniCant
                    FROM $table INNER JOIN producto_bodega
                    ON idProducto=producto_bodega_idProducto
                    INNER JOIN cliente ON producto_idCliente=idCliente
                    WHERE producto_bodega_idBodega=" . $_SESSION['bodega'] . " ORDER BY $idTabla DESC LIMIT $limit,$offset ");
                }
            } else {
                if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                    $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_uniCant
                    FROM $table  INNER JOIN producto_bodega 
                    ON idProducto=producto_bodega_idProducto
                    INNER JOIN cliente ON producto_idCliente=idCliente

                    WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND producto_bodega_estado='activo' AND producto_bodega_idBodega=" . $_SESSION['bodega'] . " ORDER BY idProducto DESC LIMIT $limit,$offset");
                } else {
                    $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_uniCant
                    FROM $table INNER JOIN producto_bodega
                    ON idProducto=producto_bodega_idProducto
                    INNER JOIN cliente ON producto_idCliente=idCliente

                    WHERE producto_bodega_estado='activo' AND producto_bodega_idBodega=" . $_SESSION['bodega'] . " ORDER BY $idTabla DESC LIMIT $limit,$offset ");
                }
            }


            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }


    function dataPrDetails($table, $bus_1, $campo_1)
    {
        try {

            $db = getDB();
            $idTabla = "id" . ucfirst($table);
            $cond = $table . '_estado';

            if ($bus_1 != '') {
                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_uniCant
                    FROM $table  INNER JOIN producto_bodega 
                    ON idProducto=producto_bodega_idProducto
                    WHERE $campo_1 LIKE '" . $bus_1 . "%' AND producto_bodega_estado='activo' AND producto_bodega_idBodega=" . $_SESSION['bodega'] . " ORDER BY idProducto DESC");
            } else {
                $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_uniCant
                    FROM $table INNER JOIN producto_bodega
                    ON idProducto=producto_bodega_idProducto
                    WHERE producto_bodega_estado='activo' AND producto_bodega_idBodega=" . $_SESSION['bodega'] . " ORDER BY $idTabla DESC");
            }



            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // consulta de los producos en tareas
    public function unlockDetails($table, $busqueda, $campo)
    {
        try {
            $db = getDB();
            $cond = $table . '_estado';

            if ($busqueda != '') {

                $stmt = $db->prepare("SELECT * FROM tarea INNER JOIN producto ON tarea_idProducto=idProducto  WHERE $campo LIKE '" . $busqueda . "%' AND tarea_estado='activo' AND tarea_novedad>0 AND tarea_idBodega=" . $_SESSION['bodega'] . " ORDER BY idTarea DESC");
            } else {
                $stmt = $db->prepare("SELECT * FROM tarea  INNER JOIN producto ON tarea_idProducto=idProducto WHERE tarea_estado='activo' AND tarea_novedad>0 AND tarea_idBodega=" . $_SESSION['bodega'] . " ORDER BY idTarea DESC");
            }

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    //  Función para agrear productos
    public function agregarProducto($id, $cant, $descrip, $prioridad)
    {

        $url = BASE_URL . 'View/contenido/product-block.php';

        try {

            session_start(['name' => "SPM"]);
            $db = getDB();

            $stmt = $db->prepare("SELECT producto_consecutivo,producto_codigo,producto_nombre,producto_peso,producto_bodega_cantidad,producto_bodega_cantidadAlis,producto_bodega_cantidadBlock,idProducto,producto_idCliente
            FROM producto INNER JOIN producto_bodega
            ON idProducto=producto_bodega_idProducto
            WHERE producto_estado='activo' AND idProducto='$id'");
            $stmt->execute();
            $histo =  $stmt->fetch(PDO::FETCH_ASSOC);
            $producto = [
                'idProducto' => $histo['idProducto'],
                'Codigo' => $histo['producto_codigo'],
                'Nombre'  => $histo['producto_nombre'],
                'Cliente' => $histo['producto_idCliente'],
                'CantAlis' => $cant,
                'Descrip' => $descrip,
                'Prioridad' => $prioridad,
                'CantExis' => $histo['producto_bodega_cantidad'],
                'Peso' => $histo['producto_peso']
            ];

            if ($cant > $histo['producto_bodega_cantidad']) {
                return "Cantidad mayor a las existencias";
            } elseif ($cant <= 0) {
                return "Cantidad No Permitida";
            } else if (empty($_SESSION['productosBlock'])) {
                $_SESSION['productosBlock'][$id] = $producto;
                return "Agregado";
            } else if (!empty($_SESSION['productosBlock'])) {
                $indice = false;
                if (isset($_SESSION['productosBlock'][$id])) {
                    $indice = true;
                    $_SESSION['productosBlock'][$id]['CantAlis'] = $_SESSION['productosBlock'][$id]['CantAlis'] + $producto['CantAlis'];
                    if (!empty($_SESSION['productosBlock'][$id]['Descrip']) && empty($descrip)) {
                    } else if (empty($_SESSION['productosBlock'][$id]['Descrip']) && !empty($descrip)) {
                        $_SESSION['productosBlock'][$id]['Descrip'] = $descrip;
                    } else {
                        $_SESSION['productosBlock'][$id]['Descrip'] = $descrip;
                    }
                    return "existe";
                }
            }
            if ($indice === false) {
                $_SESSION['productosBlock'][$id] = $producto;
                return "Agregado";
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para bloquear prodcutoa
    public function blockProduct($producto)
    {
        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count <= 1) {


                foreach ($producto as $product) {

                    $stmt = $db->prepare("SELECT producto_bodega_cantidad
                    FROM producto INNER JOIN producto_bodega ON idProducto=producto_bodega_idProducto 
                    WHERE producto_estado='activo' AND idProducto=:idProducto");
                    $stmt->bindParam(":idProducto", $product['idProducto'], PDO::PARAM_STR);
                    $stmt->execute();
                    $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($filas[0]['producto_bodega_cantidad'] < $product['CantAlis']) {
                        return "Cantidad mayor que existencias";
                    } else {
                        $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad-:cant1 ,producto_bodega_cantidadBlock=producto_bodega_cantidadBlock+:cant2 WHERE producto_bodega_idProducto=:idProducto");
                        $stmt->bindParam(":idProducto", $product['idProducto'], PDO::PARAM_STR);
                        $stmt->bindParam(":cant1", $product['CantAlis'], PDO::PARAM_STR);
                        $stmt->bindParam(":cant2", $product['CantAlis'], PDO::PARAM_STR);
                        $stmt->execute();

                        $task = new taskClass;
                        $task->taskRegistration($product['Descrip'], null, null, $product['Prioridad'], $product['idProducto'], $product['CantAlis'], $_SESSION['bodega']);
                    }
                }

                if ($stmt->rowCount() >= 1) {
                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = $product['CantAlis'] . " unidades Bloqueadas ";
                    $tabla = "producto";
                    historial($idUser, $date, $product['idProducto'], $accion, $tabla);
                }
                unset($_SESSION['productosBlock']);
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

    // Funcion para vaciar los pruductos en la sesion de productos bloqueados
    public function Vaciar()
    {
        $url = BASE_URL . 'View/contenido/product-block.php?status=Vaciado';
        try {
            session_start(['name' => "SPM"]);
            unset($_SESSION['productosBlock']);
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para eliminar un producto
    public function deleteProducto($id)
    {
        $url = BASE_URL . 'View/contenido/product-block.php?status=productDeleted';

        session_start(['name' => "SPM"]);
        try {
            $id_del = encrypt_decrypt('decrypt', $id);

            unset($_SESSION['productosBlock'][$id_del]);
            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para agregar un producto a la session
    public function agregarProductoUp($idPr, $idTr, $cant, $descrip)
    {

        try {

            session_start(['name' => "SPM"]);
            $db = getDB();

            $stmt = $db->prepare("SELECT * FROM tarea INNER JOIN producto ON tarea_idProducto=idProducto  WHERE tarea_estado='activo' AND idProducto=$idPr AND idTarea=$idTr ");
            $stmt->execute();
            $histo =  $stmt->fetch(PDO::FETCH_ASSOC);
            $producto = [
                'idTarea' => $histo['idTarea'],
                'idProducto' => $histo['idProducto'],
                'Codigo' => $histo['producto_codigo'],
                'Nombre'  => $histo['producto_nombre'],
                'Cliente' => $histo['producto_idCliente'],
                'CantAlis' => $cant,
                'Descrip' => $descrip,
                'CantExis' => $histo['tarea_novedad'],
                'Peso' => $histo['producto_peso']
            ];
            if ($cant > $histo['tarea_novedad']) {
                return "Cantidad mayor a las existencias";
            } elseif ($cant <= 0) {
                return "Cantidad No Permitida";
            } else if (empty($_SESSION['productosUnlock'])) {
                $_SESSION['productosUnlock'][$idTr] = $producto;
                return "Agregado";
            } else if (!empty($_SESSION['productosUnlock'])) {
                $indice = false;
                if (isset($_SESSION['productosUnlock'][$idTr])) {
                    $indice = true;
                    $_SESSION['productosUnlock'][$idTr]['CantAlis'] = $_SESSION['productosUnlock'][$idTr]['CantAlis'] + $producto['CantAlis'];
                    if (!empty($_SESSION['productosUnlock'][$idTr]['Descrip']) && empty($descrip)) {
                    } else if (empty($_SESSION['productosUnlock'][$idTr]['Descrip']) && !empty($descrip)) {
                        $_SESSION['productosUnlock'][$idTr]['Descrip'] = $descrip;
                    } else {
                        $_SESSION['productosUnlock'][$idTr]['Descrip'] = $descrip;
                    }

                    return "existe";
                }
            }
            if ($indice === false) {
                $_SESSION['productosUnlock'][$idTr] = $producto;
                return "Agregado";
            }
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para vacias la sesion de productos bloqueados
    public function VaciarUp()
    {

        try {
            $unL = encrypt_decrypt('encrypt', "up");
            $url = BASE_URL . 'View/contenido/product-block.php?unL=' . $unL . '&status=Vaciado';
            session_start(['name' => "SPM"]);
            unset($_SESSION['productosUnlock']);
            header("Location: $url");
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para eliminar un producto con un id especifico 
    public function deleteProductoUp()
    {
        session_start(['name' => "SPM"]);
        try {
            $id_del = encrypt_decrypt('decrypt', $_GET['idPr']);
            unset($_SESSION['productosUnlock'][$id_del]);
            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para desbloquear una tarea 
    public function UnlockProduct($producto)
    {
        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count <= 1) {

                foreach ($producto as $product) {

                    $stmt = $db->prepare("SELECT tarea_novedad FROM tarea WHERE tarea_idProducto=:idProducto AND idTarea=:idTarea");
                    $stmt->bindParam(":idProducto", $product['idProducto'], PDO::PARAM_STR);
                    $stmt->bindParam(":idTarea", $product['idTarea'], PDO::PARAM_STR);

                    $stmt->execute();
                    $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($filas[0]['tarea_novedad'] < $product['CantAlis']) {

                        $unL = encrypt_decrypt('encrypt', "up");
                        $url = BASE_URL . 'View/contenido/product-block.php?unL=' . $unL . '&status=CantNoPermitida';
                        header("Location: $url");
                        return false;
                    } else {
                        $stmt = $db->prepare("UPDATE producto_bodega SET producto_bodega_cantidad=producto_bodega_cantidad+:cant1 ,producto_bodega_cantidadBlock=producto_bodega_cantidadBlock-:cant2 WHERE producto_bodega_idProducto=:idProducto");
                        $stmt->bindParam(":idProducto", $product['idProducto'], PDO::PARAM_STR);
                        $stmt->bindParam(":cant1", $product['CantAlis'], PDO::PARAM_STR);
                        $stmt->bindParam(":cant2", $product['CantAlis'], PDO::PARAM_STR);
                        $stmt->execute();
                        $stmt = $db->prepare("UPDATE tarea SET tarea_novedad=tarea_novedad-:cant1 WHERE idTarea=:idTarea");
                        $stmt->bindParam(":idTarea", $product['idTarea'], PDO::PARAM_STR);
                        $stmt->bindParam(":cant1", $product['CantAlis'], PDO::PARAM_STR);
                        $stmt->execute();
                    }
                }

                if ($stmt->rowCount() >= 1) {
                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = $product['CantAlis'] . " unidades Desbloqueadas ";
                    $tabla = "producto";
                    historial($idUser, $date, $product['idProducto'], $accion, $tabla);
                }
                unset($_SESSION['productosUnlock']);
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

    // Funcion para consultar productos bloqueados
    public function blockDetails($table, $start, $Tpages, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {
        try {
            $db = getDB();
            $cond = $table . '_estado';
            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                // $cond='activo' AND $campo
                $stmt = $db->prepare("SELECT * FROM tarea 
                INNER JOIN producto ON tarea_idProducto=idProducto 
                WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND tarea_estado='activo' AND tarea_novedad > '0'
                ORDER BY idTarea DESC LIMIT $start,$Tpages");
            } else {
                $stmt = $db->prepare("SELECT * FROM tarea 
                INNER JOIN producto ON tarea_idProducto=idProducto  
                WHERE tarea_estado='activo' AND tarea_novedad > 0 
                ORDER BY idTarea DESC LIMIT $start,$Tpages ");
            }

            $stmt->execute();
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para consultar data
    function consultaSimple($tabla, $id)
    {
        $db = getDB();
        if ($tabla == 'productoBodega') {

            $stmt = $db->prepare("SELECT bodega_nombre, producto_bodega_fechaIngreso,producto_nombre,idBodega,idProducto_bodega,producto_bodega_ubicacion FROM producto_bodega
            INNER JOIN bodega ON producto_bodega_idBodega=idBodega
            INNER JOIN producto ON producto_bodega_idProducto=idProducto
           WHERE producto_bodega_idProducto=:id");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $data;
    }

    // Función para consultar la ubicación de una bodega
    function consultaSSimple($id)
    {
        $db = getDB();

        $stmt = $db->prepare("SELECT producto_bodega_ubicacion FROM producto_bodega
            WHERE producto_bodega_idProducto=:id AND producto_bodega_idBodega=:bodega");
        $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


        return $data;
    }

    // Función para Insertar la bodega al cliente
    public function clientCellarRegistration($idProducto, $fechaRegistro, $bodega)
    {

        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();

            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO producto_bodega (producto_bodega_idProducto,producto_bodega_idBodega,producto_bodega_fechaIngreso) VALUES (:idProd,:idBode,:fechaIn)");

                $stmt->bindParam(":idProd", $idProducto, PDO::PARAM_STR);
                $stmt->bindParam(":idBode", $bodega, PDO::PARAM_STR);
                $stmt->bindParam(":fechaIn", $fechaRegistro, PDO::PARAM_STR);

                $stmt->execute();

                $lastId = $db->lastInsertId();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Crear";
                    $tabla = "Asociar producto a bodega";
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

    // Función para validar si ya se encuentra registrada la bodega al cliente
    public function searchClientPd($id, $dbC)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM producto_bodega WHERE producto_bodega_idProducto=:id and producto_bodega_idBodega=:bd");

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
            $stmt = $db->prepare("DELETE FROM producto_bodega WHERE idProducto_Bodega=:id");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Busca un producto en base a un ID
    function searchProduct($id)
    {
        $db = getDB();

        $stmt = $db->prepare("SELECT * FROM producto WHERE producto_codigo=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->rowCount();

        return $data;
    }
    public function cliente_producto()
    {
        try {
            $db = getDB();

            $stmt = $db->prepare("SELECT * FROM cliente 
            INNER JOIN cliente_bodega ON idCliente=cliente_bodega_cliente
            WHERE cliente_estado='activo' AND cliente_bodega_bodega=" . $_SESSION['bodega']);

            $stmt->execute();
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }


    // Funcion para generar excel de productos
    function GenerateExcel($tabla, $id)
    {
        session_start(['name' => "SPM"]);

        $db = getDB();
        $date = date('d-m-Y');
        $time = date('H:i:s');
        $estado = $tabla . '_estado';

        if ($id == '') {
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT producto.*,producto_bodega.*,cliente.*,bodega.*,
                GROUP_CONCAT(imagen_nombre  SEPARATOR '$$') 
                AS imagen  FROM $tabla 
                LEFT JOIN imagen ON idProducto=producto_id
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                INNER JOIN bodega ON producto_bodega_idBodega=idBodega
                INNER JOIN cliente ON producto_idCliente=idCliente");
            } else {
                $stmt = $db->prepare("SELECT producto.*,producto_bodega.*,cliente.*,bodega.*,
                GROUP_CONCAT(imagen_nombre  SEPARATOR '$$') 
                AS imagen  FROM $tabla 
                LEFT JOIN imagen ON idProducto=producto_id
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                INNER JOIN bodega ON producto_bodega_idBodega=idBodega
                INNER JOIN cliente ON producto_idCliente=idCliente
                WHERE  $estado='activo'");
            }
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else if ($id == 'img') {

            $botonBodega = consultaSimple("bodega", $_SESSION['bodega']);


            $stmt = $db->prepare("SELECT imagen_fecha, imagen_nombre, imagen_size,producto_codigo
                FROM imagen INNER JOIN producto ON idProducto=producto_id");

            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $salida = "";
            $salida .= "<table>";
            $salida .= "
                <style> 
            
                    .th{
                        background-color: #dcdcdc ; 
                        color: #000000;
                        height:50px;
                    }
                    .invB{
                        background-color: #e3f700 ; 
                        border-bottom: 1em solid #dcdcdc;
    
                    }
                    .dispo{
                        background-color: #50b743 ; 
                        border-bottom: 1em solid #dcdcdc;
    
                    }
                    .block{
                        background-color: #ea8605 ; 
                        border-bottom: 1em solid #dcdcdc;
    
                    }
                    td{
                        padding:2px;
                        text-align: center;
                        height:30px;
                        width:auto + 5px;
                    }
                    .nombre{
                        padding:2px;
                        text-align: left !important;
                        border-bottom: 1em solid #dcdcdc;
    
                    }
                    .td {
                        border-bottom: 1em solid #dcdcdc;
                      }
                </style>

                <tr>
                    <th class='th' >Bodega :</th>
                    <th>" . $botonBodega[0]['bodega_nombre'] . "</th>
                </tr>
                <tr>
                </tr>
                <tr>
                </tr>
                <tr>
                    <td>FECHA : </td>
                    <td>" . $date . "</td>
                </tr>
    
                <tr>
                    <td>HORA : </td>
                    <td>" . $time . "</td>
                </tr>
                <tr>
                </tr>
                <thead> 
                    <th class='th'>Nombre de la imagen</th>
                    <th class='th'>Peso de la imagen</th>
                    <th class='th'>Fecha de carge</th>
                    <th class='th'>Código producto</th>
                    <th class='th'>URL imagen</th>
                </thead>";

            foreach ($row as $r) {

                $salida .= "
                    <tr>
                        <td class='td'>" . $r['imagen_nombre'] . "</td> 
                        <td class='td'>" . $r['imagen_size'] . "</td> 
                        <td class='td'>" . $r['imagen_fecha'] . "</td> 
                        <td class='nombre'>" . $r['producto_codigo'] . "</td> 
                       ";

                $salida .= "<td scope='col'>" . BASE_URL . "View/assets/img/product/temp/" . $r['imagen_nombre'] . "<br></td>";
            }

            $salida .= "</tr>";

            $salida .= "</table>";


            header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

            header("Content-Disposition: attachment; filename=producto_imagenes_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
            exit();
        } else {
            $individual = 'id' . ucfirst($tabla);
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT producto.*,producto_bodega.*,cliente.*,bodega.*,
                GROUP_CONCAT(imagen_nombre  SEPARATOR '$$') 
                AS imagen  FROM $tabla 
                LEFT JOIN imagen ON idProducto=producto_id
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                INNER JOIN bodega ON producto_bodega_idBodega=idBodega
                INNER JOIN cliente ON producto_idCliente=idCliente
                WHERE $individual=:id ");
            } else {
                $stmt = $db->prepare("SELECT producto.*,producto_bodega.*,cliente.*,bodega.*,
                GROUP_CONCAT(imagen_nombre  SEPARATOR '$$') 
                AS imagen  FROM $tabla 
                LEFT JOIN imagen ON idProducto=producto_id
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                INNER JOIN bodega ON producto_bodega_idBodega=idBodega
                INNER JOIN cliente ON producto_idCliente=idCliente
                WHERE  $estado='activo' AND  $individual=:id GROUP BY idProducto");
            }

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
                    height:50px;
                }
                .invB{
                    background-color: #e3f700 ; 
                    border-bottom: 1em solid #dcdcdc;

                }
                .dispo{
                    background-color: #50b743 ; 
                    border-bottom: 1em solid #dcdcdc;

                }
                .block{
                    background-color: #ea8605 ; 
                    border-bottom: 1em solid #dcdcdc;

                }
                td{
                    padding:2px;
                    text-align: center;
                    height:30px;
                    width:auto + 5px;
                }
                .nombre{
                    padding:2px;
                    text-align: left !important;
                    border-bottom: 1em solid #dcdcdc;

                }
                .td {
                    border-bottom: 1em solid #dcdcdc;
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
                <td>FECHA : </td>
                <td>" . $date . "</td>
            </tr>

            <tr>
                <td>HORA : </td>
                <td>" . $time . "</td>
            </tr>
            <tr>
            </tr>
            <thead> 
                <th class='th'>Consecutivo</th>
                <th class='th'>Codigo</th>
                <th class='th'>Nombre</th>
                <th class='th'>Peso U</th>
                <th class='th'>Inventario <br> en bodega</th>
                <th class='th'>Peso Total</th>
                <th class='th'>Alerta</th>
                <th class='th'>Pedidos en <br> Alistamiento</th>
                <th class='th'>Inventario Disponible</th>
                <th class='th'>Cantidad <br> Minima</th>
                <th class='th'>Cantidad <br> Bloqueada</th> 
                <th class='th'>Cliente</th> 
                <th class='th'>Imagen 1</th> 
                <th class='th'>Imagen 2</th> 
            </thead>";
        foreach ($row as $r) {
            $cantidad = intval($r['producto_bodega_cantidad']);
            $cantidadBlock = intval($r['producto_bodega_cantidadBlock']);
            $cantidadAlis = intval($r['producto_bodega_cantidadAlis']);
            $peso = intval($r['producto_peso']);
            $minimo = intval($r['producto_minimo']);
            $cantT = $cantidad + $cantidadAlis + $cantidadBlock;
            $pesoT = $cantidad * $peso;

            if ($cantidad == 0) {
                $alerta = "<td class='td pedido'>PEDIDO</td> ";
            } elseif ($cantidad <= $minimo) {
                $alerta = "<td class='td bajo'>BAJO</td> ";
            } else {
                $alerta = "<td class='td'>NORMAL</td> ";
            }
            $salida .= "
                <tr>
                    <td class='td'>" . $r['producto_consecutivo'] . "</td> 
                    <td class='td'>" . $r['producto_codigo'] . "</td> 
                    <td class='nombre'>" . $r['producto_nombre'] . "</td> 
                    <td class='td'>" . $peso . "</td> 
                    <td class='invB'>" . $cantT . "</td> 
                    <td class='td'>" . $pesoT . "</td> 
                    " . $alerta . "
                    <td class='td'>" . $cantidadAlis . "</td> 
                    <td class='dispo'>" . $cantidad . "</td> 
                    <td class='td'>" . $minimo . "</td> 
                    <td class='block'>" . $cantidadBlock . "</td> 
                    <td class='td'>" . $r['cliente_consecutivo'] . "  " . $r['cliente_nombre'] . "</td>";
            if (!empty($r["imagen"])) {
                foreach (explode("$$", $r["imagen"]) as $imgs) {


                    $salida .= "<td scope='col'>" . BASE_URL . "View/assets/img/product/temp/" . $imgs . "<br></td>";
                }
            }
            $salida .= "</tr>";
        };

        $salida .= "</table>";


        header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

        header("Content-Disposition: attachment; filename=producto_" . time() . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        return $salida;
    }
}
