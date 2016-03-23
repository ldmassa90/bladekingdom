<?php 
//Ottengo i costi relativi al livello successivo
$costo_oro 		= $obj_resources->get_cost($object, $act_level, $obj_resources->columns_name[1]);	//Oro
$costo_legno 	= $obj_resources->get_cost($object, $act_level, $obj_resources->columns_name[2]);	//Legna
$costo_ferro 	= $obj_resources->get_cost($object, $act_level, $obj_resources->columns_name[3]);	//Ferro
$costo_cibo 	= $obj_resources->get_cost($object, $act_level, $obj_resources->columns_name[4]);	//Grano
$costo_tempo	= $obj_resources->get_cost($object, $act_level, "tempo");

$free_workers	= $costruzioni_code->get_available_workers($villo_id);

if($free_workers > $costruzioni_code::MAX_WORKERS){
	$disabilita_ampliamento = true;
	$free_workers 			= -1;
}
?>
<table>
	<tr>
		<th colspan="3">
		<?php 
		
		//Messaggio di presentazione
		$build_message = $obj_resources->message["requirement"][1];
		
		if($act_level == -1){
			$build_message = $obj_resources->message["requirement"][0];
		}
		
		echo $build_message;
		
		//Solo per le 3 strutture di produzione il livello 0 significa che è gia
		//costruito e in produzione
		
		if($act_level == 0){
			echo "2";
		}else{
			
			if($act_level != -1){
				echo $act_level + 1;
			}
		}
		?>
		</th>
	</tr>
	<tr>
		<th><?=$risorse_nomi[$cl_nm_oro]?></th>
		<td><?=$costo_oro?></td>
		<td id="countdown">
		<?php 
		if($costo_oro > $oro){
			
			$disabilita_ampliamento = true;
			$tempo_di_attesa 		= $strutture->message["amplia_magazzino"];
			
			if($costo_oro <= $massima_capacita){
				$tempo_di_attesa = $obj_resources->time_to($oro_ad_ora, $oro, $costo_oro);
			}
				
			echo $tempo_di_attesa;

		}elseif($free_workers == -1){
			echo $strutture->message["full_queue"];
		}
		?>
		</td>
	</tr>
	<tr>
		<th><?=$risorse_nomi[$cl_nm_legno]?></th>
		<td><?=$costo_legno?></td>
		<td id="countdown">
		<?php 
		if($costo_legno > $legno){
			
			$disabilita_ampliamento = true;
			$tempo_di_attesa 		= $strutture->message["amplia_magazzino"];

			if($costo_legno <= $massima_capacita){
				$tempo_di_attesa = $obj_resources->time_to($legno_ad_ora, $legno, $costo_legno);
			}

			echo $tempo_di_attesa;
		}elseif($free_workers == -1){
			echo $strutture->message["full_queue"];
		}
		?>
		</td>
	</tr>
	<tr>
		<th><?=$risorse_nomi[$cl_nm_ferro]?></th>
		<td><?=$costo_ferro?></td>
		<td id="countdown">
		<?php 
		if($costo_ferro > $ferro){
			
			$disabilita_ampliamento = true;
			$tempo_di_attesa 		= $strutture->message["amplia_magazzino"];
			
			if($costo_ferro <= $massima_capacita){
				$tempo_di_attesa = $obj_resources->time_to($ferro_ad_ora, $ferro, $costo_ferro);
			}
			
			echo $tempo_di_attesa;
		}elseif($free_workers == -1){
			echo $strutture->message["full_queue"];
		}
		?>
		</td>
	</tr>
	<tr>
		<th><?=$risorse_nomi[$cl_nm_cibo]?></th>
		<td><?=$costo_cibo?></td>
		<td id="countdown">
		<?php 
		if($costo_cibo > $cibo){
			
			$disabilita_ampliamento = true;
			$tempo_di_attesa 		= $strutture->message["amplia_magazzino"];
				
			if($costo_ferro <= $massima_capacita){
				$tempo_di_attesa = $obj_resources->time_to($cibo_ad_ora, $cibo, $costo_cibo);
			}
			
			echo $tempo_di_attesa;
		}elseif($free_workers == -1){
			echo $strutture->message["full_queue"];
		}
		?>
		</td>
	</tr>
	<tr>
		<th><?=$risorse_nomi["tempo"]?></th>
		<td><?=gmdate("H:i:s", $costo_tempo);?></td>
		<td></td>
	</tr>
</table>
<form action="<?=$obj_page->createLink($page, array($villo->id_sotto_voci["ampliamento"], "v" => $villo_req), "")?>" method="POST">
<?php
echo $gui->input("hidden", $obj_resources, array("value" => $id_struttura, "name" => "strct"));
echo $gui->input("submit", $obj_resources, array("value" => "Ordina l'ampliamento", "name" => "submit", "disabled" => $disabilita_ampliamento));
?>
</form>
