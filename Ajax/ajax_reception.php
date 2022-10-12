<script src="../View/assets/js/alertas.js"></script>
<link rel="../View/assets/css/sweetalert2.min.css">


<script src="../View/assets/js/sweetalert2.min.js"></script>

<script src="../View/assets/js/sweetalert2.all.min.js"></script>

<body>

    <?php
    include_once '../Model/config.php';
    // Instanciamos el controlador
    include_once '../Controller/reception_class.php';
    $class = new receptionClass();
    // Se redirige al controlador de eliminar
    if (isset($_GET['del'])) {
        if ($_GET['del'] == 'deleteEnlisted') {
            $res = $class->deleteReception();

            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "El alistamieno se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-list.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
        if ($_GET['del'] == 'deleteProduct') {
            $id_del = encrypt_decrypt('decrypt', $_GET['idPr']);

            $res = $class->deleteProducto($id_del);

            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "El alistamieno se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php?id=<?php echo  $_SESSION['alistado'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
        if ($_GET['del'] == 'deleteProductNew') {

            $id_del = encrypt_decrypt('decrypt', $_GET['idPr']);
            $res = $class->deleteProductoNew($id_del);

            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "El alistamieno se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
        if ($_GET['del'] == 'empty') {
            $class->Vaciar();
        }
        if ($_GET['del'] == 'empty2') {
            $class->Vaciar2();
        }
    }

    // Condición para agregar el producto al ingreso sin un pre-alistamiento
    if (isset($_POST['validacion'])) {
        $bool = encrypt_decrypt('decrypt', $_POST['validacion']);
        if ($bool == "true") {
            $id = $_POST['id_producto'];
            $cantidad = $_POST['cantidad'];

            $res = $class->agregarProducto2($id, $cantidad);

            if ($res == 'cantidadMenor') { ?>
                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: 'erroe',
                        Titulo: "Ocurrio un error..",
                        Texto: "El producto se está agregado!",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'existe') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Producto Existe",
                        Texto: "El producto se está agregado!",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'agregado') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'success',
                        Titulo: "Producto Agregado",
                        Texto: " ",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }

        // Condición para agregar el producto al ingreso con un pre-alistamiento
        else if ($bool == "false") {

            $id = $_POST['id_producto'];
            $cantidad = $_POST['cantidad'];

            $res = $class->agregarProducto($id, $cantidad);
            if ($res == 'cantidadMenor') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Ocurrio un error!",
                        Texto: "La cantidad es menor a la cantidadad alistada!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php?id=<?php echo  $_SESSION['alistado'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'existe') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'erroe',
                        Titulo: "Producto Existe",
                        Texto: "El producto se está agregado!",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php?id=<?php echo  $_SESSION['alistado'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'agregado') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'success',
                        Titulo: "Producto Agregado",
                        Texto: "",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/reception-new.php?id=<?php echo  $_SESSION['alistado'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
    <?php
            }
        } else {
            header("Location:" . BASE_URL . 'View/contenido/reception-new.php');
        }
    } ?>
</body>