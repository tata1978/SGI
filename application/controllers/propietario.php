<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Propietario extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->config->load('grocery_crud');
		$this->load->library('session');
	}
	public function index(){			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
	}


	public function ver_alquileres($dni){
		$this->config->load('grocery_crud');		

		$output = $this->ver_alquileres_management($dni);	

		$this->db->select('apellidoNombre');
		$this->db->where('dni',$dni);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locador = $row->apellidoNombre;
		}

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "<div class='texto' style='display:none;' >$locador</div>".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

	public function ver_alquileres_management($dni){
		$crud=new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('alquileres');	
		$crud->set_subject('Alquiler');
		//$crud->set_relation('idInmueble','inmuebles','{direccion} - {piso} - {depto}');			

		/*$crud->set_relation('locatario','personas','apellidoNombre');
		$crud->set_relation('garante','personas','apellidoNombre');				
		$crud->set_relation('locador','personas','apellidoNombre');*/

		$crud->where('locador',$dni);
		//$crud->or_where('estado_contrato','VIGENTE');
		
		//$crud->where('pendientes','SI');

		

		$crud->unset_operations();

		$crud->columns('idContrato','idInmueble','edificio','locatario1','fechaInicio','fechaFin','estado_contrato','pendientes');

		$crud->display_as('idInmueble','Inmueble');
		$crud->display_as('idContrato','idC');
		$crud->display_as('fechaInicio','Inicio');
		$crud->display_as('fechaFin','Fin');
		$crud->display_as('estado_contrato','Estado');
		$crud->display_as('pendientes','Liquidac. pendientes');

		$crud->callback_column('idInmueble',array($this,'buscar_inmueble'));
		$crud->callback_column('edificio',array($this,'buscar_edificio'));
		$crud->callback_column('locatario1',array($this,'buscar_locatario'));
		$crud->callback_column('pendientes',array($this,'buscar_pagos'));
		$crud->callback_column('estado_contrato',array($this,'estado_contrato'));

		$crud->add_action('Detalle', '', 'Liquidacion/pagos','ui-icon-calculator');
		//$crud->add_action('Liquidac. anteriores', '', 'Liquidacion/liquidaciones_anteriores','ui-icon-calculator');
		$crud->add_action('Liquidac. anteriores', '', 'Liquidacion/liquidar','ui-icon-calculator');
		
		$output = $crud->render();
		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}			
		
	}

	public function estado_contrato($value,$row){
		if($value=="FINALIZA"){
			return '<b style=color:red>'.$value.'</br>';
		}elseif($value=="FINALIZADO"){
			return '<b style=color:red>'.$value.'</br>';
		}elseif($value=="VIGENTE"){
			return '<b style=color:green>'.$value.'</br>';
		}elseif($value=='RENUEVA'){
			return '<b style="color:#FF5733">'.$value.'</b>';
		}elseif($value=='RESCINDIDO'){
			return '<b style="color:red">'.$value.'</b>';
		}
	}

	public function buscar_inmueble($idI){
		$this->db->select('direccion,piso,depto,idEdificio');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);			
		$query=$this->db->get();			
		foreach ($query->result() as $row){
			$direccion = $row->direccion;
			$piso = $row->piso;			
			$depto = $row->depto;
			$idE=$row->idEdificio;
		}
			$this->load->model('buscar_datos_model');
			$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
		return $direccion;
	}


	public function buscar_edificio($value, $row){
		$idC=$row->idContrato;
		$idI=$this->buscar_datos_model->buscar_idI($idC);
		$idE=$this->buscar_datos_model->buscar_idE($idI);
		if(isset($idE)){
			$edificio=$this->buscar_datos_model->buscar_edificio($idE);
			$nombreE=$edificio['edificio'];
		}else{
			$nombreE=$value;
		}	
		return $nombreE;

	}

	public function buscar_locatario($dni){	

		$this->db->select('apellidoNombre');
		$this->db->from('personas');
		$this->db->where('dni',$dni);
		$query=$this->db->get();			
		foreach ($query->result() as $row){
			$locatario_nombre = $row->apellidoNombre;			
		}
		return $locatario_nombre;
	}

	public function buscar_pagos($value,$row){	
		$idC=$row->idContrato;
		$this->db->select('idpago');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('pagado_propietario',"NO");

		$query=$this->db->get();
		$cant_pagos=$query->num_rows();	

		if($cant_pagos>0)$cant_pagos='<span style="background: red" class="badge badge-pill">'.$cant_pagos.'</span>';
		
		return $cant_pagos;
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