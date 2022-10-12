<!DOCTYPE html>
<html lang="en">


<?php
include_once "../assets/in/Head.php";
include_once '../../Controller/session_log.php';

// Se incluye las conexiones a la base
include '../../Model/config.php';

$url = "task-list.php";
$tabla = "producto";
include_once '../../Controller/product_class.php';

// Total de la páginación x vista
include_once '../assets/in/returns.php';

// Instanciamos una nueva clase
$productClass = new productClass();


// Función para traer el total de la tabla consultada
if (isset($_GET['unL'])) {
    $up = encrypt_decrypt('decrypt', $_GET['unL']);
    if ($up == 'up') {
        $dataPr = $productClass->unlockDetails("producto", $bus_1, $campo_1);
        $var = "update";
    }
} else {
    $dataPr = $productClass->dataPrDetails($tabla, $bus_1, $campo_1);
}

// Se edita la cantidad de los productos a bloquear
if (!isset($_GET['new_prod']) && !empty($_POST['CantAlistNew'])) {

    $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_New']);

    $_SESSION["productosBlock"][$idProd]['CantAlis'] = $_POST['CantAlistNew'];
    $_SESSION["productosBlock"][$idProd]['Descrip'] = $_POST['descrip'];
    $_SESSION["productosBlock"][$idProd]['Prioridad'] = $_POST['prioridad'];
}

// Se edita la cantidad de los productos a actualizar
if (!isset($_GET['up_prod']) && !empty($_POST['Cant_Alist_Up'])) {

    $idProd = encrypt_decrypt('decrypt', $_POST['Cant_Alist_Up']);

    $_SESSION["productosUnlock"][$idProd]['CantAlis'] = $_POST['CantAlistUp'];
    $_SESSION["productosUnlock"][$idProd]['Descrip'] = $_POST['descripUp'];
} ?>

