<!DOCTYPE html>
<html lang="en">
<?php
include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

$url = "product-list.php";
$tabla = "producto";

// Total de la páginación x vista
include_once '../assets/in/returns.php';
include '../../Controller/product_class.php';

// Instanciamos una nueva clase de cliente
$class = new productClass;

// Función para traer el total de la tabla consultada
$data = $class->tableDetails($tabla, $start, $Tpages, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3); ?>

<body class="sb-nav-fixed">

    <?php include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>

        <div id="layoutSidenav_content">

            <main style="margin-bottom: 54px;">
                <div class="container-fluid px-4">
                    <div class="mb-4  ">
                        <div class="row mt-4 pt-4">
                            <div class="col-sm-2 ">
                                <h1 class="">Productos</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Productos</li>
                                </ol>
                            </div>
                            <?php if ($_SESSION['usuario_Rol'] != 4) { ?>
                                <div class="col-sm-1 mt-4">
                                    <a class="ml-5" href="product-new.php" role="button" role="button"><i class="bi bi-plus-lg btn btn-success"></i></a>
                                </div>
                            <?php } ?>
                            <div class="col-sm-1 mt-4">
                                <a class="ml-5" href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'producto') . '&id=' . 'img' ?>" role="button" role="button"><i class="bi bi-card-image btn btn-dark"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 ">
                        <div class="card-header">
                            <div class="row">
                                <!-- Excel -->
                                <?php if (count($data) > 0) { ?>
                                    <div class="col-sm-1">
                                        <a class="btn btn-outline-success bi bi-file-earmark-excel" style="width:100%;" href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'producto') . '&id=' ?>">
                                        </a>

                                    </div>
                                <?php } else { ?>
                                    <div class="col-sm-1">
                                        <button class="btn btn-outline-success bi bi-file-earmark-excel" style="width:100%;" disabled></button>
                                    </div>
                                <?php } ?>
                                <!-- Formulario de busqueda -->
                                <div class="col-sm-11">
                                    <form class="d-flex" role="search" method="POST" action="">
                                        <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                                            <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Código / Referencia</option>
                                            <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="cliente_nombre" <?php echo $resultado = $campo_1 == "cliente_nombre" ? "selected" : ''; ?>>Cliente</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_1" type="search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_2" style="margin-right:8px;">
                                            <option value="producto_nombre" <?php echo $resultado = $campo_2 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="producto_codigo" <?php echo $resultado = $campo_2 == "producto_codigo" ? "selected" : ''; ?>>Código / Referencia</option>
                                            <option value="cliente_nombre" <?php echo $resultado = $campo_2 == "cliente_nombre" ? "selected" : ''; ?>>Cliente</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_2" type="search" value="<?php echo $resultado = empty($_POST['bus_2']) ? '' : $_POST['bus_2']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_3" style="margin-right:8px;">
                                            <option value="cliente_nombre" <?php echo $resultado = $campo_3 == "cliente_nombre" ? "selected" : ''; ?>>Cliente</option>
                                            <option value="producto_codigo" <?php echo $resultado = $campo_3 == "producto_codigo" ? "selected" : ''; ?>>Código / Referencia</option>
                                            <option value="producto_nombre" <?php echo $resultado = $campo_3 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_3" type="search" value="<?php echo $resultado = empty($_POST['bus_3']) ? '' : $_POST['bus_3']; ?>" aria-label="Search">

                                        <button class="btn btn-outline-primary" type="submit">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">N. Consecutivo</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Peso</th>
                                        <th scope="col">Cantidad Disponible</th>
                                        <th scope="col">En Alistado</th>
                                        <th scope="col">Bloqueados</th>
                                        <th scope="col">Cantidad Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($data) {
                                        foreach ($data as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['producto_consecutivo'] ?></td>
                                                <td><?php echo $row['producto_codigo'] ?></td>
                                                <td><?php echo $row['producto_nombre'] ?></td>
                                                <td><?php echo $row['producto_peso'] ?> Kg</td>
                                                <td><?php echo $row['producto_bodega_cantidad'] . " " . $row['producto_uniCant']; ?>/s</td>
                                                <td><?php echo $row['producto_bodega_cantidadAlis']; ?></td>
                                                <td><?php echo $row['producto_bodega_cantidadBlock']; ?></td>
                                                <td><?php echo ($row['producto_bodega_cantidad'] + $row['producto_bodega_cantidadAlis'] + $row['producto_bodega_cantidadBlock']); ?></td>

                                                <td>
                                                    <a onclick="GetImgProduct(<?php echo $row['idProducto']; ?> , 'imagen')">
                                                        <i class="bi bi-card-image btn btn-light"></i>
                                                    </a>

                                                    <a onclick="GetProductDetails(<?php echo $row['idProducto']; ?> , 'producto')" class="">
                                                        <i class="bi bi-eye-fill btn btn-light"></i>
                                                    </a>
                                                </td>
                                                <?php if ($_SESSION['usuario_Rol'] == 1) { ?>

                                                    <?php
                                                    if ($row['producto_bodega_estado'] == "activo") {
                                                        if ($_SESSION['usuario_Rol'] != 4) { ?>
                                                            <td>
                                                                <a href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'producto') . '&id=' . $row['idProducto'] ?>" class="btn btn-success bi bi-file-earmark-excel">
                                                                </a>

                                                                <a href="./product-new.php?upS=<?php echo encrypt_decrypt('encrypt', "up"); ?>&idUp=<?php echo encrypt_decrypt('encrypt', $row['idProducto']); ?>"><i class="bi bi-pencil-square btn btn-primary"></i></a>

                                                                <i href="../../Ajax/ajax_product.php?del=deleteProduct&id=<?php echo encrypt_decrypt('encrypt', $row['idProducto']); ?>" class="bi bi-trash3 btn btn-danger btn_table_del"></i>
                                                            </td>
                                                        <?php }
                                                    }
                                                    if ($row['producto_bodega_estado'] == "inactivo") { ?>
                                                        <td>
                                                            <a href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'producto') . '&id=' . $row['idProducto'] ?>" class="btn btn-success bi bi-file-earmark-excel">
                                                            </a>

                                                            <?php if ($_SESSION['usuario_Rol'] != 4) { ?>

                                                                <a href="./product-new.php?upS=<?php echo encrypt_decrypt('encrypt', "up"); ?>&idUp=<?php echo encrypt_decrypt('encrypt', $row['idProducto']); ?>"><i class="bi bi-pencil-square btn btn-primary"></i></a>

                                                                <button disabled class="bi bi-trash3 btn btn-danger"></button>
                                                        </td>
                                            <?php }
                                                        }
                                                    } ?>
                                            </tr>

                                        <?php

                                            $count++;
                                        }
                                    } else { ?>
                                        <tr class="text-center">
                                            <td colspan="9">No hay registros en el sistema</td>
                                        </tr>
                                    <?php  } ?>
                                </tbody>
                            </table>
                            <!-- Paginación -->
                            <?php
                            if ($count_d > 0) {
                                include_once '../assets/in/pagination.php';
                            } ?>
                        </div>
                    </div>
                </div>
                <!-- Modals -->
                <?php include_once '../assets/in/modals.php'; ?>
            </main>

        </div>
    </div>

    <!-- Scrypt de funcionalidades -->
    <?php include_once '../assets/in/alerts_answer.php'; ?>
    <script>
        // Función para obtener los datos del usuario y mostrarlos en el modal
        function GetProductDetails(id, tabla) {
            // Add User ID to the hidden field for furture usage
            $("#hidden_user_id").val(id);
            $.post("../../Controller/modal_script.php", {
                    idT: id,
                    tablaT: tabla
                },
                function(data, status) {
                    // PARSE json data
                    var user = JSON.parse(data);

                    // Assing existing values to the modal popup fields
                    $("#producto_codigo_see").val(user.producto_codigo);
                    $("#producto_nombre_see").val(user.producto_nombre);
                    $("#producto_idCliente_see").val("CL-" + user.producto_idCliente + "  " + user.cliente_nombre);
                    $("#producto_minimo_see").val(user.producto_minimo);
                    $("#producto_maximo_see").val(user.producto_maximo);
                    if (user.producto_bodega_cantidad == 0) {
                        $("#producto_alerta_see").removeClass("alerta")
                        $("#producto_alerta_see").removeClass("bajo")
                        $("#producto_alerta_see").removeClass("pedido")
                        $("#producto_alerta_see").addClass("pedido")
                        $("#producto_alerta_see").val("PEDIDO");

                    } else if (user.producto_bodega_cantidad <= user.producto_minimo) {
                        $("#producto_alerta_see").removeClass("alerta")
                        $("#producto_alerta_see").removeClass("bajo")
                        $("#producto_alerta_see").removeClass("pedido")
                        $("#producto_alerta_see").addClass("bajo")
                        $("#producto_alerta_see").val("BAJO");

                    } else {
                        $("#producto_alerta_see").removeClass("alerta")
                        $("#producto_alerta_see").removeClass("bajo")
                        $("#producto_alerta_see").removeClass("pedido")
                        $("#producto_alerta_see").addClass("alerta")
                        $("#producto_alerta_see").val("NORMAL");
                    }
                    $("#producto_cantidad_see").val(user.producto_bodega_cantidad + " " + user.producto_uniCant + "/s");
                    // $("#producto_uniCant_see").val(user.producto_uniCant);
                    $("#producto_peso_see").val(user.producto_peso + " Kg");
                    // $("#producto_uniPeso_see").val(user.producto_uniPeso);
                    $("#producto_ancho_see").val(user.producto_ancho + " " + user.producto_uniDimen);
                    $("#producto_alto_see").val(user.producto_alto + " " + user.producto_uniDimen);
                    $("#producto_largo_see").val(user.producto_largo + " " + user.producto_uniDimen);
                    // $("#producto_uniDimen_see").val(user.producto_uniDimen);
                    $("#producto_modelo_see").val(user.producto_modelo);
                    $("#producto_serial_see").val(user.producto_serial);
                    $("#producto_lote_see").val(user.producto_lote);
                    $("#producto_marca_see").val(user.producto_marca);
                    $("#producto_rotacion_see").val(user.producto_rotacion);
                    $("#producto_diasAviso_see").val(user.producto_diasAviso);
                    $("#producto_descripcion_see").val(user.producto_descripcion);
                    $("#producto_precio_see").val(user.producto_precio);
                    $("#producto_fechaVenc_see").val(user.producto_fechaVenc);
                    $("#producto_nContenedor_see").val(user.producto_nContenedor);
                    $("#producto_cantidadAlis_see").val(user.producto_bodega_cantidadAlis);
                    $("#producto_RFID_see").val(user.producto_RFID);
                    $("#producto_ubicacion_see").val(user.producto_bodega_ubicacion);
                    $("#producto_cantidadBlock_see").val(user.producto_bodega_cantidadBlock);
                    $("#producto_cantidadAlisPeso_see").val(user.producto_bodega_cantidadAlis * user.producto_peso + " Kg");
                    $("#producto_cantidadBlockPeso_see").val(user.producto_bodega_cantidadBlock * user.producto_peso + " Kg");
                    $("#producto_pesoTotal_see").val(user.producto_bodega_cantidad * user.producto_peso + " Kg");
                    $("#producto_pesoSubTotal_see").val((parseInt(user.producto_bodega_cantidadBlock) + parseInt(user.producto_bodega_cantidad) + parseInt(user.producto_bodega_cantidadAlis)) * user.producto_peso + " Kg");



                }
            );
            // Abrir modal popup
            $("#update_product_modal").modal("show");
        }

        // Función para obtener los datos del usuario y mostrarlos en el modal
        function GetImgProduct(id, tabla) {

            // Add User ID to the hidden field for furture usage
            $("#hidden_user_id").val(id);
            $.post("../../Controller/modal_script.php", {
                    idT: id,
                    tablaT: tabla
                },
                function(data) {
                    // PARSE json data

                    var user = JSON.parse(data);

                    // // Assing existing values to the modal popup fields
                    if (user.status != 200) {

                        let name_img = [];

                        user.forEach(function(user) {
                            name_img.push(user[1]);
                        });
                        if (name_img[0] != undefined) {
                            $("#IMG_1").html('<img style="width:100%; height:100%;" src="../assets/img/product/temp/' + name_img[0] + ' " class="card-img-top">');
                        } else {
                            $("#IMG_1").html('<span style="margin-top:calc(100% - 50%);">No hay imagenes registradas</span>');
                        }
                        if (name_img[1] != undefined) {
                            $("#IMG_2").html('<img style="width:100%; height:100%;" src="../assets/img/product/temp/' + name_img[1] + ' " class="card-img-top">');
                        } else {
                            $("#IMG_2").html('<span>No hay imagenes registradas</span>');
                        }

                    } else {
                        $("#IMG_1").html(' No hay imagenes registradas');
                        $("#IMG_2").html(' No hay imagenes registradas ');
                    }
                }
            );
            // Abrir modal popup
            $("#see_image").modal("show");
        }
        // Fin de la función

        // Alerta de eliminación
        const flashdata = $('.flash-data').data('flashdata')
        if (flashdata == 1) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'El cliente se ha eliminado con éxito',
                showConfirmButton: false,
                timer: 1000
            })
            setTimeout(function() {
                window.location.href = "<?php BASE_URL ?>product-list.php";
            }, 2000);
        }
        // Fin de la función

        // Función para preguntar si desea eliminar el cliente y alerta de eliminado con éxito
        $('.btn_table_del').on("click", function(e) {

            e.preventDefault();

            // console.log('ingresa');

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
    <!-- Fin del Script -->
</body>

</html>