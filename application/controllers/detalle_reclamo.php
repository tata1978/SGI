<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Detalle_reclamo extends CI_Controller{	

		public function __construct(){			
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('grocery_CRUD');
			$this->load->library('session');

		}

		public function index(){			
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
		}

		public function detalle_reclamo(){
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('detalle_reclamos');	
			$crud->set_subject('Detalle_reclamo');	

			$crud->set_relation('idReclamo','reclamos','idReclamo');

			$output = $crud->render();	
			$this->_example_output($output);
		}

		function _example_output($output = null){
			$this->load->view('inicio',(array)$output);			
		}

	}	