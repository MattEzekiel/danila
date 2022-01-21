<?php
/**
 * @version 1.0
 */

require("class.phpmailer.php");
require("class.smtp.php");

// Valores enviados desde el formulario
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Allow: GET, POST, OPTIONS");

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
$content = trim(file_get_contents('php://input'));
$decoded = json_decode($content,true);

$nombre = filter_var(ucfirst(strtolower($decoded['nombre'])),FILTER_SANITIZE_STRING);
$hijo = filter_var(ucfirst(strtolower($decoded['hijo'])),FILTER_SANITIZE_STRING);
$edad = filter_var((int)$decoded['edad'],FILTER_SANITIZE_NUMBER_INT);
$emailUser = filter_var($decoded['email'],FILTER_SANITIZE_EMAIL);
$message = filter_var($decoded['mensaje'],FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($nombre) || empty($hijo) || empty($edad) || empty($emailUser) || empty($message)){
    exit(json_encode(['error' => "Tiene campos sin completar"]));
}

$mensaje = "<b>Contactado por:</b> $nombre <br> <b>Nombre de su hijo/a:</b> $hijo <br> <b>Edad:</b> $edad <br> <b>Email de contacto:</b> $emailUser <br> <b>Su mensaje:</b> $message.";
$email = "no-reply@c2090526.ferozo.com";

// Datos de la cuenta de correo utilizada para enviar vía SMTP
$smtpHost = "c2090526.ferozo.com";  // Dominio alternativo brindado en el email de alta
$smtpUsuario = "no-reply@c2090526.ferozo.com";  // Mi cuenta de correo
$smtpClave = "CRi8iJKb3z";  // Mi contraseña

// Email donde se enviaran los datos cargados en el formulario de contacto
$emailDestino = "info@danilahs.com.ar";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Port = 465; 
$mail->SMTPSecure = 'ssl';
$mail->IsHTML(true); 
$mail->CharSet = "utf-8";


// VALORES A MODIFICAR //
$mail->Host = $smtpHost;
$mail->Username = $smtpUsuario; 
$mail->Password = $smtpClave;

$mail->From = $email; // Email desde donde envío el correo.
$mail->FromName = $nombre;
$mail->AddAddress($emailDestino); // Esta es la dirección a donde enviamos los datos del formulario

$mail->Subject = "DanilaHS formulario de contacto"; // Este es el título del email.
$mensajeHtml = nl2br($mensaje);
$mail->Body = "{$mensajeHtml} <br /><br />Formulario enviado por DonWeb<br />"; // Texto del email en formato HTML
$mail->AltBody = "{$mensaje} \n\n Formulario de ejemplo By DonWeb"; // Texto sin formato HTML
// FIN - VALORES A MODIFICAR //

$estadoEnvio = $mail->Send(); 
if($estadoEnvio){
    echo json_encode(['success' => "El correo fue enviado correctamente."]);
} else {
    echo json_encode(['error' => "Ocurrió un error inesperado."]);
}
