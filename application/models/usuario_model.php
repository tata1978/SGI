<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model{

	public $variable;

	public function __construct(){
		parent::__construct();
	}	

	public function login($usuario, $clave){
		$this->db->select('*');

		$this->db->from('usuarios');

		$this->db->where('nombreUsuario',$usuario);
		$this->db->where('clave',$clave);



		$query=$this->db->get();

		//$result = $this->db->get('usuarios');
		foreach ($query->result() as $row) {
			$rol=$row->tipoU;
		}
		if ($query->num_rows()>0){
			$desc_rol=$this->buscar_desc_rol($rol);
			$perfil[0]=$rol;
			$perfil[1]=$desc_rol;
			return $perfil;
		}
	}

	public function buscar_desc_rol($rol){
		$this->db->select('*');
		$this->db->from('tipousuarios');
		$this->db->where('idTU',$rol);
		$query=$this->db->get();

		foreach ($query->result() as $row) {
			$desc_rol=$row->tipousuario;
		}		
		return $desc_rol;	
	}

	public function buscar_nombre($usuario){
		$this->db->select('NyA');
		$this->db->from('usuarios');
		$this->db->where('nombreUsuario',$usuario);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$nombre = $row->AyN;
		}
		return $nombre;	
	}	
}