<!DOCTYPE html>
<html lang="es">
<?php

// Variables Globales unicas x página
$url = "cellar-list.php";
$tabla = "bodega";

// Se incluyen los estilos
include_once "../assets/in/Head.php";

// Se incluye la validación del usuario logueado
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Total de la páginación x vista
include_once '../assets/in/returns.php';

// Se incluye la calse de cliente
include_once '../../Controller/cellar_class.php';

// Instanciamos una nueva clase de cliente
$clientClass = new cellarClass();

// Se declaran consultas para los select de bodega, ciudad
$cons_city = consultaSimpleAsc('ciudad', '');

// Se declaran consultas para los select de bodega
$constBg = consultaSimpleAsc('bodega', ''); ?>

<body class="sb-nav-fixed">
    <!-- Inicio de condicionales de los formularios -->
    <?php
    /* Formulario para registrar las bodegas por cliente */
    if (!empty($_POST['Newclientcell'])) {

        $tipo = "Cliente por bodega";

        $idCliente = limpiar_cadena($_SESSION['idClienteDB']);
        $fechaRegistro = date('Y-m-d');
        $bodegaCliente = limpiar_cadena($_POST['bodegaCliente']);

        if ($bodegaCliente != "Seleccione") {

            $search = $clientClass->searchClientCr($idCliente, $bodegaCliente);

            if ($search == 0) {

                if ($idCliente != "" && $fechaRegistro != "" && $bodegaCliente != "") {

                    $uid = $clientClass->clientCellarRegistration($idCliente, $fechaRegistro, $bodegaCliente);

                    if ($uid == true) { ?>
                        <script>
                            let alerta = {
                                Alerta: "simple",
                                Icono: 'success',
                                Titulo: "Bodega asociada",
                                Texto: "Agregada correctamente, cargando data!!",
                                Tipo: "success",
                                href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo  $_SESSION['idClienteNew'];  ?>"
                            };
                            alertas_ajax(alerta);
                        </script>
                    <?php
                    } else { ?>
                        <script>
                            let alerta = {
                                Alerta: "simple",
                                Icono: 'error',
                                Titulo: "Ocurrio algo inesperado",
                                Texto: "Error en el registro, intente nuevamente!",
                                Tipo: "",
                                href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $_SESSION['idClienteNew']; ?>"
                            };
                            alertas_ajax(alerta);
                        </script>
                    <?php
                    }
                } else { ?>
                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'error',
                            Titulo: "Ocurrio algo inesperado...",
                            Texto: "Hay campos que son obligatorios, intente nuevamente!",
                            Tipo: "",
                            href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $_SESSION['idClienteNew']; ?>"
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
                        Texto: "La bodega ya se encuentra registrada",
                        Tipo: "",
                        href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $_SESSION['idClienteNew']; ?>"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "Debe seleccionar una bodega",
                    Tipo: "",
                    href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $_SESSION['idClienteNew'] ?>"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    /* Validación para actualizar una nueva bodega */
    if (!empty($_POST['cellar-update'])) {

        $nombre_up = limpiar_cadena($_POST['nombre_up']);
        $ciudad_up = limpiar_cadena($_POST['ciudad_up']);
        $estado_up = limpiar_cadena($_POST['estado_up']);
        $observacion_up = limpiar_cadena($_POST['observacion_up']);

        if ($nombre_up != "" && $ciudad_up != "" && $estado_up != "") {

            $uid = $clientClass->updateClient($nombre_up, $ciudad_up, $estado_up, $observacion_up);

            if ($uid) {
                $var =  $_SESSION['idClienteNew']; ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Actualizando bodega",
                        Texto: "La bodega se está actualizando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/cellar-list.php"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "Ocurrio algo inesperado",
                        Texto: "No se pudo acualizar la bodefa, contacte con el admminstrador del sistema",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/cellar-new.php?upS=<?php echo $var ?>"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php }
        } else {
            ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "Debe completar los campos que son obligatorios",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $var ?>"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    /* Validación para crear un nuevo cliente */
    if (!empty($_POST['celler_new'])) {

        $nombre_new = limpiar_cadena($_POST['nombre_new']);
        $ciudad_new = limpiar_cadena($_POST['ciudad_new']);
        $estado_new = limpiar_cadena($_POST['estado_new']);
        $observacion_new = limpiar_cadena($_POST['observacion_new']);

        if ($ciudad_new != "Seleccione") {


            if ($nombre_new != ""  && $ciudad_new != "" && $estado_new != "") {

                $uid = $clientClass->clientRegistration($nombre_new, $ciudad_new, $estado_new, $observacion_new);

                if ($uid) {
                    $encrypt = encrypt_decrypt('encrypt', 1); ?>
                    <script>
                        let alerta = {
                            Alerta: "registro",
                            Icono: '',
                            Titulo: "Registrando bodega",
                            Texto: "La bodega se está registrando correctamente",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/cellar-list.php"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                } else { ?>
                    <script>
                        let alerta = {
                            Alerta: "error",
                            Icono: 'error',
                            Titulo: "Ocurrio algo inesperado",
                            Texto: "No se pudo registrar la bodega, contacte con el admminstrador del sistema",
                            Tipo: "error"
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php
                }
            } else {
                ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "Ocurrio algo inesperado",
                        Texto: "Debe completar los campos que son obligatorios",
                        Tipo: "error"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else {
            ?> <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado...",
                    Texto: "Debe seleccionar un cliente, tipo de identificación y una ciudad ",
                    Tipo: ""
                };
                alertas_ajax(alerta);
            </script>
    <?php
        }
    } ?>

    <!-- Se incluye el menu de navegación superior -->
    <?php include_once "../assets/in/menu_profile.php"; ?>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- Se incluye el menu de navegación superior -->
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="mb-4">
                        <div class="row pt-4">
                            <div class="col-sm-6">
                                <h1 class="mt-4"><?php echo $resultado = $var == 'update' ? 'Editar' : 'Nuevo' ?> bodega</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="client-list.php">Bodega</a></li>
                                    <li class="breadcrumb-item active"><?php echo $resultado =  $var == 'update' ? 'Editar' : 'Nuevo' ?> Bodega</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data de bodegas
                        </div>
                        <div class="card-body">
                            <?php if (!empty($_SESSION['idClienteNew'])) { ?>

                                <!-- Inicio del formulario para actualizar -->
                                <form action='' method="POST" autocomplete="off" name="client-update" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col">
                                            <label for="nombre_up" class="form-label">Nombre de la bodega: </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="nombre_up" name="nombre_up" value="<?php echo $detalis[0]['bodega_nombre'] ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="ciudad_up" class="form-label">Ciudad</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="ciudad_up" value="<?php echo $detalis[0]['cliente_tpId'] ?>" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($cons_city as $row) {
                                                    if ($row['idCiudad'] == $detalis[0]['bodega_ciudad']) { ?>
                                                        <option selected value="<?php echo $detalis[0]['bodega_ciudad']; ?>"><?php echo $row['ciudad_nombre']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $row['idCiudad']; ?>"><?php echo $row['ciudad_nombre']; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label for="estado_up" class="form-label">Estado</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="estado_up" value="<?php echo $detalis[0]['cliente_tpId'] ?>" required>
                                                <option value="">Seleccione...</option>
                                                <option value="activo" <?php echo $resultado = $detalis[0]['bodega_estado'] == "activo" ? "selected" : ''; ?>>Activo</option>
                                                <option value="inactivo" <?php echo $resultado = $detalis[0]['bodega_estado'] == "inactivo" ? "selected" : ''; ?>>Inactivo</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Observacion</label>
                                            <input type="text" class="form-control" id="observacion_up" value="<?php echo $detalis[0]['bodega_observacion'] ?>" name="observacion_up">
                                        </div>

                                    </div>
                                    <br>
                                    <button type="submit" name="cellar-update" class="btn btn-primary" value="cellar_up"> Actualizar </button>
                                </form>
                                <br>
                            <?php } else { ?>
                                <!-- Inicio de formulario para crear una nueva bodega -->
                                <form action='' method="POST" autocomplete="off" name="celler_new" class="needs-validation" novalidate>
                                    <div class="row">

                                        <div class="col">
                                            <label for="nombre_new" class="form-label">Nombre de la bodega: </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="nombre_new" name="nombre_new" required>
                                        </div>
                                        <div class="col">
                                            <label for="ciudad_new" class="form-label">Ciudad</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="ciudad_new" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($cons_city as $row) { ?>
                                                    <option value="<?php echo $row['idCiudad']; ?>"><?php echo $row['ciudad_nombre']; ?></option>
                                                <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label for="estado_new" class="form-label">Estado</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="estado_new" required>
                                                <option value="">Seleccione...</option>
                                                <option value="activo">Activo</option>
                                                <option value="inactivo">Inactivo</option>
                                            </select>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Observacion</label>
                                            <input type="text" class="form-control" id="observacion_new" name="observacion_new" required>
                                        </div>

                                    </div>

                                    <div class="row card-body">
                                        <button type="submit" name="celler_new" class="btn btn-primary" value="cellar_new"> Registrar </button>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include_once '../assets/in/modals.php'; ?>
    <!-- Scrypt de funcionalidades -->
    <script>
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