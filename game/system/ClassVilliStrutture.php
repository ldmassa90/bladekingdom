<?php

class Villistrutture extends CRUDetail{

//Nome della tabella nell'archivio dati

	public $tbl_name = 'tb_villi_strutture';

//Nome della seguente classe

	public $class_name = 'Villistrutture';
	
//Nomi delle colonne nella tabella
	
	public $columns_name = array("id", "miniera", "granaio", "foresta", "mercato", "caserma", "chiesa", "universita", "magazzino", "mura");

	public $message  = array(
			"full_queue"		=> "I tuoi operai sono già occupati in altre costruzioni ! Aspetta che si liberino.",
			"at_work"			=> "Gli operai hanno iniziato i lavori!",
			"wait_end_upgrade"  => "La struttura è in ampliamento al livello: ",
			"in_queue"			=> "I tuoi operai sono già  occupati in questa costruzione!",
			"amplia_magazzino"	=> "Non c'è abbastanza capacità  in magazzino, bisogna ampliarlo !"
	);
/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/
	
	public function __construct() {
		parent::__construct();
	}
	
	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "10","required"),
			"miniera" => array("type" => "integer","length_max" => "3"),
			"granaio" => array("type" => "integer","length_max" => "3","required"),
			"foresta" => array("type" => "integer","length_max" => "3","required"),
			"mercato" => array("type" => "integer","length_max" => "3","required"),
			"caserma" => array("type" => "integer","length_max" => "3","required"),
			"chiesa" => array("type" => "integer","length_max" => "3","required"),
			"universita" => array("type" => "integer","length_max" => "3","required"),
			"magazzino" => array("type" => "integer","length_max" => "10","required"),
			"mura" => array("type" => "integer","length_max" => "3","required"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"miniera" => "Miniera",
			"granaio" => "Granaio",
			"foresta" => "Foresta",
			"mercato" => "Mercato",
			"caserma" => "Caserma",
			"chiesa" => "Chiesa",
			"universita" => "Universita",
			"magazzino" => "Magazzino",
			"mura" => "Mura",
			"struttura" => "Struttura",
			"scadenza"  => "Pronta tra"
		);
	}
	
	//Restituisce il livello della struttura richiesta
	
	public function get_level($nome_struttura, $id_villo){
		
		$query_testo		= 	"SELECT " .$nome_struttura." 
								AS livello FROM " . $this->tbl_name . " 
								WHERE ".$this->columns_name[0]." = " . $id_villo;

		$query_livello 		= $this->query($query_testo);
		$info_struttura	 	= mysqli_fetch_assoc($query_livello);
		
		return $info_struttura["livello"];
	}

	
	public function start_up_building($column_name_ris, $params_upgrade, $villo_id){
		
		global $risorse, $costruzioni_code;
		
		//Valore numerico della struttura da costruire/ampliare
		$id_struttura = array_search($column_name_ris, $this->columns_name);
		
		$state_return = false;
		
		//Aggiorno la quantitÃ  delle risorse
		$risorse->refresh_resources($_SESSION["id_user"], $villo_id);

		$txt_oro 	= $risorse->columns_name[1];
		$txt_legno	= $risorse->columns_name[2];
		$txt_ferro  = $risorse->columns_name[3];
		$txt_cibo   = $risorse->columns_name[4];
		$txt_id_ris = $risorse->columns_name[0];
		
		$query_level 	= $this->query("SELECT " .$column_name_ris. " AS livello FROM " . $this->tbl_name . " WHERE ".$this->columns_name[0]." = " . $villo_id);

		$info_struttura = mysqli_fetch_assoc($query_level);
		$livello		= $info_struttura["livello"];
		
		//Verifico che l'utente abbia le risorse richieste per ragiungere questo livello
		
		$costo_oro 		= $risorse->get_cost($params_upgrade, $livello, $risorse->columns_name[1]);	//Oro
		$costo_legno 	= $risorse->get_cost($params_upgrade, $livello, $risorse->columns_name[2]);	//Legna
		$costo_ferro 	= $risorse->get_cost($params_upgrade, $livello, $risorse->columns_name[3]);	//Ferro
		$costo_cibo 	= $risorse->get_cost($params_upgrade, $livello, $risorse->columns_name[4]);	//Grano
		
		$query_risorse  = $this->query("SELECT * FROM " . $risorse->tbl_name . " WHERE ".$txt_id_ris." = " . $villo_id);
		
		$info_risorse   = mysqli_fetch_assoc($query_risorse);
		
		//Confronto le risorse attuali con quelle richieste
		
		if(	$costo_oro 	 < $info_risorse[$txt_oro] 		AND
			$costo_legno < $info_risorse[$txt_legno]	AND
			$costo_ferro < $info_risorse[$txt_ferro]	AND
			$costo_cibo  < $info_risorse[$txt_cibo]
		){
			
			//Verifico che questo edificio non si trovi gia in costruzione
			$query_exists = $this->query("SELECT * FROM " .$costruzioni_code->tbl_name. " WHERE ".$costruzioni_code->columns_name[1]." = ".$villo_id." AND ".$costruzioni_code->columns_name[2]." = ".$id_struttura);
			$exists		  = @mysqli_num_rows($query_exists);

			//Se l'edificio non è gia in costruzione
			if(!$exists){

				if($costruzioni_code->get_available_workers($villo_id) < $costruzioni_code::MAX_WORKERS){
	
					$costo_tempo	= $risorse->get_cost($params_upgrade, $livello, "tempo");
					
					//Scalo le risorse per l'ampliamento / costruzione
					$query_new_resources = "UPDATE " . $risorse->tbl_name . " 
											SET 
										   " .$txt_oro. 	" = " .$txt_oro. 	" - " .$costo_oro. 	 ",
										   " .$txt_legno. 	" = " .$txt_legno. 	" - " .$costo_legno. ",		
										   " .$txt_ferro. 	" = " .$txt_ferro. 	" - " .$costo_ferro. ",	
										   " .$txt_cibo.	" = " .$txt_cibo. 	" - " .$costo_cibo.  "
										   	WHERE ".$txt_id_ris." = " . $villo_id;
		
					$risorse->query($query_new_resources);
					
					//Aggiungo nella coda di costruzione questo edificio
		
					$costruzioni_code->attributes["id_villo"]	= $villo_id;
					$costruzioni_code->attributes["struttura"]	= $id_struttura;
					$costruzioni_code->attributes["scadenza"]	= date('Y-m-d H:i:s', time() + $costo_tempo);	//Il costo Ã¨ gia in secondi
					
					$costruzioni_code->update($costruzioni_code);
					
					$state_return = $this->message["at_work"];	
					
				}else{
					$state_return = $this->message["full_queue"];	
				}
			
			}else{
				$state_return = $this->message["in_queue"];
			}

		}else{
			$state_return = $risorse->message["no_resources"];
		}
		
		return $state_return;
		
	}
	
}
?>

