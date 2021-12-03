<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Comodato extends CI_Controller{	

		public function __construct(){			
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('grocery_CRUD');
			$this->config->load('grocery_crud');
			$this->load->model('buscar_datos_model');
			$this->load->model('reportes_model');
			$this->load->library('session');
		}
		public function index(){			
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
		}

		public function anular_comodato($idC){
			$idI=$this->buscar_datos_model->buscar_idI($idC);
			$this->buscar_datos_model->eliminar_alquiler($idC);

			$this->db->set('estado',0);
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');
			
			redirect('Comodato/comodato');
		}		

		public function comodato(){
			$this->config->load('grocery_crud');		

			$output = $this->comodato_management();				
			$js_files =$output->js_files;
			$css_files =  $output->css_files;
			$output = "".$output->output;

			$this->_example_output((object)array(
					'js_files' => $js_files,
					'css_files' => $css_files,
					'output'	=> $output
			));
		}

		public function comodato_management(){	
				//$this->config->set_item('grocery_crud_dialog_forms',true);
				$crud=new grocery_CRUD();				
				$crud->set_table('alquileres');	
				$crud->set_subject('Alquiler');	

				/*$crud->set_table('inmuebles');
				$crud->set_subject('Inmueble');
				$crud->set_relation_n_n('propiedad','alquileres','personas','idInmueble','dni','apellidoNombre');*/
				
				//$crud->unset_add();
				$crud->unset_print();
				$crud->unset_export();
				//$crud->set_relation('idInmueble','inmuebles','idInmueble');			

				//$crud->set_relation('idInmueble','inmuebles','dni');

				$crud->set_relation('locatario1','personas','apellidoNombre');
				$crud->set_relation('locatario2','personas','apellidoNombre');
				$crud->set_relation('garante1','personas','apellidoNombre');
				$crud->set_relation('garante2','personas','apellidoNombre');
				$crud->set_relation('garante3','personas','apellidoNombre');
				$crud->set_relation('garante4','personas','apellidoNombre');
				$crud->set_relation('locador','personas','apellidoNombre');	

				$crud->where('((estado_contrato ="VIGENTE" OR estado_contrato ="FINALIZA" OR estado_contrato ="RENUEVA" OR estado_contrato ="VIG.RESCINDE" OR estado_contrato ="RESCINDE") and (operacion="COMODATO"))',null,FALSE);		

				

				//$crud->where('operacion','COMODATO');
				/*$crud->or_where('estado_contrato','FINALIZA');
				$crud->or_where('estado_contrato','RENUEVA');*/
			$crud->field_type('comision_paga','dropdown',
           	 array('AMBOS' => 'AMBOS', 'PROPIETARIO' => 'PROPIETARIO'));

				//$crud->where('rescision','0');	este	

				//$crud->field_type('estado_alquiler', 'hidden', 0);	

				$sesion= $this->session->userdata('usuario');								

				$crud->display_as('idInmueble','Inmueble');
				$crud->display_as('punitorio','Mora diaria en %');
				$crud->display_as('comision_admin','Comision por Admin. en %');
				$crud->display_as('comision_inmo_a_pagar','Comision-Inm. a Pagar');
				$crud->display_as('fechaPago','Dia de Pago');
				$crud->display_as('fechaInicio','Inicio');				
				$crud->display_as('fechaFin','Fin');
				$crud->display_as('duracion','Duracion');
				$crud->display_as('estado_alquiler','');
				$crud->display_as('proxVenc','Prox.-Vto');
				$crud->display_as('ajuste','Ajuste en %');
				$crud->display_as('tipo_ajuste','Tipo de Ajuste');
				$crud->display_as('fecha_firma','Firma de Contrato');
				$crud->display_as('estado_contrato','Contrato');
				$crud->display_as('idContrato','#');
				$crud->display_as('sellado_contrato','Sellados de Contrato Total');		
				$crud->display_as('locatario1','Comodatario');
				$crud->display_as('locador','Comodante');	
				$crud->display_as('cant_pagos','#');											

				$crud->field_type('tipo_ajuste','enum',array('SEMESTRAL','OCTOMESTRAL','ANUAL','SIN AJUSTE' ));

				
				$crud->columns('idContrato','estado_alquiler','fechaInicio','fechaFin','idInmueble','edificio','locatario1','locador','proxVenc','estado_contrato','cant_pagos');
				
				$crud->required_fields('idInmueble','locador','operacion','locatario1','garante1','fechaInicio','punitorio','comision_admin','fechaPago','duracion','fechaFin','valor1','tipo_ajuste','ajuste','comision_paga');				

			$crud->fields('idInmueble','locador','operacion','locatario1','locatario2','garante1','garante2','garante3','garante4','fechaInicio','duracion','fechaFin','fechaPago','proxVenc','punitorio','comision_admin','tipo_ajuste','ajuste','valor1','valor2','valor3','valor4','valor5','valor6','comision_inmo_a_pagar','sellado_contrato','fecha_creacion','usuario_creacion','fecha_firma','escribano','estado_alquiler','estado_contrato','pendientes','rescision');

		$crud->edit_fields('idInmueble','locador','operacion','locatario1','locatario2','garante1','garante2','garante3','garante4','fechaInicio','duracion','fechaFin','fechaPago','proxVenc','punitorio','comision_admin','tipo_ajuste','ajuste','valor1','valor2','valor3','valor4','valor5','valor6','comision_inmo_a_pagar','sellado_contrato','fecha_firma','escribano','rescinde_dentro','rescinde_fecha','estado_contrato','rescision');				

				$crud->set_rules('duracion','Duracion','numeric');
				$crud->set_rules('fechaPago','Fecha de Pago','numeric');
				$crud->set_rules('punitorio','Punitorio','numeric');
				$crud->set_rules('comi_admin','Comisión por Adm.','numeric');
				$crud->set_rules('comision_inmo_debe','Comisión inmobiliaria','numeric');
				$crud->set_rules('ajuste','Ajuste','numeric');

				$crud->field_type('fecha_creacion','invisible');
				$crud->field_type('usuario_creacion','invisible');				
				$crud->field_type('estado_alquiler','invisible');
				$crud->field_type('estado_contrato','invisible');
				$crud->field_type('rescision','invisible');				
				$crud->field_type('pendientes','invisible');


				$crud->unset_delete();
				//$crud->unset_edit();		
				$crud->unset_read();					

				//aca se precarga el inmueble, el propietario y el valor 	
				$id=$this->uri->segment(4);
				if(is_numeric($id)){	

					$crud->callback_add_field('idInmueble', function () {
						$idI=$this->uri->segment(4);
						$this->db->select('direccion,piso,depto,dni,idEdificio,mts2,caracteristicas');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$idI);
						$query=$this->db->get();
						foreach ($query->result() as $row){
							$dni=$row->dni;
							$mts2=$row->mts2;
							$caract=$row->caracteristicas;
							$idE=$row->idEdificio;
						}						
						$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
						if(isset($idE)){
							$edificio=$this->buscar_datos_model->buscar_edificio($idE);
							$nombreE=$edificio['edificio'];
						}else{
							$nombreE="";
						}	
						//$Nedificio=

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni);		
						$query=$this->db->get();
						foreach ($query->result() as $row){
							$nombre = $row->apellidoNombre;
						}
						return '<select id="field-idInmueble" class="chosen-select" data-placeholder="Seleccionar Inmueble" value="Inmueble" name="idInmueble" style="width:auto;height:30px"><option value='.$idI.'>'.$direccion.'</option></select>'.'&nbsp-<b>Edificio:</b> '.$nombreE.'&nbsp- <b>M²:</b> '.$mts2.'&nbsp- <b>Caracteristicas:</b> '.$caract;
					});

					$crud->callback_add_field('locador', function () {
						$id=$this->uri->segment(4);
						$this->db->select('dni');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$id);
						$query=$this->db->get();
						foreach ($query->result() as $row){
							$dni=$row->dni;
						}
						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni);
						$query=$this->db->get();
						foreach ($query->result() as $row){
							$nombre = $row->apellidoNombre;
						}
						return '<select id="field-locador" class="chosen-select" data-placeholder=" " value="Locador" name="locador"><option value='.$dni.'>'.$nombre.'</option></select>';
					});					
				}	
				//fin - aca se precarga el inmueble y el propietario 

					//se carga el combo de locador y el enlace de Añadir
					$crud->callback_add_field('locatario1', function () {
						$combo = '<select id="field-locatario1" name="locatario1" class="chosen-select" data-placeholder="Seleccionar Locatario" value="Locatario">';
						$fincombo = '</select>';

						//verificar si el inmueble tiene reserva
						$idI=$this->uri->segment(4);
						$datos_reserva=$this->buscar_datos_model->buscar_datos_reserva($idI);
						if(!empty($datos_reserva)){
							$reserva=' <b>Reservado por:</b> '.$datos_reserva['interesado'];
						}else{
							$reserva="";
						}

						$estado_inmueble=$this->buscar_datos_model->buscar_estado_inmueble($idI);
						$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
						if($estado_inmueble==6){
							$dni=$datos_inmueble['renueva'];
							$locatario1=$this->buscar_datos_model->buscar_persona($dni);

							$this->db->select('dni,apellidoNombre');
							$this->db->from('personas');
							$this->db->order_by('apellidoNombre','asc');							
							$query=$this->db->get();

							foreach ($query->result() as $row){
								if($dni==$row->dni){
								 $combo .= '<option value=""></option><option value="'.$row->dni.'" selected>'.$row->apellidoNombre.'</option>';
								}else{
									$combo .= '<option value=""></option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
								}
							}
							return $combo.$fincombo.$reserva.'&nbsp;&nbsp;<a href="'.base_url('Persona/persona').'"> Añadir</a><span id="idE"></span>';							

							/*return '<select id="field-locador" name=locatario1 class="chosen-select" data-placeholder=" " value="Locador" name="locador"><option value='.$dni.'>'.$locatario1.'</option></select>';	*/						
						}else{
							$this->db->select('dni,apellidoNombre');
							$this->db->from('personas');
							$this->db->order_by('apellidoNombre','asc');							
							$query=$this->db->get();
							foreach ($query->result() as $row){
								 $combo .= '<option value=""></option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
							}
							return $combo.$fincombo.$reserva.'&nbsp;&nbsp;<a href="'.base_url('Persona/persona').'"> Añadir</a><span id="idE"></span>';
						}
						
					});	

					/*$crud->callback_add_field('fechaInicio', function () {
						$inicio = '<input id="field-fechaInicio" name="fechaInicio" type="date"   style="width:150px;height:30px" />';				
						return $inicio;
					});*/					


					$crud->callback_add_field('duracion', function () {
						$lapso = '<input id="field-duracion" name="duracion" type="text" value="" maxlength="2" style="width:40px;height:30px" class="numeric form-control" onkeyup="fin_contato()" required>';				
						return $lapso;
					});

					//a partir del dia de pago ingresado se genera la fecha del prox_venc en el siguiente input mediante onchange
					$crud->callback_add_field('fechaPago', function () {
						$paga = '<input id="field-fechaPago" name="fechaPago" type="text" value="" maxlength="10" style="width:30px;height:30px" onchange="proxvenc()" />';							
						return $paga;
					});

					$crud->callback_add_field('fechaFin', function () {
						$fecha_fin = '<input id="field-fechaFin" name="fechaFin" type="text" value="" maxlength="10" style="width:100px;height:30px"  />';	
						return $fecha_fin;
					});

					$crud->callback_add_field('proxVenc', function () {
						$prox_venc = '<input id="field-proxVenc" name="proxVenc" type="text"  maxlength="10" style="width:100px;height:30px" />';	
						return $prox_venc;
					});

					$crud->callback_add_field('comision_admin', function () {
						$comi_admin = '<input id="field-comision_admin" name="comision_admin" type="text" value="" maxlength="2" style="width:30px;height:30px" class="numeric form-control" />';	
						return $comi_admin;
					});

					$crud->callback_add_field('punitorio', function () {
						$punitorio = '<input id="field-punitorio" name="punitorio" type="text" value="" maxlength="2" style="width:30px;height:30px" class="numeric form-control" />';	
						return $punitorio;
					});

					$crud->callback_add_field('ajuste', function () {
						$ajuste = '  <input id="field-ajuste" name="ajuste" type="text" value="" maxlength="4" style="width:50px;height:30px" class="numeric form-control" />';	
						return $ajuste;
					});

					$crud->callback_add_field('valor1', function () {
						$idI=$this->uri->segment(4);
						$this->db->select('valor');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$idI);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$valor=$row->valor;
						}

						//verificar si tiene reserva el inmueble						
						$datos_reserva=$this->buscar_datos_model->buscar_datos_reserva($idI);
						if(!empty($datos_reserva)){
							$sena=' <b>Seña por reserva:</b> '.$datos_reserva['sena'].', debe abonar: <b style="color:red;; font-size:16px">'.($valor-$datos_reserva['sena']).'</b>';
						}else{
							$sena="";
						}						

						if(!isset($valor)) $valor="0";

						return '<input id="field-valor1" name="valor1" type="text" value='.$valor.' class="numeric form-control" maxlength="8" style="width:80px;height:30px"/>'.$sena;
					});	

					$crud->callback_add_field('valor2', function () {
						return '<input id="field-valor2" name="valor2" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id),vaciar_valores()"/>'.'&nbsp<span id=valor2><span/>';
					});	

					$crud->callback_add_field('valor3', function () {
						return '<input id="field-valor3" name="valor3" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor3><span/>';
					});	

					$crud->callback_add_field('valor4', function () {
						return '<input id="field-valor4" name="valor4" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor4><span/>';
					});

					$crud->callback_add_field('valor5', function () {
						return '<input id="field-valor5" name="valor5" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor5><span/>';
					});

					$crud->callback_add_field('valor6', function () {
						return '<input id="field-valor6" name="valor6" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor6><span/>';
					});	

					$crud->callback_add_field('comision_inmo_a_pagar', function () {
						$comision_a_pagar = '<input id="field-comision_inmo_a_pagar" name="comision_inmo_a_pagar" type="text" onfocus="calcular_comision(this)" maxlength="10" style="width:80px;height:30px"/>';	
						return $comision_a_pagar;
					});	

					$crud->callback_add_field('sellado_contrato', function () {
						$idI=$this->uri->segment(4);
						$valor1=$post_array['valor1'];		

						$boton_imprimir_gastos = '&nbsp;&nbsp <input type="button" name="button" id="gastos_alquiler" value="IMPRIMIR GASTOS" class="ui-input-button" onclick="imprimir_gastos_alquiler()">';

						$certificacion='&nbsp; Certificacion:<input id="certificacion" value="0" name="certificacion" type="text" style="width:80px;height:30px"/>';

						$veraz='&nbsp; Veraz:<input id="veraz" name="veraz" value="0" type="text" style="width:80px;height:30px"/>';


						return '<input id="field-sellado_contrato" name="sellado_contrato" type="text" onfocus="calcular_sellado(this)" class="numeric form-control" maxlength="8" style="width:80px;height:30px"/>'.$certificacion.$veraz.$boton_imprimir_gastos;
					});										

					$crud->callback_add_field('fecha_firma', function () {
						$firma = '<input id="field-fecha_firma" name="fecha_firma" type="date" value=""  style="width:150px;height:30px"/>';				
						return $firma;
					});	

					$crud->callback_add_field('escribano', function () {
						$escribano = '  <input id="field-escribano" name="escribano" type="text" value="Juan Vedoya Gonzalez" maxlength="25" style="width:275px;height:30px"/>';	
						return $escribano;
					});														

				$crud->callback_before_insert(array($this,'fechaCreacion_Usuario'));							

				//cambiar el estado del inmueble despues de alquilar un inmueble
				//$crud->callback_after_insert(array($this,'estado_inmueble'));

				$crud->callback_after_insert(array($this,'primer_pago'));

							
				if($sesion[0]==1 or $sesion[0]==2){
					$crud->add_action('Cobrar','','Pago/pagar/add','ui-icon-calculator');
				}							
				
					

				$crud->add_action('Pagos','','Verpago/verpago','ui-icon-calendar');	

				$crud->add_action('Ver','','Comodato/read','ui-icon-folder-open');

				if($sesion[0]==1 or $sesion[0]==3){
					$crud->add_action('Editar','','Comodato/comodato_edit/edit','ui-icon-pencil');
					
					$crud->add_action('Finalizar','','Comodato/finalizar_contrato','ui-icon-flag');	

					//$crud->add_action('Prorroga','','Comodato/prorroga','ui-icon-arrowthickstop-1-e');											
				}

				if($sesion[0]==1){					
					$crud->add_action('Rescindir','','Pago/rescindir_contrato/add','ui-icon-closethick');
				}					

				$crud->add_action('No renueva','','Comodato/cancelar_renueva','ui-icon-cancel');				

				$crud->callback_column('idInmueble',array($this,'buscar_direccion'));

				//$crud->callback_column('fechaFin',array($this,'fecha_fin'));

				$crud->callback_column('edificio',array($this,'buscar_edificio'));

				$crud->callback_column('estado_contrato',array($this,'estado_contrato'));
				

				$crud->callback_after_update(array($this,'primer_pago_update'));

				$crud->callback_before_update(array($this,'fechaCreacion_Usuario_update'));

				//$crud->add_action('Eliminar','','Alquiler/eliminar_alquiler','ui-icon-circle-minus');		

				//$idC = $this->buscar_datos_model->buscar_idC_id();

		$crud->set_lang_string('insert_success_message',
	                 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
	                 <script type="text/javascript">
	                  window.location = "'.site_url('Comodato/comodato').'";
	                 </script>
	                 <div style="display:none">
	                 '
	   ); 		

	   			//$crud->set_crud_url_path(site_url('Comodato/comodato'));	
						
				$output = $crud->render();
				if($crud->getState() != 'list') {
					$this->_example_output($output);
				} else {
					return $output;
				}

				$state = $crud->getState();
				$state_info = $crud->getStateInfo();

				if($state == 'success'){
					redirect('Comodato/comodato');	//echo "aca es el ADD";	//Do your cool stuff here . You don't need any State info you are in add
				}elseif($state == 'update'){
					redirect('Comodato/comodato');
				}						
		}

