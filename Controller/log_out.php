<?php
include '../Model/config.php';

session_start(['name' => 'SPM']);

session_unset();
session_destroy();

$URL = BASE_URL;
header('Location: ' . $URL . 'index.php');
