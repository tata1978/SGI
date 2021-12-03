		 <h5 align="left">&nbsp;Alquileres/Comodatos con deudas <span class="badge badge-pill"><?=$deuda ?></span></h5>

		 <div class="marquesiana" >		   
		   <?

		   		if(isset($deudaalquiler)){
				   		asort($deudaalquiler);				   		
				   		foreach ($deudaalquiler as $dni => $nombre) {							
				   			echo '<b> '.$nombre.',</b><br> ';
						}					
				}		    			
		   ?>
  		</div>

  		////////////RECLAMOS EN MAIN
  				 <div class="card-main4" >
		    <div class="card-header" align="center">Reclamos de Inquilinos</div>
		    <div class="card-body">

			    	<? if (isset($reclamos)){ 
					    		 echo '<div class="card5">';		
					    		 echo '<span style="background: #FF5100" class="badge badge-pill">&nbsp;</span> Pendiente <span style="background: #10af00" class="badge badge-pill">&nbsp;</span> En proceso<br>';
							    		foreach ($reclamos as $idC => $valor) {	?>
							    			<?php $sesion= $this->session->userdata('usuario');
		  										if($sesion[0]==1 or $sesion[0]==2){ ?>				

							          			 		<a style="color:black" href="<?php echo site_url('Reclamo/ver_reclamos/').$idC ?>" ><? echo '<abbr title="'.$descripcion[$idC].'"> '.$valor.'</abbr><br><br>'  ?></a>
							          			<?php }else{ ?> 

							          				<a style="color:black"><? echo '<abbr title="'.$descripcion[$idC].'"> '.$valor.'</abbr><br><br>'  ?></a>
							          			
							          			<?php } ?>		

							          	<? }   echo '</div>' ;?>

						<? } ?>						       
		    </div>
  		</div>