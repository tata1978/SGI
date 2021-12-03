<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion_edit extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->config->load('grocery_crud');
		$this->load->library('session');
	}	
	public function edit(){
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('liquidaciones');	
		$crud->set_subject('Liquidaciones');

		$crud->set_relation('idpago','pagos','periodo');

		$crud->set_relation('idContrato','alquileres','idContrato');
		$crud->set_relation('locador','personas','apellidoNombre');
		$crud->set_relation('locatario','personas','apellidoNombre');
		

		/*$idC=$this->uri->segment(3);
		$crud->where('liquidaciones.idContrato',$idC);*/

		//$crud->columns('idpago','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');

		$crud->edit_fields('idContrato','locador','locatario','idpago','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');
		$crud->display_as('idContrato','Inmueble');	
		$crud->display_as('idpago','Periodo');		
		$crud->display_as('comiAdmin','Comision por Administración');
		//$crud->display_as('comodin','<b><u>Descuentos al Propietario</b></u>');
		//$crud->display_as('comodin2','<b><u>Total a Pagar al Propietario</b></u>');
		$crud->display_as('descArreglos','Arreglos');
		$crud->display_as('expExtras','Expensas Extr.');
		$crud->display_as('totalPagar','Monto a Pagar');

		//$crud->field_type('periodo','invisible');

		$crud->required_fields('alquiler','totalPagar');		

		////////////////////////////////////////
		/////CUANDO SE EDITA LA LIQUIDACION////
		//////////////////////////////////////
		$crud->callback_edit_field('idContrato', function ($value,$id) {			
			//$this->config->set_item('miid', $value);
			//$this->session->set_flashdata('miid', $value);

			$this->db->select('idInmueble');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$value);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idI = $row->idInmueble;				
			}
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
			$this->db->select('nombreTipo');
			$this->db->from('tipoinmuebles');
			$this->db->where('idTipoInmueble',$id_tipoinmueble);		
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$tipo_inmueble = $row->nombreTipo;
			}
			$combo = $tipo_inmueble.' en :'.'<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto"><option value = "'.$value.'">'.strtoupper($direccion).' - '.$piso.' - '.$depto.'</option></select>';
			return $combo;
		});//cierro callback_edit_field		

		$crud->callback_edit_field('locador', function ($value,$id) {

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$value);							
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$dni = $row->dni;															
				$locador = $row->apellidoNombre;
			}
			return '<select id="field-locador" class="form-control"  name="locador"><option value="'.$dni.'">'.$locador.'</option></select>';	
		});//fin callback_edit_field

		$crud->callback_edit_field('locatario', function ($value,$id) {
			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$value);							
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$dni = $row->dni;															
				$locatario = $row->apellidoNombre;
			}
			return '<select id="field-locatario" class="form-control"  name="locatario"><option value="'.$dni.'">'.$locatario.'</option></select>';	
		});//fin callback_edit_field	

		$crud->callback_edit_field('idpago', function ($value,$id) {
			//$idP=$this->uri->segment(4);
			$this->db->select('periodo');
			$this->db->from('pagos');
			$this->db->where('idpago',$value);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$periodo = $row->periodo;															
			}
			
			return '<select id="field-idpago" class="chosen-select"  name="idpago"><option value="'.$value.'">'.$periodo.'</option></select>';
		});  //fin callback_edit_field	

		$crud->callback_edit_field('alquiler', function ($value,$id) {	
			$valor_alquiler = '<span id="b">$</span><input id="field-alquiler" name="alquiler" type="text" value="'.$value.'" maxlength="10" style="width:80px;height:30px"  />';						
			return $valor_alquiler;
		});//cierro callback_edit_field

		$crud->callback_edit_field('punitorios', function ($value,$id) {
			$input_punitorios = '<span id="b">$</span><input id="field-punitorios" name="punitorios" type="text" value="'.$value.'" maxlength="10" style="width:80px;height:30px"  />';									
			return $input_punitorios;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('comiAdmin', function ($value,$id) {			
			$input_comi_admin = '<span id="b">$</span><input id="field-comiAdmin" name="comiAdmin" type="text" value="'.$value.'" maxlength="10" style="width:80px;height:30px"/> ';			
			return $input_comi_admin;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('descArreglos', function ($value,$id) {			
			$desc_arreglos = '<span id="b">$</span><input id="field-descArreglos" name="descArreglos" type="text" value="'.$value.'" maxlength="5" style="width:80px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)"/>';
			$textarea='</br><textarea></textarea>';
			return $desc_arreglos;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('expExtras', function ($value,$id) {			
			$exp_extras = '<span id="b">$</span><input id="field-expExtras" name="expExtras" type="text" value="'.$value.'" maxlength="5" style="width:80px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)"/>';
			return $exp_extras;			
		});//cierro callback_edit_field	


		$crud->callback_edit_field('totalPagar', function ($value,$id) {			
			$total = '<span id="b">$</span><input id="field-totalPagar" name="totalPagar" type="text" value="'.$value.'" maxlength="10" style="width:110px;height:40px" style="font-weight:bold" class="numerico"/>';

		$boton_calcular='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="calcular" value="CALCULAR" class="ui-input-button">';

		$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

			return $total.$boton_calcular.$boton_limpiar;
		});///cierro callback_edit_field		


		$idL=$this->uri->segment(3);
		$this->db->select('idContrato');
		$this->db->from('liquidaciones');
		$this->db->where('idLiquida',$idL);
		$query = $this->db->get();
		foreach ($query->result() as $row){
			$idC=$row->idContrato;
		}		
		//if(isset($idC)){
		$crud->set_crud_url_path(site_url('Liquidacion/liquidar/'.$idC));
		//}	

		$output = $crud->render();	
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
 
		/*if($state == 'success'){
			$crud->set_crud_url_path(site_url('Liquidacion/liquidar/'.$idC));
		}*/
		


		$this->_example_output($output);		
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