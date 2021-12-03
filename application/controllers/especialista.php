<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Especialista extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('session');
	}
	/*public function index(){			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
	}*/

	public function especialista(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);		

		$crud=new grocery_CRUD();				
		$crud->set_theme('bootstrap');
		$crud->set_table('tecnicos');		
		$crud->set_subject('Técnico');
	

		$output = $crud->render();


			$this->_example_output($output);
			
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


















	