<?php

class Villi extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_villi';

//Nome della seguente classe

	public $class_name = 'Villi';


	public $testo_messaggi =  array(
			"id_proprietario",
			"nome" => "Nome del villaggio",
			"descrizione",
			"x",
			"y",
			"capitale"
	);
	
	//Nomi delle colonne nella tabella
	
	public $columns_name = array("id", "id_proprietario", "nome", "descrizione", "x", "y", "capitale");
	
	
	/*
	 * Le seguenti sono sottovoci della sezione villi le quali associano ad ogni struttura il proprio identificativo
	 * per l'associazione con le pagine di gestione
	 */
	
	public $id_sotto_voci	= array("villo" 		=> 1, 
									"municipio" 	=> 2, 
									"legna" 		=> 3, 
									"grano" 		=> 4, 
									"pietra" 		=> 5,		
									"ampliamento"	=> 6, 
									"mercato" 		=> 7,
									"caserma" 		=> 8,
									"warehouse"		=> 9,
									"wall"			=> 10,
									"universita"	=> 11
	);
	
	public $message  = array(
			"build_no_found"	=> "Mhm... pare che questa struttura non esista.",
			"section_no_found"	=> "Mhm... pare che in questa struttura non ci sia la sezione indicata.",
	);

/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "10","required"),
			"id_proprietario" => array("type" => "integer","length_max" => "5","required"),
			"nome" => array("type" => "string","length_max" => "20", "length_min" => "4","required"),
			"descrizione" => array("type" => "string"),
			"x" => array("type" => "integer","length_max" => "5","required"),
			"y" => array("type" => "integer","length_max" => "5","required"),
			"capitale" => array("type" => "integer","length_max" => "3","required"),
			"PRIMARY" => "id"
		);
	}


	public function is_owner($id_villo, $id_user){
	
		$state = false;
	
		if($id_villo > 0 && $id_user > 0){
	
			$query_find = $this->query("SELECT id FROM " . $this->tbl_name . " WHERE id = " . $id_villo . " AND id_proprietario = " . $id_user);
				
			if(mysqli_num_rows($query_find)){
				$state = true;
			}
	
		}
	
		return $state;
	}
	
	//Verifica l'esistenza di una città in base al suo id o al nome
	public function exists_town($town, $type_return = 1){
	
		$tag_string = "%";
		$tag_equal  = "LIKE";
		$search_by  = $this->columns_name[2];
		
		if(is_numeric($town)){
			$tag_string = "";
			$tag_equal  = "=";
			$search_by  = $this->columns_name[0];
		}
	
		$query_exists = $this->query("SELECT " . $search_by . " FROM " . $this->tbl_name . " WHERE " . $search_by . " ".$tag_equal." \"" . $town . "".$tag_string."\"");

		if($type_return == 1){
			return mysqli_num_rows($query_exists);
		}else{
			return $query_exists;
		}
	}
	
	
	//Restituisce l'id del villo in base al suo nome
	
	public function getIdByName($name){
		
		$id_name = $this->columns_name[0];
		
		$query_town = $this->query("SELECT " . $id_name . " FROM " . $this->tbl_name . " WHERE "  . $this->columns_name[2] . " = \"".$name."\"");
		
		$info_town	= mysqli_fetch_assoc($query_town);
		
		return $info_town[$id_name];
	}
	
}
?>