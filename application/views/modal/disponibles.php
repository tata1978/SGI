<style type="text/css">
.form-control{
  background-color: #e9f5ff;  
}  
#reclamos{
  position: absolute;
   top: -20px;
}
.modal-body1{
  padding: 5px;
  text-align: justify;
}


.modal-body{  
 height: 450px;
 
  overflow: auto;
  padding: 5px;
}/*para el scroll del modal*/

#modal-disponibles{
  width: 90% !important;
}

#loading-image {
  display:block;
  margin:auto;
}

</style>

<!-- Modal Ver Inmueble -->
<div class="modal fade" id="disponibles" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog" id="modal-disponibles" role="document">
    <div class="modal-content">
      <div style="background: #006688; height: 45px;display: flex;align-items: center;">        
          &nbsp;<b class="modal-title" style="color:white" id="titulo"></b>&nbsp;
          <input type="text"  id="buscar" placeholder="Escribe para buscar..." autocomplete="off" autofocus>
          <p><span class="badge" style="background: #FF5100">Alquilados</span><span class="badge" style="background: #10af00">Disponibles</span></p>
      </div>
      <div class="modal-body" id="contenidos">

          <table id="tablaDisponibles"  class="table table-hover" width="100%">
            <thead>
              <tr style="background: #2c3e50;color:white" >
                <th>ID</th>
                <th>Dirección</th>
                <th>Edificio</th>
                <th>Barrio</th>
                <th>Cant.Dor</th>
                <th>Cochera</th>
                <th>Valor</th>
                <th>Operación</th>
                <th>Propietario</th>
                <th></th>
                <th></th>                
              </tr>
            </thead>
            <tbody class="tbody">
        
            </tbody>            
          </table>
          <div>
                <img id="loading-image" src="<?php echo site_url('assets/images/loading.gif')?>" style="display:none;"/> 
          </div> 
      </div>
      <div  style="height: 20px; vertical-align: text-bottom;">
        <b id="cantidad" style="color:red;text-align: left;"></b>
      </div>
    </div>
  </div>
</div>

<!-- Modal Ver mas: caracteristicas de inmuebles  class="modal modal-child"-->
<div id="ver_mas" class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-modal-parent="#myModal">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body1">
               
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>
</div>

<!-- Modal requisitos del alquiler -->
<div class="modal fade" id="requisitos" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div style="background: #006688; height: 35px;display: flex;align-items: center;">        
          &nbsp;<b class="modal-title" style="color:white">Requisitos para Alquilar</b>&nbsp;
          
      </div>

      <div class="modal-body2">
          <textarea class="form-control" id="areaRequisitos" name="areaRequisitos" rows="10">          
          </textarea>
          <script>
           CKEDITOR.replace('areaRequisitos');
        </script>  

      </div>
      
        <div id="msg" style="display:none">            
          <p style="color:#ff4302">Datos Guardados</p>
        </div>      
      <div class="modal-footer"> 
        <button type="button" class="btn btn-sm" onclick="imprimir_requisitos()">Imprimir</button>        
        <button type="button" class="btn btn-sm" onclick="guardar_requisitos()">Guardar Cambios</button>
        <button type="button" class="btn btn-sm" data-dismiss="modal">Cerrar</button>      
      </div>
    </div>
  </div>
</div>


