<!DOCTYPE html>
<html lang="en">

<?php

include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Variables Globales
$url = "reception-list.php";
$tabla = "entrada";

// Total de la páginación x vista
include_once '../assets/in/returns.php';

// Se incluye la calse de cliente
include_once '../../Controller/reception_class.php';

// Instanciamos una nueva clase de cliente
$receptionClass = new receptionClass(); ?>

<body class=" sb-nav-fixed">
    <?php

    // Se obtiene el id del ingreso para reealizar las consultas
    if (isset($_GET['id'])) {

        $_SESSION['alistado'] = $_GET['id'];
        $id_del = $_SESSION['alistado'];

        $enlisted = $receptionClass->searchEnlisted($_SESSION['alistado']);

        // Se declaran consultas para los select de cliente
        $cons_client = consultaSimple('clienteAlist', $enlisted[0]['alistado_idCliente']);

        if (empty($enlisted)) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio un error...",
                    Texto: "Pre aviso ya despachado!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/reception-list.php"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        } else {
            $user = $enlisted[0]['alistado_idCliente'];
            $dataPr = $receptionClass->tableDetails("productoUser", $user, $campo_1, $bus_1);

            $id_del = encrypt_decrypt('decrypt', $id_del);
        }
    }

    // Se actualiza la data de la Sessión de produtos, se agregar la información del estado del producto
    if (isset($_POST['actu_prod']) and $_POST['actu_prod'] == 'actu_prod') {

        $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_UP']);

        $cant_alis = $_POST['cant_alistup'];
        $novedades = $_POST['novedadesUp'];

        if ($cant_alis < 0) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio un error...",
                    Texto: "La cantidad ingresada no es valida!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/reception-list.php"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        } else if ($novedades < 0) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio un error...",
                    Texto: "La cantidad ingresada no es valida!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/reception/reception-list.php"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        } else {
            $_SESSION["recepcion"][$idProd]['CantAlis'] = $_POST['cant_alistup'];
            $_SESSION["recepcion"][$idProd]['Novedades'] = $novedades;
            $_SESSION["recepcion"][$idProd]['Descripcion'] = $_POST['descripcionUp'];
            $_SESSION["recepcion"][$idProd]['Prioridad'] = $_POST['prioridadUp'];
        }
    }

    // Se crea un nuevo ingreso en la data de la Sessión de produtos, se agregar la información del estado del producto
    if (isset($_POST['new_prod']) and $_POST['new_prod'] == 'new_prod') {

        $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_New']);

        $cant_alisNew = $_POST['CantAlistNew'];
        $novedadesNew = $_POST['NewnovedadesUp'];

        if ($cant_alisNew < 0) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio un error...",
                    Texto: "La cantidad ingresada no es valida!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/client-list.php"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        } else if ($novedadesNew < 0) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio un error...",
                    Texto: "La cantidad ingresada no es valida!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/client-list.php"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        } else {
            $_SESSION["recepcion2"][$idProd]['CantAlis'] = $_POST['CantAlistNew'];
            $_SESSION["recepcion2"][$idProd]['Novedades'] = $novedadesNew;
            $_SESSION["recepcion2"][$idProd]['Descripcion'] = $_POST['NewdescripcionUp'];
            $_SESSION["recepcion2"][$idProd]['Prioridad'] = $_POST['NewprioridadUp'];
        }
    }

    // Vista de Seleccion de usuario y producto
    if (!empty($_POST['id_key'])) {

        $_SESSION['id_client_pro'] = $_POST['id_key'];

        $dataPr = $receptionClass->tableDetails("productoUser", $_SESSION['id_client_pro'], $campo_1, $bus_1);
        $client_data = consultaSimple('clienteAlist', $_SESSION['id_client_pro']);
    } else if (!empty($_SESSION['id_client_pro'])) {

        $dataPr = $receptionClass->tableDetails("productoUser",  $_SESSION['id_client_pro'], $campo_1, $bus_1);

        $client_data = consultaSimple('clienteAlist',  $_SESSION['id_client_pro']);
    }

    /* Create Form con preaviso*/
    if (!empty($_POST['reception_new'])) {
        $nombre = limpiar_cadena($_POST['nombrePersona_new']);
        $cedula = limpiar_cadena($_POST['cedulaPersona_new']);
        $placa = limpiar_cadena($_POST['placaPersona_new']);
        $idCliente = limpiar_cadena($_POST['idCliente_New']);
        $bodega = limpiar_cadena($_SESSION['bodega']);
        $firma = limpiar_cadena($_FILES['ingreso_firma']['name']);

        $imgExt = strtolower(pathinfo($firma, PATHINFO_EXTENSION));

        $allow = array('png', 'jpg', 'jpeg');

        if (!in_array($imgExt, $allow)) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'info',
                    Titulo: "Ocurrio un error...",
                    Texto: "La extención o el documento no es valido!"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }

        if (isset($_SESSION['recepcion']) && isset($enlisted)) {
            $producto = $_SESSION['recepcion'];
        } else {
            $producto = "";
        }

        if ($producto != "" && $enlisted != '' && $nombre != '' && $cedula != '' && $placa != '' && $bodega != '') {

            $vali = $receptionClass->validacionPersona($nombre, $cedula, $placa, $enlisted);

            if ($vali == "true") {

                $val = $receptionClass->receptionRegistration($idCliente, $producto, $nombre, $cedula, $placa, $bodega, $_FILES['ingreso_firma']);


                if ($val == 'true') { ?>
                    <script>
                        let alerta = {
                            Alerta: "registro",
                            Icono: '',
                            Titulo: "Registrando ingreso",
                            Texto: "El ingreso se está registrando correctamente",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/reception-list.php"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                } else if ($val == "WrongProduct") { ?>
                    <script>
                        let alerta = {
                            Alerta: "error",
                            Icono: 'info',
                            Titulo: "Ocurrio un error...",
                            Texto: "El producto seleccionado no está en el alistamiento!"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                } else { ?>
                    <script>
                        let alerta = {
                            Alerta: "error",
                            Icono: 'error',
                            Titulo: "Ocurrio un error...",
                            Texto: "Error en el registro, intente nuevamente!"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                }
            } else if ($vali == "nombre") {
                ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'warning',
                        Titulo: "Ocurrio un error...",
                        Texto: "Nombre de la persona no coincide con el alistado"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else if ($vali == "cedula") { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'warning',
                        Titulo: "Ocurrio un error...",
                        Texto: "Cedula de la persona no coincide con el alistado"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else if ($vali == "placa") { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'warning',
                        Titulo: "Ocurrio un error...",
                        Texto: "Placa de la persona no coincide con el alistado"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "Ocurrio un error...",
                        Texto: "Ocurrio un error en el registro, contacte ocn el administrador!"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'warning',
                    Titulo: "Ocurrio un error...",
                    Texto: "Debe seleccionar al menos un producto!"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        }
    }

    /* Crear formulario sin un-preaviso*/
    if (!empty($_POST['reception2_new'])) {

        $nombre = limpiar_cadena($_POST['nombrePersona_new']);
        $cedula = limpiar_cadena($_POST['cedulaPersona_new']);
        $placa = limpiar_cadena($_POST['placaPersona_new']);
        $bodega = limpiar_cadena($_SESSION['bodega']);
        $idCliente = limpiar_cadena($_POST['idCliente_New']);
        $firma = limpiar_cadena($_FILES['ingreso_firma_2']['name']);

        $imgExt = strtolower(pathinfo($firma, PATHINFO_EXTENSION));

        $allow = array('png', 'jpg', 'jpeg');

        if (!in_array($imgExt, $allow)) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'info',
                    Titulo: "Ocurrio un error...",
                    Texto: "La extención o el documento no es valido!"
                };
                alertas_ajax(alerta);
            </script>
            <?php }

        if (isset($_SESSION['recepcion2'])) {
            $producto = $_SESSION['recepcion2'];
        } else {
            $producto = "";
        }

        if ($nombre != '' && $cedula != '' && $placa != '' && $producto != '') {


            $val = $receptionClass->receptionRegistration2($idCliente, $producto, $nombre, $cedula, $placa, $bodega, $_FILES['ingreso_firma_2']);

            if ($val == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Registrando ingreso",
                        Texto: "El ingreso se está registrando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-list.php"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "Ocurrio un error...",
                        Texto: "Error al registrar el ingreso, contacte con el administrador!"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'warning',
                    Titulo: "Ocurrio un error...",
                    Texto: "Hay campos que no sean completado!"
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
                    <h1 class="mt-4">Ingresar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <?php if (!isset($_GET['id'])) { ?>
                            <li class="breadcrumb-item"><a href="reception-list.php">Ingresos</a></li>

                            <li class="breadcrumb-item"><a href="client-search.php">Escoger Cliente</a></li>
                        <?php } else { ?>
                            <li class="breadcrumb-item"><a href="enlisted-list.php">Pre-Ingresos</a></li>
                        <?php } ?>
                        <li class="breadcrumb-item active">Ingresar pedido</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data de Ingresos
                        </div>
                        <div class="card-body">
                            <?php if (!isset($_GET['id'])) { ?>
                                <!-- Ingreso de productos sin pre-Alistamiento -->
                                <div class="row">
                                    <div class="col-sm-2">
                                        <a onclick="GetUserDetails2()" style="width:100%;" class="btn btn-success">Agregar Productos</a>
                                    </div>
                                    <div class="col-sm-2">
                                        <form action="client-search.php" method="post">
                                            <input type="text" name="url" value="reception" hidden>
                                            <button type="submit" style="width:100%;" class="btn btn-secondary">Cambiar Cliente </button>
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
                                                        <th scope="col">Codigo</th>
                                                        <th scope="col">Peso</th>
                                                        <th scope="col">Novedades</th>
                                                        <th scope="col">Descripción</th>
                                                        <th scope="col">Prioridad</th>
                                                        <th scope="col">Cantidad a Ingresar</th>
                                                        <th scope="col">Peso Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($_SESSION["recepcion2"])) {
                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["recepcion2"] as $row) {
                                                            $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                            $cantidadT = $cantidadT + $row['CantAlis'];  ?>
                                                            <tr>
                                                                <td><?php echo $row['Codigo'] ?></td>
                                                                <td><?php echo $row['Peso']; ?></td>
                                                                <form action="#" method="post">
                                                                    <td>
                                                                        <input class="form-control" type="number" name="NewnovedadesUp" value="<?php echo $row['Novedades']; ?>">
                                                                    </td>
                                                                    <td>
                                                                        <textarea class="form-control" maxlength="500" name="NewdescripcionUp"><?php echo $row['Descripcion']; ?></textarea>
                                                                    </td>
                                                                    <td>
                                                                        <select class=" form-select" name="NewprioridadUp">
                                                                            <option value="Control" <?php echo $resultado = $row['Prioridad']  == "Control" ? "selected" : ''; ?>>Control</option>
                                                                            <option value="Alto" <?php echo $resultado = $row['Prioridad']  == "Alto" ? "selected" : ''; ?>>Alto</option>
                                                                            <option value="Medio" <?php echo $resultado = $row['Prioridad']  == "Medio" ? "selected" : ''; ?>>Medio</option>
                                                                            <option value="Bajo" <?php echo $resultado = $row['Prioridad']  == "Bajo" ? "selected" : ''; ?>>Bajo</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control" type="number" name="CantAlistNew" value="<?php echo $row['CantAlis']; ?>">
                                                                    </td>
                                                                    <td><?php echo ($row['Peso'] * $row['CantAlis']); ?></td>
                                                                    <td>
                                                                        <input type="hidden" name="Cant_Alist_New" value="<?php echo encrypt_decrypt('encrypt', $row['id']); ?>">

                                                                        <button class="btn btn-success" type="submit" name="new_prod" value="new_prod"><i class="fa-solid fa-floppy-disk"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        <a href="../../Ajax/ajax_reception.php?del=deleteProductNew&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>
                                                                    </td>
                                                                </form>
                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        } ?>
                                                        <tr>
                                                            <td colspan="5"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?></td>
                                                        </tr>
                                                    <?php
                                                    } else { ?>
                                                        <tr class="text-center">
                                                            <td colspan="12">No hay registros en el sistema</td>
                                                        </tr>
                                                    <?php  } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($_SESSION['recepcion2'])) { ?>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <a class="btn btn-danger" href="../../Ajax/ajax_reception.php?del=empty2">Vaciar Productos</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <form action='' method="POST" autocomplete="off" name="reception_new" id="reception_new" class="needs-validation" novalidate enctype="multipart/form-data">

                                    <div class="row card-body mt-3">
                                        <div class="card-header mb-3">
                                            <i class="fas fa-table me-1"></i>
                                            Datos del Cliente
                                        </div>
                                        <div class="row mb-3 ">
                                            <div class="col">
                                                <label class="form-label">Nombre del cliente: </label>
                                                <input type="hidden" class="form-control" name="idCliente_New" value="<?php echo $client_data[0]['idCliente'] ?>">

                                                <input type="text" class="form-control" value="<?php echo $client_data[0]['cliente_nombre']  . ' ' .  $client_data[0]['cliente_apellido'] ?>" disabled>
                                            </div>
                                            <div class="col">
                                                <label class="form-label">N. Documento: </label>
                                                <input type="text" class="form-control" name="cliente_nDocument" value="<?php echo $client_data[0]['cliente_nDocument'] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3 ">


                                        </div>
                                    </div>
                                    <div class="row card-body">
                                        <div class="card-header mb-3">
                                            <i class="fas fa-table me-1"></i>
                                            Datos del Conductor
                                        </div>
                                        <div class="row mb-3 ">

                                            <div class="col">
                                                <label class="form-label">Nombre: </label>
                                                <input type="text" class="form-control" name="nombrePersona_new" required>
                                            </div>

                                            <div class="col">
                                                <label class="form-label">Cedula: </label>
                                                <input type="number" class="form-control" name="cedulaPersona_new" required>
                                            </div>
                                            <div class="col">
                                                <label class="form-label">Placa: </label>
                                                <input type="text" class="form-control" name="placaPersona_new" required>
                                            </div>
                                        </div>

                                        <!-- firma -->
                                        <div class="row mb-3">
                                            <span class="d-block pb-2">Firma digital: </span>
                                            <div id="signature-pad" class="signature-pad">
                                                <div class="signature-pad--body">
                                                    <canvas style="width: 100%; height: 250px; border:0.9px solid #ced4da; border-radius:12px;"></canvas>

                                                </div>
                                                <div class="signature-pad--footer">
                                                    <div class="signature-pad--actions">
                                                        <div class="row">
                                                            <div class="col mt-5">
                                                                <button type="button" class="btn btn-secondary" data-action="clear">Limpiar Firma</button>
                                                                <button type="button" class="btn btn-success" data-action="save-png">Guardar como PNG</button>
                                                            </div>
                                                            <div class="col-8 mt-2">
                                                                <label class="form-label" for="customFile">Adjuntar firma</label>
                                                                <input type="file" class="form-control" name="ingreso_firma_2" multiple accept="jpg,.png">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="reception2_new" class="btn btn-primary" value="enlisted_new"> Registrar </button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                                <br>
                            <?php } else { ?>
                                <!-- Formulario para un pre-aviso -->
                                <div class="row">
                                    <div class="col-sm-2">
                                        <a onclick="GetUserDetails()" style="width: 100%;" class="btn btn-success mb-3">Agregar Productos </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="card-body">
                                            <div class="card-header">
                                                <i class="fas fa-table me-1"></i>
                                                Productos
                                            </div>
                                            <div class="card-body table-responsive">
                                                <table class="table align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Codigo</th>
                                                            <th scope="col">Peso</th>
                                                            <th scope="col">Novedades</th>
                                                            <th scope="col">Descripción</th>
                                                            <th scope="col">Prioridad</th>
                                                            <th scope="col">Cantidad a ingresar</th>
                                                            <th scope="col">Peso Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="overflow-y:auto;">
                                                        <?php
                                                        if (!empty($_SESSION["recepcion"])) {
                                                            $pesoT = 0;
                                                            $cantidadT = 0;

                                                            foreach ($_SESSION["recepcion"] as $row) {
                                                                $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                                $cantidadT = $cantidadT + $row['CantAlis']; ?>
                                                                <tr>
                                                                    <td><?php echo $row['Codigo'] ?></td>
                                                                    <td><?php echo $row['Peso']; ?></td>
                                                                    <form action="#" method="post">
                                                                        <td>
                                                                            <input class="form-control" type="number" name="novedadesUp" value="<?php echo $row['Novedades']; ?>">
                                                                        </td>
                                                                        <td>
                                                                            <textarea class="form-control" maxlength="500" name="descripcionUp"><?php echo $row['Descripcion']; ?></textarea>
                                                                        </td>
                                                                        <td>
                                                                            <select class=" form-select" name="prioridadUp">
                                                                                <option value="Control" <?php echo $resultado = $row['Prioridad']  == "Control" ? "selected" : ''; ?>>Control</option>
                                                                                <option value="Alto" <?php echo $resultado = $row['Prioridad']  == "Alto" ? "selected" : ''; ?>>Alto</option>
                                                                                <option value="Medio" <?php echo $resultado = $row['Prioridad']  == "Medio" ? "selected" : ''; ?>>Medio</option>
                                                                                <option value="Bajo" <?php echo $resultado = $row['Prioridad']  == "Bajo" ? "selected" : ''; ?>>Bajo</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control" type="number" name="cant_alistup" value="<?php echo $row['CantAlis']; ?>">
                                                                        </td>
                                                                        <td>
                                                                            <?php echo ($row['Peso'] * $row['CantAlis']); ?>
                                                                        </td>

                                                                        <td>
                                                                            <input type="hidden" name="Cant_Alist_UP" value="<?php echo encrypt_decrypt('encrypt', $row['id']); ?>">
                                                                            <button class="btn btn-success" type="submit" name="actu_prod" value="actu_prod"><i class="fa-solid fa-floppy-disk"></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <a href="../../Ajax/ajax_reception.php?del=deleteProduct&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>
                                                                        </td>
                                                                    </form>
                                                                </tr>
                                                            <?php
                                                                $count++;
                                                            } ?>
                                                            <tr>
                                                                <td colspan="5"></td>
                                                                <td class="table-active"><?php echo $cantidadT ?></td>
                                                                <td class="table-active"><?php echo $pesoT ?></td>
                                                            </tr>
                                                        <?php
                                                        } else { ?>
                                                            <tr class="text-center">
                                                                <td colspan="9">No hay registros en el sistema</td>
                                                            </tr>
                                                        <?php  } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="card-body">
                                            <div class="card-header">
                                                <i class="fas fa-table me-1"></i>
                                                Productos Alistados
                                            </div>
                                            <div class="card-body table-responsive">
                                                <table class="table align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Codigo</th>
                                                            <th scope="col">Peso</th>
                                                            <th scope="col">Cantidad Existente</th>
                                                            <th scope="col">Cantidad Alistada</th>
                                                            <th scope="col">Peso Total</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody style="overflow-x:auto;">
                                                        <?php
                                                        if (!empty($enlisted)) {
                                                            $pesoT2 = 0;
                                                            $cantidadT2 = 0;

                                                            foreach ($enlisted as $enli) {
                                                        ?>
                                                                <tr>
                                                                    <?php foreach (explode("__", $enli["productos"]) as $e) {
                                                                        $producto = explode("..", $e);
                                                                        $pesoT2 = $pesoT2 + ($producto[3] * $enli['producto_alistado_cantidad']);
                                                                        $cantidadT2 = $cantidadT2 + $enli['producto_alistado_cantidad']; ?>

                                                                        <td><?php echo $producto[0] ?></td>
                                                                        <td><?php echo $producto[3]; ?></td>
                                                                        <td><?php echo $producto[4]; ?></td>

                                                                    <?php } ?>
                                                                    <td><?php echo $enli['producto_alistado_cantidad']; ?></td>

                                                                    <td><?php echo ($producto[3] * $enli['producto_alistado_cantidad']) ?></td>

                                                                </tr>
                                                            <?php
                                                                $count++;
                                                            } ?>
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <td class="table-active"><?php echo $cantidadT2 ?></td>
                                                                <td class="table-active"><?php echo $pesoT2 ?></td>
                                                            </tr>
                                                        <?php
                                                        } else { ?>
                                                            <tr class="text-center">
                                                                <td colspan="9">No hay registros en el sistema</td>
                                                            </tr>
                                                        <?php  } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($_SESSION['recepcion'])) { ?>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <a class="btn btn-danger" href="../../Ajax/ajax_reception.php?del=empty">Vaciar Productos</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <form action='' method="POST" autocomplete="off" name="reception_new" class="needs-validation" novalidate enctype="multipart/form-data">
                                    <div class="row card-body mt-3">
                                        <div class="col">
                                            <label class="form-label">Bodega (Seleccionada)</label>
                                            <select class="form-select" aria-label="Default select example" name="bodega_new" value="<?php echo $enlisted[0]['idBodega'] ?>" style="margin-right:8px;" disabled>
                                                <option value="<?php echo $enlisted[0]['idBodega'] . '' ?>" selected><?php echo $enlisted[0]['bodega_nombre'] ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row card-body mt-3">
                                        <div class="card-header mb-3">
                                            <i class="fas fa-table me-1"></i>
                                            Datos del Cliente
                                        </div>
                                        <div class="row mb-3 ">
                                            <div class="col">
                                                <label class="form-label">Nombre del cliente: </label>
                                                <input type="hidden" class="form-control" name="idCliente_New" value="<?php echo $cons_client[0]['idCliente'] ?>">

                                                <input type="text" class="form-control" value="<?php echo $cons_client[0]['cliente_nombre']  . ' ' .  $cons_client[0]['cliente_apellido'] ?>" disabled>
                                            </div>
                                            <div class="col">
                                                <label class="form-label">N. Documento: </label>
                                                <input type="text" class="form-control" name="cliente_nDocument" value="<?php echo $cons_client[0]['cliente_nDocument'] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3 ">


                                        </div>
                                    </div>
                                    <div class="row card-body mt-3">
                                        <div class="card-header mb-3">
                                            <i class="fas fa-table me-1"></i>
                                            Datos del Conductor
                                        </div>
                                        <div class="row mb-3 ">
                                            <?php if (isset($_POST['nombrePersona_new'])) { ?>
                                                <div class="col">
                                                    <label class="form-label">Nombre: </label>
                                                    <input type="text" class="form-control" name="nombrePersona_new" value="<?php echo $_POST['nombrePersona_new'] ?>" required>
                                                </div>

                                                <div class="col">
                                                    <label class="form-label">Cedula:</label>
                                                    <input type="number" class="form-control" name="cedulaPersona_new" value="<?php echo $_POST['cedulaPersona_new'] ?>" required>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label">Placa:</label>
                                                    <input type="text" class="form-control" name="placaPersona_new" value="<?php echo $_POST['placaPersona_new'] ?>" required>
                                                </div>
                                            <?php
                                            } else {
                                            ?>
                                                <div class="col">
                                                    <label class="form-label">Nombre: </label>
                                                    <input type="text" class="form-control" name="nombrePersona_new" value="<?php echo $resultado = !empty($enlisted[0]['alistado_nombrePersona']) ? $enlisted[0]['alistado_nombrePersona'] : ''; ?>" required>
                                                </div>

                                                <div class="col">
                                                    <label class="form-label">Cedula:</label>
                                                    <input type="number" class="form-control" name="cedulaPersona_new" value="<?php echo $resultado = !empty($enlisted[0]['alistado_cedulaPersona']) ? $enlisted[0]['alistado_cedulaPersona'] : ''; ?>" required>
                                                </div>
                                                <div class="col">
                                                    <label class="form-label">Placa:</label>
                                                    <input type="text" class="form-control" name="placaPersona_new" value="<?php echo $resultado = !empty($enlisted[0]['alistado_placaPersona']) ? $enlisted[0]['alistado_placaPersona'] : ''; ?>" required>
                                                </div>

                                            <?php
                                            }
                                            ?>
                                        </div>

                                    </div>
                                    <div class="row card-body">

                                        <div class="col-sm-12">
                                            <span class="d-block pb-2">Firma digital</span>
                                            <div id="signature-pad" class="signature-pad">
                                                <div class="signature-pad--body">
                                                    <canvas style="width: 100%; height: 250px; border:0.9px solid #ced4da; border-radius:12px;"></canvas>
                                                </div>
                                                <div class="signature-pad--footer">
                                                    <div class="signature-pad--actions">
                                                        <div class="row">
                                                            <div class="col mt-5">
                                                                <button type="button" class="btn btn-secondary" data-action="clear">Limpiar Firma</button>
                                                                <button type="button" class="btn btn-success" data-action="save-png">Guardar como PNG</button>
                                                            </div>
                                                            <div class="col-8 mt-2">
                                                                <label class="form-label" for="customFile">Adjuntar firma</label>
                                                                <input type="file" class="form-control" name="ingreso_firma" multiple accept="jpg,.png">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="reception_new" class="btn btn-primary" value="enlisted_new"> Registrar </button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
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
        // Función para mostrar el modal de ingreso con alistamiento
        function GetUserDetails() {
            $("#reception_modal").modal("show");
        }
        // Fin de la función

        // Función para mostrar el modal de ingreso sin alistamiento
        function GetUserDetails2() {
            $("#reception2_modal").modal("show");
        }
        // Fin de la función

        // Example starter JavaScript for disabling form submissions if there are invalid fields
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

        // script firma 

        const wrapper = document.getElementById("signature-pad");
        const clearButton = wrapper.querySelector("[data-action=clear]");
        const savePNGButton = wrapper.querySelector("[data-action=save-png]");
        const canvas = wrapper.querySelector("canvas");
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            signaturePad.clear();
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        function download(dataURL, filename) {
            const blob = dataURLToBlob(dataURL);
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement("a");
            a.style = "display: none";
            a.href = url;
            a.download = filename;

            document.body.appendChild(a);
            a.click();

            window.URL.revokeObjectURL(url);
        }

        function dataURLToBlob(dataURL) {
            const parts = dataURL.split(';base64,');
            const contentType = parts[0].split(":")[1];
            const raw = window.atob(parts[1]);
            const rawLength = raw.length;
            const uInt8Array = new Uint8Array(rawLength);

            for (let i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }

            return new Blob([uInt8Array], {
                type: contentType
            });
        }

        clearButton.addEventListener("click", () => {
            signaturePad.clear();
        });

        savePNGButton.addEventListener("click", () => {
            if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
            } else {
                const dataURL = signaturePad.toDataURL();
                download(dataURL, "signature.png");
            }
        });

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
    </script>

</body>

</html>