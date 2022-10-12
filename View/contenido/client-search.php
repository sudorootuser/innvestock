<!DOCTYPE html>
<html lang="en">
<?php
include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

include '../../Model/config.php';
include '../../Controller/client_class.php';
$class = new clientClass;
$tabla = "cliente";
if (isset($_POST['url'])) {
    unset($_SESSION['url']);
    $_SESSION['url'] = $_POST['url'];
}
$actionUrl = $_SESSION['url'];

include_once '../assets/in/returns.php';
$client_key = $class->searchTableDetails('cliente', $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3); ?>

<body class="sb-nav-fixed">
    <?php include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="mb-4">
                        <div class="row pt-4">
                            <div class="col-sm-4">
                                <h1 class="">Escoger Cliente</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <?php if ($_SESSION['url'] == "enlisted") {
                                        echo "<li class='breadcrumb-item'><a href=" . $_SESSION['url'] . '-list.php' . ">Pre-Ingresos</a></li> ";
                                    } ?>
                                    <?php if ($_SESSION['url'] == "enlistedDs") {
                                        echo "<li class='breadcrumb-item'><a href=" . $_SESSION['url'] . '-list.php' . ">Alistados</a></li> ";
                                    } ?>
                                    <?php if ($_SESSION['url'] == "reception") {
                                        echo "<li class='breadcrumb-item'><a href=" . $_SESSION['url'] . '-list.php' . ">Ingresos</a></li> ";
                                    } ?>
                                    <li class="breadcrumb-item active">Escoger Cliente</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row">
                            <div class="col-sm-2">
                                <?php if ($_SESSION['usuario_Rol'] == 1 or $_SESSION['usuario_Rol'] == 2) { ?>
                                    <a class="btn btn-success" style="width:100%;" href="client-new.php" role="button">Crear cliente </a>
                                <?php } ?>
                            </div>
                            <div class="col-sm-10">
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
                        <div class="card-body ">

                            <!-- Tabla de contenido -->
                            <div class="card-body table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">N. consecutivo</th>
                                            <th scope="col">Nombre / Razón Social</th>
                                            <th scope="col">Apellido</th>
                                            <th scope="col">Tipo de Identificación</th>
                                            <th scope="col">N-Identificación</th>
                                            <th scope="col">Digito Verificación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($client_key) {
                                            foreach ($client_key as $row) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $row['cliente_consecutivo'] ?></td>
                                                    <td><?php echo $row['cliente_nombre'] ?></td>
                                                    <td><?php echo $row['cliente_apellido'] ?></td>
                                                    <td><?php echo $row['cliente_tpId'] ?></td>
                                                    <td><?php echo $row['cliente_nDocument']; ?></td>
                                                    <td><?php echo $row['cliente_dv']; ?></td>
                                                    <form action="<?php echo $_SESSION['url'] . '-new.php' ?>" method="post">
                                                        <td>
                                                            <input type="hidden" name="id_key" value="<?php echo $row['idCliente']; ?>">
                                                            <button class="btn btn-success" type="submit" name="id_client" value="id_client"><i class="fa-solid fa-check"></i></button>
                                                        </td>
                                                    </form>
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

                            </div>
                        </div>
                    </div>
                    <!-- Modals -->
                    <?php include_once '../assets/in/modals.php'; ?>
            </main>


        </div>
    </div>

    <!-- Scrypt de funcionalidades -->
    <script>
        if (window.history.replaceState) { // verificamos disponibilidad
            window.history.replaceState(null, null, window.location.href);
        }
        // Función para obtener los datos del usuario y mostrarlos en el modal
        function GetUserDetails(id, tabla) {
            // Add User ID to the hidden field for furture usage
            $("#hidden_user_id").val(id);
            $.post("../../Controller/modal_script.php", {
                    idT: id,
                    tablaT: tabla
                },
                function(data, status) {
                    // PARSE json data
                    var user = JSON.parse(data);

                    console.log('data', data);
                    // Assing existing values to the modal popup fields
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
            // Abrir modal popup
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

    <!-- Fin del Script -->
</body>



</html>