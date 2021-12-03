<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Barrio extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
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


	public function barrio(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);		

		$output = $this->barrio_management();		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}
	public function barrio_management(){			
			$crud=new grocery_CRUD();		
			//$crud->set_theme('bootstrap');
			$crud->set_table('barrios');	
			$crud->set_subject('Barrio');	
			$crud->display_as('nombreBarrio','Nombre de Barrio');
			$crud->required_fields('nombreBarrio');

		$crud->callback_add_field('nombreBarrio', function () {					
			$barrio='<input id="field-nombreBarrio"   name="nombreBarrio" type="text"  maxlength="100" style="width:400px;height:30px" />';
			return $barrio;	
		});//fin callback_add_field	

		$crud->callback_edit_field('nombreBarrio', function ($value,$id) {					
			$barrio='<input id="field-nombreBarrio"   name="nombreBarrio" type="text" value="'.$value.'"  maxlength="100" style="width:400px;height:30px" />';
			return $barrio;	
		});//fin callback_add_field	


		$crud->callback_before_delete(array($this,'verificar_inmuebles'));	


		$crud->set_lang_string('delete_error_message','Hay Inmuebles y/o Edificios asociados al Barrio, verifique!!');		

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

	public function verificar_inmuebles($idB){
			//SE VERIFICA QUE NO HAYA INMUEBLES ASOCIADO AL BARRIO QUE SE INTENTA ELIMINAR 			

			$verificar=$this->buscar_datos_model->verificar_inmuebles_barrio($idB);

			if($verificar=="SI"){							
				return false;
			}else{
				return true; 
			}
	}	
}