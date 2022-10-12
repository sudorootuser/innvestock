<?php
// Función para encriptar o desencriptar
function encrypt_decrypt($action, $string)
{
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'Innvestok-2022';
    $secret_iv = '$@364981SSF';

    // Hash
    $key = hash('sha256', $secret_key);

    // Iv - Encrypt method AES-256-CBC exoects 16 bytes
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return  $output;
}

// Función para mostrar la páginación
function pagination($tabla)
{
    $cond = $tabla . '_estado';

    $db = getDB();

    if ($tabla == 'historial') {
        $stmt = $db->prepare("SELECT count(*) as cont_total FROM $tabla");
    } else {
        $stmt = $db->prepare("SELECT count(*) as cont_total FROM $tabla WHERE $cond='activo'");
    }
    $stmt->execute();
    $total =  $stmt->fetch(PDO::FETCH_ASSOC);

    $count = $total['cont_total'];

    return $count;
}

// Función para limpiar la data de los input
function limpiar_cadena($cadena)
{
    $cadena = trim($cadena);
    $cadena = stripslashes($cadena);
    $cadena = str_replace("<script>", "", $cadena);
    $cadena = str_replace("</script>", "", $cadena);
    $cadena = str_replace("<script src>", "", $cadena);
    $cadena = str_replace("<script type=", "", $cadena);
    $cadena = str_replace("SELECT * FROM", "", $cadena);
    $cadena = str_replace("DELETE INTO", "", $cadena);
    $cadena = str_replace("WHERE", "", $cadena);
    $cadena = str_replace("TRUNCATE TABLE", "", $cadena);
    $cadena = str_replace("DROP DATABASE", "", $cadena);
    $cadena = str_replace("SHOW TABLES", "", $cadena);
    $cadena = str_replace("SHOW DATABASE", "", $cadena);
    $cadena = str_replace("<?php", "", $cadena);
    $cadena = str_replace("?>", "", $cadena);
    $cadena = str_replace("--", "", $cadena);
    $cadena = str_replace(">", "", $cadena);
    $cadena = str_replace("<", "", $cadena);
    $cadena = str_replace("[", "", $cadena);
    $cadena = str_replace("]", "", $cadena);
    $cadena = str_replace("^", "", $cadena);
    $cadena = str_replace("==", "", $cadena);
    $cadena = str_replace("=", "", $cadena);
    $cadena = str_replace(";", "", $cadena);
    $cadena = str_replace("::", "", $cadena);
    $cadena = str_replace("'", "", $cadena);
    $cadena = stripslashes($cadena);
    $cadena = trim($cadena);

    return $cadena;
}

// Función para generar un registo al realizar algún tipo de movimiento
function historial($idUser, $date, $lastId, $accion, $tabla)
{
    $db = getDB();

    // Consulta para generar el histroia
    $stmh = $db->prepare("INSERT INTO historial(historial_tipoAccion,historial_tablaAccion,historial_idAccion,historial_userAccion,historial_fechaAccion,historial_idBodega) VALUES (:accion,:tabla,:idAccion,:user,:fechaAccion,:bodega) ");

    $stmh->bindParam(":accion", $accion, PDO::PARAM_STR);
    $stmh->bindParam(":tabla", $tabla, PDO::PARAM_STR);
    $stmh->bindParam(":idAccion", $lastId, PDO::PARAM_STR);
    $stmh->bindParam(":user", $idUser, PDO::PARAM_INT);
    $stmh->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
    $stmh->bindParam(":fechaAccion", $date);

    $stmh->execute();
    return $stmh;
}

// Función para consultar el historial
function consulta_Historial($table, $limit, $offset)
{
    $db = getDB();

    $stmt = $db->prepare("SELECT * FROM $table WHERE historial_idBodega=" . $_SESSION['bodega'] . " ORDER BY historial_fechaAccion ASC LIMIT 5 ");
    $stmt->execute();
    $histo =  $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $histo;
}

