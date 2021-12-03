<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class reportes_model extends CI_Model{

	public $variable;

	public function __construct(){
		parent::__construct();
	}

	public function reportes(){
		$this->db->select('tipoProblema,count(*)');
		$this->db->from('reclamos');
		$this->db->group_by('tipoProblema');
		$query=$this->db->get();
		return $query;

	}
}