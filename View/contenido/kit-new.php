<!DOCTYPE html>
<html lang="en">

<body class=" sb-nav-fixed">

    <?php
    // Se incluye loss estilos
    include_once "../assets/in/Head.php";

    // Se controla los permisos de ingreso a traves de la sesión
    include_once '../../Controller/session_log.php';

    // Se incluye las conexiones a la base
    include '../../Model/config.php';

    // Variables Globales  para la paginación 
    $url = "kit-list.php";
    $tabla = "kit";
    $count = 1;

    // Se incluyen validaciónes importantes para las consultas
    include_once '../assets/in/returns.php';

    // Se incluye la clase de enlistado
    include_once '../../Controller/kit_class.php';

    // Instanciamos una nueva clase de enlistado
    $kitClass = new kitClass();


    ?>

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
            $_SESSION["kitUp"][$idProd]['CantAlis'] = $_POST['cant_alistup'];
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
            $_SESSION["kitNew"][$idProd]['CantAlis'] = $_POST['CantAlistNew'];
        }
    }

    // Validación para actualizar un producto alistado
    if (!empty($_GET['idUp']) and !empty($_GET['idCli'])) {
        unset($_SESSION['kitUpdate']);
        $_SESSION['kitUpdate'] = $_GET['idUp'];
        $_SESSION['idCliUp'] = $_GET['idCli'];
        $kitClass->searchKit($_SESSION['kitUpdate']);
        $KIT = consultaSimple("kit", encrypt_decrypt('decrypt', $_SESSION['kitUpdate']));
        $up = encrypt_decrypt('encrypt', "up");
        $dataUs = $kitClass->tableDetails("productoUser", $_SESSION['idCliUp'], $start, $Tpages, '');
        $_SESSION['url_global_ingreso'] = "kit-new.php?upS=" . $up . "&idUp=" . $_SESSION['kitUpdate'] . '&idCli=' . $_SESSION['idCliUp'];
        $urlnew = "kit-new.php?upS=" . $up . "&idUp=" . $_SESSION['kitUpdate'];
    } else {
        $urlnew = "kit-new.php";
        if (!empty($_SESSION['idCliUp'])) {
            // $KIT = consultaSimple("kit", encrypt_decrypt('decrypt', $_SESSION['kitUpdate']));
            $dataUs = $kitClass->tableDetails("productoUser", $_SESSION['idCliUp'], $start, $Tpages, '');
        }
    }

    // Vista de Seleccion de usuario y producto
    if (!empty($_POST['id_key'])) {

        $user = $_POST['id_key'];

        $_SESSION['id_client_pro'] = $user;

        $dataUs = $kitClass->tableDetails("productoUser", $user, $start, $Tpages, '');
        // Consulta de las bodegas asociadas cliente
        $cons_city = consultaBodegaCliente($user);

        $client_data = consultaSimple('clienteAlist', $_POST['id_key']);
    } else if (!empty($_SESSION['id_client_pro'])) {

        $cli_ing = consultaSimple('clienteAlist', $_SESSION['id_client_pro']);

        $dataUs = $kitClass->tableDetails("productoUser", $cli_ing[0]['idCliente'], $start, $Tpages, '');

        // Consulta de las bodegas asociadas cliente
        $cons_city = consultaBodegaCliente($_SESSION['id_client_pro']);

        $client_data = consultaSimple('clienteAlist', $_SESSION['id_client_pro']);
    } else {
        $_SESSION['id_client_pro'] = '';
    }

    /* Formulario para actualizar los productos alistados */
    if (!empty($_POST['kit_up'])) {
        $cliente = limpiar_cadena($_POST['cliente_up']);
        $nombre = limpiar_cadena($_POST['nombre_up']);
        $pesoT = limpiar_cadena($_POST['pesoT_up']);
        if (isset($_SESSION['kitUp'])) {
            $producto = $_SESSION['kitUp'];
        } else {
            $producto = "";
        }
        if ($cliente != "" && $nombre != "" && $producto > 0) {
            $uid = $kitClass->updateKit($cliente, $nombre, $producto, $pesoT);
            if ($uid) {
                $encrypt = encrypt_decrypt('encrypt', 1);
                $url = BASE_URL . 'View/contenido/kit-list.php?up=' . $encrypt;
                header("Location: $url"); // Page redirecting to home.php 
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
    if (!empty($_POST['kit_new'])) {

        $cliente = limpiar_cadena($_POST['cliente_new']);
        $nombre = limpiar_cadena($_POST['nombre_new']);
        $pesoT = limpiar_cadena($_POST['pesoT_new']);

        if (isset($_SESSION['kitNew'])) {
            $producto = $_SESSION['kitNew'];
        } else {
            $producto = "";
        }

        // Validación de datos y muestra de alertas EN-1


        if ($cliente != "" && $producto > 0 && $nombre != "") {

            $uid = $kitClass->KitRegistration($cliente, $nombre, $producto, $pesoT);

            if ($uid) { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Registrando Kit...",
                        Texto: "e",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/kit-list.php"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "ERROR!",
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
                    Titulo: "ERROR!",
                    Texto: "Debe agregar al menos un producto al alistamiento!",
                    Tipo: "error"
                };
                alertas_ajax(alerta);
            </script>
    <?php
        }
    }
    ?>

    <!-- Inicio del contenido -->

    <?php include_once "../assets/in/menu_profile.php"; ?>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>

        <div id="layoutSidenav_content">
            <!-- Contenedor principal -->
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><?php echo $resultado = $var == 'update' ? 'Editar' : 'Nuevo' ?> Kit </h1>

                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="kit-list.php">Kits</a></li>
                        <?php if ($_SESSION['tp_estado'] != 'update') { ?>
                            <li class="breadcrumb-item"><a href="client-search.php">Escoger Cliente</a></li>
                        <?php } ?>
                        <li class="breadcrumb-item active"><?php echo $resultado =  $var == 'update' ? 'Editar' : 'Nuevo' ?> Kit</li>
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
                                <a onclick="GetUserDetailsUp()" class="btn btn-success mb-3">Editar Productos </a>

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
                                                    if (!empty($_SESSION["kitUp"])) {

                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["kitUp"] as $row) {

                                                            if ($row['CantAlis'] > 0) {

                                                                $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                                $cantidadT = $cantidadT + $row['CantAlis'];
                                                    ?>
                                                                <tr>
                                                                    <td>#</td>
                                                                    <td><?php echo $row['Codigo'] ?></td>
                                                                    <td><?php echo $row['Nombre'] ?></td>
                                                                    <td><?php echo $row['Peso']; ?> Kg </td>
                                                                    <td> <?php echo $row['CantExis']; ?></td>
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
                                                                            <a href="../../Ajax/ajax_kit.php?del=deleteProductUp&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>&cantAlis=<?php echo encrypt_decrypt('encrypt', $row['CantAlis']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>
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
                                                            <td class="table-active"><?php echo $pesoT ?> Kg </td>
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
                                <br>
                                <form action='' method="POST" autocomplete="off" name="kit_up" class="needs-validation" novalidate>
                                    <div class="row mb-3">
                                    <input type="hidden" name="pesoT_up" value="<?php echo $pesoT ?>">

                                        <div class="col">
                                            <label class="form-label">Nombre Kit</label>
                                            <input type="text" name="nombre_up" class="form-control" value="<?php echo $resultado = !empty($_POST['nombre_up']) ? $_POST['nombre_new'] : $KIT->kit_nombre; ?>">

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

                                    <br>
                                    <button type="submit" name="kit_up" class="btn btn-primary" value="kit_up"> Registrar </button>
                                    <input type="hidden" name="CantExistUp" value="<?php echo $row['id']; ?>">

                                    <?php if (!empty($_SESSION['kitUp'])) { ?>
                                        <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_kit.php?del=emptyUp">Vaciar Productos</a>
                                    <?php } ?>

                                </form>
                                <br>

                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <a onclick="GetUserDetails()" class="btn btn-success">Agregar Productos </a>

                                    </div>
                                    <div class="col-sm-3">
                                        <form action="client-search.php" method="post">
                                            <input type="text" name="url" value="kit" hidden>
                                            <button type="submit" class="btn btn-success">Cambiar Cliente </button>
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
                                                    if (!empty($_SESSION["kitNew"])) {
                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["kitNew"] as $row) {
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
                                                                        <a href="../../Ajax/ajax_kit.php?del=deleteProduct&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>" class="btn btn-danger btn_table_del bi bi-trash3"></a>
                                                                    </td>
                                                                </form>
                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        } ?>
                                                        <tr>
                                                            <td colspan="5"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?> Kg </td>
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

                                    <form action='' method="POST" autocomplete="off" name="kit_new" class="needs-validation" novalidate>
                                        <div class="row mb-3">
                                            <input type="hidden" name="pesoT_new" value="<?php echo $pesoT ?>">

                                            <div class="col">
                                                <label class="form-label">Nombre Kit</label>
                                                <input type="text" name="nombre_new" class="form-control" value="<?php echo $resultado = !empty($_POST['nombre_new']) ? $_POST['nombre_new'] : ""; ?>">

                                            </div>

                                            <div class="col">
                                                <label class="form-label">Cliente</label>
                                                <input type="hidden" name="cliente_new" value="<?php echo $idCliente = !empty($_SESSION['id_client_pro']) ? $_SESSION['id_client_pro'] : 0; ?>">
                                                <input type="text" class="form-control" id="" value="<?php echo $client_data[0]['cliente_nombre'] . ' ' . $client_data[0]['cliente_apellido'] ?>" required disabled>
                                            </div>



                                        </div>

                                        <br>
                                        <button type="submit" name="kit_new" class="btn btn-primary" value="kit_new"> Registrar </button>
                                        <?php if (!empty($_SESSION['kitNew'])) { ?>
                                            <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_kit.php?del=empty">Vaciar Productos</a>
                                        <?php } ?>

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
            $("#kit_modal").modal("show");
        }

        // Función para mostrar el Modal para Actualizar los alistamientos de ingreso
        function GetUserDetailsUp() {
            $("#update_kit_modal").modal("show");
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
    </script>

</body>

</html>