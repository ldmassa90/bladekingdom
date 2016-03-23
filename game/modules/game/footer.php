<script type="text/javascript">

$(document).ready(function(){
	<?php
	//Queste variabili servono per l'aggiornamento dinamico in javascript. Valori in milli secondi
	$refresh_oro	 = 3600 / $oro_ad_ora   * 1000;
	$refresh_miniera = 3600 / $ferro_ad_ora * 1000;
	$refresh_granaio = 3600 / $cibo_ad_ora  * 1000;
	$refresh_foresta = 3600 / $legno_ad_ora * 1000;

	$refresh_oro	 = ceil($refresh_oro);
	$refresh_miniera = ceil($refresh_miniera);
	$refresh_granaio = ceil($refresh_granaio);
	$refresh_foresta = ceil($refresh_foresta);

	//Aggiorno le risorse solo se il magazzino consente la giacenza

	if($oro <= $massima_capacita){
		echo 'window.setInterval(function(){refresh_resource("qt_1", '.$massima_capacita.')}, '.$refresh_oro.');';
	}

	if($legno <= $massima_capacita){
		echo 'window.setInterval(function(){refresh_resource("qt_2", '.$massima_capacita.')}, '.$refresh_foresta.');';
	}

	if($ferro <= $massima_capacita){
		echo 'window.setInterval(function(){refresh_resource("qt_3", '.$massima_capacita.')}, '.$refresh_miniera.');';
	}

	if($cibo <= $massima_capacita){
		echo 'window.setInterval(function(){refresh_resource("qt_4", '.$massima_capacita.')}, '.$refresh_granaio.');';
	}
	?>
});
</script>