<?php
// Se inicia la consulta a la base de datos
include_once '../../../Model/config.php';
include_once '../../../Controller/main_class.php';


$db = getDB();

if (!empty($_GET['tb'])) {

    session_start(['name' => 'SPM']);

    $botonBodega = consultaSimple("bodega", $_SESSION['bodega']);

    $tabla = encrypt_decrypt('decrypt', $_GET['tb']);

    $idTabla = $tabla . '_id';

    $query = $db->prepare("SELECT * FROM $tabla 
		INNER JOIN producto ON producto_ingresado_idProducto=idProducto 
		INNER JOIN entrada ON producto_ingresado_idEntrada=idEntrada 
		WHERE $tabla.producto_ingresado_idEntrada=:id AND entrada_estado='activo'");

    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

    $query->execute();

    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    $idImg = $data[0]['producto_ingresado_idEntrada'];
    // Consulta de las imagenes en relación
    $query = $db->prepare("SELECT imagen_nombre FROM imagen 
    WHERE entrada_firmaId=:id");

    $query->bindParam(':id', $idImg, PDO::PARAM_INT);

    $query->execute();

    $dataImg = $query->fetchAll(PDO::FETCH_ASSOC);

    $cons_ct = consultaSimpleAsc('cliente', $data[0]['producto_idCliente']);


    // Se incluye la librería de fpDF
    require "./FPDF/fpdf.php";

    $pdf = new FPDF('P', 'mm', 'Letter');
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();


    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(33, 33, 33);

    $pdf->Image('../img/logo.png', 18, 13, 60, 11, 'PNG');

    $pdf->Cell(350, -10, utf8_decode('N. de factura'), '', 0, 'C');

    $pdf->Ln(6);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(350, -10, utf8_decode('FT-' . $cons_ct[0]['idCliente']), '', 0, 'C');

    $pdf->Ln(7);

    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(36, 8, utf8_decode('Fecha de emisión:'), 0, 0);
    $pdf->SetTextColor(97, 97, 97);
    $pdf->Cell(27, 8, utf8_decode(date("F j, Y, g:i a")), 0, 0);

    $pdf->Ln(8);
    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(17, 8, utf8_decode('Bodega:'), 0, 0);
    $pdf->SetTextColor(97, 97, 97);
    $pdf->Cell(80, 8, utf8_decode($botonBodega[0]['bodega_nombre']), 0, 0);


    $pdf->Ln(20);
    $pdf->SetFont('Arial', '', 13);
    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(25, 10, utf8_decode('Datos del Cliente'), 0, 0);

    $pdf->Ln(12);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(25, 8, utf8_decode('Consecutivo:'), 0, 0);
    $pdf->SetTextColor(97, 97, 97);

    $pdf->Cell(30, 8, utf8_decode($cons_ct[0]['cliente_consecutivo']), 0, 0);
    $pdf->SetTextColor(33, 33, 33);

    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(15, 8, utf8_decode('Cliente:'), 0, 0);
    $pdf->SetTextColor(97, 97, 97);

    $pdf->Cell(70, 8, utf8_decode($cons_ct[0]['cliente_nombre'] . ' ' . $cons_ct[0]['cliente_apellido']), 0, 0);
    $pdf->SetTextColor(33, 33, 33);

    $pdf->Ln(10);

    $pdf->Cell(10, 8, utf8_decode('DNI:'), 0, 0);
    $pdf->SetTextColor(97, 97, 97);
    $pdf->Cell(45, 8, utf8_decode($cons_ct[0]['cliente_nDocument']), 0, 0);
    $pdf->SetTextColor(33, 33, 33);

    $pdf->Cell(19, 8, utf8_decode('Teléfono:'), 0, 0);
    $pdf->SetTextColor(97, 97, 97);
    $pdf->Cell(36, 8, utf8_decode($cons_ct[0]['cliente_telefono']), 0, 0);
    $pdf->SetTextColor(33, 33, 33);

    $pdf->Ln(20);
    $pdf->SetFillColor(196, 111, 0);
    $pdf->SetDrawColor(196, 111, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 10, utf8_decode('PRODUCTOS'), 1, 0, 'C', true);
    $pdf->SetTextColor(97, 97, 97);
    $pdf->Cell(45, 10, utf8_decode(' N.Consecutivo: ' . $data[0]['entrada_consecutivo']), 'L', 0, 'C');

    $pdf->Ln(10);
    $pdf->SetFillColor(196, 111, 0);
    $pdf->SetDrawColor(192, 192, 192);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(25, 10, utf8_decode('Código'), 1, 0, 'C', true);
    $pdf->Cell(110, 10, utf8_decode('Nombre'), 1, 0, 'C', true);
    $pdf->Cell(18, 10, utf8_decode('Peso'), 1, 0, 'C', true);
    $pdf->Cell(18, 10, utf8_decode('Cantidad'), 1, 0, 'C', true);
    $pdf->Cell(22, 10, utf8_decode('Peso total'), 1, 0, 'C', true);

    $pdf->Ln(10);
    $pdf->SetTextColor(97, 97, 97);

    $total = 0;

    $total_f = 0;

    foreach ($data as $row) {

        $total = $row['producto_peso'] * $row['producto_ingresado_cantidad'];

        $total_f += $total;

        $pdf->Cell(25, 10, utf8_decode($row['producto_codigo']), 'L', 0, 'C');
        $pdf->Cell(110, 10, utf8_decode($row['producto_nombre']), 'L', 0, 'L');
        $pdf->Cell(18, 10, utf8_decode($row['producto_peso']) . ' (kg)', 'L', 0, 'C');
        $pdf->Cell(18, 10, utf8_decode($row['producto_ingresado_cantidad']), 'L', 0, 'C');
        $pdf->Cell(22, 10, utf8_decode($total), 'LR', 0, 'C');

        $pdf->Ln(6);
    }


    $pdf->Ln(4);
    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(193, 10, utf8_decode(''), 'T', 0, 'C');

    $pdf->Ln(0);

    $pdf->Cell(153, 10, utf8_decode(''), 'R', 0, 'R');
    $pdf->Cell(18, 10, utf8_decode('TOTAL'), 'R', 0, 'C');
    $pdf->Cell(22, 10, utf8_decode($total_f), 'LR', 0, 'C');

    $pdf->Ln(15);
    $pdf->SetFont('Arial', '', 13);
    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(103, 10, utf8_decode('Datos del conductor'), 0, 0);
    $pdf->Cell(103, 10, utf8_decode('Firma del conductor'), 0, 0);

    $pdf->Ln(12);
    $pdf->SetFont('Arial', '', 12);

    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(15, 8, utf8_decode('Cédula:'), 0, 0);
    $pdf->Image('../img/signature/'. $dataImg[0]['imagen_nombre'], 120, 165, 70, 15, 'PNG');

    $pdf->SetTextColor(97, 97, 97);

    $pdf->Cell(88, 8, utf8_decode($data[0]['entrada_cedulaPersona']), 0, 0);
    $pdf->SetTextColor(33, 33, 33);

    $pdf->Ln(7);

    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(18, 8, utf8_decode('Nombre:'), 0, 0);

    $pdf->SetTextColor(97, 97, 97);

    $pdf->Cell(85, 8, utf8_decode($data[0]['entrada_nombrePersona']), 0, 0);
    $pdf->SetTextColor(33, 33, 33);

    $pdf->Ln(7);

    $pdf->Cell(15, 8, utf8_decode('Placa:'), 0, 0);
    $pdf->SetTextColor(97, 97, 97);
    $pdf->Cell(70, 8, utf8_decode($data[0]['entrada_placaPersona']), 0, 0);
    $pdf->SetTextColor(33, 33, 33);


    $pdf->Ln(45);

    /*----------  INFO. EMPRESA  ----------*/
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(33, 33, 33);
    $pdf->Cell(0, 6, utf8_decode('Innvestock'), 0, 0, 'L');

    $pdf->Ln(8);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 6, utf8_decode("Celular: " . '(+57) 3002520895'), 0, 0, 'L');

    $pdf->Ln(7);
    $pdf->Cell(0, 6, utf8_decode("Correo: " . 'Mercadeo@logistica3.com'), 0, 0, 'L');

    $pdf->Ln(7);
    $pdf->Cell(0, 6, utf8_decode('Ubicación: ' . 'Principal Km. 19 vía  Bogotá - Madrid Parque Industrial San Jorge Bodega 53 '), 0, 0, 'L');
    $pdf->Output("I", "Factura_" . 'DS' . $cons_ct[0]['idCliente']  . ".pdf", true);
} else { ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php ?></title>
        <?php include "../vistas/inc/Link.php"; ?>
    </head>

    <body>
        <div class="full-box container-404">
            <div>
                <p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
                <h1 class="text-center">Ocurrio un error</h1>
                <p class="lead text-center">No hemos enontrado la información de su consulta!</p>
            </div>
        </div>
        <?php include "../vistas/inc/Script.php"; ?>
    </body>

    </html>

<?php } ?>