<?php
include_once 'main_class.php';

/*********** Clase para los clientes **************/
class clientClass
{
    // Se consulta información, de los clientes
    function tableDetails($table, $limit, $offset, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {
        try {

            $db = getDB();
            $idTabla = "id" . ucfirst($table);
            $cond = $table . '_estado';
            if ($_SESSION['usuario_Rol'] == 1) {

                if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                    $stmt = $db->prepare("SELECT cliente_consecutivo,cliente_nombre,cliente_apellido,cliente_dv,cliente_tpId,cliente_nDocument,idCliente,cliente_estado
                    FROM $table INNER JOIN cliente_bodega 
                    ON idCliente=cliente_bodega_cliente  
                    WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND cliente_bodega_bodega=" . $_SESSION['bodega'] . " ORDER BY $idTabla DESC LIMIT $limit,$offset ");
                } else {
                    $stmt = $db->prepare("SELECT cliente_consecutivo,cliente_nombre,cliente_apellido,cliente_dv,cliente_tpId,cliente_nDocument,idCliente,cliente_estado
                    FROM $table INNER JOIN cliente_bodega 
                    ON idCliente=cliente_bodega_cliente 
                    WHERE cliente_bodega_bodega=" . $_SESSION['bodega'] . "
                    ORDER BY $idTabla DESC LIMIT $limit,$offset ");
                }
            } else {
                if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                    $stmt = $db->prepare("SELECT cliente_consecutivo,cliente_nombre,cliente_apellido,cliente_dv,cliente_tpId,cliente_nDocument,idCliente 
                    FROM $table INNER JOIN cliente_bodega 
                    ON idCliente=cliente_bodega_cliente  
                    WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND $cond='activo' AND cliente_bodega_bodega=" . $_SESSION['bodega'] . " ORDER BY $idTabla DESC LIMIT $limit,$offset ");
                } else {
                    $stmt = $db->prepare("SELECT cliente_consecutivo,cliente_nombre,cliente_apellido,cliente_dv,cliente_tpId,cliente_nDocument,idCliente 
                    FROM $table INNER JOIN cliente_bodega 
                    ON idCliente=cliente_bodega_cliente 
                    WHERE $cond='activo' AND cliente_bodega_bodega=" . $_SESSION['bodega'] . "
                    ORDER BY $idTabla DESC LIMIT $limit,$offset ");
                }
            }


            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Función para buscar clientes
    function searchTableDetails($table, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3)
    {
        try {

            $db = getDB();
            $idTabla = "id" . ucfirst($table);
            $cond = $table . '_estado';
            if ($bus_1 != '' || $bus_2 != '' || $bus_3 != '') {
                $stmt = $db->prepare("SELECT cliente_consecutivo,cliente_nombre,cliente_apellido,cliente_dv,cliente_tpId,cliente_nDocument,idCliente,cliente_estado
                    FROM $table INNER JOIN cliente_bodega 
                    ON idCliente=cliente_bodega_cliente  
                    WHERE $campo_1 LIKE '" . $bus_1 . "%' AND $campo_2 LIKE '" . $bus_2 . "%' AND $campo_3 LIKE '" . $bus_3 . "%' AND cliente_bodega_bodega=" . $_SESSION['bodega'] . " ORDER BY $idTabla DESC");
            } else {
                $stmt = $db->prepare("SELECT cliente_consecutivo,cliente_nombre,cliente_apellido,cliente_dv,cliente_tpId,cliente_nDocument,idCliente 
                    FROM $table INNER JOIN cliente_bodega 
                    ON idCliente=cliente_bodega_cliente 
                    WHERE $cond='activo' AND cliente_bodega_bodega=" . $_SESSION['bodega'] . "
                    ORDER BY $idTabla DESC");
            }

            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $filas;
        } catch (Exception $e) {
            echo '{"Error"}:{"text"}:' . $e->getMessage() . '}}';
        }
    }

    // Crear cliente
    public function clientRegistration($tpId, $nDocument, $dv, $nombre, $apellido, $actEco, $direccion, $telefono, $ciudad, $tpCliente)
    {
        try {

            $idUser = $_SESSION['idUsuario'];
            $date = date('Y-m-d H:i:s', time());
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_INT);
            $st->execute();
            $count = $st->rowCount();
            if ($count >= 1) {

                $stmt = $db->prepare("INSERT INTO cliente(cliente_tpId,cliente_nDocument,cliente_dv,cliente_nombre,cliente_apellido,cliente_actEco,cliente_direccion,cliente_telefono,cliente_ciudad,cliente_tpCliente) VALUES (:tpId,:nDocument,:dv,:nombre,:apellido,:actEco,:direccion,:telefono,:ciudad,:tpCliente)");

                $stmt->bindParam(":tpId", $tpId, PDO::PARAM_STR);
                $stmt->bindParam(":nDocument", $nDocument, PDO::PARAM_INT);
                $stmt->bindParam(":dv", $dv, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":apellido", $apellido, PDO::PARAM_STR);
                $stmt->bindParam(":actEco", $actEco, PDO::PARAM_STR);
                $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
                $stmt->bindParam(":telefono", $telefono, PDO::PARAM_INT);
                $stmt->bindParam(":ciudad", $ciudad, PDO::PARAM_STR);
                $stmt->bindParam(":tpCliente", $tpCliente, PDO::PARAM_STR);
                $stmt->execute();
                $lastId = $db->lastInsertId();
                $consecutivo = 'CL-' . $lastId;

                $stmt = $db->prepare("UPDATE cliente SET cliente_consecutivo=:consecutivo WHERE idCliente=:id");
                $stmt->bindParam(":consecutivo", $consecutivo, PDO::PARAM_STR);
                $stmt->bindParam(":id", $lastId, PDO::PARAM_STR);
                $stmt->execute();

                $stmt = $db->prepare("INSERT INTO cliente_bodega(cliente_bodega_cliente,cliente_bodega_bodega,cliente_bodega_fechaIngreso) VALUES (:cliente,:bodega,:fecha)");
                $stmt->bindParam(":cliente", $lastId, PDO::PARAM_STR);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_STR);
                $stmt->bindParam(":fecha", $date, PDO::PARAM_STR);

                $stmt->execute();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $accion = "Crear";
                    $tabla = "cliente";
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

    // Eliminar cliente
    public function deleteClient()
    {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            session_start(['name' => 'SPM']);
            if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') {

                $id_del = encrypt_decrypt('decrypt', $_GET['id']);
                $db = getDB();

                $stmt = $db->prepare("UPDATE cliente SET cliente_estado='inactivo' WHERE idCliente=:id");
                $stmt->bindParam(":id", $id_del, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Eliminar";
                    $tabla = "cliente";
                    historial($idUser, $date, $id_del, $accion, $tabla);
                }
            }
            return true;
        }
    }

    // Actualizar cliente
    public function updateClient($tpId, $nDocument, $dv, $nombre, $apellido, $actEco, $direccion, $telefono, $ciudad, $tpCliente, $id, $estado)
    {

        try {
            $idUser = $_SESSION['idUsuario'];
            $db = getDB();
            $st = $db->prepare("SELECT idUsuario FROM usuario WHERE idUsuario=:iduser");
            $st->bindParam(":iduser", $idUser, PDO::PARAM_STR);
            $st->execute();
            $count = $st->rowCount();
            if ($count == 1) {
                $stmt = $db->prepare("UPDATE cliente SET cliente_tpId=:tpId,cliente_nDocument=:nDocument,cliente_dv=:dv,cliente_nombre=:nombre,cliente_apellido=:apellido,cliente_actEco=:actEco,cliente_direccion=:direccion,cliente_telefono=:telefono,cliente_ciudad=:ciudad,cliente_tpCliente=:tpCliente,cliente_estado=:estado WHERE idCliente = :id");
                $stmt->bindParam(":tpId", $tpId, PDO::PARAM_STR);
                $stmt->bindParam(":nDocument", $nDocument, PDO::PARAM_INT);
                $stmt->bindParam(":dv", $dv, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":apellido", $apellido, PDO::PARAM_STR);
                $stmt->bindParam(":actEco", $actEco, PDO::PARAM_STR);
                $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
                $stmt->bindParam(":telefono", $telefono, PDO::PARAM_INT);
                $stmt->bindParam(":ciudad", $ciudad, PDO::PARAM_INT);
                $stmt->bindParam(":tpCliente", $tpCliente, PDO::PARAM_STR);
                $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);

                $stmt->execute();


                if ($stmt->rowCount() >= 1) {

                    $idUser = $_SESSION['idUsuario'];
                    $date = date('Y-m-d H:i:s', time());
                    $accion = "Actualizar";
                    $tabla = "cliente";

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

    // Funcion para generar excel de clientes
    function GenerateExcel($tabla, $id)
    {
        session_start(['name' => "SPM"]);

        $db = getDB();
        $date = date('d-m-Y');
        $time = date('h:i:s a');

        $estado = $tabla . '_estado';

        if ($id == '') {
            if ($_SESSION['usuario_Rol'] == 1) {
                $stmt = $db->prepare("SELECT * FROM $tabla  as tb 
                LEFT JOIN cliente_bodega ON idCliente=cliente_bodega_cliente 
                LEFT JOIN bodega ON cliente_bodega_bodega=idBodega
                LEFT JOIN ciudad ON cliente_ciudad=idCiudad
                WHERE idBodega=:bodega");
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("SELECT * FROM $tabla  as tb 
                LEFT JOIN cliente_bodega ON idCliente=cliente_bodega_cliente 
                LEFT JOIN bodega ON cliente_bodega_bodega=idBodega
                LEFT JOIN ciudad ON cliente_ciudad=idCiudad
                WHERE $estado='activo' AND idBodega=:bodega");
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
                    <td>" . $r['ciudad_nombre']  . "</td>
                    <td>" . $r['cliente_nombre'] . "</td>
                    <td>" . $r['cliente_apellido'] . "</td>
                    <td>" . $r['cliente_actEco'] . "</td>
                    <td>" . $r['cliente_direccion'] . "</td>
                    <td>" . $r['cliente_telefono'] . "</td>
                    <td>" . $r['cliente_tpCliente'] . "</td>
                    <td>" . $r['cliente_estado'] . "</td>
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
                $stmt = $db->prepare("SELECT cliente.*,bodega_nombre,ciudad_nombre,
                GROUP_CONCAT(producto_consecutivo,'..',producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_bodega_cantidad,'..',producto_alerta,'..',producto_bodega_cantidadAlis,'..',producto_minimo,'..',producto_bodega_cantidadBlock  SEPARATOR '__') AS productos
                FROM cliente 
                LEFT JOIN producto ON idCliente=producto_idCliente
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                LEFT JOIN ciudad ON cliente_ciudad=idCiudad
                LEFT JOIN bodega ON producto_bodega_idBodega=idBodega
                WHERE idCliente=:id AND producto_bodega_idBodega=:bodega");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->bindParam(":bodega", $_SESSION['bodega'], PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $db->prepare("SELECT idBodega,bodega_nombre, bodega_estado
                FROM cliente 
                LEFT JOIN cliente_bodega ON idCliente=cliente_bodega_cliente
                LEFT JOIN bodega ON cliente_bodega_bodega=idBodega
                WHERE $individual=:id ");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                $bodegass = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $db->prepare("SELECT cliente.*,bodega_nombre,ciudad_nombre,
                GROUP_CONCAT(producto_consecutivo,'..',producto_codigo,'..',producto_nombre,'..',producto_peso,'..', producto_bodega_cantidad,'..',producto_alerta,'..',producto_bodega_cantidadAlis,'..',producto_minimo,'..',producto_bodega_cantidadBlock  SEPARATOR '__') AS productos
                FROM cliente 
                LEFT JOIN producto ON idCliente=producto_idCliente
                LEFT JOIN ciudad ON cliente_ciudad=idCiudad
                LEFT JOIN producto_bodega ON idProducto=producto_bodega_idProducto
                LEFT JOIN bodega ON producto_bodega_idBodega=idBodega
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
                            <td>" . $r['ciudad_nombre']  . "</td>
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
                            <th class='th' colspan='11'>Productos Asociados</th>
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
                    $minimo = intval($producto[7]);
                    $pesoT = $pesoT + ($peso * $cantidad);
                    $cantidadT = $cantidadT + $peso;
                    if ($cantidad == 0) {
                        $alerta = "<td class='td pedido'>PEDIDO</td> ";
                    } elseif ($cantidad <= $minimo) {
                        $alerta = "<td class='td bajo'>BAJO</td> ";
                    } else {
                        $alerta = "<td class='td'>NORMAL</td> ";
                    }
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
                        ($peso * ($cantidad + $cantidadBlock + $cantidadAlis)) . "Kg
                                </td>"
                        . $alerta .
                        "<td scope='col'>" .
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
                <th  colspan='3' class='th'>Bodegas Asociadas</th>
            </tr>
            <tr>
                <th class='th'>ID</th>
                <th class='th'>Nombre</th>
                <th class='th'>Estado</th>
                
            </tr>
    
        ";
            if (!empty($bodegass)) {
                foreach ($bodegass as $bg) {
                    // [0]=id
                    // [1]=nombre
                    // [2]=estado
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

            header("Content-Disposition: attachment; filename=clientes_" . time() . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            return $salida;
        }
    }
}
