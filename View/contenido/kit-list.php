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
$url = "kit-list.php";
$tabla = "kit";

// Total de la páginación x vista
include_once '../assets/in/returns.php';
include '../../Controller/kit_class.php';

// Se intancia la clase
$class = new kitClass;

// Función para traer el total de la tabla consultada
unset($_SESSION['kitUp']);

$data = $class->kitDetails($tabla, $start, $Tpages, $bus_1, $bus_2, $bus_3, $campo_1, $campo_2, $campo_3); ?>

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
                        <div class="row mt-4 pt-4">
                            <div class="col-sm-2">
                                <h1 class="">Kits</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Kits</li>
                                </ol>
                            </div>

                            <div class="col-sm-1 mt-4">
                                <form action="client-search.php" method="post">
                                    <input type="text" name="url" value="kit" hidden>
                                    <button type="submit" class="bi bi-plus-lg btn btn-success"></button>
                                </form>
                            </div>

                            <div class="col-sm-9 mt-4">
                                <form class="d-flex" role="search" method="POST" action="">
                                    <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                                        <option value="kit_consecutivo" <?php echo $resultado = $campo_1 == "kit_consecutivo" ? "selected" : ''; ?>>Consecutivo Kit
                                        </option>
                                        <option value="kit_nombre" <?php echo $resultado = $campo_1 == "kit_nombre" ? "selected" : ''; ?>>Fecha de Entrada</option>
                                        <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo Producto</option>
                                        <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre Producto</option>
                                        <option value="producto_idCliente" <?php echo $resultado = $campo_1 == "producto_idCliente" ? "selected" : ''; ?>>Cliente</option>

                                    </select>
                                    <input class="form-control me-2" name="bus_1" type="search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>" aria-label="Search">
                                    <select class="form-select" aria-label="Default select example" name="campo_2" style="margin-right:8px;">
                                        <option value="kit_nombre" <?php echo $resultado = $campo_2 == "kit_nombre" ? "selected" : ''; ?>>Fecha de Entrada</option>
                                        <option value="kit_consecutivo" <?php echo $resultado = $campo_2 == "kit_consecutivo" ? "selected" : ''; ?>>Consecutivo Kit</option>
                                        <option value="producto_codigo" <?php echo $resultado = $campo_2 == "producto_codigo" ? "selected" : ''; ?>>Codigo Producto</option>
                                        <option value="producto_nombre" <?php echo $resultado = $campo_2 == "producto_nombre" ? "selected" : ''; ?>>Nombre Producto</option>
                                        <option value="producto_idCliente" <?php echo $resultado = $campo_2 == "producto_idCliente" ? "selected" : ''; ?>>Cliente</option>
                                    </select>
                                    <input class="form-control me-2" name="bus_2" type="search" value="<?php echo $resultado = empty($_POST['bus_2']) ? '' : $_POST['bus_2']; ?>" aria-label="Search">
                                    <select class="form-select" aria-label="Default select example" name="campo_3" style="margin-right:8px;">
                                        <option value="producto_codigo" <?php echo $resultado = $campo_3 == "producto_codigo" ? "selected" : ''; ?>>Codigo Producto</option>
                                        <option value="kit_consecutivo" <?php echo $resultado = $campo_3 == "kit_consecutivo" ? "selected" : ''; ?>>Consecutivo Kit</option>
                                        <option value="kit_nombre" <?php echo $resultado = $campo_3 == "kit_nombre" ? "selected" : ''; ?>>Fecha de Entrada</option>
                                        <option value="producto_nombre" <?php echo $resultado = $campo_3 == "producto_nombre" ? "selected" : ''; ?>>Nombre Producto</option>
                                        <option value="producto_idCliente" <?php echo $resultado = $campo_3 == "producto_idCliente" ? "selected" : ''; ?>>Cliente</option>
                                    </select>
                                    <input class="form-control me-2" name="bus_3" type="search" value="<?php echo $resultado = empty($_POST['bus_3']) ? '' : $_POST['bus_3']; ?>" aria-label="Search">

                                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div class="card mb-4 ">

                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabla de datos
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table">

                                <thead>
                                    <tr>
                                        <th scope="col">N. Consecutivo</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Productos</th>
                                        <th scope="col">Cliente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($data) {

                                        foreach ($data as $row) {

                                            $cliente = consultaSimple('productoKit', $row['idKit']); ?>
                                            <tr>
                                                <td><?php echo $row['kit_consecutivo'] ?></td>
                                                <td><?php echo $row['kit_nombre'] ?></td>
                                                <td class="table-responsive">

                                                    <table class="table">
                                                        <thead class="table-active">
                                                            <td scope="col">Codigo</td>
                                                            <td scope="col">Nombre</td>
                                                            <td scope="col">Peso</td>
                                                            <td scope="col">Cantidad </td>
                                                            <td scope="col">Peso Total</td>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $pesoT = 0;
                                                            $cantidadT = 0;
                                                            foreach (explode("__", $row["productos"]) as $productosConcatenados) {
                                                                $producto = explode("..", $productosConcatenados);
                                                                $pesoT = $pesoT + ($producto[2] * $producto[3]);
                                                                $cantidadT = $cantidadT + $producto[3];
                                                            ?>

                                                                <tr>
                                                                    <td scope="col">
                                                                        <?php echo $producto[0] ?>
                                                                    </td>
                                                                    <td scope="col">
                                                                        <?php echo $producto[1] ?>
                                                                    </td>
                                                                    <td scope="col">
                                                                        <?php echo $producto[2] ?>
                                                                    </td>
                                                                    <td scope="col">
                                                                        <?php echo $producto[3] ?>
                                                                    </td>
                                                                    <td scope="col">
                                                                        <?php echo ($producto[2] * $producto[3]) ?> Kg
                                                                    </td>
                                                                </tr>

                                                            <?php    } ?>

                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <td class="table-active"><?php echo $cantidadT ?></td>
                                                                <td class="table-active"><?php echo $pesoT ?> Kg</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>

                                                </td>
                                                <td><?php echo $cliente[0]["cliente_nombre"] . ' ' . $cliente[0]["cliente_apellido"]; ?></td>
                                                <!-- Button trigger modal -->


                                                <td>
                                                    <a href="./kit-new.php?upS=<?php echo encrypt_decrypt('encrypt', "up"); ?>&idUp=<?php echo encrypt_decrypt('encrypt', $row['idKit']); ?>&idCli= <?php echo $row['kit_idCliente']; ?>"><i class="bi bi-pencil-square btn btn-primary"></i></a>
                                                </td>
                                                <td>

                                                    <i href="../../Ajax/ajax_kit.php?del=deleteEnlisted&id=<?php echo encrypt_decrypt('encrypt', $row['idKit']); ?>" class="bi bi-trash3 btn btn-danger btn_table_del"></i>
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
        </div>
    </div>

    <!-- Scrypt de funcionalidades -->
    <script>
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
                window.location.href = "<?php BASE_URL ?>kit-list.php";
            }, 2000);
        }
        // Fin de la función

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
    <!-- Fin del Script -->
</body>

</html>