<!DOCTYPE html>
<html lang="en">
<?php

// Variables Globales unicas x página
$url = "usuario-list.php";
$tabla = "usuario";

// Se incluyen los estilos
include_once "../assets/in/Head.php";

// Se incluye la validación del usuario logueado
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Total de la páginación x vista
include_once '../assets/in/returns.php';

// Se incluye la calse de cliente
include_once '../../Controller/usuario_class.php';

// Instanciamos una nueva clase de cliente
$clientClass = new usuarioClass();

// Se declaran consultas para los select de bodega, ciudad
$cons_city = $clientClass->consultaSimpleAsc('ciudad', '');

// Se declaran consultas para los select de bodega
$constBg = $clientClass->consultaSimpleAsc('bodega', '');

// Se declaran consultas para los select del Rol
$constRol = $clientClass->consultaSimpleAsc('rol', ''); ?>

<body class="sb-nav-fixed">
    <!-- Inicio de condicionales de los formularios -->

    <?php
    /* Formulario para registrar las bodegas por cliente */
    if (!empty($_POST['Newclientcell'])) {

        $tipo = "Usuario por bodega";

        $idCliente = limpiar_cadena($_SESSION['idClienteDB']);
        $fechaRegistro = date('Y-m-d');
        $bodegaCliente = limpiar_cadena($_POST['bodegaCliente']);

        if ($bodegaCliente != "Seleccione") {

            $search = $clientClass->searchClientCr($idCliente, $bodegaCliente);

            if ($search == 0) {

                if ($idCliente >= 1 && $fechaRegistro >= 1 && $bodegaCliente >= 1) {

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

    /* Validación para actualizar un nuevo cliente */
    if (!empty($_POST['client-update'])) {

        $tpId = limpiar_cadena($_POST['tp-document_up']);
        $nDocument = limpiar_cadena($_POST['nDocument_up']);
        $fecha = limpiar_cadena($_POST['fecha_up']);
        $telefono = limpiar_cadena($_POST['telefono_up']);
        $nombre = limpiar_cadena($_POST['nombre_up']);
        $apellido = limpiar_cadena($_POST['apellido_up']);
        $correo = limpiar_cadena($_POST['correo_up']);
        $direccion = limpiar_cadena($_POST['direccion_up']);
        $bodega = limpiar_cadena($_POST['tpBodega_up']);
        $tpRol = limpiar_cadena($_POST['tpRol_up']);
        $password = limpiar_cadena($_POST['password']);
        $password1 = limpiar_cadena($_POST['password1']);



        if ($tpId != "" && $nDocument != "" && $fecha != ""  && $telefono != "" && $nombre != "" && $apellido != "" && $correo != "" && $bodega != "" &&  $tpRol != "" && $password != "" && $password1 != "") {

            $uid = $clientClass->updateUsuario($tpId, $nDocument, $fecha, $telefono, $nombre, $apellido, $correo, $direccion, $bodega, $tpRol, $password, $id);
            if ($uid) { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Actualizando usuario",
                        Texto: "El usuario se está actualizando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/usuario-list.php"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            } else {
            ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "Ocurrio algo inesperado",
                        Texto: "No se pudo acualizar el cliente, contacte con el admminstrador del sistema",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $var ?>"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php            }
        } else {
            ?>
            <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "Hay campos que son obligatorios!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $var ?>"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    /* Validación para crear un nuevo cliente */
    if (!empty($_POST['client_new'])) {

        $tpId_new = limpiar_cadena($_POST['tp-document_new']);
        $nDocument_new = limpiar_cadena($_POST['nDocument_new']);
        $fecha_new = limpiar_cadena($_POST['fecha_new']);
        $telefono_new = limpiar_cadena($_POST['telefono_new']);
        $nombre_new = limpiar_cadena($_POST['Nombre_new']);
        $apellido_new = limpiar_cadena($_POST['Apellido_new']);
        $correo_new = limpiar_cadena($_POST['correo_new']);
        $direccion_new = limpiar_cadena($_POST['direccion_new']);
        $tpBodega_up = limpiar_cadena($_POST['tpBodega_up']);
        $tpRol_up = limpiar_cadena($_POST['tpRol_up']);

        $user_exis = $clientClass->consultaCedula($nDocument_new);

        if ($user_exis == 0) {

            if ($tpBodega_up != "" and $tpId_new != "" and $tpRol_up != '') {


                if ($tpId_new != ''  && $nDocument_new != '' && $fecha_new != '' && $telefono_new != '' && $nombre_new != '' && $apellido_new != '' && $correo_new != '' && $direccion_new != '' && $tpBodega_up != '' && $tpRol_up != '') {

                    $uid = $clientClass->clientRegistration($tpId_new, $nDocument_new, $fecha_new, $telefono_new, $nombre_new, $apellido_new, $correo_new, $direccion_new, $tpBodega_up, $tpRol_up);

                    if ($uid) {
                        $encrypt = encrypt_decrypt('encrypt', 1); ?>
                        <script>
                            let alerta = {
                                Alerta: "registro",
                                Icono: '',
                                Titulo: "Registrando cliente",
                                Texto: "El cliente se está registrando correctamente",
                                Tipo: "success",
                                href: "<?php echo BASE_URL; ?>View/contenido/usuario-list.php"
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
                                Texto: "No se pudo registrar el cliente, contacte con el admminstrador del sistema",
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
        } else {
            ?> <script>
                let alerta = {
                    Alerta: "error",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado...",
                    Texto: "El numero de Documento ya se encuentra registrado",
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
                                <h1 class="mt-4"><?php echo $resultado = $var == 'update' ? 'Editar' : 'Nuevo' ?> usuario</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="usuario-list.php">Usuarios</a></li>
                                    <li class="breadcrumb-item active"><?php echo $resultado =  $var == 'update' ? 'Editar' : 'Nuevo' ?> usuario</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <div class="card-body">
                            <?php if (!empty($_SESSION['idClienteNew'])) { ?>

                                <!-- Inicio del formulario de actualizar -->
                                <form action='' method="POST" autocomplete="off" name="client-update" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col">
                                            <label for="Apellido" class="form-label">Tipo de identificación</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="tp-document_up" value="<?php echo $detalis->usuario_tDocument ?>" required>
                                                <option value="">Seleccione...</option>
                                                <option value="NIT" <?php echo $resultado = $detalis->usuario_tDocument == "NIT" ? "selected" : ''; ?>>NIT</option>
                                                <option value="Cédula de ciudadanía" <?php echo $resultado = $detalis->usuario_tDocument == "Cédula de ciudadanía" ? "selected" : ''; ?>>Cédula de ciudadanía</option>
                                                <option value="Cédula de extrangería" <?php echo $resultado = $detalis->usuario_tDocument == "Cédula de extrangería" ? "selected" : ''; ?>>Cédula de extranjeria</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="Apellido" class="form-label"># Identificación</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="nDocument_up" name="nDocument_up" value="<?php echo $detalis->usuario_documento ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="fecha_up" class="form-label">Fecha de Nacimiento</label><span style="color: red;">*</span>
                                            <input type="date" class="form-control" id="fecha_up" aria-describedby="DV" name="fecha_up" value="<?php echo $detalis->usuario_fecha ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="telefono_up" class="form-label">Telefono</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" id="telefono_up" aria-describedby="Telefono" name="telefono_up" value="<?php echo $detalis->usuario_telefono ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col">
                                            <label for="nombre_up" class="form-label">Nombre</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="nombre_up" aria-describedby="nombre" name="nombre_up" value="<?php echo $detalis->usuario_nombre ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="apellido_up" class="form-label">Apellido </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="apellido_up" aria-describedby="apellido" name="apellido_up" value="<?php echo $detalis->usuario_apellido ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label for="correo_up" class="form-label">Correo: </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="correo_up" aria-describedby="actEco" name="correo_up" value="<?php echo $detalis->usuario_correo ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Dirección: </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="direccion_up" aria-describedby="Direccion" name="direccion_up" value="<?php echo $detalis->usuario_direccion ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label for="tpBodega_up" class="form-label">Bodega</label><span style="color: red;">*</span>

                                            <select class="form-select" name="tpBodega_up" required>
                                                <?php foreach ($constBg as $key) {
                                                    // Se declaran consultas para los select de bodega -->
                                                    $constBgd = consultaSimpleAsc('bodega', $detalis->usuario_idBodega);

                                                    if ($key['idBodega'] == $detalis->usuario_idBodega) { ?>
                                                        <option value="<?php echo $key['idBodega'] ?>" selected><?php echo $constBgd[0]['bodega_nombre'] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $key['idBodega'] ?>"><?php echo $key['bodega_nombre'] ?></option>
                                                <?php }
                                                } ?>
                                            </select>


                                            <!-- <label for="tpBodega_up" class="form-label">Bodega</label><span style="color: red;">*</span>
                                            <select class="form-select" name="tpBodega_up" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($constBg as $key) { ?>
                                                    <option value="<?php echo $key['idBodega'] ?>"><?php echo $key['bodega_nombre'] ?></option>
                                                <?php } ?>
                                            </select> -->
                                        </div>
                                        <div class="col-6">
                                            <label for="tpRol_up" class="form-label">Tipo de Rol</label><span style="color: red;">*</span>
                                            <select class="form-select" name="tpRol_up" required>
                                                <option value="">Seleccione...</option>
                                                <option value="1" <?php echo $resultado = $detalis->usuario_idRol == 1 ? "selected" : ''; ?>>Administrador</option>
                                                <option value="2" <?php echo $resultado = $detalis->usuario_idRol == 2 ? "selected" : ''; ?>>Coordinador</option>
                                                <option value="3" <?php echo $resultado = $detalis->usuario_idRol == 3 ? "selected" : ''; ?>>Operativo</option>
                                                <option value="4" <?php echo $resultado = $detalis->usuario_idRol == 4 ? "selected" : ''; ?>>Invitado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="tpBodega_up" class="form-label">Contraseña</label><span style="color: red;">*</span>
                                            <input class="form-control" type="text" name="password" value="<?php echo encrypt_decrypt('decrypt', $detalis->usuario_password) ?>">

                                        </div>
                                        <div class="col-6">
                                            <label for="tpRol_up" class="form-label">Verificar contraseña</label><span style="color: red;">*</span>
                                            <input class="form-control" type="text" name="password1" value="<?php echo encrypt_decrypt('decrypt', $detalis->usuario_password) ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center align-items-center mb-1">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type=" submit" name="client-update" class="btn btn-primary" value="client_up"> Actualizar </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <br>

                            <?php } else { ?>
                                <!-- Inicio de formulario para crear un nuevo usuario -->
                                <form action='' method="POST" autocomplete="off" name="client_new" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col">
                                            <label for="tp-document_new" class="form-label">Tipo de identificación </label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="tp-document_new" required>
                                                <option value="">Seleccione...</option>
                                                <option value="NIT">NIT</option>
                                                <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                                <option value="Cédula de extrangería">Cédula de extranjeria</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="nDocument_new" class="form-label"># Identificación</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" id="nDocument_new" name="nDocument_new" value="<?php echo $nDocument_new = !empty($nDocument_new) ? $nDocument_new : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="fecha_new" class="form-label">Fecha de nacimiento</label><span style="color: red;">*</span>
                                            <input type="date" class="form-control" id="fecha_new" aria-describedby="DV" name="fecha_new" value="<?php echo $dv = !empty($fecha_new) ? $fecha_new : ''; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="telefono_new" class="form-label">Teléfono</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="telefono_new" aria-describedby="DV" name="telefono_new" value="<?php echo $dv = !empty($telefono_new) ? $telefono_new : ''; ?>" required>

                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col">
                                            <label for="Nombre_new" class="form-label">Nombre: </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="Nombre_new" aria-describedby="Nombre_new" name="Nombre_new" value="<?php echo $nombre_new = !empty($nombre_new) ? $nombre_new : ''; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="Apellido_new" class="form-label">Apellido: </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="Apellido_new" aria-describedby="Apellido_new" name="Apellido_new" value="<?php echo $apellido_new = !empty($apellido_new) ? $apellido_new : ''; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label for="correo_new" class="form-label">Correo: </label><span style="color: red;">*</span>
                                            <input type="email" class="form-control" id="correo_new" aria-describedby="correo_new" name="correo_new" value="<?php echo $actEco_new = !empty($correo_new) ? $correo_new : ''; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="direccion_new" class="form-label">Dirección: </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="direccion_new" aria-describedby="direccion_new" name="direccion_new" value="<?php echo $direccion_new = !empty($direccion_new) ? $direccion_new : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="tpBodega_up" class="form-label">Bodega</label><span style="color: red;">*</span>
                                            <select class="form-select" name="tpBodega_up" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($constBg as $key) { ?>
                                                    <option value="<?php echo $key['idBodega'] ?>"><?php echo $key['bodega_nombre'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="tpRol_up" class="form-label">Tipo de Rol</label><span style="color: red;">*</span>
                                            <select class="form-select" name="tpRol_up" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($constRol as $key) { ?>
                                                    <option value="<?php echo $key['idRol'] ?>"><?php echo $key['rol_nombre'] ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center align-items-center mb-1">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="client_new" class="btn btn-primary" value="client_new"> Registrar </button>
                                            </div>
                                        </div>
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
    <!-- <?php include_once '../assets/in/alerts_answer.php'; ?> -->
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