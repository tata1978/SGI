<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tecnico extends CI_Controller{

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

	public function tecnico(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);		

		$output = $this->tecnico_management();

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

	 function tecnico_management(){
		$crud=new grocery_CRUD();				
		$crud->set_theme('datatables');
		$crud->set_table('tecnicos');		
		$crud->set_subject('Técnico');

$crud->set_lang_string('insert_success_message',
                 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
                 <script type="text/javascript">
                  window.location = "'.site_url('Tecnico/tecnico').'";
                 </script>
                 <div style="display:none">
                 '
   );	

   $crud->set_lang_string('update_success_message',
		 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
		 <script type="text/javascript">
		  window.location = "'.site_url('Tecnico/tecnico').'";
		 </script>
		 <div style="display:none">
		 '
   );   	

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
}	


















	