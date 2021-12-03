<nav class="navbar navbar-inverse navbar-fixed-top" >
      <div class="container">        
        <div class="navbar-header">   
        <a  class="navbar-brand" style="margin-top:-11px"><b style="font-size: 15px" ><img src="<?php echo base_url('assets/images/icon.png'); ?>" width="75" height="45">Taragüi Propiedades&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a>       
          
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
              <li>
                <a href="<?php echo site_url('Main')?>">Inicio</a>
              </li>
            <ul class="nav navbar-nav navbar">                               
            <li class="dropdown" >
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Inmuebles</a>              
              <ul class="dropdown-menu" aria-labelledby="themes">
                <li><a href="<?php echo site_url('Inmueble/inmueble')?>">Listado de Inmuebles</a></li>  

                  <?php  $sesion= $this->session->userdata('usuario');
                        if($sesion[0]==1){
                  ?> 
                          <li><a href="<?php echo site_url('Inmueble/reservar')?>">Reserva de Inmuebles</a></li>

                  <?php } ?>  

              </ul>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar">                               
            <li class="dropdown" >
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Alquileres</a>              
              <ul class="dropdown-menu" aria-labelledby="themes">
                <li><a href="<?php echo site_url('Alquiler/alquiler')?>">Listado de Alquileres</a></li>

                <li><a href="<?php echo site_url('Comodato/comodato')?>">Listado de Comodatos</a></li>


                 <?php  $sesion= $this->session->userdata('usuario');
                        if($sesion[0]==1 or $sesion[0]==3){
                  ?>  
                        <li><a href="<?php echo site_url('Alquiler/alquileres_finalizados')?>">Alquileres Finalizados</a></li>
                  <?php } ?>      


                 <?php  $sesion= $this->session->userdata('usuario');
                        if($sesion[0]==1 or $sesion[0]==2){
                  ?>  
                       <li><a href="<?php echo site_url('Alquiler/alquiler_reclamos')?>">Reclamos</a></li>
                  <?php }  ?>

                <!--<li><a href="<?php echo site_url('Inmueble/imprimir_requisitos_gral')?>">Imprimir Requisitos</a></li>-->
                    <li><a href="javascript:void(0);" data-toggle="modal" data-target="#requisitos" onclick="requisitos_alquilar()">Requisitos Alquiler</a></li>

              </ul>
            </li>
          </ul>         

          <ul class="nav navbar-nav navbar">                               
            <li class="dropdown" >              

               <?php  $sesion= $this->session->userdata('usuario');
                    if($sesion[0]==1){
               ?>     
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Datos Generales</a> 
                  <ul class="dropdown-menu" aria-labelledby="themes">
                    <li><a href="<?php echo site_url('Edificio/edificio')?>">Edificios</a></li>
                    <li><a href="<?php echo site_url('Barrio/barrio')?>">Barrios</a></li>
                    <li><a href="<?php echo site_url('Persona/persona')?>">Personas</a></li>
                    <li><a href="<?php echo site_url('Tecnico/tecnico')?>">Tecnicos</a></li>        
                  </ul>

              <?php } else{ ?>  
                <a class="dropdown-toggle" data-toggle="dropdown" id="themes">Datos Generales</a>     
              <?php } ?>  

            </li>
          </ul>
          
          <ul class="nav navbar-nav navbar"> 
              <li>
                <?php  $sesion= $this->session->userdata('usuario');
                 if($sesion[0]==1 or $sesion[0]==3 ){
                ?>  
                    <a href="<?php echo site_url('Persona/ver_propietarios')?>">Propietarios</a>
                <?php }else{ ?>
                     <a>Propietarios</a>
                <?php } ?>     

              </li>   
          </ul>  

          </ul> 
          <ul class="nav navbar-nav navbar-right">                               
            <li class="dropdown" >
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">
                <?
                 $sesion= $this->session->userdata('usuario');
                 echo $sesion[2].' - '.$sesion[3];
                  ?>
                <span class="caret"></span></a>              
              <ul class="dropdown-menu" aria-labelledby="themes">

                <?php if($sesion[0]==1){ ?>
                      <li><a href="<?php echo site_url('Usuario/usuario')?>">Usuarios</a></li>
                 <?php } ?>

                <li><a href="<?php echo site_url('login/logout')?>">Salir</a></li>               
              </ul>
            </li>
        </ul>

          <ul class="nav navbar-nav navbar">                               
            <li class="dropdown" >
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Reportes</a>              
              <ul class="dropdown-menu" aria-labelledby="themes">
                <li><a href="<?php echo site_url('Alquiler/reclamos_reportes')?>">Reclamos</a></li>
                <li><a href="<?php echo site_url('Alquiler/deudores_reportes')?>">Deudores</a></li>
                <li><a href="<?php echo site_url('Reporte/reporte_caja')?>">Caja</a></li>
              </ul>
            </li>
          </ul> 
        
      </div>
    </div>
  </nav>
