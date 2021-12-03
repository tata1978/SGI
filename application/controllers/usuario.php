<?php 
class Usuario extends CI_Controller{

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

	public function usuario(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);	

		$output = $this->usuario_management();		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

	public function usuario_management(){
		$crud = new grocery_CRUD();
		$crud->set_table('usuarios');
		$crud->set_subject('Usuario');	

		$crud->set_relation('tipoU','tipousuarios','tipousuario');	
		
		$crud->required_fields('nombreUsuario','NyA','clave','idTU','tipoU');
		 
		$crud->columns('NyA','nombreUsuario','clave', 'tipoU');
		
		$crud->change_field_type('clave', 'password');	 
		
		$crud->display_as('NyA','Nombre y Apellido');
		$crud->display_as('tipoU','Tipo de usuario');

		$crud->display_as('nombreUsuario','Nombre de Usuario');

		$crud->callback_add_field('NyA', function () {
			$nombre = '<input id="field-NyA" name="NyA" type="text" value="" maxlength="50" style="width:275px;height:30px" required>';				
			return $nombre;
		});

		$crud->callback_add_field('nombreUsuario', function () {
			$usuario = '<input id="field-nombreUsuario" name="nombreUsuario" type="text" value="" maxlength="30" style="width:275px;height:30px" required>';				
			return $usuario;
		});	

		$crud->callback_add_field('clave', function () {
			$clave = '<input id="field-clave" name="clave" type="password" value="" maxlength="30" style="width:275px;height:30px" required>';				
			return $clave;
		});				
		 
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
		 
		function encrypt_password_callback($post_array) {
			$this->load->library('encrypt');
			$key = 'li01sa02';
			$post_array['clave'] = $this->encrypt->encode($post_array['clave'], $key);
			 
			return $post_array;
		}   
}	
