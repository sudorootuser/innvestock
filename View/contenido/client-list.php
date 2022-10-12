<!DOCTYPE html>
<html lang="en">
<?php
// Se incluyen los estilos y Boostrap y Js
include_once "../assets/in/Head.php";

// Se controla los permisos de ingreso a traves de la sesión
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

// Instanciamos una nueva clase de cliente
$url = "client-list.php";
$tabla = "cliente";

// Total de la páginación x vista
include_once '../assets/in/returns.php';
include '../../Controller/client_class.php';
$class = new clientClass;

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
                                <h1 class="">Clientes</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Clientes</li>
                                </ol>
                            </div>
                            <div class="col-sm-1 mt-4">
                                <a class="btn btn-success " href="client-new.php" role="button"><i class="bi bi-plus-lg btn-success"></i></a>
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
                                            <a class="btn btn-outline-success bi bi-file-earmark-excel" style="width:100%;" href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'cliente') . '&id=' ?>"></a>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-sm-1">
                                            <button class="btn btn-outline-success bi bi-file-earmark-excel" style="width:100%;" disabled></button>
                                        </div>
                                <?php
                                    }
                                } ?>
                                <div class="col-sm-11">
                                    <form class="d-flex" role="search" method="POST" action="">
                                        <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                                            <option value="cliente_tpId" <?php echo $resultado = $campo_1 == "cliente_tpId" ? "selected" : ''; ?>>Tipo Identificación</option>
                                            <option value="cliente_nDocument" <?php echo $resultado = $campo_1 == "cliente_nDocument" ? "selected" : ''; ?>># Identificación</option>
                                            <option value="cliente_nombre" <?php echo $resultado = $campo_1 == "cliente_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="cliente_apellido" <?php echo $resultado = $campo_1 == "cliente_apellido" ? "selected" : ''; ?>>Apellido</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_1" type="search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_2" style="margin-right:8px;">
                                            <option value="cliente_nDocument" <?php echo $resultado = $campo_2 == "cliente_nDocument" ? "selected" : ''; ?>># Identificación</option>
                                            <option value="cliente_tpId" <?php echo $resultado = $campo_2 == "cliente_tpId" ? "selected" : ''; ?>>Tipo Identificación</option>
                                            <option value="cliente_nombre" <?php echo $resultado = $campo_2 == "cliente_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="cliente_apellido" <?php echo $resultado = $campo_2 == "cliente_apellido" ? "selected" : ''; ?>>Apellido</option>
                                        </select>
                                        <input class="form-control me-2" name="bus_2" type="search" value="<?php echo $resultado = empty($_POST['bus_2']) ? '' : $_POST['bus_2']; ?>" aria-label="Search">

                                        <select class="form-select" aria-label="Default select example" name="campo_3" style="margin-right:8px;">
                                            <option value="cliente_nombre" <?php echo $resultado = $campo_3 == "cliente_nombre" ? "selected" : ''; ?>>Nombre</option>
                                            <option value="cliente_tpId" <?php echo $resultado = $campo_3 == "cliente_tpId" ? "selected" : ''; ?>>Tipo Identificación</option>
                                            <option value="cliente_nDocument" <?php echo $resultado = $campo_3 == "cliente_nDocument" ? "selected" : ''; ?>># Identificación</option>
                                            <option value="cliente_apellido" <?php echo $resultado = $campo_3 == "cliente_apellido" ? "selected" : ''; ?>>Apellido</option>
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
                                        <th scope="col"></th>
                                        <th scope="col">Nombre / Razón Social</th>
                                        <th scope="col">Apellido</th>
                                        <th scope="col">Tipo de Identificación</th>
                                        <th scope="col"># Identificación</th>
                                        <?php if ($_SESSION['usuario_Rol'] == 1) { ?>

                                            <th scope="col">Estado</th>
                                        <?php } ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($data) {
                                        foreach ($data as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['cliente_consecutivo'] ?></td>
                                                <td><?php echo $row['cliente_nombre'] ?></td>
                                                <td><?php echo $row['cliente_apellido'] ?></td>
                                                <td><?php echo $row['cliente_tpId'] ?></td>
                                                <td><?php echo $row['cliente_nDocument']; ?></td>
                                                <?php if ($_SESSION['usuario_Rol'] == 1) { ?>
                                                    <td><?php echo $row['cliente_estado']; ?></td>

                                                <?php } ?>
                                                <td>
                                                    <a onclick="GetUserDetails(<?php echo $row['idCliente']; ?> , 'cliente')">
                                                        <i class="bi bi-eye-fill btn btn-light"></i>
                                                    </a>
                                                </td>
                                                <?php if ($_SESSION['usuario_Rol'] == 1) {

                                                    if ($row['cliente_estado'] == "activo") {
                                                        if ($_SESSION['usuario_Rol'] != 4) { ?>
                                                            <td>
                                                                <div class="row mb-1">
                                                                    <div class="col">

                                                                        <a href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'cliente') . '&id=' . $row['idCliente'] ?>" class="btn btn-success bi bi-file-earmark-excel">
                                                                        </a>


                                                                        <a href="./client-new.php?upS=<?php echo encrypt_decrypt('encrypt', "up"); ?>&idUp=<?php echo encrypt_decrypt('encrypt', $row['idCliente']); ?>" class="bi bi-pencil-square btn btn-primary">
                                                                        </a>



                                                                        <i href="../../Ajax/ajax_client.php?del=deleteClient&id=<?php echo encrypt_decrypt('encrypt', $row['idCliente']); ?>" class="bi bi-trash3 btn btn-danger btn_table_del"></i>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <?php
                                                        }
                                                    } else if ($row['cliente_estado'] == "inactivo") {
                                                        if ($_SESSION['usuario_Rol'] != 4) { ?>
                                                            <td>
                                                                <div class="row mb-1">
                                                                    <div class="col">
                                                                        <a href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'cliente') . '&id=' . $row['idCliente'] ?>" class="btn btn-success bi bi-file-earmark-excel">
                                                                        </a>


                                                                        <a href="./client-new.php?upS=<?php echo encrypt_decrypt('encrypt', "up"); ?>&idUp=<?php echo encrypt_decrypt('encrypt', $row['idCliente']); ?>" class="bi bi-pencil-square btn btn-primary">
                                                                        </a>


                                                                        <button class="bi bi-trash3 btn btn-danger" disabled></button>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                    <?php
                                                        }
                                                    }
                                                } else { ?>

                                                    <?php
                                                    if ($_SESSION['usuario_Rol'] != 4) { ?>
                                                        <td>
                                                            <div class="row mb-1">
                                                                <div class="col">

                                                                    <a href="./excel.php?tb=<?php echo encrypt_decrypt('encrypt', 'cliente') . '&id=' . $row['idCliente'] ?>" class="btn btn-success bi bi-file-earmark-excel">
                                                                    </a>

                                                                    <a href="./client-new.php?upS=<?php echo encrypt_decrypt('encrypt', "up"); ?>&idUp=<?php echo encrypt_decrypt('encrypt', $row['idCliente']); ?>" class="bi bi-pencil-square btn btn-primary">
                                                                    </a>

                                                                    <i href="../../Ajax/ajax_client.php?del=deleteClient&id=<?php echo encrypt_decrypt('encrypt', $row['idCliente']); ?>" class="bi bi-trash3 btn btn-danger btn_table_del"></i>

                                                                </div>
                                                            </div>
                                                        </td>
                                            <?php
                                                    }
                                                    $count++;
                                                }

                                                echo "</tr>";
                                            } ?>

                                        <?php } else { ?>

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

                    // console.log('data', data);
                    $("#tpId_see").val(user.cliente_tpId);
                    $("#nDocument_see").val(user.cliente_nDocument);
                    $("#dv_see").val(user.cliente_dv);
                    $("#estado_see").val(user.cliente_estado);
                    $("#nombre_see").val(user.cliente_nombre);
                    $("#apellido_see").val(user.cliente_apellido);
                    $("#actEco_see").val(user.cliente_actEco);
                    $("#direccion_see").val(user.cliente_direccion);
                    $("#telefono_see").val(user.cliente_telefono);
                    $("#ciudad_see").val(user.ciudad_nombre);
                    $("#tpCliente_see").val(user.cliente_tpCliente);
                }
            );
            // Abrir modal
            $("#update_client_modal").modal("show");
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
</body>

</html>