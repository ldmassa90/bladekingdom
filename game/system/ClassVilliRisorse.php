<?php

class VilliRisorse extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name 		= 'tb_villi_risorse';
	
//Nome della seguente classe

	public $class_name 		= 'VilliRisorse';
	
//Nomi delle colonne nella tabella
	
	public $columns_name = array("id", "oro", "legno", "ferro", "cibo", "popolazione", "legge", "tasse", "oro_per_ora", "pop_per_ora");
	
	public $message  = array(
									"wait_hours" 	=> "risorsa disponibile tra ",
									"wait_days"  	=> "risorsa disponibile il ",
									"no_resources" 	=> "Non sono disponibili le risorse necessarie!",
									"statistics"	=> "Statistiche di produzione",
									"requirement"	=> array(
															"Risorse necessarie per la costruzione di questo edificio",
															"Risorse necessarie per il miglioramento al livello "
															)
	);
/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "10","required"),
			"oro" => array("type" => "integer","length_max" => "10","required"),
			"legno" => array("type" => "integer","length_max" => "10","required"),
			"ferro" => array("type" => "integer","length_max" => "10","required"),
			"cibo" => array("type" => "integer","length_max" => "10","required"),
			"tasse" => array("type" => "integer","length_max" => "3","required"),
			"popolazione" => array("type" => "integer","length_max" => "7","required"),
			"legge" => array("type" => "integer","length_max" => "3","required"),
			"oro_per_ora" => array("type" => "integer","length_max" => "5","required"),
			"pop_per_ora" => array("type" => "integer","length_max" => "5","required"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"oro" => "Oro",
			"legno" => "Legno",
			"ferro" => "Ferro",
			"cibo" => "Cibo",
			"tasse" => "Tasse",
			"popolazione" => "Popolazione",
			//Campi aggiunti manualmente
			"legge" => "Severità legge",
			"tempo" => "Tempo"
		);
	}
	
	public function refresh_resources($user_id, $villo_id){

		global $utenti, $produzioni, $max_giacenza, $strutture;
		
		//Seleziono l'ultima attivit� registrata
		$query_act = $this->query("SELECT TIME_TO_SEC(TIMEDIFF(NOW(), ultima_attivita)) AS assenza FROM ".$utenti->tbl_name." WHERE id = " . $user_id);
		$last_act  = mysqli_fetch_assoc($query_act);
		
		//Tempo trascorso dall'ultimo aggiornamento delle risorse (in secondi)
		$diff	   = $last_act["assenza"];
		
		//Ottengo la quantità di risorse che dovrebbe avere ogni ora in base ai livelli delle strutture
		$query_lv  = $this->query("SELECT miniera, granaio, foresta, magazzino FROM ".$strutture->tbl_name." WHERE id = " . $villo_id);
		$livello   = mysqli_fetch_assoc($query_lv);
		
		$max_deposito = $max_giacenza[$livello["magazzino"]];
		
		//Ottengo le attuali quantità di risorse per verificare se l'incremento non comporta il superamento del limite consentito nel magazzino
		$query_qnt  = $this->query("SELECT oro, legno, ferro, cibo, tasse, legge, popolazione, oro_per_ora FROM ".$this->tbl_name." WHERE id = " . $villo_id);
		$quantita	= mysqli_fetch_assoc($query_qnt); 
		
		$oro_in_h	= (($quantita["tasse"] / 2) / 10) * $quantita["popolazione"];
		$dec_pop	= $oro_in_h / 100;
		
		//Decremento o incremento l'oro a seconda della legge
		if($quantita["legge"] == 1){
			$oro_in_h -= $dec_pop * 15;
		}elseif($quantita["legge"] == 2){
			$oro_in_h -= $dec_pop * 5;
		}else{
			$oro_in_h += $dec_pop * 15;
		}
		
		//Produzioni delle risorse per ora
		$qnt_oro	 = $quantita["oro_per_ora"];
		$qnt_miniera = $produzioni[$livello["miniera"] - 1];
		$qnt_granaio = $produzioni[$livello["granaio"] - 1];
		$qnt_foresta = $produzioni[$livello["foresta"] - 1];
	
		//Calcolo delle risorse da aggiungere
		$add_oro   = $diff / (3600 / $qnt_oro);
		$add_ferro = $diff / (3600 / $qnt_miniera);
		$add_grano = $diff / (3600 / $qnt_granaio);
		$add_legno = $diff / (3600 / $qnt_foresta);

		$tot_oro   = $quantita["oro"]   + $add_oro;
		$tot_ferro = $quantita["ferro"] + $add_ferro;
		$tot_grano = $quantita["cibo"]  + $add_grano;
		$tot_legno = $quantita["legno"] + $add_legno;

		//Verifico che la nuova quantità non sia maggiore della disponibilità in magazzino
		$tot_oro   = $tot_oro 	> $max_deposito ? $max_deposito : $tot_oro;
		$tot_ferro = $tot_ferro > $max_deposito ? $max_deposito : $tot_ferro;
		$tot_grano = $tot_grano > $max_deposito ? $max_deposito : $tot_grano;
		$tot_legno = $tot_legno > $max_deposito ? $max_deposito : $tot_legno;

		//Aggiorno i dati nel database
		//Risorse
		$this->query("UPDATE tb_villi_risorse SET oro = ".$tot_oro.", legno = ".$tot_legno.", ferro = ".$tot_ferro.", cibo = ".$tot_grano." WHERE id = " . $villo_id);
		//Ultima attività
		$this->query("UPDATE tbl_utenti SET ultima_attivita = NOW() WHERE id = " . $villo_id);
		
		return true;
	}
	
	/*
	 * Calcola la quantità di risorsa necessaria per l'ampliamento di una
	 * struttura in base al livello 
	 */
	
	public function get_cost($array, $level, $value){
		
		global $tassi_sviluppo, $tempi_evolzione;
		
		$tasso = ($value != "tempo") ? $tassi_sviluppo : $tempi_evolzione;
		
		return $array[$value] + ($array[$value] * $tasso[$level - 1]);
	}
	
	
	/*
	 * Calcola il tempo necessario per avere una data quantit� di risorsa
	*/
	
	public function time_to($prod_per_h, $act_resource, $target, $in_date = true){
	
		$time_to = 0;
		
		//In secondi
		$time_left = ceil(($target - $act_resource) / ($prod_per_h / 3600));
 
		if($in_date){
			$time_to = $this->message["wait_days"] . "". date("d-m \a\l\l\\e H:i:s", time() + $time_left); 
		}else{
			$time_to = $this->message["wait_hours"] . "". gmdate("H:i:s", $time_left);
		}
		
		return $time_to;
	}
	
	//Sottrae le risorse fornite come parametro
	
	public function modify_resources($id_town, $resources){
		
		$id			= $this->columns_name[0];
		$name_oro 	= $this->columns_name[1];
		$name_legno = $this->columns_name[2];
		$name_ferro = $this->columns_name[3];
		$name_cibo 	= $this->columns_name[4];
		
		$query = $this->query( "UPDATE " 	. $this->tbl_name . " SET " .
							  $name_oro 	. " = " . $name_oro   . " - " . $resources[$name_oro] 	. "," .
							  $name_legno 	. " = " . $name_legno . " - " . $resources[$name_legno] . "," .
							  $name_ferro 	. " = " . $name_ferro . " - " . $resources[$name_ferro] . "," .
							  $name_cibo	. " = " . $name_cibo  . " - " . $resources[$name_cibo] 	. " " .
							  "WHERE "		. $id	 . " = " . $id_town			
				);
		
		return $query;
		
	}
	
	

}
?>

