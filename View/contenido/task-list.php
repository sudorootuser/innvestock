<!DOCTYPE html>
<html lang="en">
<?php
include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Instanciamos una nueva clase de cliente
$url = "task-list.php";
$tabla = "tarea";

// Total de la páginación x vista
include_once '../assets/in/returns.php';
include_once '../../Controller/product_class.php';

// Se instancia una nueva clase
$productClass = new productClass();

// Función para traer el total de la tabla consultada
$data = $productClass->blockDetails($tabla, $start, $Tpages, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3); ?>


<body class="sb-nav-fixed">

    <?php include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!--  -->
                    <div class="mb-4">
                        <div class="row mt-4 pt-4">
                            <div class="col-sm-2">
                                <h1 class="">Tareas</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Tareas</li>
                                </ol>
                            </div>
                            <?php if ($_SESSION['usuario_Rol'] != 4) { ?>
                                <div class="col-sm-2 mt-4">
                                    <a href="product-block.php" role="button"><i class="bi bi-lock btn btn-danger"></i></a>
                                    <a class="ms-2" href="product-block.php?unL=<?php echo encrypt_decrypt('encrypt', "up"); ?>" role="button"><i class="bi bi-unlock btn btn-success"></i></a>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="card mb-4 ">
                        <div class="card-header">
                            <div class="row">
                                <?php if ($_SESSION['usuario_Rol'] != 4) {
                                    if (count($data) >= 1) { ?>
                                        <div class="col-sm-1">
                                            <!-- <a class="btn btn-outline-success bi bi-file-earmark-excel" style="width:100%;"></a> -->
                                            <a class="btn btn-outline-success bi bi-file-earmark-excel" href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'tarea') . '&id=' ?>"></a>

                                        </div>
                                <?php }
                                } ?>
                                <div class="col-sm-11">
                                    <form class="d-flex" role="search" method="POST" action="">
                                        <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                                            <option value="tarea_consecutivo" <?php echo $resultado = $campo_1 == "tarea_consecutivo" ? "selected" : ''; ?>>Consecutivo Tarea</option>
                                            <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre Producto</option>
                                            <option value="tarea_prioridad" <?php echo $resultado = $campo_1 == "tarea_prioridad" ? "selected" : ''; ?>>Prioridad</option>
                                            <option value="tarea_idEntrada" <?php echo $resultado = $campo_1 == "tarea_idEntrada" ? "selected" : ''; ?>>Ingreso</option>
                                            <option value="tarea_idDespacho" <?php echo $resultado = $campo_1 == "tarea_idDespacho" ? "selected" : ''; ?>>Despacho</option>
                                            <option value="tarea_estado" <?php echo $resultado = $campo_1 == "tarea_estado" ? "selected" : ''; ?>>Estado</option>
                                            <option value="tarea_descripCorta" <?php echo $resultado = $campo_1 == "tarea_descripCorta" ? "selected" : ''; ?>>Descripcion Corta</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_1" type="search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_2" style="margin-right:8px;">
                                            <option value="producto_nombre" <?php echo $resultado = $campo_2 == "producto_nombre" ? "selected" : ''; ?>>Nombre Producto</option>
                                            <option value="tarea_consecutivo" <?php echo $resultado = $campo_2 == "tarea_consecutivo" ? "selected" : ''; ?>>Consecutivo Tarea</option>
                                            <option value="tarea_prioridad" <?php echo $resultado = $campo_2 == "tarea_prioridad" ? "selected" : ''; ?>>Prioridad</option>
                                            <option value="tarea_estado" <?php echo $resultado = $campo_2 == "tarea_estado" ? "selected" : ''; ?>>Estado</option>
                                            <option value="tarea_idEntrada" <?php echo $resultado = $campo_2 == "tarea_idEntrada" ? "selected" : ''; ?>>Ingreso</option>
                                            <option value="tarea_idDespacho" <?php echo $resultado = $campo_2 == "tarea_idDespacho" ? "selected" : ''; ?>>Despacho</option>
                                            <option value="tarea_descripCorta" <?php echo $resultado = $campo_2 == "tarea_descripCorta" ? "selected" : ''; ?>>Descripcion Corta</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_2" type="search" value="<?php echo $resultado = empty($_POST['bus_2']) ? '' : $_POST['bus_2']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_3" style="margin-right:8px;">
                                            <option value="tarea_prioridad" <?php echo $resultado = $campo_3 == "tarea_prioridad" ? "selected" : ''; ?>>Prioridad</option>
                                            <option value="producto_nombre" <?php echo $resultado = $campo_3 == "producto_nombre" ? "selected" : ''; ?>>Nombre Producto</option>
                                            <option value="tarea_consecutivo" <?php echo $resultado = $campo_3 == "tarea_consecutivo" ? "selected" : ''; ?>>Consecutivo Tarea</option>
                                            <option value="tarea_idEntrada" <?php echo $resultado = $campo_3 == "tarea_idEntrada" ? "selected" : ''; ?>>Ingreso</option>
                                            <option value="tarea_idDespacho" <?php echo $resultado = $campo_3 == "tarea_idDespacho" ? "selected" : ''; ?>>Despacho</option>
                                            <option value="tarea_estado" <?php echo $resultado = $campo_3 == "tarea_estado" ? "selected" : ''; ?>>Estado</option>
                                            <option value="tarea_descripCorta" <?php echo $resultado = $campo_3 == "tarea_descripCorta" ? "selected" : ''; ?>>Descripcion Corta</option>
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
                                        <th scope="col">#Consecutivo</th>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Prioridad</th>
                                        <th scope="col">Bloqueados</th>
                                        <th scope="col">Origen</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($data) {
                                        foreach ($data as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['tarea_consecutivo'] ?></td>
                                                <td><?php echo $row['producto_codigo'] ?></td>
                                                <td><?php echo $row['producto_nombre'] ?></td>
                                                <td><?php echo $row['tarea_prioridad'] ?></td>
                                                <td><?php echo $row['tarea_novedad']; ?></td>
                                                <?php if (!empty($row['tarea_idEntrada'])) { ?>
                                                    <td><?php echo "ING-" . $row['tarea_idEntrada']; ?></td>
                                                <?php } else if (!empty($row['tarea_idDespacho'])) { ?>
                                                    <td><?php echo "DS-" . $row['tarea_idDespacho']; ?></td>
                                                <?php } else { ?>
                                                    <td></td>
                                                <?php } ?>
                                                <!-- Button trigger modal -->
                                                <td>
                                                    <a onclick="GetProductDetails(<?php echo $row['idTarea']; ?> , 'tarea')">
                                                        <i class="bi bi-eye-fill btn btn-light"></i>
                                                    </a>
                                                </td>

                                            </tr>

                                        <?php $count++;
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

    <!-- Script de funcionalidades -->
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
                    $("#tarea_codigo_see").val(user.producto_codigo);
                    $("#tarea_nombre_see").val(user.producto_nombre);
                    $("#tarea_idCliente_see").val("CL-" + user.idCliente + "  " + user.cliente_nombre);
                    $("#tarea_consecutivo_see").val(user.producto_consecutivo);
                    $("#tarea_prioridad_see").val(user.tarea_prioridad);
                    $("#tarea_cantidad_see").val(user.tarea_novedad);
                    $("#tarea_descripcion_see").val(user.tarea_descripCorta);
                    $("#tarea_usuario_see").val(user.usuario_nombre + "  " + user.usuario_apellido);
                    if (user.tarea_idDespacho != null) {
                        $("#tarea_origen_see").val("DS-" + user.tarea_idDespacho);
                    } else if (user.tarea_idEntrada) {
                        $("#tarea_origen_see").val("ING-" + user.tarea_idEntrada);
                    }

                }
            );
            // Abrir modal popup
            $("#tarea_modal").modal("show");
        }

        // Función para preguntar si desea eliminar el cliente y alerta de eliminado con éxito
        $('.btn_table_del').on("click", function(e) {

            e.preventDefault();

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