// Función para consultar 
function  consultaSimple($tabla, $id)
{
    $campo = "id" . ucfirst($tabla);
    $campo2 = $tabla . "_estado";
    $db = getDB();

    if ($id != '' and $tabla != 'clienteAlist' and $tabla != 'clienteBodega' and $tabla != 'productoBodega' and $tabla != 'productoKit' and $tabla != 'bodega' and $tabla != 'producto') {

        $stmt = $db->prepare("SELECT * FROM $tabla WHERE $campo=:id ");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_OBJ);
    } else if ($tabla == 'clienteAlist') {

        $stmt = $db->prepare("SELECT * FROM cliente WHERE idCliente=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else if ($tabla == 'bodega') {

        if (!empty($id)) {

            $stmt = $db->prepare("SELECT * FROM bodega 
            INNER JOIN ciudad ON bodega_ciudad=idCiudad
            WHERE idBodega=$id");

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {

            $stmt = $db->prepare("SELECT * FROM bodega
            INNER JOIN ciudad ON bodega_ciudad=idCiudad
            ");

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else if ($tabla == 'clienteBodega') {

        $stmt = $db->prepare("SELECT 
        cliente.`cliente_nombre` as nombreCliente,
        cliente.`cliente_apellido` as apellidoCliente,
        bodega.`idBodega`,
        `bodega_nombre` as nombreBodega,
        `cliente_bodega`.`cliente_bodega_fechaIngreso`,
        `cliente_bodega`.`idCliente_bodega` 
      FROM
        cliente_bodega 
        INNER JOIN cliente 
          ON cliente_bodega_cliente = cliente.`idCliente` 
        INNER JOIN bodega 
          ON `cliente_bodega`.`cliente_bodega_bodega` = bodega.`idBodega` 
          WHERE `cliente_bodega`.`cliente_bodega_cliente`=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else if ($tabla == 'producto') {

        $stmt = $db->prepare("SELECT *
        FROM producto 
        INNER JOIN cliente 
        ON producto_idCliente = idCliente
        INNER JOIN producto_bodega 
        ON idProducto = producto_bodega_idProducto
        WHERE idProducto=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_OBJ);
    } else if ($tabla == 'productoKit') {

        $stmt = $db->prepare("SELECT 
        cliente_nombre, cliente_apellido
        FROM kit 
        INNER JOIN cliente ON kit_idCliente = idCliente 
        WHERE idKit=:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $db->prepare("SELECT * FROM $tabla ORDER BY $campo ASC");

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $data;
    exit();
}

// Función para consultar una ciudad
function consultaSimpleAsc($tabla, $id)
{
    $campo = "id" . ucfirst($tabla);
    $estado = $tabla . "_estado";
    if ($tabla == "ciudad") {
        $db = getDB();
        if ($id == '') {
            $stmt = $db->prepare("SELECT * FROM $tabla WHERE $estado='activo' ORDER BY ciudad_nombre ASC");
        } else {
            $stmt = $db->prepare("SELECT * FROM $tabla WHERE $campo=:id AND $estado='activo' ORDER BY ciudad_nombre ASC");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        }
    } else {
        $db = getDB();
        if ($id == '') {
            $stmt = $db->prepare("SELECT * FROM $tabla WHERE $estado='activo' ORDER BY $campo ASC");
        } else {
            $stmt = $db->prepare("SELECT * FROM $tabla WHERE $campo=:id AND $estado='activo' ORDER BY $campo ASC");
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        }
    }
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $data;
}

// Función para consultar por cédula
function consultaCedula($id)
{
    $db = getDB();

    $stmt = $db->prepare("SELECT cliente_nDocument FROM cliente WHERE cliente_nDocument=:id");
    $stmt->bindParam(":id", $id, PDO::PARAM_STR);

    $stmt->execute();

    $data = $stmt->rowCount();

    return $data;
}

// Función para consultar por cédula
function consultaBodegaCliente($id)
{
    $db = getDB();

    $stmt = $db->prepare("SELECT b.`idBodega`, b.`bodega_nombre` FROM `cliente_bodega` AS c RIGHT JOIN `bodega` AS b  ON c.`cliente_bodega_bodega` = b.`idBodega` WHERE c.`cliente_bodega_cliente`=:id");

    $stmt->bindParam(":id", $id, PDO::PARAM_STR);

    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $data;
}

// Genera una contraseña unica
function generate_string()
{
    $strength = 15;
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }

    return $random_string;
}

// Funcion para generar excel
function GenerateExcel($tabla, $id)
{

    $db = getDB();
    $data_list = [];

    $estado = $tabla . '_estado';

    if ($id == '') {

        $stmt = $db->prepare("SELECT * FROM $tabla WHERE  $estado='activo'");
    } else {

        $individual = 'id' . ucfirst($tabla);

        $stmt = $db->prepare("SELECT * FROM $tabla WHERE  $estado='activo' AND  $individual=:id");

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    }

    $stmt->execute();


    $salida = "";
    $salida .= "<table>";
    $salida .= "
        <thead> 
            <th style='background-color: green; color: white;'>Consecutivo</th>
            <th style='background-color: green; color: white;'>Tipo de identificación</th>
            <th style='background-color: green; color: white;'># Identificación</th>
            <th style='background-color: green; color: white;'>Digito de verificación</th>
            <th style='background-color: green; color: white;'>Ciudad</th>
            <th style='background-color: green; color: white;'>Nombre / Razón Social</th>
            <th style='background-color: green; color: white;'>Apellido</th>
            <th style='background-color: green; color: white;'>Actividad económica</th>
            <th style='background-color: green; color: white;'>Dirección principal</th>
            <th style='background-color: green; color: white;'>Teléfono principal</th>
            <th style='background-color: green; color: white;'>Tipo de Cliente</th>
        </thead>";
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $data_list[] = $row;
    }

    foreach ($data_list as $r) {

        $salida .= "
            <tr>
                <td>" . $r->cliente_consecutivo . "</td> 
                <td>" . $r->cliente_tpId . "</td> 
                <td>" . $r->cliente_nDocument . "</td>
                <td>" . $r->cliente_dv . "</td>
                <td>" . $r->cliente_estado . "</td>
                <td>" . $r->cliente_nombre . "</td>
                <td>" . $r->cliente_apellido . "</td>
                <td>" . $r->cliente_actEco . "</td>
                <td>" . $r->cliente_direccion . "</td>
                <td>" . $r->cliente_telefono . "</td>
                <td>" . $r->cliente_tpCliente . "</td>
            </tr>";
    }

    $salida .= "</table>";

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");

    header("Content-Disposition: attachment; filename=clientes_" . time() . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    return $salida;
}
