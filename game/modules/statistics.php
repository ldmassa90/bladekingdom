<?php 
//Sql per gli utenti attivi
$query_ative 	= "(SELECT COUNT(*) actived FROM ".$user_auth->tbl_name." WHERE stato = 1) AS actived";

//Sql per gli utenti attivi
$query_new_usr 	= "(SELECT COUNT(*) FROM ".$user_auth->tbl_name." WHERE registrato = CURDATE()) AS new_users";


//Sql con tutti i dati relativi alle statistiche
$statistics = $user_auth->query("SELECT COUNT(*) AS registred, ".$query_ative.", ".$query_new_usr." FROM ".$user_auth->tbl_name);

$data_stat  = mysqli_fetch_assoc($statistics);
?>


<h3>Statistiche del gioco</h3>

<table>

	<tr>
		<th>Giocatori registrati:</th><td><?=$data_stat["registred"]?></td>
	</tr>
	<tr>
		<th>Giocatori attivi:</th><td><?=$data_stat["actived"]?></td>
	</tr>
	<tr>
		<th>Nuove iscrizioni:</th><td><?=$data_stat["new_users"]?></td>
	</tr>
	<tr>
		<th>Giocatori online:</th><td>2075</td>
	</tr>

	<tr>
		<th>Gioco iniziato da:</th><td>112 ore</td>
	</tr>
	
</table>