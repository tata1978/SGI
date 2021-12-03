<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	-->

	<style type="text/css">
	
	</style>	
</head>

<div id="container">
	<h3>Elija una opción de búsqueda</h3>
		<div id="body">

	<table border="0">
		<tr>
			<td>		
				<form class="form" method="post">					
					<label>Caja Diaria:</label>
					<input type="date" id="buscar_diario" name="buscar_diario" required>
					<input type="submit" value="Buscar">			
				</form>
			</td>
			<td>
				<form class="form" method="post">					 
					&nbsp&nbsp&nbsp<label>Caja Mensual:</label>
					  <select name="mes" required>
						  	<option value=""></option>
						    <option value="01">Enero</option>
						    <option value="02">Febrero</option>
						    <option value="03">Marzo</option>
						    <option value="04">Abril</option>
						    <option value="05">Mayo</option>
						    <option value="06">Junio</option>
						    <option value="07">Julio</option>
						    <option value="08">Agosto</option>					    
						    <option value="09">Septiembre</option>
						    <option value="10">Octubre</option>
						    <option value="11">Noviembre</option>
						    <option value="12">Diciembre</option>					    
					  </select>
					  <select name="ano" required>
						  	<option value=""></option>	
						<?php for ($i=2018; $i < 2025 ; $i++) { ?>
							<option value=<?php echo $i ?>><?php echo $i  ?></option>						
						<?php }  ?> 
					</select>
					<input type="submit" value="Buscar">
				</form>
			</td>

			<!--<td>							
				<form class="form" method="post">					
					&nbsp&nbsp&nbsp<label>Edificio:</label>
					  <select name="edificio" required>
					  	<option value=""></option>
					  	<?php
					  		asort($edificio);
	   			    		foreach ($edificio as $idE => $nombre_edificio){
							   echo '<option values="'.$idE.'">'.$nombre_edificio.'</option>';
	   			    		}
						?>					 	
					 </select>	
					 <input type="submit" value="Buscar">	
				</form>
			</td>-->
		</tr>
	</table>
	<?php echo "<b style='color:red'>Filas en rojo son recibos anulados.</b>"; ?>
		<br>
		<?php

		if(!empty($_POST)){					
			if($caja_diaria<>"" or $caja_mensual<>""){
				if($caja_diaria<>""){
					$reporte=$caja_diaria[0]; //locatario 
					$reporte_inmueble=$caja_diaria[1];  //inmueble
					$reporte_fecha=$caja_diaria[2]; //fecha 
					$reporte_total=$caja_diaria[3]; //total_pagar
					$cantidad_registros=$caja_diaria[4];
					$acumulado=$caja_diaria[5];
					$periodo=$caja_diaria[6];
					$reporte_usuario=$caja_diaria[7]; //total_pagar
					$reporte_edificio=$caja_diaria[8]; //total_pagar
					$dia_periodo="Día: ";	
					$reporte_anulado=$caja_diaria[9]; //anulado
				}elseif ($caja_mensual<>"") {
					$reporte=$caja_mensual[0]; //locatario 
					$reporte_inmueble=$caja_mensual[1];  //inmueble
					$reporte_fecha=$caja_mensual[2]; //fecha 
					$reporte_total=$caja_mensual[3]; //total_pagar
					$cantidad_registros=$caja_mensual[4];
					$acumulado=$caja_mensual[5];
					$periodo=$caja_mensual[6];
					$reporte_usuario=$caja_mensual[7]; //total_pagar
					$reporte_edificio=$caja_mensual[8]; //total_pagar
					$dia_periodo="Período: ";	
					$reporte_anulado=$caja_mensual[9]; //anulado				
				}	
			?>
			<table id="filtro" border="1" width="100%">
				<tr>
					<td class="dia">
						<b><?php echo $dia_periodo ?></b><?php echo '&nbsp<b style=color:red;font-size:16px>'.'<span id=dia>'.$periodo.'</span></b>' ?>
					</td>
					<td class="registros">	
						<b>Cantidad de Registros:</b><?php echo '&nbsp<b style=color:red;font-size:17px>'.$cantidad_registros.'</b>' ?>
					</td>
					<td class="sumatoria">	
						<b>Sumatoria:</b><?php echo '&nbsp<b style=color:red;font-size:17px>'.$acumulado.'</b>' ?>	
					</td>
					<td class="busqueda">
						<b>Búsqueda:</b><input type="text"  id="search" placeholder="Escribe para buscar..." style="	background: #F8FF5C"/>
					</td>
					<td class="imprimir_diario">
						<input type="button" id="excel" name="excel" value="Exportar Excel"/>
						&nbsp&nbsp&nbsp		
				 		<input type="button" id="imprimirdiario" name="imprimir" value="Imprimir Reporte"/>&nbsp 
				 	</td>
				</tr>
			</table>
		<table id="caja" class="caja_diaria_head" border="0" width="100%">
				<thead>
					<tr>
						<th class="reporte_numeral">#</th>
						<th class="reporte_nro_pago">Nro Pago</th>
						<th class="reporte_fecha">Fecha</th>
						<th class="reporte_locatario">Locatario</th>
						<th class="reporte_inmueble">Inmueble</th>
						<th class="reporte_edificio">Edificio</th>					
						<th class="reporte_monto">Monto</th>
						<th class="reporte_usuario">Usuario</th>
					</tr>
				</thead>
		</table>
		<div  id="div1">

		<table id="caja" class="caja_diaria" width="100%">		
			<tbody>				
				<?php asort($reporte);
					$i=0; 
				?>
				<?php foreach ($reporte as $idpago => $locatario): ?>
					<?php if($reporte_anulado[$idpago]==1){ ?>
						<tr style="color:red">
					 <?php }else{ ?>
					 	<tr>
					 <?php } ?>					
						<td class="reporte_numeral"><?php echo $i=$i+1  ?></td>						
						<td class="reporte_nro_pago"><?php echo '<b>'.$idpago.'</b>' ?></td>
						<td class="reporte_fecha"><?php echo $reporte_fecha[$idpago]   ?></td>
						<td class="reporte_locatario"><?php echo $locatario  ?></td>
						<td class="reporte_inmueble"><?php echo $reporte_inmueble[$idpago] ?></td>
						<td class="reporte_edificio"><?php echo $reporte_edificio[$idpago] ?></td>
						<td align="right" class="reporte_monto"><?php echo '<b>'.$reporte_total[$idpago].'</b>' ?></td>
						<td class="reporte_usuario"><?php echo $reporte_usuario[$idpago] ?></td>
					</tr>					
				<?php endforeach ?>
			<?php }else{
				echo "<b style=color:red>Sin Resultados</b>";
				} 
			} ?>
			</tbody>
		</table>
	</div>
		

	</div>

	<!--<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>-->
</div>


