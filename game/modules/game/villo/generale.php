<?php 
//require("/risorse.php");

$array_immagini	 = array();
$array_cordinate = array();
	
/* Inserimento dinamico immagini */
	

for($x = 1; $x < count($strutture->columns_name); $x++){

	$nome_struttura = $strutture->columns_name[$x];
	$info 			= $building[$nome_struttura];
		
	$forma_cordinate = $info["img_no_coord"];

	if(is_array($info)){

		$immagine   		= $info["img"];
		$margine_sinistro  	= $info["style_x"];
		$margine_alto   	= $info["style_y"];
		$forma_cordinate 	= $info["img_coord"];
		$link 				= $info["link"];
			
		if(!empty($immagine)){
			$array_immagini[] = "<img src='./images/villo/".$immagine."' style='z-index:1;position:absolute;left:".$margine_sinistro.";top:".$margine_alto."' />";
		}
	}
		
	$array_cordinate[] = '<area shape="poly" coords="'.$forma_cordinate.'" alt="'.$nome_struttura.'" href="'.$link.'&v='.$villo_req.'" />';
}

?>
	
<div id="villaggio">
	
	<img src="./images/villo/villo.jpg" style="position:absolute;margin-left:21px;margin-top:19px;" id="villo" border="0" />
	<img src="./images/villo/empty.png" style="position:absolute;z-index:2;"  usemap="#mappatura" />
	<?php
		foreach($array_immagini as $struttura_img){
	//		echo $struttura_img;
		}
	?>
</div>

<map name="mappatura">
	<area shape="poly" coords="192,96,221,100,225,94,243,96,251,81,253,80,252,76,249,74,249,67,250,67,250,62,248,52,231,49,231,42,227,43,226,48,191,45,191,38,185,38,185,45,173,44,172,30,158,24,147,34,147,35,148,35,149,87,172,89,173,86,190,87,192,89,192,95" alt="Municipio" href="./home.php?p=2&sub=<?=$villo->id_sotto_voci["municipio"]?>&v=<?=$villo_req?>" />
	<?php
	foreach($array_cordinate as $struttura_shape){
		echo $struttura_shape;
	}
	?>
</map>

<div id="coda_di_costruzione">
<?php 
require("coda_costruzioni.php"); 
?>
</div>
