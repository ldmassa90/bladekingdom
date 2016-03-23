<?php

class GUI{
	
	private $attributes_all = array();
	
	public function general_attributes($attributes = array()){
		
		foreach($attributes as $key => $value){
			$this->attributes_all[$key] = $value;
		}
		
	}
	
	private function explode_attributes($type, $attr){
		
		if($type != "select" && $type != "textarea"){
		
			$input = "<input type='".$type."'";
			
			if(isset($attr["disabled"])){
				
				if(!$attr["disabled"]){
					unset($attr["disabled"]);
				}
			}
			
			foreach($attr as $key => $value){

				if(isset($value)){
					$input .= " " . $key . " = \"" . htmlspecialchars($value) . "\"";
				}
			}
			
			$input .= " />";
			
		}elseif($type == "select"){
			
			$input = "<select>";
			
				foreach($attr as $key => $value){
					$input .= "<option value='" . $key . "'>" . $value . "</option>";
				}
				
			$input .= "</select>";
			
		}else{
			$input = "<textarea";
				
			$value_textarea = "";
			
			if(array_key_exists("value", $attr)){
				$value_textarea = $attr["value"];
				unset($attr["value"]);
			}
				
				foreach($attr as $key => $value){
					$input .= " " . $key . "='" . $value . "'";
				}
				
			$input .= ">".$value_textarea."</textarea>";
		}
		
		return $input;
		
	}
	
	/*
		Questa funzione crea un collegamento diretto con i dati ottenuti dal database
		se ci sono dati che vanno aggiunti agli input automaticamente definirli qui !
		(e nel modello strutturale della classe di interesse) es. ClassUtenti
	*/
	
	private function getInputAttribute($class, &$attributes){

		$rules = $class->rules();
		$key   = $attributes["key_rules"];
		$rules = $rules[$key];
	
		if($rules["length_max"]){
			$attributes["maxlength"] = $rules["length_max"];
		}
		
		if(!$attributes["name"]){
			$attributes["name"] = $key;
		}
		
		unset($attributes["key_rules"]);
	}
	
	/*
	 * 	type 			= Tipologia dell'input
	 *  class_name  	= Il nome della classe a cui apparterr� l'input. es. Utenti, messaggi ecc..
	 *  attributes  	= Attributi personalizzati che avr� l'input
	 *  noattribtes 	= Attributi standard che tutti gli input hanno. Per usarli bisogna prima 
	 *  			  	  riempire l'array attributes_all
	 *  autocomplete 	= Se true imposta in automatico il value dell'input	 
	 */
	
	public function input($type = "", $class_name = "", $attributes = array(), $no_default_attributes = false){

		if(!empty($class_name)){
			$this->getInputAttribute($class_name, $attributes);
		}
			
		if(!$no_default_attributes){
			//Fondo l'attributo dell'input e un POSSIBILE array con attributi che TUTTI gli input dovranno avere
			$attributes = array_merge($attributes, $this->attributes_all);
		}
			
		return $this->explode_attributes($type, $attributes);
	}

	/*
	function __destruct(){
		unset($this->attributes_all);
	}
	*/

}

?>