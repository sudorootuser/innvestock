<!DOCTYPE html>
<html lang="en">


<?php
include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Variables Globales
$url = "dispatch-listDs.php";
$tabla = "despacho";

// Total de la páginación x vista
include_once '../assets/in/returns.php';

// Se incluye la calse de cliente
include_once '../../Controller/dispatch_class.php';

// Instanciamos una nueva clase de cliente
$dispatchClass = new dispatchClass();
$_SESSION['alistado'] = $_GET['id'];
$id_del = $_SESSION['alistado'];

// busqueda de productos INDIVIDUALES del alistado
$enlisted = $dispatchClass->searchEnlisted($_SESSION['alistado']);


if (!empty($enlisted)) {
    $_SESSION['id_client_pro'] = $enlisted[0]["alistado_idCliente"];
    $cons_client = consultaSimple('clienteAlist', $enlisted[0]['alistado_idCliente']);
} ?>

<body class=" sb-nav-fixed">
    <?php
    if (empty($enlisted) && empty($enlistedKit)) { ?>
        <script>
            let alerta = {
                Alerta: "simple",
                Icono: 'info',
                Titulo: "Error",
                Texto: "Alistado Despachado",
                Tipo: "error",
                href: "<?php echo BASE_URL; ?>View/contenido/dispatch-list.php"
            };
            alertas_ajax(alerta);
        </script>
        <?php
    } else {
        $id_del = encrypt_decrypt('decrypt', $id_del);
        $dataKit = $dispatchClass->kitDetails($_SESSION['id_client_pro']);
        $dataPr = $dispatchClass->tableDetails($_SESSION['id_client_pro']);
    }

    // Se actualiza la data de la Sessión de produtos, se agregar la información del estado del producto
    if (isset($_POST['actu_prod']) and $_POST['actu_prod'] == 'actu_prod') {

        $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_UP']);

        $cant_alis = $_POST['cant_alistup'];

        if ($cant_alis <= 0) { ?>
            <script>
                alert('Menor b');
            </script>
        <?php
        } else {
            $_SESSION["despacho"][$idProd]['CantAlis'] = $_POST['cant_alistup'];
        }
    }

    /* Formulario para despachar un producto */
    if (!empty($_POST['dispatch_new'])) {


        $idClient = $enlisted[0]['alistado_idCliente'];
        $descripCorta = limpiar_cadena($_POST['descripCorta_new']);
        $nombre = limpiar_cadena($_POST['nombrePersona_new']);
        $cedula = limpiar_cadena($_POST['cedulaPersona_new']);
        $placa = limpiar_cadena($_POST['placaPersona_new']);
        $codigo = limpiar_cadena($_POST['codigo_new']);
        $clienteF = limpiar_cadena($_POST['clienteF_new']);
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

        if (isset($_SESSION['despacho']) && isset($enlisted)) {
            $producto = $_SESSION['despacho'];
        } else {
            $producto = "";
        }


        if ($enlisted != "" && $nombre != "" && $cedula != "" && $placa != "" && $producto != "") {
            $vali = $dispatchClass->validacionPersona($nombre, $cedula, $placa, $enlisted, $clienteF, $codigo);

            if ($vali == "true") {
                $val = $dispatchClass->validacion($producto, $enlisted);
                if ($val == "true") {

                    $uid = $dispatchClass->dispatchRegistration($idClient, $descripCorta, $producto, $nombre, $cedula, $placa, $codigo, $clienteF,$_FILES['ingreso_firma']);

                    if ($uid == true) { ?>
                        <script>
                            let alerta = {
                                Alerta: "registro",
                                Icono: '',
                                Titulo: "Registrando ingreso",
                                Texto: "El ingreso se está registrando correctamente",
                                Tipo: "success",
                                href: "<?php echo BASE_URL; ?>View/contenido/dispatch-list.php"
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
                            Icono: 'error',
                            Titulo: "Ocurrio un error...",
                            Texto: "<?php echo $val ?>"
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
                        Titulo: "Error",
                        Texto: "<?php echo $vali ?>",
                        Tipo: "error",

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
                    Texto: "Error en el registro, intente nuevamente!",
                    Tipo: "",

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
                    <h1 class="mt-4">Despachar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="enlistedDs-list.php">Alistados</a></li>
                        <li class="breadcrumb-item active">Despachar pedido</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2">
                                    <a onclick="GetUserDetails()" style="width: 100%;" class="btn btn-success mb-3">Agregar Productos </a>
                                </div>
                                <div class="col-sm-2">
                                    <a onclick="GetKitDetails()" style="width: 100%;" class="btn btn-success mb-3">Agregar Kit </a>
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
                                                        <th scope="col">Nombre</th>

                                                        <th scope="col">Peso</th>
                                                        <th scope="col">Cantidad a Despachar</th>
                                                        <th scope="col">Peso Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    // productos a despachar 
                                                    if (!empty($_SESSION["despacho"])) {
                                                        $pesoT = 0;
                                                        $cantidadT = 0;
                                                        foreach ($_SESSION["despacho"] as $row) {
                                                            $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                            $cantidadT = $cantidadT + $row['CantAlis']; ?>
                                                            <tr>
                                                                <td><?php echo $row['Codigo'] ?></td>
                                                                <td><?php echo $row['Nombre'] ?></td>
                                                                <td><?php echo $row['Peso']; ?> Kg</td>
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
                                                                        <a href="../../Ajax/ajax_dispatch.php?del=deleteProduct&idPr=<?php echo encrypt_decrypt('encrypt', $row['id']); ?>" class="btn btn-danger btn_table_del bi bi-trash3"></a>
                                                                    </td>

                                                                </form>

                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        } ?>
                                                        <tr>
                                                            <td colspan="3"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?> Kg</td>
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
                                                <thead class="table align-middle">
                                                    <tr>
                                                        <th scope="col">Codigo</th>
                                                        <!-- <th scope="col">Nombre</th> -->
                                                        <th scope="col">Peso</th>
                                                        <th scope="col">Cantidad Existente</th>
                                                        <th scope="col">Cantidad Alistada</th>
                                                        <th scope="col">Peso Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php



                                                    // alistado
                                                    if (!empty($enlisted)) {
                                                        $pesoT2 = 0;
                                                        $cantidadT2 = 0;

                                                        foreach ($enlisted as $enli) { ?>
                                                            <tr>
                                                                <?php foreach (explode("__", $enli["productos"]) as $e) {
                                                                    $producto = explode("..", $e);
                                                                    $pesoT2 = $pesoT2 + ($producto[3] * $enli['producto_alistado_cantidad']);
                                                                    $cantidadT2 = $cantidadT2 + $enli['producto_alistado_cantidad']; ?>

                                                                    <td><?php echo $producto[0] ?></td>
                                                                    <!-- <td><?php echo $producto[1] ?></td> -->
                                                                    <td><?php echo $producto[3]; ?> Kg</td>
                                                                    <td><?php echo $producto[4]; ?></td>

                                                                <?php } ?>
                                                                <td><?php echo $enli['producto_alistado_cantidad']; ?></td>

                                                                <td><?php echo ($producto[3] * $enli['producto_alistado_cantidad']) ?> Kg </td>
                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td colspan="3"></td>
                                                            <td class="table-active"><?php echo $cantidadT2 ?></td>
                                                            <td class="table-active"><?php echo $pesoT2 ?> Kg</td>
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
                            <?php if (!empty($_SESSION['despacho'])) { ?>
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="d-flex justify-content-center">
                                            <a class="btn btn-danger" href="../../Ajax/ajax_dispatch.php?del=empty">Vaciar Productos</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <form action='' method="POST" autocomplete="off" name="dispatch_new" class="needs-validation" novalidate enctype="multipart/form-data">
                                <div class="row card-body">
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Observacion</label>
                                            <textarea class="form-control" maxlength="100" name="descripCorta_new"><?php echo $enlisted[0]['alistado_observacion'] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3 ">
                                        <div class="col">
                                            <label class="form-label">Nombre del cliente: </label>
                                            <select class="form-select" aria-label="Default select example" name="idCliente_new" disabled>
                                                <option value="<?php echo $cons_client[0]['idCliente'] ?>" selected> <?php echo $cons_client[0]['cliente_nombre']  . ' ' .  $cons_client[0]['cliente_apellido'] ?></option>
                                            </select>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">N. Documento: </label>
                                            <input type="text" class="form-control" name="cliente_nDocument" value="<?php echo $cons_client[0]['cliente_nDocument'] ?>" disabled>
                                        </div>
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
                                            <input type="text" class="form-control" name="nombrePersona_new" value="<?php echo $enlisted[0]['alistado_nombrePersona'] ?>" required>

                                        </div>

                                        <div class="col">
                                            <label class="form-label">Cedula:</label>
                                            <input type="number" class="form-control" name="cedulaPersona_new" value="<?php echo $enlisted[0]['alistado_cedulaPersona'] ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Placa:</label>
                                            <input type="text" class="form-control" name="placaPersona_new" value="<?php echo $enlisted[0]['alistado_placaPersona'] ?>" required>

                                        </div>

                                        <?php

                                        ?>
                                    </div>

                                    <div class="row mb-3 ">
                                        <div class="col">
                                            <label class="form-label">Codigo Ingreso: </label>
                                            <input type="text" class="form-control" value="<?php echo $enlisted[0]['alistado_codigo'] ?>" name="codigo_new">
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Cliente Final</label>
                                            <input type="text" class="form-control" value="<?php echo $enlisted[0]['alistado_clienteF'] ?>" name="clienteF_new">
                                        </div>
                                        <div class="col">

                                        </div>
                                    </div>

                                    <!-- firma -->
                                    <div class="row">
                                        <div class="row">
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
                                    </div>

                                </div>
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" name="dispatch_new" class="btn btn-primary" value="enlisted_new"> Registrar </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </main>
            <?php include_once '../assets/in/modals.php'; ?>

            <?php include_once '../assets/in/modals.php'; ?>
        </div>
    </div>

    <!-- script -->
    <script>
        // Modal
        function GetUserDetails() {
            $("#despacho_modal").modal("show");
        }

        function GetKitDetails() {
            $("#despachoKit_modal").modal("show");
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

        // Alerta de eliminación
        const flashdata = $('.flash-error').data('flashdata')
        if (flashdata == 1) {
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error inesperado!',
                text: 'Todos los campos son obligatorios!',
            });
        }

        // !--script firma-- >
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
            // Code taken from https://github.com/ebidel/filer.js
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