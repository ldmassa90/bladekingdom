<br/><br/><br/>

<a href="?p=<?=$page?>&sub=1">Giocatori</a>
<a href="?p=<?=$page?>&sub=2">Alleanze</a>

<?php
	
	switch($sub_page){
		
		case 1: //Giocatori
			require("/classifica/ricevuti.php");
		break;
		case 2: //Alleanze
			require("/classifica/inviati.php");
		break;
	}
?>