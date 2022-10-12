<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Innvestock</title>
</head>

<body>
    <?php
    // Se incluye las conexiones a la base
    include '../../Model/config.php';
    include_once '../../Controller/main_class.php';

    if (isset($_GET['tb'])) {

        $id = $_GET['id'];

        $tabla = encrypt_decrypt('decrypt', $_GET['tb']);

        if ($tabla == 'cliente') {

            include_once '../../Controller/client_class.php';
            $class = new clientClass();

            echo $class->GenerateExcel($tabla, $id);
        } else if ($tabla == 'producto') {
            include_once '../../Controller/product_class.php';
            $class = new productClass();

            echo $class->GenerateExcel($tabla, $id);
        } else if ($tabla == 'usuario') {
            include_once '../../Controller/usuario_class.php';
            $class = new usuarioClass();

            echo $class->GenerateExcel($tabla, $id);
        } else if ($tabla == 'bodega') {
            include_once '../../Controller/cellar_class.php';

            $class = new cellarClass();

            echo $class->GenerateExcel($tabla, $id);
        } else if ($tabla == 'alistado') {
            include_once '../../Controller/enlisted_class.php';
            $class = new enlistedClass();

            echo $class->GenerateExcel($tabla, $id);
        } else if ($tabla == 'producto_ingresado') {
            include_once '../../Controller/reception_class.php';
            $class = new receptionClass();

            echo $class->GenerateExcel($tabla, $id);
        } else if ($tabla == 'enlistedDs') {
            include_once '../../Controller/enlistedDs_class.php';
            $class = new enlistedDsClass();

            echo $class->GenerateExcel($tabla, $id);
        } else if ($tabla == 'tarea') {
            include_once '../../Controller/task_class.php';
            $class = new taskClass();

            echo $class->GenerateExcel($tabla, $id);
        } else { ?>
            <script>
                alert('Error, No se puede generar el excel contacte con el adminisrador del sistema!');
                window.location.href = './excel.php';
            </script>
        <?php
        }
    } else { ?>
        <script>
            alert('Error, contacte con el adminisrador del sistema');
            window.location.href = 'dashboard.php';
        </script>
    <?php
    }

    ?>
</body>

</html>