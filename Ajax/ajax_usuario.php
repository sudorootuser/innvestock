<script src="../View/assets/js/alertas.js"></script>
<link rel="../View/assets/css/sweetalert2.min.css">


<script src="../View/assets/js/sweetalert2.min.js"></script>

<script src="../View/assets/js/sweetalert2.all.min.js"></script>

<body>

    <?php
    include_once '../Model/config.php';

    // Instanciamos el controlador
    include_once '../Controller/usuario_class.php';
    $client = new usuarioClass();

    // Se redirige al controlador de eliminar
    if (isset($_GET['del'])) {
        if ($_GET['del'] == 'deleteUser') {
            $res = $client->deleteUser();

            if ($res == true) {  ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "El usuario se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL; ?>View/contenido/usuario-list.php"
                    };

                    alertas_ajax(alerta);
                </script>
            <?php
            }
        }
    }
    if (isset($_GET['deldb'])) {

        if ($_GET['deldb'] == 'deleteClienbd') {
            $id_del = encrypt_decrypt('decrypt', $_GET['id']);

            $res = $client->deleteClientbd($id_del);

            if ($res == true) {
                $var =  $_SESSION['idClienteNew'];
            ?>
                <script>
                    let alerta = {
                        Alerta: "simple",
                        Icono: '',
                        Titulo: "Finalizando operación",
                        Texto: "La bodega se está eliminado correctamente",
                        Tipo: "success",
                        href: "<?php echo BASE_URL ?>View/contenido/client-new.php?upS=<?php echo $var ?>"
                    };

                    alertas_ajax(alerta);
                </script>
    <?php
            }
        }
    } ?>
</body>