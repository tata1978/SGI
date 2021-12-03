<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class BuscarDatos_model extends CI_Model{

	public $variable;

	public function __construct(){
		parent::__construct();
	}	

	public function cambiar_estado($id_inmueble){
		$this->db->set('estado','DISPONIBLE');
		$this->db->where('idInmueble',$id_inmueble);
		$this->db->update('inmuebles');
	}	
}