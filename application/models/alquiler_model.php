<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Alquiler_model extends CI_Model{

	public $variable;

	public function __construct(){
		parent::__construct();
	}	

	public function eliminar($id){
		$this->db->where('idContrato',$id);
		$this->db->delete('alquileres');	

	}	
}