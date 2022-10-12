<script src="../View/assets/js/alertas.js"></script>
<link rel="../View/assets/css/sweetalert2.min.css">
<script src="../View/assets/js/sweetalert2.min.js"></script>
<script src="../View/assets/js/sweetalert2.all.min.js"></script>

<body>
    <?php
    include_once '../Model/config.php';

    // Instanciamos el controlador
    include_once '../Controller/enlistedDs_class.php';
    $class = new enlistedDsClass();

    // Se redirige al controlador de eliminar
    if (isset($_GET['del'])) {

        if ($_GET['del'] == 'deleteEnlisted') {
            $class->deleteEnlisted();
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
                        Texto: "EL producto se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
        // Función para eliminar los porductos agregados en la vista de editar
        if ($_GET['del'] == 'deleteProductUp') {

            $cantExist = encrypt_decrypt('decrypt', $_GET['cantAlis']);
            $id_del = encrypt_decrypt('decrypt', $_GET['idPr']);

            $res =  $class->deleteProductoUp($id_del, $cantExist);

            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "EL producto se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
        if ($_GET['del'] == 'empty') {
            $class->Vaciar();
        }
        if ($_GET['del'] == 'emptyUp') {
            $class->VaciarUp();
        }

        if ($_GET['del'] == 'deleteKit') {

            $id_del = encrypt_decrypt('decrypt', $_GET['idPr']);
            $res = $class->deleteKit($id_del);

            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "EL producto se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }

        if ($_GET['del'] == 'deleteKitUp') {

            $cantExist = encrypt_decrypt('decrypt', $_GET['cantAlis']);
            $id_del = encrypt_decrypt('decrypt', $_GET['idPr']);

            $res =  $class->deleteKitUp($id_del, $cantExist);

            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "EL producto se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
    }

    if (!empty($_POST['id_kit'])) {

        $id = $_POST['id_kit'];
        $cantidad = $_POST['cantidad'];
        if (!empty($_POST['up_kit'])) {

            $res = $class->agregarKitUp($id, $cantidad);

            if ($res == 'menorCero') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Ocurrio un error",
                        Texto: "La cantidad debe ser mayor a cero!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == false) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "ERROR!",
                        Texto: "Error desconocido, intente nuevamente !",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'kitNoExis') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'info',
                        Titulo: "Error!",
                        Texto: "Kit no disponible!",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'cantExist') { ?>

                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'info',
                        Titulo: "Producto existente",
                        Texto: "El producto se está agregando",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'agregado') { ?>

                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: 'info',
                        Titulo: "Finalizando operación",
                        Texto: "El producto se está agregando...",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>

                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Algo salió mal!",
                        Texto: "Productos insuficientes,<?php echo $res; ?> ",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        } else {
            $res = $class->agregarKit($id, $cantidad);
            if ($res == 'menorCero') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Ocurrio un error",
                        Texto: "La cantidad debe ser mayor a cero!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == false) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "ERROR!",
                        Texto: "Error desconocido, intente nuevamente !",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'kitNoExis') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'info',
                        Titulo: "Error!",
                        Texto: "Kit no disponible!",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'agregado') { ?>

                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: 'info',
                        Titulo: "Finalizando operación",
                        Texto: "El producto se está agregando...",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } else { ?>

                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Algo salió mal!",
                        Texto: "Productos insuficientes,<?php echo $res; ?> ",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
                <?php
            }
            // Controlador para prepara un despacho sin pre-alistamiento 
        }
    }

    // Función para agregar un producto en la vista de los despachos alistados
    if (isset($_POST['id_producto'])) {
        if (!empty($_POST['up'])) {
            if (isset($_POST['cantidad'])) {
                $id = $_POST['id_producto'];
                $cantidad = $_POST['cantidad'];

                $res = $class->agregarProductoUp($id, $cantidad);

                if ($res == 'menorUno') { ?>
                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'error',
                            Titulo: "Ocurrio un error",
                            Texto: "La cantidad debe ser maayor a cero!",
                            Tipo: "error",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                        };

                        alertas_ajax(alerta);
                    </script>
                <?php
                } elseif ($res == 'product_agotado') { ?>
                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'error',
                            Titulo: "Producto Agotado",
                            Texto: "El producto se encuentra agotado!",
                            Tipo: "error",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                        };

                        alertas_ajax(alerta);
                    </script>
                <?php
                } elseif ($res == 'cantMayor') { ?>
                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'info',
                            Titulo: "Cantidad no disponible",
                            Texto: "La cantidad a agregar es mayor a la disponible!",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                        };

                        alertas_ajax(alerta);
                    </script>
                <?php
                } elseif ($res == 'cantExist') { ?>

                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'info',
                            Titulo: "Producto existente",
                            Texto: "El producto se está agregando",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                        };

                        alertas_ajax(alerta);
                    </script>
                <?php
                } elseif ($res == 'agregado') { ?>

                    <script>
                        let alerta = {
                            Alerta: "registro",
                            Icono: 'info',
                            Titulo: "Finalizando operación",
                            Texto: "El producto se está agregando...",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_despacho'] ?>"
                        };

                        alertas_ajax(alerta);
                    </script>
                <?php
                }
            }
            // Controlador para prepara un despacho sin pre-alistamiento 
        } else if (isset($_POST['cantidad'])) {
            $id = $_POST['id_producto'];
            $cantidad = $_POST['cantidad'];

            $res = $class->agregarProducto($id, $cantidad);
            if ($res == 'menorUno') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Ocurrio un error",
                        Texto: "La cantidad debe ser maayor a cero!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'product_agotado') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'error',
                        Titulo: "Producto Agotado",
                        Texto: "El producto se encuentra agotado!",
                        Tipo: "error",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'cantMayor') { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'info',
                        Titulo: "Cantidad no disponible",
                        Texto: "La cantidad a agregar es mayor a la disponible!",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'cantExist') { ?>

                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: 'info',
                        Titulo: "Producto existente",
                        Texto: "El producto se está agregando",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            } elseif ($res == 'agregado') { ?>

                <script>
                    let alerta = {
                        Alerta: "registro",
                        Icono: 'info',
                        Titulo: "Finalizando operación",
                        Texto: "El producto se está agregando...",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/enlistedDs-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
    <?php
            }
        }
    }
    ?>
</body>