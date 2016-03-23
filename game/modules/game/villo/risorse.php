<?php
//Aggiorno la quantità  di risorse presenti
$obj_resources->refresh_resources($_SESSION["id_user"], $villo_req);
//Aggiorno le strutture ultimate
$costruzioni_code->upgrade_building($villo_req);

$cl_nm_oro		= $obj_resources->columns_name[1];
$cl_nm_legno	= $obj_resources->columns_name[2];
$cl_nm_ferro	= $obj_resources->columns_name[3];
$cl_nm_cibo		= $obj_resources->columns_name[4];
$cl_nm_oro_ora	= $obj_resources->columns_name[8];
$cl_nm_pop_ora	= $obj_resources->columns_name[9];

$query_quantita_tst = "SELECT 	".$cl_nm_oro.", 
							".$cl_nm_legno.", 
							".$cl_nm_ferro.", 
							".$cl_nm_cibo.", 
							".$cl_nm_oro_ora.", 
							".$cl_nm_pop_ora." 
							FROM " . $obj_resources->tbl_name . " WHERE id = " . $villo_req;

$query_quantita_risorse  = $obj_resources->query($query_quantita_tst);
$info_quantita_risorse	 = mysqli_fetch_assoc($query_quantita_risorse); 

//Livelli delle singole strutture di produzione

$cl_nm_miniera		= $strutture->columns_name[1];
$cl_nm_granaio		= $strutture->columns_name[2];
$cl_nm_taglialegna	= $strutture->columns_name[3];
$cl_nm_deposito		= $strutture->columns_name[8];
$cl_nm_caserma		= $strutture->columns_name[5];

$query_strutture_livelli_tst = 	"SELECT ".$cl_nm_miniera.", 
								".$cl_nm_granaio.", 
								".$cl_nm_taglialegna.",
								".$cl_nm_deposito.",
								".$cl_nm_caserma."
						 		FROM " . $strutture->tbl_name . " WHERE id = " . $villo_req;

$query_strutture_livelli  	= $strutture->query($query_strutture_livelli_tst);
$level  				 	= mysqli_fetch_assoc($query_strutture_livelli);

$oro_ad_ora   = $info_quantita_risorse[$cl_nm_oro_ora];
$ferro_ad_ora = $produzioni[$level[$cl_nm_miniera] - 1];
$cibo_ad_ora  = $produzioni[$level[$cl_nm_granaio] - 1];
$legno_ad_ora = $produzioni[$level[$cl_nm_taglialegna] - 1];

$massima_capacita = $max_giacenza[$level[$cl_nm_deposito]];

$oro 		= $info_quantita_risorse[$cl_nm_oro];
$legno		= $info_quantita_risorse[$cl_nm_legno];
$ferro  	= $info_quantita_risorse[$cl_nm_ferro];
$cibo   	= $info_quantita_risorse[$cl_nm_cibo];
$abitanti 	= 1;
$truppe 	= 1;

//Dimensioni delle progress bar delle risorse
$width = 60;
$height = 50;
?>
<div id="risorse">
<table style="width:450px;text-align:center;font-size:11px;" align="right">
	<tr>
		<td>
			<input type="text" id="qt_1" value="<?=floor($oro)?>" data-thickness=".2" data-angleOffset=-125 data-angleArc=250 data-skin="tron" class="dial" data-width="<?=$width?>" data-height="<?=$height?>" data-displayInput=false data-fgColor="#c5a886">
		</td>
		<td>
			<input type="text" id="qt_2" value="<?=floor($legno)?>" data-thickness=".2" data-angleOffset=-125 data-angleArc=250 data-skin="tron" class="dial" data-width="<?=$width?>" data-height="<?=$height?>" data-displayInput=false data-fgColor="#c5a886">
		</td>
		<td>
			<input type="text" id="qt_3" value="<?=floor($ferro)?>" data-thickness=".2" data-angleOffset=-125 data-angleArc=250 data-skin="tron" class="dial" data-width="<?=$width?>" data-height="<?=$height?>" data-displayInput=false data-fgColor="#c5a886">
		</td>
		<td>
			<input type="text" id="qt_4" value="<?=floor($cibo)?>" data-thickness=".2" data-angleOffset=-125 data-angleArc=250 data-skin="tron" class="dial" data-width="<?=$width?>" data-height="<?=$height?>" data-displayInput=false data-fgColor="#c5a886">
		</td>
		<td>
			<input type="text" id="qt_5" value="<?=floor($abitanti)?>" data-thickness=".2" data-angleOffset=-125 data-angleArc=250 data-skin="tron" class="dial" data-width="<?=$width?>" data-height="<?=$height?>" data-displayInput=false data-fgColor="#c5a886">
		</td>
		<?php
		if($level[$cl_nm_caserma]){
		?>
		<td>
			<input type="text" id="qt_6" value="<?=floor($truppe)?>" data-thickness=".2" data-angleOffset=-125 data-angleArc=250 data-skin="tron" class="dial" data-width="<?=$width?>" data-height="<?=$height?>" data-displayInput=false data-fgColor="#c5a886">
		</td>
		<?php 
		}
		?>
	</tr>
	<tr>
		<td id="vqt_1">
			<?php
			echo floor($oro) . "/" . $massima_capacita; 
			?>
		</td>
		<td id="vqt_2">
			<?php
			echo floor($legno) . "/" . $massima_capacita; 
			?>
		</td>
		<td id="vqt_3">
			<?php
			echo floor($ferro) . "/" . $massima_capacita; 
			?>
		</td>
		<td id="vqt_4">
			<?php
			echo floor($cibo) . "/" . $massima_capacita; 
			?>
		</td>	
		<td id="vqt_5">
			<?=floor($abitanti)?>
		</td>	
		<?php
		if($level[$cl_nm_caserma]){
		?>
			<td id="vqt_6">
				<?=floor($truppe)?>
			</td>	
		<?php 				
		}
		?>
	</tr>

</table>
</div>
<script>
        $(".dial").knob({
        	'readOnly'  : true
        });
        
        $('.dial').trigger(
                'configure',
                {
                    "min":1,
                    "max":<?=$massima_capacita?>
                }
            );
</script>
