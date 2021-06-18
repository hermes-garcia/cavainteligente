<?php
$GLOBALS['timeout'] = 600;

function connect(&$connection){
    //Local data env
	$user = "root";
	$password = "";
	$server = "localhost";
	$database = "cavainteligente";

	$connection = mysqli_connect( $server, $user, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
	mysqli_set_charset($connection, 'utf8');
	$db = mysqli_select_db( $connection, $database ) or die ( "No se ha podido seleccionar la base de datos" );

	if($db){
		return true;
	}
	else{
		return false;
	}
}
