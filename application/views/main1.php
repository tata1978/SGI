<div class="container-main">
		<!--<div class="elemento elemento1">Alquileres</div>			
		<div class="elemento elemento2">Inmuebles</div>
		<div class="elemento elemento3">Reclamos</div>
		<div class="elemento elemento4">Pendientes</div>-->
		 <div class="card text-white bg-primary">
		    <div class="card-header" align="center"><p style="font-size: 16px">Alquileres/Comodatos</p></div>
		    <div class="card-body">
		        <h5 align="left">&nbsp;Alquileres Vigentes:&nbsp;<b style="font-size: 16px;color:yellow"><?=$alqui ?></b></h5>
		        <h5 align="left">&nbsp;Comodatos Vigentes:&nbsp;<b style="font-size: 16px;color:yellow"><?=$comodato ?></b></h5>
		        <h5 align="left">&nbsp;Alquileres/Comodatos con deudas:&nbsp;<b style="font-size: 16px;color: #ff7800"><?=$deuda ?></b></h5>
		        <h5 align="left">&nbsp;Alquileres/Comodatos próximos a vencer <b>:</b></h5>		       
		        	<?php
		        		if(isset($alquileresxvencer)){
		        			echo '<div class="card1" style="border:none; width :auto;height : 262px; overflow : auto;  text-align:left;">';   			
		        			 foreach ($alquileresxvencer as $inmueble => $locatario1 ) {
		        			 	echo '■ '.$locatario1.', '.$inmueble.'<br />'; ?> 
		        			 <?
		        			 }
		        			 echo '</div>';
		        		}
		        	?>		       		          
		    </div>
  		</div>

		 <div class="card text-white bg-primary">
		    <div class="card-header"  align="center"><p style="font-size: 16px">Inmuebles</p></div>
		    <div class="card-body">
		        <h5 align="left">&nbsp;Inm. Registrados:&nbsp;<b style="font-size: 16px"><a href="<?php echo site_url('Inmueble/inmueble')?>" style="color:yellow"><?=$inmu ?></a></b> - &nbsp;Disponibles:&nbsp;<b style="font-size: 16px"><a style="color: #00ff36" href="<?php echo site_url('Inmueble/inmueble')?>"><?=$inmu_disp?></a></b></h5>		 
				<h5 align="left">&nbsp;Departamentos:&nbsp;<b style="font-size: 16px;color:yellow"><?=$deptos ?> </b> - Alquilados:&nbsp;<b style="font-size: 16px;color:#ff7800"><?=$depto_alqui ?></b></h5>

				<h5 align="left">&nbsp;Monoambientes:&nbsp;<b style="font-size: 16px;color:yellow"><?=$mono ?> </b> - Alquilados:&nbsp;<b style="font-size: 16px;color:#ff7800"><?=$mono_alqui ?></b></h5>						                    
		       
				<h5 align="left">&nbsp;Dúplex:&nbsp;<b style="font-size: 16px;color:yellow"><?=$duplex ?></b> - Alquilados:&nbsp;<b style="font-size: 16px;color:#ff7800"><?=$duplex_alqui ?></b></h5>		                    
		        	
				<h5 align="left">&nbsp;Casas:&nbsp;<b style="font-size: 16px;color:yellow"><?=$casas ?></b> - Alquilados:&nbsp;<b style="font-size: 16px;color:#ff7800"><?=$casas_alqui ?></b></h5>
				<h5 align="left">&nbsp;Reservas:&nbsp;<b style="font-size: 16px"><a style="color: #00ff36 " href=""><?=$cant_reservas?></a></b></h5>


							<?php 
								if(isset($reservas)){
									echo '<div class="card2" style="border:none;width :auto;height : 186px; overflow : auto; text-align:left;">';
						    		foreach ($reservas as $idR => $reserva) {	?>
						          		<a style="color:yellow" href="<?php echo site_url('Inmueble/reservar')?>" ><? echo '■ '.$reserva.'<br />' ?></a>
				       				 <? } echo '</div>';?>
				        	<? } ?> 

		    </div>
  		</div>

		<div class="card text-white bg-primary">
		  <div class="card-header" align="center"><p style="font-size: 16px">Propietarios: Liquidac. Pendientes</p></div>

		  <?php $sesion= $this->session->userdata('usuario');
		  		if($sesion[0]==1 or $sesion[0]==3){
		   ?>

				  <div class="card-body">
				  <? if(isset($propietario)){
				        echo '<div class="card3" style="border:none;width :auto; height : 366px; overflow : auto; text-align:left;">';
				        	asort($propietario);		  	
				    	 foreach ($propietario as $dni => $nombre){ ?>
				    	 		<a style="color:yellow" href="<?php echo site_url('Propietario/ver_alquileres/').$dni ?>"><? echo '■ '.$nombre.'<br />'; ?> </a>
				  <?	  	
				   		} 
				   		 echo '</div>';
				   	}	
				   ?>
				  </div>

			<?php }else{ ?>

					<div class="card-body">
					  <? if(isset($propietario)){
					        echo '<div class="card3" style="border:none;width :auto; height : 366px; overflow : auto; text-align:left;">';
					        	asort($propietario);		  	
					    	 foreach ($propietario as $dni => $nombre){ ?>
					    	 		<a style="color:yellow"><? echo '■ '.$nombre.'<br />'; ?> </a>
					  <?	  	
					   		} 
					   		 echo '</div>';
					   	}	
					   ?>
					  </div>
			<?php } ?>	  



  		</div>

		 <div class="card text-white bg-primary" >
		    <div class="card-header" align="center"><p style="font-size: 16px">Reclamos de Inquilinos</p></div>
		    <div class="card-body">

			    	<? if (isset($reclamos)){ 
					    		 echo '<div class="card4" style="border:none;width :auto;height : 360px; overflow : auto; text-align:left;">';		

							    		foreach ($reclamos as $idC => $valor) {	?>
							    			<?php $sesion= $this->session->userdata('usuario');
		  										if($sesion[0]==1 or $sesion[0]==2){ ?>				

							          			 		<a style="color:yellow" href="<?php echo site_url('Reclamo/ver_reclamos/').$idC ?>" ><? echo '■<abbr title="'.$descripcion[$idC].'"> '.$valor.'</abbr><br />'  ?></a>
							          			<?php }else{ ?> 

							          				<a style="color:yellow"><? echo '■<abbr title="'.$descripcion[$idC].'"> '.$valor.'</abbr><br />'  ?></a>
							          			
							          			<?php } ?>		

							          	<? }   echo '</div>' ;?>

						<? } ?>						       
		    </div>
  		</div>
</div>

</body>
</html>