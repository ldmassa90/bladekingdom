<h2>
<?php 
echo $text_labels["messaggi_ricevuti"];
?>
</h2>
<?php

$ricevuti = $messaggi->query("SELECT id, mittente, oggetto, data_invio FROM " . $messaggi->tbl_name . " WHERE destinatario = " . $_SESSION["id_user"] . " LIMIT 0,30");

if(mysqli_num_rows($ricevuti)){
	
	echo "
			<table>
			
			<tr>
				<th>".$text_labels["mittente"]."</th>
				<th>".$text_labels["oggetto"]."</th>
				<th>".$text_labels["data_invio"]."</th>
			</tr>
		";
	
		 while($msg_ricevuti = mysqli_fetch_assoc($ricevuti)){
			
			echo "
					<tr>
						<td>".$utenti->getUser($msg_ricevuti["mittente"])."</td>
						<td><a href='?p=".$page."&id=".$msg_ricevuti["id"]."'>".$msg_ricevuti["oggetto"]."</a></td>
						<td>".$msg_ricevuti["data_invio"]."</td>
					</tr>
				 ";
		}
		
		echo "</table>";

}else{
	echo "Non sono presenti messaggi al momento.";
}


?>

