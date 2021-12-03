<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Reclamo extends CI_Controller{	

	public function __construct(){			
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('session');
		$this->config->load('grocery_crud');
		$this->load->model('buscar_datos_model');
		$this->load->library('session');
	}

	public function index(){			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
	}

	public function getEspecialidad(){
		echo json_encode($this->buscar_datos_model->getEspecialidad());
		//return $this->buscar_datos_model->getEspecialidad();
	}

	public function nuevoReclamo(){		
		echo $this->buscar_datos_model->nuevoReclamo();
	}

	public function buscarInmueble(){
		$consultaBusqueda = $_POST['valorBusqueda'];
		$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
		$caracteres_buenos = array("&lt;", "&gt;", "&quot;", "&#x27;", "&#x2F;", "&#060;", "&#062;", "&#039;", "&#047;");
		$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);		
		
		//return $this->buscar_datos_model->buscarInmueble($consultaBusqueda);
		echo json_encode($this->buscar_datos_model->buscarInmueble($consultaBusqueda));		
	}

	public function buscarPersonas(){
		$consultaBusqueda = $_POST['valorBusqueda'];
		$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
		$caracteres_buenos = array("&lt;", "&gt;", "&quot;", "&#x27;", "&#x2F;", "&#060;", "&#062;", "&#039;", "&#047;");
		$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);		
		
		return $this->buscar_datos_model->buscarPersonas($consultaBusqueda);	
	}	

	public function getTecnicos(){
		echo json_encode($this->buscar_datos_model->getTecnicos());
	}

	public function update_reclamo(){
		echo $this->buscar_datos_model->update_reclamo();
	}

	public function imprimir_reclamo($idR){//imprimir reclamo desde el main
			
			$host=$_SERVER['SERVER_NAME'];			

			$datos=$this->buscar_datos_model->buscar_datos_reclamos_individual($idR);
			
			$rubro=$datos[0];
			$problema=$datos[1];//problama
			$reporte=$datos[2];//direccion
			$locatario=$datos[3];//locatario
			$contacto=$datos[4];//telefono
			$prioridad=$datos[5];
			$estado=$datos[6];
			$descripcion=$datos[7];
			$tecnico=$datos[8];							

			$html = 
			        	"<style>@page {        		
						    margin-top: 1cm;
						    margin-bottom: 1cm;
						    margin-left: 1.27cm;
						    margin-right: 1.27cm;
						}
						</style>".
			       		 "<body>
			       		 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
			       		 	<tr>
			       		 		<td align='center' valign='bottom'><b style='font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:18px'>Reporte de Reclamos de Inquilinos</b></td> 			       		 		
			        		</tr>
			        		<tr>
				        		<td colspan='2' align='center' style='vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragui V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
				        		</td>	        		
			        		</tr>        		
			        	</table>	
			        	<br>	
			
						<table width='100%' border='0' cellpadding='0' cellspacing='0'>
							<tr >
								<td height='40'  align='center' ><b><u>Técnico</u>: </b>$tecnico</td>
							</tr>														
						";
				
				foreach($reporte as $idR => $direccion){								
											
					$dato_requi.=					
							"
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td height='25' valign='top'><b><u>Nro</u>:</b>$idR - <b><u>Direccion</u>: </b>$direccion  - <b><u>Locatario</u>: </b>$locatario[$idR]</td>
							</tr>
							<tr>
								<td height='25' valign='top'><b><u> Contacto:</u></b> $contacto[$idR]</td>
							</tr>	
							<tr>
								<td height='25' valign='top'><b><u> Prioridad:</u></b> $prioridad[$idR]  - <b><u>Rubro</u>: </b>$rubro[$idR]</td>
							</tr>							
							<tr>
								<td height='25' valign='top'><b><u>Problema</u>: </b>$problema[$idR]</td>
							</tr>							
							";
			        		if($descripcion[$idR]<>""){
			        			$dato_requi.="<tr>
								<td height='25' valign='top'><b><u>Descripcion</u>: </b>$descripcion[$idR]</td>
							</tr>";
			        		}

						$dato_requi.="<tr><td style='border-bottom: 1px solid black;'> &nbsp;   </td></tr>";
				}												
			$html.=$dato_requi;	

			$html.=$fin="</table></body>";			
			
			$pdfFilePath = "reporte_reclamos".".pdf";	
        	//load mPDF library
        	$this->load->library('M_pdf');
       	 	$mpdf = new mPDF('c', 'A4-P'); 
 			$mpdf->WriteHTML($html);
			//$mpdf->Output($pdfFilePath, "D");
       		// //generate the PDF from the given html
       		$this->m_pdf->pdf->WriteHTML($html);
 
       		//  //download it.
       		$this->m_pdf->pdf->Output($pdfFilePath, "D");		
	}


	public function reclamo($id){

		$crud = new grocery_CRUD();			
		$crud->set_theme('datatables');
		$crud->set_table('reclamos');	
		$crud->set_subject('Reclamo');	
		$crud->set_relation('idContrato','alquileres','idContrato');

		if(!isset($id)){
			redirect('Alquiler/alquiler_reclamos');
		}

		$state=$this->uri->segment(3);

		if($state =='success'){				
				redirect('Alquiler/alquiler_reclamos');
		}
			
		$crud->where('idContrato',$id);				

		$crud->columns('idContrato','locador','locatario1','tipoProblema','problema','prioridad','estado');

		$crud->fields('idContrato','locador','locatario1','telefono','tipoProblema','problema','prioridad','fechaReclamo','usuario','estado');

		$crud->display_as('idContrato','Inmueble');
		$crud->display_as('tipoProblema','Especialidad');
		$crud->display_as('descripcion','Observaciones');

		$crud->field_type('usuario','invisible');
		$crud->field_type('fechaReclamo','invisible');
		$crud->field_type('estado','hidden','PENDIENTE');

		$crud->required_fields('tipoProblema','problema','prioridad','telefono');

		//$crud->unset_back_to_list();

		$idC=$this->uri->segment(4);
			if(is_numeric($idC)){			
				$crud->callback_add_field('idContrato', function () {
					$idC=$this->uri->segment(4);

					$this->db->select('idInmueble');
					$this->db->from('alquileres');
					$this->db->where('idContrato',$idC);		
					$query=$this->db->get();								
					foreach ($query->result() as $row){							
						$idI=$row->idInmueble;						
					}

					$direccion = $this->buscar_datos_model->buscar_inmueble($idI);
					$idE=$this->buscar_datos_model->buscar_idE($idI);

					if(isset($idE)){
						$edificio=$this->buscar_datos_model->buscar_edificio($idE);	
						$Nedificio=$edificio['edificio'];
						$edificioN='&nbsp; Edificio: '.$Nedificio;
					}else{
						$edificioN="";
					}

						return '<select id="field-idContrato" class="chosen-select" data-placeholder="Seleccionar Inmueble" value="Inmueble" name="idContrato"><option value='.$idC.'>'.$direccion.'</option></select>'.$edificioN;
					});	
				}

					$crud->callback_add_field('locador', function () {						
						$idC=$this->uri->segment(4);
						$this->db->select('locador');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){							
							$dni=$row->locador;						
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$nombrelocador=$row->apellidoNombre;													
						}
						return '<input id="field-locador" name="locador" type="text" maxlength="50" value="'.$nombrelocador.'" style="width:350px" readonly/>';
					});		


					$crud->callback_add_field('locatario1', function () {						
						$idC=$this->uri->segment(4);
						$this->db->select('locatario1');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){							
							$dni=$row->locatario1;						
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$nombrelocatario=$row->apellidoNombre;													
						}
						return '<input id="field-locatario1" name="locatario1" type="text" value="'.$nombrelocatario.'" style="width:350px" readonly/>';
					});	


					$crud->callback_add_field('telefono', function () {						
						$idC=$this->uri->segment(4);
						$locatario=$this->buscar_datos_model->buscar_dni_locatario($idC);
						$telefono=$this->buscar_datos_model->buscar_telefono_locatario($locatario);
						if($telefono=="-"){$telefono="";}
						/*$this->db->select('locatario1');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){							
							$dni=$row->locatario1;						
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$nombrelocatario=$row->apellidoNombre;													
						}*/						
						return '<input id="field-telefono" name="telefono" type="text" value="'.$telefono.'" style="width:200px"/>';
					});						


					$crud->callback_add_field('problema', function () {
						return '<textarea id="field-problema" maxlength="500" name="problema"></textarea>';
					});

			$crud->callback_before_insert(array($this,'fecha_usuario'));
			$crud->callback_after_insert(array($this,'actualiza_telefono'));	

			//$crud->callback_after_insert(array($this, 'actualiza_reclamo'));


			//$crud->set_crud_url_path(site_url('Alquiler/alquiler_reclamos/index'));


			$output = $crud->render();
			$state = $crud->getState();
			$state_info = $crud->getStateInfo();
			$this->_example_output($output);
		}	

		public function fecha_usuario($post_array){				
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$post_array['fechaReclamo'] = date('d/m/Y G:i');

			$sesion= $this->session->userdata('usuario');
			$usuario=$sesion[1];			
			$post_array['usuario'] = $usuario;
			return $post_array;
		}

		public function actualiza_telefono($post_array,$id){
			$idC=$post_array['idContrato'];
			$locatario=$this->buscar_datos_model->buscar_dni_locatario($idC);
			$telefono1=$this->buscar_datos_model->buscar_telefono_locatario($locatario);
			if($telefono1=="-"){
				$telefono2=$post_array['telefono'];
				$this->db->set('telefono',$telefono2);
				$this->db->where('dni',$locatario);
				$this->db->update('personas');
			}			
		}

		/*public function actualiza_reclamo($post_array,$primary_key){			
			$idC =$post_array['idContrato'];

			/*$this->db->select('idContrato');
			$this->db->from('reclamos');				
			$this->db->where('idContrato',$idC);			

			$reclamos= $this->db->count_all_results();	

			$this->db->select('idContrato');
			$this->db->from('reclamos');			
			$this->db->where('estado','PENDIENTE');
			$this->db->where('idContrato',$idC);
			
			$pendientes= $this->db->count_all_results();


			$this->db->select('idContrato');
			$this->db->from('reclamos');			
			$this->db->where('estado','EN PROCESO');
			$this->db->where('idContrato',$idC);

			$proceso= $this->db->count_all_results();	
			
			$valor = $pendientes." / ".$proceso." / ".$reclamos;

			$this->db->set('reclamos','SI');
			$this->db->set('pendientes',$valor);						
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');			
		}*/



	public function ver_reclamos($id){
		/*$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);*/	

		$output = $this->ver_reclamos_management($id);	

		$this->db->select('locador,locatario1,idInmueble');
		$this->db->where('idContrato',$id);
		$this->db->from('alquileres');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locador = $row->locador;
			$locatario1=$row->locatario1;
			$idI=$row->idInmueble;
		}
		$inmueble= $this->buscar_datos_model->buscar_inmueble($idI);

		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locatario1);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locatario1 = $row->apellidoNombre;
		}

		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locador);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locador = $row->apellidoNombre;
		}		


		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "<div class='texto' style='display:none;' ><b style='font-weight: normal;color:white'> - Inmueble:</b> $inmueble<b style='font-weight: normal;color:white'> - Locatario:</b> $locatario1 <b style='font-weight: normal;color:white'> - Locador:</b> $locador</div>".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

		public function ver_reclamos_management($id){
			
			$this->config->set_item('grocery_crud_dialog_forms',true);
			//$this->config->set_item('grocery_crud_default_per_page',10);

			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('reclamos');	
			$crud->set_subject('Reclamo');				
			
			$crud->where('idContrato',$id);
			$crud->order_by('fechaReclamo','DESC');

			$crud->columns('idReclamo','tipoProblema','problema','prioridad','estado','fechaReclamo','fecha_atencion','encargado','liquidado');

			$crud->unset_print();
			$crud->unset_export();

			$crud->fields('locador','locatario1','tipoProblema','problema','fechaReclamo','prioridad','usuario');

			$crud->field_type('usuario','invisible');
			$crud->field_type('fechaReclamo','invisible');

			$crud->display_as('idReclamo','Nro R.');
			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('tipoProblema','Especialidad');
			$crud->display_as('fechaReclamo','Reclamo');
			$crud->display_as('encargado','Técnico');			
			$crud->display_as('fecha_atencion','Atendido');
			$crud->display_as('dinero_desc','Para');
			$crud->display_as('dinero_dado','Se entrega dinero');


			$idC=$this->uri->segment(3);

			$crud->callback_read_field('idContrato', function ($value, $primary_key) {
				$idC=$value;
				$idI=$this->buscar_datos_model->buscar_idI($idC);
				$inmueble=$this->buscar_datos_model->buscar_inmueble($idI);
				return $inmueble;
			});

			$crud->edit_fields('tipoProblema','prioridad','problema','fechaReclamo','usuario');


			$crud->callback_edit_field('problema', function ($value) {
				return '<textarea id="field-problema" maxlength="255" name="problema">'.$value.'</textarea>';
			});			


			$crud->callback_column('estado',array($this,'estado_reclamo'));

			//$crud->add_action('Atender', '', 'Reclamo/atender_reclamos/'.$idC.'/edit','ui-icon-plus');

			$crud->add_action('Atender', '', 'Reclamo/atender_reclamos/edit','ui-icon-plus');

			$crud->unset_add();
			//$crud->unset_delete();

   $crud->set_lang_string('update_success_message',
		 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
		 <script type="text/javascript">
		  window.location = "'.site_url('Reclamo/ver_reclamos/'.$idC).'";
		 </script>
		 <div style="display:none">
		 '
   );   			


			
			
			$output = $crud->render();

			$state = $crud->getState();
			$state_info = $crud->getStateInfo();
			//$this->_example_output($output);
			if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}

		}

		public function estado_reclamo($value, $row){
			if($value=="EN PROCESO"){
				return '<span style="background: #00D159" class="badge badge-pill">'.$value.'</span>';
			}elseif ($value=="PENDIENTE") {
				return '<span style="background: #FF5100" class="badge badge-pill">'.$value.'</span>';
			}else{
				return '<span style="background: red" class="badge badge-pill">'.$value.'</span>';
			}	
		}

		public function atender_reclamos($id){
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('reclamos');	
			$crud->set_subject('Atender Reclamo');

			//$crud->set_relation('idContrato','alquileres','idContrato');

			$crud->where('idReclamo',$id);
			$crud->edit_fields('problema','encargado','descripcion','dinero_dado','dinero_desc','quien_paga','fecha_atencion','usuario','estado','finalizar');


			$crud->field_type('usuario','invisible');
			$crud->field_type('fecha_atencion','invisible');
			$crud->field_type('estado','invisible');	

			
			$crud->required_fields('encargado');

			

			//$crud->field_type('descripcion', 'readonly');
			//$crud->field_type('idContrato', 'readonly');

			$crud->display_as('dinero_desc','Para');
			$crud->display_as('dinero_dado','Costo de Reparación');
			$crud->display_as('idContrato','Inmueble');	
			$crud->display_as('encargado','Técnico');
			$crud->display_as('descripcion','Observaciones');

			$crud->display_as('finalizar','¿Finalizar Reclamo?');


			
			/*$crud->callback_edit_field('idContrato', function ($value) {
				return '<input id="field-idContrato" type="text"  name="idContrato" value="'.$value.'" style="width:30px"/>';
			});*/

			$crud->callback_edit_field('problema', function ($value) {
				return '<textarea id="field-problema" maxlength="500" name="problema">'.$value.'</textarea>';	
				 
			});	



			$crud->callback_edit_field('encargado', function ($value) {
				$combo = '<select id="field-encargado" name="encargado" class="chosen-select" data-placeholder="Seleccionar Tecnico">';
				$fincombo = '</select>';

				$tecnicos=$this->buscar_datos_model->tecnicos();						
				
				foreach ($tecnicos as $nombre_tecnico => $nombre_tecnico){
						if($nombre_tecnico==$value){
							$combo .= '<option value=""></option><option value="'.$nombre_tecnico.'"selected>'.$nombre_tecnico.'</option>';
						}else{
							$combo .= '<option value=""></option><option value="'.$nombre_tecnico.'">'.$nombre_tecnico.'</option>';
						}

				}						
				return $combo.$fincombo;			
			});//fin callback_add_field



			/*$crud->callback_edit_field('encargado', function ($value) {
				return '<input id="field-encargado" name="encargado" type="text" value="'.$value.'"  maxlength="50" style="width:200px;height:30px"/>';	
				 
			});	*/			


			$crud->callback_edit_field('descripcion', function ($value) {
				return '<textarea id="field-descripcion" maxlength="500" name="descripcion">'.$value.'</textarea>';
			});
			
			$crud->callback_edit_field('dinero_dado', function ($value) {
				return '  $:<input id="field-dinero_dado" name="dinero_dado" type="text" value="'.$value.'"  maxlength="5" style="width:50px;height:30px" class="numerico"  />';	
				 
			});	


			$crud->callback_edit_field('dinero_desc', function ($value) {
				$textarea='<textarea id="field-dinero_desc" maxlength="255" name="dinero_desc">'.$value.'</textarea>';			
				return $textarea ;
			});

			//$crud->unset_add();
			//$crud->unset_delete();
			
			$crud->callback_before_update(array($this,'fecha_atencion'));	

			$crud->callback_after_update(array($this,'reclamos_editar'));

			$idR=$this->uri->segment(4);
			$idC=$this->buscar_datos_model->buscar_idC($idR);

   $crud->set_lang_string('update_success_message',
		 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
		 <script type="text/javascript">
		  window.location = "'.site_url('Reclamo/ver_reclamos/'.$idC).'";
		 </script>
		 <div style="display:none">
		 '
   ); 			

			$output = $crud->render();
			$this->_example_output($output);

		}//CIERRA FUNCION


		public function reclamos_editar($post_array,$id){
			$id =$post_array['idContrato'];
			//$id=$this->uri->segment(3);
			
			$this->db->select('idContrato');
			$this->db->from('reclamos');				
			$this->db->where('idContrato',$id);	

			$query=$this->db->get();
			$reclamos=$query->num_rows();					

			

			$this->db->select('idContrato');
			$this->db->from('reclamos');			
			$this->db->where('estado','PENDIENTE');
			$this->db->where('idContrato',$id);

			$query=$this->db->get();
			$pendientes=$query->num_rows();			

			$this->db->select('idContrato');
			$this->db->from('reclamos');			
			$this->db->where("(estado = 'EN PROCESO' OR estado = 'EN ESPERA')");
			$this->db->where('idContrato',$id);	

			$query=$this->db->get();
			$proceso=$query->num_rows();			
			
			$valor = $pendientes."   -   ".$proceso."   -   ".$reclamos;
			
			$this->db->select('estado');
			$this->db->from('reclamos');
			$this->db->where('idContrato',$id);
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$estado = $row->estado;
			}
			if($pendientes>0 or $proceso>0){
				$this->db->set('reclamos',"SI");
			}else{
				$this->db->set('reclamos',"NO");
			}
				$this->db->where('idContrato',$id);
				$this->db->update('alquileres');
			
			/*$this->db->set('pendientes',$valor);						
			$this->db->where('idContrato',$id);
			$this->db->update('alquileres');*/

		}



		public function fecha_atencion($post_array){				
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$post_array['fecha_atencion'] = date('d/m/Y G:i');

			$sesion= $this->session->userdata('usuario');
			$usuario=$sesion[1];			
			$post_array['usuario'] = $usuario;
			$finaliza=$post_array['finalizar'];	
			if($finaliza==""){
				$post_array['estado']="EN PROCESO";
			}elseif($finaliza=="ANULAR"){
				$post_array['estado']="ANULADO";
			}else{
				$post_array['estado']="FINALIZADO";				
			}
			return $post_array;
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