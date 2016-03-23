<?php 
require("/ClassConnection.php");

class CRUDetail extends Connection{

	public $class_init = "";
	public $error_message  = array(
								"empty" 		=> "non può essere vuoto\n", 
								"size" 			=> "non è di una lunghezza consentita\n", 
								"date_format" 	=> "non ha un formato data valido\n", 
								"date_invalid" 	=> "non ha valori corretti\n", 
								"type" 			=> "non è della tipolgia consentita\n",
								"few_data"		=> "Uno o piu dati obbligatori non sono stati compilati"
							);
	/*
		Questa funzione valida i dati ottenuti dall'utente verificando che siano compatibili
		con le strutture presenti nell'archivio dati
		
		
		$level_control = 1 //Viene controllato TUTTO dall'obbligatorietà dei dati alla tipologia
		$level_control = 2 //I dati ricevuti non vengono verificati a livello di obbligatorietà
	*/
	
	public function validate($rules, $attributes, $level_control = 1){

		//Conto nelle regole quanti dati sono obbligatori
		
		//Rimuovo dall'array attributes eventuali campi che NON rientrano nelle regole del modulo di validazione
		unset($rules["PRIMARY"]);
		
		$key_additional = array_diff_key($attributes,$rules);
		
		foreach($key_additional as $key => $value){
			unset($attributes[$key]);
		}

		$attributes_elements = count($attributes);

		if($level_control == 1){
			
			$rules_required = 0;
			$field_required = array();
		
			foreach($rules as $key => $value){

				if(in_array("required", $value)){
					$field_required[] = $key;
					$rules_required++;
				}
			}
			
		}else{
			$rules_required = $attributes_elements;
		}
		
		//Verifico che i dati ricevuti dall'utente siano ALMENO uguali ai campi obbligatori. Altrimenti
		//avr� omesso dati essenziali ai fini della validazione
		
		if($attributes_elements >= $rules_required){

			if($level_control == 1){
			
				//Verifico che nei dati ricevuti ci siano quelli obbligatori
				$validate_successful = true;
				$counter 			 = 0;
				$attributes_search   = $attributes;
				
				do{
					//Confronto gli attributi richiesti dal db
					$attribute = $field_required[$counter];

					if(!array_key_exists($attribute, $attributes_search)){
						$validate_successful = false;
					}else{
						//Elimino il dato trovato nei dati dell'utente per ottimizzare le ricerche successive
						unset($attributes_search[$attribute]);
					}
					
					$counter++;
				}while($validate_successful && $counter < $rules_required);
			
			}else{
				$validate_successful = true;
			}
			
			if($validate_successful){

				//Ora che ho ALMENO TUTTI i dati OBBLIGATORI procedo a controllarne la validit� in tipologia, lunghezza ecc..
				
				$errors    = array();

				foreach($attributes as $key => $value){
					
					if(!empty($value)){
				
						if($rules[$key]["type"] != "date"){
							
							if(gettype($value) != $rules[$key]["type"]){
								//Questo dato non è della stessa tipologia !
								$errors["type"][] = $key;
							}
							
							$size_value = strlen($value);
							
							if(array_key_exists("length_max", $rules[$key])){

								if($size_value > $rules[$key]["length_max"]){
									//Questo dato supera la lunghezza consentita dal sistema !
									$errors["size"][] = $key;
								}
								
							}
							
							if(array_key_exists("length_min", $rules[$key])){
						
								if($size_value < $rules[$key]["length_min"]){
									//Questo dato è inferiore alla lunghezza minima!
									$errors["size"][] = $key;
								}
							}
							
						}else{
							
							//Splitto la data in formato stringa per verificarne la validità
							
							$data = explode("-", $value);
							$anno = $data[0];
							$mese = $data[1];
							$giorno = $data[2];
							
							if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value)){
								
								if(!checkdate($mese, $giorno, $anno)){
									//Questo dato non è della stessa tipologia !
									$errors["date_invalid"][] = $key;
								}
								
							}else{
								//Data nel formato non valido !
								$errors["date_format"][] = $key;
							}

						}
					
					}else{
						//Uno o piu campi sono vuoti !
						$errors["empty"][] = $key;
					}
				}

			}else{
				//Uno o piu dati obbligatori mancano !
				$errors["few_data"] = true;
			}
			
			
		}else{
			//Uno o piu dati obbligatori mancano !
			$errors["few_data"] = true;
		}
	
		
		if(!array_key_exists("few_data", $errors)){
			$msg_errors = $this->print_errors($errors, $msg_errors = "");
		}else{
			$msg_errors = $this->error_message["few_data"];
		}


		if(!is_bool($msg_errors)){
			return $msg_errors;
		}else{
			return true;
		}

	}
	
	private function function_die(){
		//Eliminazione dei dati ricevuti
		unset($this->attributes);
	}
	
	private function update_autocomplete($attributes){

		$rules 			= $this->rules();			//Regole proveniente dal db
		$rules_pk 		= $rules["PRIMARY"];		//Prelevo la chiave primaria
		$pk				= $attributes[$rules_pk];	//La chiave primaria ci deve essere per forza per gli aggiornamenti
		$case_action 	= array_key_exists($rules_pk, $attributes) ? true : false;
		$sql			= "";
		$columns_in_tb  = array();
		
		unset($attributes[$rules_pk]);
		
		foreach($rules as $key => $value){
			
			//Se la chiave delle regole esise nei dati
			if(array_key_exists($key, $attributes)){

				//Verifico di che tipo è il valore ricevuto per tradurlo nella sua forma sql
				$value_key  = addslashes($attributes[$key]);
				$type_value = gettype($value_key);

				if(is_numeric($type_value)){
					$text = $value_key;
				}else{
					$text = "'" . $value_key . "'";
				}
				
				if($case_action){
					//Aggiornamento
					$sql .= "`".$key."` = ".$text;
				}else{
					//Inserimento
					$sql .= $text;
				}
				
				$sql .= ",";
				$columns_in_tb[] = $key;
			}
				
		}
		
		//Nell'archivio tutte le PK saranno numeriche e intere
		//Elimino la virgola dall'ultimo parametro imposato
		$lunghezza_sql 				= strlen($sql);
		$sql[$lunghezza_sql - 1]	= " ";		
		
		//Costruisco l'ordine di ingresso dei dati
		$n_elements = count($columns_in_tb);
		
		$order = "(`".$columns_in_tb[0]."`";
		
		if(count($columns_in_tb) > 1){
			
			$order .= ",";
			
			for($x = 1; $x < $n_elements - 1; $x++){
				$order .= "`".$columns_in_tb[$x]."`,";
			}
			
			$order .= "`".$columns_in_tb[$x]."`)";
		}else{
			$order .= ")";
		}
	
		if($case_action){
			//Aggiornamento	
			$sql_query = "UPDATE `" . $this->tbl_name . "` SET " . $sql . "WHERE `" . $rules["PRIMARY"] . "` = " . $pk . ";";
		}else{
			//Inserimento
			$sql_query = "INSERT INTO `" . $this->tbl_name . "` ".$order." VALUES(" . $sql . ");";
		}

		return $sql_query;
	}
	
	//La seguente funzione permette l'aggiornamento o l'inserimento dei dati nell'archivio
	
	public function update($data_class){
		$response 	= false;
		$class_name = $data_class->class_name;

		//Inizializzo la classe richiamante per ottenere le regole di validazione dati
		if(!is_object($this->class_init)){
			$this->class_init  = new $class_name();
		}
		
		$rules = $this->class_init->rules();
		
		//Verifico in base alla chiave primaria se si tratta di un inserimento o un aggiornamento
		$attributes  = $data_class->attributes;
		
		//In base alla PK della classe capisco se è un inserimento o aggiornamento

		$sql_query = $this->update_autocomplete($attributes);

		$update = $this->query($sql_query);
		
		if($update){
			$response = true;
		}
		
		$this->function_die();
		return $response;
		
	}
	
	
	private function print_errors($list_errors, $text_errors){

		if(count($list_errors) == 0){
			
			if(empty($text_errors)){
				return true;
			}else{
				return $text_errors;
			}
			
		}else{

			$category_error = key($list_errors);
					
	
			if(array_key_exists($category_error, $list_errors) && count($list_errors[$category_error]) > 0){
			
				$field = $list_errors[$category_error][0];
				$text_errors .= "Il campo ".$field." ".$this->error_message[$category_error];
				
				array_shift($list_errors[$category_error]);
				
				}else{
					
				unset($list_errors[$category_error]);
			}
				
			
			
			return $this->print_errors($list_errors, $text_errors);
		}
		
	}
	
	//Eliminazione di un record da database
	
	public function delete($data_class){
	
		$class_name = $data_class->class_name;
		
		if(!is_object($this->class_init)){
			$this->class_init  = new $class_name();
		}
		
		//Eliminazione diretta
		$this->function_die();
	}
	
	
	function __destruct(){
		unset($this->class_init);
	}

}
?>