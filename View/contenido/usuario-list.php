<!DOCTYPE html>
<html lang="en">
<?php
// Se incluyen los estilos y Boostrap y Js
include_once "../assets/in/Head.php";

// Se controla los permisos de ingreso a traves de la sesión
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Instanciamos una nueva clase de usuario
$url = "usuario-list.php";
$tabla = "usuario";

// Total de la páginación x vista
include_once '../assets/in/returns.php';
include '../../Controller/usuario_class.php';
$class = new usuarioClass;

// Función para traer el total de la tabla consultada
$data = $class->tableDetails($tabla, $start, $Tpages, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3); ?>

<body class="sb-nav-fixed">
    <?php include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <!-- Inicio de formulario de busqueda -->
                    <div class="mb-4">
                        <div class="row mt-4 pt-4">
                            <div class="col-sm-2">
                                <h1>Usuarios</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">usuarios</li>
                                </ol>
                            </div>
                            <div class="col-sm-1 mt-4">
                                <a class="btn btn-success " href="usuario-new.php" role="button"><i class="bi bi-plus-lg btn-success"></i></a>
                            </div>

                        </div>
                    </div>

                    <!-- Inicio del contenido de la tabla -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row">
                                <?php if ($_SESSION['usuario_Rol'] != 4) {
                                    if (count($data) >= 1) { ?>
                                        <div class="col-sm-1">
                                            <!-- <a class="btn btn-outline-success bi bi-file-earmark-excel" href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'usuario') . '&id=' ?>"></a> -->
                                        </div>
                                <?php }
                                } ?>
                                <div class="col-sm-11">
                                    <form class="d-flex" role="search" method="POST" action="">
                                        <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                                            <option value="usuario_tDocument" <?php echo $resultado = $campo_1 == "usuario_tDocument" ? "selected" : ''; ?>>Tipo Identificación</option>
                                            <option value="usuario_documento" <?php echo $resultado = $campo_1 == "usuario_documento" ? "selected" : ''; ?>># Identificación</option>
                                            <option value="usuario_nombre" <?php echo $resultado = $campo_1 == "usuario_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="usuario_apellido" <?php echo $resultado = $campo_1 == "usuario_apellido" ? "selected" : ''; ?>>Apellido</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_1" type="search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_2" style="margin-right:8px;">
                                            <option value="usuario_documento" <?php echo $resultado = $campo_2 == "usuario_documento" ? "selected" : ''; ?>># Identificación</option>
                                            <option value="usuario_tDocument" <?php echo $resultado = $campo_2 == "usuario_tDocument" ? "selected" : ''; ?>>Tipo Identificación</option>
                                            <option value="usuario_nombre" <?php echo $resultado = $campo_2 == "usuario_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="usuario_apellido" <?php echo $resultado = $campo_2 == "usuario_apellido" ? "selected" : ''; ?>>Apellido</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_2" type="search" value="<?php echo $resultado = empty($_POST['bus_2']) ? '' : $_POST['bus_2']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_3" style="margin-right:8px;">
                                            <option value="usuario_nombre" <?php echo $resultado = $campo_3 == "usuario_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="usuario_tDocument" <?php echo $resultado = $campo_3 == "usuario_tDocument" ? "selected" : ''; ?>>Tipo Identificación</option>
                                            <option value="usuario_documento" <?php echo $resultado = $campo_3 == "usuario_documento" ? "selected" : ''; ?>># Identificación</option>
                                            <option value="usuario_apellido" <?php echo $resultado = $campo_3 == "usuario_apellido" ? "selected" : ''; ?>>Apellido</option>
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
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre: </th>
                                        <th scope="col">Apellido: </th>
                                        <th scope="col">Tipo de Identificación</th>
                                        <th scope="col"># Identificación</th>
                                        <th scope="col">Correo: </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($data) {
                                        foreach ($data as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['idUsuario']; ?></td>
                                                <td><?php echo $row['usuario_nombre'] ?></td>
                                                <td><?php echo $row['usuario_apellido'] ?></td>
                                                <td><?php echo $row['usuario_tDocument'] ?></td>
                                                <td><?php echo $row['usuario_documento']; ?></td>
                                                <td><?php echo $row['usuario_correo']; ?></td>
                                                <!-- Button trigger modal -->
                                                <td>
                                                    <a onclick="GetUserDetails(<?php echo $row['idUsuario']; ?> , 'usuario')">
                                                        <i class="bi bi-eye-fill btn btn-light"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <!-- <a href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'usuario') . '&id=' . $row['idUsuario'] ?>"> <i class="btn btn-success bi bi-file-earmark-excel" style="color:white;"></i>
                                                    </a> -->
                                                </td>
                                                <td><a href="./usuario-new.php?upS=<?php echo encrypt_decrypt('encrypt', "up"); ?>&idUp=<?php echo encrypt_decrypt('encrypt', $row['idUsuario']); ?>"><i class="bi bi-pencil-square btn btn-primary"></i></a></td>
                                                <td>

                                                    <i href="../../Ajax/ajax_usuario.php?del=deleteUser&id=<?php echo encrypt_decrypt('encrypt', $row['idUsuario']); ?>" class="bi bi-trash3 btn btn-danger btn_table_del"></i>
                                                </td>
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
            <!-- Footer -->
        </div>
    </div>

    <!-- Scrypt de funcionalidades -->
    <script>
        // Función para obtener los datos del usuario y mostrarlos en el modal
        function GetUserDetails(id, tabla) {
            $("#hidden_user_id").val(id);
            $.post("../../Controller/modal_script.php", {
                    idT: id,
                    tablaT: tabla
                },
                function(data, status) {
                    var user = JSON.parse(data);

                    $("#user_tpDocument").val(user.usuario_tDocument);
                    $("#user_document").val(user.usuario_documento);
                    $("#fecha_nacimiento").val(user.usuario_fecha);
                    $("#user_estado").val(user.usuario_estado);
                    $("#user_nombre").val(user.usuario_nombre);
                    $("#user_apellido").val(user.usuario_apellido);
                    $("#user_correo").val(user.usuario_correo);
                    $("#user_direccion").val(user.usuario_direccion);
                    $("#user_bodega").val(user.usuario_idBodega);
                    $("#user_rol").val(user.usuario_idRol);
                }
            );
            // Abrir modal
            $("#user_modal").modal("show");
        }


        // Función para preguntar si desea eliminar el usuario y alerta de eliminado con éxito
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
</body>

</html>