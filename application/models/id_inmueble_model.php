<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Id_Inmueble_model extends CI_Model{

	public $variable;

	public function __construct(){
		parent::__construct();
	}	

	public function buscar_idI($idC){
		$this->db->select('idInmueble');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idI = $row->idInmueble;
		}
		return $idI;
	}	
}