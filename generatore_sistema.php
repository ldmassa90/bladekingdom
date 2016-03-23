<?php 
$x = mysqli_connect("localhost","root","","bladekingdom");

$query_showtable = $x->query("SHOW TABLES");
while($table_name = mysqli_fetch_array($query_showtable)){
	
	$nome_tab 		= $table_name[0];
	
	$query_detail = $x->query("DESCRIBE ".$nome_tab);
	$nome_classe = strstr($nome_tab, "_");

	$nome_classe = str_replace("_", "", $nome_classe);
	$nome_classe = ucfirst(strtolower($nome_classe));
	
	if($nome_classe != "Villi" && $nome_classe != "Villirisorse" && $nome_classe != "VilliStrutture" && $nome_classe != "Utenti" && $nome_classe != "Messaggi"){
		
	$file = fopen("./".$nome_classe.".php","w");
	$content = "<?php\n\nclass ".$nome_classe." extends CRUDetail{\n\n\n";
	
	$content .= "//Nome della tabella nell'archivio dati\n\n";
	$tbl_name = "$";
	$tbl_name .= "tbl_name";
	$content .= "\tpublic $tbl_name = '".$nome_tab."';\n\n";
	
	$content .= "//Nome della seguente classe\n\n";
	$class = "$";
	$class .= "class_name";
	$content .= "\tpublic $class = '".$nome_classe."';\n\n";	
	
	//Creo la funzione per le regole di validazione dei singoli dati
	$content .=  "/*\n Definizione delle regole per la validazione dei dati ricevuti \n in input dall'utente. I seguenti dati sono ottenuti \n direttamente dal database.\n*/\n\n";
	$content .=  "\tpublic function rules(){\n";
	$content .=  "\n\t\treturn array(\n";
	$content2 = "";
	while($table_details = mysqli_fetch_assoc($query_detail)){
		
		if($table_details['Key'] == "PRI"){
			
			$tipologia = strstr($table_details['Type'], "(", true);
			
			if(!$tipologia){
				$tipologia = $table_details['Type'];
			}
			
			$content2 .=  "\t\t\t\"PRIMARY\" => \"".$table_details['Field']."\"\n";
/*
			$tipologia = strstr($table_details['Type'], "(", true);
			if(!$tipologia){
				$tipologia = $table_details['Type'];
			}
			
			if($tipologia == "int"){
			$tipologia= "integer";
			}
			if($tipologia == "varchar"){
			$tipologia= "string";
			}
			
			$content .=  "\"type\" => \"".$tipologia."\"";
			
			$lunghezza = strstr($table_details['Type'], "(");
			if($lunghezza){
			
				$lunghezza = str_replace("(","",$lunghezza);
				$lunghezza = str_replace(")","",$lunghezza);

				$content .=  ",\"length_max\" => \"".$lunghezza."\"),\n";
			}else{
				echo "";
			}
*/
			
		}
		//else{

			$content .=  "\t\t\t\"".$table_details['Field']."\" => array(";
			
			$tipologia = strstr($table_details['Type'], "(", true);
			if(!$tipologia){
				$tipologia = $table_details['Type'];
			}
			
			if($tipologia == "int" || $tipologia == "smallint" || $tipologia == "tinyint" || $tipologia == "mediumint"){
				$tipologia= "integer";
			}
			if($tipologia == "varchar" || $tipologia == "text"){
				$tipologia= "string";
			}

			
			$content .=  "\"type\" => \"".$tipologia."\"";
			
			$lunghezza = strstr($table_details['Type'], "(", true);
			
			if($lunghezza){
				
				if($lunghezza == "smallint"){
					$lunghezza = 5;
				}else{
				
					if($lunghezza == "mediumint"){
						$lunghezza = 7;
					}else{
	
						if($lunghezza == "tinyint"){
							$lunghezza = 3;
						}else{
							
							if($lunghezza == "int"){
								$lunghezza = 10;
							}else{
								$lunghezza = strstr($table_details['Type'], "(");
								$lunghezza = str_replace("(","",$lunghezza);
								$lunghezza = str_replace(")","",$lunghezza);
							}
						}
					}
				}

				
				
				$content .=  ",\"length_max\" => \"".$lunghezza."\"";
			}else{
				echo "";
			}
			
			$nullo = $table_details['Null'];

			if($nullo == "NO"){
				$content .=  ",\"required\"),\n";
			}else{
				$content .=  "),\n";
			}
			
	//	}
	}
	
	
	$content .= $content2;
	
	$content .=  "\t\t);\n";
	$content .=  "\t}\n\n";
	
	
	//Creo la funzione grafica per la visualizzazione
	
	$query_detail = $x->query("DESCRIBE ".$nome_tab);
	$content .=  "/*\n Definizione del formato testuale dei campi che visualizzera' l'utente.\n*/\n\n";
	
	$content .=  "\tpublic function attributeLabels(){\n";
	$content .=  "\n\t\treturn array(\n";
		
	while($table_details = mysqli_fetch_assoc($query_detail)){
		if($table_details['Key'] != "PRI"){
		
			$content .=  "\t\t\t\"".$table_details['Field']."\" => \"".ucwords(str_replace("_", " ", $table_details['Field']))."\",\n";
			

		}
	}
	
	
	$content .=  "\t\t);\n";
	$content .=  "\t}\n\n";
	
	$content .=  "}\n?>\n\n";
	
	fwrite($file,$content);
	fclose($file);
	}
}

?>