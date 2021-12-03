<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Alquiler extends CI_Controller{	

		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('grocery_CRUD');
		}
		public function index(){			
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
		}

			public function alquiler(){				
				$crud=new grocery_CRUD();		
				$crud->set_theme('datatables');
				$crud->set_table('alquileres','tipoinmuebles','inmuebles');	
				$crud->set_subject('Alquiler','TipoInmueble','Inmueble');	

				/*$crud->set_table('inmuebles');
				$crud->set_subject('Inmueble');
				$crud->set_relation_n_n('propiedad','alquileres','personas','idInmueble','dni','apellidoNombre');*/
				
				//$crud->where('estado','ALQUILADO');	

				$crud->set_relation('idInmueble','inmuebles','{direccion} - {piso} - {depto}');			

				$crud->set_relation('locatario','personas','apellidoNombre');
				$crud->set_relation('garante','personas','apellidoNombre');
				//$crud->set_relation('iddetalle','detallealquiler','estado_alquiler');
				$crud->set_relation('locador','personas','apellidoNombre');

				//$crud->field_type('estado_alquiler', 'hidden', 'AL DIA');
				//$crud->field_type('proxVenc', 'hidden', '01/01/2018');

				$crud->display_as('idInmueble','Inmueble');
				$crud->display_as('punitorio','Mora diaria en %');
				$crud->display_as('comision','Comision en %');
				$crud->display_as('fechaPago','Dia de Pago');
				$crud->display_as('fechaInicio','Fecha de Inicio');
				$crud->display_as('fechaFin','Fecha de Fin');
				$crud->display_as('duracion','Duracion(meses))');
				//$crud->display_as('estado_alquiler','Estado');
				
				//$crud->display_as('tipoAlquiler','Tipo de Alquiler');
				$crud->columns('Estado','fechaInicio','fechaFin','idInmueble','locatario','locador','duracion','valor');
				
				$crud->required_fields('idInmueble','locador','locatario','garante','fechaInicio','fechaFin','punitorio','comision','fechaPago','duracion','valor');
				$crud->fields('idInmueble','locador','garante','locatario','fechaInicio','fechaFin','punitorio','comision','fechaPago','duracion','valor');
				$crud->unset_delete();		

				//$crud->callback_after_insert(array($this, 'detalle_alquiler'));

				//aca se precarga el inmueble, el propietario y el valor 	
				$id=$this->uri->segment(4);					
				if(is_numeric($id)){			
					$crud->callback_add_field('idInmueble', function () {
						$id=$this->uri->segment(4);
						$this->db->select('direccion,piso,depto,dni');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$id);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$direccion = $row->direccion;
							$piso=$row->piso;			
							$depto=$row->depto;
							$dni=$row->dni;						
						}
						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre = $row->apellidoNombre;
						}
						return '<select id="field-idInmueble" class="chosen-select" data-placeholder="Seleccionar Inmueble" value="Inmueble" name="idInmueble"><option value='.$id.'>'.$direccion." - ".$piso." - ".$depto.'</option></select>';
					});	

					
					$crud->callback_add_field('valor', function () {
						$id=$this->uri->segment(4);
						$this->db->select('valor');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$id);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$valor=$row->valor;
						}
						return '<input id="field-valor" name="valor" type="text" value='.$valor.'class="numeric form-control" maxlength="8"/>';
					});	


					$crud->callback_add_field('locador', function () {
						$id=$this->uri->segment(4);
						$this->db->select('dni');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$id);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$dni=$row->dni;
						}
						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre = $row->apellidoNombre;
						}
						return '<select id="field-locador" class="chosen-select" data-placeholder=" " value="Locador" name="locador"><option value='.$dni.'>'.$nombre.'</option></select>';
					});					
				}	
				//fin - aca se precarga el inmueble y el propietario 

					//para añadir enlade añadir garante, pero no puedo llenar el combo con los garantes existentes
					/*$crud->callback_add_field('garante', function () {
						$this->db->select('dni,apellidoNombre');
						$this->db->from('personas');							
						$query=$this->db->get();
						foreach ($query->result_array() as $row){
							 $NyA = $row['apellidoNombre'];
							 $dni=$row['dni'];
						}
						return '<select id="field-garante" class="chosen-select" data-placeholder="Seleccionar Garante" value="Garante" name="Garante"><option value='.$dni.'>'.$NyA.'</option></select>
						<a href="'.site_url('persona/persona_management/add').'">Añadir Persona</a>';
					});*/


				//cambiar el estado del alquiler despues de alquilar un inmueble
				$crud->callback_after_insert(array($this,'estado_alquiler'));
			
				
				$crud->add_action('Pagar','','Alquiler/pagar','ui-icon-calculator');

				$crud->add_action('Eliminar','','Alquiler/eliminar_alquiler','ui-icon-circle-minus');			
						
				$output = $crud->render();	
				$this->_example_output($output);
			}



			public function pagar(){	
				$id=$this->uri->segment(4);	
				
			}


			/*function detalle_alquiler($post_array){

			}*/

			public function estado_alquiler($post_array, $pk){	
				//$id=$this->uri->segment(4);
				$id =$post_array['idInmueble'];
				$this->db->set('estado','ALQUILADO');
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');				
			}
			public function eliminar_alquiler($id){		
				//busco el idinmueble para cambiar el estado a disponible 
				$this->db->select('idInmueble');
				$this->db->from('alquileres');
				$this->db->where('idContrato',$id);			
				$query=$this->db->get();			
				foreach ($query->result() as $row){
					$id_inmueble = $row->idInmueble;			
				}
				$this->load->model('inmueble_model');
				$this->inmueble_model->cambiar_estado($id_inmueble);

				//aca elimino el contrato
				$this->load->model('alquiler_model');
				$this->alquiler_model->eliminar($id);
				redirect('Alquiler/alquiler');
			}			

			function _example_output($output = null){

				$this->load->view('inicio',(array)$output);
			
			}	
}