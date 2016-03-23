<?php
session_start();
header('Content-Type: application/json');
require("../session.php");
//TODO Quando verrà caricato il gioco online modificare questa PATH !!!

require($_SERVER['DOCUMENT_ROOT']."/BladeKingdom/game/system/ClassCRUDetail.php");
require($_SERVER['DOCUMENT_ROOT']."/BladeKingdom/game/system/ClassVilli.php");

$obj_town = new Villi();

///TODO Fare il search in modo tale che accetti le richieste solo dall'ip del sito e non da altri ip!!

$town_search  = addslashes($_GET["term"]);
$name 	 = $obj_town->columns_name[2];

//Verifico che ci siano elementi nella ricerca desiderata

$data_town		= array();
$town_match		= $obj_town->exists_town($town_search, 2);

while($match_towns = mysqli_fetch_assoc($town_match)){
	$data_town[]["value"] 	= $match_towns[$name];
}

echo json_encode($data_town);
?>