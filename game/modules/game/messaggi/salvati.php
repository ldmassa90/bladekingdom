<h2>
<?php 
echo $text_labels["messaggi_salvati"];
?>
</h2>
<?php

$salvati = $messaggi->query("SELECT id, destinatario, oggetto, data_invio FROM " . $messaggi->tbl_name . " WHERE destinatario = " . $_SESSION["id_user"] . " and stato = 2 LIMIT 0,30");

if(mysqli_num_rows($salvati)){
	
	echo "
			<table>
			
			<tr>
				<th>".$text_labels["destinatario"]."</th>
				<th>".$text_labels["oggetto"]."</th>
				<th>".$text_labels["data_invio"]."</th>
			</tr>
		";
	
		 while($msg_salvati = mysqli_fetch_assoc($salvati)){
			
			echo "
					<tr>
						<td>".$utenti->getUser($msg_salvati["destinatario"])."</td>
						<td>".$msg_salvati["oggetto"]."</td>
						<td>".$msg_salvati["data_invio"]."</td>
					</tr>
				 ";
		}
		
		echo "</table>";

}else{
	echo "Non sono presenti messaggi al momento.";
}


?>

