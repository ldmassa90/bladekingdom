<?php

class CodaCostruzione extends CRUDetail{

	const MAX_WORKERS = 2;
	
//Nome della tabella nell'archivio dati

	public $tbl_name = 'tb_coda_costruzione';

//Nome della seguente classe

	public $class_name = 'Codacostruzione';
	
//Nomi delle colonne nella tabella
	
	public $columns_name = array("id", "id_villo", "struttura", "scadenza");

/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "10","required"),
			"id_villo" => array("type" => "integer","length_max" => "10","required"),
			"struttura" => array("type" => "integer","length_max" => "3","required"),
			"scadenza" => array("type" => "timestamp","required"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"id_villo" => "Id Villo",
			"struttura" => "Struttura",
			"scadenza" => "Scadenza",
		);
	}
	

	
	//Restituisce i dati per la costruzione di una tabella per gli edifici in costruzione
	public function get_queue($id_villo, $building = array()){
		
		global $strutture;
		
		//Mostro tutte le code presenti
		$query_building = "SELECT ".$this->columns_name[2].", TIMEDIFF(".$this->columns_name[3].", NOW()) AS scadenza FROM " .$this->tbl_name. " WHERE ".$this->columns_name[1]." = " .$id_villo;
		
		if(count($building)){

			//Mostro solo le strutture richieste nell'array

			$query_building	.= " AND ";
			
			foreach($building as $key => $value){
				$id_strct    	 = (array_search($value, $strutture->columns_name) - 1);
				$query_building .= $this->columns_name[2] ." = ". $id_strct." || ";	
			}
			
			//Elimino i due caratteri || di troppo
			$query_building = substr($query_building, 0, -3);
		}
		
		$info_building = $this->query($query_building);

		return $info_building;
	}
	
	//Verifica se un edificio è già  in coda di costruzione
	
	public function in_queue($id_villo, $id_str){
		
		$query_exists = $this->query("SELECT ".$this->columns_name[0].", TIMEDIFF(".$this->columns_name[3].", NOW()) AS scadenza FROM " .$this->tbl_name. " WHERE ".$this->columns_name[1]." = ".$id_villo." AND ".$this->columns_name[2]." = ".$id_str);
		
		return @mysqli_fetch_assoc($query_exists);
	}
	
	//Elimina dalle code di costruzioni strutture finite e aggiorna il loro livello
	
	public function upgrade_building($id_villo){

		global $strutture;
		
		$query_queue = $this->query("SELECT * FROM " .$this->tbl_name." WHERE ".$this->columns_name[1]." = ".$id_villo." AND ".$this->columns_name[3]." <= NOW()");
		$n_results   = mysqli_num_rows($query_queue);
		
		if($n_results){

			//Elimino gli edifici ultimati e incremento il loro livello
			
			$this->query("DELETE FROM " .$this->tbl_name." WHERE ".$this->columns_name[1]." = ".$id_villo." AND ".$this->columns_name[3]." <= NOW()");
			
			while($data_buildings = mysqli_fetch_assoc($query_queue)){
				
				$column_name = $data_buildings[$this->columns_name[2]];
				$column_name = $strutture->columns_name[$column_name];
				
				$this->query("UPDATE ".$strutture->tbl_name." SET ". $column_name ." = ".$column_name." + 1 WHERE ".$strutture->columns_name[0]." = ".$id_villo);
				
			}
			
		}
		
		return true;
	}

	//Restituisce il numero di operai occupati nelle costruzioni
	
	public function get_available_workers($id_town){
	
		//Conto quante costruzioni questo utente ha avviato nel villaggio
	
		$query_workers  = $this->query("SELECT COUNT(*) AS workers FROM " .$this->tbl_name. " WHERE " .$this->columns_name[1]. " = ".$id_town);
		$n_workers  	= @mysqli_fetch_assoc($query_workers);
	
		return $n_workers["workers"];
	}
	
}
?>