public function prorroga($idC){

}		

public function imprimir_gastos_alquiler(){
		$host=$_SERVER['SERVER_NAME'];

		//$x=3;
		//"+idI+'/'+duracion+'/'+tipo_ajuste+'/'+sellado+'/'+comision+'/'+certificacion+'/'+veraz+'/'+valor1+'/'+valor2+'/'+valor3+'/'+valor4+'/'+valor5+'/'+valor6;

		$idI=$this->uri->segment(3);
		$duracion=$this->uri->segment(4);
		$tipo_ajuste=$this->uri->segment(5);


		$sellado_total=$this->uri->segment(6);
		$sellado_f=$sellado_total/2;
		$sellado=number_format($sellado_f, 2, ',', '');

		$comision=$this->uri->segment(7);
		$certificacion=$this->uri->segment(8);
		$veraz=$this->uri->segment(9);

		$valor1=$this->uri->segment(10);
		$valor2=$this->uri->segment(11);

		$monto_total_t=floatval($valor1+$sellado_f+$comision+$certificacion+$veraz);
		$monto_total=number_format($monto_total_t, 2, ',', '');

		if($duracion=="12"){
			if($tipo_ajuste=="ANUAL"){
				$valor1=$this->uri->segment(10);
				$i=1;
			}else if($tipo_ajuste=="SEMESTRAL"){
				$valor1=$this->uri->segment(10);
				$valor2=$this->uri->segment(11);
				$i=2;
			}
		}else if($duracion=="24"){
			if($tipo_ajuste=="SEMESTRAL"){
				$valor3=$this->uri->segment(12);
				$valor4=$this->uri->segment(13);
				$i=4;
			}elseif($tipo_ajuste=="OCTOMESTRAL"){
				$valor3=$this->uri->segment(12);
				$i=3;				
			}elseif($tipo_ajuste=="ANUAL"){
				$valor2=$this->uri->segment(11);
				$i=2;
			}
		}elseif ($duracion=="36") {
			if($tipo_ajuste=="SEMESTRAL"){
				$valor3=$this->uri->segment(12);
				$valor4=$this->uri->segment(13);
				$valor5=$this->uri->segment(14);
				$valor6=$this->uri->segment(15);
				$i=6;
			}elseif($tipo_ajuste=="ANUAL"){
				$valor3=$this->uri->segment(12);
				$i=3;				
			}
		}
		/*
		$valor4=$this->uri->segment(7);
		$valor5=$this->uri->segment(8);
		$valor6=$this->uri->segment(9);
		$duracion=$this->uri->segment(10);
		$tipo_ajuste=$this->uri->segment(11);
		$sellado=$this->uri->segment(12);	*/	

		$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
		$direccion=$this->buscar_datos_model->buscar_inmueble($idI);

		$idE=$this->buscar_datos_model->buscar_idE($idI);
		if(isset($idE)){
			$edificio=$this->buscar_datos_model->buscar_edificio($idE);
			$Nedificio=$edificio['edificio'];
			$Nbarrio=$edificio['barrio'];
		}else{
			$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
			$idB=$datos_inmueble['idB'];
			$barrio= $this->buscar_datos_model->buscar_barrio($idB);
		}
		/*$ubicacion=$datos_inmueble['ubicacion']; 
		$cochera=$datos_inmueble['cochera']; 
		$cant_dorm=$datos_inmueble['dorm']; 
		$caracteristicas=$datos_inmueble['caract_adicional'];
		$condicion=$datos_inmueble['condicion'];
		$requisitos=$datos_inmueble['requisitos'];
		$valor=$datos_inmueble['valor'];*/

		$html = 
        	"<style>@page {        		
			    margin-top: 1cm;
			    margin-bottom: 1cm;
			    margin-left: 1.27cm;
			    margin-right: 1.27cm;
			}
			</style>".
       		 "<body>
       		 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
       		 	<tr>
       		 		<td align='center' valign='bottom'><b style='width:85%;font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:20px'>Gastos de Alquiler</b></td> 
       		 		<td style='width:15%' align='center'><img src='http://$host/SGI/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		<tr>
        		<tr>
	        		<td colspan='2' style='height:20px;vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragüi V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
	        		</td>	        		
        		</tr>        		
        	</table>

        	<br>

        	<table width='425px' border='0' cellpadding='0' cellspacing='0'>	
        		<tr> 
        			<td style='width:75px; height:25px'><b><u>Inmueble</u>: </b></td>        			
        			<td style='width:350px;height:25px'>&nbsp; $direccion</td>

        		</tr>
        	</table>
        	<br>
        	<table  style='width:385px' border='0' cellpadding='0' cellspacing='0'>			
        	
        		<tr>
        			<td style='width:300px; height:25px'><b><u>Valor Alquiler</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp;&nbsp; $valor1,00</td>
        		</tr>

        		<tr>
        			<td style='width:300px; height:25px'><b><u>Sellado de contrato(50%)</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$sellado</td>
        		</tr> 

        		<tr>
        			<td style='width:300px; height:25px'><b><u>Comisión Inmobiliaria</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$comision,00</td>
        		</tr>         		

        		<tr>
        			<td style='width:300px; height:25px'><b><u>Certificación de Firmas(2 firmas)</u>: </b></td>      			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$certificacion,00</td>
        		</tr>         		

        		<tr>        			
        			<td style='width:300px; height:25px'><b><u>Veraz (Locatario y Garante)</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$veraz,00</td>      			
        		</tr>
        		</table>					

        		<table width='400px' border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
	        			<td style='width:280px; height:25px'><b><u>Monto Total para firmar el Contrato</u>: </b></td>       			
	        			<td style='width:120px;height:25px;border-top: 1px solid black;' align='right'><b style='font-size:18px'>$ $monto_total</b></td>
        			</tr>  
        		</table>

        		<br>
        		<br>
        		<br>

        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>	
        		<tr>
        			<td style='height:35px'>
        				Contrato de locación por: <b>$duracion meses</b> - Ajuste del alquiler: <b>$tipo_ajuste</b>        				
        		</tr>";

        		if($i==1){
        			$html.=$valores="<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
        		}elseif($i==3){
        			$html.=$valores="<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2, $ $valor3</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
        		}elseif($i==4){
        			$html.=$valores="<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2, $ $valor3, $ $valor4</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
        		}elseif($i==6){
        			$html.=$valores="<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2, $ $valor3, $ $valor4, $ $valor5, $ $valor6</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
        		}elseif($i==2){
					$html.=$valores="<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE. </td>
					</tr>";        			
        		}

        		$html.=$pie="<tr>	  
        			<td style='height:35px'>Expensas aproximadamente: $.................... incluye.......................................+ LUZ + AGUA + CSP
        			</td>     			
        		</tr>
        		<tr>	  
        			<td style='height:35px'><b>Depósito en garantía:</b> No se exige en efectivo, se reemplaza por 1 pagares por 2 meses de alquiler.
        			</td>     			
        		</tr>

        		</table>
        		";       		
        	       	       	      	

			$html.=$fin="</table></body>";        	

        	$pdfFilePath = $direccion.".pdf";
 
        	// $html = $this->load->view('v_dpdf',$date,true);
 
        	//load mPDF library
        	$this->load->library('M_pdf');
       	 	$mpdf = new mPDF('c', 'A4-P'); 
 			$mpdf->WriteHTML($html);
			//$mpdf->Output($pdfFilePath, "D");
       		// //generate the PDF from the given html
       		$this->m_pdf->pdf->WriteHTML($html);
 
       		//  //download it.
       		$this->m_pdf->pdf->Output($pdfFilePath, "D"); 		

	}		

		public function estado_contrato($value,$row){
			$idC=$row->idContrato;
			$idI=$this->buscar_datos_model->buscar_idI($idC);

			if($value=='VIGENTE'){
				return '<b style="color:green"><abbr title="Inmueble:'.$idI.'">'.$value.'</b></abbr>';
			}elseif($value=='FINALIZA'){
				return '<b style="color:red"><abbr title="Inmueble:'.$idI.'">'.$value.'</b></abbr>';
			}elseif($value=='RENUEVA'){
				return '<b style="color:#FF5733"><abbr title="Inmueble:'.$idI.'">'.$value.'</b></abbr>';
			}elseif($value=='RESCINDIDO'){
				return '<b style="color:red"><abbr title="Inmueble:'.$idI.'">'.$value.'</b></abbr>';
			}elseif($value=='FINALIZADO'){
				return '<b style="color:red"><abbr title="Inmueble:'.$idI.'">'.$value.'</b></abbr>';
			}

		}

		public function primer_pago($post_array, $pk){
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$fecha=$post_array['fechaInicio'];
			$primer_periodo=$this->buscar_datos_model->formato_fecha($fecha);

				$id =$post_array['idInmueble'];
				$valor1=$post_array['valor1'];

				$this->db->set('estado',1);//estado 1 es alquilado
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');

				$this->db->set('valor',$valor1);
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');			

				$this->db->set('reserva',0);
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');

				//actualizo cantidad de alquileres vigentes del locador
				$locador=$post_array['locador'];
				$this->db->set('pendientes',"NO");				
				$this->db->where('dni',$locador);
				$this->db->update('personas');				

				$this->buscar_datos_model->eliminar_reserva($id);

				return true;
		}

		public function buscar_contacto($value, $row){
			$locatario=$row->locatario1;
			$contacto_locatario=$this->buscar_datos_model->buscar_telefono_locatario($locatario);
			return $contacto_locatario;
		}			
		

		public function buscar_edificio($value, $row){
			$idC=$row->idContrato;
			$idI=$this->buscar_datos_model->buscar_idI($idC);
			$idE=$this->buscar_datos_model->buscar_idE($idI);
			if(isset($idE)){
				$this->load->model('buscar_datos_model');
				$edificio=$this->buscar_datos_model->buscar_edificio($idE);
				return $edificio['edificio'];
			}else{
				return $value;
			}				
		}

		public function buscar_direccion($value, $row){
			$idI=$row->idInmueble;			
			$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
			return $direccion;
		}

		public function fecha_fin($value, $row){
			return $value;
		}

		public function hay_reclamos($value, $row){
			$idC=$row->idContrato;
			$reclamo_si=0;
			$this->db->select('estado');
			$this->db->from('reclamos');
			$this->db->where('idContrato',$idC);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$estado = $row->estado;
				if($estado =="EN PROCESO" or $estado == "PENDIENTE"){
					$reclamo_si = $reclamo_si + 1;
				}			
			}
			if($reclamo_si > 0){
				$estado = '<b style="color:red;font-size:13px">'."SI".'</b>';
			}else{
				$estado = '<b style="color:green;font-size:13px">'."NO".'</b>';
			}
			return $estado;
		}

		public function contar_estados($value, $row){
			$idC=$row->idContrato;	
			//$idC=$this->buscar_datos_model->buscar_idC($idI);	
			$estado['pendiente']=0;
			$estado['proceso']=0;
			$estado['finalizado']=0;				
			$this->db->select('estado');
			$this->db->from('reclamos');
			$this->db->where('idContrato',$idC);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$estado_temp = $row->estado;
				if($estado_temp =='PENDIENTE'){
					$estado['pendiente']=$estado['pendiente'] + 1;
				}elseif($estado_temp =='EN PROCESO'){
					$estado['proceso']=$estado['proceso'] + 1;
				}elseif($estado_temp =='FINALIZADO'){	
					$estado['finalizado']=$estado['finalizado'] + 1;
				}			
			}			
			$estados_reclamos='<span style="font-size:13px">'.'<b  style="color:red">'.$estado['pendiente'].'<b/>'.' / '.'<b  style="color:#ff8000">'.$estado['proceso'].'</b>'.' / '.'<b style="color:green">'.$estado['finalizado'].'</b></span>';
			return $estados_reclamos;
		}

		public function contar_reclamos($value, $row){
			$tecnico=$row->encargado;						
			$this->db->select('*');
			$this->db->from('reclamos');
			$this->db->where('encargado',$tecnico);
			$this->db->where('estado <>','FINALIZADO');
			$query=$this->db->get();
			$count=$query->result();
 			return count($count);
		}


		public function fechaCreacion_Usuario($post_array){				
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$post_array['fecha_creacion']=date('d/m/Y G:i');

			$sesion= $this->session->userdata('usuario');
			$usuario=$sesion[1];

			$post_array['usuario_creacion']=$usuario;
			//$saldoInicial=$post_array['comision_inmo_debe'];
			//$post_array['saldo_inicial_CI']=$saldoInicial;
			$post_array['estado_contrato']="VIGENTE";
			$post_array['estado_alquiler']="DEUDA";
			$post_array['pendientes']="SI";

			return $post_array;
		}

		public function pagar(){	
			$id=$this->uri->segment(4);	
			redirect('Pago/pago');
		}

		public function estado_inmueble($post_array, $pk){	
				//$id=$this->uri->segment(4);
				$id =$post_array['idInmueble'];
				$this->db->set('estado','ALQUILADO');
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');

				//actualizo cantidad de alquileres vigentes del locador
				$locador=$post_array['locador'];
				/*$this->db->select('idContrato');
				$this->db->from('alquileres');
				$this->db->where('locador',$locador);

				$cant_alquileres= $this->db->count_all_results();*/

				$this->db->set('pendientes',"NO");				
				$this->db->where('dni',$locador);
				$this->db->update('personas');

		}

		public function read(){
			$crud=new grocery_CRUD();				
			$crud->set_table('alquileres');	
			$crud->set_subject('Alquiler');	

			$crud->display_as('idInmueble','Inmueble');
			$crud->display_as('punitorio','Mora diaria en %');
			$crud->display_as('comision_admin','Comision por Admin. en %');
			$crud->display_as('comision_inmo_a_pagar','Comisión Inmobiliaria');

			$crud->display_as('fechaPago','Dia de Pago');
			$crud->display_as('fechaInicio','Inicio');
			$crud->display_as('fechaFin','Fin');
			$crud->display_as('duracion','Duracion');
			$crud->display_as('estado_alquiler','Estado');
			$crud->display_as('proxVenc','Próximo-Vto');
			$crud->display_as('ajuste','Ajuste en %:');
			$crud->display_as('tipo_ajuste','Tipo de Ajuste');
			$crud->display_as('valor1','Alquiler 1er período');
			$crud->display_as('valor2','Alquiler 2do período');
			$crud->display_as('valor3','Alquiler 3er período');
			$crud->display_as('valor4','Alquiler 4to período');
			$crud->display_as('valor5','Alquiler 5to período');
			$crud->display_as('valor6','Alquiler 6to período');
			$crud->display_as('locatario1','Comodatario');
			$crud->display_as('locatario2','Comodatario2');
			$crud->display_as('locador','Comodante');

			//$crud->field_type('comision_inmo_debe','hidden');
			//$crud->field_type('comision_inmo_paga','hidden');
			//$crud->field_type('saldo_inicial_CI','hidden');
			$crud->field_type('pendientes','hidden');
			$crud->field_type('reclamos','hidden');
			$crud->field_type('rescision','hidden');
			$crud->field_type('cant_pagos','hidden');

			$crud->callback_read_field('idInmueble', function ($value, $primary_key) {
				$idI=$value;
				$direccion=$this->buscar_datos_model->buscar_inmueble($value);				
				return $direccion;
			});

			$crud->callback_read_field('edificio', function ($value, $primary_key) {
				$idC=$primary_key;
				$idI=$this->buscar_datos_model->buscar_idI($idC);
				$idE=$this->buscar_datos_model->buscar_idE($idI);
				if(isset($idE)){
					$this->load->model('buscar_datos_model');
					$edificio=$this->buscar_datos_model->buscar_edificio($idE);
					return $edificio['edificio'];
				}else{
					return $value;
				}
			});			

			$crud->callback_read_field('locador', function ($value, $primary_key) {				
				$locador=$this->buscar_datos_model->buscar_persona($value);
			return $locador;
			});	

			$crud->callback_read_field('locatario1', function ($value, $primary_key) {
				
			$locatario1=$this->buscar_datos_model->buscar_persona($value);
			return $locatario1;
			});

			$crud->callback_read_field('locatario2', function ($value, $primary_key) {
				
				$locatario2=$this->buscar_datos_model->buscar_persona($value);
			return $locatario2;
			});			

			$crud->callback_read_field('garante1', function ($value, $primary_key) {				
				$garante1=$this->buscar_datos_model->buscar_persona($value);
			return $garante1;
			});

			$crud->callback_read_field('garante2', function ($value, $primary_key) {				
				$garante2=$this->buscar_datos_model->buscar_persona($value);
			return $garante2;
			});

			$crud->callback_read_field('garante3', function ($value, $primary_key) {				
				$garante3=$this->buscar_datos_model->buscar_persona($value);
			return $garante3;
			});

			$crud->callback_read_field('garante4', function ($value, $primary_key) {				
				$garante4=$this->buscar_datos_model->buscar_persona($value);
			return $garante4;
			});

			$crud->callback_read_field('fechaInicio', function ($value, $primary_key) {				
				$fecha_inicio=$this->buscar_datos_model->formato_fecha($value);
				return $fecha_inicio;
				//return $value;
			});	

			$crud->callback_read_field('fechaFin', function ($value, $primary_key) {				
				$fecha_fin=$this->buscar_datos_model->formato_fecha($value);
				return $fecha_fin;
				//return $value;
			});										

			$crud->set_crud_url_path(site_url('Comodato/comodato'));		
			

			$output = $crud->render();
			$this->_example_output($output);	

			/*if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}*/
		}

	public function comodato_edit(){
			$crud=new grocery_CRUD();				
			$crud->set_table('alquileres');	
			$crud->set_subject('Alquiler');	

			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('punitorio','Mora diaria en %');
			$crud->display_as('comision_admin','Comision por Admin. en %');
			$crud->display_as('comision_inmo_a_pagar','Comisión Inmobiliaria');

			$crud->display_as('locatario1','Comodatario');
			$crud->display_as('locatario2','Comodatario2');
			$crud->display_as('locador','Comodante');

			$crud->display_as('fechaPago','Dia de Pago');
			$crud->display_as('fechaInicio','Inicio');
			$crud->display_as('fechaFin','Fin');
			$crud->display_as('duracion','Duracion');
			$crud->display_as('estado_alquiler','Estado');
			$crud->display_as('proxVenc','Próximo-Vto');
			$crud->display_as('ajuste','Ajuste en %:');
			$crud->display_as('tipo_ajuste','Tipo de Ajuste');
			/*$crud->display_as('valor1','Alquiler 1er período');
			$crud->display_as('valor2','Alquiler 2do período');
			$crud->display_as('valor3','Alquiler 3er período');
			$crud->display_as('valor4','Alquiler 4to período');
			$crud->display_as('valor5','Alquiler 5to período');
			$crud->display_as('valor6','Alquiler 6to período');*/

			//$crud->field_type('tipo_ajuste','enum',array('SEMESTRAL','OCTOMESTRAL','ANUAL','SIN AJUSTE'));
			//$crud->field_type('duracion','enum',array('24','36'));


				$crud->set_relation('locatario1','personas','apellidoNombre');
				$crud->set_relation('locatario2','personas','apellidoNombre');
				$crud->set_relation('garante1','personas','apellidoNombre');
				$crud->set_relation('garante2','personas','apellidoNombre');
				$crud->set_relation('garante3','personas','apellidoNombre');
				$crud->set_relation('garante4','personas','apellidoNombre');
				$crud->set_relation('locador','personas','apellidoNombre');	

			//$crud->field_type('comision_inmo_debe','hidden');
			//$crud->field_type('comision_inmo_paga','hidden');
			//$crud->field_type('saldo_inicial_CI','hidden');
			/*$crud->field_type('pendientes','hidden');
			$crud->field_type('reclamos','hidden');
			$crud->field_type('rescision','hidden');
			$crud->field_type('cant_pagos','hidden');*/

			//$crud->field_type('estado_alquiler','invisible');			
			//$crud->field_type('pendientes','invisible');

			$crud->field_type('rescision','invisible');
			$crud->field_type('estado_contrato','invisible');

			$idC=$this->uri->segment(3);

		$crud->edit_fields('idInmueble','locador','operacion','locatario1','locatario2','garante1','garante2','garante3','garante4','fechaInicio','duracion','fechaFin','fechaPago','proxVenc','punitorio','comision_admin','tipo_ajuste','ajuste','valor1','valor2','valor3','valor4','valor5','valor6','comision_inmo_a_pagar','sellado_contrato','fecha_firma','escribano','rescinde_dentro','rescinde_fecha','estado_contrato','rescision');

			$crud->required_fields('idInmueble','locador','operacion','locatario1','garante1','fechaInicio','punitorio','comision_admin','fechaPago','duracion','valor1','tipo_ajuste','ajuste');			

			//aca se precarga el inmueble, el propietario y el valor	
			

					$crud->callback_edit_field('idInmueble', function ($value, $primary_key) {
						$idI=$value;
						//BUSCAR DIRECCION DEL INMUEBLE
						//$idI=$this->buscar_datos_model->buscar_idI($idC);
						$direccion_inmueble=$this->buscar_datos_model->buscar_inmueble($idI);

						$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);

						$cant_pagos=$datos_contrato['cant_pagos'];

						$idC=$this->buscar_datos_model->buscar_idC_idI($idI);

						$prox_venc=$this->buscar_datos_model->prox_venc($idC);

						$idE=$this->buscar_datos_model->buscar_idE($idI);
						if(isset($idE)){ 
							$A_edificio=$this->buscar_datos_model-> buscar_edificio($idE);
							$edificio=' - '.$A_edificio['edificio'];
							$barrio=', Barrio: '.$A_edificio['barrio'];
						}else{
							$edificio="";
							$barrio=" ";
						}	
						$combo = '<select id="field-idInmueble" name="idInmueble" class="chosen-select" data-placeholder="Seleccionar" value="Inmueble">';
						$fincombo = '</select>';
						$combo .= '<option value="'.$value.'">'.$direccion_inmueble.'</option>';

						$nro_pago='<span id="nro_pago" style="visibility:hidden">'.$cant_pagos.'</span>';

						$proxvencsig='<span id="venc_periodo" style="visibility:hidden">'.$prox_venc.'</span>';

						return $combo.$fincombo.$nro_pago.$proxvencsig;		
					});


					//se carga el combo de locador y el enlace de Añadir
					$crud->callback_edit_field('locador', function ($value, $primary_key) {
						$dni=$value;
						$combo = '<select id="field-locador" name="locador" class="chosen-select" data-placeholder="Seleccionar Locatario" value="Locatario">';
						$fincombo = '</select>';

						$locador=$this->buscar_datos_model->buscar_persona($dni);

						$combo .= '<option value="'.$dni.'">'.$locador.'</option>';

							return $combo.$fincombo;				
						
					});	

					//se carga el combo de locatario y el enlace de Añadir
					$crud->callback_edit_field('locatario1', function ($value, $primary_key) {
						$dni=$value;
						$locatario1=$this->buscar_datos_model->buscar_persona($dni);
						$combo = '<select id="field-locatario1" name="locatario1" class="chosen-select" data-placeholder="Seleccionar"><option value="'.$dni.'">'.$locatario1.'';
						$fincombo = '</select>';
												
						$this->db->select('dni,apellidoNombre');
						$this->db->from('personas');
						$this->db->order_by('apellidoNombre','asc');							
						$query=$this->db->get();
						foreach ($query->result() as $row){
								$combo .= '</option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
								}						
						return $combo.$fincombo.'&nbsp;&nbsp;<a href="'.base_url('Barrio/barrio').'"> Añadir</a>';			
						
					});

					//se carga el combo de locatario y el enlace de Añadir
					$crud->callback_edit_field('locatario2', function ($value, $primary_key) {
						$dni=$value;
						$locatario2=$this->buscar_datos_model->buscar_persona($dni);
						$combo = '<select id="field-locatario2" name="locatario2" class="chosen-select" data-placeholder="Seleccionar"><option value="'.$dni.'">'.$locatario2.'';
						$fincombo = '</select>';
												
						$this->db->select('dni,apellidoNombre');
						$this->db->from('personas');
						$this->db->order_by('apellidoNombre','asc');							
						$query=$this->db->get();
						foreach ($query->result() as $row){
								$combo .= '</option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
								}						
						return $combo.$fincombo;			
						
					});	

					$crud->callback_edit_field('garante1', function ($value, $primary_key) {
						$dni=$value;
						$garante1=$this->buscar_datos_model->buscar_persona($dni);
						$combo = '<select id="field-garante1" name="garante1" class="chosen-select" data-placeholder="Seleccionar"><option value="'.$dni.'">'.$garante1.'';
						$fincombo = '</select>';
												
						$this->db->select('dni,apellidoNombre');
						$this->db->from('personas');
						$this->db->order_by('apellidoNombre','asc');							
						$query=$this->db->get();
						foreach ($query->result() as $row){
								$combo .= '</option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
								}						
						return $combo.$fincombo;			
						
					});

					$crud->callback_edit_field('garante2', function ($value, $primary_key) {
						$dni=$value;
						$garante2=$this->buscar_datos_model->buscar_persona($dni);
						$combo = '<select id="field-garante2" name="garante2" class="chosen-select" data-placeholder="Seleccionar"><option value="'.$dni.'">'.$garante2.'';
						$fincombo = '</select>';
												
						$this->db->select('dni,apellidoNombre');
						$this->db->from('personas');
						$this->db->order_by('apellidoNombre','asc');							
						$query=$this->db->get();
						foreach ($query->result() as $row){
								$combo .= '</option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
								}						
						return $combo.$fincombo;			
						
					});

					$crud->callback_edit_field('garante3', function ($value, $primary_key) {
						$dni=$value;
						$garante3=$this->buscar_datos_model->buscar_persona($dni);
						$combo = '<select id="field-garante3" name="garante3" class="chosen-select" data-placeholder="Seleccionar"><option value="'.$dni.'">'.$garante3.'';
						$fincombo = '</select>';
												
						$this->db->select('dni,apellidoNombre');
						$this->db->from('personas');
						$this->db->order_by('apellidoNombre','asc');							
						$query=$this->db->get();
						foreach ($query->result() as $row){
								$combo .= '</option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
								}						
						return $combo.$fincombo;			
						
					});

					$crud->callback_edit_field('garante4', function ($value, $primary_key) {
						$dni=$value;
						$garante4=$this->buscar_datos_model->buscar_persona($dni);
						$combo = '<select id="field-garante4" name="garante4" class="chosen-select" data-placeholder="Seleccionar"><option value="'.$dni.'">'.$garante4.'';
						$fincombo = '</select>';
												
						$this->db->select('dni,apellidoNombre');
						$this->db->from('personas');
						$this->db->order_by('apellidoNombre','asc');							
						$query=$this->db->get();
						foreach ($query->result() as $row){
								$combo .= '</option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
								}						
						return $combo.$fincombo;			
						
					});	

					$crud->callback_edit_field('duracion', function ($value, $primary_key) {
						$lapso = '<input id="field-duracion" name="duracion" type="text" value="'.$value.'" maxlength="2" class="numerico" style="width:40px;height:30px" class="numeric form-control" onchange="fin_contato()" onclick="vaciar_input(this.id)" required>';				
						return $lapso;
					});	

					$crud->callback_edit_field('fechaPago', function ($value, $primary_key) {
						$paga = '<input id="field-fechaPago" name="fechaPago" type="text" value="'.$value.'" maxlength="10" class="numerico" style="width:30px;height:30px" onchange="proxvenc()" onclick="vaciar_input(this.id)" onfocus="vaciar_input(this.id)" />';							
						return $paga;
					});

					$crud->callback_edit_field('fechaFin', function ($value, $primary_key) {
						$fecha_fin = '<input id="field-fechaFin" name="fechaFin" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onclick="fin_contato()" />'.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<label id="mensaje" style="color:red"></label>';	
						return $fecha_fin;
					});					


					$crud->callback_edit_field('proxVenc', function ($value, $primary_key) {
						$prox_venc = '<input id="field-proxVenc" name="proxVenc" type="text"  maxlength="10" style="width:100px;height:30px" value="'.$value.'" onclick="proxvenc()" />';	
						return $prox_venc;
					});

					$crud->callback_edit_field('punitorio', function ($value, $primary_key) {
						$punitorio = '<input id="field-punitorio" name="punitorio" type="text" value="'.$value.'" maxlength="3" style="width:40px;height:30px" class="numerico" onclick="vaciar_input(this.id)" />';	
						return $punitorio;
					});

					$crud->callback_edit_field('comision_admin', function ($value, $primary_key) {
						$comi_admin = '<input id="field-comision_admin" name="comision_admin" type="text" value="'.$value.'" maxlength="3" style="width:40px;height:30px" class="numerico" onclick="vaciar_input(this.id)" />';	
						return $comi_admin;
					});

					$crud->callback_edit_field('tipo_ajuste', function ($value, $primary_key) {
						$combo = '<select id="field-tipo_ajuste" name="tipo_ajuste" class="chosen-select" data-placeholder="Seleccionar Ajuste">
								  <option value=""></option>';
							if($value=="SEMESTRAL"){	  
								  $combo.='<option value="SEMESTRAL" selected>SEMESTRAL</option>
								  			<option value="OCTOMESTRAL">OCTOMESTRAL</option>
								  			<option value="ANUAL">ANUAL</option>	
								  			<option value="SIN AJUSTE">SIN AJUSTE</option>';
								  			
							}	  
							if($value=="OCTOMESTRAL"){
								  $combo.='<option value="OCTOMESTRAL" selected>OCTOMESTRAL</option>
								  			<option value="SEMESTRAL">SEMESTRAL</option>
								  			<option value="ANUAL">ANUAL</option>	
								  			<option value="SIN AJUSTE">SIN AJUSTE</option>';
							}
							if($value=="ANUAL"){	  
								  $combo.='<option value="ANUAL" selected>ANUAL</option>
								  			<option value="OCTOMESTRAL">OCTOMESTRAL</option>
								  			<option value="SEMESTRAL">SEMESTRAL</option>	
								  			<option value="SIN AJUSTE">SIN AJUSTE</option>';
							}
							if($value=="SIN AJUSTE"){	  
								  $combo.='<option value="SIN AJUSTE" selected>SIN AJUSTE</option>
								  			<option value="OCTOMESTRAL">OCTOMESTRAL</option>
								  			<option value="SEMESTRAL">SEMESTRAL</option>	
								  			<option value="ANUAL">ANUAL</option>';
							}	  

							$fin='</select>'.'<span id=mensaje_tipo_ajuste style=color:red></span>';

							return $combo.$fin;
					});					

					$crud->callback_edit_field('ajuste', function ($value, $primary_key) {
						$ajuste = '  <input id="field-ajuste" name="ajuste" type="text" value="'.$value.'" maxlength="4" style="width:50px;height:30px" class="numeric form-control" onclick="vaciar_input(this.id)"/>';	
						return $ajuste;
					});

					$crud->callback_edit_field('valor1', function ($value, $primary_key) {
						/*$idC=$primary_key;

						$idI=$this->buscar_datos_model->buscar_idI($idC);
						$this->db->select('valor');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$idI);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$valor=$row->valor;
						}*/

						//verificar si tiene reserva el inmueble						
						/*$datos_reserva=$this->buscar_datos_model->buscar_datos_reserva($idI);
						if(!empty($datos_reserva)){
							$sena=' <b>Seña por reserva:</b> '.$datos_reserva['sena'].', debe abonar: <b style="color:red;; font-size:16px">'.($valor-$datos_reserva['sena']).'</b>';
						}else{
							$sena="";
						}						

						if(!isset($valor)) $valor="0";*/

						return '<input id="field-valor1" name="valor1" type="text" value='.$value.' class="numeric form-control" maxlength="8" style="width:80px;height:30px"/>';//.$sena
					});	

					$crud->callback_edit_field('valor2', function ($value, $primary_key) {
						return '<input id="field-valor2" name="valor2" type="text" value="'.$value.'" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id),vaciar_valores()"/>'.'&nbsp<span id=valor2><span/>';
					});	

					$crud->callback_edit_field('valor3', function ($value, $primary_key) {
						return '<input id="field-valor3" name="valor3" type="text" value="'.$value.'" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor3><span/>';
					});	

					$crud->callback_edit_field('valor4', function ($value, $primary_key) {
						return '<input id="field-valor4" name="valor4" type="text" value="'.$value.'" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor4><span/>';
					});

					$crud->callback_edit_field('valor5', function ($value, $primary_key) {
						return '<input id="field-valor5" name="valor5" type="text" value="'.$value.'" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor5><span/>';
					});

					$crud->callback_edit_field('valor6', function ($value, $primary_key) {
						return '<input id="field-valor6" name="valor6" type="text" value="'.$value.'" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>'.'&nbsp<span id=valor6><span/>';
					});	

					$crud->callback_edit_field('comision_inmo_a_pagar', function ($value, $primary_key) {
						$comision_a_pagar = '<input id="field-comision_inmo_a_pagar" name="comision_inmo_a_pagar" value="'.$value.'" type="text"  maxlength="10" style="width:80px;height:30px" onfocus="calcular_comision(this)"/>';//
						return $comision_a_pagar;
					});	

					$crud->callback_edit_field('sellado_contrato', function ($value, $primary_key) {
						$idI=$this->uri->segment(4);
						//$valor1=$post_array['valor1'];	

						return '<input id="field-sellado_contrato" name="sellado_contrato" type="text" value="'.$value.'" class="numeric form-control" maxlength="8" style="width:80px;height:30px"/>';
					});										

					$crud->callback_edit_field('fecha_firma', function ($value, $primary_key) {
						$firma = '<input id="field-fecha_firma" name="fecha_firma" type="date" value="'.$value.'"  style="width:150px;height:30px"/>';				
						return $firma;
					});	

					$crud->callback_edit_field('escribano', function ($value, $primary_key) {
						$escribano = '  <input id="field-escribano" name="escribano" type="text" value="'.$value.'" maxlength="25" style="width:275px;height:30px"/>';	
						return $escribano;
					});	

					$crud->callback_edit_field('rescinde_dentro', function ($value, $primary_key){

						$rescinde_dentro='<input id="field-rescinde_dentro" name="rescinde_dentro" type="text" min="0" max="6" value="'.$value.'" maxlength="1" style="width:50px;height:30px"  onkeyup="calcular_periodo_rescision()" class="numerico"/>';

						return $rescinde_dentro.' meses ,ponga 0 para cancelar la rescisión';	
					}); //fin callback_add_field	


					$crud->callback_edit_field('rescinde_fecha', function ($value, $primary_key){
											
						$periodo='<input id="field-rescinde_fecha" type="text" maxlength="8" name="rescinde_fecha" value="'.$value.'" style="width:80px;height:30px"/>';

						return $periodo;
					});  //fin callback_add_field							

						

			$crud->set_crud_url_path(site_url('Comodato/comodato'));		
			

			$output = $crud->render();
			$this->_example_output($output);	

			/*if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}*/
		}

		public function primer_pago_update($post_array, $pk){
			//date_default_timezone_set('America/Argentina/Buenos_Aires');
			//$fecha=$post_array['fechaInicio'];
			//$primer_periodo=$this->buscar_datos_model->formato_fecha($fecha);

				$id =$post_array['idInmueble'];
				$valor1=$post_array['valor1'];

				/*$this->db->set('estado',1);//estado 1 es alquilado
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');*/

				$this->db->set('valor',$valor1);
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');			

				$this->db->set('reserva',0);
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');

				//actualizo cantidad de alquileres vigentes del locador
				$locador=$post_array['locador'];
				$this->db->set('pendientes',"NO");				
				$this->db->where('dni',$locador);
				$this->db->update('personas');

				return true;
		}

		public function fechaCreacion_Usuario_update($post_array, $pk){

			$post_array['estado_contrato']="VIGENTE";
			$post_array['estado_alquiler']="DEUDA";
			$post_array['pendientes']="SI";

			$valor3=$post_array['valor3'];
			$valor4=$post_array['valor4'];
			$valor5=$post_array['valor5'];
			$valor6=$post_array['valor6'];

			if($valor3=="") $post_array['valor3']="0";
			if($valor4=="") $post_array['valor4']="0";
			if($valor5=="") $post_array['valor5']="0";
			if($valor6=="") $post_array['valor6']="0";

			return $post_array;
		}						

		public function finalizar_contrato($idC){
			//$idC=$row->idContrato;
			$idI=$this->buscar_datos_model->buscar_idI($idC);	
			$estado=$this->buscar_datos_model->estado_contrato($idI);
			$reserva=$this->buscar_datos_model->buscar_reserva($idI);
			if($estado =="FINALIZA"){
				if($reserva==1){
					$this->db->set('estado',3);	
				}else{
					$this->db->set('estado',0);	
				}				
			}elseif($estado =="RENUEVA"){
				$this->db->set('estado',6);				
			}
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');


			$this->db->set('estado_contrato',"FINALIZADO");				
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');	

			redirect('Comodato/comodato');
		}

		public function cancelar_renueva($idC){
			$idI=$this->buscar_datos_model->buscar_idI($idC);
			$this->db->set('estado_contrato','FINALIZA');
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');

			$this->db->set('estado',5);
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');

			redirect('Comodato/comodato');
		}

		function _example_output($output = null){
			$this->load->view('inicio',(array)$output);			
		}						



	}	