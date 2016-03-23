<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/jquery.mapz.js"></script>
<script type="text/javascript" src="./js/coord.js"></script>
<?php
require("map/params.php");
require("./system/ClassMappa.php");

$mappa = new Mappa();

//VARIABILI DI CONFIGURAZIONE

//Dimensioni standard dell'immagine
$immagine_larghezza = 120;
$immagine_altezza 	= 80;

//Se l'immagine va ridimensionta abilitare questo codice !

$immagine_lgr_ridim = 78;
$immagine_alt_ridim = ($immagine_altezza * $immagine_lgr_ridim) / $immagine_larghezza;
	
$immagine_larghezza = $immagine_lgr_ridim;
$immagine_altezza	= $immagine_alt_ridim;

//Varibili Mappa
$mappa_larghezza = 680;
$mappa_altezza   = 450;


//Per le coordinate Y
$y_height = (($immagine_altezza / 100) * 60);

//Coordinate del villo capitale dell'utente

$query_coordinate = $obj_town->query("SELECT x, y FROM " . $obj_town->tbl_name . " WHERE capitale = 1 AND id_proprietario = " . $_SESSION["id_user"]);
$coordinate		  = mysqli_fetch_assoc($query_coordinate);

$x	= $coordinate["x"] - 1;
$y  = $coordinate["y"] - 1;

$px_posizione_x = ($x - 4) * $immagine_larghezza;
$px_posizione_y = ($y - 4) * $immagine_altezza;
?>
<style>
.level{ 
	position:absolute; 
	left:0; 
	top:0; 
}
.map-viewport{
	position:relative; 
	width: <?=$mappa_larghezza;?>px; 
	height:<?=$mappa_altezza;?>px; 
	border:1px solid black; 
	overflow:hidden;
	float:left;
	margin-top:10px;
	margin-left:35px;
}

#map{ 
	width:<?=($immagine_larghezza * X_LAND);?>px; 
	height:<?=($immagine_altezza * Y_LAND);?>px; 
	position:absolute; 
	left:-<?=($px_posizione_x);?>px; 
	top:-<?=($px_posizione_y);?>px;
	cursor: pointer;
	background-image: url('../game/images/mappa/Terrenobase.jpg');
	z-index:1;
}

#container_xcoord{
	width:<?php echo $mappa_larghezza; ?>px;
	overflow:hidden;
	font-size: 15px;
	float:left;
	margin-left:35px;
}

<?php //Aggiunta di + X_LAND per il border grigio, i +50 sono per evitare effetto move delle coordinate ?>
#coord_x{
	width:<?php echo ($immagine_larghezza * X_LAND + X_LAND) + 50; ?>px; 
}

#xcoord_element{
	width:<?=$immagine_larghezza;?>px; 
	float: left;
	text-align: center;
}

#coord_y{
	height:<?php echo ($immagine_larghezza * X_LAND + X_LAND) + 50; ?>px;   
}

#container_ycoord{
	height:<?=$mappa_altezza?>px;
	width: 30px;
	text-align:center;
	overflow:hidden;
	font-size: 15px;
	position:absolute;
	margin-top: 22px;
}

#ycoord_element{
	height:<?=($y_height)?>px; 
	float: clear; 
	margin-top:<?=($immagine_altezza-$y_height)?>px;
}



#profile_land{
	margin-left: 41px;
	position: absolute;
	margin-top:170px;
}

#message_backg{
	display: none;
}


*[title='test']{
	color: red;
}
</style>

<div id="map_container">
	<?php
	
		//Stampo coordinate X
		echo "<div id='container_xcoord'><div id='coord_x'>";

		for($x = 1; $x <= X_LAND; $x++){
			echo "<div id='xcoord_element'>".$x."</div>";
		}
		
		echo "</div></div>";
		

		//Stampo coordinate Y
		echo "<div id='container_ycoord'><div id='coord_y'>";

		for($x = 1; $x <= Y_LAND; $x++){
			echo "<div id='ycoord_element'>".$x."</div> ";
		}
		
		echo "</div></div>";

		
		/*
		for($x = 1; $x <= X_LAND; $x++){
			
			for($y = 1; $y <= Y_LAND; $y++){
				
				$scelta = rand(1, 5);
				
				if($scelta == 1){
					$tipo = rand(1, 4);
					mysqli_query(mysqli_connect("localhost", "root", "", "bladekingdom"),"INSERT INTO tbl_mappa VALUES(NULL, $x, $y, $tipo, '')");
				}
			}
			
		}
		*/
		
		
		/*
		
		$query = mysql_query("SELECT * FROM mappa WHERE Tipo = 1");
			
		while($data = mysql_fetch_assoc($query)){
			$livello = rand(1, 10);
			
			if($livello <= 3){
				$truppe = rand(20, 50);
			}elseif($livello <= 6){
				$truppe = rand(50, 100);
			}elseif($livello <= 10){
				$truppe = rand(100, 400);
			}
			
			mysql_query("UPDATE mappa SET Livello = ".$livello.", Truppe = ".$truppe." WHERE ID = ".$data['ID']);
			
		}
		*/
		$position   = array();
		$data_map 	= mysqli_query(mysqli_connect("localhost", "root", "", "bladekingdom"),"SELECT id, x, y, Tipo FROM " . $mappa->tbl_name);
				
		while($infoisland = mysqli_fetch_assoc($data_map)){
			$x = $infoisland["x"];
			$y = $infoisland["y"];
			$position[$x][$y] = $infoisland["Tipo"];
		}
	
	?>

	<div class="map-viewport">
		<div id="profile_land">
			<div id="message_backg">sdd
				<div id="close" onclick="close_window();" style="cursor:pointer"></div>
				<div id="text"></div>
			</div>
		</div>
		<div id="map">
			<?php		

				//Sub immagini per composizioni più ampie
				$imgalberi 	= array();
				$top_margin = 0;
				
				for($x = 1; $x <= X_LAND; $x++){
					
					$left_margin = 0;
					
					for($y = 1; $y <= Y_LAND; $y++){
						
						if(isset($position[$x][$y])){
							echo "<img src='./images/mappa/".$imgmap[$position[$x][$y]]."' style='width:".$immagine_larghezza."px;border:0px dotted #717171;position:absolute;top:".$top_margin."px;left:".$left_margin."px;' onclick='getdata(".$infoisland['ID'].");'></img>";
						}

						$left_margin += $immagine_larghezza;
						
					}
					
					$top_margin += $immagine_altezza;

					
				}

				mysqli_free_result($data_map);
			?>			
		</div>
	</div>
</div>
<script type="text/javascript">

	//Inizialize map
	$("#map").mapz();

	function getdata(id){

		/*
		var request = $.ajax({
			url: "../game/mappa/profile.php",
			cache: true,
			dataType: "html",
			type: "GET",
			data: {land : id}
		});
		 */
		/*request.done(function(msg) { */  //});
	}

	var elementtxt  = document.getElementById('text');
	var element	    = $('#message_backg');
		
	function close_window(){
		$(element).hide(300);
	}
	
	function open_window(message){
		elementtxt.innerHTML = message;
		$(element).show("fast");
	}

	
</script>
