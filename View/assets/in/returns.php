<?php
// Funciones globales
include_once '../../Controller/main_class.php';

// Se valida que la data sea correcta desde el método GET

// Validacion de la vista de new

if (isset($_GET['upS'])) {

    $up = encrypt_decrypt('decrypt', $_GET['upS']);

    if ($up == 'up') {

        $detalis = consultaSimple($tabla, encrypt_decrypt('decrypt', $_GET['idUp']));

        $AllCellarClient = consultaSimple('clienteBodega', encrypt_decrypt('decrypt', $_GET['idUp']));
        $id = encrypt_decrypt('decrypt', $_GET['idUp']);
        $_SESSION['idClienteDB'] = $id;

        // Sessión para las alerta
        $_SESSION['idClienteNew'] = $_GET['upS'] . '&idUp=' . $_GET['idUp'];

        $var = "update";
        $_SESSION['tp_estado'] = 'update';
    }
} else {
    $_SESSION['idClienteNew'] = '';
    $var = "new";
    $_SESSION['tp_estado'] = 'new';
}

// Se valida que la busqueda tenga data de noser el caso se asigna a la variable campos vacios
if (isset($_POST['bus_1']) && isset($_POST['campo_1'])) {
    $bus_1 = limpiar_cadena($_POST['bus_1']);
    $campo_1 = $_POST['campo_1'];
} else {
    $bus_1 = "";
    $campo_1 = "";
}

if (isset($_POST['bus_2']) && isset($_POST['campo_2'])) {
    $bus_2 = limpiar_cadena($_POST['bus_2']);
    $campo_2 = $_POST['campo_2'];
} else {
    $bus_2 = "";
    $campo_2 = "";
}
if (isset($_POST['bus_3']) && isset($_POST['campo_3'])) {
    $bus_3 = limpiar_cadena($_POST['bus_3']);
    $campo_3 = $_POST['campo_3'];
} else {
    $bus_3 = "";
    $campo_3 = "";
}

// Se valida que la busqueda tenga data de noser el caso se asigna a la variable campos vacios
if (isset($_POST['busClient']) && isset($_POST['campoClient'])) {
    $busClient = limpiar_cadena($_POST['busClient']);
    $campoClient = $_POST['campoClient'];
} else {
    $busClient = '';
    $campoClient = '';
}

// Se condiciona la respuesta del registro para mostrar la respectiva alerta
if (isset($_GET['d'])) : ?>
    <div class="flash-data" data-flashdata="<?= $_GET['d']; ?>"></div>
<?php endif;

// Total de paginas x vista
$Tpages = 8;

// Función para traer el total de la paginación
$count_d = pagination($tabla, $Tpages);
$page = false;

//examino la pagina a mostrar y el inicio del registro a mostrar
if (isset($_GET["pg"])) {
    $page = encrypt_decrypt('decrypt', $page = $_GET["pg"]);
}

if (!$page) {
    $start = 0;
    $page = 1;
} else {
    $start = ($page - 1) * $Tpages;
}

//calculo el total de paginas para la paginación
$total_pages = ceil($count_d / $Tpages);


$count = 1;
