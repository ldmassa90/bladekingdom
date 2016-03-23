<?php
require("./system/ClassMessaggi.php");

$messaggi = new Messaggi();

$tb_msg_rules = $messaggi->rules();
$text_labels  = $messaggi->attributeLabels();

//Questo id riguarda la lettura di un eventuale messaggio
$id_email = $_GET["id"];
?>
<a href="?p=<?=$page?>&sub=<?=$messaggi->id_sotto_voci["ricevuti"]?>">
<?php
echo $text_labels["ricevuti"];
?>
</a>
<a href="?p=<?=$page?>&sub=<?=$messaggi->id_sotto_voci["inviati"]?>">
<?php
echo $text_labels["inviati"];		
?>
</a>
<a href="?p=<?=$page?>&sub=<?=$messaggi->id_sotto_voci["salvati"]?>">
<?php
echo $text_labels["salvati"];		
?>
</a>
<a href="?p=<?=$page?>&sub=<?=$messaggi->id_sotto_voci["nuovo"]?>">
<?php
echo $text_labels["nuovo"];			
?>
</a>
<a href="?p=<?=$page?>&sub=<?=$messaggi->id_sotto_voci["impostazioni"]?>">
<?php
echo $text_labels["impostazioni"];	
?>
</a>

<?php
	
	if(!isset($id_email)){
	
		switch($sub_page){
			
			case $messaggi->id_sotto_voci["ricevuti"]: //Ricevuti
				require("messaggi/ricevuti.php");
			break;
			case $messaggi->id_sotto_voci["inviati"]: //Inviati
				require("messaggi/inviati.php");
			break;		
			case $messaggi->id_sotto_voci["salvati"]: //Salvati
				require("messaggi/salvati.php");
			break;		
			case $messaggi->id_sotto_voci["nuovo"]: //Nuovo messaggio
				require("messaggi/scrivi.php");
			break;
			case $messaggi->id_sotto_voci["impostazioni"]: //Impostazioni
				require("messaggi/impostazioni.php");
			break;
		}
		
	}else{
		require("messaggi/leggi.php");
	}
?>