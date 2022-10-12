<!DOCTYPE html>
<html lang="en">

<?php
include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Total de la páginación x vista
$tabla = "producto";

include_once '../assets/in/returns.php';
// Se incluye la calse de cliente

include_once '../../Controller/product_class.php';
include 'UIDContainer.php';

// Se instancia un nuevo objecto
$productClass = new productClass();

// Se declaran consultas para los select de cliente
$cons_client = $productClass->cliente_producto();
// Se btiiene el id del producto a editar
if (isset($_GET['idUp'])) {
    
    $idBodega = encrypt_decrypt('decrypt', $_GET['idUp']);
    $ubicacion = $productClass->consultaSSimple(encrypt_decrypt('decrypt', $_GET['idUp']));
    $constBg = consultaSimple('clienteBodega', $_SESSION['idClienteDB']);
    $BodegaProducto = $productClass->consultaSimple('productoBodega',  $_SESSION['idClienteDB']);
} ?>

<!-- Función para el RFID para escanerar y obtener data -->
<!-- <script>
        $(document).ready(function() {
            $("#getUID").load("UIDContainer.php");
            setInterval(function() {
                $("#getUID").load("UIDContainer.php");
            }, 500);
        });
    </script> -->

<body class="sb-nav-fixed">
    <?php
    /* Formulario para registrar las bodegas por producto */
    if (!empty($_POST['Newclientcell'])) {
        $tipo = "Producto por bodega";
        $idProducto = limpiar_cadena($_SESSION['idClienteDB']);
        $fechaRegistro = date('Y-m-d');
        $bodega = limpiar_cadena($_POST['bodegaCliente']);
        if ($bodega != "Seleccione") {
            $search = $productClass->searchClientPd($idProducto, $bodega);
            if ($search == 0) {
                // Validación de datos y muestra de alertas
                if ($idProducto != '' && $fechaRegistro != '' && $bodega != '') {

                    $uid = $productClass->clientCellarRegistration($idProducto, $fechaRegistro, $bodega);

                    if ($uid) {
                        $var =  $_SESSION['idClienteNew'];

                        $encrypt = encrypt_decrypt('encrypt', 1); ?>
                        <script>
                            let alerta = {
                                Alerta: "simple",
                                Icono: 'success',
                                Titulo: "Bodega asociada",
                                Texto: "Agregada correctamente, cargando data!!",
                                Tipo: "success",
                                href: "<?php echo BASE_URL; ?>View/contenido/product-new.php?upS=<?php echo $var ?>"
                            };
                            alertas_ajax(alerta);
                        </script>
                    <?php
                    } else {
                        $var =  $_SESSION['idClienteNew']; ?>
                        <script>
                            let alerta = {
                                Alerta: "simple",
                                Icono: 'warning',
                                Titulo: "Ocurrio algo inesperado",
                                Texto: "La bodega ya se encuentra registrada",
                                Tipo: "",
                                href: "<?php echo BASE_URL; ?>View/contenido/product-new.php?upS=<?php echo $var ?>"
                            };
                            alertas_ajax(alerta);
                        </script>
                <?php
                    }
                }
            } else {
                $var =  $_SESSION['idClienteNew']; ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'warning',
                        Titulo: "Ocurrio algo inesperado",
                        Texto: "La bodega ya se encuentra registrada",
                        Tipo: "",
                        href: "<?php echo BASE_URL; ?>View/contenido/product-new.php?upS=<?php echo $var ?>"
                    };
                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else {
            $var =  $_SESSION['idClienteNew']; ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'error',
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "Debe seleccionar una bodega",
                    Tipo: "",
                    href: "<?php echo BASE_URL; ?>View/contenido/product-new.php?upS=<?php echo $var ?>"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    // Formulario para actualizar los productos
    if (!empty($_POST['product_up'])) {

        $codigo = limpiar_cadena($_POST['codigo_up']);
        $nombre = limpiar_cadena($_POST['nombre_up']);
        $rotacion = limpiar_cadena($_POST['rotacion_up']);
        $diasAviso = limpiar_cadena($_POST['diasAviso_up']);
        $minimo = limpiar_cadena($_POST['minimo_up']);

        $maximo  = limpiar_cadena($_POST['maximo_up']);
        $precio = limpiar_cadena($_POST['precio_up']);
        $descripcion = limpiar_cadena($_POST['descripcion_up']);
        $uniCant = limpiar_cadena($_POST['uniCant_up']);

        $peso = limpiar_cadena($_POST['peso_up']);
        $uniPeso = limpiar_cadena($_POST['uniPeso_up']);
        $modelo = limpiar_cadena($_POST['modelo_up']);
        $serial = limpiar_cadena($_POST['serial_up']);

        $lote = limpiar_cadena($_POST['lote_up']);
        $marca = limpiar_cadena($_POST['marca_up']);
        $fechaVenc = limpiar_cadena($_POST['fechaVenc_up']);
        $nContenedor = limpiar_cadena($_POST['nContenedor_up']);
        $ancho = limpiar_cadena($_POST['ancho_up']);

        $alto = limpiar_cadena($_POST['alto_up']);
        $largo = limpiar_cadena($_POST['largo_up']);
        $uniDimen = limpiar_cadena($_POST['uniDimen_up']);
        $idCliente = limpiar_cadena($_POST['idCliente_up']);
        $subinventario = limpiar_cadena($_POST['subinventario_up']);
        $estado = limpiar_cadena($_POST['estado_up']);
        $ubicacion_up = limpiar_cadena($_POST['ubicacion_up']);

        if ($nombre != '' && $codigo != '' && $uniCant != '' &&  $peso != '' && $uniPeso != '' && $idCliente != '' && $minimo != '' && $maximo != '' && $estado != '') {

            if (!empty($_FILES['imagen_1']['name'])) {
                $cont_img = $productClass->searchImage($_FILES['imagen_1']['name']);
            } elseif (!empty($_FILES['imagen_2']['name'])) {
                $cont_img = $productClass->searchImage($_FILES['imagen_2']['name']);
            } else {
                $cont_img = 0;
            }
            if ($cont_img == 0) {
                $val_img = 'true';

                if (!empty($_FILES['imagen_1']['name'])) {
                    $tp_1 = $_FILES['imagen_1']['type'];
                    $size_1 = $_FILES['imagen_1']['size'];
                    $val_img = $productClass->val_img_product($tp_1, $size_1);
                }
                if (!empty($_FILES['imagen_2']['name'])) {
                    $tp_2 = $_FILES['imagen_2']['type'];
                    $size_2 = $_FILES['imagen_2']['size'];
                    $val_img = $productClass->val_img_product($tp_2, $size_2);
                }

                if ($val_img == 'true') {
                    $productClass->updateProductoO($id, $ubicacion_up);
                    if ($uniPeso == "Gramo") {
                        $peso = $peso / 100;
                    } elseif ($uniPeso == "Libra") {
                        $peso = $peso / 2;
                    }
                    $uid = $productClass->updateProducto($codigo, $nombre, $rotacion, $diasAviso, $minimo, $maximo, $precio, $descripcion, $uniCant, $peso, $modelo, $serial, $lote, $marca, $fechaVenc, $nContenedor, $ancho, $alto, $largo, $uniDimen, $idCliente, $id, $subinventario, $estado);

                    if ($uid != '') {

                        if ($_POST['img_nm_1'] != 'N/A') {

                            if (!empty($_FILES['imagen_1']['name'])) {
                                $name_old = $_POST['img_nm_1'];
                                $name_new = $_FILES['imagen_1']['name'];

                                $tp_1 = $_FILES['imagen_1']['type'];
                                $size_1 = $_FILES['imagen_1']['size'];
                                $pltemp = $_FILES['imagen_1']['tmp_name'];

                                $resp =  $productClass->up_temp_img_product($name_old, $pltemp, $name_new, $tp_1, $size_1, $id);
                            } else {
                                $resp = true;
                            }
                        } else {
                            if (!empty($_FILES['imagen_1']['name'])) {
                                $img_1 = $_FILES['imagen_1']['name'];
                                $tp_1 = $_FILES['imagen_1']['type'];
                                $size_1 = $_FILES['imagen_1']['size'];
                                $pltemp = $_FILES['imagen_1']['tmp_name'];

                                $resp =  $productClass->add_temp_img_product($pltemp, $img_1, $tp_1, $size_1, $id);
                            } else {
                                $resp = true;
                            }
                        }

                        if ($_POST['img_nm_2'] != 'N/A') {

                            if (!empty($_FILES['imagen_2']['name'])) {

                                $name_old = $_POST['img_nm_2'];
                                $name_new = $_FILES['imagen_2']['name'];

                                $tp_2 = $_FILES['imagen_2']['type'];
                                $size_2 = $_FILES['imagen_2']['size'];
                                $pltemp = $_FILES['imagen_2']['tmp_name'];

                                $resp_2 = $productClass->up_temp_img_product($name_old, $pltemp, $name_new, $tp_2, $size_2, $id);
                            } else {
                                $resp = true;
                            }
                        } else {

                            if (!empty($_FILES['imagen_2']['name'])) {

                                $img_2 = $_FILES['imagen_2']['name'];
                                $tp_2 = $_FILES['imagen_2']['type'];
                                $size_2 = $_FILES['imagen_2']['size'];
                                $pltemp = $_FILES['imagen_2']['tmp_name'];

                                $resp_2 = $productClass->add_temp_img_product($pltemp, $img_2, $tp_2, $size_2, $id);
                            } else {
                                $resp_2 = true;
                            }
                        }

                        if ($resp == true or $resp_2 == true) {
                            $encrypt = encrypt_decrypt('encrypt', 1); ?>

                            <script>
                                let alerta = {
                                    Alerta: "registro",
                                    Icono: '',
                                    Titulo: "Actualizando producto",
                                    Texto: "El cliente se está registrando correctamente",
                                    Tipo: "success",
                                    href: "<?php echo BASE_URL; ?>View/contenido/product-list.php"
                                };
                                alertas_ajax(alerta);
                            </script>
                <?php
                        } else {
                            $errorMsgLogin = $resp;
                            $errorMsgLogin = $resp_2;
                        }
                    } else {
                        $errorMsgLogin = "Error en el registro, contacte con el administrador!!";
                    }
                } else {
                    $errorMsgLogin = $val_img;
                }
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "Ocurrio algo inesperado",
                        Texto: "El nombre de la imagen ya se encuentra registrado, le recomendamos cambiar el nombre!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $var ?>"
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
                    Titulo: "Ocurrio algo inesperado",
                    Texto: "Todos los campos son obligatorios!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/client-new.php?upS=<?php echo $var ?>"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    /* Formulario para crear un nuevo producto */
    if (!empty($_POST['product_new'])) {

        $codigo_new = limpiar_cadena($_POST['codigo_new']);
        $nombre_new = limpiar_cadena($_POST['nombre_new']);
        $idCliente = limpiar_cadena($_POST['idCliente_new']);

        $minimo_new = limpiar_cadena($_POST['minimo_new']);
        $maximo_new = limpiar_cadena($_POST['maximo_new']);
        $subinventario_new = limpiar_cadena($_POST['subinventario_new']);

        $uniCant_new = limpiar_cadena($_POST['uniCant_new']);
        $peso_new = limpiar_cadena($_POST['peso_new']);
        $uniPeso_new = limpiar_cadena($_POST['uniPeso_new']);

        $ancho_new = limpiar_cadena($_POST['ancho_new']);
        $alto_new = limpiar_cadena($_POST['alto_new']);
        $largo_new = limpiar_cadena($_POST['largo_new']);
        $uniDimen_new = limpiar_cadena($_POST['uniDimen_new']);

        $modelo_new = limpiar_cadena($_POST['modelo_new']);
        $serial_new = limpiar_cadena($_POST['serial_new']);
        $lote_new = limpiar_cadena($_POST['lote_new']);
        $marca_new = limpiar_cadena($_POST['marca_new']);

        $rotacion_new = limpiar_cadena($_POST['rotacion_new']);
        $diasAviso_new = limpiar_cadena($_POST['diasAviso_new']);
        $descripcion_new = limpiar_cadena($_POST['descripcion_new']);
        $precio_new = limpiar_cadena($_POST['precio_new']);
        $fechaVenc_new = limpiar_cadena($_POST['fechaVenc_new']);
        $nContenedor_new = limpiar_cadena($_POST['nContenedor_new']);
        $rfid_new_new = limpiar_cadena($_POST['rfid_new']);

        $ubicacion_new = limpiar_cadena($_POST['ubicacion_new']);

        $user_exis = $productClass->searchProduct($codigo_new);

        if ($user_exis == 0) {

            if ($idCliente != "Seleccione" and $uniCant_new != "Seleccione" and $uniPeso_new != "Seleccione" and $uniDimen_new != 'Seleccione' and $rotacion_new != "Seleccione") {

                if ($nombre_new != '' && $codigo_new != '' && $uniCant_new != '' &&  $peso_new != '' && $uniPeso_new != '' && $idCliente != '' && $minimo_new != '' && $maximo_new != '') {

                    if (!empty($_FILES['imagen_1']['name'])) {
                        $cont_img = $productClass->searchImage($_FILES['imagen_1']['name']);
                    } elseif (!empty($_FILES['imagen_2']['name'])) {
                        $cont_img = $productClass->searchImage($_FILES['imagen_2']['name']);
                    } else {
                        $cont_img = 0;
                    }
                    if ($cont_img == 0) {
                        $val_img = 'true';

                        if (!empty($_FILES['imagen_1']['name'])) {
                            $tp_1 = $_FILES['imagen_1']['type'];
                            $size_1 = $_FILES['imagen_1']['size'];
                            $val_img = $productClass->val_img_product($tp_1, $size_1);
                        }
                        if (!empty($_FILES['imagen_2']['name'])) {

                            $tp_2 = $_FILES['imagen_2']['type'];
                            $size_2 = $_FILES['imagen_2']['size'];
                            $val_img = $productClass->val_img_product($tp_2, $size_2);
                        }

                        if ($val_img == 'true') {
                            if ($uniPeso_new == "Gramo") {
                                $peso_new = $peso_new / 100;
                            } elseif ($uniPeso_new == "Libra") {
                                $peso_new = $peso_new / 2;
                            }

                            $uid = $productClass->productRegistration($codigo_new, $nombre_new, $rotacion_new, $diasAviso_new, $minimo_new, $maximo_new, $precio_new, $descripcion_new, $uniCant_new, $peso_new, $modelo_new, $serial_new, $lote_new, $marca_new, $fechaVenc_new, $nContenedor_new, $ancho_new, $alto_new, $largo_new, $uniDimen_new, $idCliente, $rfid_new_new, $subinventario_new, $ubicacion_new);

                            if ($uid != '') {

                                if (!empty($_FILES['imagen_1']['name'])) {
                                    $img_1 = $_FILES['imagen_1']['name'];
                                    $tp_1 = $_FILES['imagen_1']['type'];
                                    $size_1 = $_FILES['imagen_1']['size'];
                                    $pltemp = $_FILES['imagen_1']['tmp_name'];

                                    $resp =  $productClass->add_temp_img_product($pltemp, $img_1, $tp_1, $size_1, $uid);
                                } else {
                                    $resp = true;
                                }
                                if (!empty($_FILES['imagen_2']['name'])) {

                                    $img_2 = $_FILES['imagen_2']['name'];
                                    $tp_2 = $_FILES['imagen_2']['type'];
                                    $size_2 = $_FILES['imagen_2']['size'];
                                    $pltemp = $_FILES['imagen_2']['tmp_name'];

                                    $resp_2 = $productClass->add_temp_img_product($pltemp, $img_2, $tp_2, $size_2, $uid);
                                } else {
                                    $resp_2 = true;
                                }
                                if ($resp == true or $resp_2 == true) {
                                    $encrypt = encrypt_decrypt('encrypt', 1); ?>
                                    <script>
                                        let alerta = {
                                            Alerta: "registro",
                                            Icono: '',
                                            Titulo: "Registrando producto",
                                            Texto: "El cliente se está registrando correctamente",
                                            Tipo: "success",
                                            href: "<?php echo BASE_URL; ?>View/contenido/product-list.php"
                                        };
                                        alertas_ajax(alerta);
                                    </script>
                                <?php
                                } else { ?>
                                    <script>
                                        let alerta = {
                                            Alerta: "error",
                                            Icono: 'error',
                                            Titulo: "Ocurrio algo inesperado...",
                                            Texto: "<?php echo  $resp . ' ' . $resp_2 ?>",
                                            Tipo: ""
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
                                        Titulo: "Ocurrio algo inesperado...",
                                        Texto: "Error en el registro, contacte con el administrador!!",
                                        Tipo: ""
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
                                    Titulo: "Ocurrio algo inesperado...",
                                    Texto: "<?php echo $val_img; ?>",
                                    Tipo: ""
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
                                Titulo: "Ocurrio algo inesperado...",
                                Texto: "El nombre de la imagen ya se encuentra registrado, le recomedamos cambiar el nombre!",
                                Tipo: ""
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
                            Titulo: "Ocurrio algo inesperado...",
                            Texto: "Debe completar los campos que son obligatorios",
                            Tipo: ""
                        };
                        alertas_ajax(alerta);
                    </script>
                <?php }
            } else { ?>
                <script>
                    let alerta = {
                        Alerta: "error",
                        Icono: 'error',
                        Titulo: "Ocurrio algo inesperado...",
                        Texto: "Debe seleccionar un cliente, unidad de peso dimenciones, rotación, unidad de medida ",
                        Tipo: ""
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
                    Titulo: "Ocurrio algo inesperado...",
                    Texto: "El código del producto ya se encuentra registrado",
                    Tipo: ""
                };
                alertas_ajax(alerta);
            </script>
    <?php
        }
    } ?>

    <?php
    include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>

        <!-- Contenedor de los Formualrios de Actualizar y registrar -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="mb-4">
                        <div class="row pt-4">
                            <div class="col-sm-6">
                                <h1 class="mt-4"><?php echo $resultado = $var == 'update' ? 'Editar' : 'Nuevo' ?> producto</h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="product-list.php">Productos</a></li>
                                    <li class="breadcrumb-item active"><?php echo $resultado =  $var == 'update' ? 'Editar' : 'Nuevo' ?> Producto</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>

                        <div class="card-body">

                            <?php if (!empty($_SESSION['idClienteNew'])) { ?>

                                <!-- Formulario para Actualizar -->
                                <form action='' method="POST" autocomplete="off" name="product_up" class="needs-validation" enctype="multipart/form-data" novalidate>

                                    <div class="row">

                                        <div class="col">
                                            <label class="form-label">Código / Referencia</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" name="codigo_up" value="<?php echo $detalis->producto_codigo ?>" required>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Nombre</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" name="nombre_up" value="<?php echo $detalis->producto_nombre ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Cliente</label><span style="color: red;">*</span>
                                            <select class="form-select" name="idCliente_up" required>
                                                <option value=""></option>

                                                <?php foreach ($cons_client as $row) {
                                                    if ($row['idCliente'] == $detalis->producto_idCliente) { ?>
                                                        <option selected value="<?php echo $row['idCliente']; ?>"><?php echo $row['cliente_nombre'] . ' ' . $row['cliente_apellido']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $row['idCliente']; ?>"><?php echo $row['cliente_nombre'] . ' ' . $row['cliente_apellido']; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Mínimo</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" name="minimo_up" value="<?php echo $detalis->producto_minimo ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Máximo</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" name="maximo_up" value="<?php echo $detalis->producto_maximo ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Alerta</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" name="alerta_up" value="<?php echo $detalis->producto_alerta ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Ubicacion</label>
                                            <input type="text" class="form-control" name="ubicacion_up" value="<?php echo $resultado = isset($ubicacion_up) ? $ubicacion_up : $ubicacion[0]['producto_bodega_ubicacion'] ?>">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Sub-Inventario</label><span style="color: red;"></span>
                                            <input type="text" class="form-control" name="subinventario_up" value="<?php echo $detalis->producto_subInventario ?>">
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Unidad de Medida (cantidad)</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="uniCant_up" required>
                                                <option value=""></option>

                                                <option value="Caja" <?php if ($detalis->producto_uniCant == "Caja") {
                                                                            echo "selected";
                                                                        } ?>>Caja</option>
                                                <option value="Unitaria" <?php if ($detalis->producto_uniCant == "Unitaria") {
                                                                                echo "selected";
                                                                            } ?>>Unitaria</option>
                                                <option value="Pallet" <?php if ($detalis->producto_uniCant == "Pallet") {
                                                                            echo "selected";
                                                                        } ?>>Pallet</option>
                                                <option value="Bulto" <?php if ($detalis->producto_uniCant == "Bulto") {
                                                                            echo "selected";
                                                                        } ?>>Bulto</option>
                                                <option value="Paquete" <?php if ($detalis->producto_uniCant == "Paquete") {
                                                                            echo "selected";
                                                                        } ?>>Paquete</option>
                                                <option value="Huacal" <?php if ($detalis->producto_uniCant == "Huacal") {
                                                                            echo "selected";
                                                                        } ?>>Huacal</option>

                                            </select>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Peso</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" name="peso_up" value="<?php echo $detalis->producto_peso ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Unidad de Medida (Peso)</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="uniPeso_up" required>
                                                <option value=""></option>


                                                <option value="Gramo" <?php if ($detalis->producto_uniPeso == "Gramo") {
                                                                            echo "selected";
                                                                        } ?>>Gramo</option>
                                                <option value="Libra" <?php if ($detalis->producto_uniPeso == "Libra") {
                                                                            echo "selected";
                                                                        } ?>>Libra</option>
                                                <option value="KiloGramo" <?php if ($detalis->producto_uniPeso == "KiloGramo") {
                                                                                echo "selected";
                                                                            } ?>>KiloGramo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Ancho</label>
                                            <input type="number" class="form-control" name="ancho_up" value="<?php echo $detalis->producto_ancho ?>">
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Alto</label>
                                            <input type="number" class="form-control" name="alto_up" value="<?php echo $detalis->producto_alto ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Largo</label>
                                            <input type="number" class="form-control" name="largo_up" value="<?php echo $detalis->producto_largo ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Unidad de Medida (Dimensiones)</label>
                                            <select class="form-select" aria-label="Default select example" name="uniDimen_up">
                                                <option value="">Seleccione...</option>

                                                <option value="MiliMetros" <?php if ($detalis->producto_uniDimen == "MiliMetros") {
                                                                                echo "selected";
                                                                            } ?>>MiliMetros</option>
                                                <option value="CentiMetros" <?php if ($detalis->producto_uniDimen == "CentiMetros") {
                                                                                echo "selected";
                                                                            } ?>>CentiMetros</option>
                                                <option value="Metros" <?php if ($detalis->producto_uniDimen == "Metros") {
                                                                            echo "selected";
                                                                        } ?>>Metros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Rotación</label>
                                            <select class="form-select" aria-label="Default select example" name="rotacion_up">
                                                <option value="_"></option>

                                                <option value="A" <?php if ($detalis->producto_rotacion == "A") {
                                                                        echo "selected";
                                                                    } ?>>A</option>
                                                <option value="B" <?php if ($detalis->producto_rotacion == "B") {
                                                                        echo "selected";
                                                                    } ?>>B</option>
                                                <option value="C" <?php if ($detalis->producto_rotacion == "C") {
                                                                        echo "selected";
                                                                    } ?>>C</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Días de aviso de vencimiento</label>
                                            <input type="number" class="form-control" name="diasAviso_up" value="<?php echo $detalis->producto_diasAviso ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Modelo</label>
                                            <input type="text" class="form-control" name="modelo_up" value="<?php echo $detalis->producto_modelo ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Serial</label>
                                            <input type="text" class="form-control" name="serial_up" value="<?php echo $detalis->producto_serial ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Lote</label>
                                            <input type="text" class="form-control" name="lote_up" value="<?php echo $detalis->producto_lote ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Marca</label>
                                            <input type="text" class="form-control" name="marca_up" value="<?php echo $detalis->producto_marca ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Descripción</label>
                                            <input type="text" class="form-control" name="descripcion_up" value="<?php echo $detalis->producto_descripcion ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Precio</label>
                                            <input type="number" class="form-control" name="precio_up" value="<?php echo $detalis->producto_precio ?>">
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label class="form-label">Fecha de Vencimiento</label>
                                            <input type="date" class="form-control" name="fechaVenc_up" min="<?php echo date('Y-m-d') ?>" value="<?php echo $detalis->producto_fechaVenc ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Número de Contenedor</label>
                                            <input type="number" class="form-control" name="nContenedor_up" value="<?php echo $detalis->producto_nContenedor ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Estado</label><span style="color: red;">*</span>
                                            <select class="form-select" name="estado_up" required>
                                                <option value="activo" <?php echo $resultado = $detalis->producto_bodega_estado == "activo" ? "selected" : ''; ?>>Activo</option>
                                                <option value="inactivo" <?php echo $resultado = $detalis->producto_bodega_estado == "inactivo" ? "selected" : ''; ?>>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    $host = 'localhost';
                                    $user = 'root';
                                    $password = '';
                                    $database = 'innvestock';

                                    $con = new mysqli($host, $user, $password, $database);

                                    $id = $detalis->idProducto;
                                    $query = "SELECT * FROM imagen WHERE producto_id = '$id'";
                                    $result = mysqli_query($con, $query);
                                    $row = mysqli_fetch_all($result);

                                    $img = [];
                                    foreach ($row as $rw) {
                                        $img[] = $rw[1];
                                    };
                                    // var_dump($img);die;
                                    ?>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="imagen_1" class="form-label">Actualizar Imagen</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><img style="width:20px; height:20px;" src="../assets/img/product/temp/<?php echo $resultado = empty($img[0]) ? 'default-product.jpg' : $img[0]; ?>" alt="" srcset=""></span>
                                                <input type="file" class="form-control" name="imagen_1" size="30" type="file" aria-describedby="basic-addon1">
                                            </div>
                                            <input type="hidden" name="img_nm_1" value="<?php echo $resultado = empty($img[0]) ? 'N/A' : $img[0]; ?>">
                                            <div id="emailHelp" class="form-text"><b>Nombre: </b><?php echo $resultado = empty($img[0]) ? 'N/A' : $img[0]; ?></div>
                                        </div>
                                        <div class="col-6">
                                            <!-- <?php echo $img[1]; ?> -->
                                            <label for="imagen_2" class="form-label">Actualizar Imagen</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><img style="width:20px; height:20px;" src="../assets/img/product/temp/<?php echo $resultado = empty($img[1]) ? 'default-product.jpg' : $img[1]; ?>" alt="" srcset=""></span>
                                                <input type="file" class="form-control" name="imagen_2" size="30" type="file" aria-describedby="basic-addon1">
                                            </div>
                                            <input type="hidden" name="img_nm_2" value="<?php echo $resultado = empty($img[1]) ? 'N/A' : $img[1]; ?>">
                                            <div id="emailHelp" class="form-text"><b>Nombre: </b><?php echo $resultado = empty($img[1]) ? 'N/A' : $img[1]; ?></div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center align-items-center mb-1">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="product_up" class="btn btn-primary" value="product_up"> Registrar </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="row">
                                    <div class="card-body mb-4 mt-2">
                                        <div class="card-body">
                                            <!-- Inicio del formulario para agregar bodegas por cliente -->
                                            <div class="row">
                                                <div class="col-8">
                                                    <form action='' method="POST" autocomplete="off" name="client-update" class="needs-validation" novalidate>
                                                        <div class="card-body table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Bodega asociada</th>
                                                                        <th scope="col">Fecha de registro</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php

                                                                    if (count($BodegaProducto) > 0) {
                                                                        foreach ($BodegaProducto as $row) { ?>
                                                                            <tr>
                                                                                <td><?php echo $row['bodega_nombre'] ?></td>
                                                                                <td><?php echo $row['producto_bodega_fechaIngreso'] ?></td>
                                                                                <td>
                                                                                    <a href="../../Ajax/ajax_product.php?deldb=deleteClienbd&id=<?php echo encrypt_decrypt('encrypt', $row['idProducto_bodega']); ?>" class="btn btn-danger btn_table_del bi bi-trash3"></a>
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
                                                            if (count($BodegaProducto) > 0) {
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
                                                                    <option value="Seleccione" selected>Seleccione..</option>
                                                                    <?php foreach ($constBg as $row) { ?>
                                                                        <option value="<?php echo $row['idBodega']; ?>"><?php echo $row['nombreBodega']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="d-grid gap-2">
                                                            <button type="submit" name="Newclientcell" class="btn btn-primary" value="Newclientcell"> Asociar bodega </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Inicio del Formulario registro -->
                            <?php } else { ?>
                                <!-- Formulario Crear -->
                                <form action='' method="POST" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
                                    <input type="hidden" name="product_new" value="product_new">
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Código / Referencia</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" name="codigo_new" value="<?php echo $codigo_new = empty($codigo_new) ? '' : $codigo_new; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Nombre</label><span style="color: red;">*</span>
                                            <input type="text" class="form-control" name="nombre_new" value="<?php echo $nombre_new = empty($nombre_new) ? '' : $nombre_new; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Cliente</label><span style="color: red;">*</span>
                                            <select class="form-select" name="idCliente_new" required>
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($cons_client as $row) { ?>
                                                    <option value="<?php echo $row['idCliente']; ?>"><?php echo $row['cliente_nombre'] . ' ' . $row['cliente_apellido']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">RFID</label>
                                            <input type="text" value="<?php echo $UIDresult ?>" class="form-control" name="rfid_new" size="30" value="<?php echo $rfid_new = empty($rfid_new) ? '' : $rfid_new; ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Mínimo</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" name="minimo_new" value="<?php echo $minimo_new = empty($minimo_new) ? '' : $minimo_new; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Máximo</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" name="maximo_new" value="<?php echo $maximo_new = empty($maximo_new) ? '' : $maximo_new; ?>" required>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Ubicacion</label>
                                            <input type="text" class="form-control" name="ubicacion_new" value="<?php echo $ubicacion_new = empty($ubicacion_new) ? '' : $ubicacion_new; ?>">
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label class="form-label">Sub-Inventario</label><span style="color: red;"></span>
                                            <input type="text" class="form-control" name="subinventario_new" value="<?php echo $subinventario_new = empty($subinventario_new) ? '' : $subinventario_new; ?>">
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Unidad de Medida (cantidad)</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="uniCant_new" value="" required>
                                                <option value="">Seleccione...</option>
                                                <option value="Caja" <?php echo $resultado = isset($uniCant_new) == "Caja" ? 'selected' : '' ?>>Caja</option>
                                                <option value="Unitaria" <?php echo $resultado = isset($uniCant_new) == "Unitaria" ? 'selected' : '' ?>>Unitaria</option>
                                                <option value="Pallet" <?php echo $resultado = isset($uniCant_new) == "Pallet" ? 'selected' : '' ?>>Pallet</option>
                                                <option value="Bulto" <?php echo $resultado = isset($uniCant_new) == "Bulto" ? 'selected' : '' ?>>Bulto</option>
                                                <option value="Paquete" <?php echo $resultado = isset($uniCant_new) == "Paquete" ? 'selected' : '' ?>>Paquete</option>
                                                <option value="Huacal" <?php echo $resultado = isset($uniCant_new) == "Huacal" ? 'selected' : '' ?>>Huacal</option>
                                            </select>
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Peso</label><span style="color: red;">*</span>
                                            <input type="number" class="form-control" name="peso_new" value="<?php echo $peso_new = empty($peso_new) ? '' : $peso_new; ?>" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Unidad de Medida (Peso)</label><span style="color: red;">*</span>
                                            <select class="form-select" aria-label="Default select example" name="uniPeso_new" required>
                                                <option value="">Seleccione...</option>
                                                <option value="Gramo" <?php echo $resultado = isset($uniPeso_new) == "Gramo" ? 'selected' : '' ?>>Gramo</option>
                                                <option value="Libra" <?php echo $resultado = isset($uniPeso_new) == "Libra" ? 'selected' : '' ?>>Libra</option>
                                                <option value="KiloGramo" <?php echo $resultado = isset($uniPeso_new) == "KiloGramo" ? 'selected' : '' ?>>KiloGramo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Ancho</label>
                                            <input type="number" class="form-control" name="ancho_new" value="<?php echo $ancho_new = empty($ancho_new) ? '' : $ancho_new; ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Alto</label>
                                            <input type="number" class="form-control" name="alto_new" value="<?php echo $alto_new = empty($alto_new) ? '' : $alto_new; ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Largo</label>
                                            <input type="number" class="form-control" name="largo_new" value="<?php echo $largo_new = empty($largo_new) ? '' : $largo_new; ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Unidad de Medida (Dimensiones)</label>
                                            <select class="form-select" aria-label="Default select example" name="uniDimen_new">
                                                <option value="">Seleccione...</option>
                                                <option value="MiliMetros" <?php echo $resultado = isset($uniDimen_new) == "MiliMetros" ? 'selected' : '' ?>>MiliMetros</option>
                                                <option value="CentiMetros" <?php echo $resultado = isset($uniDimen_new) == "CentiMetros" ? 'selected' : '' ?>>CentiMetros</option>
                                                <option value="Metros" <?php echo $resultado = isset($uniDimen_new) == "Metros" ? 'selected' : '' ?>>Metros</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col">
                                            <label class="form-label">Modelo</label>
                                            <input type="text" class="form-control" name="modelo_new" value="<?php echo $modelo_new = empty($modelo_new) ? '' : $modelo_new; ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Serial</label>
                                            <input type="text" class="form-control" name="serial_new" value="<?php echo $serial_new = empty($serial_new) ? '' : $serial_new; ?>">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Lote</label>
                                            <input type="text" class="form-control" name="lote_new" value="<?php echo $lote_new = empty($lote_new) ? '' : $lote_new; ?>">
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Marca</label>
                                            <input type="text" class="form-control" name="marca_new" value="<?php echo $marca_new = empty($marca_new) ? '' : $marca_new; ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Rotación</label>
                                            <select class="form-select" aria-label="Default select example" name="rotacion_new" value="<?php echo $rotacion_new = empty($rotacion_new) ? '' : $rotacion_new; ?>">
                                                <option value="">Seleccione..</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Días de aviso de vencimiento</label>
                                            <input type="number" class="form-control" name="diasAviso_new" value="<?php echo $diasAviso_new = empty($diasAviso_new) ? '' : $diasAviso_new; ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Descripción</label>
                                            <input type="text" class="form-control" name="descripcion_new" value="<?php echo $descripcion_new = empty($descripcion_new) ? '' : $descripcion_new; ?>">
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Precio</label>
                                            <input type="number" class="form-control" name="precio_new" value="<?php echo $precio_new = empty($precio_new) ? '' : $precio_new; ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Fecha de Vencimiento</label>
                                            <input type="date" class="form-control" name="fechaVenc_new" min="<?php echo date('Y-m-d') ?>" value="<?php echo $fechaVenc_new = empty($fechaVenc_new) ? '' : $fechaVenc_new; ?>">
                                        </div>

                                        <div class="col">
                                            <label class="form-label">Número de Contenedor</label>
                                            <input type="number" class="form-control" name="nContenedor_new" value="<?php echo $nContenedor_new = empty($nContenedor_new) ? '' : $nContenedor_new; ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="imagen_1" class="form-label">Agregar Imagen 1</label>
                                            <input type="file" class="form-control" name="imagen_1" size="30" type="file">
                                        </div>
                                        <div class="col-6">
                                            <label for="imagen_2" class="form-label">Agregar Imagen 2</label>
                                            <input type="file" class="form-control" name="imagen_2" size="30" type="file">
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row justify-content-center align-items-center mb-1">
                                        <div class="col">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" name="product_new" class="btn btn-primary" value="product_new"> Registrar </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Modals -->
                    <?php include_once '../assets/in/modals.php'; ?>
                </div>
            </main>
        </div>
    </div>

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

        // Fin de la función

        // var myVar = setInterval(myTimer, 1000);
        // var myVar1 = setInterval(myTimer1, 1000);
        // var oldID = "";
        // clearInterval(myVar1);

        // function myTimer() {
        //     var getID = document.getElementById("getUID").innerHTML;
        //     oldID = getID;
        //     if (getID != "") {
        //         myVar1 = setInterval(myTimer1, 500);
        //         showUser(getID);
        //         clearInterval(myVar);
        //     }
        // }

        // function myTimer1() {
        //     var getID = document.getElementById("getUID").innerHTML;
        //     if (oldID != getID) {
        //         myVar = setInterval(myTimer, 500);
        //         clearInterval(myVar1);
        //     }
        // }

        function showUser(str) {
            if (str == "") {
                document.getElementById("show_user_data").innerHTML = "";
                return;
            } else {
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else {
                    // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("show_user_data").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "read tag user data.php?id=" + str, true);
                xmlhttp.send();
            }
        }

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

    <!-- <?php $Write = "<?php $" . "UIDresult='" . "" . "'; " . " ?>";

            file_put_contents('UIDContainer.php', $Write); ?> -->

</body>

</html>