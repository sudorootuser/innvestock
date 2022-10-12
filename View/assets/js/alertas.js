
function alertas_ajax(alerta) {
    // Alerta simple
    if (alerta.Alerta === "simple") {
        Swal.fire({
            icon: alerta.Icono,
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                }, 90)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
        }).then(() => {
            window.location.href = alerta.href;
        });

    }
    // Alerta  de registro
    else if (alerta.Alerta === 'registro') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        })

        Toast.fire({
            icon: 'success',
            title: alerta.Titulo
        }).then(() => {
            window.location.href = alerta.href;
        });
    }
    // Alerta de error 
    else if (alerta.Alerta === "error") {
        Swal.fire({
            icon: 'error',
            title: alerta.Titulo,
            text: alerta.Texto,
            type: alerta.Tipo,
            confirmButtonText: 'Aceptar'
        });
        // .then((result) => {
        //     if (result.value) {

        //     }
        // });
    }
    // Alerta de carge
    else if (alerta.Alerta === "load") {
        let timerInterval
        Swal.fire({
            title: 'Procesando solicitud!',
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                }, 100)
            },
            willClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
                // window.location.href = 'http://localhost/alertas/';

            }
        })

    }
    // Alerta de carge
    else if (alerta.Alerta === "question") {

        Swal.fire({
            title: '¿Estas seguro de continuar?',
            text: "¡Las cantidades no coinciden, se creara un nuevo alistamiento con las cantidades restantes!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, continuar!'
        }).then((result) => {
            if (result.value == true) {
                
            } else {
                window.location.reload()
            }
        });

    }

}

