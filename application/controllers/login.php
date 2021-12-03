<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller{
		public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		// load Session Library
        $this->load->library('session');
		$this->load->library('grocery_CRUD');

        $this->load->helper('form');
        $this->load->library('form_validation');		
	}

	public function index(){
		if($this->session->userdata('usuario')){
			redirect('Inicio');
		}

		if(isset($_GET['usuario'])){
			$this->load->model('usuario_model');
			$this->load->model('buscar_datos_model');
			/*if($this->usuario_model->login($_GET['usuario'],md5($_GET['clave']))){*/
			$perfil=$this->usuario_model->login($_GET['usuario'],$_GET['clave']);

			if($this->usuario_model->login($_GET['usuario'],$_GET['clave'])){

				$NyA=$this->buscar_datos_model->buscar_nombre_usuario($_GET['usuario'],$_GET['clave']);

				$perfil=$this->usuario_model->login($_GET['usuario'],$_GET['clave']);

				$sesion[0]=$perfil[0];
				$sesion[1]=$_GET['usuario'];
				$sesion[2]=$NyA;
				$sesion[3]=$perfil[1];									

				$this->session->set_userdata('usuario',$sesion);				
				
				redirect('Main');			

			}else{
				//$this->form_validation->set_message('verifica','Contraseña incorrecta');
				redirect('login');				
			}
		}

		$this->load->view('login');
	}

	public function logout(){
		//$this->load->library('session');
		//$this->session->unset('usuario');
		$this->session->sess_destroy();
		redirect('login', 'refresh');
	}
}

?>