<?php
//Coda di costruzione
$strutture_in_coda = $costruzioni_code->get_queue($villo_req);

if(mysqli_num_rows($strutture_in_coda)){
?>
<table>

	<tr>
		<th><?=$strutture_nomi["struttura"];?></th>
		<th><?=$strutture_nomi["scadenza"];?></th>
	</tr>
	
	<?php 
	
		while($info_strutture = mysqli_fetch_assoc($strutture_in_coda)){
			
			$id_costruzione = $info_strutture[$costruzioni_code->columns_name[2]];
			$nome_struttura = $strutture->columns_name[$id_costruzione];
			
			echo "<tr><td>".$strutture_nomi[$nome_struttura]."</td><td id='countdown'>".$info_strutture["scadenza"]."</td></tr>";
		}
	
	?>

</table>
<?php 
}
?>
