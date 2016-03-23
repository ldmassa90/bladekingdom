<?php

class Universita extends CRUDetail{


//Nome della tabella nell'archivio dati

	public $tbl_name = 'tbl_universita';

//Nome della seguente classe

	public $class_name = 'Universita';

	//Nomi delle colonne nella tabella
	
	public $columns_name = array("id", "id_villo", "tecnologia_militare", "tecnologia_produttiva", "tecnologia_civile");

	public $messaggi  = array(
			
			"telescopio" => array(
									"descrizione"   => "Grazie a questo marchingegno riusciremo a vedere i nemici da lunghissime distanze !",
									"potenziamento" => array("+15% di reattività delle truppe"),
									"costi"			=> array()
								  ),
			"trappole" => array(
					"descrizione"   => "Chiunque oserà avvicinarsi alle nostre proprietà ci penserà due volte prima di farlo.",
					"potenziamento" => array(	"5-15% di truppe avversarie in caso di attacchi", 
												"+5% di possibilità nello sgominare spie"
											),
					"costi"			=> array()
			),		
			"ronda" => array(
					"descrizione"   => "La ronda cittadina consentirà una maggiore sicurezza della nostra bella città !",
					"potenziamento" => array(	"+40% di reattività delle truppe",
												"+25% di possibilità nello sgominare spie",
												"-10% su ogni risorsa"
											),
					"costi"			=> array()
			),
			"turni" => array(
					"descrizione"   => "I turni di guardia spesso sono stressanti... una pausa ogni tanto permetterebbe una maggiore efficenza !",
					"potenziamento" => array(	"+5% reattività truppe"),
					"costi"			=> array()
			),
			
	);
	
	//Albero di sviluppo nell'università
	
	public $albero	 = array(
			"militare"		=> array("telescopio", "trappole", "ronda", "turni", "cani", "polvere"),
			"produzione"	=> array("illuminazione", "binari"),
			"civile"		=> array("tasse", "allarme", "arruolamento", "sindacato", "festa"),
	);

/*
 Definizione delle regole per la validazione dei dati ricevuti 
 in input dall'utente. I seguenti dati sono ottenuti 
 direttamente dal database.
*/

	public function rules(){

		return array(
			"id" => array("type" => "integer","length_max" => "10","required"),
			"id_villo" => array("type" => "integer","length_max" => "10","required"),
			"tecnologia_militare" => array("type" => "string","length_max" => "1","required"),
			"tecnologia_produttiva" => array("type" => "string","length_max" => "1","required"),
			"tecnologia_civile" => array("type" => "string","length_max" => "1","required"),
			"PRIMARY" => "id"
		);
	}

/*
 Definizione del formato testuale dei campi che visualizzera' l'utente.
*/

	public function attributeLabels(){

		return array(
			"id_villo" => "Id Villo",
			"tecnologia_militare" => "Tecnologia Militare",
			"tecnologia_produttiva" => "Tecnologia Produttiva",
			"tecnologia_civile" => "Tecnologia Civile",
		);
	}
	
	
	// Restituisce il livello tecnologico per una categoria
	// 0 = Militare
	// 1 = Produzione
	// 2 = Civile
	
	public function avanzamento_tecnologico($id_villaggio, $tipo_categoria){
		
		$nome_colonna = $this->columns_name[2];
		
		if($tipo_categoria == 2){
			$nome_colonna = $this->columns_name[3];
		}elseif($tipo_categoria == 3){
			$nome_colonna = $this->columns_name[4];
		}
		
		$query = $this->query("SELECT " . $nome_colonna . " FROM " . $this->tbl_name . " WHERE " . $this->columns_name[1] . " = " . $id_villaggio);
		$info_avanzamento  = mysqli_fetch_assoc($query);
		
		return $info_avanzamento[$nome_colonna];
	}

	
}
?>

