<?php
/*
	PHP contact form script
	Version: 1.0
	Hermes Garcia
	hgarciamanzanarez@gmail.com
*/
require_once ('../connection/database.php');
/***************** Configuration *****************/

$contactEmailTo = 'info@cavainteligente.com';
$contactEmailFrom = 'contacto@cavainteligente.com';
$ccEmail = 'hermes.garcia@cavainteligente.com';
$ccEmail2 = 'hermes@hermesgarcia.com';
$bccEmail = 'hgarciamanzanarez@gmail.com';
$bccEmail2 = '';
$subjectTitle = 'Mensaje formulario de contacto:';
$nameTitle = 'Nombre:';
$emailTitle = 'Email:';
$messageTitle = 'Mensaje:';

$contactErrorName = 'El nombre es muy corto o muy largo';
$contactErrorEmail = 'Por favor ingresa un email válido';
$contactErrorSubject = 'El asunto es muy corto o muy largo';
$contactErrorMessage = 'Tu mensaje es muy corto, escribe un poco más para nosotros';

/********** Send Script ***********/
$tz = 'America/Mexico_City';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz));
$dt->setTimestamp($timestamp);
$date = $dt->format('Y-m-d H:i:s');

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	die('Lo sentimos, la petición debe ser tipo Ajax POST');
}

if(isset($_POST)) {

	$name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
	$subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

	if(!$contactEmailTo || $contactEmailTo == 'contact@example.com') {
		die('El email de recepción para el formulario de contacto no ha sudo configurado');
	}

	if(strlen($name)<3 || strlen($name)>50){
		die($contactErrorName);
	}

	if(!$email){
		die($contactErrorEmail);
	}

	if(strlen($subject)<3 || strlen($subject)>50){
		die($contactErrorSubject);
	}

	if(strlen($message)<3 || strlen($message)>256){
		die($contactErrorMessage);
	}

	if(!isset($contactEmailFrom)) {
		$contactEmailFrom = 'contacto@' . @preg_replace('/^www\./','', $_SERVER['SERVER_NAME']);
	}

	$headers = 'From: ' . $name . ' <' . $contactEmailFrom . '>' . PHP_EOL;
	$headers .= 'Reply-To: ' . $email . PHP_EOL;
	$headers .= 'Cc: ' . $ccEmail . ',' . $ccEmail2 . PHP_EOL;
	$headers .= 'Bcc: ' . $bccEmail . ',' . $bccEmail2 . PHP_EOL;
	$headers .= 'MIME-Version: 1.0' . PHP_EOL;
	$headers .= 'Content-Type: text/html; charset=UTF-8' . PHP_EOL;
	$headers .= 'X-Mailer: PHP/' . phpversion();

	$message_content = '<strong>' . $nameTitle . '</strong> ' . $name . '<br>';
	$message_content .= '<strong>' . $emailTitle . '</strong> ' . $email . '<br>';
	$message_content .= '<strong>' . $messageTitle . '</strong> ' . nl2br($message);
	die("OK");
	$sendemail = mail($contactEmailTo, $subjectTitle . ' ' . $subject, $message_content, $headers);

	if( $sendemail ) {
		if(!connect($connection)){
			echo "OK";
		}else{

			$query = "SELECT id FROM subscriptions  
			    WHERE email = '". $email."';";
			$result = mysqli_query($connection, $query) or die ("Algo ha salido mal");

			if($result->num_rows > 0){
				$id = $result->fetch_row()[0];
			}else{
				$query = "INSERT INTO subscriptions (email, time) 
			 				VALUES ('". $email."', '" . $date . "')";
				$result = mysqli_query($connection, $query) or die ("Algo ha salido mal");
				$id = mysqli_insert_id($connection);
			}
			$query = "INSERT INTO sent_contact_emails (email, time, name, subject, message, status, subscriptions_id) 
			    			VALUES ('". $email."', '" . $date . "', '" . $name . "', '" . $subject ."' ,'" . $message . "', true,'" . $id . "')";
			$result = mysqli_query($connection, $query) or die ("Algo ha salido mal");
			echo "OK";
		}
	} else {
		if(!connect($connection)){
			echo 'El email no pudo ser enviado, intentálo de nuevo.';
		}else{
			$query = "SELECT id FROM subscriptions  
			    WHERE email = '". $email."';";
			$result = mysqli_query($connection, $query) or die ("Algo ha salido mal");

			if($result->num_rows > 0){
				$id = $result->fetch_row()[0];
			}else{
				$query = "INSERT INTO subscriptions (email, time) 
			 				VALUES ('". $email."', '" . $date . "')";
				$result = mysqli_query($connection, $query) or die ("Algo ha salido mal");
				$id = mysqli_insert_id($connection);
			}
			$query = "INSERT INTO sent_contact_emails (email, time, name, subject, message, status, subscriptions_id) 
			    			VALUES ('". $email."', '" . $date . "', '" . $name . "', '" . $subject ."' ,'" . $message . "', false,'" . $id . "')";
			$result = mysqli_query($connection, $query) or die ("Algo ha salido mal");

			echo 'El email no pudo ser enviado, intentálo de nuevo.';
		}
	}
}
