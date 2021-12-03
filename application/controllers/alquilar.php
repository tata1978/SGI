<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Alquilar extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
	}

	public function alquilar(){
			
		$crud->callback_after_insert(array($this, 'estado_alquiler'));
		
	}

	function estado_alquiler(){			
		$this->db->SET('estado','ALQUILADO');
	}	



}	