<body class=" sb-nav-fixed">
    <?php
    /* Validación para desbloquear un producto */
    if (!empty($_POST['product_Unlock_new'])) {
        if (isset($_SESSION['productosUnlock'])) {
            $producto = $_SESSION['productosUnlock'];
        } else {
            $producto = "";
        }

        if ($producto >= 1) {
            $uid = $productClass->UnlockProduct($producto);

            if ($uid) { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Desbloqueando producto",
                        Texto: "El producto se está desbloqueando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/task-list.php"
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
                        Texto: "No se pudo desbloquear el producto, contacte con el admminstrador del sistema!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/task-list.php"
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
                    Texto: "No se encontro un producto a desbloquear, contacte con el admminstrador del sistema!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/task-list.php"
                };
                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    /* Validación para bloquear un producto */
    if (!empty($_POST['product_block_new'])) {
        if (isset($_SESSION['productosBlock'])) {
            $producto = $_SESSION['productosBlock'];
        } else {
            $producto = "";
        }

        if ($producto >= 1) {
            $uid = $productClass->blockProduct($producto);

            if ($uid) { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: '',
                        Titulo: "Bloqueando producto",
                        Texto: "El producto se está bloqueando correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/task-list.php"
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
                        Texto: "No se pudo desbloquear el producto, contacte con el admminstrador del sistema!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/task-list.php"
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
                    Texto: "No se encontro el producto a bloquear, contacte con el admminstrador del sistema!",
                    Tipo: "error",
                    href: "<?php echo BASE_URL; ?>View/contenido/task-list.php"
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


                    <div class="mb-4">
                        <div class="row pt-4">
                            <div class="col-sm-6">
                                <h1 class="mt-4"><?php echo $resultado = isset($up) ? 'Desbloquear' : 'Bloquear' ?> productos </h1>
                                <ol class="breadcrumb mb-4">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="task-list.php">Tareas</a></li>
                                    <li class="breadcrumb-item active"><?php echo $resultado = isset($up) ? 'Desbloquear' : 'Bloquear' ?> Productos</li>
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
                            <?php if ($var == 'update') { ?>
                                <a onclick="GetUserDetailsUp()" class="btn btn-success mb-3">Agregar Productos </a>
                                <div class="row">
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
                                                        <th scope="col">Bloqueado</th>
                                                        <th scope="col">Descripcion</th>
                                                        <th scope="col">Cantidad a ingresar</th>
                                                        <th scope="col">Peso Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($_SESSION["productosUnlock"])) {
                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["productosUnlock"] as $row) {
                                                            $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                            $cantidadT = $cantidadT + $row['CantAlis'];
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $row['Codigo'] ?></td>
                                                                <td><?php echo $row['Nombre'] ?></td>
                                                                <td><?php echo $row['Peso']; ?> Kg</td>
                                                                <td><?php echo $row['CantExis']; ?></td>
                                                                <form action="#" method="post">
                                                                    <td><input class="form-control" type="text" name="descripUp" value="<?php echo $row['Descrip']; ?>"></td>
                                                                    <td><input class="form-control" type="number" name="CantAlistUp" value="<?php echo $row['CantAlis']; ?>"></td>
                                                                    <!-- <td><textarea class="form-control" type="number" name="descrip" value="<?php echo $row['Descrip']; ?>"></textarea></td> -->
                                                                    <td><?php echo ($row['Peso'] * $row['CantAlis']); ?> Kg</td>


                                                                    <td>

                                                                        <button class="btn btn-success" type="submit" name="up_prod" value="up_prod"><i class="fa-solid fa-floppy-disk"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        <a href="../../Ajax/ajax_product.php?del=deleteProductoUp&idPr=<?php echo encrypt_decrypt('encrypt', $row['idTarea']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>

                                                                        <input type="hidden" name="Cant_Alist_Up" value="<?php echo encrypt_decrypt('encrypt', $row['idTarea']); ?>">
                                                                    </td>
                                                                </form>
                                                                <!-- Button trigger modal -->
                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        } ?>
                                                        <tr>
                                                            <td colspan="5"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?> Kg</td>
                                                        </tr>
                                                    <?php
                                                    } else {
                                                        echo
                                                        "<tr class='text-center'>
                                                                <td colspan='9'>No hay registros en el sistema</td>
                                                            </tr>";
                                                    } ?>
                                                </tbody>
                                            </table>
                                            <form action='' method="POST" autocomplete="off" name="product_Unlock_new" class="needs-validation" novalidate>
                                                <div class="row justify-content-center align-items-center mb-1">
                                                    <div class="col">
                                                        <div class="d-flex justify-content-center">
                                                            <button type="submit" name="product_Unlock_new" class="btn btn-primary" value="product_Unlock_new"> Desbloquear </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!empty($_SESSION['productosUnlock'])) { ?>
                                                    <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_product.php?del=emptyUp">Vaciar Productos</a>
                                                <?php } ?>

                                            </form>
                                            <!-- Paginación -->

                                        </div>
                                    </div>


                                </div>
                                <br>


                            <?php } else { ?>
                                <a onclick="GetUserDetails()" class="btn btn-success mb-3">Agregar Productos </a>

                                <div class="row">

                                    <div class="card-body">
                                        <div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            Productos
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"></th>
                                                        <th scope="col">Codigo</th>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Cliente</th>
                                                        <th scope="col">Peso</th>
                                                        <th scope="col">Cantidad existente</th>
                                                        <th scope="col">Descripcion</th>
                                                        <th></th>
                                                        <th scope="col">Cantidad a ingresar</th>

                                                        <th scope="col">Peso Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($_SESSION["productosBlock"])) {
                                                        $pesoT = 0;
                                                        $cantidadT = 0;

                                                        foreach ($_SESSION["productosBlock"] as $row) {
                                                            $pesoT = $pesoT + ($row['Peso'] * $row['CantAlis']);
                                                            $cantidadT = $cantidadT + $row['CantAlis'];
                                                    ?>
                                                            <tr>
                                                                <td>#</td>
                                                                <td><?php echo $row['Codigo'] ?></td>
                                                                <td><?php echo $row['Nombre'] ?></td>
                                                                <td><?php echo $row['Cliente'] ?></td>
                                                                <td><?php echo $row['Peso']; ?> Kg</td>
                                                                <td><?php echo $row['CantExis']; ?></td>
                                                                <form action="#" method="post">
                                                                    <div class="row">

                                                                    </div>
                                                                    <td><input class="form-control" type="text" name="descrip" value="<?php echo $row['Descrip']; ?>"></td>
                                                                    <td>
                                                                        <select class="form-select" name="prioridad" value="<?php echo $row['Prioridad'] ?>" required>
                                                                            <option value="Control" <?php echo $resultado = $row['Prioridad']  == "Control" ? "selected" : ''; ?>>Control</option>
                                                                            <option value="Alto" <?php echo $resultado = $row['Prioridad']  == "Alto" ? "selected" : ''; ?>>Alto</option>
                                                                            <option value="Medio" <?php echo $resultado = $row['Prioridad']  == "Medio" ? "selected" : ''; ?>>Medio</option>
                                                                            <option value="Bajo" <?php echo $resultado = $row['Prioridad']  == "Bajo" ? "selected" : ''; ?>>Bajo</option>
                                                                        </select>
                                                                    </td>

                                                                    <td><input class="form-control" type="number" name="CantAlistNew" value="<?php echo $row['CantAlis']; ?>"></td>

                                                                    <!-- <td><textarea class="form-control" type="number" name="descrip" value="<?php echo $row['Descrip']; ?>"></textarea></td> -->
                                                                    <td><?php echo ($row['Peso'] * $row['CantAlis']); ?> Kg</td>

                                                                    <td>
                                                                        <input type="hidden" name="Cant_Alist_New" value="<?php echo encrypt_decrypt('encrypt', $row['idProducto']); ?>">

                                                                        <button class="btn btn-success" type="submit" name="new_prod" value="new_prod"><i class="fa-solid fa-floppy-disk"></i></button>
                                                                    </td>
                                                                    <td>
                                                                        <a href="../../Ajax/ajax_product.php?del=deleteProducto&idPr=<?php echo encrypt_decrypt('encrypt', $row['idProducto']); ?>" class="btn btn-danger btn_table_del bi bi-trash3 "></a>
                                                                    </td>
                                                                </form>
                                                                <!-- Button trigger modal -->
                                                            </tr>
                                                        <?php
                                                            $count++;
                                                        } ?>
                                                        <tr>
                                                            <td colspan="8"></td>
                                                            <td class="table-active"><?php echo $cantidadT ?></td>
                                                            <td class="table-active"><?php echo $pesoT ?> Kg</td>
                                                        </tr>
                                                    <?php
                                                    } else {
                                                        echo
                                                        "<tr class='text-center'>
                                                                <td colspan='10'>No hay registros en el sistema</td>
                                                            </tr>";
                                                    } ?>
                                                </tbody>
                                            </table>
                                            <form action='' method="POST" autocomplete="off" name="product_block_new" class="needs-validation" novalidate>
                                                <div class="row justify-content-center align-items-center mb-1">
                                                    <div class="col">
                                                        <div class="d-flex justify-content-center">
                                                            <button type="submit" name="product_block_new" class="btn btn-primary" value="product_block_new"> Bloquear </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if (!empty($_SESSION['productosBlock'])) { ?>
                                                    <a class="btn btn-danger btn_table_del" href="../../Ajax/ajax_product.php?del=empty">Vaciar Productos</a>
                                                <?php } ?>

                                            </form>
                                            <!-- Paginación -->

                                        </div>
                                    </div>


                                </div>

                            <?php
                            } ?>
                        </div>
                    </div>
            </main>
            <?php include_once '../assets/in/modals.php'; ?>
        </div>

    </div>

    <!-- Scrypt de funcionalidades -->
    <?php include_once '../assets/in/alerts_answer.php'; ?>
    <script>
        // Función para ver la data desde el modal de productos bloqueados
        function GetUserDetails() {
            $("#producto_block_modal").modal("show");
        }
        // Función para ver la data desde el modal de productos desbloqueados
        function GetUserDetailsUp() {
            $("#producto_blockUp_modal").modal("show");
        }

        // Función para mostrar la alerta antes de eliminar
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
        // Fin de la función
    </script>
</body>

</html>