<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller{

		public function __construct(){			
			parent::__construct();

		$this->load->library('session');
		}


	public function index(){	
			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		//$output = $crud->render();	

		//$this->_example_output($output);
		//$this->load->view('inicio');
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