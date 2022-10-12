<!DOCTYPE html>
<html lang="en">

<?php
// Se incluye loss estilos

include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Variables Globales para la paginación
$url = "enlistedDs-list.php";
$urlnew = "enlistedDs-new.php";
$tabla = "alistado";

// Se incluyen validaciónes importantes para las consultas
include_once '../assets/in/returns.php';

// Se incluye la calse de cliente
include_once '../../Controller/enlistedDs_class.php';

// Instanciamos una nueva clase de cliente
$enlistedClass = new enlistedDsClass(); ?>

<body class=" sb-nav-fixed">
    <?php
    // Operación para actualizar la cantidad alistada
    if (!isset($_GET['actu_prod']) && !empty($_POST['cant_alistup'])) {
        $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_UP']);

        if ($_POST['cant_alistup'] <= 0) { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'warning',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "La cantidad no puede ser menor a cero!",
                    Tipo: "",
                    href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        } else {
            $_SESSION["productosDsUp"][$idProd]['CantAlis'] = $_POST['cant_alistup'];
        }
    }

    // Operación para validar que la canidad sea mayor a cero
    if (!isset($_GET['new_prod']) && !empty($_POST['CantAlistNew'])) {

        $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_New']);
        if ($_POST['CantAlistNew'] <= 0) { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'warning',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "La cantidad no puede ser menor a cero!",
                    Tipo: "",
                    href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                };
                alertas_ajax(alerta);
            </script>
            <?php

        } else {
            $_SESSION["productosDs"][$idProd]['CantAlis'] = $_POST['CantAlistNew'];
        }
    }

    // Operación para consultar los productos asociados al cliente
    if (!empty($_GET['idUp']) and !empty($_GET['idCli']) || $_SESSION['idCliUp']) {

        unset($_SESSION['alistadoDsUpdate']);
        $_SESSION['alistadoDsUpdate'] = $_GET['idUp'];
        if (!isset($_SESSION['idCliUp'])) {
            $_SESSION['idCliUp'] = $_GET['idCli'];
        }

        $enlist = encrypt_decrypt('decrypt', $_SESSION['alistadoDsUpdate']);
        $alistado = consultaSimple('alistado', $enlist);
        $enlistedClass->searchEnlisted($_SESSION['alistadoDsUpdate']);
        $up = encrypt_decrypt('encrypt', "up");
        $dataUs = $enlistedClass->tableDetails($_SESSION['idCliUp'], $campo_1, $bus_1);
        $dataKit = $enlistedClass->kitDetails($_SESSION['idCliUp']);
        $_SESSION['url_global_despacho'] = "enlistedDs-new.php?upS=" . $up . "&idUp=" . $_SESSION['alistadoDsUpdate'] . '&idCli=' . $_SESSION['idCliUp'];
        $urlnew = $_SESSION['url_global_despacho'] = "enlistedDs-new.php?upS=" . $up . "&idUp=" . $_SESSION['alistadoDsUpdate'] . '&idCli=' . $_SESSION['idCliUp'];
    } else {
        $urlnew = "enlistedDs-new.php";
        if (!empty($_SESSION['idCliUp'])) {
            $_SESSION['idCliUp'];
        }
    }

    // Vista de Seleccion de usuario y producto
    if (!empty($_POST['id_key'])) {
        $user = $_POST['id_key'];
        $_SESSION['id_client_pro'] = $user;

        // Detalle de la tabla de producto
        $dataUs = $enlistedClass->tableDetails($user, $campo_1, $bus_1);
        $dataKit = $enlistedClass->kitDetails($user);
        $client_data = consultaSimple('clienteAlist', $user);
    } else if (isset($_SESSION['id_client_pro'])) {
        $user = $_SESSION['id_client_pro'];
        // Detalle de la tabla de producto
        $dataUs = $enlistedClass->tableDetails($user, $campo_1, $bus_1);
        $dataKit = $enlistedClass->kitDetails($user);
        $client_data = consultaSimple('clienteAlist', $user);
    }

    /* Formulario para actualizar los productos alistados */
    if (!empty($_POST['enlisted_up'])) {
        $tipo = "Despacho";
        $CantExistUp = limpiar_cadena($_POST['CantExistUp']);
        $fechaEntrada = "";
        $fechaDespacho = limpiar_cadena($_POST['fechaDespacho_up']);
        $cliente = limpiar_cadena($_POST['cliente_up']);
        $nombre = limpiar_cadena($_POST['nombrePersona_up']);
        $cedula = limpiar_cadena($_POST['cedulaPersona_up']);
        $placa = limpiar_cadena($_POST['placaPersona_up']);
        $clienteF = limpiar_cadena($_POST['clienteF_up']);
        $codigo = limpiar_cadena($_POST['codigo_up']);
        $observacion = limpiar_cadena($_POST['observacion_up']);

        if (isset($_SESSION['productosDsUp'])) {

            $producto = $_SESSION['productosDsUp'];
        } else {
            $producto = "";
        }



        if ($fechaDespacho >= 1 && $cliente >= 1 && $producto >= 1 && $nombre != "" && $cedula >= 1 && $placa != "" && $clienteF != "" && $codigo != "") {



            $uid = $enlistedClass->updateEnlisted($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $CantExistUp, $nombre, $cedula, $placa, $clienteF, $codigo, $observacion);
            if ($uid) { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Registrando Alistamiento...",
                        Texto: "El alistamiento se está registrando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-list.php"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'warning',
                        Titulo: "Ocurrio algo inesperado",
                        Texto: "Por favor verifique los datos ingresados!",
                        Tipo: "",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'warning',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "Error en el registro, intente nuevamente!",
                    Tipo: "",
                    href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                };
                alertas_ajax(alerta);
            </script>
            <?php    }
    }

    /* Formulario para registrar los nuevos productos*/
    if (!empty($_POST['enlisted_new'])) {

        $tipo = "Despacho";
        $fechaEntrada = "";
        $fechaDespacho = limpiar_cadena($_POST['fechaDespacho_new']);
        $cliente = limpiar_cadena($_POST['cliente_new']);
        $nombre = limpiar_cadena($_POST['nombrePersona_new']);
        $cedula = limpiar_cadena($_POST['cedulaPersona_new']);
        $placa = limpiar_cadena($_POST['placaPersona_new']);
        $clienteF = limpiar_cadena($_POST['clienteF_new']);
        $codigo = limpiar_cadena($_POST['codigo_new']);
        $observacion = limpiar_cadena($_POST['observacion_new']);

        if (isset($_SESSION['productosDs'])) {
            $producto = $_SESSION['productosDs'];
        } else {
            $producto = 0;
        }


        if ($fechaDespacho >= 1 && $cliente >= 1 && $producto >= 1 && $nombre != "" && $cedula >= 1 && $placa >= 1 && $clienteF != "" && $codigo != "") {

            $uid = $enlistedClass->enlistedRegistration($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $nombre, $cedula, $placa, $_SESSION['bodega'], $clienteF, $codigo, $observacion);

            if ($uid == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Registrando Alistamiento...",
                        Texto: "El alistamiento se está registrando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-list.php"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'warning',
                        Titulo: "Ocurrio algo inesperado",
                        Texto: "Error en el registro, intente nuevamente!",
                        Tipo: "",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'warning',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "Hay campos que son obligatorios!",
                    Tipo: "",
                    href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                };
                alertas_ajax(alerta);
            </script>
    <?php
        }
    } ?>

    <?php include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><?php echo $resultado = $var == 'update' ? 'Editar' : 'Nuevo' ?> Alistamiento </h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="enlistedDS-list.php">Alistados</a></li>
                        <?php if ($_SESSION['tp_estado'] != 'update') { ?>
                            <li class="breadcrumb-item"><a href="client-search.php">Escoger Cliente</a></li>
                        <?php } ?>
                        <li class="breadcrumb-item active"><?php echo $resultado =  $var == 'update' ? 'Editar' : 'Nuevo' ?> Alistamiento</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <div class="card-body">
                            <?php
                            if ($_SESSION['tp_estado'] == 'update') {   ?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <a onclick="GetUserDetailsUp()" style="width: 100%;" class="btn btn-success mb-3">Agregar Productos </a>
                                    </div>
                                    <div class="col-sm-2">
                                        <a onclick="GetKitDetailsUp()" style="width: 100%;" class="btn btn-success mb-3">Agregar Kit </a>
                                    </div>
                                </div>




                                <div class="row">

                                    <div class="card-body">
                                        <div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            Productos
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"></th>
                                                        <th scope="col">Codigo</th>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Peso</th>
                                                        <th scope="col">Cantidad Existente</th>
                                                        <th scope="col">Cantidad Alistado</th>
                                                        <th scope="col">Peso Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    // productos
                                                    if (!empty($_SESSION["productosDsUp"])) {

                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["productosDsUp"] as $row) {
                                                            if ($row['CantAlis'] > 0) {
                                                                # code...
                                                                $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                                $cantidadT = $cantidadT + $row['CantAlis'];
                                                                // Cantidad disponible 
                                                    ?>
                                                                <tr>
                                                                    <td>#</td>
                                                                    <td><?php echo $row['Codigo'] ?></td>
                                                                    <td><?php echo $row['Nombre'] ?></td>
                                                                    <td><?php echo $row['Peso']; ?> Kg</td>
                                                                    <td><?php echo $row['CantExis']; ?></td>
                                                                    <form action="#" method="post">
                                                                        <td>
                                                                            <input class="form-control" type="number" name="cant_alistup" value="<?php echo $row['CantAlis']; ?>">
                                                                        </td>
                                                                        <td>
                                                                            <?php echo ($row['Peso'] * $row['CantAlis']); ?> Kg
                                                                        </td>

                                                                        <td>
                                                                            <input type="hidden" name="Cant_Alist_UP" value="<?php echo encrypt_decrypt('encrypt', $row['id']); ?>">

                                                                            <button class="btn btn-success" type="submit" name="actu_prod" value="actu_prod"><i class="fa-solid fa-floppy-disk"></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <a href="../../Ajax/ajax_enlistedDs.php?del=deleteProductUp&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>&cantAlis=<?php echo encrypt_decrypt('encrypt', $row['CantAlis']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>
                                                                        </td>
                                                                    </form>
                                                                </tr>
                                                        <?php
                                                                $count++;
                                                            }
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td colspan="5"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?> Kg</td>
                                                        </tr>
                                                    <?php
                                                    } else {
                                                        echo
                                                        "<tr class='text-center'>
                                                                <td colspan='9'>No hay registros en el sistema</td>
                                                            </tr>";
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($_SESSION['productosDsUp'])) { ?>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_enlistedDs.php?del=emptyUp">Vaciar Productos</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <form action='' method="POST" autocomplete="off" name="enlisted_new" class="needs-validation" novalidate>
                                    <div class="row mb-3">

                                        <div class="col">
                                            <label class="form-label">Fecha Despacho</label>
                                            <input type="date" class="form-control" name="fechaDespacho_up" value="<?php echo $alistado->alistado_fechaDespacho ?>" required>
                                        </div>

                                        <div class="col">
                                            <?php $cli_ing = consultaSimple('clienteAlist', $_SESSION['idCliUp']); ?>

                                            <div class="col">
                                                <label class="form-label">Cliente</label>
                                                <input type="hidden" name="cliente_up" value="<?php echo $cli_ing[0]['idCliente']; ?>">
                                                <input type="text" class="form-control" id="" value="<?php echo  $cli_ing[0]['cliente_nombre'] . ' ' . $cli_ing[0]['cliente_apellido'] ?>" required disabled>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Cliente Final</label>
                                            <input type="text" class="form-control" name="clienteF_up" value="<?php echo $alistado->alistado_clienteF ?>" required>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Codigo Ingreso</label>
                                            <input type="text" class="form-control" name="codigo_up" value="<?php echo $alistado->alistado_codigo ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">

                                        <div class="col">
                                            <label class="form-label">Nombre Persona</label>
                                            <input type="text" class="form-control" name="nombrePersona_up" value="<?php echo $alistado->alistado_nombrePersona ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Cedula Persona</label>
                                            <input type="number" class="form-control" name="cedulaPersona_up" value="<?php echo $alistado->alistado_cedulaPersona ?>" required>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Placa</label>
                                            <input type="text" class="form-control" name="placaPersona_up" value="<?php echo $alistado->alistado_placaPersona ?>" required>
                                        </div>

                                    </div>
                                    <div class="row mb-3">

                                        <div class="col">
                                            <label class="form-label">Observación</label>
                                            <textarea class="form-control" name="observacion_up" maxlength="200"><?php echo $alistado->alistado_observacion ?></textarea>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="enlisted_up" class="btn btn-primary" value="enlisted_up"> Registrar </button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="CantExistUp" value="<?php echo $row['id']; ?>">



                                </form>
                                <br>

                            <?php } else { ?>

                                <div class="row">
                                    <div class="col-sm-2">
                                        <a onclick="GetUserDetails()" style="width: 100%;" class="btn btn-success">Agregar Producto</a>
                                    </div>
                                    <div class="col-sm-2">
                                        <a onclick="GetKitDetails()" style="width: 100%;" class="btn btn-success">Agregar Kit </a>
                                    </div>
                                    <div class="col-sm-2">
                                        <form action="client-search.php" method="post">
                                            <input type="text" name="url" value="enlistedDs" hidden>
                                            <button type="submit" style="width: 100%;" class="btn btn-secondary">Cambiar Cliente </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="card-body">
                                        <div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            Productos
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"></th>
                                                        <th scope="col">Codigo</th>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Peso</th>
                                                        <th scope="col">Cantidad Existente</th>
                                                        <th scope="col">Cantidad Alistado</th>
                                                        <th scope="col">Peso Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php


                                                    if (!empty($_SESSION["productosDs"])) {

                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["productosDs"] as $row) {
                                                            $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                            $cantidadT = $cantidadT + $row['CantAlis'];

                                                    ?>
                                                            <tr>
                                                                <td>#</td>
                                                                <td><?php echo $row['Codigo'] ?></td>
                                                                <td><?php echo $row['Nombre'] ?></td>
                                                                <td><?php echo $row['Peso']; ?> Kg</td>
                                                                <td><?php echo $row['CantExis']; ?></td>

                                                                <form action="#" method="post">

                                                                    <td>
                                                                        <input class="form-control" type="number" name="CantAlistNew" value="<?php echo $row['CantAlis']; ?>">
                                                                    </td>
                                                                    <td>
                                                                        <?php echo ($row['Peso'] * $row['CantAlis']); ?> Kg
                                                                    </td>

                                                                    <td>
                                                                        <input type="hidden" name="Cant_Alist_New" value="<?php echo encrypt_decrypt('encrypt', $row['id']); ?>">

                                                                        <button class="btn btn-success" type="submit" name="new_prod" value="new_prod"><i class="fa-solid fa-floppy-disk"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        <a href="../../Ajax/ajax_enlistedDs.php?del=deleteProduct&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>
                                                                    </td>
                                                                </form>
                                                                <!-- Button trigger modal -->
                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        }
                                                        ?>

                                                        <tr>
                                                            <td colspan="5"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?> Kg</td>
                                                        </tr>
                                                    <?php

                                                    } else {
                                                        echo
                                                        "<tr class='text-center'>
                                                                <td colspan='9'>No hay registros en el sistema</td>
                                                            </tr>";
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($_SESSION['productosDs'])) { ?>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_enlistedDs.php?del=empty">Vaciar Productos</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <form action='' method="POST" autocomplete="off" name="enlisted_new" class="needs-validation" novalidate>
                                    <div class="row mb-3">

                                        <div class="col">
                                            <label class="form-label">Fecha Despacho</label>
                                            <input type="date" class="form-control" name="fechaDespacho_new" value="<?php echo date("Y-m-d"); ?>" required>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Cliente</label>
                                            <input type="hidden" name="cliente_new" value="<?php echo $_SESSION['id_client_pro']; ?>">
                                            <?php if (isset($client_data[0])) { ?>
                                                <input type="text" class="form-control" id="" value="<?php echo $client_data[0]['cliente_nombre'] . ' ' . $client_data[0]['cliente_apellido'] ?>" required disabled>

                                            <?php } else { ?>
                                                <input type="text" class="form-control" id="" value="" required disabled>
                                            <?php } ?>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Cliente Final</label>
                                            <input type="text" class="form-control" name="clienteF_new" value="<?php echo $resultado = empty($_POST['clienteF_new']) ? '' : $_POST['clienteF_new']; ?>" required>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Codigo Ingreso</label>
                                            <input type="text" class="form-control" name="codigo_new" value="<?php echo $resultado = empty($_POST['codigo_new']) ? '' : $_POST['codigo_new']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label class="form-label">Nombre Persona</label>
                                            <input type="text" class="form-control" name="nombrePersona_new" value="<?php echo $resultado = empty($_POST['nombrePersona_new']) ? '' : $_POST['nombrePersona_new']; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Cedula Persona</label>
                                            <input type="number" class="form-control" name="cedulaPersona_new" value="<?php echo $resultado = empty($_POST['cedulaPersona_new']) ? '' : $_POST['cedulaPersona_new']; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Placa</label>
                                            <input type="text" class="form-control" name="placaPersona_new" value="<?php echo $resultado = empty($_POST['placaPersona_new']) ? '' : $_POST['placaPersona_new']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">

                                        <div class="col">
                                            <label class="form-label">Observación</label>
                                            <textarea class="form-control" name="observacion_new" maxlength="200"><?php echo $resultado = empty($_POST['observacion_new']) ? '' : $_POST['observacion_new']; ?></textarea>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="enlisted_new" class="btn btn-primary" value="enlisted_new"> Registrar </button>
                                            </div>
                                        </div>
                                    </div>


                                </form>
                                <br>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once '../assets/in/modals.php'; ?>

        </div>
    </div>

    <!-- Scrypt de funcionalidades -->
    <script>
        // Modal
        function GetUserDetails() {
            $("#producto_alistadoDs_modal").modal("show");
        }

        function GetKitDetails() {
            $("#kit_alistadoDs_modal").modal("show");
        }

        function GetKitDetailsUp() {
            $("#kit_alistadoDsUp_modal").modal("show");
        }

        function GetUserDetailsUp() {
            $("#update_alistadoDs_modal").modal("show");
        }
        // Fin de la función

        // Función para preguntar si desea eliminar el cliente y alerta de eliminado con éxito
        $('.btn_table_del').on("click", function(e) {

            e.preventDefault();

            console.log('ingresa');

            const href = $(this).attr('href')

            Swal.fire({
                title: '¿Estas seguro?',
                text: "¡No podras revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí, eliminar!'
            }).then((result) => {
                if (result.value) {
                    document.location.href = href;
                }
            });
        });

        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

</body>

</html>