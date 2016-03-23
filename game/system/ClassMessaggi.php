<?php

class Messaggi extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_messaggi';

//Nome della seguente classe

	public $class_name = 'Messaggi';


	public $testo_messaggi =  array(
									"mittente",
									"destinatario",
									"oggetto",
									"messaggio",
									"data_invio",
									"stato",
									"ricevuti",
									"inviati",
									"salvati",
									"nuovo",
									"impostazioni",
									"messaggi_ricevuti" => "Messaggi ricevuti",
									"messaggi_inviati" 	=> "Messaggi inviati",
									"messaggi_salvati" 	=> "Messaggi salvati"
									);
	

	/*
	 * Le seguenti sono sottovoci della sezione messaggi
	*/
	
	public $id_sotto_voci	= array("ricevuti" => 1, "inviati" => 2, "salvati" => 3, "nuovo" => 4, "impostazioni" => 5);
	
	
/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "10","required"),
			"mittente" => array("type" => "integer","length_max" => "5","required"),
			"destinatario" => array("type" => "integer","length_max" => "5","required"),
			"oggetto" => array("type" => "string","length_max" => "35","required"),
			"messaggio" => array("type" => "string","required"),
			"data_invio" => array("type" => "datetime","required"),
			"stato" => array("type" => "integer","length_max" => "3","required"),
			"PRIMARY" => "id"
		);
	}

	
	/*
	 * Funzione per stabilire se un messaggio appartiene o meno all'utente 
	 * 
	 */

	public function mex_owner($id_mex, $id_user){
		
		$state = false;
		
		if(is_numeric($id_mex) && $id_mex > 0){
		
			//Verifico se questo messaggio esiste
		
			$mex_exists = $this->query("SELECT id FROM " .$this->tbl_name. " WHERE id = " .$id_mex);
			
			if(mysqli_num_rows($mex_exists)){
				
				//Verifico che questo utente possa leggere il messaggio
				$is_auth = $this->query("SELECT destinatario, mittente, oggetto, messaggio, data_invio FROM " .$this->tbl_name. " WHERE id = " .$id_mex." AND (destinatario = ".$id_user." || mittente = ".$id_user.")");
				
				if(mysqli_num_rows($is_auth)){
					$state = $is_auth;
				}
			}
		
		}
		
		return $state;
		
	}
	

}
?>

