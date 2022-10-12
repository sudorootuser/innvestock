<script src="../View/assets/js/alertas.js"></script>
<link rel="../View/assets/css/sweetalert2.min.css">


<script src="../View/assets/js/sweetalert2.min.js"></script>

<script src="../View/assets/js/sweetalert2.all.min.js"></script>

<body>

    <?php
    include_once '../Model/config.php';

    // Instanciamos el controlador
    include_once '../Controller/enlisted_class.php';
    $class = new enlistedClass();
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
                        href: "<?php echo BASE_URL; ?>View/contenido/enlisted-new.php"
                    };

                    alertas_ajax(alerta);
                </script>
                <?php
            }
        }
        if ($_GET['del'] == 'deleteProductUp') {
            $cantExist = encrypt_decrypt('decrypt', $_GET['cantAlis']);
            $id_del = encrypt_decrypt('decrypt', $_GET['idPr']);
            $class->deleteProductoUp($id_del, $cantExist);
        }
        if ($_GET['del'] == 'empty') {
            $class->Vaciar();
        }
        if ($_GET['del'] == 'emptyUp') {
            $class->VaciarUp();
        }
    }
    // Controlaador para agregar productos en el alistamiento del ingreso
    if (isset($_POST['up'])) {
        if ($_POST['up'] == "up") {
            // echo 'aca 1';die;
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
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_ingreso']?>"
                        };

                        alertas_ajax(alerta);
                    </script>
                <?php
                }elseif ($res == 'Agregado') { ?>
                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'success',
                            Titulo: "Producto Agregado",
                            Texto: "El producto se agrego correctamente!",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_ingreso']?>"
                        };
        
                        alertas_ajax(alerta);
                    </script>
                <?php
                }  elseif ($res == 'cantidadAgotado') { ?>
                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'error',
                            Titulo: "Producto Agotado",
                            Texto: "El producto se encuentra agotado!",
                            Tipo: "error",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_ingreso']?>"
                        };

                        alertas_ajax(alerta);
                    </script>
                <?php
                } elseif ($res == 'cantidadMayor') { ?>
                    <script>
                        let alerta = {
                            Alerta: "simple",
                            Icono: 'info',
                            Titulo: "Cantidad no disponible",
                            Texto: "La cantidad a agregar es mayor a la disponible!",
                            Tipo: "success",
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_ingreso']?>"
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
                            href: "<?php echo BASE_URL; ?>View/contenido/<?php echo $_SESSION['url_global_ingreso']?>"
                        };

                        alertas_ajax(alerta);
                    </script>
            <?php
                }
            }
        }
    } else if (isset($_POST['cantidad'])) {
        $id = $_POST['id_producto'];
        $cantidad = $_POST['cantidad'];
        $res = $class->agregarProducto($id, $cantidad);
        if ($res == 'cantidadMenorQueCero') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'error',
                    Titulo: "Ocurrio un error",
                    Texto: "La cantidad es menor a Cero!",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/enlisted-new.php"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        } elseif ($res == 'Agregado') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'success',
                    Titulo: "Producto Agregado",
                    Texto: "El producto se agrego correctamente!",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/enlisted-new.php"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        } elseif ($res == 'Existe') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'info',
                    Titulo: "Producto Existe",
                    Texto: "El producto se está agregado!",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/enlisted-new.php"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        } elseif ($res == 'Agregado') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'success',
                    Titulo: "Producto Agregado",
                    Texto: "El producto se está agregando",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/enlisted-new.php"
                };

                alertas_ajax(alerta);
            </script>
    <?php
        }
    } ?>
</body>