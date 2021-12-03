<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Persona extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->config->load('grocery_crud');
		$this->load->model('buscar_datos_model');
		$this->load->helper('numeros');
		$this->load->library('session');
	}
	public function index(){			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
	}

	public function persona(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);	

		$output = $this->persona_management();		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}	
		function persona_management(){

			$crud=new grocery_CRUD();				
			$crud->set_theme('datatables');
			$crud->set_table('personas');		
			$crud->set_subject('Persona');	
	

			$crud->set_relation('id_estadocivil','estadocivil','estadocivil_descripcion');

			$crud->order_by('apellidoNombre','asc');

			$crud->required_fields('dni','apellidoNombre');

			$dni=$this->uri->segment(4);

			if(is_numeric($dni)){
				$doc=$post_array['dni'];
				$crud->set_rules('doc','DNI','callback_verificar_duplicado_dni_edit');
			}else{
				$crud->set_rules('dni','DNI','callback_verificar_duplicado_dni');
			}	

			

			$crud->columns('dni','apellidoNombre','cuit_cuil','direccion','telefono','celular','email');

			$crud->display_as('id_estadocivil','Estado Civil');
			$crud->display_as('cuit_cuil','CUIT-L');
			$crud->display_as('apellidoNombre','Apellido y Nombre');
			$crud->display_as('dni','DNI/CUIT');
			$crud->display_as('vigentes','Alquileres');	
			$crud->display_as('pendientes','Pagos pendientes');

			$crud->fields('dni','apellidoNombre','fechaNac','id_estadocivil','cuit_cuil','direccion','telefono','celular','email');



			$crud->callback_add_field('dni', function () {					
				$dni='<input id="field-dni" name="dni" type="number" maxlength="12" placeholder="Obligatorio" style="width:150px;height:30px" />';
				return $dni.' - SI ES CUIT PONER SIN GUION';	
			});//fin callback_add_field	

			$crud->callback_add_field('apellidoNombre', function () {					
				$nya='<input id="field-apellidoNombre"   name="apellidoNombre" type="text"  maxlength="50" placeholder="Obligatorio" style="width:350px;height:30px" />';
				return $nya;	
			});//fin callback_add_field				

			$crud->callback_add_field('fechaNac', function () {					
				$fn='<input id="field-fechaNac"   name="fechaNac" type="date"  maxlength="10" style="width:150px;height:30px" />';
				return $fn;	
			});//fin callback_add_field	

			$crud->callback_add_field('cuit_cuil', function () {					
				$cuit_cuil='<input id="field-cuit_cuil"   name="cuit_cuil" type="text"  maxlength="14" style="width:150px;height:30px" />';
				return $cuit_cuil;	
			});//fin callback_add_field					

			$crud->callback_add_field('direccion', function () {					
				$direccion='<input id="field-direccion"   name="direccion" type="text"  maxlength="150" style="width:600px;height:30px" />';
				return $direccion;	
			});//fin callback_add_field	

			$crud->callback_add_field('telefono', function () {					
				$telefono='<input id="field-telefono"   name="telefono" type="text"  maxlength="20" style="width:150px;height:30px" />';
				return $telefono;	
			});//fin callback_add_field				

			$crud->callback_add_field('celular', function () {					
				$celular='<input id="field-celular"   name="celular" type="text"  maxlength="20" style="width:150px;height:30px" />';
				return $celular;	
			});//fin callback_add_field	

			$crud->callback_add_field('email', function () {					
				$email='<input id="field-email" name="email" type="email"  maxlength="30" style="width:250px;height:30px" />';
				return $email;	
			});//fin callback_add_field					

			////////////////////////////////////////////////
			////////////EDITAR PERSONA//////////////////////
			$crud->callback_edit_field('dni', function ($value, $primary_key) {					
				$dni='<input id="field-dni"   name="dni" type="number" value="'.$value.'"  maxlength="15" placeholder="Obligatorio" style="width:150px;height:30px" />';
				return $dni;	
			});//fin callback_add_field	

			$crud->callback_edit_field('apellidoNombre', function ($value, $primary_key) {					
				$nya='<input id="field-apellidoNombre"   name="apellidoNombre" type="text" value="'.$value.'" maxlength="50" placeholder="Obligatorio" style="width:350px;height:30px" />';
				return $nya;	
			});//fin callback_add_field				

			$crud->callback_edit_field('fechaNac', function ($value, $primary_key) {					
				$fn='<input id="field-fechaNac"   name="fechaNac" type="date" value="'.$value.'"  maxlength="10" style="width:150px;height:30px" />';
				return $fn;	
			});//fin callback_add_field	

			$crud->callback_edit_field('cuit_cuil', function ($value, $primary_key) {					
				$cuit_cuil='<input id="field-cuit_cuil"   name="cuit_cuil" type="text" value="'.$value.'"  maxlength="14" style="width:150px;height:30px" />';
				return $cuit_cuil;	
			});//fin callback_add_field					

			$crud->callback_edit_field('direccion', function ($value, $primary_key) {					
				$direccion='<input id="field-direccion"   name="direccion" type="text" value="'.$value.'"  maxlength="150" style="width:650px;height:30px" />';
				return $direccion;	
			});//fin callback_add_field	

			$crud->callback_edit_field('telefono', function ($value, $primary_key) {					
				$telefono='<input id="field-telefono"   name="telefono" type="text" value="'.$value.'"  maxlength="20" style="width:150px;height:30px" />';
				return $telefono;	
			});//fin callback_add_field				

			$crud->callback_edit_field('celular', function ($value, $primary_key) {					
				$celular='<input id="field-celular"   name="celular" type="text" value="'.$value.'"  maxlength="20" style="width:150px;height:30px" />';
				return $celular;	
			});//fin callback_add_field	

			$crud->callback_edit_field('email', function ($value, $primary_key) {					
				$email='<input id="field-email" name="email" type="email" value="'.$value.'" maxlength="30" style="width:250px;height:30px" />';
				return $email;	
			});//fin callback_add_field				

			///////////////////////////////////////////////////

			/*$crud->set_subject('Persona');
			$crud->set_crud_url_path(site_url(strtolower(__CLASS__."/".__FUNCTION__)),site_url(strtolower(__CLASS__."/persona")));*/

			$crud->callback_before_delete(array($this,'verificar_inmuebles'));
			


$crud->set_lang_string('insert_success_message',
                 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
                 <script type="text/javascript">
                  window.location = "'.site_url('Persona/persona').'";
                 </script>
                 <div style="display:none">
                 '
   );		

			$crud->set_lang_string('update_success_message','Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
				<script type="text/javascript">
					window.location = "'.site_url('Persona/persona').'";
				</script>
				<div style="display:none">'
		   	);	 


			$crud->set_lang_string('delete_error_message','Hay Inmuebles y/o Alquileres afectados a esta Pesrona, verifique!!');		   	  	

			$output = $crud->render();

			$state = $crud->getState();
		    /*if($state == 'add')
				
		    {	*/		
			
			if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}
		}


	public function verificar_inmuebles($primary_key){
			//SE VERIFICA QUE NO HAYA INMUEBLES Y/O ALQUILERES A NOMBRE DE ESA PERSONA 

			$dni=$primary_key;

			$verificar=$this->buscar_datos_model->verificar_inmuebles_alquileres($dni);

			if($verificar=="NO"){							
				return true;
			}elseif($verificar=="SI"){
				return false; 
			}
	}


	public function verificar_duplicado_dni($dni){
		$existe_dni=$this->buscar_datos_model->verifica_dni($dni);
		if($existe_dni>0){
			$this->form_validation->set_message('verificar_duplicado_dni','DNI/CUIT ya existente, verifique!!!');
			return false;			
		}else{
			return true;
		}
	}	

	public function verificar_duplicado_dni_edit($doc){
		$existe_dni=$this->buscar_datos_model->verifica_dni_edit($doc);
		if($existe_dni>0){
			$this->form_validation->set_message('verificar_duplicado_dni_edit','DNI/CUIT ya existente, verifique!!!');
			return false;			
		}else{
			return true;
		}
	}	

	public function ver_propietarios(){
		$this->config->load('grocery_crud');
		$crud=new grocery_CRUD();
		
		$crud->set_table('personas');	
		$crud->set_subject('Persona');		
		
		//$crud->set_relation('dni','alquileres','locador'); 
		$crud->where('tipo_persona','PROPIETARIO');

		$crud->columns('apellidoNombre','cuit_cuil','celular','propiedades','vigentes','pendientes');

		$crud->display_as('apellidoNombre','Propietario');
		$crud->display_as('cuit_cuil','CUIT/L');
		$crud->display_as('pendientes','¿Pagos Pendientes?');
		$crud->display_as('vigentes','Alquileres vigentes');		

		$crud->unset_operations();

		$crud->callback_column('pendientes',array($this,'buscar_pagos'));
		$crud->callback_column('vigentes',array($this,'buscar_alquiler'));
		$crud->callback_column('propiedades',array($this,'buscar_propiedad'));

		$crud->add_action('Ver Alquileres', '','Propietario/ver_alquileres',' ui-icon-search');	

		$output = $crud->render();
		$this->_example_output($output);
	
	}

	public function buscar_pagos($value,$row){
		$dni=$row->dni;		
		$this->db->select('*');
		$this->db->from('pagos');		
		$this->db->where('pagado_propietario',"NO");
		$this->db->where('locador',$dni);

		$query=$this->db->get();
		$cant_pagos=$query->num_rows();
		
		return $cant_pagos;		
	}


	public function buscar_alquiler($value,$row){
		$dni=$row->dni;
		$this->db->select('idContrato');
		$this->db->from('alquileres');
		$this->db->where('locador',$dni);
		$this->db->where('estado_contrato','VIGENTE');

		$query=$this->db->get();
		$cant_alquiler=$query->num_rows();
		
		if($cant_alquiler == 0){
			$this->db->set('vigentes',"0");
			$this->db->set('pendientes'," ");
			$this->db->where('dni',$dni);
			$this->db->update('personas');
		}
		return $cant_alquiler;
	}

	public function buscar_propiedad($value,$row){
		$dni=$row->dni;
		$this->db->select('idInmueble');
		$this->db->from('inmuebles');
		$this->db->where('dni',$dni);

		$query=$this->db->get();
		$cant_propiedad=$query->num_rows();	
		
		if($cant_propiedad == 0 ){
			$this->db->set('propiedades',"0");
			$this->db->set('tipo_persona'," ");
			$this->db->where('dni',$dni);
			$this->db->update('personas');
		}
		return $cant_propiedad;
		
	}
		function _example_output($output = null){
			$login= $this->session->userdata('usuario');
			if($login){
				$this->load->view('inicio',(array)$output);		
			}else{
				redirect('login');
			}				
		}
}