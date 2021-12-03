<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Edificio extends CI_Controller{	

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

	public function edificio(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);	

		$output = $this->edificio_management();		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}	
	public	function edificio_management(){

		$crud=new grocery_CRUD();				
		//$crud->set_theme('bootstrap');
		$crud->set_table('edificios');			
		$crud->set_subject('Edificio');
		$crud->set_relation('idBarrio','barrios','nombreBarrio');

		$crud->order_by('descEdificio','asc');

		$crud->required_fields('descEdificio,direccion');

		$crud->display_as('idBarrio','Barrio');
		$crud->display_as('descEdificio','Nombre Edificio');		
			
		

		$crud->callback_add_field('idBarrio', function () {
			$combo = '<select id="field-idBarrio" name="idBarrio" class="chosen-select" data-placeholder="Seleccionar Barrio">';
			$fincombo = '</select>';

			$this->db->select('idBarrio,nombreBarrio');
			$this->db->from('barrios');
			$this->db->order_by('nombreBarrio','asc');							
			$query=$this->db->get();
			foreach ($query->result() as $row){
					$combo .= '<option value=""></option><option value="'.$row->idBarrio.'">'.$row->nombreBarrio.'</option>';
					}						
			return $combo.$fincombo.'&nbsp;&nbsp;<a href="'.base_url('Barrio/barrio').'"> Añadir</a>';
				//<a href="javascript:void(window.open('MI_URL'));" rel="nofollow">TEXTO</a>
				 //<a href="'.base_url('persona/persona_management/add').'"> Añadir</a>
		});//fin callback_add_field



		$crud->callback_add_field('descEdificio', function () {					
			$edificio='<input id="field-descEdificio"   name="descEdificio" type="text"  maxlength="50" style="width:250px;height:30px" />';
			return $edificio;	
		});//fin callback_add_field	

		$crud->callback_add_field('direccion', function () {					
			$direccion='<input id="field-direccion"   name="direccion" type="text"  maxlength="100" style="width:400px;height:30px" />';
			return $direccion;	
		});//fin callback_add_field		



		$crud->callback_edit_field('descEdificio', function ($value,$id) {					
			$edificio='<input id="field-descEdificio"   name="descEdificio" type="text" value="'.$value.'"  maxlength="50" style="width:250px;height:30px" />';
			return $edificio;	
		});//fin callback_add_field	

		$crud->callback_edit_field('direccion', function ($value,$id) {					
			$direccion='<input id="field-direccion"   name="direccion" type="text" value="'.$value.'" maxlength="100" style="width:400px;height:30px" />';
			return $direccion;	
		});//fin callback_add_field	


		$crud->callback_before_delete(array($this,'verificar_inmuebles'));	


		$crud->set_lang_string('delete_error_message','Hay Inmuebles asociados al Edificio, verifique!!');						


		$output = $crud->render();
		//$this->_example_output($output);
		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}
		function _example_output($output = null){
			$login= $this->session->userdata('usuario');
			if($login){
				$this->load->view('inicio',(array)$output);		
			}else{
				redirect('login');
			}				
		}


	public function verificar_inmuebles($idE){
			//SE VERIFICA QUE NO HAYA INMUEBLES ASOCIADO AL EDIFICIO QUE SE INTENTA ELIMINAR 			

			$verificar=$this->buscar_datos_model->verificar_inmuebles($idE);

			if($verificar=="SI"){							
				return false;
			}else{
				return true; 
			}
	}		

}			