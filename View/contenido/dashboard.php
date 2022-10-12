<!DOCTYPE html>
<html lang="en">
<?php
include_once "../assets/in/Head.php";

include_once '../../Controller/session_log.php';
// Se incluye las conexiones a la base
include '../../Model/config.php';

$url = "dashboard.php";
$tabla = "historial";
// Total de la páginación x vista
include_once '../assets/in/returns.php';

// Función para traer el total de la tabla consultada
$data =  consulta_Historial($tabla, $start, $Tpages,); ?>

<body class="sb-nav-fixed">
    <?php include_once "../assets/in/menu_profile.php"; ?>
    <div id="layoutSidenav">

        <div id="layoutSidenav_nav">
            <?php include_once '../assets/in/menu_Lat.php'; ?>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Estatus</h1>
                    <!-- Slides Estadisticas -->
                    <div class="row" style="padding: 0 10% 3% 10%;">
                        <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="true">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="../assets/img/Grafica_3.png" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item active">
                                    <img src="../assets/img/Grafica_3.png" class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    <!-- Historial -->
                    <?php if ($_SESSION['usuario_Rol'] == 1) { ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla de datos - Historial
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Usuario</th>
                                            <th scope="col">Tabla modificada</th>
                                            <th scope="col">Tipo de acción</th>
                                            <th scope="col">Identificador de la Tabla</th>
                                            <th scope="col">Fecha del Movimiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $_SESSION['bodega'];
                                        if (!empty($data)) {
                                            foreach ($data as $row) { ?>
                                                <tr>
                                                    <td><?php echo $count ?></td>

                                                    <?php if ($row['historial_userAccion'] == 1) { ?>
                                                        <td>Administrador</td>
                                                    <?php } else if ($row['historial_userAccion'] == 2) { ?>
                                                        <td>Coordinador de Bodega</td>
                                                    <?php } else if ($row['historial_userAccion'] == 3) { ?>
                                                        <td>Operario</td>
                                                    <?php } ?>
                                                    <td><?php echo $row['historial_tablaAccion'] ?></td>
                                                    <td><?php echo $row['historial_tipoAccion'] ?></td>
                                                    <td>N: <?php echo $row['historial_idAccion'] ?></td>
                                                    <td><?php echo $row['historial_fechaAccion'] ?></td>
                                                </tr>
                                            <?php
                                                $count += 1;
                                            }
                                        } else { ?>
                                            <tr class="text-center">
                                                <td colspan="9">No hay Historial en el sistema</td>
                                            </tr>
                                        <?php  } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- Modals -->
                <?php include_once '../assets/in/modals.php'; ?>
            </main>
        </div>
    </div>
</body>

</html>