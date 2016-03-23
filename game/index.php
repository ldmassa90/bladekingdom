<?php 
session_start();
error_reporting(E_ALL & ~E_NOTICE);
require("./system/ClassCRUDetail.php");
require("./system/ClassUtenti.php");
require("./system/ClassGUI.php");

//Istanzio l'oggetto grafico
$gui = new GUI();

//Inizializzo la classe per la validazione

$user_auth 	= new Utenti();
$user_field	= $user_auth->attributeLabels();

//Validazione in caso di richiesta login
$submit = $_POST['submit'];

if(isset($_POST['submit'])){
	$attributi = array();

	$attributi['utente'] 	= addslashes($_POST['utente']);
	$attributi['password'] =  addslashes($_POST['password']);
	$rules					= $user_auth->rules();

	$esito = $user_auth->validate($rules, $attributi, 2);
	
	if(is_bool($esito)){		//I dati sono formalmente corretti.
		
		//Verifico l'esistenza di questi dati nell'archivio
		
		$attributi['password'] = md5(md5(sha1(md5($attributi['password']))));
		
		$exists = $user_auth->query("SELECT id, stato FROM ".$user_auth->tbl_name." WHERE utente = '".$attributi['utente']."' AND password = '".$attributi['password']."'");
		
		echo "SELECT id, stato FROM ".$user_auth->tbl_name." WHERE utente = '".$attributi['utente']."' AND password = '".$attributi['password']."'";

		if(!$exists){
			$esito = "Non risulta nessun utente registrato con questi dati";
		}else{
			//Verifico se l'utente è attivo
			$exists = mysqli_fetch_assoc($exists);
			$state 	= $exists["stato"];
			
			if($state == 1){
				//L'utente può accedere ! Aggiorno l'ultimo login
				
				$user_auth->attributes["id"] 		   = $exists['id'];
				$user_auth->attributes["ultimo_login"] = date("Y-m-d H:i:s");
				$update_info_user = $user_auth->update($user_auth);
			
				if($update_info_user){
					$_SESSION["id_user"] = (int)$exists["id"];
					header("Location: ./home.php");
				}
				
			}elseif($state == 0){
				$esito = "Il tuo utente non è stato ancora attivato ! Accedi alla tua posta elettronica e clicca sul link riportato nella email di registrazione !";
			}
			
		}
		
	}
}

$stop = false; //Includo TUTTI i moduli nella pagina
?>
<html>
	<head>
		<script type="text/javascript" src="jquery-1.11.2.min.js"></script>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<link rel="stylesheet" href="./style/styleHome.css">
		<title>BladeKingdom - Browser Game</title>
	</head>
	<body>
		<div id="title">
			<img src="./images/home/test.png" />
		</div>
		
		<div id="content">
			
			<div id="top">
				<div id="login"><?php include("./modules/login.php"); ?></div>
			</div>
			
			<div id="middle">
				<div id="statistics"><?php include("./modules/statistics.php"); ?></div>
				<div id="datetime">Orario del server: <?=date(" H:i:s");?></div>
				<div id="errors">
				<?php 
					if(!empty($esito)){
						echo nl2br($esito);
						$stop = true;
					}
				?>
				</div>
				<?php 
					include("./modules/registration.php");
				?>
			</div>
			
		</div>
		
		<div id="register_free">
			<h2><a href="">Registrati ora gratuitamente !</a></h2>
		</div>
	</body>
	<script type="text/javascript">
	<!--
	setInterval(function(){ time_refresh() }, 1000);
	
	function time_refresh(){
		//DateTime refresh
		var data 	= new Date();
		var time 	= "Orario del server: ";
		
		time += data.getHours() + ":";
		time += data.getMinutes() + ":";
		time += data.getSeconds();
		document.getElementById("datetime").innerHTML = time;
	}

	-->
	</script>

</html>