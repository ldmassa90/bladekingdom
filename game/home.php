<?php
session_start();
ini_set("display_errors", 0);

require("modules/game/session.php");
require("./system/ClassCRUDetail.php");
require("./system/ClassGUI.php");
require("./system/ClassUtenti.php");
require("./system/ClassVilli.php");

//Istanzio l'oggetto grafico
$gui = new GUI();

$obj_page = new Text();

//Classe degli utenti
$utenti = new Utenti();

//Classe per i villaggi
$obj_town = new Villi();

//Dati della pagina (se esisteranno)
$sub_page  = $_GET["sub"];		//Modulo da includere
$page	   = $_GET["p"];		//Pagina da mostrare
$villo_req = $_GET["v"];		//Id del villaggio da visualizzare

$id_villo = $villo_req;

if(!isset($villo_req) || !$obj_town->is_owner($villo_req, $_SESSION["id_user"])){
	//Porto l'utente in un suo villaggio che potrebbe essere il primo
	$query_villo = $obj_town->query("SELECT id FROM " . $obj_town->tbl_name . " WHERE id_proprietario = " .$_SESSION["id_user"]. " ORDER BY id ASC");	
	$data_villo  = mysqli_fetch_assoc($query_villo);
	$id_villo    = $data_villo["id"];
}

$page 		 = $_GET["p"];
$css_include = array();

switch($page){

	case VOCE_MENU_01: //Messaggi
		$file_include = "./modules/game/messaggi.php";
		$js_include	  = array();
		$css_include  = array();
		break;
	case VOCE_MENU_02: //Villaggio
		$file_include = "./modules/game/villo.php";
		$js_include	  = array  ("bottom" 	=> array("./js/villo/villo.js"),
								"top" 		=> array("./js/jquery-ui.min.js", "./js/resources.js", "./js/jquery.knob.js"));
		$css_include  = array("./style/villo.css");
		break;
	case VOCE_MENU_03: //Mappa/Mondo
		$file_include = "./modules/game/mappa.php";
		$js_include	  = array();
		$css_include  = array();
		break;
	case VOCE_MENU_04: //Messaggi
		$file_include = "./modules/game/messaggi.php";
		$js_include	  = array();
		$css_include  = array();
		break;
	case VOCE_MENU_05: //Gold
		$file_include = "./modules/game/gold.php";
		$js_include	  = array();
		$css_include  = array();
		break;
	case VOCE_MENU_07: //Logout
		$file_include = "./modules/game/logout.php";
		break;
}
	

?>
<html>
	<head>
		<script type="text/javascript" src="./js/jquery-1.11.2.min.js"></script>
		<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
		<link rel='stylesheet' href='./style/style.css' media='all' />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<?php 
		//Inclusione dei css
		if(count($css_include)){
			
			foreach($css_include as $key => $value){
				echo "<link rel='stylesheet' href='".$value."' media='all' />";
			}
		
		}

		//Inclusione dei javascript
		if(count($js_include["top"])){
				
			foreach($js_include["top"] as $key => $value){
				echo "<script type='text/javascript' src='".$value."'></script>";
			}
		
		}		
		?>
	
		<title>BladeKingdom - Browser Game</title>
	</head>
	<body>
	
		<div id="page_header">
			<div id="left_skin"></div>
			<div id="logo_small"></div>
			<div id="right_skin"></div>
		</div>
		
		<div id="main_menu">
		
			<table id="menu_table">
				<tr>
					<td class="menu_btn2">
						<a href="home.php?p=<?=VOCE_MENU_02?>&sub=1&v=<?=$id_villo;?>">Villaggio</a>
					</td>
					<td class="menu_btn3">
						<a href="home.php?p=<?=VOCE_MENU_03?>">Mondo</a>
					</td>
					<td class="menu_btn1">
						<a href="home.php?p=<?=VOCE_MENU_01?>">Messaggi</a>
					</td>
					<td class="menu_btn4">
						<a href="home.php?p=<?=VOCE_MENU_04?>">Classifica</a>
					</td>
					<td class="menu_btn5">
						<a href="home.php?p=<?=VOCE_MENU_05?>">Gold</a>
					</td>
					<td class="menu_btn6">
						<a href="home.php?p=<?=VOCE_MENU_07?>">Esci</a>	
					</td>
				</tr>
			</table>
		</div>
	
		<div id="content">
			
			<div id="content_top">
			</div>
			<div id="c_main">
				<div id="content_main">	
				<?php 
					if($file_include){
						require($file_include);
					}
				?>
				</div>
			</div>

			<div id="content_footer"></div>
	

				<?php
				/* TODO Fare un file .htacces che consenta la visione SOLO  
				 		ad alcuni file e non alla directory "game/messaggi"
				 		via url in modo diretto.
				 		es miosito.it/game/messaggi ec...
				*/
				?>

		</div>
		<?php 
		
		if(count($js_include["bottom"])){
		
			foreach($js_include["bottom"] as $key => $value){
				echo "<script type='text/javascript' src='".$value."'></script>";
			}
		
		}
		
		require("/modules/game/footer.php");
		?>
	
	</body>

</html>