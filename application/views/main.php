<?php include_once('modal/disponibles.php') ?> 
<div class="container-main">
	<div class="card-main1">
		<div class="card-body">	
			<div class="card-header" align="center">Información</div>					
				<div class="card1">					
			        <p align="left">&nbsp;Inm. Registrados <a href="javascript:void(0);"><span id="registrados" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$inmu ?></span></a> - &nbsp;Disponibles <a href="javascript:void(0);"><span id="disponibles" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$inmu_disp?></span></a></p>	

			        <h5 align="left">&nbsp;Alquileres/Comodatos Vigentes <a href="<?php echo site_url('Alquiler/alquiler')?>"><span style="background: red" class="badge badge-pill"><?=$alqui ?></span></a>
			         <a href="<?php echo site_url('Comodato/comodato')?>"><span style="background: red" class="badge badge-pill"><?=$comodato ?></span></a></h5>

					<h5 align="left">&nbsp;Departamentos <a href="javascript:void(0);"><span id="deptos" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$deptos ?></span></a> <span style="background: red" class="badge badge-pill"><?=$depto_alqui ?></span> <a href="javascript:void(0);"><span id="1" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$deptos_disp?></span></a></h5>

					<h5 align="left">&nbsp;Monoambientes <a href="javascript:void(0);"><span id="monos" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$mono ?></span></a>  <span style="background: red" class="badge badge-pill"><?=$mono_alqui ?></span> <a href="javascript:void(0);"><span id="4" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$mono_disp?></span></a></h5>						                    
			       
					<h5 align="left">&nbsp;Dúplex <a href="javascript:void(0);"><span id="duplex" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$duplex ?></span></a> <span style="background: red" class="badge badge-pill"><?=$duplex_alqui ?></span> <a href="javascript:void(0);"><span id="3" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$duplex_disp?></span></a></h5>		                    
			        	
					<h5 align="left">&nbsp;Casas <a href="javascript:void(0);"><span id="casas" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$casas ?></span></a> <span style="background: red" class="badge badge-pill"><?=$casas_alqui ?></span> <a href="javascript:void(0);"><span id="2" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$casas_disp?></span></a></h5>


					<h5 align="left">&nbsp;Cocheras <a href="javascript:void(0);"><span id="cocheras" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$cocheras ?></span></a> <span style="background: red" class="badge badge-pill"><?=$cocheras_alqui ?></span> <a href="javascript:void(0);"><span id="10" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$cocheras_disp?></span></a></h5>


					<h5 align="left">&nbsp;Locales Comerciales <a href="javascript:void(0);"><span id="locales" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$local ?></span></a> <span style="background: red" class="badge badge-pill"><?=$local_alqui ?></span> <a href="javascript:void(0);"><span id="5" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$local_disp?></span></a></h5>

					<h5 align="left">&nbsp;Oficinas <a href="javascript:void(0);"><span id="oficinas" onclick="disponibles(this.id)" style="background: #006688" class="badge badge-pill"><?=$oficinas ?></span></a> <span style="background: red" class="badge badge-pill"><?=$oficinas_alqui ?></span> <a href="javascript:void(0);"><span id="11" onclick="disponibles(this.id)"  style="background: green" class="badge badge-pill"><?=$oficinas_disp?></span></a></h5>				
				</div>

			        	<div class="card-header" align="center">Reservaciones</div>	        	 
			        		<div class="card2">
			        			
								<?php 
									if(isset($reservas)){
										
							    		foreach ($reservas as $idR => $reserva) {	?>
							          		<a href="<?php echo site_url('Inmueble/reservar')?>" ><? echo ' '.$reserva.'<br />' ?></a>
					       				 <? } ?>
					        	<? } ?> 
					        </div>	
					       	

					        	
		</div>

  	</div>

		 <div class="card-main2">
		 	<div class="card-body">
		    	
		    <div class="card-header" align="center">Alquileres/Comodatos por Vencer/Rescindir</div>
		    	<div class="card3">
		    		
		        	<?php
		        		if(isset($alquileresxvencer)){		        			  			
		        			 foreach ($alquileresxvencer as $inmueble => $locatario1 ) {
		        			 	echo $locatario1.', '.$inmueble.'<br/></br>'; ?> 
		        			 <?
		        			 }		        			 
		        		}
		        	?>
		        </div>	
		       
 				<div class="card-header"  align="center">Renovaciones</div>
 				 
 				 	<div class="card4">
 				 		
			        	<?php
			        		if(!empty($inqui_renuevan)){
			        			   			
			        			 foreach ($inqui_renuevan as $idI => $renuevan ) {
			        			 	echo $renuevan.'<br/></br>'; ?> 
			        			 <?
			        			 }			        			 
			        		}
			        	?>	
		        	</div>	
		    </div>
  		</div>

		<div class="card-main3">
			<div class="card-body">
		 	 	
					<div class="card-header" align="center">Liquidaciones Pendientes</div>
					<div class="card5">
						
					  <?php $sesion= $this->session->userdata('usuario');
					  		if($sesion[0]==1 or $sesion[0]==3){
					   ?>
							  
							  <? if(isset($propietario)){
							        
							        	asort($propietario);		  	
							    	 foreach ($propietario as $dni => $nombre){ ?>
							    	 		<a style="color:black" href="<?php echo site_url('Propietario/ver_alquileres/').$dni ?>"><? echo $nombre.'<br />'; ?> </a>
							  <?	  	
							   		} 						   		 
							   	}	
							   ?>				  

						<?php }else{ ?>

								  <? if(isset($propietario)){
								        
								        	asort($propietario);		  	
								    	 foreach ($propietario as $dni => $nombre){ ?>
								    	 		<a style="color:black"><? echo $nombre.'<br>'; ?> </a>
								  <?	  	
								   		}								   		
								   	}	
								   ?>

						<?php } ?>	  
					</div>					
				
						<div class="card-header" align="center">Inquilinos Morosos</div>
						<div class="card6">
							
						  <? if(isset($deudaalquiler)){						       
						        	asort($deudaalquiler);		  	
							   		if(isset($deudaalquiler)){						   						   		
									   		foreach ($deudaalquiler as $idC => $nombre) {?>

									   		<a style="color:black" href="<?php echo site_url('Pago/pagar/add/').$idC ?>" >

									   		<? echo '<b style="font-weight: normal;color: red">■ '.$nombre.'</b><br> ';?>
									   		</a>
									   		<?
											}					
									}						   		 
						   	}	
						   ?>
						</div>

					
			</div>			
  		</div>

		 <div class="card-main4" >
		 	<div class="card-body">

			    <div><div class="card-header">Reclamos de Inquilinos - <span style="background: #10af00" class="badge">En Proc.</span>&nbsp;<span style="background: #FF5100" class="badge">Pend.</span>&nbsp;&nbsp;<a href="javascript:void(0);"><img title="Nuevo Reclamo" id="nuevo" src="<?php echo site_url('/assets/images/nuevo.png') ?>" onclick="nuevoReclamo()"></a></div></div>
			    <div class="card7">
			    	
				    	<? if (isset($reclamos)){ 					    				
						    		 
								    		foreach ($reclamos as $idC => $valor) {	?>
								    			<?php $sesion= $this->session->userdata('usuario');
			  										if($sesion[0]==1 or $sesion[0]==2){ ?>				

								          			 	<? echo $valor.'<br><br>'  ?>

								          			<?php }else{ ?>

								          				<? echo $valor.'<br><br>'  ?>

								          			<?php } ?>
								          	<? } ?>

							<? } ?>	
					</div>							       
		    </div>
  		</div>
</div>

<script type="text/javascript">
	var baseurl = "<?php echo base_url(); ?>"
</script>

</body>

</html>