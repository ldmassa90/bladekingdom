<?php 
//Tutti gli input avranno queste caratteristiche
$gui->general_attributes(array("size" => 30));

//Validazione e invio del nuovo messaggio
$submit 	  = $_POST['submit'];

if($submit){
	
	$attributi = array();
	
	$attributi["destinatario"] 	= $_POST['destinatario'];
	$attributi["oggetto"]	  	= $_POST['oggetto'];
	$attributi["messaggio"]		= $_POST['messaggio'];
	
	//Il destinatario deve essere un id
	if(!empty($attributi["destinatario"])){
		
		$corrispondenza = $utenti->query("SELECT id FROM ".$utenti->tbl_name." WHERE utente = '".$attributi["destinatario"]."'");
		
		//Se c'� un utente corrispondente ottengo l'id e lo imposto nel campo destinatario
		$attributi["destinatario"] = -1;
		
		if($corrispondenza){
			$dati_destinatario = $corrispondenza->fetch_assoc();
			$attributi["destinatario"] = (int)$dati_destinatario["id"];
		}
	}

	$esito = $messaggi->validate($tb_msg_rules, $attributi, 2);
	
	if(is_bool($esito)){
		
		if($attributi["destinatario"] != -1){
			
			if($attributi["destinatario"] != $_SESSION["id_user"]){
				
				//Invio il messaggio
				$messaggi->attributes["mittente"] 		= $_SESSION["id_user"];
				$messaggi->attributes["destinatario"] 	= $attributi["destinatario"];
				$messaggi->attributes["oggetto"] 		= $attributi["oggetto"];
				$messaggi->attributes["messaggio"] 		= $attributi["messaggio"];
				$messaggi->attributes["data_invio"]     = date("Y-m-d H:i:s");

				$send_message = $messaggi->update($messaggi);
	
				if($send_message){
					echo "Messaggio inviato con successo !";
				}else{
					echo "L'invio dei messaggi è momentaneamente sospeso.";
				}
				
			}else{
				$esito = "Non puoi inviarti un messaggio.";
			}
			
		}else{
			$esito = "Questo utente non esiste";
		}
		
		
	}
	
	/*

		//Controllo la lunghezza


		if(!$errore){
				
			//Controllo la validit� dell utente
				
			if(strtolower($destinatario) != strtolower($_SESSION['infouser']['Nome'])){

				$risultato = user_exists($destinatario);

				if(gettype($risultato) == boolean){
						
					//L'utente � valido, invio il messaggio
						
					//Messaggio appartenente all'utente mittente
					mysql_query("INSERT INTO messaggi VALUES (NULL, ".usr_id($destinatario).", ".$_SESSION['infouser']['ID'].", ".$_SESSION['infouser']['ID'].", '".$oggetto."', NOW(), 0, 0, 0, '".$messaggio."')") or die(mysql_error());
						
					//Messaggio appartenente all'utente destinatario
					mysql_query("INSERT INTO messaggi VALUES (NULL, ".usr_id($destinatario).", ".$_SESSION['infouser']['ID'].", ".usr_id($destinatario).", '".$oggetto."', NOW(), 0, 0, 0, '".$messaggio."')") or die(mysql_error());

					reideric("Messaggio inviato con successo ! ", $_SERVER['REQUEST_URI']);
				}else{
					echo $risultato;
				}

			}else{
				$errore = "Non puoi inviarti un messaggio da solo";
			}

	*/

}

?>
<div id="errors"><p>
	<?php 
		if(!is_bool($esito)){
			echo nl2br($esito);
		}
	?></p>
</div>
<form action='<?php echo $_SERVER['REQUEST_URI']; ?>' method='POST'>
	<table>
		<tr>
			<td><?=$text_labels["destinatario"];?>:</td>
			<td>*<?=$gui->input("text", $utenti, array("key_rules" => "utente", "name" => "destinatario", "value" => $_POST['destinatario']));?></td>
		</tr>
		<tr>
			<td><?=$text_labels["oggetto"];?>:</td>
			<td>*<?=$gui->input("text", $messaggi, array("key_rules" => "oggetto", "value" => $_POST["oggetto"]));?></td>
		</tr>
		<tr>
			<td><?=$text_labels["messaggio"];?>:</td>
			<td>*<?=$gui->input("textarea", $messaggi, array("key_rules" => "messaggio", "cols" => 50, "rows" => 20, "value" => $_POST["messaggio"]));?></td>
		</tr>				
		<tr>
			<td></td>
			<td><input type="submit" value="Invia" name="submit" /><input type="reset" value="Resetta" /></td>
		</tr>			
	</table>
</form>