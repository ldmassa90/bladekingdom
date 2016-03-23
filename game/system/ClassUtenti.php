<?php

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
			"id" => array("type" => "integer","length_max" => "5","required"),
			"utente" => array("type" => "string","length_max" => "20","required"),
			"password" => array("type" => "string","length_max" => "100","required"),
			"email" => array("type" => "string","length_max" => "100","required"),
			"registrato" => array("type" => "date","required"),
			"stato" => array("type" => "integer","length_max" => "3","required"),
			"ultimo_login" => array("type" => "datetime","required"),
			"ultima_attivita" => array("type" => "datetime","required"),
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
			"password_c" => "Conferma della password",
			"email" => "Email",
			"registrato" => "Registrato",
			"stato" => "Stato",
			"ultimo_login" => "Ultimo login",
			"ultima_attivita" => "Ultimo attività",
		);
	}

/*
 Funzione per ottenere il nome/id dell'utente in base ad un nome o un id
 */
	
	public function getUser($identifier){
		
		$type_identifier = gettype($identifier);
		$type_pk		 = $this->rules();
		$type_pk		 = gettype($type_pk["PRIMARY"]);
		
		//Da nome a id
		$search = "id";
		$by		= "utente";
		
		if(is_numeric($identifier)){
			//Da id a nome
			$search = "utente";
			$by		= "id";
		}
		
		$get_data = $this->query("SELECT " .$search. " FROM " . $this->tbl_name . " WHERE " .$by. " LIKE '".$identifier."'");

		$user = $get_data->fetch_assoc();
		
		return $user[$search];
		
	}

}
?>

