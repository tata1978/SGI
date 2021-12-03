<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Reclamo2 extends CI_Controller{	

		public function __construct(){			
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('grocery_CRUD');
			$this->load->library('session');
		}

		public function index(){			
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
		}

		public function reclamo2(){
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('reclamos');	
			$crud->set_subject('Reclamo');	

			$crud->set_relation('idContrato','alquileres','idInmueble');			
			

			$crud->columns('idContrato','locador','locatario','tipoProblema','descripcion','prioridad','estado');

			$crud->fields('idContrato','locador','locatario','tipoProblema','descripcion','prioridad','fechaReclamo','usuario','estado');

			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('tipoProblema','Especialidad');

			$crud->field_type('usuario','invisible');
			$crud->field_type('fechaReclamo','invisible');
			$crud->field_type('estado','hidden','PENDIENTE');

			$crud->field_type('prioridad','enum',array('ALTA','MEDIA','BAJA'));
			$crud->field_type('tipoProblema','enum',array('ASCENSOR','ELECTRICIDAD','PLOMERIA','HUMEDAD','GOTERAS/FILTRACIONES','ALBAÑILERIA','CARPINTERIA','ABERTURAS','VIDRIERIA'));


				
						
					/*$crud->callback_add_field('idContrato', function () {
						$idC=$this->uri->segment(4);

						$this->db->select('idInmueble');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){							
							$idI=$row->idInmueble;						
						}

						$this->db->select('direccion,piso,depto');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$idI);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$direccion = $row->direccion;
							$piso=$row->piso;			
							$depto=$row->depto;													
						}
						return '<select id="field-idContrato" class="chosen-select" data-placeholder="Seleccionar Inmueble" value="Inmueble" name="idContrato"><option value='.$idInmueble.'>'.$direccion." - ".$piso." - ".$depto.'</option></select>';
				});	*/

				

					/*$crud->callback_add_field('locador', function () {						
						$idC=$this->uri->segment(4);
						$this->db->select('locador');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){							
							$dniLocador=$row->locador;						
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dniLocador);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$nombrelocador=$row->apellidoNombre;													
						}
						return '<input id="field-locador" name="locador" type="text" value='.$nombrelocador.' style="width:200px"   />';
					});		


					$crud->callback_add_field('locatario', function () {						
						$idC=$this->uri->segment(4);
						$this->db->select('locatario');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){							
							$dnilocatario=$row->locatario;						
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dnilocatario);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$nombrelocatario=$row->apellidoNombre;													
						}
						return '<input id="field-locatario" name="locatario" type="text" value='.$nombrelocatario.' style="width:200px"  />';
					});*/


					$crud->callback_add_field('descripcion', function () {
						return '<textarea id="field-descripcion" maxlength="255" name="descripcion"></textarea>';
					});

			$crud->callback_before_insert(array($this,'fecha_usuario'));		
			
			$output = $crud->render();	
			$this->_example_output($output);
		}

			public function fecha_usuario($post_array){				
				date_default_timezone_set('America/Argentina/Buenos_Aires');
				$post_array['fechaReclamo'] = date('d/m/Y G:i');
				$post_array['usuario'] = $this->session->userdata('usuario');
				return $post_array;
			}

			function _example_output($output = null){
				$this->load->view('inicio',(array)$output);			
			}	
}		