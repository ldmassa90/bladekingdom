<table>
<tr>
<th colspan="3"><?=$risorse->message["statistics"];?></th>
	</tr>
	<tr>
		<td></td>
		<td>attualmente</td>
		<td>al prossimo livello</td>
	</tr>
	<tr>
		<td>1/h</td>
		<td><?=$produzione_oraria;?></td>
		<td><?=$produzione_oraria_succ;?></td>
	</tr>
	<tr>
		<td>6/h</td>
		<td><?=$produzione_oraria * 6;?></td>
		<td><?=$produzione_oraria_succ * 6;?></td>
	</tr>
	<tr>
		<td>12/h</td>
		<td><?=$produzione_oraria * 12;?></td>
		<td><?=$produzione_oraria_succ * 12;?></td>
	</tr>
	<tr>
		<td>24/h</td>
		<td><?=$produzione_oraria * 24;?></td>
		<td><?=$produzione_oraria_succ * 24;?></td>
	</tr>
</table>