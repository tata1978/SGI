<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Direccion_Inmueble_model extends CI_Model{

	public $variable;

	public function __construct(){
		parent::__construct();
	}	

	public function buscar_inmueble($idI){
		$this->db->select('direccion,piso,depto,dni,idTipoInmueble');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$direccion = $row->direccion;
			$piso=$row->piso;			
			$depto=$row->depto;
			$dni_locador=$row->dni;
			$id_tipoinmueble=$row->idTipoInmueble;
		}
		$direccion_inmueble=$direccion.' - '.$piso.' - '.$depto;
		return $direccion_inmueble;
	}	
}