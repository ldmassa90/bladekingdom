<?php

class Text{
	
	
	/*
	 * Definizione della funzione per la creazione dei link
	 */
	
	public function createLink($pagina, $attributi = array(), $nome_pagina = false){
	
		$sotto_pagina = array("sub", "sub_2", "sub_3");
	
		if(!$nome_pagina){
			$link = "home.php?p=".$pagina;
		}else{
			$link = $nome_pagina.".php?p=".$pagina;
		}
	
		foreach($attributi as $key => $value){
	
			if(is_numeric($key)){
				$link .= "&" . $sotto_pagina[$key] . "=" . $value;
			}else{
				$link .= "&" . $key . "=" . $value;
			}
				
		}
	
		return $link;
	}
	
	/*
	 Definizione del formato testuale dei campi che visualizzera' l'utente.
	*/
	
	public function attributeLabels(){
	
		//Trasformo ogni prima lettera della chiave in maiuscolo (se non è specificato un testo)
	
		if(isset($this->testo_messaggi)){
			
			foreach($this->testo_messaggi as $chiave => $testo){
					
				if(is_numeric($chiave)){
					$testo_utente = str_replace("_", " ", $testo);
					$this->testo_messaggi[$testo] = ucwords($testo_utente);
				}
			}
		
			return $this->testo_messaggi;
		}
	}
	
}

?>