<br/><br/>

<?php 

	//$id_email proviene da messaggi.php
	
	$build_message = $messaggi->mex_owner($id_email, $_SESSION["id_user"]);
	
	if($build_message){
		
		$msg_dati = mysqli_fetch_assoc($build_message);
		
		echo "<table>";
		
		//Se il mittente corrisponde allora sto visualizzando un messaggio inviato, quindi mostro il destinatario
		if($msg_dati["mittente"] == $_SESSION["user_id"]){
			
			echo "
					<tr>
						<th>".$text_labels['mittente']."</th>
						<td>".$utenti->getUser($msg_dati["mittente"])."</td>
					</tr>
				";
			
		}else{

			echo "
					<tr>
						<th>".$text_labels['destinatario']."</th>
						<td>".$utenti->getUser($msg_dati["destinatario"])."</td>
					</tr>
				";
		}
		
		echo "		
					<tr>
						<th>".$text_labels['oggetto']."</th>
						<td>".$msg_dati["oggetto"]."</td>
					</tr>
					<tr>
						<th>".$text_labels['data_invio']."</th>
						<td>".$msg_dati["data_invio"]."</td>
					</tr>
					<tr>
						<th>".$text_labels['messaggio']."</th>
						<td>".$msg_dati["messaggio"]."</td>
					</tr>
				</table>	
			";
		
		//Tasti per le operazioni
		echo $gui->input("submit", $messaggi, array("name" => "submit", value => "Elimina"));
		echo $gui->input("submit", $messaggi, array("name" => "submit", value => "Rispondi"));
		echo $gui->input("submit", $messaggi, array("name" => "submit", value => "Rispondi ed elimina"));
		
	}else{
		echo "Ooops, non è stato possibile recuperare questo messaggio.Forse lo hai già eliminato...oppure non è un tuo messaggio";
	}


?>