<div class="row justify-content-center align-items-center mb-1">
    <div class="col">
        <div class="d-flex justify-content-center">
            <nav class="text-center">
                <ul class="pagination">
                    <!-- Si la página actual es igual a uno, deshabilitamos el boton de regresar -->
                    <?php if ($page == 1) { ?>
                        <li class="page-item disabled">
                            <a class="page-link" tabindex="-1" aria-disabled="true" href="./<?php echo $url ?>?pg=<?php echo encrypt_decrypt('encrypt', $page - 1); ?> ">
                                Regresar
                            </a>
                        </li>
                    <?php } else { ?>
                        <!-- Si la página actual es diferente, se habilita el boton de siguiente -->
                        <li class="page-item">
                            <a class="page-link" tabindex="-1" aria-disabled="true" href="./<?php echo $url ?>?pg=<?php echo encrypt_decrypt('encrypt', $page - 1); ?> ">
                                Regresar
                            </a>
                        </li>
                    <?php } ?>
                    <!-- Mostramos enlaces para ir a todas las páginas. Es un simple ciclo for-->
                    <?php for ($x = 1; $x <= $total_pages; $x++) { ?>
                        <li class="<?php if ($x == $page)  echo "page-item active" ?>">
                            <a class="page-link" href="./<?php echo $url ?>?pg=<?php echo  encrypt_decrypt('encrypt', $x); ?>">
                                <?php echo $x ?></a>
                        </li>
                    <?php } ?>
                    <!-- Si la página actual es menor al total de páginas, mostramos un botón para ir una página adelante -->
                    <?php if ($page < $total_pages) { ?>
                        <li class="page-item">
                            <a class="page-link" href="./<?php echo $url ?>?pg=<?php echo encrypt_decrypt('encrypt', $page + 1); ?>">
                                Siguiente
                            </a>
                        </li>
                        <!-- Si la página actual es igual al total de páginas, se deshabilita el boton de siguiente -->
                    <?php } else if ($page == $total_pages) { ?>
                        <li class="page-item disabled">
                            <a class="page-link" tabindex="-1" aria-disabled="true" href="./<?php echo $url ?>?pg=<?php echo encrypt_decrypt('encrypt', $page - 1); ?> ">
                                Siguiente
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</div>