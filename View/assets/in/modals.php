<style>
  .modal-xl {
    max-width: 90% !important;
  }
</style>
<!-- Modal client -->
<div class="modal fade" id="update_client_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL CLIENTE</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <div class="row">
              <div class="col">
                <label class="form-label">Tipo de identificacion</label>
                <select class="form-select" aria-label="Default select example" id="tpId_see">
                  <option value="NIT">NIT</option>
                  <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                  <option value="Cédula de extrangería">Cédula de extranjeria</option>
                </select>
              </div>
              <div class="col">
                <label class="form-label">Identificacion</label>
                <input type="text" class="form-control" id="nDocument_see">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="N-Documento" class="form-label">Digito de Verificacion</label>
                <input type="text" class="form-control" id="dv_see" aria-describedby="DV">
              </div>
              <div class="col">
                <label class="form-label">Estado</label>
                <select class="form-select" id="estado_see">
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
                </select>
              </div>
            </div>
            <div class="row">

              <div class="col">
                <label for="N-Documento" class="form-label">Nombre / Razón Social</label>
                <input type="text" class="form-control" id="nombre_see" aria-describedby="pNombre">
              </div>
              <div class="col">
                <label for="N-Documento" class="form-label">Apellido </label>
                <input type="text" class="form-control" id="apellido_see" aria-describedby="sApellido">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label for="N-Documento" class="form-label">Actividad Economica </label>
                <input type="text" class="form-control" id="actEco_see" aria-describedby="actEco">
              </div>
              <div class="col">
                <label for="N-Documento" class="form-label">Direccion Principal</label>
                <input type="text" class="form-control" id="direccion_see" aria-describedby="Direccion">
              </div>
            </div>
            <div class="row">

              <div class="col">
                <label for="N-Documento" class="form-label">Telefono Principal</label>
                <input type="number" class="form-control" id="telefono_see" aria-describedby="Telefono">
              </div>
              <div class="col">
                <label for="N-Documento" class="form-label">Ciudad</label>
                <input type="text" class="form-control" id="ciudad_see" aria-describedby="ciudad">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label class="form-label">Tipo de Cliente</label>
                <select class="form-select" id="tpCliente_see">
                  <option value="Importador">Importador</option>
                  <option value="Exportador">Exportador</option>
                  <option value="Distribuidor">Distribuidor</option>
                  <option value="Fabricante">Fabricante</option>
                  <option value="Tercero">Tercero</option>
                </select>
              </div>
            </div>

            <!-- <div class="row">
              <div class="col-sm-12">
                <div class="card-body table-responsive">
                  <table class="table" id="table_client">
                    <thead id="thead_client">
                      <tr>
                        <th scope="col">Bodega asociada</th>
                        <th scope="col">Fecha de registro</th>
                      </tr>
                    </thead>
                    <tbody id="tbody_client">
                      <?php
                      $bodegas = explode(",", $_SESSION['cliente_bodega']['bodegas']);
                      $fechas = explode(",", $_SESSION['cliente_bodega']['fechas']);
                      ?>

                      <?php for ($i = 0; $i < count($bodegas); $i++) {  ?>
                        <tr>
                          <td><?php echo $bodegas[$i]; ?></td>
                          <td><?php echo $fechas[$i]; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div> -->

          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal user -->
<div class="modal fade" id="user_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL USUARIO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <div class="row">
              <div class="col">
                <label class="form-label">Tipo de identificacion: </label>
                <input class="form-control" type="text" id="user_tpDocument">
              </div>
              <div class="col">
                <label class="form-label"># Identificacion: </label>
                <input type="text" class="form-control" id="user_document">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="Fecha_nacimiento" class="form-label">Fecha de nacimiento: </label>
                <input type="date" class="form-control" id="fecha_nacimiento" aria-describedby="fecha_nacimiento">
              </div>
              <div class="col">
                <label class="form-label">Estado: </label>
                <input type="text" class="form-control" id="user_estado">
              </div>
            </div>
            <div class="row">

              <div class="col">
                <label for="user_nombre" class="form-label">Nombre: </label>
                <input type="text" class="form-control" id="user_nombre" aria-describedby="user_nombre">
              </div>
              <div class="col">
                <label for="user_apellido" class="form-label">Apellido </label>
                <input type="text" class="form-control" id="user_apellido" aria-describedby="user_apellido">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label for="user_correo" class="form-label">Correo: </label>
                <input type="text" class="form-control" id="user_correo" aria-describedby="user_correo">
              </div>
              <div class="col">
                <label for="user_direccion" class="form-label">Direccion</label>
                <input type="text" class="form-control" id="user_direccion" aria-describedby="user_direccion">
              </div>
            </div>
            <div class="row">

              <div class="col">
                <label for="user_bodega" class="form-label">Bodega</label>
                <input type="text" class="form-control" id="user_bodega" aria-describedby="user_bodega">
              </div>
              <div class="col">
                <label class="form-label">Tipo de Rol</label>
                <input type="text" class="form-control" id="user_rol" aria-describedby="user_rol">
              </div>
            </div>

          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal Product -->
<div class="modal fade" id="update_product_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL PRODUCTOS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <input type="hidden" id="product_see">

            <div class="row">
              <div class="col">
                <label class="form-label">Codigo / Referencia</label>
                <input type="text" class="form-control" id="producto_codigo_see">
              </div>
              <div class="col">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="producto_nombre_see">
              </div>
              <div class="col">
                <label class="form-label">Cliente</label>
                <input type="text" class="form-control" id="producto_idCliente_see">
              </div>
              <div class="col">
                <label class="form-label">RFID</label>
                <input type="text" class="form-control" id="producto_RFID_see">
              </div>
            </div>
            <style>
              .pedido {
                color: #0a53be;
              }

              .bajo {
                color: #dc3545;
              }
            </style>
            <div class="row">
              <div class="col">
                <label class="form-label">Minimo</label>
                <input type="number" class="form-control" id="producto_minimo_see">
              </div>
              <div class="col">
                <label class="form-label">Maximo</label>
                <input type="number" class="form-control" id="producto_maximo_see">
              </div>
              <div class="col">
                <label class="form-label">Alerta</label>
                <input type="text" class="form-control alerta" id="producto_alerta_see">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Cantidad</label>
                <input type="text" class="form-control" id="producto_cantidad_see">
              </div>


              <div class="col">
                <label class="form-label">Peso c/u</label>
                <input type="text" class="form-control" id="producto_peso_see">
              </div>

              <div class="col">
                <label class="form-label">Peso Total</label>
                <input type="text" class="form-control" id="producto_pesoTotal_see">
              </div>

            </div>

            <div class="row">


              <div class="col">
                <label class="form-label">Ancho</label>
                <input type="text" class="form-control" id="producto_ancho_see">
              </div>
              <div class="col">
                <label class="form-label">Alto</label>
                <input type="text" class="form-control" id="producto_alto_see">
              </div>
              <div class="col">
                <label class="form-label">Largo</label>
                <input type="text" class="form-control" id="producto_largo_see">
              </div>

            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" id="producto_modelo_see">
              </div>
              <div class="col">
                <label class="form-label">Serial</label>
                <input type="text" class="form-control" id="producto_serial_see">
              </div>
              <div class="col">
                <label class="form-label">Lote</label>
                <input type="text" class="form-control" id="producto_lote_see">
              </div>

              <div class="col">
                <label class="form-label">Marca</label>
                <input type="text" class="form-control" id="producto_marca_see">
              </div>

            </div>

            <div class="row">
              <div class="col">
                <label class="form-label">Rotacion</label>
                <select class="form-select" aria-label="Default select example" id="producto_rotacion_see">
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                </select>
              </div>
              <div class="col">
                <label class="form-label">Dias de aviso de vencimiento</label>
                <input type="number" class="form-control" id="producto_diasAviso_see">
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label class="form-label">Descripcion</label>
                <input type="text" class="form-control" id="producto_descripcion_see">
              </div>

              <div class="col">
                <label class="form-label">Precio</label>
                <input type="number" class="form-control" id="producto_precio_see">
              </div>

              <div class="col">
                <label class="form-label">Ubicación</label>
                <input type="text" class="form-control" id="producto_ubicacion_see">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Fecha de Vencimiento</label>
                <input type="date" class="form-control" id="producto_fechaVenc_see">
              </div>

              <div class="col">
                <label class="form-label">Número de Contenedor</label>
                <input type="number" class="form-control" id="producto_nContenedor_see">
              </div>
            </div>
            <div class="row">

              <div class="col">
                <label class="form-label">Cantidad en Alistamiento</label>
                <input type="number" class="form-control" id="producto_cantidadAlis_see">
              </div>

              <div class="col">
                <label class="form-label">Peso en Alistamiento</label>
                <input type="text" class="form-control" id="producto_cantidadAlisPeso_see">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Cantidad Bloqueada</label>
                <input type="text" class="form-control" id="producto_cantidadBlock_see">
              </div>

              <div class="col">
                <label class="form-label">Peso en Bloqueos</label>
                <input type="text" class="form-control" id="producto_cantidadBlockPeso_see">
              </div>


            </div>
            <div class="row">
              <div class="col">
                <label class="form-label">Peso Sub-Total</label>
                <input type="text" class="form-control" id="producto_pesoSubTotal_see">
              </div>
              <div class="col">

              </div>

            </div>
          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal Alistado -->
<div class="modal fade" id="alistado_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL PRE-INGRESO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <div class="row">
              <div class="col">
                <label class="form-label">Consecutivo</label>
                <input type="text" class="form-control" id="alistado_consecutivo_see">
              </div>

              <div class="col">
                <label class="form-label">Cliente</label>
                <input type="text" class="form-control" id="alistado_cliente_see">
              </div>

              <div class="col">
                <label for="N-Documento" class="form-label">Fecha Entrada</label>
                <input type="date" class="form-control" id="alistado_fecha_see" aria-describedby="DV">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Nombre Conductor</label>
                <input type="text" class="form-control" id="alistado_nombrePersona_see">
              </div>

              <div class="col">
                <label class="form-label">Cedula Conductor</label>
                <input type="text" class="form-control" id="alistado_cedulaPersona_see">
              </div>

              <div class="col">
                <label class="form-label">Placa Conductor</label>
                <input type="text" class="form-control" id="alistado_placaPersona_see">
              </div>
            </div>


          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>


<!-- Modal Alistado Ds-->
<div class="modal fade" id="alistadoDs_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL ALISTADO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <div class="row">
              <div class="col">
                <label class="form-label">Consecutivo</label>
                <input type="text" class="form-control" id="alistadoDs_consecutivo_see">
              </div>

              <div class="col">
                <label class="form-label">Cliente</label>
                <input type="text" class="form-control" id="alistadoDs_cliente_see">
              </div>

              <div class="col">
                <label for="N-Documento" class="form-label">Fecha Entrada</label>
                <input type="date" class="form-control" id="alistadoDs_fecha_see" aria-describedby="DV">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Nombre Conductor</label>
                <input type="text" class="form-control" id="alistadoDs_nombrePersona_see">
              </div>

              <div class="col">
                <label class="form-label">Cedula Conductor</label>
                <input type="text" class="form-control" id="alistadoDs_cedulaPersona_see">
              </div>

              <div class="col">
                <label class="form-label">Placa Conductor</label>
                <input type="text" class="form-control" id="alistadoDs_placaPersona_see">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Cliente Final</label>
                <input type="text" class="form-control" id="alistadoDs_clienteF_see">
              </div>

              <div class="col">
                <label class="form-label">Codigo</label>
                <input type="text" class="form-control" id="alistadoDs_codigo_see">
              </div>


            </div>
            <div class="row">

              <div class="col">
                <label class="form-label">Cliente Final</label>
                <input type="text" class="form-control" id="alistadoDs_clienteF_see">
              </div>

              <div class="col">
                <label class="form-label">Codigo</label>
                <input type="text" class="form-control" id="alistadoDs_codigo_see">
              </div>


            </div>
            <div class="row">

              <div class="col">
                <label class="form-label">Observacion</label>
                <textarea class="form-control" id="alistadoDs_observacion_see"></textarea>
              </div>


            </div>
          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal Entrada-->
<div class="modal fade" id="entrada_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DE LA ENTRADA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <div class="row">
              <div class="col">
                <label class="form-label">Consecutivo</label>
                <input type="text" class="form-control" id="entrada_consecutivo_see">
              </div>

              <div class="col">
                <label class="form-label">Cliente</label>
                <input type="text" class="form-control" id="entrada_cliente_see">
              </div>

            </div>
            <div class="row">

              <div class="col">
                <label for="N-Documento" class="form-label">Fecha Entrada</label>
                <input type="datetime" class="form-control" id="entrada_fecha_see" aria-describedby="DV">
              </div>
              <div class="col">
                <label class="form-label">Nombre Conductor</label>
                <input type="text" class="form-control" id="entrada_nombrePersona_see">
              </div>
            </div>


            <div class="row">

              <div class="col">
                <label class="form-label">Cedula Conductor</label>
                <input type="text" class="form-control" id="entrada_cedulaPersona_see">
              </div>

              <div class="col">
                <label class="form-label">Placa Conductor</label>
                <input type="text" class="form-control" id="entrada_placaPersona_see">
              </div>
            </div>

          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal despacho-->
<div class="modal fade" id="see_despacho_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL DESPACHO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <div class="row">
              <div class="col">
                <label class="form-label">Consecutivo</label>
                <input type="text" class="form-control" id="despacho_consecutivo_see">
              </div>

              <div class="col">
                <label class="form-label">Cliente</label>
                <input type="text" class="form-control" id="despacho_cliente_see">
              </div>

              <div class="col">
                <label for="N-Documento" class="form-label">Fecha Entrada</label>
                <input type="datetime" class="form-control" id="despacho_fecha_see" aria-describedby="DV">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Nombre Conductor</label>
                <input type="text" class="form-control" id="despacho_nombrePersona_see">
              </div>

              <div class="col">
                <label class="form-label">Cedula Conductor</label>
                <input type="text" class="form-control" id="despacho_cedulaPersona_see">
              </div>

              <div class="col">
                <label class="form-label">Placa Conductor</label>
                <input type="text" class="form-control" id="despacho_placaPersona_see">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Cliente Final</label>
                <input type="text" class="form-control" id="despacho_clienteF_see">
              </div>

              <div class="col">
                <label class="form-label">Codigo</label>
                <input type="text" class="form-control" id="despacho_codigo_see">
              </div>


            </div>
          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>


<!-- Modal see image -->
<div class="modal fade" id="see_image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-left" id="exampleModalLabel">Imagenes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <div class="card">
            <div class="card-body table-responsive">
              <div class="row">
                <div class="col-sm-6 col-mb-6" id="IMG_1" style="text-align:center;align-items:center;align-content: center;">
                </div>
                <div class="col-sm-6 col-sm-6" id="IMG_2" style="text-align:center;align-items:center;align-content: center;">
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal image -->
<div class="modal fade" id="new_image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Lista de Imagenes </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="../../Ajax/ajax_image.php" enctype="multipart/form-data" method="post">
          <input type="hidden" name="save_img" value="product_image_new">
          <div>
            <label for="imagen" class="form-label">Agregar Imagen</label>
            <input type="file" class="form-control" name="imagen" size="30" type="file">
          </div>
          <br>
          <!-- Tabla -->
          <table class="table align-middle">
            <thead class="table-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre de la imagen</th>
                <th scope="col">Tipo de Imagen</th>
                <th scope="col">Fecha de carge</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($_SESSION['imagenes_new'])) {
                foreach ($_SESSION['imagenes_new'] as $rows) { ?>
                  <tr>
                    <th scope="row">1</th>
                    <td><?php echo $rows['Nombre']; ?></td>
                    <td><?php echo $rows['Tipo']; ?></td>
                    <td><?php echo $rows['size']; ?></td>
                  </tr>
                <?php }
              } else { ?>
                <tr class="text-center">
                  <td colspan="4">No hay registros en el sistema</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Alistamiento-->
<div class="modal fade" id="update_enlisted_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL ALISTAMIENTO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>

            <div class="row">
              <div class="col">
                <label for="N-Documento" class="form-label">Consecutivo</label>
                <input type="text" class="form-control" id="alistado_idAlistado_see">
              </div>
              <div class="col">
                <label class="form-label">Tipo</label>
                <input type="text" class="form-control" id="alistado_tipo_see">
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="N-Documento" class="form-label">Fecha Entrada</label>
                <input type="date" class="form-control" id="alistado_fechaEntrada_see">
              </div>
              <div class="col">
                <label for="N-Documento" class="form-label">Cliente</label>
                <input type="text" class="form-control" id="alistado_idCliente_see">
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="N-Documento" class="form-label">Estado</label>
                <input type="text" class="form-control" id="alistado_estado_see">
              </div>
              <div class="col">

              </div>
            </div>
          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal Alistamiento Ds-->
<div class="modal fade" id="list_enlistedDs_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL ALISTAMIENTO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>

            <div class="row">
              <div class="col">
                <label for="N-Documento" class="form-label">Consecutivo</label>
                <input type="text" class="form-control" id="alistadoDs_idAlistado_see">
              </div>
              <div class="col">
                <label class="form-label">Tipo</label>
                <input type="text" class="form-control" id="alistadoDs_tipo_see">
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="N-Documento" class="form-label">Fecha Despacho</label>
                <input type="date" class="form-control" id="alistadoDs_fechaDespacho_see">
              </div>
              <div class="col">
                <label for="N-Documento" class="form-label">Cliente</label>
                <input type="text" class="form-control" id="alistadoDs_idCliente_see">
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="N-Documento" class="form-label">Estado</label>
                <input type="text" class="form-control" id="alistadoDs_estado_see">
              </div>
              <div class="col">

              </div>
            </div>
          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal producto_alistado enlisted new-->
<div class="modal fade" id="producto_alistado_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Barra de Busqueda -->

        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
              </div>
            </nav>
          </div>
        </div>
        <div class="mb-4 ">
          <?php $count = 1; ?>
          <div class="row mb-3">
            <div class="col">
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>

          <!-- Inicio de las tablas -->
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad en bodega</th>
                  <th scope="col">Cantidad a Agregar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($dataUs) {
                  foreach ($dataUs as $rowPr) {  ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad']; ?></td>

                      <form action="../../Ajax/ajax_enlisted.php" method="post">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->
          </div>
        </div>
        </fieldset>
      </div>
    </div>
  </div>
</div>

<!-- Modal producto_alistadoDs-->
<div class="modal fade" id="producto_alistadoDs_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>
        <div class="mb-4 ">
          <?php $count = 1; ?>

          <div class="row mb-3">
            <div class="col">
              <!-- Barra de busqueda -->
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad en bodega</th>
                  <th scope="col">Cantidad a alistar</th>

                </tr>
              </thead>
              <tbody>
                <?php if ($dataUs) {
                  foreach ($dataUs as $rowPr) { ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad']; ?></td>
                      <?php if ($rowPr['producto_bodega_cantidad'] > 0) { ?>
                        <form action="../../Ajax/ajax_enlistedDs.php" method="post">
                          <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>

                          <td>
                            <input type="number" class="form-control" name="cantidad" id="cantidad">
                          </td>
                          <!-- Button trigger modal -->
                          <td>
                            <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                          </td>
                        </form>
                      <?php
                      } else if ($rowPr['producto_bodega_cantidad'] <= 0) { ?>
                        <td>
                          <input type="text" class="form-control" value="Producto Agotado" disabled>
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button class="btn btn-success" name="" disabled><i class="fa-solid fa-plus"></i></button>
                        </td>

                      <?php
                      }
                      ?>

                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal producto_alistadoDs-->
<div class="modal fade" id="update_alistadoDs_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>
        <div class=" mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="row mb-3">
            <div class="col">
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad disponible</th>
                  <th scope="col">Cantidad alistada</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($dataUs) {

                  foreach ($dataUs as $rowPr) {
                ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad']; ?></td>

                      <td><?php echo $rowPr['producto_bodega_cantidadAlis']; ?></td>
                      <?php if ($rowPr['producto_bodega_cantidad'] > 0) { ?>

                        <form action="../../Ajax/ajax_enlistedDs.php" method="post">
                          <input type="hidden" name="up" value="up">
                          <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                          <td>
                            <input class="form-control" type="number" name="cantidad" id="cantidad">
                          </td>
                          <!-- Button trigger modal -->
                          <td>
                            <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                          </td>
                        </form>
                      <?php } else if ($rowPr['producto_bodega_cantidad'] <= 0) { ?>
                        <td>
                          <input class="form-control" type="text" disabled value="Producto Agotado">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" disabled><i class="fa-solid fa-plus"></i></button>
                        </td>
                      <?php } ?>

                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal kit_alistadoDs-->
<div class="modal fade" id="kit_alistadoDs_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Kits</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
                <form class="d-flex" role="search" method="POST" action="">
                  <select class="form-select" aria-label="Default select example" name="campo" style="margin-right:8px;">
                    <option value="producto_codigo">Codigo / Referencia</option>
                    <option value="producto_nombre">Nombre</option>
                    <option value="producto_idCliente">Cliente</option>
                  </select>

                  <input class="form-control me-2" name="bus" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
              </div>
            </nav>
          </div>
        </div>
        <div class="card mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de datos
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad Para Alistar</th>

                </tr>
              </thead>
              <tbody>
                <?php

                if ($dataKit) {
                  foreach ($dataKit as $rowPr) {

                ?>
                    <tr>
                      <td><?php echo $rowPr['kit_consecutivo'] ?></td>
                      <td><?php echo $rowPr['kit_nombre'] ?></td>
                      <td><?php echo $rowPr['kit_peso'] ?> Kg</td>

                      <form action="../../Ajax/ajax_enlistedDs.php" method="post">
                        <input type="text" name="id_kit" id="id_producto" value="<?php echo $rowPr['idKit'] ?>" hidden>
                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal kit_alistadoDs-->
<div class="modal fade" id="kit_alistadoDsUp_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Kits Up</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
                <form class="d-flex" role="search" method="POST" action="">
                  <select class="form-select" aria-label="Default select example" name="campo" style="margin-right:8px;">
                    <option value="producto_codigo">Codigo / Referencia</option>
                    <option value="producto_nombre">Nombre</option>
                    <option value="producto_idCliente">Cliente</option>
                  </select>

                  <input class="form-control me-2" name="bus" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
              </div>
            </nav>
          </div>
        </div>
        <div class="card mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de datos
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad Para Alistar</th>

                </tr>
              </thead>
              <tbody>
                <?php

                if ($dataKit) {
                  foreach ($dataKit as $rowPr) {

                ?>
                    <tr>
                      <td><?php echo $rowPr['kit_consecutivo'] ?></td>
                      <td><?php echo $rowPr['kit_nombre'] ?></td>
                      <td><?php echo $rowPr['kit_peso'] ?> Kg</td>

                      <form action="../../Ajax/ajax_enlistedDs.php" method="post">
                        <input type="text" name="id_kit" id="id_producto" value="<?php echo $rowPr['idKit'] ?>" hidden>
                        <input type="text" name="up_kit" id="up_kit" value="up_kit" hidden>

                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal producto_alistado-->
<div class="modal fade" id="update_alistado_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>
        <div class="mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="row mb-3">
            <div class="col">
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad disponible</th>
                  <th scope="col">Cantidad alistada</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($dataUs)) {
                  foreach ($dataUs as $rowPr) {
                ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad']; ?></td>
                      <td><?php echo $rowPr['producto_bodega_cantidadAlis']; ?></td>

                      <form action="../../Ajax/ajax_enlisted.php" method="post">
                        <input type="hidden" name="up" value="up">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <td>
                          <input class="form-control" type="number" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal producto_ingreso-->
<div class="modal fade" id="producto_ingreso_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
                <form class="d-flex" role="search" method="POST" action="">
                  <select class="form-select" aria-label="Default select example" name="campo" style="margin-right:8px;">
                    <option value="producto_codigo">Codigo / Referencia</option>
                    <option value="producto_nombre">Nombre</option>
                    <option value="producto_idCliente">Cliente</option>
                  </select>

                  <input class="form-control me-2" name="bus" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
              </div>
            </nav>
          </div>
        </div>
        <div class="card mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de datos
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Precio</th>
                  <th scope="col">Cantidad En Inventario</th>
                  <th scope="col">Cantidad Para Agregar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($dataPr) {
                  foreach ($dataPr as $rowPr) {
                ?>
                    <tr>
                      <td>#</td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_precio'] ?></td>
                      <td><?php echo $rowPr['producto_cantidad']; ?></td>

                      <form action="../../Ajax/ajax_enlisted.php" method="post">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->
          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal Tareas-->
<div class="modal fade" id="tarea_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">DATOS DEL PRODUCTOS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <fieldset disabled>
          <form>
            <input type="hidden" id="product_see">
            <div class="row">

              <div class="col">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="tarea_nombre_see">
              </div>

            </div>
            <div class="row">
              <div class="col">
                <label class="form-label">Codigo / Referencia</label>
                <input type="text" class="form-control" id="tarea_codigo_see">
              </div>

              <div class="col">
                <label class="form-label">Consecutivo</label>
                <input type="text" class="form-control" id="tarea_consecutivo_see">
              </div>
              <div class="col">
                <label class="form-label">Cliente</label>
                <input type="text" class="form-control" id="tarea_idCliente_see">
              </div>
            </div>

            <div class="row">

              <div class="col">
                <label class="form-label">Prioridad</label>
                <input type="text" class="form-control" id="tarea_prioridad_see">
              </div>

              <div class="col">
                <label class="form-label">Cantidad Bloqueada</label>
                <input type="text" class="form-control" id="tarea_cantidad_see">
              </div>

              <div class="col">
                <label class="form-label">Origen</label>
                <input type="text" class="form-control" id="tarea_origen_see">
              </div>
              <div class="col">
                <label class="form-label">Usuario</label>
                <input type="text" class="form-control" id="tarea_usuario_see">
              </div>
            </div>
            <div class="row">

              <div class="col">
                <label class="form-label">Descripcion</label>
                <textarea class="form-control" id="tarea_descripcion_see"></textarea>
              </div>

            </div>

          </form>
        </fieldset>
      </div>

    </div>
  </div>
</div>
<!-- Modal Despacho -->
<div class="modal fade" id="despacho_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>

        <div class="mb-4 ">
          <?php $count = 1; ?>
          <div class="row mb-3">
            <div class="col">
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad en bodega</th>
                  <th scope="col">Cantidad alistada</th>
                  <th scope="col">Cantidad para alistar</th>

                </tr>
              </thead>
              <tbody>
                <?php if ($dataPr) {
                  foreach ($dataPr as $rowPr) { ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad'] + $rowPr['producto_bodega_cantidadAlis'] ?></td>
                      <td><?php echo $rowPr['producto_bodega_cantidadAlis'] ?></td>
                      <?php if ($rowPr['producto_bodega_cantidadAlis'] > 0) { ?>
                        <form action="../../Ajax/ajax_dispatch.php" method="post">
                          <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                          <td>
                            <input type="number" class="form-control" name="cantidad" id="cantidad">
                          </td>
                          <!-- Button trigger modal -->
                          <td>
                            <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                          </td>
                        </form>
                      <?php } else { ?>
                        <td>
                          <input type="text" class="form-control" disabled value="Producto Agotado">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button class="btn btn-success" disabled><i class="fa-solid fa-plus"></i></button>
                        </td>
                    </tr>
                <?php
                      }
                      $count++;
                    }
                  } else { ?>
                <tr class="text-center">
                  <td colspan="6">No hay registros en el sistema</td>
                </tr>
              <?php  } ?>
              </tbody>
            </table>
          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal Ingreso o reception.php -->
<div class="modal fade" id="reception_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>
        <div class="mb-4 ">
          <?php $count = 1; ?>
          <div class="row mb-3">
            <div class="col">
              <!-- Barra de busqueda -->
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                  <option value="producto_idCliente" <?php echo $resultado = $campo_1 == "producto_idCliente" ? "selected" : ''; ?>>Cliente</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"># Consecutivo</th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad en Bodega</th>
                  <th scope="col">Cantidad a Ingresar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (!empty($dataPr)) {
                  foreach ($dataPr as $rowPr) { ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad'] ?></td>

                      <form action="../../Ajax/ajax_reception.php" method="post">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <input type="text" name="validacion" id="validacion" value="<?php echo $echo = encrypt_decrypt('encrypt', "false"); ?>" hidden>

                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="6">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal Ingreso 2-->
<div class="modal fade" id="reception2_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>
        <div class="mb-4 ">
          <?php $count = 1; ?>

          <div class="row mb-3">
            <div class="col">
              <!-- Barra de busqueda -->
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad en bodega</th>
                  <th scope="col">Cantidad a Ingresar</th>

                </tr>
              </thead>
              <tbody>
                <?php
                if (!empty($dataPr)) {
                  foreach ($dataPr as $rowPr) { ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad'] ?></td>

                      <form action="../../Ajax/ajax_reception.php" method="post">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <input type="text" name="validacion" id="validacion" value="<?php $echo = encrypt_decrypt('encrypt', "true");
                                                                                    echo $echo; ?>" hidden>
                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class=" btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="6">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
          </div>
        </div>
        </fieldset>
      </div>
    </div>
  </div>
</div>


<!-- Modal producto_bloquear-->
<div class="modal fade" id="producto_block_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title col text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>
        <div class="mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="row mb-3">
            <div class="col">
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="producto_consecutivo" <?php echo $resultado = $campo_1 == "producto_consecutivo" ? "selected" : ''; ?>>Consecutivo Producto</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                  <option value="producto_idCliente" <?php echo $resultado = $campo_1 == "producto_idCliente" ? "selected" : ''; ?>>Cliente</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Buscar..." aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>


          <div class="p-3 table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Inventario</th>
                  <th scope="col">Bloquear</th>
                  <th scope="col">Descripcion</th>


                </tr>
              </thead>
              <tbody>
                <?php
                if ($dataPr) {
                  foreach ($dataPr as $rowPr) {
                ?>
                    <tr>
                      <td><?php echo $rowPr['producto_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_bodega_cantidad']; ?></td>

                      <form action="../../Ajax/ajax_product.php" method="post">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>

                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <td>
                          <textarea class="form-control" name="descrip" id="descrip"> </textarea>
                        </td>
                        <td>
                          <select class="form-select" name="prioridad" required>
                            <option value="Control">Control</option>
                            <option value="Alto">Alto</option>
                            <option value="Medio">Medio</option>
                            <option value="Bajo">Bajo</option>
                          </select>
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal producto_Desbloquear-->
<div class="modal fade" id="producto_blockUp_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">

              </div>
            </nav>
          </div>
        </div>
        <div class="mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="row mb-3">
            <div class="col">
              <form class="d-flex" role="search" method="POST" action="">
                <select class="form-select" aria-label="Default select example" name="campo_1" style="margin-right:8px;">
                  <option value="tarea_consecutivo" <?php echo $resultado = $campo_1 == "tarea_consecutivo" ? "selected" : ''; ?>>Consecutivo Tarea</option>
                  <option value="producto_codigo" <?php echo $resultado = $campo_1 == "producto_codigo" ? "selected" : ''; ?>>Codigo / Referencia</option>
                  <option value="producto_nombre" <?php echo $resultado = $campo_1 == "producto_nombre" ? "selected" : ''; ?>>Nombre</option>
                  <option value="producto_idCliente" <?php echo $resultado = $campo_1 == "producto_idCliente" ? "selected" : ''; ?>>Cliente</option>
                </select>
                <input class="form-control me-2" name="bus_1" type="search" placeholder="Search" aria-label="Search" value="<?php echo $resultado = empty($_POST['bus_1']) ? '' : $_POST['bus_1']; ?>">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </div>
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Bloqueado</th>
                  <th scope="col">Desloquear</th>
                  <th scope="col">Descripcion</th>




                </tr>
              </thead>
              <tbody>
                <?php
                if ($dataPr) {
                  foreach ($dataPr as $rowPr) {
                ?>
                    <tr>
                      <td><?php echo $rowPr['tarea_consecutivo'] ?></td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['tarea_novedad']; ?></td>

                      <form action="../../Ajax/ajax_product.php" method="post">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <input type="text" name="Unlock" id="Unlock" value="Unlock" hidden>
                        <input type="text" name="id_block" id="id_block" value="<?php echo $rowPr['idTarea'] ?>" hidden>



                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <td>
                          <textarea type="number" class="form-control" name="descrip" id="descrip"></textarea>
                        </td>

                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal bodega-->
<div class="modal fade" id="bodega_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Elegir Bodega</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Barra de Busqueda -->
        <!-- <div class="mb-4">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
                <form class="d-flex" role="search" method="POST" action="">
                  <select class="form-select" aria-label="Default select example" name="campo" style="margin-right:8px;">
                    <option value="producto_codigo">Codigo / Referencia</option>
                    <option value="producto_nombre">Nombre</option>
                    <option value="producto_idCliente">Cliente</option>
                  </select>
                  <input class="form-control me-2" name="bus" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
              </div>
            </nav>
          </div>
        </div> -->
        <div class="mb-4 ">
          <?php
          $count = 1;
          ?>

          <!-- Inicio de las tablas -->
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Ciudad</th>


                </tr>
              </thead>
              <tbody>
                <?php
                if ($dataBodega) {
                  foreach ($dataBodega as $rowPr) {
                ?>
                    <tr>

                      <td>BD-<?php echo $rowPr['idBodega'] ?></td>
                      <td><?php echo $rowPr['bodega_nombre'] ?></td>
                      <td><?php echo $rowPr['ciudad_nombre'] ?></td>

                      <form action="../../Ajax/ajax_main.php" method="post">

                        <!-- Button trigger modal -->
                        <td><input type="hidden" name="id_bodega" value="<?php echo $rowPr['idBodega'] ?>"></td>
                        <td><input type="hidden" name="nombre_bodega" value="<?php echo $rowPr['bodega_nombre'] ?>"></td>

                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-check"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->
          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal producto_kit-->
<div class="modal fade" id="kit_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar ProductosS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Barra de Busqueda -->
        <div class="mb-4">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
                <form class="d-flex" role="search" method="POST" action="">
                  <select class="form-select" aria-label="Default select example" name="campo" style="margin-right:8px;">
                    <option value="producto_codigo">Codigo / Referencia</option>
                    <option value="producto_nombre">Nombre</option>
                    <option value="producto_idCliente">Cliente</option>
                  </select>
                  <input class="form-control me-2" name="bus" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
              </div>
            </nav>
          </div>
        </div>
        <div class="card mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de datos
          </div>
          <!-- Inicio de las tablas -->
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad Alistada</th>
                  <th scope="col">Cantidad a Agregar</th>

                </tr>
              </thead>
              <tbody>
                <?php
                if ($dataUs) {
                  foreach ($dataUs as $rowPr) {
                ?>
                    <tr>
                      <td>#</td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidadAlis']; ?></td>

                      <form action="../../Ajax/ajax_kit.php" method="post">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->
          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal producto_alistado-->
<div class="modal fade" id="update_kit_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar ProductosS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
                <form class="d-flex" role="search" method="POST" action="">
                  <select class="form-select" aria-label="Default select example" name="campo" style="margin-right:8px;">
                    <option value="producto_codigo">Codigoo / Referencia</option>
                    <option value="producto_nombre">Nombre</option>
                    <option value="producto_idCliente">Cliente</option>
                  </select>

                  <input class="form-control me-2" name="bus" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
              </div>
            </nav>
          </div>
        </div>
        <div class="card mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de datos
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad disponible</th>
                  <th scope="col">Cantidad alistada</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($dataUs)) {
                  foreach ($dataUs as $rowPr) {
                ?>
                    <tr>
                      <td>#</td>
                      <td><?php echo $rowPr['producto_codigo'] ?></td>
                      <td><?php echo $rowPr['producto_nombre'] ?></td>
                      <td><?php echo $rowPr['producto_peso'] ?> Kg</td>
                      <td><?php echo $rowPr['producto_bodega_cantidad']; ?></td>

                      <td><?php echo $rowPr['producto_bodega_cantidadAlis']; ?></td>

                      <form action="../../Ajax/ajax_kit.php" method="post">
                        <input type="hidden" name="up" value="up">
                        <input type="text" name="id_producto" id="id_producto" value="<?php echo $rowPr['idProducto'] ?>" hidden>
                        <td>
                          <input class="form-control" type="number" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>

<!-- Modal kit_alistadoDs-->
<div class="modal fade" id="despachoKit_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Agregar Kits</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <div class="d-grid gap-2 d-md-block">
            <nav class="navbar navbar-expand-lg bg">
              <div class="container-fluid">
                <form class="d-flex" role="search" method="POST" action="">
                  <select class="form-select" aria-label="Default select example" name="campo" style="margin-right:8px;">
                    <option value="producto_codigo">Codigo / Referencia</option>
                    <option value="producto_nombre">Nombre</option>
                    <option value="producto_idCliente">Cliente</option>
                  </select>

                  <input class="form-control me-2" name="bus" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
              </div>
            </nav>
          </div>
        </div>
        <div class="card mb-4 ">
          <?php
          $count = 1;
          ?>
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de datos
          </div>
          <div class="card-body table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th scope="col">Codigo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Peso</th>
                  <th scope="col">Cantidad Para Alistar</th>

                </tr>
              </thead>
              <tbody>
                <?php

                if ($dataKit) {
                  foreach ($dataKit as $rowPr) {
                ?>
                    <tr>
                      <td><?php echo $rowPr['kit_consecutivo'] ?></td>
                      <td><?php echo $rowPr['kit_nombre'] ?></td>
                      <td><?php echo $rowPr['kit_peso'] ?> Kg</td>

                      <form action="../../Ajax/ajax_dispatch.php" method="post">
                        <input type="text" name="id_kit" id="id_producto" value="<?php echo $rowPr['idKit'] ?>" hidden>
                        <td>
                          <input type="number" class="form-control" name="cantidad" id="cantidad">
                        </td>
                        <!-- Button trigger modal -->
                        <td>
                          <button type="submit" class="btn btn-success" name=""><i class="fa-solid fa-plus"></i></button>
                        </td>
                      </form>
                    </tr>
                  <?php
                    $count++;
                  }
                } else { ?>
                  <tr class="text-center">
                    <td colspan="9">No hay registros en el sistema</td>
                  </tr>
                <?php  } ?>
              </tbody>
            </table>
            <!-- Paginación -->

          </div>
        </div>
        </fieldset>
      </div>

    </div>
  </div>
</div>