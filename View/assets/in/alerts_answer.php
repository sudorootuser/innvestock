<script>
    <?php
    // Se valida la creación del nuevo regitro de manera éxitosa por el metodo GET
    if (isset($_GET['up'])) {

        $decryp = encrypt_decrypt('decrypt', $_GET['up']);
        if ($decryp == '1') {
    ?>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Registro actualizado',
                showConfirmButton: false,
                timer: 1500

            })
            setTimeout(function() {
                window.location.href = "<?php BASE_URL ?><?php echo $url?>";
            }, 2000);

        <?php
        }
    }

    // Se valida la actualización del regitro de manera éxitosa por el metodo GET
    if (isset($_GET['cr'])) {
        $decryp = encrypt_decrypt('decrypt', $_GET['cr']);
        if ($decryp == '1') {
        ?>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Registro creado',
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(function() {
                window.location.href = "<?php BASE_URL ?><?php echo $url?>";
            }, 2000);

    <?php
        } else {
            header('Location: ../dashboard.php');
        }
    } ?>

  
</script>