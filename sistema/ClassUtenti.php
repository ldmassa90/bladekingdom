<?php
require('/ClassCRUDetail.php'); 

class Utenti extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_utenti';

//Nome della seguente classe

	public $class_name = 'Utenti';

/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "11","required"),
			"utente" => array("type" => "string","length_max" => "20","required"),
			"password" => array("type" => "string","length_max" => "100","required"),
			"email" => array("type" => "string","length_max" => "100","required"),
			"registrato" => array("type" => "date","required"),
			"stato" => array("type" => "integer","length_max" => "11","required"),
			"ultimo_login" => array("type" => "datetime","required"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"utente" => "Utente",
			"password" => "Password",
			"email" => "Email",
			"registrato" => "Registrato",
			"stato" => "Stato",
			"ultimo_login" => "Ultimo Login",
		);
	}

}
?>

