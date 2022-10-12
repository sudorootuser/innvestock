<script src="../View/assets/js/alertas.js"></script>
<link rel="../View/assets/css/sweetalert2.min.css">


<script src="../View/assets/js/sweetalert2.min.js"></script>

<script src="../View/assets/js/sweetalert2.all.min.js"></script>


<body>
    <?php
    include_once '../Model/config.php';
    session_start(["name" => "SPM"]);

    if (isset($_POST['id_bodega'])) {
        
        $_SESSION['bodega'] = $_POST['id_bodega'];
        unset($_SESSION['id_client_pro']);
        unset($_SESSION['productosIg']);
        unset($_SESSION['alistado']);
        unset($_SESSION['despacho']);
        unset($_SESSION['alistadoUpdate']);
        unset($_SESSION['productosUp']);
        unset($_SESSION['productosIg']);
        unset($_SESSION['idCliUp']);
        unset($_SESSION['url_global_ingreso']);
        unset($_SESSION['alistadoDsUpdate']);
        unset($_SESSION['productosDs']);
        unset($_SESSION['productosDsUp']);
        unset($_SESSION['productosBlock']);
        unset($_SESSION['productosUnlock']);
        unset($_SESSION['recepcion']);
        unset($_SESSION['recepcion2']);
        unset($_SESSION['productosBlock']);
        unset($_SESSION['productosUnlock']);
        unset($_SESSION['recepcion']);
        unset($_SESSION['recepcion2']);
        unset($_SESSION['kitDs']);
        unset($_SESSION['kitDsUp']);
        unset($_SESSION['productosKitDsUp']);  ?>
        
        <script>
            let alerta = {
                Alerta: "simple",
                Icono: 'success',
                Titulo: "Cambiando de Bodega...",
                Texto: "",
                Tipo: "success",
                href: "<?php echo BASE_URL; ?>View/contenido/dashboard.php"
            };
            alertas_ajax(alerta);
        </script>
    <?php
    }
    ?>

</body>