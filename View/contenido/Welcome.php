<?php include '../assets/in/Head.php';?>

<link rel="stylesheet" href="../assets/css/welcome.css">

<body onload="redireccionar()">
    <div class="container">
        <div class="row">
            <div class="col-4 img-cub">
                <img src="../assets/img/cubo.png" class="rotate">
            </div>
            <div class="col-8 img_wel">
                <img src="../assets/img/innvestock.png">
                <br>
                <span class="text">¡Bienvenid@ <?php echo $_SESSION['us_nombre'] . ' ' . $_SESSION['us_apellido']; ?>!</span>
            </div>
        </div>
    </div>
    <script language="JavaScript">
        function redireccionar() {
            setTimeout("location.href='dashboard.php'", 1300);
        }
    </script>
</body>