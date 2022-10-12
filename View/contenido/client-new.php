<!DOCTYPE html>
<html lang="es">
<?php
// Variables Globales unicas x página
$url = "client-list.php";
$tabla = "cliente";

// Se incluyen los estilos
include_once "../assets/in/Head.php";

// Se incluye la validación del usuario logueado
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Total de la páginación x vista
include_once '../assets/in/returns.php';

// Se incluye la calse de cliente
include_once '../../Controller/client_class.php';

// Instanciamos una nueva clase de cliente
$clientClass = new clientClass();

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

    /* Validación para actualizar un nuevo cliente */
    if (!empty($_POST['client-update'])) {

        $tpId = limpiar_cadena($_POST['tp-document_up']);
        $nDocument = limpiar_cadena($_POST['nDocument_up']);
        $dv = limpiar_cadena($_POST['dv_up']);
        $ciudad = limpiar_cadena($_POST['ciudad_up']);
        $nombre = limpiar_cadena($_POST['nombre_up']);

        $apellido = limpiar_cadena($_POST['apellido_up']);
        $actEco = limpiar_cadena($_POST['actEco_up']);
        $direccion = limpiar_cadena($_POST['direccion_up']);
        $telefono = limpiar_cadena($_POST['telefono_up']);
        $tpCliente = limpiar_cadena($_POST['tpCliente_up']);
        $estado = limpiar_cadena($_POST['estado_up']);

        if ($tpId != "" && $nDocument != "" && $dv != "" && $ciudad != ""  && $nombre != "" && $apellido != "" && $actEco != "" && $direccion != "" && $telefono != "" &&  $tpCliente != "" &&  $estado != "") {

            $uid = $clientClass->updateClient($tpId, $nDocument, $dv, $nombre, $apellido, $actEco, $direccion, $telefono, $ciudad, $tpCliente, $id, $estado);

            if ($uid) {
                $var =  $_SESSION['idClienteNew']; ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Actualizando cliente",
                        Texto: "El cliente se está actualizando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/client-list.php"
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
                        Texto: "No se pudo acualizar el cliente, contacte con el admminstrador del sistema",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $var ?>"
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
    if (!empty($_POST['client_new'])) {

        $tpId_new = limpiar_cadena($_POST['tp-document_new']);
        $nDocument_new = limpiar_cadena($_POST['nDocument_new']);
        $dv_new = limpiar_cadena($_POST['dv_new']);
        $ciudad_new = limpiar_cadena($_POST['ciudad_new']);
        $nombre_new = limpiar_cadena($_POST['nombre_new']);
        $apellido_new = limpiar_cadena($_POST['apellido_new']);
        $actEco_new = limpiar_cadena($_POST['actEco_new']);
        $direccion_new = limpiar_cadena($_POST['direccion_new']);
        $telefono_new = limpiar_cadena($_POST['telefono_new']);
        $tpCliente_new = limpiar_cadena($_POST['tpCliente_new']);


        $user_exis = consultaCedula($nDocument_new);

        if ($user_exis == 0) {

            if ($tpCliente_new != "Seleccione" and $tpId_new != "Seleccione" and $ciudad_new != 'Seleccione') {


                if ($tpId_new != ""  && $nDocument_new != "" && $dv_new != "" && $ciudad_new != "" && $nombre_new != "" && $apellido_new != "" && $actEco_new != "" && $direccion_new != "" && $telefono_new != "" && $tpCliente_new != "") {

                    $uid = $clientClass->clientRegistration($tpId_new, $nDocument_new, $dv_new, $nombre_new, $apellido_new, $actEco_new, $direccion_new, $telefono_new, $ciudad_new, $tpCliente_new);

                    if ($uid) {
                        $encrypt = encrypt_decrypt('encrypt', 1); ?>
                        <script>
                            let alerta = {
                                Alerta: "registro",
                                Icono: '',
                                Titulo: "Registrando cliente",
                                Texto: "El cliente se está registrando correctamente",
                                Tipo: "success",
                                href: "<?php echo BASE_URL; ?>View/contenido/client-list.php"
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
                    Texto: "El Número de documento ya se encuentra registrado!",
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
                                <h1 class="mt-4"><?php echo $resultado = $var == 'update' ? 'Editar' : 'Nuevo' ?> cliente</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="client-list.php">Clientes</a></li>
                                    <li class="breadcrumb-item active"><?php echo $resultado =  $var == 'update' ? 'Editar' : 'Nuevo' ?> Cliente</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data de clientes
                        </div>
                        <div class="card-body">
                            <?php if (!empty($_SESSION['idClienteNew'])) { ?>

                                <!-- Inicio del formulario de actualizar -->
                                <form action='' method="POST" autocomplete="off" name="client-update" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col">
                                            <label for="Apellido" class="form-label">Tipo de identificación</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="tp-document_up" value="<?php echo $detalis->cliente_tpId ?>" required>
                                                <option value="">Seleccione...</option>
                                                <option value="NIT" <?php echo $resultado = $detalis->cliente_tpId == "NIT" ? "selected" : ''; ?>>NIT</option>
                                                <option value="Cédula de ciudadanía" <?php echo $resultado = $detalis->cliente_tpId == "Cédula de ciudadanía" ? "selected" : ''; ?>>Cédula de ciudadanía</option>
                                                <option value="Cédula de extrangería" <?php echo $resultado = $detalis->cliente_tpId == "Cédula de extrangería" ? "selected" : ''; ?>>Cédula de extranjeria</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="Apellido" class="form-label"># Identificación</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" id="nDocument_up" name="nDocument_up" value="<?php echo $detalis->cliente_nDocument ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Digito de Verificación</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="dv_up" aria-describedby="DV" name="dv_up" value="<?php echo $detalis->cliente_dv ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Ciudad</label><span style="color: red;">*</span>
                                            <select class="form-select" name="ciudad_up" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($cons_city as $row) {
                                                    if ($row['idCiudad'] == $detalis->cliente_ciudad) { ?>
                                                        <option selected value="<?php echo $detalis->cliente_ciudad; ?>"><?php echo $row['ciudad_nombre']; ?></option>
                                                    <?php } else {
                                                    ?>
                                                        <option value="<?php echo $row['idCiudad']; ?>"><?php echo $row['ciudad_nombre']; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Nombre / Razón Social</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="nombre_up" aria-describedby="nombre" name="nombre_up" value="<?php echo $detalis->cliente_nombre ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Apellido </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="apellido_up" aria-describedby="apellido" name="apellido_up" value="<?php echo $detalis->cliente_apellido ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Actividad Económica </label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="actEco_up" aria-describedby="actEco" name="actEco_up" value="<?php echo $detalis->cliente_actEco ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Dirección Principal</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" id="direccion_up" aria-describedby="Direccion" name="direccion_up" value="<?php echo $detalis->cliente_direccion ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label for="N-Documento" class="form-label">Telófono Principal</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" id="telefono_up" aria-describedby="Telefono" name="telefono_up" value="<?php echo $detalis->cliente_telefono ?>" required>
                                        </div>
                                        <div class="col">
                                            <label for="Apellido" class="form-label">Tipo de Cliente</label><span style="color: red;">*</span>
                                            <select class="form-select" name="tpCliente_up" required>
                                                <option value="">Seleccione...</option>
                                                <option value="Importador" <?php echo $resultado = $detalis->cliente_tpCliente == "Importador" ? "selected" : ''; ?>>Importador</option>
                                                <option value="Exportador" <?php echo $resultado = $detalis->cliente_tpCliente == "Exportador" ? "selected" : ''; ?>>Exportador</option>
                                                <option value="Distribuidor" <?php echo $resultado = $detalis->cliente_tpCliente == "Distribuidor" ? "selected" : ''; ?>>Distribuidor</option>
                                                <option value="Fabricante" <?php echo $resultado = $detalis->cliente_tpCliente == "Fabricante" ? "selected" : ''; ?>>Fabricante</option>
                                                <option value="Tercero" <?php echo $resultado = $detalis->cliente_tpCliente == "Tercero" ? "selected" : ''; ?>>Tercero</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Estado</label><span style="color: red;">*</span>
                                            <select class="form-select" name="estado_up" required>
                                                <option value="activo" <?php echo $resultado = $detalis->cliente_estado == "activo" ? "selected" : ''; ?>>Activo</option>
                                                <option value="inactivo" <?php echo $resultado = $detalis->cliente_estado == "inactivo" ? "selected" : ''; ?>>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center align-items-center mb-1">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="client-update" class="btn btn-primary" value="client_up"> Actualizar </button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                                <br>

                                <div class="card mb-4 mt-2">
                                    <div class="card-body">
                                        <!-- Inicio del formulario para agregar bodegas por cliente -->
                                        <div class="row">
                                            <div class="col-8">
                                                <form action='' method="POST" autocomplete="off" name="client-update" class="needs-validation" novalidate>

                                                    <div class="card-body table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Bodega asociada</th>
                                                                    <th scope="col">Fecha de registro</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (count($AllCellarClient) > 0) {
                                                                    foreach ($AllCellarClient as $row) { ?>
                                                                        <tr>
                                                                            <td><?php echo $count ?></td>
                                                                            <td><?php echo $row['nombreBodega'] ?></td>
                                                                            <td><?php echo $row['cliente_bodega_fechaIngreso'] ?></td>
                                                                            <td>
                                                                                <a href="../../Ajax/ajax_client.php?deldb=deleteClienbd&id=<?php echo encrypt_decrypt('encrypt', $row['idCliente_bodega']); ?>" class="btn btn-danger btn_table_del"><i class="bi bi-trash3 btn-danger"></i></a>

                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                        $count++;
                                                                    }
                                                                } else { ?>
                                                                    <tr class="text-center">
                                                                        <td colspan="12">No hay Bodegas </td>
                                                                    </tr>
                                                                <?php  } ?>
                                                            </tbody>
                                                        </table>
                                                        <!-- Paginación -->
                                                        <?php
                                                        if (count($AllCellarClient) > 0) {
                                                            include_once '../assets/in/pagination.php';
                                                        } ?>
                                                    </div>
                                                    <br>
                                                </form>
                                            </div>
                                            <div class="col-4">
                                                <!-- Formurio que muestra la data del cliente y las bodegas a asociar -->
                                                <form action='' method="POST" autocomplete="off" name="client_new" class="needs-validation" novalidate>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label for="N-Documento" class="form-label">Bodega</label><span style="color: red;">*</span>
                                                            <select class="form-select" name="bodegaCliente" required>
                                                                <option value="" selected>Seleccione...</option>
                                                                <?php foreach ($constBg as $row) { ?>
                                                                    <option value="<?php echo $row['idBodega']; ?>"><?php echo $row['bodega_nombre']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br>

                                                    <button type="submit" name="Newclientcell" class="btn btn-primary" value="Newclientcell"> Asociar bodega </button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>

                    <?php } else { ?>
                        <!-- Inicio de formulario para crear un nuevo cliente -->
                        <form action='' method="POST" autocomplete="off" name="client_new" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col">
                                    <label for="Apellido" class="form-label">Tipo de identificación </label><span style="color: red;">*</span>
                                    <select class="form-select" aria-label="Default select example" name="tp-document_new" required>
                                        <option value="">Seleccione...</option>
                                        <option value="NIT">NIT</option>
                                        <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                        <option value="Cédula de extrangería">Cédula de extranjeria</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="Apellido" class="form-label"># Identificación</label><span style="color: red;">*</span>
                                    <input type="number" class="form-control" id="nDocument_new" name="nDocument_new" value="<?php echo $nDocument_new = !empty($nDocument_new) ? $nDocument_new : ''; ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="N-Documento" class="form-label">Digito de Verificación</label><span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="dv_new" aria-describedby="DV" name="dv_new" value="<?php echo $dv = !empty($dv_new) ? $dv_new : ''; ?>" required>
                                </div>
                                <div class="col">
                                    <label for="N-Documento" class="form-label">Ciudad</label><span style="color: red;">*</span>
                                    <select class="form-select" name="ciudad_new" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($cons_city as $row) { ?>
                                            <option value="<?php echo $row['idCiudad']; ?>"><?php echo $row['ciudad_nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col">
                                    <label for="N-Documento" class="form-label">Nombre / Razón Social</label><span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="pNombre_new" aria-describedby="pNombre" name="nombre_new" value="<?php echo $nombre_new = !empty($nombre_new) ? $nombre_new : ''; ?>" required>
                                </div>
                                <div class="col">
                                    <label for="N-Documento" class="form-label">Apellido </label><span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="pApellido_new" aria-describedby="sApellido" name="apellido_new" value="<?php echo $apellido_new = !empty($apellido_new) ? $apellido_new : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col">
                                    <label for="N-Documento" class="form-label">Actividad Económica </label><span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="actEco_new" aria-describedby="actEco" name="actEco_new" value="<?php echo $actEco_new = !empty($actEco_new) ? $actEco_new : ''; ?>" required>
                                </div>
                                <div class="col">
                                    <label for="N-Documento" class="form-label">Dirección Principal</label><span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="direccion_new" aria-describedby="Direccion" name="direccion_new" value="<?php echo $direccion_new = !empty($direccion_new) ? $direccion_new : ''; ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="N-Documento" class="form-label">Teléfono Principal</label><span style="color: red;">*</span>
                                    <input type="number" class="form-control" id="telefono_new" aria-describedby="Telefono" name="telefono_new" value="<?php echo $telefono_new = !empty($telefono_new) ? $telefono_new : ''; ?>" required>
                                </div>
                                <div class="col-6">
                                    <label for="Apellido" class="form-label">Tipo de Cliente</label><span style="color: red;">*</span>
                                    <select class="form-select" name="tpCliente_new" required>
                                        <option value="">Seleccione..</option>
                                        <option value="Importador">Importador</option>
                                        <option value="Exportador">Exportador</option>
                                        <option value="Distribuidor">Distribuidor</option>
                                        <option value="Fabricante">Fabricante</option>
                                        <option value="Tercero">Tercero</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center align-items-center mb-1">
                                <div class="col">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" name="client_new" class="btn btn-primary  " value="client_new"> Registrar </button>
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