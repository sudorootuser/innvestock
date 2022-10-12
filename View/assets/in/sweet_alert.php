 <?php if (isset($_SESSION['idUsuario']) && $_SESSION['idUsuario'] != '') { ?>
     <script>
         Swal.fire({
             title: 'Estas seguro?',
             text: "You won't be able to revert this!",
             icon: 'warning',
             button: 'Ok. Done!'
         });
     </script>
 <?php } ?>
 <script>
     $(document).ready(function() {
         $('.delete_btn_ajax').click(function() {
                console.log(deleteif)
         });
     });
 </script>