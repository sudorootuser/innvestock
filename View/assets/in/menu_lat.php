<style>
    .small .a {
        font-weight: 800;
        font-size: large;
    }

    .small {
        font-weight: bold;
        color: #fff;
    }

    .logo {
        width: 100%;
        margin-bottom: 20px;
    }

    .sb-sidenav-menu {
        padding: 10% 10%;
    }
</style>
<?php

$botonBodega = consultaSimple("bodega", $_SESSION['bodega']);

$dataBodega = consultaSimple("bodega", "") ?>

<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <a href="dashboard.php">
            <div class="d-flex justify-content-center" style="border-bottom: 1px solid #fff;margin-bottom: 20px;">

                <a href="dashboard.php"> <img class="logo" src="../assets/img/innvestock2.png" alt=""></a>
            </div>
        </a>
        <div class="nav">
            <?php if ($_SESSION['usuario_Rol'] == 1) { ?>
                <a onclick="BBBodega()" class="btn btn-light mb-3">
                    <?php echo $botonBodega[0]['bodega_nombre']; ?>
                </a>
            <?php } ?>
            <?php if ($_SESSION['usuario_Rol'] == 1 or $_SESSION['usuario_Rol'] == 2) { ?>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#Administrar" aria-expanded="false" aria-controls="Administrar">
                    Administrar
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="Administrar" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">

                        <a class="nav-link" href="client-list.php">Clientes</a>
                        <a class="nav-link" href="product-list.php">Productos</a>
                        <a class="nav-link" href="task-list.php">Tareas</a>
                        <a class="nav-link" href="cellar-list.php">Bodegas</a>
                        <a class="nav-link" href="kit-list.php">Kits</a>
                    </nav>
                </div>
            <?php } ?>
            <?php if ($_SESSION['usuario_Rol'] == 4) { ?>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#Administrar" aria-expanded="false" aria-controls="Administrar">
                    Administrar
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="Administrar" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">

                        <a class="nav-link" href="product-list.php">Productos</a>

                    </nav>
                </div>
            <?php } ?>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#Alistados" aria-expanded="false" aria-controls="Alistados">
                Alistados
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="Alistados" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="enlisted-list.php">Pre-Aviso <br> Recepciones</a>
                    <a class="nav-link" href="enlistedDs-list.php">Alistamiento Despachos</a>
                </nav>
            </div>

            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#Ejecutar" aria-expanded="false" aria-controls="Ejecutar">
                Ejecutar
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="Ejecutar" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">

                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="reception-list.php">Ingresos</a>
                    <a class="nav-link" href="dispatch-list.php">Despachos</a>

                </nav>
            </div>
            <?php if ($_SESSION['usuario_Rol'] == 1) { ?>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#permisos_usuario" aria-expanded="false" aria-controls="permisos_usuario">
                    Permisos de usuarios
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="permisos_usuario" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="usuario-list.php">Usuarios</a>
                    </nav>
                </div>
            <?php } ?>
            <a class=" nav-link  btn_log_out" href="../../Controller/log_out.php">
                Salir
            </a>

        </div>
    </div>
    <div class="sb-sidenav-footer">
        <?php if ($_SESSION['usuario_Rol'] == 1) { ?>
            <div class="small d-flex justify-content-center"><a class="nav-link a">Administrador</a></div>
        <?php } else if ($_SESSION['usuario_Rol'] == 2) { ?>
            <div class="small d-flex justify-content-center"><a class="nav-link a" style="font-size: 17px;">Coordinador de Bodega</a></div>
        <?php } else if ($_SESSION['usuario_Rol'] == 3) { ?>
            <div class="small d-flex justify-content-center"><a class="nav-link a">Operativo</a></div>
        <?php } else if ($_SESSION['usuario_Rol'] == 4) { ?>
            <div class="small d-flex justify-content-center"><a class="nav-link a">Invitado</a></div>
        <?php } ?>
        <div class="small d-flex justify-content-center">Ultimo ingreso:</div>
        <div class="small d-flex justify-content-center"><?php echo $_SESSION['last_session']; ?></div>
    </div>

    <script>
        $('.btn_log_out').on("click", function(e) {

            e.preventDefault();

            const href = $(this).attr('href')

            Swal.fire({
                title: '¿Deseas cerrar Sesión?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Sí, cerrar sesión!'
            }).then((result) => {
                if (result.value) {
                    document.location.href = href;
                }
            });
        });

        function BBBodega() {
            $("#bodega_modal").modal("show");
        }
    </script>
</nav>