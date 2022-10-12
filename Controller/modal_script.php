<?php
// Connection variables 
// Server congiguration 
// $host = "localhost"; // MySQL host name eg. localhost
// $user = "u159993157_admin"; // MySQL user. eg. root ( if your on localserver)
// $password = "u^4!SiEW"; // MySQL user password  (if password is not set for your root user then keep it empty )
// $database = "u159993157_innvestock"; // MySQL Database name
session_start(["name" => "SPM"]);


// Localhost configuration
$host = "localhost"; // MySQL host name eg. localhost
$user = "root"; // MySQL user. eg. root ( if your on localserver)
$password = ""; // MySQL user password  (if password is not set for your root user then keep it empty )
$database = "innvestock"; // MySQL Database name

// Connect to MySQL Database
$con = new mysqli($host, $user, $password, $database);

// check request
if (isset($_POST['idT']) && isset($_POST['idT']) != "") {
    // get User ID
    $id = $_POST['idT'];
    $tabla = $_POST['tablaT'];

    if ($tabla == 'imagen') {
        $campo = "producto_id";
    } else {
        $campo = "id" . ucfirst($tabla);
    }

    // Get User Details
    if ($tabla == 'cliente') {
        $query = "SELECT cliente.*,ciudad_nombre,
        GROUP_CONCAT(bodega_nombre)AS bodegas,
        GROUP_CONCAT(cliente_bodega_fechaIngreso)AS fechas
        FROM cliente 
        INNER JOIN cliente_bodega ON idCliente = cliente_bodega_cliente
        INNER JOIN bodega ON idBodega=cliente_bodega_bodega
        INNER JOIN ciudad on cliente_ciudad=idCiudad
        WHERE $campo = '$id'
        LIMIT 1 ";
    } elseif ($tabla == 'producto') {
        $query = "SELECT producto.*,producto_bodega.*,cliente_nombre
        FROM producto 
        INNER JOIN cliente ON producto_idCliente=idCliente
        INNER JOIN producto_bodega ON idProducto = producto_bodega_idProducto
        WHERE idProducto = $id AND producto_bodega_idBodega=" . $_SESSION['bodega'] . "
         LIMIT 1 ";
    } elseif ($tabla == 'alistado') {
        $query = "SELECT alistado.*,cliente_nombre
        FROM alistado 
        INNER JOIN cliente ON alistado_idCliente=idCliente
        WHERE idAlistado = $id LIMIT 1 ";
    } elseif ($tabla == 'entrada') {
        $query = "SELECT entrada.*,cliente_nombre
        FROM entrada 
        INNER JOIN cliente ON entrada_idCliente=idCliente
        WHERE idEntrada = $id LIMIT 1 ";
    } elseif ($tabla == 'despacho') {
        $query = "SELECT despacho.*,cliente_nombre
        FROM despacho 
        INNER JOIN cliente ON despacho_idCliente=idCliente
        WHERE idDespacho = $id LIMIT 1 ";
    } elseif ($tabla == 'tarea') {
        $query = "SELECT tarea.*,producto_nombre,producto_codigo,producto_consecutivo,idCliente,cliente_nombre,usuario_nombre,usuario_apellido
        FROM tarea
        INNER JOIN producto ON tarea_idProducto=idProducto 
        INNER JOIN cliente ON producto_idCliente=idCliente
        INNER JOIN usuario ON tarea_usuario=idUsuario
        WHERE idTarea = $id AND tarea_idBodega=" . $_SESSION['bodega'] . " LIMIT 1 ";
    } else {
        $query = "SELECT * FROM $tabla WHERE $campo = '$id'";
    }

    if (!$result = mysqli_query($con, $query)) {
        exit(mysqli_error($con));
    }

    $response = array();
    if (mysqli_num_rows($result) > 0) {
        if ($tabla == 'imagen') {
            while ($row = mysqli_fetch_all($result)) {
                $response = $row;
            }
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $response = $row;
            }
        }
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid Request!";
    }

    // display JSON data
    echo json_encode($response);
}
