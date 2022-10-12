<!DOCTYPE html>
<html lang="en">
<?php
include_once "../assets/in/Head.php";

// Se incluye las conexiones a la base
include '../../Model/config.php';
include '../../Controller/login_class.php';

$userClass = new userClass();

$errorMsgLogin = '';

if (!empty($_POST['loginSubmit'])) {

    $usernameEmail = $_POST['loginUser'];
    $password = $_POST['loginPassword'];

    if (strlen(trim($usernameEmail)) > 1 && strlen(trim($password)) > 1) {

        $uid = $userClass->userLogin($usernameEmail, $password);
        if ($uid) {
            $url = BASE_URL . 'View/contenido/Welcome.php';
            header("Location: $url"); // Page redirecting to home.php 
        } else {
            $errorMsgLogin = "Por favor verifique los datos ingresados.";
        }
    }
}
?>
<style>
    .errorMsg {
        color: #cc0000;
        margin-bottom: 10px
    }
</style>

<link rel="stylesheet" href="../assets/css/login.css">

<body>

    <div class="container-fluid formulario">
        <div class="row height justify-content-center align-items-center">
            <div class="col-7 img height ">
            </div>
            <div class="col-5 login">
                <div id="login">
                    <form method="POST" action="" name="login" autocomplete="off">
                        <div class="item">
                            <label for="loginUser">Usuario</label>
                            <br>
                            <br>
                            <input class="form-control form-control-lg" id="loginUser" name="loginUser" type="text" placeholder="Usuario" />
                        </div>
                        <br>
                        <br>
                        <div class="item">
                            <label for="loginPassword">Contraseña</label>
                            <br>
                            <br>
                            <input class="form-control form-control-lg" id="loginPassword" name="loginPassword" type="password" placeholder="Password" />
                        </div>
                        <div class="errorMsg"><?php echo $errorMsgLogin; ?></div>
                        <br><br>
                        <div class="row justify-content-center align-items-center mb-1">
                            <div class="col">
                                <div class="d-flex justify-content-center">
                                    <input type="submit" class="btn btn-outline-light  btn-lg" name="loginSubmit" value="Ingresar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>