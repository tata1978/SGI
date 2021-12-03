<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion1 extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->config->load('grocery_crud');
	}
	public function index(){	
			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	
	}
	public function descargar_pdf($idL){
			$data = [];

			$this->db->select('idpago,idContrato,locador,locatario,alquiler,punitorios,comiAdmin,descArreglos,expExtras,totalPagar');
			$this->db->from('liquidaciones');
			$this->db->where('idLiquida',$idL);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idP = $row->idpago;
				$idC=$row->idContrato;
				$locador=$row->locador;
				$locatario=$row->locatario;
				$alquiler=$row->alquiler;
				$punitorios=$row->punitorios;
				$comiAdmin=$row->comiAdmin;
				$descArreglos=$row->descArreglos;
				$expExtras=$row->expExtras;
				$totalPagar=$row->totalPagar;
			}	

			$this->db->select('periodo');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$periodo = $row->periodo;			
			}	

			$this->db->select('idInmueble,locador,locatario, fechaFin,');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idI = $row->idInmueble;
				$locador = $row->locador;
				$locatario=$row->locatario;
				$fechaF=$row->fechaFin;				
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
				$tipoinmueble = $row->nombreTipo;		
			}

			$this->db->select('apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$locador);		
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$locador = $row->apellidoNombre;
			}

			$this->db->select('apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$locatario);		
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$locatario = $row->apellidoNombre;
			}

			$hoy = date("dmyhis");

        	//load the view and saved it into $html variable
        	$html = 
        	"<style>@page {        		
			    margin-top: 1cm;
			    margin-bottom: 1.27cm;
			    margin-left: 1.27cm;
			    margin-right: 1.27cm;
			}
			</style>".
       		 "<body>
        	<div style='color:black;'><h1> Taragüi Propiedades </h1></div>".
        		"<div> Córdoba 682 - Corrientes Capital - Tel: 0379-4423771 - correo electrónico: admtaragui@gmail.com</div>
        			<hr>
        		<table border='0' cellpadding='0' cellspacing='0'>
        			<tr >
        				<td style='height:100px'><u><b>RECIBO: </b></u></td>
        				<td style='height:100px'>de cobro de alquiler, correspondiente al periodo: <b>$periodo</b> del $tipoinmueble en <b>$direccion - $piso - $depto</b> </td>
        			</tr>
        		</table>
        		<table border='0' cellpadding='0' cellspacing='0'>	
        			<tr >
        				<td ><u><b>LOCADOR:</b> </u></td>
        				<td > $locador </td>
        			</tr>
        			<tr>
        				<td style='height:25px'><u><b>LOCATARIO:</b> </u></td>
        				<td style='height:25px'> $locatario </td>
        			</tr> 
        		</table>
        		<table border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
        				<td style='height:50px' ><u><b>DETALLE:</b></u></td>
        			</tr>
        			<tr>	
        				<td  > Alquiler: </td>
        				<td align='right' > $$alquiler.00 </td>
        			</tr>
        			<tr>	
        				<td  > Punitorios: </td>
        				<td align='right'> $$punitorios </td>
        			</tr> 
        			<tr>	
        				<td style='width:90%' > Comision por Administración: </td>
        				<td align='right'> -$$comiAdmin </td>
        			</tr>
        			<tr>	
        				<td  > Arreglos: </td>
        				<td align='right' > -$$descArreglos </td>
        			</tr>
        			<tr>	
        				<td  > Expensas Extra.: </td>
        				<td align='right' > -$$expExtras </td>
        			</tr>         			
        			  			          			       			          			
        		</table>
        		<hr style='width:100%' text-align='left'>
        		<table border='0' cellpadding='0' cellspacing='0'>
        		    <tr>	
        				<td style='width:90%'  ><b> TOTAL A RECIBIR:</b> </td>
        				<td align='right' > $$totalPagar </td>
        			</tr>
        		</table>	
        	</body>";

        	// $html = $this->load->view('v_dpdf',$date,true);
 		
 			//$html="asdf";
        	//this the the PDF filename that user will get to download
        	$pdfFilePath = "cipdf_".$hoy.".pdf";
 
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

	public function pagos($id){
		$this->config->load('grocery_crud');
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		//$this->config->set_item('grocery_crud_default_per_page',10);			

		$output = $this->pagos_management($id);	

		$this->db->select('idInmueble,locatario,locador');
		$this->db->where('idContrato',$id);
		$this->db->from('alquileres');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$locatario=$row->locatario;
			$dniL=$row->locador;
		}

		$this->db->select('direccion,piso,depto');
		$this->db->where('idInmueble',$idI);
		$this->db->from('inmuebles');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$dire=$row->direccion;
			$piso=$row->piso;
			$depto=$row->depto;
		}
		$direccion=$dire.' - '.$piso.' - '.$depto;


		/*$this->db->select('locador');
		$this->db->where('idContrato',$id);
		$this->db->from('pagos');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$dniL=$row->locador;
		}*/

		$this->db->select('apellidoNombre');
		$this->db->where('dni',$dniL);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locador = $row->apellidoNombre;
		}	


		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locatario);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locatario = $row->apellidoNombre;
		}				

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "<table  border='0' width='100%'>
					<tbody>
						<tr>
							<th bgcolor='#016700' width='13%'  style='color: #FFFFFF; text-align: center; font-size:17px;font-weight: normal'>   		
								<div class='fecha'></div>
    	 					</th>						
							<th id='header'><h4>LIQUIDACIÓN - INMUEBLE: <span class='texto'>$direccion</span> - LOCADOR: <span class='texto'>$locador</span> - LOCATARIO: <span class='texto'>$locatario</span></h4> </th>
							<th  id='hora' bgcolor='#016700' width='10%' style='color: #FFFFFF; text-align: center;font-size:17px;font-weight: normal'><div id='contenedor'></div>
							</th>							
						</tr>	
					</tbody>	
				</table>".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}

	public function pagos_management($id){
		//$this->config->set_item('grocery_crud_dialog_forms',true);
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');				

			$crud->where('idContrato',$id);
			$crud->where('pagado_propietario',"NO");
			$crud->unset_operations();

			$crud->columns('periodo','fecha_pago','valor_alquiler','punitorios','expensas','csp','luz','agua','saldos_otros','debe_c_inmo','total_pagar');
				

			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('periodo','Periodo');
			$crud->display_as('fecha_pago','Pagado');
			$crud->display_as('valor_alquiler','Alquiler');
			$crud->display_as('punitorios','Interes');
			$crud->display_as('mora_dias','Mora a la fecha');
			$crud->display_as('paga_mora','¿Paga mora?');
			$crud->display_as('saldos_otros','Varios');
			$crud->display_as('total_pagar','Total');
			$crud->display_as('debe_c_inmo','CI');
			$crud->display_as('fechaUltimoPago','Ultimo Pago, Periodo');
			$crud->display_as('pagado_propietario','¿Pagado a Propietario?');

			$crud->add_action('Liquidar', '', 'Liquidacion/liquidar/add','ui-icon-calculator');

			$output = $crud->render();	
			//$this->_example_output($output);
			if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}
	}


	public function liquidaciones_anteriores($idC){
		$this->config->load('grocery_crud');
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		//$this->config->set_item('grocery_crud_default_per_page',10);			
		//$idP=$this->uri->segment(4);
			
		$output = $this->liquidar_anteriores_management($idC);	

		$this->db->select('idInmueble,locador,locatario');
		$this->db->where('idContrato',$idC);
		$this->db->from('alquileres');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$dlocador=$row->locador;			
			$dlocatario=$row->locatario;
		}

		$this->db->select('direccion,piso,depto');
		$this->db->where('idInmueble',$idI);
		$this->db->from('inmuebles');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$dire=$row->direccion;
			$piso=$row->piso;
			$depto=$row->depto;
		}
		$direccion=$dire.' - '.$piso.' - '.$depto;


		$this->db->select('apellidoNombre');
		$this->db->where('dni',$dlocador);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locador = $row->apellidoNombre;
		}	


		/*$this->db->select('apellidoNombre');
		$this->db->where('dni',$dlocatario);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locatario = $row->apellidoNombre;
		}*/	

			$js_files =$output->js_files; 
			$css_files =  $output->css_files; 
			$output = "<table  border='0' width='100%'>
						<tbody>
							<tr>
								<th bgcolor='#016700' width='15%'  style='color: #FFFFFF; text-align: center; font-size:17px;font-weight: normal'>   		
									<div class='fecha'></div>
	    	 					</th>
								<th id='header'><h4>LIQUIDAC. ANTERIORES - INMUEBLE: <span class='texto'>$direccion</span>  -  LOCADOR: <span class='texto'>$locador</span></h4> </th>
								<th  id='hora' bgcolor='#016700' width='10%' style='color: #FFFFFF; text-align: center;font-size:17px;font-weight: normal'><div id='contenedor'></div>
								</th>
							</tr>	
						</tbody>	
					</table>".$output->output;

			$this->_example_output((object)array(
					'js_files' => $js_files,
					'css_files' => $css_files,
					'output'	=> $output
			));	
	}

	public function liquidar_anteriores_management($idC){
		/*$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);*/	
		$crud=new grocery_CRUD();
		$crud->set_table('liquidaciones');	
		$crud->set_subject('Liquidacion');	

		//$crud->set_relation('idpago','pagos','periodo');
		$crud->where('idContrato',$idC);


		$crud->columns('idpago','locatario','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');
		$crud->fields('idContrato','locador','locatario','idpago','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');
		$crud->display_as('idContrato','Inmueble');	
		$crud->display_as('idpago','Periodo');		
		$crud->display_as('comiAdmin','Comision por Administración');
		//$crud->display_as('comodin','<b><u>Descuentos al Propietario</b></u>');
		//$crud->display_as('comodin2','<b><u>Total a Pagar al Propietario</b></u>');
		$crud->display_as('descArreglos','Arreglos');
		$crud->display_as('expExtras','Expensas Extr.');
		$crud->display_as('totalPagar','Monto');

		//$crud->unset_operations();
		//$crud->unset_edit();
		$crud->add_action('Imprimir', '', 'Liquidacion/descargar_pdf','ui-icon-print');
		$crud->add_action('Ver', '', 'Liquidacion/liquidar/read','ui-icon-document');
		$crud->add_action('Editar', '', 'Liquidacion/liquidar/edit','ui-icon-pencil');				

		$crud->callback_column('idpago',array($this,'buscar_periodo'));



		$output = $crud->render();

		if($crud->getState() == 'success') {
			$idC=$this->uri->segment(3);
			redirect('Liquidacion/liquidaciones_anteriores/'.$idC);
		}		

		if($crud->getState() != 'list') {
				$this->_example_output($output);
		} else {
			return $output;
		}	


	}

	public function buscar_periodo($value,$row){
		$idP=$row->idpago;		
		$this->db->select('periodo');
		$this->db->from('pagos');		
		$this->db->where('idpago',$idP);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$periodo = $row->periodo;
		}
		return $periodo;		
	}

	public function liquidar(){
		$crud=new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('liquidaciones');	
		$crud->set_subject('Liquidacion');

		if(!isset($idP)){
			$id=$this->uri->segment(4);
			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$id);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$idC=$row->idContrato;
			}
			//redirect('Liquidacion/pagos/'.$idC);
			if (isset($idC)){
				$crud->set_crud_url_path(site_url('Liquidacion/pagos/'.$idC));
			}	
		}	
		//$crud->set_crud_url_path(site_url('Liquidacion/pagos'));
		//$crud->unset_add();
		if (isset($idP)){
			$crud->where('liquidaciones.idContrato',$idP);			
		}	



		$crud->set_relation('idpago','pagos','periodo');

		$crud->set_relation('idContrato','alquileres','idContrato');
		$crud->set_relation('locador','personas','apellidoNombre');
		$crud->set_relation('locatario','personas','apellidoNombre');

		

		$crud->columns('idContrato','idpago','locador','locatario','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');
		$crud->fields('idContrato','locador','locatario','idpago','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');
		$crud->display_as('idContrato','Inmueble');	
		$crud->display_as('idpago','Periodo');		
		$crud->display_as('comiAdmin','Comision por Administración');
		//$crud->display_as('comodin','<b><u>Descuentos al Propietario</b></u>');
		//$crud->display_as('comodin2','<b><u>Total a Pagar al Propietario</b></u>');
		$crud->display_as('descArreglos','Arreglos');
		$crud->display_as('expExtras','Expensas Extr.');
		$crud->display_as('totalPagar','Monto');

		//$crud->field_type('periodo','invisible');

		$crud->required_fields('alquiler','totalPagar');


		$idL=$this->uri->segment(4);
		$this->db->select('idContrato');
		$this->db->from('liquidaciones');
		$this->db->where('idLiquida',$idL);
		$query = $this->db->get();
		foreach ($query->result() as $row){
			$idC=$row->idContrato;
		}

		if(isset($idC)){
			$crud->set_crud_url_path(site_url('Liquidacion/liquidaciones_anteriores/'.$idC));
		}	
		//$crud->edit_fields('idpago','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');



		$crud->callback_add_field('idContrato', function () {
			$idP=$this->uri->segment(4);
			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idC = $row->idContrato;															
			}

			$this->db->select('idInmueble');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);		
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
			$combo = $tipo_inmueble.' en :'.'<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto"><option value = "'.$idC.'">'.strtoupper($direccion).' - '.$piso.' - '.$depto.'</option></select>';
			return $combo;
		});//cierro callback_add_field	

		$crud->callback_add_field('locador', function () {
			$idP=$this->uri->segment(4);
			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idC = $row->idContrato;															
			}

			$this->db->select('locador');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idL = $row->locador;															
			}

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$idL);							
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$dni = $row->dni;															
				$locador = $row->apellidoNombre;
			}
			return '<select id="field-locador" class="form-control"  name="locador"><option value="'.$dni.'">'.$locador.'</option></select>';	
		});//fin callback_add_field

		$crud->callback_add_field('locatario', function () {
			$idP=$this->uri->segment(4);
			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idC = $row->idContrato;															
			}

			$this->db->select('locatario');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idL = $row->locatario;															
			}

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$idL);							
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$dni = $row->dni;															
				$locatario = $row->apellidoNombre;
			}
			return '<select id="field-locatario" class="form-control"  name="locatario"><option value="'.$dni.'">'.$locatario.'</option></select>';	
		});//fin callback_add_field		



		$crud->callback_add_field('idpago', function () {
			$idP=$this->uri->segment(4);
			$this->db->select('periodo');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$periodo = $row->periodo;															
			}
			
			return '<select id="field-idpago" class="chosen-select"  name="idpago"><option value="'.$idP.'">'.$periodo.'</option></select>';
		});  //fin callback_add_field	


		$crud->callback_add_field('alquiler', function () {	
			$idP=$this->uri->segment(4);
			$this->db->select('valor_alquiler');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$valor = $row->valor_alquiler;															
			}

			$valor_alquiler = '<span id="b">$</span><input id="field-alquiler" name="alquiler" type="text" value="'.$valor.'" maxlength="10" style="width:80px;height:30px"  />';						
			return $valor_alquiler;
		});//cierro callback_add_field

		$crud->callback_add_field('punitorios', function () {	
			$idP=$this->uri->segment(4);
			$this->db->select('punitorios,valor_alquiler');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$punitorios = $row->punitorios;	
				$valor=$row->valor_alquiler;														
			}
			$input_punitorios = '<span id="b">$</span><input id="field-punitorios" name="punitorios" type="text" value="'.$punitorios.'" maxlength="10" style="width:80px;height:30px"  />';	
			$subtotal=' Subtotal: '.($punitorios + $valor);						
			return $input_punitorios.$subtotal;
		});//cierro callback_add_field	

		/*$crud->callback_add_field('comodin', function () {	
							
			return '' ;
		});*///cierro callback_add_field

		$crud->callback_add_field('comiAdmin', function () {	
			$idP=$this->uri->segment(4);					
			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get();													
			foreach ($query->result() as $row){							
				$idC = $row->idContrato;							
			}
			$this->db->select('valor_alquiler');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get();													
			foreach ($query->result() as $row){							
				$valor = $row->valor_alquiler;							
			}			
			$this->db->select('comision_admin');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){				
				$comi_porc =  $row->comision_admin;														
			}	

			$this->db->select('punitorios');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$punitorios = $row->punitorios;															
			}
			$comision=($valor + $punitorios) * ($comi_porc / 100);
			$input_comi_admin = '<span id="b">$</span><input id="field-comiAdmin" name="comiAdmin" type="text" value="'.$comision.'" maxlength="10" style="width:80px;height:30px"/> ';			
			$comi_admin= $comi_porc.' % ';
			return $input_comi_admin.$comi_admin;
		});//cierro callback_add_field	


		$crud->callback_add_field('descArreglos', function () {			
			$desc_arreglos = '<span id="b">$</span><input id="field-descArreglos" name="descArreglos" type="text" value="0" maxlength="5" style="width:80px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)"/>';
			$textarea='</br><textarea></textarea>';
			return $desc_arreglos;
		});//cierro callback_add_field		

		$crud->callback_add_field('expExtras', function () {			
			$exp_extras = '<span id="b">$</span><input id="field-expExtras" name="expExtras" type="text" value="0" maxlength="5" style="width:80px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)"/>';
			return $exp_extras;			
		});//cierro callback_add_field	

		/*$crud->callback_add_field('comodin2', function () {							
			return '' ;
		});*///cierro callback_add_field

		$crud->callback_add_field('totalPagar', function () {			
			$total = '<span id="b">$</span><input id="field-totalPagar" name="totalPagar" type="text" value="" maxlength="10" style="width:110px;height:40px" style="font-weight:bold" class="numerico"/>';

		$boton_calcular='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="calcular" value="CALCULAR" class="ui-input-button">';

		$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

			return $total.$boton_calcular.$boton_limpiar;
		});//cierro callback_add_field*/


		////////////////////////////////////////
		/////CUANDO SE EDITA LA LIQUIDACION////
		//////////////////////////////////////
		$crud->callback_edit_field('idContrato', function ($value,$id) {			
			//$this->config->set_item('miid', $value);
			$this->session->set_flashdata('miid', $value);

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


		$idL=$this->uri->segment(4);
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();		
		if($state == 'success'){
			$this->db->select('idContrato');
			$this->db->from('liquidaciones');
			$this->db->where('idLiquida',$idL);			
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idC = $row->idContrato;			
			}			
			redirect('Liquidacion/liquidaciones_anteriores/'.$idC);	
		}

		//$crud->callback_before_insert(array($this,'set_periodo'));
		$crud->callback_after_insert(array($this,'update_pendiente'));

		$crud->callback_after_update(array($this,'update_pendiente_update'));

		

		$output = $crud->render();
		$this->_example_output($output);

	}//CIERRA FUNCION LIQUIDAR()

	/*public function set_periodo($post_array){
		$idP=$this->uri->segment(4);
		$this->db->select('periodo');
		$this->db->from('pagos');
		$this->db->where('idpago',$idP);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$periodo = $row->periodo;
		}

		$post_array['periodo']=$periodo;		
		return $post_array;		
	}*/

	public function update_pendiente($post_array,$primary_key){
		$idC=$post_array['idContrato'];
		$idP=$post_array['idpago'];		
		$this->db->set('pagado_propietario',"SI");				
		$this->db->where('idContrato',$idC);
		$this->db->where('idpago',$idP);
		$this->db->update('pagos');

		$dniL=$post_array['locador'];
		$this->db->set('pendientes',"NO");				
		$this->db->where('dni',$dniL);
		$this->db->update('personas');		
	}


	public function update_pendiente_update($post_array,$primary_key){
		$idC=$post_array['idContrato'];
		$idP=$post_array['idpago'];		
		$this->db->set('pagado_propietario',"SI");				
		$this->db->where('idContrato',$idC);
		$this->db->where('idpago',$idP);
		$this->db->update('pagos');

		$dniL=$post_array['locador'];
		$this->db->set('pendientes',"NO");				
		$this->db->where('dni',$dniL);
		$this->db->update('personas');		
	}	

	function _example_output($output = null){
		$this->load->view('inicio',(array)$output);			
	}
}//CIERRA CLASE	