<script src="../View/assets/js/alertas.js"></script>
<link rel="../View/assets/css/sweetalert2.min.css">


<script src="../View/assets/js/sweetalert2.min.js"></script>

<script src="../View/assets/js/sweetalert2.all.min.js"></script>

<body>

    <?php
    include_once '../Model/config.php';

    // Instanciamos el controlador
    include_once '../Controller/product_class.php';
    $client = new productClass();
    if (isset($_GET['del'])) {

        if ($_GET['del'] == 'deleteProduct') {
            $client->deleteProduct();
        }
        if ($_GET['del'] == 'deleteProducto') {
            $res = $client->deleteProducto($_GET['idPr']);
            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "EL producto se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/product-block.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
        if ($_GET['del'] == 'deleteProductoUp') {
            $res = $client->deleteProductoUp($_GET['idPr']);
            $unL = encrypt_decrypt('encrypt', "up");
            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "EL producto se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/product-block.php?unL=<?php echo $unL ?>"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
        if ($_GET['del'] == 'empty') {
            $client->Vaciar();
        }
        if ($_GET['del'] == 'emptyUp') {
            $client->VaciarUp();
        }
    }
    if (isset($_POST['Unlock'])) {
        $idProducto = $_POST['id_producto'];
        $idTarea = $_POST['id_block'];
        $cantidad = $_POST['cantidad'];
        $descrip = $_POST['descrip'];
        $res = $client->agregarProductoUp($idProducto, $idTarea, $cantidad, $descrip);
        $unL = encrypt_decrypt('encrypt', "up");
        if ($res == 'Agregado') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'success',
                    Titulo: "Producto Agregado",
                    Texto: "El producto se agrego correctamente!",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/product-block.php?unL=<?php echo $unL ?>"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        } elseif ($res == 'existe') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'info',
                    Titulo: "Producto Existe",
                    Texto: "El producto se está agregado!",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/product-block.php?unL=<?php echo $unL ?>"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'error',
                    Titulo: "Ocurrio un error",
                    Texto: "<?php echo $res ?>",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/product-block.php?unL=<?php echo $unL ?>"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        }
    } else if (isset($_POST['cantidad'])) {
        $id = $_POST['id_producto'];
        $cantidad = $_POST['cantidad'];
        $descrip = $_POST['descrip'];
        $prioridad = $_POST['prioridad'];
        $res = $client->agregarProducto($id, $cantidad, $descrip, $prioridad);
        if ($res == 'Agregado') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'success',
                    Titulo: "Producto Agregado",
                    Texto: "El producto se agrego correctamente!",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/product-block.php"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        } elseif ($res == 'existe') { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'info',
                    Titulo: "Producto Existe",
                    Texto: "El producto se está agregado!",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/product-block.php"
                };

                alertas_ajax(alerta);
            </script>
        <?php
        } else { ?>
            <script>
                let alerta = {
                    Alerta: "simple",
                    Icono: 'error',
                    Titulo: "Ocurrio un error",
                    Texto: "<?php echo $res ?>",
                    Tipo: "success",
                    href: "<?php echo BASE_URL; ?>View/contenido/product-block.php"
                };

                alertas_ajax(alerta);
            </script>
            <?php
        }
    }

    if (isset($_GET['deldb'])) {

        if ($_GET['deldb'] == 'deleteClienbd') {

            $id_del = encrypt_decrypt('decrypt', $_GET['id']);
            $res = $client->deleteClientbd($id_del);

            if ($res == true) { ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "La bodega se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/product-list.php"
                    };

                    alertas_ajax(alerta);
                </script>
    <?php
            }
        }
    } ?>
</body>