<table>
	<tr>
		<td>
			<a href="<?=$obj_page->createLink($page, array($sub_page, $obj_market->id_sotto_voci["invia"], "v" => $villo_req), "")?>">
			<?=$obj_market->field_text["invia_risorse"]?>
			</a>
		</td>
		<td>
			<a href="<?=$obj_page->createLink($page, array($sub_page, $obj_market->id_sotto_voci["offri"], "v" => $villo_req), "")?>">
			<?=$obj_market->field_text["offri_risorse"];?>
			</a>
		</td>
		<td>
			<a href="<?=$obj_page->createLink($page, array($sub_page, $obj_market->id_sotto_voci["ricevi"], "v" => $villo_req), "")?>">
			<?=$obj_market->field_text["offerte_sul_mercato"];?>
			</a>
		</td>
		<td>
			<a href="<?=$obj_page->createLink($page, array($sub_page, $obj_market->id_sotto_voci["mercato_nero"], "v" => $villo_req), "")?>">
			<?=$obj_market->field_text["mercato_nero"];?>
			</a>
		</td>
	</tr>
</table>
<?php 

//Inclusione dei sotto-moduli

$sub2 = $_GET["sub_2"];

if(is_numeric($sub2)){

	switch($sub2){
		
		case $obj_market->id_sotto_voci["invia"]:
			require("/send.php");
		break;
		case $obj_market->id_sotto_voci["offri"]:
			require("/offer.php");
		break;
		case $obj_market->id_sotto_voci["ricevi"]:
			require("/market_offers.php");
		break;	
		case $obj_market->id_sotto_voci["mercato_nero"]:
			require("/black_market.php");
		break;
		default:
			echo $villo->message["section_no_found"];
		break;
	}
	
}



?>