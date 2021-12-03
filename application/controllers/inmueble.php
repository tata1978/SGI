<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inmueble extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->config->load('grocery_crud');
		$this->load->model('buscar_datos_model');
		$this->load->helper('numeros');
		$this->load->library('session');
		
	}
	public function index(){			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
	}

	public function cancelar_renovacion($id){
		$this->buscar_datos_model->cancelar_renovacion($id);
		redirect('Inmueble/inmueble');
	}

	public function descargar_pdf($idR){
		$data = [];

			$this->db->select('idReserva,idInmueble,apellidoyNombre,telefono,sena,fecha_creacion,usuario');
			$this->db->from('reservas');
			$this->db->where('idReserva',$idR);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idR = $row->idReserva;
				$idI=$row->idInmueble;
				$interesado=$row->apellidoyNombre;
				$telefono=$row->telefono;
				$sena=$row->sena;
				$fecha_creacion=$row->fecha_creacion;
				$user=$row->usuario;				
			}	

			$valor_letra=num_to_letras($sena);

			setlocale(LC_TIME,"es_RA");
			/*$fecha_firma=date("d/m/Y", strtotime($fecha_F));
			$fechaI = date("m/y", strtotime($fechaIn));
			$fechaF = date("m/y", strtotime($fechaFin));*/

			$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
			$this->db->from('inmuebles');
			$this->db->where('idInmueble',$idI);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){				
				$id_tipoinmueble=$row->idTipoInmueble;
				$idE=$row->idEdificio;
			}
			if(isset($idE)){
				$edificio=$this->buscar_datos_model->buscar_edificio($idE);
				$nombreE='-'.$edificio['edificio'];	
			}else $nombreE="";

			$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
			$tipoinmueble = $this->buscar_datos_model->buscar_tipoInmueble($id_tipoinmueble);

			setlocale(LC_ALL,"es_RA");
			setlocale(LC_TIME,"es_RA");
			date_default_timezone_set ("America/Argentina/Buenos_Aires");

			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");			

			$fecha_sena=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
			$hora_sena=strftime("%H:%M");
			$sesion= $this->session->userdata('usuario');
			$user=$sesion[2];
			$this->db->select('NyA');
			$this->db->from('usuarios');
			$this->db->where('nombreUsuario',$user);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$usuario=$row->NyA;
			}


			$hoy = 'reserva-'.$interesado.'-'.$direccion;

			$host=$_SERVER['SERVER_NAME'];

        	//load the view and saved it into $html variable
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
       		 		<td align='center' valign='bottom'><b style='font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:20px'>Reserva de Inmueble</b> - <b style='font-size:12px'>N° de reserva: $idR</b></td> 
       		 		<td align='right'><img src='http://$host/SGI/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		</tr>
        		<tr>
	        		<td align='center' colspan='2' style='height:20px;vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:14px'>Carlos Pellegrini 1557, Taragüi V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
	        		</td>	        		
        		</tr>        		
        	</table>
        	<br>
        			
        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>	
        			<tr >        				
        				<td style='height:50px;vertical-align:text-top'><u><b>RECIBO</b></u>: dinero en concepto de reserva, para el <u>Inmueble</u>: <b>$direccion $nombreE</b>, válido por 5 (cinco) días hábiles, con previa presentación de documentación y aprobación por parte del propietario.  
        				</td>
        			</tr>
        		</table>
        		<table border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
        				<td ><u><b>Interesado</b></u>:</td>
        				<td > $interesado </td>
        			</tr>
        			<tr>	
        				<td ><u><b>Teléfono</b></u>:</td>
        				<td > $telefono </td>        				
        			</tr>
        		</table>
        		<br>
        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
        				<td style='width:auto'><b></b></td>
        				<td style='font-size:12px' align='center'><b>DETALLE</b></td>
        				<td style='font-size:12px' align='center'><b>VALOR</b></td>
        			</tr>
        			<tr>	
        				<td style='width:auto' style='border-bottom: 1px solid #000000'> </td>
        				<td align='center' style='border-bottom: 1px solid #000000'>Dinero en concepto por reserva de inmueble   </td>
        				<td align='right' style='border-bottom: 1px solid #000000' > $sena.00 </td>
        			</tr>	
        		    <tr>        		    	
        				<td colspan='2' style='height:20px;width:auto' align='right'><b style='font-size:17px'> Importe : $</b></td>
        				<td style='height:20px;width:auto' align='right' ><b style='font-size:17px'>$sena.00</b></td>
        			</tr>        			
        			<tr>       				       				
        				<td colspan='3' style='font-size:12'>
        				Son pesos: <b>$valor_letra</b>
        				</td>        				
        			</tr>
        			<tr>
        				<td colspan='3' style='height:40px;vertical-align:text-top;font-size:12' >Emitido el : $fecha_sena a las $hora_sena por $usuario</td>
        			</tr> 
        		</table>

        		<br>
        		<table width='100%' border='0' cellpadding='0' cellspacing='0' style='font-size:12'>	 
        			<tr>
        				<td style='height:30px;vertical-align:text-top'>Firma:..........................................</td>
        				<td style='height:20px;vertical-align:text-top'>DNI:..........................................</td>
        			</tr>
        			<tr>        			
        				<td>Aclaración:........................................................</td>
        				<td>Fecha:..........................................</td>
        			</tr>         			      			
        		</table>        		
        		<br>
        		<br> 
        		<br>       		
       		        			
        	</body>";

        	// $html = $this->load->view('v_dpdf',$date,true);
 		
 			//$html="asdf";
        	//this the the PDF filename that user will get to download
        	$pdfFilePath = $hoy.".pdf";
 
        	//load mPDF library
        	$this->load->library('M_pdf');
       	 	$mpdf = new mPDF('c', 'A4-P'); 
 			$mpdf->WriteHTML($html);
			$mpdf->Output($pdfFilePath, "D");
       		// //generate the PDF from the given html
       		//  $this->m_pdf->pdf->WriteHTML($html);
 
       		//  //download it.
       		//  $this->m_pdf->pdf->Output($pdfFilePath, "D"); 
	}

	public function reservar(){
		$this->config->load('grocery_crud');
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);

		$output = $this->reservar_management();		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}


	public function reservar_management(){
			$crud = new grocery_CRUD();			
			$crud->set_table('reservas');
			$crud->set_subject('Reserva');	

			$crud->set_relation('idInmueble','inmuebles','idInmueble');
			$crud->set_relation('propietario','personas','apellidoNombre');

			$crud->order_by('idInmueble','desc');
			//$crud->set_rules('telefono','Telefono','numeric');
			//$crud->set_rules('sena','Sena','numeric');
			$crud->fields('idInmueble','propietario','apellidoyNombre','telefono','sena','fecha_creacion','usuario');
			$crud->columns('idReserva','inmueble','propietario','edificio','estado','apellidoyNombre','telefono','sena','fecha_creacion');
			$crud->field_type('fecha_creacion','invisible');
			$crud->field_type('usuario','invisible');



			$crud->required_fields('idInmueble','apellidoyNombre','telefono','sena');

			$crud->display_as('idReserva','#');
			$crud->display_as('idInmueble','Inmueble');
			$crud->display_as('apellidoyNombre','Interesado');
			$crud->display_as('sena','Seña');
			$crud->display_as('fecha_creacion','Fecha de Seña');	

			$crud->unset_edit();
			$crud->unset_read();
			$crud->unset_delete();

			$sesion= $this->session->userdata('usuario');

			$crud->callback_add_field('propietario', function () {
				$idI=$this->uri->segment(4);
				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$dni=$datos_inmueble['dni'];
				$propietario=$this->buscar_datos_model->buscar_persona($dni);
				$combo = '<select id="field-propietario" name="propietario" class="chosen-select" data-placeholder="Seleccionar Propietario"><option value="'.$dni.'">'.$propietario.'</option></select>';	
				return $combo;			
			});			

			$crud->callback_column('inmueble',array($this,'buscar_inmueble'));			

			$crud->callback_column('edificio',array($this,'buscar_edificio'));

			$crud->callback_column('estado',array($this,'buscar_estado'));			

			//$crud->callback_before_delete(array($this,'actualiza_estado_delete'));

			$crud->callback_before_insert(array($this,'actualiza_estado_inmueble'));	

			//$crud->callback_after_insert(array($this, 'refresh_inmueble'));	

			$crud->callback_after_insert(array($this, 'update_reserva'));

			$crud->add_action('Recibo', '', 'Inmueble/descargar_pdf','ui-icon-print');


			$crud->add_action('Cancelar', '', 'Inmueble/eliminar_reserva','	ui-icon-cancel');			




			//$crud->add_action('Imprimir', '', 'Imprimir/descargar_pdf','ui-icon-print');

$crud->set_lang_string('insert_success_message',
                 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
                 <script type="text/javascript">
                  window.location = "'.site_url('Inmueble/reservar').'";
                 </script>
                 <div style="display:none">
                 '
   );

	



			$crud->callback_read_field('idInmueble', function ($value, $primary_key) {
				$idI=$value;
				$direccion=$this->buscar_datos_model->buscar_inmueble($idI);	
				$caract=$this->buscar_datos_model->buscar_caract_inmueble($idI);			
				return $direccion.' - <b>Caracteristicas:</b> '.$caract;
			});		

			$crud->edit_fields('apellidoyNombre','sena');
			$crud->callback_edit_field('idInmueble', function ($value, $primary_key) {
				$idI=$value;
				$direccion=$this->buscar_datos_model->buscar_inmueble($value);				
				return $direccion;
			});					

			$crud->unset_print();
			$crud->unset_export();

			$idI=$this->uri->segment(4);
			if(is_numeric($idI)){	
				$crud->callback_add_field('idInmueble', function () {
					$idI=$this->uri->segment(4);
					$this->db->select('direccion,piso,depto,dni,idEdificio,mts2,caracteristicas');
					$this->db->from('inmuebles');
					$this->db->where('idInmueble',$idI);		
					$query=$this->db->get();								
					foreach ($query->result() as $row){
						$dni=$row->dni;	
						$mts2=$row->mts2;	
						$caract=$row->caracteristicas;
						$idE=$row->idEdificio;			
					}						
					$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
					$edificio=$this->buscar_datos_model->buscar_edificio($idE);
					$nombreE=$edificio['edificio'];
					//$Nedificio=
					$fecha_fin=$this->buscar_datos_model->buscar_fechaFin($idI);

					return '<select id="field-idInmueble" class="chosen-select" data-placeholder="Seleccionar Inmueble" value="Inmueble" name="idInmueble" style="width:auto;height:30px;background-color:#FDFF93"><option value='.$idI.'>'.$direccion.'</option></select>'.'&nbsp-<b>Edificio:</b> '.$nombreE.'&nbsp- <b>M²:</b> '.$mts2.'&nbsp- <b>Caracteristicas:</b> '.$caract. '&nbsp;- <b>Fin de Contrato: </b>'.$fecha_fin;
				});				

				$crud->callback_add_field('apellidoyNombre', function () {					
					$interesado='<input id="field-apellidoyNombre" name="apellidoyNombre" type="text"  maxlength="50" style="width:250px;height:30px;background-color:#FDFF93" required>';
					return $interesado;	
				});//fin callback_add_field	

				$crud->callback_add_field('telefono', function () {					
					$telefono='<input id="field-telefono"   name="telefono" type="number" maxlength="20" style="width:150px;height:30px;background-color:#FDFF93" required>';
					return $telefono;	
				});//fin callback_add_field	

				
				$crud->callback_add_field('sena', function () {	
					$idI=$this->uri->segment(4);
					//////20% del valor del alquiler
					$alquiler=$this->buscar_datos_model->valor_alquiler($idI);
					$valor_sena=$alquiler*0.20;

					$sena='<input id="field-sena"   name="sena" type="number" min="1" max="99999"  maxlength="5" style="width:150px;height:30px;background-color:#FDFF93" required>'.' Seña recomendada (20%): <b style="color:#FC0206">$'.$valor_sena.'</b>';
					return $sena;	
				});//fin callback_add_field

											
			}			

			/*$output = $crud->render();
			$this->_example_output($output);*/

			$output = $crud->render();	
			//$this->_example_output($output);


			if($crud->getState() != 'list') {				
				$this->_example_output($output);				
			} else {				
				return $output;
			}
	}

	public function refresh_inmueble($post_array,$primary_key){

		redirect(base_url("Inmuebles/Inmueble"));
		//redirect(site_url(strtolower('Inmueble/reservar')));
	
	}

	public function buscar_estado($value,$row){
		$idI=$row->idInmueble;		
		$estado=$this->buscar_datos_model->buscar_estado_inmueble($idI);	
		if($estado==3 || $estado==4){
			return '<span style="color:green; font-weight:bolder">DISPONIBLE</span>';
		}else{
			return '<span style="color:red; font-weight:bolder">ALQUILADO</span>';
		}
	}


	public function eliminar_reserva($id){//eliminar reservas desde la pagina de reservas
		$idI=$this->buscar_datos_model->buscar_idI_R($id);
		$estado=$this->buscar_datos_model->buscar_estado_inmueble($idI);	
		
		if($estado==2){
			$this->db->set('estado',5);
			$this->db->set('reserva','0');	
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');
		}elseif ($estado==3) {
			$this->db->set('estado',0);
			$this->db->set('reserva','0');	
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');
		}		
		$this->db->where('idReserva',$id);
		$this->db->delete('reservas');		
		redirect('Inmueble/inmueble');
	}

	public function cancelar_reserva($idI){//eliminar reservas desde la pagina de inmuebles
		$datos_reserva=$this->buscar_datos_model->buscar_datos_reserva($idI);
		$idR=$datos_reserva['idR'];

		$estado=$this->buscar_datos_model->buscar_estado_inmueble($idI);	
		
		if($estado==2){
			$this->db->set('estado',5);
			$this->db->set('reserva','0');	
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');
		}elseif ($estado==3) {
			$this->db->set('estado',0);
			$this->db->set('reserva','0');	
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');
		}		
		$this->db->where('idReserva',$idR);
		$this->db->delete('reservas');		
		redirect('Inmueble/inmueble');
	}


	public function actualiza_estado_inmueble($post_array,$primary_key){
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$post_array['fecha_creacion'] = date('d/m/Y G:i');
		$sesion= $this->session->userdata('usuario');
		$post_array['usuario'] = $sesion[1];

		return $post_array;				
	}

	public function buscar_inmueble($value,$row){
		$idI=$row->idInmueble;			
		$direccion=$this->buscar_datos_model->buscar_inmueble($idI);		
		return $direccion;
	}

	public function buscar_edificio($value,$row){
		$idI=$row->idInmueble;
		$idE= $this->buscar_datos_model->buscar_idE($idI);
		if(isset($idE)){
			$edificio=$this->buscar_datos_model->buscar_edificio($idE);
			$nombre_edificio=$edificio['edificio'];
		}else{
			$nombre_edificio="";
		}
		return $nombre_edificio;
	} 		

	public function update_reserva($post_array){
		$idI=$post_array['idInmueble'];
		$estado=$this->buscar_datos_model->buscar_estado_inmueble($idI);
		if($estado==1){
			$this->db->set('estado',2);				
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');
		}elseif($estado==0){
			$this->db->set('estado',3);				
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');
		}elseif($estado==5){
			$this->db->set('estado',2);				
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');			
		}

		$this->db->set('reserva','1');				
		$this->db->where('idInmueble',$idI);
		$this->db->update('inmuebles');
	}

	public function inmueble(){
		$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);

		$output = $this->inmueble_management();		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files;
		$output = "<div></div>".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

	public function inmueble_management(){			
			$crud = new grocery_CRUD();			
			$crud->set_table('inmuebles');
			$crud->set_subject('Inmueble');

			$crud->set_relation('idTipoInmueble','tipoInmuebles','nombreTipo');
			$crud->set_relation('idBarrio','barrios','nombreBarrio');
			$crud->set_relation('dni','personas','apellidoNombre');
			$crud->set_relation('idEdificio','edificios','descEdificio');

			$crud->order_by('idInmueble','asc');

			$crud->columns('idInmueble','estado','operacion','idTipoInmueble','idEdificio','direccion','cant_dorm','idBarrio','valor','dni');

			$sesion= $this->session->userdata('usuario');
			
			if($sesion[0]==2){
				$crud->unset_add();
				$crud->unset_edit();
				$crud->unset_delete();
			}	

			if($sesion[0]==3){
				$crud->unset_add();	
				$crud->unset_edit();			
				$crud->unset_delete();
			}		

			//$crud->unset_delete();
			//$crud->unset_jquery();	
			//$crud->unset_bootstrap();		
			
			$crud->display_as('idTipoInmueble','Tipo-Inm.');
			$crud->display_as('idEdificio','Edificio');
			$crud->display_as('cant_dorm','Dorm');			
			$crud->display_as('idBarrio','Barrio');
			//$crud->display_as('caracteristicas','Caracteristicas');
			$crud->display_as('operacion','Operac');
			$crud->display_as('valor','Valor');
			$crud->display_as('valor_venta','Valor-Venta');
			$crud->display_as('idInmueble','#');
			$crud->display_as('dni','Propietario');
			$crud->display_as('caract_adicional', 'Caract. Adicioanles');
			$crud->display_as('requisitos', 'Requisitos Alquiler');
			//$crud->display_as('mts2','m²');
			$crud->display_as('nro_cochera','Nro/Letra Cochera');
			
			$crud->field_type('estado', 'hidden', 0);
			//$crud->field_type('cochera','enum',array('SI','NO'));
			//$crud->field_type('operacion','enum',array('ALQUILER','VENTA','ALQUILER-VENTA'));


			
			//$crud->set_rules('dni','numeric');		

	

			$crud->callback_column('estado',array($this,'desc_estado'));			

			$crud->callback_column('direccion',array($this,'buscar_direccion'));

			$crud->callback_column('valor',array($this,'buscar_ajustes'));			

			//$crud->callback_column('idBarrio',array($this,'buscar_barrio'));

			$crud->callback_read_field('piso', function ($value, $primary_key) {
				return $value;
			});

			$crud->callback_read_field('caract_adicional', function ($value, $primary_key) {
				$idI=$primary_key;
				$boton_imprimir = '<br><input type="button" name="button" id="imprimir_inmueble" value="IMPRIMIR" class="ui-input-button" style="background:#AFEEEE" onclick="imprimir_carac_inmueble('.$idI.')">';	

				return $value.$boton_imprimir;
			});

			$crud->callback_read_field('requisitos', function ($value, $primary_key) {
				$idI=$primary_key;
				$boton_imprimir_req = '<br><input type="button" name="button" id="imprimir_requisitos" value="IMPRIMIR" class="ui-input-button" style="background:#AFEEEE" onclick="imprimir_requisitos_inmueble('.$idI.')">';	

				return $value.$boton_imprimir_req;
			});
		

		$crud->fields('dni','adrema','idTipoInmueble','mts2','idEdificio','idBarrio','direccion','ubicacion','piso','depto','cant_dorm','cant_baño','cochera','nro_cochera','caracteristicas','caract_adicional','condicion','operacion','valor','valor_venta','observaciones','requisitos');

		$crud->required_fields('idTipoInmueble','cant_baño','dni','condicion','cochera','operacion');

			    //$crud->set_field_upload('fotos','assets/uploads/files');		
			
			//$crud->field_type('inmueble','hidden');
			//$crud->field_type('inmueble','invisible');
			

			if($sesion[0]==1){
				$crud->add_action('Alquilar','','Alquiler/alquiler/add','ui-icon-key');
				$crud->add_action('Reservar','','Inmueble/reservar/add','ui-icon-clock');
				//$crud->add_action('','','Inmueble/','ui-icon-circle-arrow-s');
				//$crud->add_action('No Renueva','','Inmueble/cancelar_renovacion','ui-icon-cancel');	
			}	
			

					


			//$crud->add_action('Fotos','','','ui-icon-image');
			
			//$crud->set_field_upload('file_url','assets/uploads/files');		  

			//se carga el combo de locador y el enlace de Añadir
			$crud->callback_add_field('dni', function () {
				$combo = '<select id="field-dni" name="dni" class="chosen-select" data-placeholder="Seleccionar Propietario">';
				$fincombo = '</select>';

				$this->db->select('dni,apellidoNombre');
				$this->db->from('personas');
				$this->db->order_by('apellidoNombre','asc');							
				$query=$this->db->get();
				foreach ($query->result() as $row){
						$combo .= '<option value=""></option><option value="'.$row->dni.'">'.$row->apellidoNombre.'</option>';
						}						
				//return $combo.$fincombo.'&nbsp;&nbsp;<a href="#openModal"> Añadir</a>';
				return $combo.$fincombo.'&nbsp;&nbsp;<a href="'.base_url('Persona/persona').'"> Añadir</a>';
			});//fin callback_add_field

			$crud->callback_add_field('adrema', function () {					
				$adrema='<input id="field-adrema"   name="adrema" type="text"  maxlength="15" style="width:130px;height:30px" />';
				return $adrema;	
			});//fin callback_add_field	

	

			$crud->callback_add_field('idEdificio', function () {
				$combo = '<select id="field-idEdificio" name="idEdificio" class="chosen-select" data-placeholder="Seleccionar Edificio" onchange="disable_input_barrio()">';
				$fincombo = '</select>';

				$this->db->select('idEdificio,descEdificio');
				$this->db->from('edificios');
				$this->db->order_by('descEdificio','asc');							
				$query=$this->db->get();
				foreach ($query->result() as $row){
						$combo .= '<option value=""></option><option value="'.$row->idEdificio.'">'.$row->descEdificio.'</option>';
						}						
				return $combo.$fincombo.'&nbsp;&nbsp;<a href="'.base_url('Edificio/edificio').'"> Añadir</a><span id="idE" style="visibility:hidden"></span>'.'  '.'<span style="font-size:12px; color:red">No agregue Barrio ni Dirección si seleccionó Edificio</span>';
			});//fin callback_add_field

			

			$crud->callback_add_field('idBarrio', function () {
				$combo = '<select id="field-idBarrio" name="idBarrio" class="chosen-select" data-placeholder="Seleccionar Barrio">';
				$fincombo = '</select>';

				$this->db->select('idBarrio,nombreBarrio');
				$this->db->from('barrios');
				$this->db->order_by('nombreBarrio','asc');							
				$query=$this->db->get();
				foreach ($query->result() as $row){
						$combo .= '<option value=""></option><option value="'.$row->idBarrio.'">'.$row->nombreBarrio.'</option>';
						}						
				return $combo.$fincombo.'&nbsp;&nbsp;<a href="'.base_url('Barrio/barrio').'"> Añadir</a>';			
			});//fin callback_add_field					


			$crud->callback_add_field('direccion', function () {									
				$direccion='<input id="field-direccion"   name="direccion" type="text"  maxlength="500" style="width:400px;height:30px" />';
				return $direccion;	
			});//fin callback_add_field	

			$crud->callback_add_field('mts2', function () {					
				$mts2='<input id="field-mts2"   name="mts2" type="number" class="numerico"  maxlength="6" style="width:75px;height:30px" />';
				return $mts2;	
			});//fin callback_add_field				

			

			$crud->callback_add_field('piso', function () {					
				$piso='<input id="field-piso" name="piso" type="text" value=""  maxlength="2" style="width:40px;height:30px" />';
				return $piso;	
			});//fin callback_add_field

			$crud->callback_add_field('depto', function () {					
				$depto='<input id="field-depto" name="depto" type="text" value="" maxlength="2" style="width:40px;height:30px"/>';
				return $depto;	
			});///fin callback_add_field	

			$crud->callback_add_field('cant_dorm', function () {					
				$dorm='<input id="field-cant_dorm" class="numerico" name="cant_dorm" type="number" value="0" min="0" maxlength="2" style="width:40px;height:30px" />';
				return $dorm;	
			});//fin callback_add_field		

			$crud->callback_add_field('cant_baño', function () {					
				$baño='<input id="field-cant_baño" class="numerico" name="cant_baño" type="number" value="0" min="0" maxlength="2" style="width:40px;height:30px" />';
				return $baño;	
			});//fin callback_add_field

			$crud->callback_add_field('nro_cochera', function () {					
				$nro_cochera='<input id="field-nro_cochera" name="nro_cochera" type="text" value="" maxlength="2" style="width:40px;height:30px"/>';
				return $nro_cochera;	
			});///fin callback_add_field				
			

			$crud->callback_add_field('caract_adicional', function () {					
				$caract='<textarea name="caract_adicional" maxlength="10000" id="field-caract_adicional"></textarea>';
				return $caract;	
			});//fin callback_add_field	


			$crud->callback_add_field('valor', function () {					
				$valor_alquiler='<input id="field-valor" class="numerico" name="valor" type="numeric" value="" maxlength="6" style="width:80px;height:30px" />';

				return $valor_alquiler;	
			});//fin callback_add_field	

			$crud->callback_add_field('valor_venta', function () {					
				$valor_venta='<input id="field-valor_venta" class="numerico" name="valor_venta" type="numeric" value="" maxlength="6" style="width:80px;height:30px" />';
				return $valor_venta;	
			});//fin callback_add_field							


			$crud->callback_add_field('observaciones', function () {
				$obserc = '<textarea name="observaciones" maxlength="300" id="field-observaciones"></textarea>';	
				return $obserc;
			});	//fin 

			$datos_requisitos="-Fotocopia DNI Locatario y Garante. (obligatorio)
-Fotocopia del Recibo de Sueldo del Garante (obligatorio)
-Fotocopia del Recibo de Sueldo o monotributo del Locatario (Justificación de Ingreso pago alquiler) NO EXCLUYENTE
Reserva valida por 5 días. Implica la presentación de la documentación requerida para la firma y la evaluación de la misma por parte del propietario.
";

			$crud->callback_add_field('requisitos', function () {
				$requisitos = '<textarea name="requisitos" style="height:150px" maxlength="500" id="field-requisitos">Fotocopia DNI Locatario y Garante, (obligatorio).
Fotocopia del Recibo de Sueldo del Garante (obligatorio), No jubilado.
Fotocopia de boleta de servicio a nombre del garante.
Fotocopia del Recibo de Sueldo o monotributo del Locatario (Justificación de Ingreso pago alquiler) NO EXCLUYENTE.
Reserva valida por 5 días. Implica la presentación de la documentación requerida para la firma y la evaluación de la misma por parte del propietario.</textarea>';	
				return $requisitos;
			});	//fin			


///////////////////////EDITAR INMUEBLE////////////////////////////////////////////
		$crud->edit_fields('idInmueble','dni','adrema','idTipoInmueble','mts2','idEdificio','idBarrio','direccion','ubicacion','piso','depto','cant_dorm','cant_baño','cochera','nro_cochera','caracteristicas','caract_adicional','condicion','operacion','valor','valor_venta','observaciones','requisitos');

		//$crud->display_as('rescinde_dentro','Rescinde dentro de');

		$crud->field_type('idInmueble','hidden');

		$crud->set_rules('idInmueble','inmueble','callback_verificar_liquidaciones');


			$crud->callback_edit_field('adrema', function ($value, $primary_key) {					
				$adrema='<input id="field-adrema"   name="adrema" type="text" value="'.$value.'"  maxlength="15" style="width:130px;height:30px" />';
				return $adrema;	
			});//fin callback_add_field					


			$crud->callback_edit_field('direccion', function ($value, $primary_key) {					
				$direccion='<input id="field-direccion"   name="direccion" type="text" value="'.$value.'"  maxlength="500" style="width:500px;height:30px" />';
				return $direccion;	
			});//fin callback_add_field	

			$crud->callback_edit_field('mts2', function ($value, $primary_key) {					
				$mts2='<input id="field-mts2"   name="mts2" type="number" class="numerico" value="'.$value.'"  maxlength="6" style="width:75px;height:30px" />';
				return $mts2;	
			});//fin callback_edit_field						
			

			$crud->callback_edit_field('piso', function ($value, $primary_key) {					
				$piso='<input id="field-piso"  name="piso" type="TEXT" value="'.$value.'" maxlength="2" style="width:40px;height:30px" />';
				return $piso;	
			});//fin callback_add_field

			$crud->callback_edit_field('depto', function ($value, $primary_key) {					
				$depto='<input id="field-depto"  name="depto" type="text" value="'.$value.'" maxlength="2" style="width:40px;height:30px">';
				return $depto;	
			});//fin callback_add_field	

			$crud->callback_edit_field('cant_dorm', function ($value, $primary_key) {					
				$dorm='<input id="field-cant_dorm" class="numerico" name="cant_dorm" type="number" value="'.$value.'" min="1" maxlength="2" style="width:40px" />';
				return $dorm;	
			});//fin callback_add_field		

			$crud->callback_edit_field('cant_baño', function ($value, $primary_key) {					
				$baño='<input id="field-cant_baño" class="numerico" name="cant_baño" type="number" value="'.$value.'" min="0" maxlength="2" style="width:40px" />';
				return $baño;	
			});//fin callback_add_field	

			$crud->callback_edit_field('nro_cochera', function ($value, $primary_key) {					
				$nro_cochera='<input id="field-nro_cochera" name="nro_cochera" type="text" value="'.$value.'" maxlength="2" style="width:40px;height:30px"/>';
				return $nro_cochera;	
			});///fin callback_add_field				

			$crud->callback_edit_field('caract_adicional', function ($value, $primary_key) {					
				$caract='<textarea name="caract_adicional" maxlength="10000" id="field-caract_adicional ">'.$value.'</textarea>';
				return $caract;	
			});//fin callback_add_field			

			$crud->callback_edit_field('valor', function ($value, $idI) {	
				/*$estado_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$estado=$estado_inmueble['estado'];
				if($estado==6){
					$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);
					$idC=$datos_contrato['idC'];
					$datos_pagos=$this->buscar_datos_model->anterior_impuestos($idC);
					$ultimo_alquiler=$datos_pagos['valor_alquiler'];
					.'&nbsp<u>último valor de alquiler</u>: '.$ultimo_alquiler
				}*/

				$valor_alquiler='<input id="field-valor" class="numerico" name="valor" type="numeric" value="'.$value.'" maxlength="6" style="width:80px;height:30px" />';
				return $valor_alquiler;	
			});//fin callback_add_field	

			$crud->callback_edit_field('valor_venta', function ($value, $primary_key) {					
				$valor_venta='<input id="field-valor_venta" class="numerico" name="valor_venta" type="numeric" value="'.$value.'" maxlength="8" style="width:80px;height:30px" />';
				return $valor_venta;	
			});//fin callback_add_field	

			$crud->callback_edit_field('observaciones', function ($value, $primary_key) {
				$obserc = '<textarea name="observaciones" maxlength="300" id="field-observaciones">'.$value.'</textarea>';	
				return $obserc;
			});	//fin 	

			$crud->callback_edit_field('requisitos', function ($value, $primary_key) {
				$requisitos = '<textarea name="requisitos" maxlength="500" style="height:150px" id="field-requisitos">'.$value.'</textarea>';	
				return $requisitos;
			});	//fin 		

			/*$crud->callback_edit_field('rescinde_dentro', function ($value, $primary_ke){			
				$rescinde_dentro='<input id="field-rescinde_dentro" name="rescinde_dentro" type="number" min="0" max="6" value="'.$value.'" maxlength="1" style="width:40px;height:30px" />';
				return $rescinde_dentro.' meses';	
			});*/ //fin callback_add_field												

///////////////////////FIN EDITAR INMUEBLE///////////////////////////////////////

			$crud->callback_read_field('direccion', function ($value, $primary_key) {	
				$idI=$primary_key;
				$this->load->model('buscar_datos_model');
				$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
				return $direccion;
			});		

			$crud->callback_read_field('idBarrio', function ($value, $primary_key) {	
				$idB=$value;
				$idI=$primary_key;
				if(isset($idB)){				
					$barrio=$this->buscar_datos_model->buscar_barrio($idB);	
				}else{					
					$idE=$this->buscar_datos_model->buscar_idE($idI);
					$edificio=$this->buscar_datos_model->buscar_edificio($idE);
					$barrio=$edificio['barrio'];				}
				return $barrio;	
			});	
	
			$crud->callback_after_insert(array($this, 'update_cant_propiedad'));

			$crud->callback_before_update(array($this,'update_barrio'));

			$crud->callback_after_update(array($this,'update_inmueble'));			

			$crud->callback_before_insert(array($this, 'sin_barrio'));

			$crud->callback_before_delete(array($this,'verificar_alquileres'));

		   $crud->set_lang_string('insert_success_message',
				 'Inmueble Registrado.
				 <script type="text/javascript">
				  window.location = "'.site_url('Inmueble/inmueble').'";
				 </script>
				 <div style="display:none">
				 '
		   );
			

			$crud->set_lang_string('update_success_message','Datos de Inmueble Actualizados.
				<script type="text/javascript">
					window.location = "'.site_url('Inmueble/inmueble').'";
				</script>
				<div style="display:none">'
		   	);	

			$crud->set_lang_string('delete_error_message','Hay Liquidaciones o CI pendientes para este inmueble, verifique!!!!');

			//$crud->set_lang_string('error_message','Cannot add the record');


			$output = $crud->render();	

			//$this->_example_output($output);
			if($crud->getState() != 'list') {				
				$this->_example_output($output);
			} else {
				return $output;
			}

		}

		function _example_output($output = null){
			$login= $this->session->userdata('usuario');
			if($login){
				$this->load->view('inicio',(array)$output);		
			}else{
				redirect('login');
			}
				
		}
			



	public function verificar_liquidaciones($idI){	
		//$idI=$this->uri->segment(4);

		$datos_alquiler=$this->buscar_datos_model->datos_contrato($idI);

		$idC=$datos_alquiler['idC'];

		$dni=$datos_alquiler['locador'];

		$dni_pendiente=$this->buscar_datos_model->buscar_locador_pendientes($idC);

		$locador=$this->buscar_datos_model->buscar_persona($dni_pendiente);

		if($dni_pendiente<>""){
			$this->form_validation->set_message('verificar_liquidaciones','Hay Liquidaciones pendientes para '.$locador.', liquide primero.'); 
			return false;		
		}else{

			if(!isset($dni)){
				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$dni=$datos_inmueble['dni'];
				$this->buscar_datos_model->actualizar_propietario($dni);
			}		
			
			return true;
		}	


	}	


	public function buscar_ajustes($value,$row){
		$idI=$row->idInmueble;
		$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
		$estado=$datos_inmueble['estado'];

		if ($estado==1){			
			$datos_alquiler=$this->buscar_datos_model->datos_contrato($idI);
			$idC=$datos_alquiler['idC'];
			$valores=$this->buscar_datos_model->buscar_ajustes_alquiler($idC);
			return '<span style="color:green; font-weight:bolder"><abbr title="Ajustes:'.$valores['1'].','.$valores['2'].','.$valores['3'].','.$valores['4'].','.$valores['5'].','.$valores['6'].'">'.$value.'</abbr></span>' ;
		}else{
			return $value;
		}
		
	}

	public function imprimir_inmueble($primary_key){
		$idI=$primary_key;
		$host=$_SERVER['SERVER_NAME'];

		$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
		$direccion=$this->buscar_datos_model->buscar_inmueble($idI);

		$idE=$this->buscar_datos_model->buscar_idE($idI);
		if(isset($idE)){
			$edificio=$this->buscar_datos_model->buscar_edificio($idE);
			$Nedificio=$edificio['edificio'];
			$Nbarrio=$edificio['barrio'];
		}else{
			$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
			$idB=$datos_inmueble['idB'];
			$barrio= $this->buscar_datos_model->buscar_barrio($idB);
		}

		$ubicacion=$datos_inmueble['ubicacion']; 
		$cochera=$datos_inmueble['cochera']; 
		$cant_dorm=$datos_inmueble['dorm']; 
		$caracteristicas=$datos_inmueble['caract_adicional'];
		$condicion=$datos_inmueble['condicion'];
		$requisitos=$datos_inmueble['requisitos'];
		$observaciones=$datos_inmueble['observaciones'];
		$valor=$datos_inmueble['valor'];
		$idI=$datos_inmueble['idI'];

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
       		 		<td align='center' valign='bottom'><b style='font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:20px'>Datos de Inmueble</b></td> 
       		 		<td align='right'><img src='http://$host/SGI/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		<tr>
        		<tr>
	        		<td colspan='2' align='center' style='height:20px;;vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragüi V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
	        		</td>	        		
        		</tr>        		
        	</table>

        	<br>

        	<table width='100%' border='0' cellpadding='0' cellspacing='0'>	
        		<tr> 
        			<td style='height:25px;vertical-align:text-top'><b><u>Inmueble</u></b>: $idI - $direccion</td>
        		</tr>	
        	";

        	if(isset($ubicacion)){
        		$html.=$dato_ubicacion=
        			"<tr><td style='height:25px;vertical-align:text-top'><b><u>Ubicación</u></b>: $ubicacion</td></tr>";
        	}         	

        	if(isset($Nedificio)){
        		$html.=$dato_edificio=
        			"<tr><td style='height:25px;vertical-align:text-top'><b><u>Edificio</u></b>: $Nedificio</td></tr>
        			<tr><td style='height:25px;vertical-align:text-top'><b><u>Barrio</u></b>: $Nbarrio</td></tr>";
        	}

        	if(isset($barrio)){
        		$html.=$dato_barrio=
        			"<tr><td style='height:25px;vertical-align:text-top'><b><u>Barrio</u></b>: $barrio</td></tr>";
        	}

        	if(isset($condicion)){
        		$html.=$dato_condicion=
        			"<tr><td style='height:25px;vertical-align:text-top'><b><u>Condición</u></b>: $condicion</td></tr>";
        	}        	

        	if(isset($cant_dorm)){
        		$html.=$dato_dorm=
        			"<tr><td style='height:25px;vertical-align:text-top'><b><u>Cant.Dormit.</u></b>: $cant_dorm</td></tr>";
        	}          	         	

        	if(isset($cochera)){
        		$html.=$dato_cochera=
        			"<tr><td style='height:25px;vertical-align:text-top'><b><u>Cochera</u></b>: $cochera</td></tr>";
        	}  

        	if(isset($caracteristicas)){
        		$html.=$dato_caract=
        			"<tr><td style='height:25px;vertical-align:text-top;text-align:justify'><b><u>Caracteristicas</u></b>: <p>$caracteristicas</p></td></tr>";
        	} 

        	if(isset($observaciones)){
        		$html.=$observac=
        			"<tr><td style='height:25px;text-align:justify'><b><u>Observaciones</u></b>: <p>$observaciones</p></td></tr>";
        	} 

        	if(isset($valor)){
        		$html.=$alquiler=
        			"<tr><td style='height:25px;text-align:justify'><b><u>Valor</u></b>: $valor</td></tr>";
        	}        	         	 

        	if(isset($requisitos)){
        		$i=substr_count($requisitos, ".");
        		$item_requisitos=explode(".", $requisitos);        		
        		$html.=$dato_requi=
        			"<tr><td>&nbsp;</td></tr><tr><td style='height:25px;vertical-align:text-top;text-align:justify'><b><u>Requisitos para Alquilar</u></b>:<br></td></tr>";
        		for($i=0;$i<6;$i++){	
        			$html.=$item_requi="<tr><td>$item_requisitos[$i].</td></tr>";
        		}	
        	}         	       	      	

			$html.=$fin="</table></body>";

        	

        	

        	$pdfFilePath = $direccion.".pdf";
 
        	// $html = $this->load->view('v_dpdf',$date,true);
 
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


public function imprimir_requisitos($primary_key){
		$idI=$primary_key;
		$host=$_SERVER['SERVER_NAME'];

		$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);

		$requisitos=$datos_inmueble['requisitos'];

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
       		 		<td align='center' valign='bottom'><b style='font-size:26px'>Taragüi Propiedades<br></b><b>Alquiler, Ventas, Tasación y Administración de Propiedades</b></td> 
       		 		<td align='right'><img src='http://$host/SGI/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		</tr>
        		<tr>
	        		<td colspan='2' align='center' style='vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragui V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
	        		</td>	        		
        		</tr>        		
        	</table>

        	<br>

        	<table width='100%' border='0' cellpadding='0' cellspacing='0'>";        	 

        	if(isset($requisitos)){
        		$i=substr_count($requisitos, ".");
        		$item_requisitos=explode(".", $requisitos);        		
        		$html.=$dato_requi=
        			"<tr><td>&nbsp;</td></tr><tr><td style='height:25px;vertical-align:text-top;text-align:justify'><b><u>REQUISITOS PARA ALQUILAR</u></b>:<br></td></tr>";
        		for($i=0;$i<6;$i++){	
        			$html.=$item_requi="<tr><td>*$item_requisitos[$i].</td></tr>";
        		}	
        	}         	       	      	

			$html.=$fin="</table></body>";

        	

        	

        	$pdfFilePath = "requisitos".".pdf";
 
        	// $html = $this->load->view('v_dpdf',$date,true);

 
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


public function imprimir_requisitos_gral(){
		$host=$_SERVER['SERVER_NAME'];

		$requisitos=$this->buscar_datos_model->buscar_requisitos();		

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
       		 		<td align='center' valign='bottom'><b style='font-size:26px'>Taragüi Propiedades<br></b><b>Alquiler, Ventas, Tasación y Administración de Propiedades</b></td> 
       		 		<td align='right'><img src='http://$host/SGI/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		</tr>
        		<tr>
	        		<td colspan='2' align='center' style='vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragui V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
	        		</td>	        		
        		</tr>        		
        	</table>

        	<br>

        	<table width='100%' border='0' cellpadding='0' cellspacing='0'>";        	 

        	if(isset($requisitos)){
        		$i=substr_count($requisitos, ".");
        		$item_requisitos=explode(".", $requisitos);        		
        		$html.=$dato_requi=
        			"<tr><td>&nbsp;</td></tr><tr><td style='height:25px;vertical-align:text-top;text-align:justify'><b><u>REQUISITOS PARA ALQUILAR</u></b>:<br></td></tr>";
        		for($i=0;$i<6;$i++){	
        			$html.=$item_requi="<tr><td>-$item_requisitos[$i].</td></tr>";
        		}	
        	}         	       	      	

			$html.=$fin="</table></body>";

        	

        	

        	$pdfFilePath = "requisitos".".pdf";
 
        	// $html = $this->load->view('v_dpdf',$date,true);

 
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

	

		public function desc_estado($value,$row){
			$idI=$row->idInmueble;
		
			if($value==0 ){
				//return 'DISPONIBLE';

				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$mascotas=$this->buscar_datos_model->se_permite_mascota($idI);
				return '<span id="estado" style="color:green; font-weight:bolder"><abbr title="Caracteristicas:'.$datos_inmueble['caract_adicional'].', Cochera: '.$datos_inmueble['cochera'].', Obser:'.$datos_inmueble['observaciones'].', Mascotas:'.$mascotas.'">DISPONIBLE</abbr></span>' ;
			}elseif($value==1 ){
				//return 'ALQUILADO';
				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);
				return '<span id="estado" style="color:red; font-weight:bolder"><abbr title="idC: '.$datos_contrato['idC'].'- Fin de contrato:'.$datos_contrato['fin'].', Caracteristicas: '.$datos_inmueble['caract_adicional'].'">ALQUILADO</abbr></span>' ;
			}elseif($value==2){
				//return 'ALQ.RESERV';
				$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);
				$reserva=$this->buscar_datos_model->buscar_idReserva($idI);
				return '<span id="estado"  style="color:#FF5733 ; font-weight:bolder"><abbr title="Nro de reserva: '.$reserva['idR'].', '.$reserva['nombre'].', $'.$reserva['sena'].', '.$reserva['fecha'].'">ALQ.RESERV</abbr></span>' ;
			}elseif($value==3){		
				$reserva=$this->buscar_datos_model->buscar_idReserva($idI);		
				//return 'DISP.RESERV';
				return '<span id="estado" style="color:#FF5733 ; font-weight:bolder"><abbr title="Nro de reserva: '.$reserva['idR'].', '.$reserva['nombre'].', $'.$reserva['sena'].', '.$reserva['fecha'].'">DISP.RESERV</abbr></span>' ;
			}elseif($value==4){
				$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);
				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$dni=$datos_inmueble['renueva'];
				$locatario1=$this->buscar_datos_model->buscar_persona($dni);
				//$locatario2=$this->buscar_datos_model->buscar_persona($dni);
				return '<span id="estado"  style="color:#FF5733 ; font-weight:bolder"><abbr title="Locatario:'.$locatario1.' - idC:'.$datos_contrato['idC'].$datos_contrato['oper'].'">ALQ.RENUEV</abbr></span>';		
			}elseif($value==5){
				//return "ALQ.NO.REN";
				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$dni=$datos_inmueble['renueva'];
				$locatario1=$this->buscar_datos_model->buscar_persona($dni);				
				return '<span id="estado"  style="color:#FF5733 ; font-weight:bolder"><abbr title="Locatario:'.$locatario1.'">ALQ.NO.REN</abbr></span>';

			}elseif($value==6){
				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$dni=$datos_inmueble['renueva'];
				$locatario1=$this->buscar_datos_model->buscar_persona($dni);				
				return '<span id="estado"  style="color:#FF5733 ; font-weight:bolder"><abbr title="Locatario:'.$locatario1.'">DISP.RENUEV</abbr></span>';
			}elseif($value==7){
				$datos_inmueble=$this->buscar_datos_model->datos_inmueble($idI);
				$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);
				$dni=$datos_inmueble['renueva'];
				$locatario1=$this->buscar_datos_model->buscar_persona($dni);				
				return '<span id="estado"  style="color:#FF5733 ; font-weight:bolder"><abbr title="Rescinde en: '.$datos_contrato['rescinde_fecha'].'">ALQ.RESCINDE</abbr></span>';
			}				
		}

		public function sin_barrio($post_array){
			$edificio=$post_array['idEdificio'];
			$barrio=$post_array['idBarrio'];
			if($edificio =="" and $barrio ==""){
				$post_array['idBarrio']=58;
			}elseif($edificio ==""){
				$post_array['idBarrio']=$barrio;
			}else{
				$idE=$post_array['idEdificio'];
				$edificio=$this->buscar_datos_model->buscar_edificio($idE);
				$idB=$edificio['idB'];
				$post_array['idBarrio']=$idB;
			}

			//$post_array['inmueble']=$post_array['idInmueble'];

			return $post_array;
		}

		public function verificar_alquileres($primary_key){//antes de borrar un inmueble verificar alquileres y liquidaciones pendientes
			$idI=$primary_key;
			$verificar=$this->buscar_datos_model->verificar_alquileres($idI);

			if($verificar[0]=="SI" or $verificar[1]>0 ){//compruebo si hay liquidaciones pendientes o se debe CI				
				return false;
			}else{
				return true; 
			}

			
			
		}

		public function update_barrio($post_array, $primary_key){
			$edificio=$post_array['idEdificio'];
			$idI=$primary_key;

			$locador_update=$post_array['dni'];

			$datos_alquiler=$this->buscar_datos_model->datos_contrato($idI);

			$idC=$datos_alquiler['idC'];

			$locador_pendiente=$this->buscar_datos_model->buscar_locador_pendientes($idC);

			$barrio=$post_array['idBarrio'];
			if($barrio ==""){
				$post_array['idBarrio']=58; //SIN DATO
			}



			return $post_array;			

		}

		public function buscar_direccion($value,$row){
			$idI= $row->idInmueble;
			$direccion=$this->buscar_datos_model->buscar_inmueble_2($idI);
			return $direccion;
		}

		public function buscar_barrio($value,$row){
			$idE=$row->idEdificio;
				$idB=$value;
				$barrio=$this->buscar_datos_model->buscar_barrio($idB);
				return $barrio;
		}
					

		public function update_cant_propiedad($post_array){
			$locador=$post_array['dni'];

			$this->db->set('tipo_persona','PROPIETARIO');				
			$this->db->where('dni',$locador);
			$this->db->update('personas');	

			$this->db->set('pendientes','NO');				
			$this->db->where('dni',$locador);
			$this->db->update('personas');	
		}

		public function update_inmueble($post_array,$primary_key){
			//$idI=$post_array['idInmueble'];	
			$idI=$this->uri->segment(4);
			$locador=$post_array['dni'];

			$this->db->set('tipo_persona','PROPIETARIO');
			$this->db->where('dni',$locador);
			$this->db->update('personas');

			$this->db->set('locador',$locador);
			$this->db->where('idInmueble',$idI);
			$this->db->update('alquileres');

			return true;
		}
}

