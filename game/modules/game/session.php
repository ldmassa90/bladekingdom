<?php

if(!isset($_SESSION["id_user"])){
	header("Location: ./index.php");
	exit;
}else{
	session_regenerate_id();
}

//TODO Migliorare la sicurezza delle sessioni !
?>