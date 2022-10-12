<?php
if ($_GET['status'] == "sts") {
    $errorMsg = 8;
    if ($errorMsg) {
        unset($_SESSION['id_client_pro']);
        unset($_SESSION["productosDs"]); ?>
        <div class="flash-error" data-flashdata="<?= $errorMsg; ?>"></div>
<?php
    }
}
?>
<script>
    $('.btn_table_del').on("click", function(e) {

        e.preventDefault();

        console.log('ingresa');

        const href = $(this).attr('href')

        Swal.fire({
            title: '¿Estas seguro?',
            text: "¡No podras revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, eliminar!'
        }).then((result) => {
            if (result.value) {
                document.location.href = href;
            }
        });
    });
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // Alerta de eliminación
    const flashdata = $('.flash-error').data('flashdata')
    

    if (flashdata == 8) {
        setTimeout(function() {
            window.location.href = "<?php BASE_URL ?><?php echo $urlnew ?>";
        }, 100);
    }
    // Fin de la función
</script>