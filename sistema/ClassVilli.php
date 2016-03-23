<?php
require('/ClassCRUDetail.php'); 

class Villi extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_villi';

//Nome della seguente classe

	public $class_name = 'Villi';

/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "11","required"),
			"id_proprietario" => array("type" => "integer","length_max" => "11","required"),
			"nome" => array("type" => "string","length_max" => "20","required"),
			"descrizione" => array("type" => "text"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"id_proprietario" => "Id Proprietario",
			"nome" => "Nome",
			"descrizione" => "Descrizione",
		);
	}

}
?>

