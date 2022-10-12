<!DOCTYPE html>
<html lang="en">

<?php
// Se incluye loss estilos
include_once "../assets/in/Head.php";

// Se controla los permisos de ingreso a traves de la sesión
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Variables Globales para la paginación 
$url = "enlisted-list.php";
$tabla = "alistado";

// Se incluyen validaciónes importantes para las consultas
include_once '../assets/in/returns.php';

// Se incluye la clase de enlistado
include_once '../../Controller/enlisted_class.php';

// Instanciamos una nueva clase de enlistado
$enlistedClass = new enlistedClass(); ?>

<body class=" sb-nav-fixed">
    <?php
    // Validacion e la cantidad alistada de la vista de actualizar
    if (!isset($_GET['actu_prod']) && !empty($_POST['cant_alistup'])) {

        $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_UP']);

        if ($_POST['cant_alistup'] <= 0) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado!",
                    Texto: "La cantidad seleccionada no es valida!",
                    Tipo: "error"
                };
                alertas_ajax(alerta);
            </script>
        <?php
        } else {
            $_SESSION["productosUp"][$idProd]['CantAlis'] = $_POST['cant_alistup'];
        }
    }

    // Validacion e la cantidad alistada de la vista de crear
    if (!isset($_GET['new_prod']) && !empty($_POST['CantAlistNew'])) {

        $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_New']);

        if ($_POST['CantAlistNew'] <= 0) { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado!",
                    Texto: "La cantidad seleccionada no es valida!",
                    Tipo: "error"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        } else {
            $_SESSION["productosIg"][$idProd]['CantAlis'] = $_POST['CantAlistNew'];
        }
    }

    // Validación para actualizar un producto alistado
    if (!empty($_GET['idUp']) or !empty($_GET['idCli'])) {

        unset($_SESSION['alistadoUpdate']);
        $_SESSION['alistadoUpdate'] = $_GET['idUp'];
        $_SESSION['idCliUp'] = $_GET['idCli'];

        $enlistedClass->searchEnlisted($_SESSION['alistadoUpdate']);
        $enlist = encrypt_decrypt('decrypt', $_SESSION['alistadoUpdate']);
        $alistado = consultaSimple('alistado', $enlist);
        $up = encrypt_decrypt('encrypt', "up");

        $dataUs = $enlistedClass->tableDetails("productoUser", $_SESSION['idCliUp'], $campo_1, $bus_1);

        $_SESSION['url_global_ingreso'] = "enlisted-new.php?upS=" . $up . "&idUp=" . $_SESSION['alistadoUpdate'] . '&idCli=' . $_SESSION['idCliUp'];
        $urlnew = "enlisted-new.php?upS=" . $up . "&idUp=" . $_SESSION['alistadoUpdate'];
    } else {
        $urlnew = "enlisted-new.php";
        if (!empty($_SESSION['idCliUp'])) {
            // $enlist = encrypt_decrypt('decrypt', $_SESSION['alistadoUpdate']);

            // $alistado = consultaSimple('alistado', $enlist);
            $dataUs = $enlistedClass->tableDetails("productoUser", $_SESSION['idCliUp'], $campo_1, $bus_1);
        }
    }
    // Vista de Seleccion de usuario y producto
    if (!empty($_POST['id_key'])) {

        $user = $_POST['id_key'];

        $_SESSION['id_client_pro'] = $user;

        $dataUs = $enlistedClass->tableDetails("productoUser", $user, $campo_1, $bus_1);
        // Consulta de las bodegas asociadas cliente
        $cons_city = consultaBodegaCliente($user);

        $client_data = consultaSimple('clienteAlist', $_POST['id_key']);
    } else if (!empty($_SESSION['id_client_pro'])) {

        $cli_ing = consultaSimple('clienteAlist', $_SESSION['id_client_pro']);

        $dataUs = $enlistedClass->tableDetails("productoUser", $cli_ing[0]['idCliente'], $campo_1, $bus_1);

        // Consulta de las bodegas asociadas cliente
        $cons_city = consultaBodegaCliente($_SESSION['id_client_pro']);

        $client_data = consultaSimple('clienteAlist', $_SESSION['id_client_pro']);
    } else {
        $_SESSION['id_client_pro'] = '';
    }

    /* Formulario para actualizar los productos alistados */
    if (!empty($_POST['enlisted_up'])) {
        $tipo = "Entrada";
        $CantExistUp = limpiar_cadena($_POST['CantExistUp']);
        $fechaEntrada = limpiar_cadena($_POST['fechaEntrada_up']);
        $fechaDespacho = "0000-00-00";
        $cliente = limpiar_cadena($_POST['cliente_up']);
        $nombre = limpiar_cadena($_POST['nombrePersona_up']);
        $cedula = limpiar_cadena($_POST['cedulaPersona_up']);
        $placa = limpiar_cadena($_POST['placaPersona_up']);


        if (isset($_SESSION['productosUp'])) {
            $producto = $_SESSION['productosUp'];
        } else {
            $producto = "";
        }

        if ($fechaEntrada >= 1 && $cliente >= 1 && $producto >= 1 && $nombre != "" && $cedula != "" && $placa != "") {
            $bool = true;

            foreach ($producto as $key) {
                if ($key['CantAlis'] == 0) { ?>

                    <script>
                        let alerta = {
                            Alerta: "error",
                            Titulo: "Ocurrio un error...!",
                            Texto: "Hay algunos productos con la cantidad igual a cero, debe ingresar una cantidad valida, o seleccionar un producto!",
                            Tipo: "error"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                    $bool = false;
                }
            }
            if ($bool == false) { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Titulo: "Ocurrio un error...!",
                        Texto: "Hay algunos productos con la cantidad igual a cero, debe ingresar una cantidad valida, o seleccionar un producto!",
                        Tipo: "error"
                    };
                    alertas_ajax(alerta);
                </script>
                <?php
            } else {
                $uid = $enlistedClass->updateEnlisted($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $CantExistUp, $nombre, $cedula, $placa);
                if ($uid) { ?>
                    <script>
                        let alerta = {
                            Alerta: "registro",
                            Icono: '',
                            Titulo: "Registrando Alistamiento...",
                            Texto: "El alistamiento se está registrando correctamente",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/enlisted-list.php"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                } else { ?>
                    <script>
                        let alerta = {
                            Alerta: "error",
                            Titulo: "Ocurrio algo inesperado!",
                            Texto: "Hay un problema en el registro, intente nuevamente!",
                            Tipo: "error"
                        };
                        alertas_ajax(alerta);
                    </script>
            <?php
                }
            }
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Titulo: "Ocurrio algo inesperado!",
                    Texto: "Hay campos que son obligatorios!",
                    Tipo: "error"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    /* Formulario para registrar los nuevos productos */
    if (!empty($_POST['enlisted_new'])) {

        $tipo = "Entrada";
        $fechaEntrada = limpiar_cadena($_POST['fechaEntrada_new']);
        $fechaDespacho = "";
        $cliente = limpiar_cadena($_POST['cliente_new']);
        $nombre = limpiar_cadena($_POST['nombrePersona_new']);
        $bodega = limpiar_cadena($_SESSION['bodega']);
        $cedula = limpiar_cadena($_POST['cedulaPersona_new']);
        $placa = limpiar_cadena($_POST['placaPersona_new']);


        if (isset($_SESSION['productosIg'])) {
            $producto = $_SESSION['productosIg'];
        } else {
            $producto = "";
        }

        // Validación de datos y muestra de alertas EN-1
        if ($bodega != '') {

            if ($fechaEntrada != "" && $cliente != "" && $producto > 0) {

                $uid = $enlistedClass->EnlistedRegistration($tipo, $fechaEntrada, $fechaDespacho, $cliente, $producto, $nombre, $cedula, $bodega, $placa);

                if ($uid) { ?>
                    <script>
                        let alerta = {
                            Alerta: "registro",
                            Icono: '',
                            Titulo: "Registrando Alistamiento...",
                            Texto: "El alistamiento se está registrando correctamente",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/enlisted-list.php"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                } else { ?>
                    <script>
                        let alerta = {
                            Alerta: "error",
                            Icono: 'error',
                            Titulo: "Ocurrio algo inesperado!",
                            Texto: "Debe completar los campos que son obligatorios",
                            Tipo: "error"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                }
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Titulo: "Ocurrio algo inesperado!",
                        Texto: "Debe agregar al menos un producto al alistamiento!",
                        Tipo: "error"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Titulo: "Ocurrio algo inesperado!",
                    Texto: "Debe seleccionar una bodega",
                    Tipo: "error"
                };
                alertas_ajax(alerta);
            </script>
    <?php }
    } ?>

    <!-- Inicio del contenido -->

    <?php include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">
        <!-- Menú de navegación -->
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>

        <div id="layoutSidenav_content">
            <!-- Contenedor principal -->
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><?php echo $resultado = $var == 'update' ? 'Editar' : 'Nuevo' ?> Pre-Ingreso </h1>

                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="enlisted-list.php">Pre-Ingresos</a></li>
                        <?php if ($_SESSION['tp_estado'] != 'update') { ?>
                            <li class="breadcrumb-item"><a href="client-search.php">Escoger Cliente</a></li>
                        <?php } ?>
                        <li class="breadcrumb-item active"><?php echo $resultado =  $var == 'update' ? 'Editar' : 'Nuevo' ?> Pre-Ingreso</li>
                    </ol>

                    <!-- Inicio del formulario -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>

                        <!-- Inicio de tablas -->
                        <div class="card-body">
                            <!-- Tablas de la opción Update -->
                            <?php if ($_SESSION['tp_estado'] == 'update') { ?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <a onclick="GetUserDetailsUp()" style="width: 100%;" class="btn btn-success mb-3">Editar Productos </a>
                                    </div>
                                    <div class="col"></div>
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
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($_SESSION["productosUp"])) {

                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["productosUp"] as $row) {

                                                            if ($row['CantAlis'] > 0) {

                                                                $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                                $cantidadT = $cantidadT + $row['CantAlis']; ?>
                                                                <tr>
                                                                    <td>#</td>
                                                                    <td><?php echo $row['Codigo'] ?></td>
                                                                    <td><?php echo $row['Nombre'] ?></td>
                                                                    <td><?php echo $row['Peso']; ?> Kg</td>
                                                                    <td> <?php echo $row['CantExis']; ?></td>
                                                                    <form action="#" method="post">

                                                                        <td>
                                                                            <input class="form-control" type="number" name="cant_alistup" value="<?php echo $row['CantAlis']; ?>">
                                                                        </td>
                                                                        <td><?php echo ($row['Peso'] * $row['CantAlis']); ?> Kg </td>
                                                                        <td>
                                                                            <input type="hidden" name="Cant_Alist_UP" value="<?php echo encrypt_decrypt('encrypt', $row['id']); ?>">

                                                                            <button class="btn btn-success" type="submit" name="actu_prod" value="actu_prod"><i class="fa-solid fa-floppy-disk"></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <a href="../../Ajax/ajax_enlisted.php?del=deleteProductUp&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>&cantAlis=<?php echo encrypt_decrypt('encrypt', $row['CantAlis']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>
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
                                                    } else { ?>
                                                        <tr class='text-center'>
                                                            <td colspan='12'>No hay registros en el sistema</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($_SESSION['productosUp'])) { ?>
                                    <div class="row mb-4">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_enlisted.php?del=emptyUp">Vaciar Productos</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <form action='' method="POST" autocomplete="off" name="enlisted_new" class="needs-validation" novalidate>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label class="form-label">Fecha Entrada</label>
                                            <input type="date" class="form-control" name="fechaEntrada_up" min="<?php echo date('Y-m-d') ?>" value="<?php echo date("Y-m-d"); ?>" required>
                                        </div>

                                        <div class="col">
                                            <?php $cli_ing = consultaSimple('clienteAlist', $_SESSION['idCliUp']); ?>

                                            <div class="col">
                                                <label class="form-label">Cliente</label>
                                                <input type="hidden" name="cliente_up" value="<?php echo $cli_ing[0]['idCliente']; ?>">
                                                <input type="text" class="form-control" id="" value="<?php echo  $cli_ing[0]['cliente_nombre'] . ' ' . $cli_ing[0]['cliente_apellido'] ?>" required disabled>
                                            </div>
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
                                    <br>
                                    <div class="row justify-content-center align-items-center mb-1">
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
                                        <a onclick="GetUserDetails()" style="width: 100%;" class="btn btn-success">Agregar Productos </a>
                                    </div>
                                    <div class="col-sm-2">
                                        <form action="client-search.php" method="post">
                                            <input type="text" name="url" value="enlisted" hidden>
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
                                                        <th scope="col">Cantidad existente</th>
                                                        <th scope="col">Cantidad a ingresar</th>
                                                        <th scope="col">Peso Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($_SESSION["productosIg"])) {
                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["productosIg"] as $row) {
                                                            $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                            $cantidadT = $cantidadT + $row['CantAlis']; ?>
                                                            <tr>
                                                                <td>#</td>
                                                                <td><?php echo $row['Codigo'] ?></td>
                                                                <td><?php echo $row['Nombre'] ?></td>
                                                                <td><?php echo $row['Peso']; ?> Kg</td>
                                                                <td><?php echo $row['CantExis']; ?></td>
                                                                <form action="#" method="post">

                                                                    <td><input class="form-control" type="number" name="CantAlistNew" value="<?php echo $row['CantAlis']; ?>"></td>
                                                                    <td><?php echo ($row['Peso'] * $row['CantAlis']); ?> Kg</td>

                                                                    <td>
                                                                        <input type="hidden" name="Cant_Alist_New" value="<?php echo encrypt_decrypt('encrypt', $row['id']); ?>">

                                                                        <button class="btn btn-success" type="submit" name="new_prod" value="new_prod">
                                                                            <i class="fa-solid fa-floppy-disk"></i>
                                                                        </button>
                                                                    </td>
                                                                    <td>
                                                                        <a href="../../Ajax/ajax_enlisted.php?del=deleteProduct&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>" class="btn btn-danger btn_table_del bi bi-trash3"></a>
                                                                    </td>
                                                                </form>
                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        } ?>
                                                        <tr>
                                                            <td colspan="5"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?> Kg</td>
                                                        </tr>
                                                    <?php
                                                    } else { ?>

                                                        <tr class='text-center'>
                                                            <td colspan='12'>No hay registros en el sistema</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <?php if (!empty($_SESSION['productosIg'])) { ?>
                                        <div class="row mb-5">
                                            <div class="col">
                                                <div class="d-flex justify-content-center">
                                                    <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_enlisted.php?del=empty">Vaciar Productos</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <form action='' method="POST" autocomplete="off" name="enlisted_new" class="needs-validation" novalidate>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label class="form-label">Fecha Entrada</label>
                                                <input type="date" class="form-control" name="fechaEntrada_new" value="<?php echo date("Y-m-d"); ?>" required>
                                            </div>
                                            <div class="col">
                                                <label class="form-label">Cliente</label>
                                                <input type="hidden" name="cliente_new" value="<?php echo $idCliente = !empty($_SESSION['id_client_pro']) ? $_SESSION['id_client_pro'] : 0; ?>">
                                                <input type="text" class="form-control" id="" value="<?php echo $client_data[0]['cliente_nombre'] . ' ' . $client_data[0]['cliente_apellido'] ?>" required disabled>
                                            </div>

                                        </div>
                                        <div class="row mb-3">

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
                                        <br>
                                        <div class="row justify-content-center align-items-center mb-1">
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

            <!-- Se incluye los modals para mostrar información -->
            <?php include_once '../assets/in/modals.php'; ?>
        </div>
    </div>

    <!-- Script de funcionalidades -->
    <script>
        // Modal para ver los productos en relación al cliente
        function GetUserDetails() {
            $("#producto_alistado_modal").modal("show");
        }

        // Función para mostrar el Modal para Actualizar los alistamientos de ingreso
        function GetUserDetailsUp() {
            $("#update_alistado_modal").modal("show");
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