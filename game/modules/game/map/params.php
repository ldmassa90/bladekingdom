<?php
define("X_LAND", 25);
define("Y_LAND", 25);

//Descrizioni isole

$imgmap = array("Terrenobase.png","LagoG.png","ForestaG.png","MontagnaG.png","VillaggioG.png");

$infozona = array(

		array(
				"NAME" => "Pianura",
				"DESC" => "Un bel terreno liscio e rigoglioso signore, ottimo per costruirci qualcosa...",
				"SUB0" => ""
		),
		array(
				"NAME" => "Laghetto",
				"DESC" => "Questa sembra un ottima zona per pescare.All'orizzonte si vedono delle persone...",
				"SUB0" => ""
		),
		array(
				"NAME" => "Foresta",
				"DESC" => "Le uniche cose che so di questo luogo sono due: Alberi alti e spessi come colonne di pietra e strani ruomori provenire dall'alto",
				"SUB0" => ""
		),
		array(
				"NAME" => "Montagne",
				"DESC" => "Le montagne..... luoghi pericolosi signore. Pullulano di bestie mai viste prima in tutto il regno e pochi ne fanno ritorno incolumi.",
				"SUB0" => "Miniera d'oro",
				"SUB1" => "Miniera di metalli"
		),
		array(
				"NAME" => "Villaggio",
				"DESC" => "Ho provato ad avvicinarmi signore ma le guardie mi hanno guardato male e cacciato.",
				"SUB0" => ""
		)
);
?>