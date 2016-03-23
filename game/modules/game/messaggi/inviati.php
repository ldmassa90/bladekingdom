<h2>
<?php 
echo $text_labels["messaggi_inviati"];
?>
</h2>
<?php

$inviati = $messaggi->query("SELECT id, destinatario, oggetto, data_invio FROM " . $messaggi->tbl_name . " WHERE mittente = " . $_SESSION["id_user"] . " LIMIT 0,30");

if(mysqli_num_rows($inviati)){
	
	echo "
			<table>
			
			<tr>
				<th>".$text_labels["destinatario"]."</th>
				<th>".$text_labels["oggetto"]."</th>
				<th>".$text_labels["data_invio"]."</th>
			</tr>
		";
	
		 while($msg_inviati = mysqli_fetch_assoc($inviati)){
			
			echo "
					<tr>
						<td>".$utenti->getUser($msg_inviati["destinatario"])."</td>
						<td><a href='?p=".$page."&id=".$msg_inviati["id"]."'>".$msg_inviati["oggetto"]."</a></td>
						<td>".$msg_inviati["data_invio"]."</td>
					</tr>
				 ";
		}
		
		echo "</table>";

}else{
	echo "Non sono presenti messaggi al momento.";
}


?>

