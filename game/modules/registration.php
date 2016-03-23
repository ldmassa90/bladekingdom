<form action="page.php" method="POST">
  <div align="center">
	  <h2>Registrati!</h2>
		  <table>
		  	<tr>
		      <td><?=$user_field["utente"];?></td>
		      <td>
			  <?=$gui->input("input", $user_auth, array("name" => "username", "value" => $_POST["username"]));?>
		      </td>
		    </tr>
		    <tr>
		      <td><?=$user_field["email"];?></td>
		      <td>
		          <?=$gui->input("input", $user_auth, array("name" => "email", "value" => $_POST["email"]));?>
		      </td>
		    </tr>
		    <tr>
		      <td><?=$user_field["password"];?></td>
		      <td>
		          <?=$gui->input("input", $user_auth, array("name" => "password"));?>
		      </td>
		    </tr>
		    <tr>
		      <td><?=$user_field["password_c"];?></td>
		      <td>
		          <?=$gui->input("input", $user_auth, array("name" => "password_2"));?>
		      </td>
		    </tr>
		    <tr>
		      <td></td>
		      <td>
		          <?=$gui->input("submit", $user_auth, array("name" => "submit", value => "Registrati !"));?>
		      </td>
		    </tr>
		  </table>

  </div>
</form>