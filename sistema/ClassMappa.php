<?php
require('/ClassCRUDetail.php'); 

class Mappa extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_mappa';

//Nome della seguente classe

	public $class_name = 'Mappa';

/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "11","required"),
			"x" => array("type" => "integer","length_max" => "11","required"),
			"y" => array("type" => "integer","length_max" => "11","required"),
			"tipo" => array("type" => "integer","length_max" => "11","required"),
			"id_villo" => array("type" => "integer","length_max" => "11","required"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"x" => "X",
			"y" => "Y",
			"tipo" => "Tipo",
			"id_villo" => "Id Villo",
		);
	}

}
?>

