<?php 
//Creo gli input per il login

//Tutti gli input avranno queste caratteristiche
$gui->general_attributes();

//Disegno gli input
$input_username = $gui->input("text", $user_auth, array("key_rules" => "utente", "value" => $_POST["utente"]));
$input_password = $gui->input("password", $user_auth, array("key_rules" => "password"));
$input_submit   = $gui->input("submit", "", array("value" => "Login", "name" => "submit"), true);
?>
<form action="./index.php" method="post">
	<table>
		<tr>
			<th>Username: </th><td><?=$input_username?></td>
			<th>Password: </th><td><?=$input_password?></td>
			<th></th><td><?=$input_submit?></td>
		</tr>
	</table>
</form>