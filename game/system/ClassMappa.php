<?php

class Mappa extends CRUDetail{


	//Questo valore indica il tempo (base) necessario per il movimento di una casella Espresso in secondi
	
	public $time_to_move = array("merchants" => 200);
	
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
			"id" => array("type" => "integer","length_max" => "7","required"),
			"x" => array("type" => "integer","length_max" => "5","required"),
			"y" => array("type" => "integer","length_max" => "5","required"),
			"tipo" => array("type" => "integer","length_max" => "3","required"),
			"id_villo" => array("type" => "integer","length_max" => "10","required"),
			"PRIMARY" => "id"
		);
	}

	//Nomi delle colonne nella tabella
	
	public $columns_name = array("id", "x", "y", "tipo", "id_villo");
	
	
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

	/*
	 * Funzione che verifica l'esistenza di un villo in base al nome o alle coordinate
	 */
	
	public function exists_town($town_data){
	
		global $villo;
		
		if(!isset($town_data["town_name"])){
				
			//Vengono fornite le coordinate x e y
			$search = "x = " . $town_data["x"] . " AND y = " . $town_data["y"];
		
		}else{
				
			//Viene fornito il nome della città
			$search = "nome LIKE '" . $town_data["town_name"] . "'";
		}
		
		$exists = $this->query("SELECT " . $villo->columns_name[0] . " FROM " . $villo->tbl_name . " WHERE " . $search);
		
		echo "SELECT " . $villo->columns_name[0] . " FROM " . $villo->tbl_name . " WHERE " . $search;
		return mysqli_num_rows($exists);
	}
	
	//Restituisce le coordinate id un villo a partire dal suo id
	
	public function get_coord($id_town){

		//Selezione delle coordinate
		$query_town_coord = $this->query("SELECT " 	. $this->columns_name[1] . "," 			. $this->columns_name[2] . " 
										 FROM " 	. $this->tbl_name 		 . " WHERE  " 	. $this->columns_name[4] . " = " . $id_town);
		
		return mysqli_fetch_assoc($query_town_coord);
	}
}
?>
