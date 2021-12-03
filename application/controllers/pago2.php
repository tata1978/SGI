<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Pago extends CI_Controller{	

		public function __construct(){			
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('grocery_CRUD');
		}

		public function index(){			
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
		}

		public function pagar(){
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');	

			$crud->set_relation('idContrato','alquileres','locatario');
			$crud->required_fields('idContrato');

			$crud->fields('idContrato','fechaUltimoPago','periodo','mora_dias','paga_mora','punitorios','expensas','csp','luz','agua','saldos_otros','total_pagar','observaciones','fecha_pago','usuario');

			$output = $crud->render();	
			$this->_example_output($output);
		}		


			function _example_output($output = null){
				$this->load->view('inicio',(array)$output);			
			}	
}					