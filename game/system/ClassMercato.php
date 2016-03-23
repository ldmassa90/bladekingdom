<?php

class Mercato extends CRUDetail{

	public $message = 	array(	"market"	=> array(
													"build" 			=> "Mhm... signore, questo luogo sembra ideale per costruirci un mercato ! Quale luogo migliore se non nel centro del villaggio...",
													"default"			=> "Il nostro fiorente mercato ! Qui sono presenti merci di ogni genere al prezzo peggiore, la maggior parte delle volte..",
													"avable_merchants" 	=> "Numero di mercanti disponibli: ",
													"no_merchants"	   	=> "Signore, attualmente tutti i mercanti sono occupati ! Proviamo questa spedizione in un altro momento...",
													"no_town"			=> "Non riusciamo a trovare il villaggio sulla mappa !",
													"no_resources"		=> "Attualmente non disponiamo della quantit� di risorse necessarie per questa spedizione.",
													"auto_send"			=> "Ah mio sire... magari potesse essere possibile questo...",
													"zero_send"			=> "Niente risorse nel carico ? E cosa dovrei consengnare a destinazione..",
													"sended"			=> "Il nostro mercante � partito ! Presto sar� di ritorno."
													)
								);

//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_mercato';

//Nome della seguente classe

	public $class_name = 'Mercato';

	public $field_text =  array(
			"invia_risorse" 		=> "Invia risorse",
			"offri_risorse" 		=> "Offri risorse",
			"offerte_sul_mercato" 	=> "Offerte sul mercato",
			"mercato_nero" 			=> "Mercato nero",
			"send_to_town"			=> "Nome villo",
			"send_to_town_x"		=> "Coordinata x del villo",
			"send_to_town_y"		=> "Coordinata y del villo",
			"send_res_gold"			=> "Quantit� di oro",
			"send_res_wood"			=> "Quantit� di legna",
			"send_res_iron"			=> "Quantit� di ferro",
			"send_res_food"			=> "Quantit� di cibo",
	);
	

	
	public $id_sotto_voci	= array("invia"  		=> 1,
									"offri"  		=> 2,
									"ricevi" 		=> 3,
									"mercato_nero"	=> 4
									);
	
	//Nomi delle colonne nella tabella
	
	public $columns_name = array("id", "id_mittente", "id_destinatario", "oro", "legno", "ferro", "cibo");
        
        CONST NO_DESTINATARY = -1; 
	
/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "10","required"),
			"id_mittente" => array("type" => "integer","length_max" => "10","required"),
			"id_destinatario" => array("type" => "integer","length_max" => "10"),
			"oro" => array("type" => "integer","length_max" => "10","required"),
			"legno" => array("type" => "integer","length_max" => "10","required"),
			"ferro" => array("type" => "integer","length_max" => "10","required"),
			"cibo" => array("type" => "integer","length_max" => "10","required"),
			"PRIMARY" => "id"
		);
	}
	
	//Restituisce il numero di mercanti attualmente occupati
	
	public function work_merchants($id_town){
		
		//TODO Sistemare questo dettaglio...(vedi commento dopo) 
		//Id mittente � inteso come id della citt� e NON come id dell'utente
		
		$query_merchants = $this->query("SELECT COUNT(*) AS n_merchants FROM " . $this->tbl_name . " WHERE " .$this->columns_name[1]. " = " . $id_town);
		$info_merchants  = mysqli_fetch_assoc($query_merchants);

		return intval($info_merchants["n_merchants"]);
	}
	
	//Restituisce il numero di mercanti disponibili per quel villo
	
	public function avable_merchants($level_market){
		return ceil($level_market * 0.5);
	}
	
        public function avable_offers(){
            $query_offers = $this->query("SELECT * FROM " . $this->tbl_name. " WHERE "
                    . $this->columns_name[2]." = ".self::NO_DESTINATARY);
       
            return $query_offers;
        }
        
        public function summary_offers($id_user, $operation = "="){
            
            $sql = "SELECT * FROM " . $this->tbl_name. " WHERE ".
                    $this->columns_name[1]." $operation $id_user and ".$this->columns_name[2]." = ".
                    self::NO_DESTINATARY;
            
            $query_offers = $this->query("SELECT * FROM " . $this->tbl_name. " WHERE ".
                    $this->columns_name[1]." $operation $id_user and ".$this->columns_name[2]." = ".
                    self::NO_DESTINATARY);
            
            
            return $query_offers;
        }
        
	//Calcola il tempo necessario per la consegna delle risorse
	
	public function time_end($id_from, $id_to){
		
		global $obj_mappa;

		//Ottengo le coordinate del villo di destinazione
		$coord_town_to   = $obj_mappa->get_coord($id_to);
		
		//Ottengo le coordinate del villo mittente
		$coord_town_from = $obj_mappa->get_coord($id_from);
		
		//Calcolo della distanza
		$x_diff  = abs($coord_town_to["x"] - $coord_town_from["x"]);
		$y_diff  = abs($coord_town_to["y"] - $coord_town_from["y"]);

                //Calcola il tempo
                $time = round(sqrt(pow($x_diff,2) + pow($y_diff,2))) * 25;
                $format = '%02d:%02d';
                $hours = floor($time / 60);
                $minutes = ($time % 60);
                return sprintf($format, $hours, $minutes);
                
	
	}
	
	//Inserisce le risorse inviate nel database
	
	public function send($id_from, $id_to, $resources){

		//Inserisco nella coda questa spedizione

		$query = $this->query( 	"INSERT INTO " . $this->tbl_name . " VALUES(NULL,".
								$id_from 	. "," .
								$id_to		. "," .
								$resources["oro"] 	. "," .
								$resources["legno"] . "," .
								$resources["ferro"] . "," .
								$resources["cibo"] . ", NOW())") or die(mysql_error());

		return $query;
	}
}
?>

