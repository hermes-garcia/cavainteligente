<?php
/*
	PHP subscription form script
	Version: 1.0
	Hermes Garcia
	hgarciamanzanarez@gmail.com
*/
require_once ('../connection/database.php');
$contactErrorEmail = 'Por favor ingresa un email válido';
$tz = 'America/Mexico_City';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz));
$dt->setTimestamp($timestamp);
$date = $dt->format('Y-m-d H:i:s');

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	die('Lo sentimos, la petición debe ser tipo Ajax POST');
}
if(isset($_POST)) {

	$email = filter_var($_POST["subscription-email"], FILTER_SANITIZE_STRING);

	if( $email ) {
		if(connect($connection)){
			$query = "INSERT INTO subscriptions (email, time) 
			 VALUES ('". $email."', '" . $date . "')";
			$result = mysqli_query($connection, $query) or die ("Ya te has registrado, ¡Muchas Gracias!");
			echo "OK";
		}else{
			echo "Algo ha salido mal, por favor intentálo de nuevo";
		}
	} else {
		echo 'Por favor ingresa un email válido';
	}
}
