<?php

if (!isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] == '') {
    include '../../Model/config.php';

    session_unset();
    session_destroy();

    header('Location: ' . BASE_URL . 'index.php');
}
