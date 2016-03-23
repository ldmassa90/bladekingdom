<?php
require('/ClassCRUDetail.php'); 

class Messaggi extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_messaggi';

//Nome della seguente classe

	public $class_name = 'Messaggi';

/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "11","required"),
			"mittente" => array("type" => "integer","length_max" => "11","required"),
			"destinatario" => array("type" => "integer","length_max" => "11","required"),
			"oggetto" => array("type" => "string","length_max" => "25","required"),
			"messaggio" => array("type" => "text","required"),
			"stato" => array("type" => "integer","length_max" => "11","required"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"mittente" => "Mittente",
			"destinatario" => "Destinatario",
			"oggetto" => "Oggetto",
			"messaggio" => "Messaggio",
			"stato" => "Stato",
		);
	}

}
?>