<!-- Modal RECLAMOS -->
<div class="modal fade" id="reclamos" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div style="color:white;background: #006688">
        <h5 class="modal-title" id="locatario" ></h5>
        &nbsp;<label id="inmueble" style="font-weight: normal;"></label>
        &nbsp;<label id="telefono" style="font-weight: normal;"></label><br>
        &nbsp;<label id="inicio" style="font-weight: normal;"></label>
       &nbsp; <label id="update" style="font-weight: normal;"></label>       
      </div>
      <div class="modal-body">
          <form id="reclamo" method="post">
            <input id="idR" type="hidden" name="idR">            
           <label>Problema</label>
            <textarea type="text" id="problema" name="problema" class="form-control" rows="1"></textarea>

            <label>Técnico</label>
            <select name="tecnico" class="form-control" id="tecnico" required onchange="borrar_msg();">

            </select>
            <div id="msgTecnico" style="display: none;"><p style="color:red">Complete Aqui</p></div>

            <label>Observaciones</label>
            <textarea type="text" id="descripcion" name="descripcion" class="form-control" rows="3"></textarea>  
            
            <label>Costo Reparación</label>
             <input type="number" id="costo" name="costo" class="form-control" maxlength="3" value="0" min="0">

            <label>Para</label>
            <textarea type="text" id="para" name="para" class="form-control" rows="1"></textarea>         

            <label>¿Quien Paga?</label>
            <select name="paga" class="form-control" id="paga" onchange="borrar_msg()";>
              <option value="">Elija</option>
              <option value="PROPIETARIO">PROPIETARIO</option>
              <option value="INQUILINO">INQUILINO</option>
              <option value="INMOBILIARIA">INMOBILIARIA</option>
            </select>
            <div id="msgPaga" style="display: none;"><p style="color:red">Complete Aqui</p></div>
            <br>
            <label>¿Finalizar Reclamo?</label>
            <select name="finaliza" class="form-control">
              <option value="NO">NO</option>
              <option value="SI">SI</option>
              <option value="ANULAR">ANULAR</option>              
            </select>  
                    
          </form>              
        </div>
        <div id="exito" style="display:none">
            <p style="color:#ff4302">Datos Guardados</p>
        </div>
        <div id="error" style="display:none">
            <p style="color:#ff4302">Se ha producido un error</p>            
        </div>
      <div class="modal-footer">        
        <button type="button" id="botonenviar" class="btn btn-primary btn-sm" style="color: black">Guardar</button>
        <button type="button" id="imprimir_reclamo" class="btn btn-primary btn-sm" style="color: black">Imprimir</button>
        <button type="button" class="btn btn-primary btn-sm" style="color: black" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal NUEVO RECLAMO -->
<div class="modal fade" id="m_nuevoReclamo" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div style="background: #006688; height: 35px;display: flex;align-items: center;">        
          &nbsp;<b class="modal-title" style="color:white">Nuevo Reclamo</b>&nbsp;
          
        </div>
      <div class="modal-body">
          <form id="nuevoReclamo" method="post" accept-charset="utf-8">                     
           <label>Locatario</label>
           <input type="text" name="locatario" id="busquedaPersona" dni="" class="form-control" autocomplete="off" onKeyUp="buscarPersonas();mayus(this);" placeholder="Ingrese Apellido y Seleccione..." required>
              <div class="resultadoPersonas" style="font-size: 13px"></div>
              <div id="msgbusquedaPersona" style="display: none;"><p style="color:red">Complete Aqui</p></div>

            <label>Inmueble</label>
            <input type="hidden" name="idC" id="idC">
            <input type="text" name="inmueble" id="direccion" class="form-control" required readonly>
            <div class="resultadoInmueble" hidden="hidden"></div>
            
            <label>Locador</label>
            <input type="text" name="locador" id="locador" class="form-control" required readonly>

            <label>Teléfono</label>
            <input type="text" name="telefono" id="contacto" class="form-control" required> 
            <div id="msgTelefono" style="display: none;"><p style="color:red">Complete Aqui</p></div>        

            <label>Especialidad</label>           
           <select name="especialidad" class="form-control" id="especialidad" onchange="borrar_msg()" required></select>
           <div id="msgEspecialidad" style="display: none;"><p style="color:red">Complete Aqui</p></div>

            <label>Problema</label>
            <textarea type="text" id="problemaNuevo" name="problema" class="form-control" rows="2" onchange="borrar_msg()" required></textarea>
            <div id="msgProblema" style="display: none;"><p style="color:red">Complete Aqui</p></div>

            <label>Prioridad</label>
            <select class="form-control" id="prioridad" name="prioridad" onchange="borrar_msg()" required>
              <option value="">Elija</option>
              <option value="ALTA">ALTA</option>
              <option value="MEDIA">MEDIA</option>
              <option value="BAJA">BAJA</option>
            </select>  
            <div id="msgPrioridad" style="display: none;"><p style="color:red">Complete Aqui</p></div>        
          </form>              
        </div>
        <div id="exitoReclamo" style="display:none">
            <p style="color:#ff4302">Datos Guardados</p>
        </div>
        <div id="errorReclamo" style="display:none">
            <p style="color:#ff4302">Se ha producido un error</p>            
        </div>
      <div class="modal-footer">        
        <button type="button" id="enviarReclamo" class="btn btn-primary btn-sm" style="color: black">Guardar</button>        
        <button type="button" class="btn btn-primary btn-sm" style="color: black" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>