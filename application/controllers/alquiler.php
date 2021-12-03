<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alquiler extends CI_Controller
{

	public function __construct()
	{
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
	public function index()
	{
		$this->_example_output((object)array('output' => '', 'js_files' => array(), 'css_files' => array()));
	}

	public function buscar_reclamo($idR)
	{
		echo json_encode($this->buscar_datos_model->buscar_reclamo($idR));
	}

	public function imprimir_requisitos()
	{
		$this->db->select('requisitos');
		$this->db->from('requisitos');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$requisitos = $row->requisitos;
		}
		$host = $_SERVER['SERVER_NAME'];

		$html =
			"<style>@page {        		
			    margin-top: 1cm;
			    margin-bottom: 1cm;
			    margin-left: 1.27cm;
			    margin-right: 1.27cm;
			}
			</style>" .
			"<body>
       		 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
       		 	<tr>
       		 		<td align='center'><b style='font-size:26px'>Taragüi Propiedades</b></td> 
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
        			<td style='height:25px;vertical-align:text-top'><b><u>Requisitos para Alquilar</u></b>: $requisitos</td>
        		</tr>	
        	";



		/*if(isset($requisitos)){
        		$i=substr_count($requisitos, ".");
        		$item_requisitos=explode(".", $requisitos);        		
        		$html.=$dato_requi=
        			"<tr><td>&nbsp;</td></tr><tr><td style='height:25px;vertical-align:text-top;text-align:justify'><b><u>Requisitos para Alquilar</u></b>:<br></td></tr>";
        		for($i=0;$i<6;$i++){	
        			$html.=$item_requi="<tr><td>$item_requisitos[$i].</td></tr>";
        		}	
        	}  */

		$html .= $fin = "</table></body>";





		$pdfFilePath = "requisitos.pdf";

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

	public function guardar_requisitos()
	{
		$this->buscar_datos_model->guardar_requisitos();
	}

	public function anular_contrato($idC)
	{
		$idI = $this->buscar_datos_model->buscar_idI($idC);
		$this->buscar_datos_model->eliminar_alquiler($idC);

		$this->db->set('estado', 0);
		$this->db->where('idInmueble', $idI);
		$this->db->update('inmuebles');

		redirect('Alquiler/alquiler');
	}

	public function deudores_reportes()
	{
		$this->config->load('grocery_crud');

		$output = $this->deudores_reportes_management();

		$js_files = $output->js_files;
		$css_files =  $output->css_files;
		$output = "" . $output->output;

		$this->_example_output((object)array(
			'js_files' => $js_files,
			'css_files' => $css_files,
			'output'	=> $output
		));
	}

	public function deudores_reportes_management()
	{
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		$crud = new grocery_CRUD();

		//$crud->set_model('reportes_model');				

		$crud->set_table('alquileres');
		$crud->set_subject('Deudores');

		$crud->set_relation('locatario1', 'personas', 'apellidoNombre');
		$crud->set_relation('locador', 'personas', 'apellidoNombre');

		/*$crud->where('(estado_contrato ="VIGENTE" OR estado_contrato ="FINALIZA" OR estado_contrato ="RENUEVA")',null,FALSE);*/

		$hoy = date('Y-m-d');

		//$crud->where('proxVenc <','2021/12/01');
		$crud->where('proxVenc <', $hoy);
		$crud->where('estado_contrato', 'VIGENTE');

		//$crud->group_by('tipoProblema');

		$crud->unset_operations();

		$crud->display_as('idInmueble', 'Inmueble');
		$crud->display_as('punitorio', 'Mora diaria en %');
		$crud->display_as('comision_admin', 'Comision por Admin. en %');
		$crud->display_as('comision_inmo_a_pagar', 'Comision-Inm. a Pagar');
		$crud->display_as('fechaPago', 'Dia de Pago');
		$crud->display_as('fechaInicio', 'Inicio');
		$crud->display_as('fechaFin', 'Fin');
		$crud->display_as('duracion', 'Duracion');
		$crud->display_as('estado_alquiler', '');
		$crud->display_as('proxVenc', 'Próximo-Vto');
		$crud->display_as('ajuste', 'Ajuste en %');
		$crud->display_as('tipo_ajuste', 'Tipo de Ajuste');
		$crud->display_as('fecha_firma', 'Firma de Contrato');
		$crud->display_as('estado_contrato', 'Contrato');
		$crud->display_as('idContrato', 'Contrato');
		$crud->display_as('sellado_contrato', 'Sellados de Contrato Total');
		$crud->display_as('locatario1', 'Locatario');
		$crud->display_as('proxVenc', 'Venc. Actual');

		$crud->columns('idContrato', 'idInmueble', 'edificio', 'locatario1', 'locador', 'Ultimo Pago', 'contacto');

		$crud->callback_column('idInmueble', array($this, 'buscar_direccion'));

		$crud->callback_column('edificio', array($this, 'buscar_edificio'));

		$crud->callback_column('Ultimo Pago', array($this, 'buscar_ultimo_pago'));

		$crud->callback_column('proxVenc', array($this, 'buscar_venc_actual'));

		$crud->callback_column('contacto', array($this, 'buscar_contacto'));

		$output = $crud->render();
		//$this->_example_output($output);
		if ($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}

	public function buscar_venc_actual($value, $row)
	{
		$dia = substr($value, 8, 2);
		$venc_actual = $this->buscar_datos_model->vencimiento_actual();
		return $venc_actual;
	}

	public function buscar_ultimo_pago($value, $row)
	{
		$idC = $row->idContrato;
		$ultimo_periodo_pago = $this->buscar_datos_model->buscar_ultimo_pago($idC);
		return $ultimo_periodo_pago;
	}



	public function imprimir_deudores_reportes()
	{

		$morosos = $this->buscar_datos_model->buscar_inquilinos_morosos();

		$inmueble = $morosos[0];
		$locatario = $morosos[1];
		$contacto = $morosos[2];
		$ultimo_pago = $morosos[3];
		$venc_actual = $morosos[4];



		$html =
			"<style>@page {        		
									    margin-top: 1cm;
									    margin-bottom: 1cm;
									    margin-left: 1.27cm;
									    margin-right: 1.27cm;
									}

								    .filas_reporte {
										font-family: Verdana, Arial, Helvetica, sans-serif;
										font-size:12px;	
										border-collapse:collapse;															

										}	
										.filas_reporte td{
											padding: 3px;								
										}
										.titulo_reporte th{
											padding: 3px;
										}	
										.u_pago{
											text-align: center;
										}

										.v_actual{
											text-align: center;
										}																												
									</style>" .
			"<body>
						       		 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
						       		 	<tr>
						       		 		<td align='center' valign='bottom'><b style='font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:18px'>Reporte de Inquilinos Morosos</b></td> 			       		 		
						        		</tr>
						        		<tr>
							        		<td colspan='2' align='center' style='vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragui V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
							        		</td>	        		
						        		</tr>        		
						        	</table>	
						        	<br>	
						        	<br>	
						        	<b>Vencimiento Actual: $venc_actual</b>	
									<table width='100%' border='1' cellpadding='0' cellspacing='0' class='filas_reporte'>
										<tr style='background:#CBCBCB' class='titulo_reporte'>
											<th>Locatario</th>
											<th>Inmueble</th>
											<th>Ult.Pago</th>											
											<th>Contacto</th>
										</tr>														
									";
		asort($locatario);
		foreach ($locatario as $idI => $nombre) {

			$dato_requi .=
				"<tr class='filas_reporte'>
								<td height='25'>$nombre</td>
								<td height='25'>$inmueble[$idI]</td>
								<td height='25' class='u_pago'>$ultimo_pago[$idI]</td>								
								<td height='25' align='center'>$contacto[$idI]</td>
							</tr>";
		}

		$html .= $dato_requi;
		$html .= $fin = "</table></body>";

		$hoy = date('Y-m-d');

		$pdfFilePath = "reporte_morosos_" . $hoy . ".pdf";
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

	public function requisitos_alquiler()
	{
		/*$this->config->load('grocery_crud');
			$this->config->set_item('grocery_crud_dialog_forms',true);
			$this->config->set_item('grocery_crud_default_per_page',10);	*/

		$output = $this->requisitos_alquiler_management();

		$js_files = $output->js_files;
		$css_files =  $output->css_files;
		$output = "" . $output->output;

		$this->_example_output((object)array(
			'js_files' => $js_files,
			'css_files' => $css_files,
			'output'	=> $output
		));
	}

	public function requisitos_alquiler_management()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('alquileres');
		$crud->set_subject('Alquiler');

		$crud->columns('idContrato', 'fechaInicio', 'fechaFin', 'idInmueble', 'edificio', 'locatario1', 'locador', 'estado_contrato');

		$crud->unset_add();
		$crud->unset_export();
		$crud->unset_print();
		$crud->unset_edit();
		$crud->unset_read();
		$crud->unset_delete();

		/*$crud->add_action('Ver','','Alquiler/read','ui-icon-document');	
			$crud->add_action('Pagos','','Verpago/verpago_finalizados','ui-icon-calculator');
			$crud->add_action('Eliminar','','Alquiler/eliminar_alquiler','ui-icon-trash');*/

		$output = $crud->render();
		if ($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}


	public function alquileres_finalizados()
	{
		//$this->config->load('grocery_crud');
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		//$this->config->set_item('grocery_crud_default_per_page',10);	

		$output = $this->alquiler_finalizados_management();

		$js_files = $output->js_files;
		$css_files =  $output->css_files;
		$output = "" . $output->output;

		$this->_example_output((object)array(
			'js_files' => $js_files,
			'css_files' => $css_files,
			'output'	=> $output
		));
	}

	public function alquiler_finalizados_management()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('alquileres');
		$crud->set_subject('Alquiler');

		$crud->set_relation('locatario1', 'personas', 'apellidoNombre');
		$crud->set_relation('locatario2', 'personas', 'apellidoNombre');
		$crud->set_relation('garante1', 'personas', 'apellidoNombre');
		$crud->set_relation('garante2', 'personas', 'apellidoNombre');
		$crud->set_relation('garante3', 'personas', 'apellidoNombre');
		$crud->set_relation('garante4', 'personas', 'apellidoNombre');
		$crud->set_relation('locador', 'personas', 'apellidoNombre');

		$crud->where('(estado_contrato ="FINALIZADO" OR estado_contrato ="RESCINDIDO")', null, FALSE);

		$crud->columns('idContrato', 'fechaInicio', 'fechaFin', 'idInmueble', 'edificio', 'locatario1', 'locador', 'estado_contrato');

		$crud->display_as('idContrato', 'idC');
		$crud->display_as('locatario1', 'Locatario');
		$crud->display_as('idInmueble', 'Inmueble');
		$crud->display_as('estado_contrato', 'Contrato');

		$crud->callback_column('idInmueble', array($this, 'buscar_direccion'));

		$crud->callback_column('edificio', array($this, 'buscar_edificio'));

		$crud->callback_column('estado_contrato', array($this, 'estado_contrato'));

		$crud->unset_add();
		$crud->unset_export();
		$crud->unset_print();
		$crud->unset_edit();
		$crud->unset_read();
		$crud->unset_delete();

		$crud->add_action('Ver', '', 'Alquiler/read', 'ui-icon-folder-open');
		$crud->add_action('Pagos', '', 'Verpago/verpago_finalizados', 'ui-icon-calculator');

		$crud->add_action('Eliminar', '', 'Alquiler/eliminar_alquiler', 'ui-icon-trash');

		$crud->callback_before_delete(array($this, 'verificar_liquidaciones'));


		$crud->set_lang_string('delete_error_message', 'Hay Liquidaciones pendientes para este Alquiler, verifique!!!!');

		$output = $crud->render();
		if ($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}


	public function verificar_liquidaciones($idC)
	{
		//verificar que no haya liquidaciones pendientes

		$verificar = $this->buscar_datos_model->buscar_pagos_pendientes($idC);

		if ($verificar == "SI") {
			return false;
		} else {
			return true;
		}
	}

	public function eliminar_alquiler($idC)
	{
		$host = $_SERVER['SERVER_NAME'];
		//verificos si tiene liquidaciones pendientes a propietarios
		$pagos_pendientes = $this->buscar_datos_model->buscar_pagos_pendientes($idC);
		if ($pagos_pendientes == "NO") {
			$this->buscar_datos_model->eliminar_alquiler($idC);
			/*echo "<script type='text/javascript'> alert('Datos Eliminados!!!');							
					 </script>" ;	*/
			//redirect('Alquiler/alquileres_finalizados');
			echo "<script type='text/javascript'> alert('¡¡¡Alquiler Eliminado!!!');
							window.location='http://$host/SGI/Alquiler/alquileres_finalizados';
					 </script>";
		} else {
			echo "<script type='text/javascript'> alert('¡¡¡Hay liquidaciones pendientes!!!');
							window.location='http://$host/SGI/Liquidacion/pagos/$idC';
					 </script>";
		}
	}


	public function alquiler_reclamos()
	{
		/*$this->config->load('grocery_crud');
			$this->config->set_item('grocery_crud_dialog_forms',true);
			$this->config->set_item('grocery_crud_default_per_page',10);	*/

		$output = $this->alquiler_reclamos_management();

		$js_files = $output->js_files;
		$css_files =  $output->css_files;
		$output = "" . $output->output;

		$this->_example_output((object)array(
			'js_files' => $js_files,
			'css_files' => $css_files,
			'output'	=> $output
		));
	}

	public function alquiler_reclamos_management()
	{
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		$crud = new grocery_CRUD();

		$crud->set_table('alquileres');
		$crud->set_subject('Alquiler');

		$crud->set_relation('idInmueble', 'inmuebles', 'idInmueble');
		$crud->set_relation('locatario1', 'personas', 'apellidoNombre');
		$crud->set_relation('locatario2', 'personas', 'apellidoNombre');

		$crud->set_relation('locador', 'personas', 'apellidoNombre');

		$crud->where('alquileres.estado_contrato', 'VIGENTE');
		$crud->where('rescision', '0');

		$crud->columns('idContrato', 'inmueble', 'edificio', 'locador', 'locatario1', 'reclamos', 'pendientes');

		//$crud->display_as('idInmueble','#');
		$crud->display_as('locatario1', 'Locatario');
		$crud->display_as('reclamos', '¿Reclamos?');
		$crud->display_as('pendientes', 'Pend./Proc./Final.');

		$crud->unset_operations();

		$crud->callback_column('inmueble', array($this, 'buscar_direccion'));

		$crud->callback_column('edificio', array($this, 'buscar_edificio'));

		$crud->callback_column('reclamos', array($this, 'hay_reclamos'));

		$crud->callback_column('pendientes', array($this, 'contar_estados'));

		$crud->add_action('Nuevo Reclamo', '', 'Reclamo/reclamo/add', 'ui-icon-plus');

		$crud->add_action('Ver Reclamos', '', 'Reclamo/ver_reclamos', 'ui-icon-folder-open');




		$output = $crud->render();
		//$this->_example_output($output);
		if ($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}


	public function reclamos_reportes()
	{
		$this->config->load('grocery_crud');
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		//$this->config->set_item('grocery_crud_default_per_page',10);

		$output = $this->reclamos_reportes_management();

		$js_files = $output->js_files;
		$css_files =  $output->css_files;
		$output = "" . $output->output;

		$this->_example_output((object)array(
			'js_files' => $js_files,
			'css_files' => $css_files,
			'output'	=> $output
		));
	}

	public function reclamos_reportes_management()
	{
		//$this->config->set_item('grocery_crud_dialog_forms',true);
		$crud = new grocery_CRUD();

		//$crud->set_model('reportes_model');				

		$crud->set_table('reclamos');
		$crud->set_subject('Reclamos');

		/*$crud->set_relation('idInmueble','inmuebles','idInmueble');
				$crud->set_relation('locatario1','personas','apellidoNombre');
				$crud->set_relation('locatario2','personas','apellidoNombre');*/

		//$crud->set_relation('locador','personas','apellidoNombre');

		$crud->group_by('encargado');

		$crud->columns('encargado', 'cantidadReclamos');

		$crud->display_as('encargado', 'Técnico');
		$crud->display_as('cantidadReclamos', 'Cantidad de Reclamos');


		//$crud->display_as('reclamos','¿Reclamos?');
		//$crud->display_as('pendientes','Pend./Proc./Final.');

		$crud->unset_operations();

		//$crud->callback_column('inmueble',array($this,'buscar_direccion'));

		//$crud->callback_column('edificio',array($this,'buscar_edificio'));

		//$crud->callback_column('reclamos',array($this,'hay_reclamos'));


		$crud->callback_column('cantidadReclamos', array($this, 'contar_reclamos'));


		$crud->add_action('Imprimir', '', 'Alquiler/imprimir_reportes', 'ui-icon-print');

		//$crud->add_action('Ver Reclamos', '', 'Reclamo/ver_reclamos',' ui-icon-search');							


		$output = $crud->render();
		//$this->_example_output($output);
		if ($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}
	}

	public function imprimir_reportes()
	{
		$idR = $this->uri->segment(3);
		$host = $_SERVER['SERVER_NAME'];

		$datos = $this->buscar_datos_model->buscar_datos_reclamos($idR);

		$rubro = $datos[0];
		$problema = $datos[1]; //problama
		$reporte = $datos[2]; //direccion
		$locatario = $datos[3]; //locatario
		$contacto = $datos[4]; //telefono
		$prioridad = $datos[5];
		$estado = $datos[6];
		$descripcion = $datos[7];
		$tecnico = $datos[8];

		$html =
			"<style>@page {        		
						    margin-top: 1cm;
						    margin-bottom: 1cm;
						    margin-left: 1.27cm;
						    margin-right: 1.27cm;
						}
						</style>" .
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

		foreach ($reporte as $idR => $direccion) {

			$dato_requi .=
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
			if ($descripcion[$idR] <> "") {
				$dato_requi .= "<tr>
								<td height='25' valign='top'><b><u>Descripcion</u>: </b>$descripcion[$idR]</td>
							</tr>";
			}

			$dato_requi .= "<tr><td style='border-bottom: 1px solid black;'> &nbsp;   </td></tr>";
		}
		$html .= $dato_requi;

		$html .= $fin = "</table></body>";

		$pdfFilePath = "reporte_reclamos" . ".pdf";
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

	public function alquiler()
	{
		/*$this->config->load('grocery_crud');
			$this->config->set_item('grocery_crud_dialog_forms',true);
			$this->config->set_item('grocery_crud_default_per_page',10);*/
		$output = $this->alquiler_management();
		$js_files = $output->js_files;
		$css_files =  $output->css_files;
		$output = "" . $output->output;

		$this->_example_output((object)array(
			'js_files' => $js_files,
			'css_files' => $css_files,
			'output'	=> $output
		));
	}

	public function alquiler_management()
	{

		$crud = new grocery_CRUD();
		$crud->set_table('alquileres');
		$crud->set_subject('Alquiler');

		/*$crud->set_table('inmuebles');
				$crud->set_subject('Inmueble');
				$crud->set_relation_n_n('propiedad','alquileres','personas','idInmueble','dni','apellidoNombre');*/

		//$crud->unset_add();
		$crud->unset_print();
		$crud->unset_export();
		//$crud->set_relation('idInmueble','inmuebles','idInmueble');			

		//$crud->set_relation('idInmueble','inmuebles','dni');

		//$crud->set_relation('locatario1','personas','apellidoNombre');
		//$crud->set_relation('locatario2','personas','apellidoNombre');
		$crud->set_relation('garante1', 'personas', 'apellidoNombre');
		$crud->set_relation('garante2', 'personas', 'apellidoNombre');
		$crud->set_relation('garante3', 'personas', 'apellidoNombre');
		$crud->set_relation('garante4', 'personas', 'apellidoNombre');
		$crud->set_relation('locador', 'personas', 'apellidoNombre');

		$crud->where('((estado_contrato ="VIGENTE" OR estado_contrato ="FINALIZA" OR estado_contrato ="RENUEVA" OR estado_contrato ="VIG.RESCINDE" OR estado_contrato ="RESCINDE") and (operacion="ALQUILER" OR operacion="COMERCIAL"))', null, FALSE);

		//$crud->where('(estado_contrato ="VIGENTE")',null,FALSE);

		//$crud->where('operacion','ALQUILER');
		//$crud->or_where('operacion','COMERCIAL');

		$crud->field_type(
			'comision_paga',
			'dropdown',
			array('AMBOS' => 'AMBOS', 'PROPIETARIO' => 'PROPIETARIO')
		);


		$sesion = $this->session->userdata('usuario');

		$crud->display_as('idInmueble', 'Inmueble');
		$crud->display_as('punitorio', 'Mora diaria en %');
		$crud->display_as('comision_admin', 'Comision por Admin. en %');
		$crud->display_as('comision_inmo_a_pagar', 'Comision-Inm. a Pagar');
		$crud->display_as('fechaPago', 'Dia de Pago');
		$crud->display_as('fechaInicio', 'Inicio');
		$crud->display_as('fechaFin', 'Fin');
		$crud->display_as('duracion', 'Duracion');
		$crud->display_as('estado_alquiler', '');
		$crud->display_as('operacion', 'Operación');
		$crud->display_as('proxVenc', 'Prox.-Vto');
		$crud->display_as('ajuste', 'Ajuste en %');
		$crud->display_as('tipo_ajuste', 'Tipo de Ajuste');
		$crud->display_as('fecha_firma', 'Firma de Contrato');
		$crud->display_as('estado_contrato', 'Contrato');
		$crud->display_as('garante1', 'garante1');
		$crud->display_as('valor1', 'Valor1');
		$crud->display_as('idContrato', '#');
		$crud->display_as('sellado_contrato', 'Sellados de Contrato Total');
		$crud->display_as('locatario1', 'Locatario');
		$crud->display_as('locador', 'Locador');
		$crud->display_as('cant_pagos', '#');

		//$crud->field_type('tipo_ajuste','enum',array('SEMESTRAL','OCTOMESTRAL','ANUAL','SIN AJUSTE' ));

		$crud->columns('idContrato', 'estado_alquiler', 'fechaInicio', 'fechaFin', 'idInmueble', 'edificio', 'locatario1', 'locador', 'proxVenc', 'estado_contrato', 'cant_pagos');

		$crud->required_fields('idInmueble', 'locador', 'operacion', 'locatario1', 'garante1', 'fechaInicio', 'punitorio', 'comision_admin', 'fechaPago', 'duracion', 'fechaFin', 'valor1', 'tipo_ajuste', 'ajuste', 'comision_paga');

		$crud->fields('idInmueble', 'locador', 'operacion', 'locatario1', 'locatario2', 'garante1', 'garante2', 'garante3', 'garante4', 'fechaInicio', 'duracion', 'fechaFin', 'fechaPago', 'proxVenc', 'punitorio', 'comision_admin', 'tipo_ajuste', 'ajuste', 'valor1', 'valor2', 'valor3', 'valor4', 'valor5', 'valor6', 'comision_inmo_a_pagar', 'comision_paga', 'sellado_contrato', 'fecha_creacion', 'usuario_creacion', 'fecha_firma', 'escribano', 'estado_alquiler', 'estado_contrato', 'pendientes');

		$crud->edit_fields('idInmueble', 'locador', 'operacion', 'locatario1', 'locatario2', 'garante1', 'garante2', 'garante3', 'garante4', 'fechaInicio', 'duracion', 'fechaFin', 'fechaPago', 'proxVenc', 'punitorio', 'comision_admin', 'tipo_ajuste', 'ajuste', 'valor1', 'valor2', 'valor3', 'valor4', 'valor5', 'valor6', 'comision_inmo_a_pagar', 'comision_paga', 'sellado_contrato', 'fecha_firma', 'escribano', 'rescinde_dentro', 'rescinde_fecha', 'estado_contrato', 'rescision');

		$crud->set_rules('duracion', 'Duracion', 'numeric');
		$crud->set_rules('fechaPago', 'Fecha de Pago', 'numeric');
		$crud->set_rules('punitorio', 'Punitorio', 'numeric');
		$crud->set_rules('comi_admin', 'Comisión por Adm.', 'numeric');
		$crud->set_rules('comision_inmo_debe', 'Comisión inmobiliaria', 'numeric');
		$crud->set_rules('ajuste', 'Ajuste', 'numeric');

		$crud->field_type('fecha_creacion', 'invisible');
		$crud->field_type('usuario_creacion', 'invisible');
		$crud->field_type('estado_alquiler', 'invisible');
		$crud->field_type('estado_contrato', 'invisible');
		$crud->field_type('rescision', 'invisible');
		$crud->field_type('pendientes', 'invisible');


		$crud->unset_delete();
		//$crud->unset_edit();		
		$crud->unset_read();

		//aca se precarga el inmueble, el propietario y el valor 	
		$id = $this->uri->segment(4);
		if (is_numeric($id)) {

			$crud->callback_add_field('idInmueble', function () {
				$idI = $this->uri->segment(4);
				$this->db->select('direccion,piso,depto,dni,idEdificio,mts2,caracteristicas');
				$this->db->from('inmuebles');
				$this->db->where('idInmueble', $idI);
				$query = $this->db->get();
				foreach ($query->result() as $row) {
					$dni = $row->dni;
					$mts2 = $row->mts2;
					$caract = $row->caracteristicas;
					$idE = $row->idEdificio;
				}
				$direccion = $this->buscar_datos_model->buscar_inmueble($idI);
				if (isset($idE)) {
					$edificio = $this->buscar_datos_model->buscar_edificio($idE);
					$nombreE = $edificio['edificio'];
				} else {
					$nombreE = "";
				}
				//$Nedificio=

				$this->db->select('apellidoNombre');
				$this->db->from('personas');
				$this->db->where('dni', $dni);
				$query = $this->db->get();
				foreach ($query->result() as $row) {
					$nombre = $row->apellidoNombre;
				}
				return '<select id="field-idInmueble" class="chosen-select" data-placeholder="Seleccionar Inmueble" value="Inmueble" name="idInmueble" style="width:auto;height:30px"><option value=' . $idI . '>' . $direccion . '</option></select>' . '&nbsp-<b>Edificio:</b> ' . $nombreE . '&nbsp- <b>M²:</b> ' . $mts2 . '&nbsp- <b>Caracteristicas:</b> ' . $caract;
			});

			$crud->callback_add_field('locador', function () {
				$id = $this->uri->segment(4);
				$this->db->select('dni');
				$this->db->from('inmuebles');
				$this->db->where('idInmueble', $id);
				$query = $this->db->get();
				foreach ($query->result() as $row) {
					$dni = $row->dni;
				}
				$this->db->select('apellidoNombre');
				$this->db->from('personas');
				$this->db->where('dni', $dni);
				$query = $this->db->get();
				foreach ($query->result() as $row) {
					$nombre = $row->apellidoNombre;
				}
				return '<select id="field-locador" class="chosen-select" data-placeholder=" " value="Locador" name="locador"><option value=' . $dni . '>' . $nombre . '</option></select>';
			});
		}
		//fin - aca se precarga el inmueble y el propietario 

		//se carga el combo de locador y el enlace de Añadir
		$crud->callback_add_field('locatario1', function () {
			$combo = '<select id="field-locatario1" name="locatario1" class="chosen-select" data-placeholder="Seleccionar Locatario" value="Locatario">';
			$fincombo = '</select>';

			//verificar si el inmueble tiene reserva
			$idI = $this->uri->segment(4);
			$datos_reserva = $this->buscar_datos_model->buscar_datos_reserva($idI);
			if (!empty($datos_reserva)) {
				$reserva = ' <b>Reservado por:</b> ' . $datos_reserva['interesado'];
			} else {
				$reserva = "";
			}

			$estado_inmueble = $this->buscar_datos_model->buscar_estado_inmueble($idI);
			$datos_inmueble = $this->buscar_datos_model->datos_inmueble($idI);
			if ($estado_inmueble == 6) { //DISP.RENUEVA
				$dni = $datos_inmueble['renueva'];
				$locatario1 = $this->buscar_datos_model->buscar_persona($dni);

				$this->db->select('dni,apellidoNombre');
				$this->db->from('personas');
				$this->db->order_by('apellidoNombre', 'asc');
				$query = $this->db->get();

				foreach ($query->result() as $row) {
					if ($dni == $row->dni) {
						$combo .= '<option value=""></option><option value="' . $row->dni . '" selected>' . $row->apellidoNombre . '</option>';
					} else {
						$combo .= '<option value=""></option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
					}
				}
				return $combo . $fincombo . $reserva . '&nbsp;&nbsp;<a href="' . base_url('Persona/persona') . '"> Añadir</a><span id="idE"></span>';

				/*return '<select id="field-locador" name=locatario1 class="chosen-select" data-placeholder=" " value="Locador" name="locador"><option value='.$dni.'>'.$locatario1.'</option></select>';	*/
			} else {
				$this->db->select('dni,apellidoNombre');
				$this->db->from('personas');
				$this->db->order_by('apellidoNombre', 'asc');
				$query = $this->db->get();
				foreach ($query->result() as $row) {
					$combo .= '<option value=""></option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
				}
				return $combo . $fincombo . $reserva . '&nbsp;&nbsp;<a href="' . base_url('Persona/persona') . '"> Añadir</a><span id="idE"></span>';
			}
		});

		$crud->callback_add_field('locatario2', function () {
			$combo = '<select id="field-locatario2" name="locatario2" class="chosen-select" data-placeholder="Seleccionar Locatario" value="Locatario">';
			$fincombo = '</select>';


			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->order_by('apellidoNombre', 'asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$combo .= '<option value=""></option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
			}
			return $combo . $fincombo . '&nbsp;&nbsp;<a href="' . base_url('Persona/persona') . '"> Añadir</a><span id="idE"></span>';
		});
		//se carga el combo de garante y el enlace de Añadir
		$crud->callback_add_field('garante1', function () {
			$combo = '<select id="field-garante1" name="garante1" class="chosen-select" data-placeholder="Seleccionar Garante 1" value="Garante">';
			$fincombo = '</select>';

			//verificar si el inmueble tiene reserva
			$idI = $this->uri->segment(4);

			$estado_inmueble = $this->buscar_datos_model->buscar_estado_inmueble($idI);
			$datos_alquiler = $this->buscar_datos_model->datos_contrato($idI);
			if ($estado_inmueble == 6) { //DISP.RENUEVA
				$garante = $datos_alquiler['garante1'];
				$garante1 = $this->buscar_datos_model->buscar_persona($garante);

				$this->db->select('dni,apellidoNombre');
				$this->db->from('personas');
				$this->db->order_by('apellidoNombre', 'asc');
				$query = $this->db->get();

				foreach ($query->result() as $row) {
					if ($garante == $row->dni) {
						$combo .= '<option value=""></option><option value="' . $row->dni . '" selected>' . $row->apellidoNombre . '</option>';
					} else {
						$combo .= '<option value=""></option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
					}
				}
				return $combo . $fincombo . $reserva . '&nbsp;&nbsp;<a href="' . base_url('Persona/persona') . '"> Añadir</a><span id="idE"></span>';

				/*return '<select id="field-locador" name=locatario1 class="chosen-select" data-placeholder=" " value="Locador" name="locador"><option value='.$dni.'>'.$locatario1.'</option></select>';	*/
			} else {
				$this->db->select('dni,apellidoNombre');
				$this->db->from('personas');
				$this->db->order_by('apellidoNombre', 'asc');
				$query = $this->db->get();
				foreach ($query->result() as $row) {
					$combo .= '<option value=""></option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
				}
				return $combo . $fincombo . $reserva . '&nbsp;&nbsp;<a href="' . base_url('Persona/persona') . '"> Añadir</a><span id="idE"></span>';
			}
		});


		$crud->callback_add_field('duracion', function () {
			$lapso = '<input id="field-duracion" name="duracion" type="text" value="" maxlength="2" style="width:40px;height:30px" class="numeric form-control" onchange="fin_contrato()">';
			return $lapso;
		});

		//a partir del dia de pago ingresado se genera la fecha del prox_venc en el siguiente input mediante onchange
		$crud->callback_add_field('fechaPago', function () {
			$paga = '<input id="field-fechaPago" name="fechaPago" type="text" value="" maxlength="2" style="width:30px;height:30px" onchange="proxvenc()" />';
			return $paga;
		});

		$crud->callback_add_field('fechaFin', function () {
			$fecha_fin = '<input id="field-fechaFin" name="fechaFin" type="text" value="" maxlength="10" style="width:100px;height:30px" readonly/>' . '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<label id="mensaje" style="color:red"></label>';
			return $fecha_fin;
		});

		$crud->callback_add_field('proxVenc', function () {
			$prox_venc = '<input id="field-proxVenc" name="proxVenc" type="text"  maxlength="10" style="width:100px;height:30px" onfocus="comprobar_fecha_pago()" />';
			return $prox_venc;
		});

		$crud->callback_add_field('comision_admin', function () {
			$comi_admin = '<input id="field-comision_admin" name="comision_admin" type="text" value="" maxlength="3" style="width:40px;height:30px" class="numeric form-control" />';
			return $comi_admin;
		});

		$crud->callback_add_field('punitorio', function () {
			$punitorio = '<input id="field-punitorio" name="punitorio" type="text" value="" maxlength="3" style="width:40px;height:30px" class="numeric form-control" />';
			return $punitorio;
		});

		$crud->callback_add_field('tipo_ajuste', function () {
			return '<select id="field-tipo_ajuste" name="tipo_ajuste" class="chosen-select" data-placeholder="Seleccionar Ajuste">
								  <option value=""></option>
								  <option value="SEMESTRAL">SEMESTRAL</option>
								  <option value="OCTOMESTRAL">OCTOMESTRAL</option>
								  <option value="ANUAL">ANUAL</option>
								  <option value="SIN AJUSTE">SIN AJUSTE</option>
								</select>' . '<span id=mensaje_tipo_ajuste style=color:red></span>';
		});

		$crud->callback_add_field('ajuste', function () {
			$ajuste = '  <input id="field-ajuste" name="ajuste" type="text" value="0" maxlength="4" style="width:50px;height:30px" class="numeric form-control" />';
			return $ajuste;
		});

		$crud->callback_add_field('valor1', function () {
			$idI = $this->uri->segment(4);
			$this->db->select('valor');
			$this->db->from('inmuebles');
			$this->db->where('idInmueble', $idI);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$valor = $row->valor;
			}

			//verificar si tiene reserva el inmueble						
			$datos_reserva = $this->buscar_datos_model->buscar_datos_reserva($idI);

			$estado_inmueble = $this->buscar_datos_model->buscar_estado_inmueble($idI);
			if ($estado_inmueble == 6) {
				$datos_alquiler = $this->buscar_datos_model->datos_contrato($idI);
				$idC = $datos_alquiler['idC'];
				$ultimo_valor = $this->buscar_datos_model->ultimo_valor_alquiler($idC);
				$mensaje = 'Ultimo valor:<b style=color:red>' . $ultimo_valor . '</b>';
			} else {
				$mensaje = "";
			}



			if (!empty($datos_reserva)) {
				$sena = ' <b>Seña por reserva:</b> ' . $datos_reserva['sena'] . ', debe abonar: <b style="color:red;; font-size:16px">' . ($valor - $datos_reserva['sena']) . '</b>';
			} else {
				$sena = "";
			}

			if (!isset($valor)) $valor = "0";

			return '<input id="field-valor1" name="valor1" type="text" value=' . $valor . ' class="numeric form-control" maxlength="8" style="width:80px;height:30px"/>' . $sena . $mensaje;
		});

		$crud->callback_add_field('valor2', function () {
			return '<input id="field-valor2" name="valor2" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id),vaciar_valores()"/>' . '&nbsp<span id=valor2 style="color:red"><span/>';
		});

		$crud->callback_add_field('valor3', function () {
			return '<input id="field-valor3" name="valor3" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor3><span/>';
		});

		$crud->callback_add_field('valor4', function () {
			return '<input id="field-valor4" name="valor4" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor4><span/>';
		});

		$crud->callback_add_field('valor5', function () {
			return '<input id="field-valor5" name="valor5" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor5><span/>';
		});

		$crud->callback_add_field('valor6', function () {
			return '<input id="field-valor6" name="valor6" type="text" value="" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor6><span/>';
		});

		$crud->callback_add_field('comision_inmo_a_pagar', function () {
			$comision_a_pagar = '<input id="field-comision_inmo_a_pagar" name="comision_inmo_a_pagar" type="text" onfocus="calcular_comision(this)" maxlength="10" style="width:80px;height:30px"/>';
			return $comision_a_pagar;
		});

		$crud->callback_add_field('sellado_contrato', function () {
			$idI = $this->uri->segment(4);
			$valor1 = $post_array['valor1'];

			$boton_imprimir_gastos = '&nbsp;&nbsp <input type="button" name="button" id="gastos_alquiler" value="IMPRIMIR GASTOS" class="ui-input-button" onclick="imprimir_gastos_alquiler()">';

			$certificacion = '&nbsp; Certificacion:<input id="certificacion" value="0" name="certificacion" type="text" style="width:80px;height:30px"/>';

			$veraz = '&nbsp; Veraz:<input id="veraz" name="veraz" value="0" type="text" style="width:80px;height:30px"/>';


			return '<input id="field-sellado_contrato" name="sellado_contrato" type="text" onfocus="calcular_sellado(this)" class="numeric form-control" maxlength="8" style="width:80px;height:30px"/>' . $certificacion . $veraz . $boton_imprimir_gastos;
		});

		$crud->callback_add_field('fecha_firma', function () {
			$firma = '<input id="field-fecha_firma" name="fecha_firma" type="date" value=""  style="width:150px;height:30px"/>';
			return $firma;
		});

		$crud->callback_add_field('escribano', function () {
			$escribano = '  <input id="field-escribano" name="escribano" type="text" value="Juan Vedoya Gonzalez" maxlength="25" style="width:275px;height:30px"/>';
			return $escribano;
		});

		$crud->callback_before_insert(array($this, 'fechaCreacion_Usuario'));

		$crud->callback_after_insert(array($this, 'primer_pago'));


		$crud->callback_after_update(array($this, 'primer_pago_update'));

		$crud->callback_before_update(array($this, 'fechaCreacion_Usuario_update'));


		if ($sesion[0] == 1 or $sesion[0] == 2) {
			$crud->add_action('Cobrar', '', 'Pago/pagar/add', 'ui-icon-calculator');
		}


		$crud->add_action('Pagos', '', 'Verpago/verpago', 'ui-icon-calendar');

		$crud->add_action('Ver', '', 'Alquiler/read', 'ui-icon-folder-open');

		if ($sesion[0] == 1 or $sesion[0] == 3) {
			$crud->add_action('Editar', '', 'Alquiler/alquiler_edit/edit', 'ui-icon-pencil');

			$crud->add_action('Finalizar', '', 'Alquiler/finalizar_contrato', 'ui-icon-flag');
		}

		if ($sesion[0] == 1) {
			$crud->add_action('Rescindir', '', 'Pago/rescindir_contrato/add', 'ui-icon-closethick');
		}

		$crud->add_action('No renueva', '', 'Alquiler/cancelar_renueva', 'ui-icon-cancel');

		//$cancelar_alquiler=$this->buscar_datos_model->cancelar_alquiler();

		//$crud->add_action('', '', 'Alquiler/cancelar','	ui-icon-trash');


		$crud->callback_column('idInmueble', array($this, 'buscar_direccion'));

		$crud->callback_column('locatario1', array($this, 'unir_locatarios'));

		$crud->callback_column('edificio', array($this, 'buscar_edificio'));

		$crud->callback_column('estado_contrato', array($this, 'estado_contrato'));

		//	$crud->callback_column('estado_alquiler',array($this,'estado_alquiler'));

		//$crud->callback_column('cant_pagos',array($this,'cant_pagos'));



		//$crud->add_action('Eliminar','','Alquiler/eliminar_alquiler','ui-icon-circle-minus');		

		//$idC = $this->buscar_datos_model->buscar_idC_id();

		$crud->set_lang_string(
			'insert_success_message',
			'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
					                 <script type="text/javascript">
					                  window.location = "' . site_url('Alquiler/alquiler') . '";
					                 </script>
					                 <div style="display:none">'
		);


		$crud->set_lang_string(
			'update_success_message',
			'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
				<script type="text/javascript">
					window.location = "' . site_url('Alquiler/alquiler') . '";
				</script>
				<div style="display:none">'
		);


		$output = $crud->render();
		if ($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}

		$state = $crud->getState();
		$state_info = $crud->getStateInfo();

		if ($state == 'success') {
			redirect('Alquiler/alquiler');	//echo "aca es el ADD";	//Do your cool stuff here . You don't need any State info you are in add
		} elseif ($state == 'update') {
			redirect('Alquiler/alquiler');
		}
	}


	public function cancelar($idC)
	{
		$host = $_SERVER['SERVER_NAME'];
		//verificos si tiene liquidaciones pendientes a propietarios

		/*	$idC=$this->buscar_datos_model->buscar_idC_P($idPago);
					$this->buscar_datos_model->cancelar_pago($idPago);*/

		echo "<script type='text/javascript'> alert('Alquiler Cancelado');
							
					 </script>";
	}


	public function imprimir_gastos_alquiler()
	{
		$host = $_SERVER['SERVER_NAME'];

		//$x=3;
		//"+idI+'/'+duracion+'/'+tipo_ajuste+'/'+sellado+'/'+comision+'/'+certificacion+'/'+veraz+'/'+valor1+'/'+valor2+'/'+valor3+'/'+valor4+'/'+valor5+'/'+valor6;

		$idI = $this->uri->segment(3);
		$duracion = $this->uri->segment(4);
		$tipo_ajuste = $this->uri->segment(5);


		$sellado_total = $this->uri->segment(6);
		$sellado_f = $sellado_total / 2;
		$sellado = number_format($sellado_f, 2, ',', '');

		$comision = $this->uri->segment(7);
		$certificacion = $this->uri->segment(8);
		$veraz = $this->uri->segment(9);

		$valor1 = $this->uri->segment(10);
		$valor2 = $this->uri->segment(11);

		$monto_total_t = floatval($valor1 + $sellado_f + $comision + $certificacion + $veraz);
		$monto_total = number_format($monto_total_t, 2, ',', '');

		if ($duracion == "12") {
			if ($tipo_ajuste == "ANUAL") {
				$valor1 = $this->uri->segment(10);
				$i = 1;
			} else if ($tipo_ajuste == "SEMESTRAL") {
				$valor1 = $this->uri->segment(10);
				$valor2 = $this->uri->segment(11);
				$i = 2;
			}
		} else if ($duracion == "24") {
			if ($tipo_ajuste == "SEMESTRAL") {
				$valor3 = $this->uri->segment(12);
				$valor4 = $this->uri->segment(13);
				$i = 4;
			} elseif ($tipo_ajuste == "OCTOMESTRAL") {
				$valor3 = $this->uri->segment(12);
				$i = 3;
			} elseif ($tipo_ajuste == "ANUAL") {
				$valor2 = $this->uri->segment(11);
				$i = 2;
			}
		} elseif ($duracion == "36") {
			if ($tipo_ajuste == "SEMESTRAL") {
				$valor3 = $this->uri->segment(12);
				$valor4 = $this->uri->segment(13);
				$valor5 = $this->uri->segment(14);
				$valor6 = $this->uri->segment(15);
				$i = 6;
			} elseif ($tipo_ajuste == "ANUAL") {
				$valor3 = $this->uri->segment(12);
				$i = 3;
			}
		}
		/*
		$valor4=$this->uri->segment(7);
		$valor5=$this->uri->segment(8);
		$valor6=$this->uri->segment(9);
		$duracion=$this->uri->segment(10);
		$tipo_ajuste=$this->uri->segment(11);
		$sellado=$this->uri->segment(12);	*/

		$datos_inmueble = $this->buscar_datos_model->datos_inmueble($idI);
		$direccion = $this->buscar_datos_model->buscar_inmueble($idI);

		$idE = $this->buscar_datos_model->buscar_idE($idI);
		if (isset($idE)) {
			$edificio = $this->buscar_datos_model->buscar_edificio($idE);
			$Nedificio = $edificio['edificio'];
			$Nbarrio = $edificio['barrio'];
		} else {
			$datos_inmueble = $this->buscar_datos_model->datos_inmueble($idI);
			$idB = $datos_inmueble['idB'];
			$barrio = $this->buscar_datos_model->buscar_barrio($idB);
		}
		/*$ubicacion=$datos_inmueble['ubicacion']; 
		$cochera=$datos_inmueble['cochera']; 
		$cant_dorm=$datos_inmueble['dorm']; 
		$caracteristicas=$datos_inmueble['caract_adicional'];
		$condicion=$datos_inmueble['condicion'];
		$requisitos=$datos_inmueble['requisitos'];
		$valor=$datos_inmueble['valor'];*/

		$html =
			"<style>@page {        		
			    margin-top: 1cm;
			    margin-bottom: 1cm;
			    margin-left: 1.27cm;
			    margin-right: 1.27cm;
			}
			</style>" .
			"<body>
       		 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
       		 	<tr>
       		 		<td align='center' valign='bottom'><b style='width:85%;font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:20px'>Gastos de Alquiler</b></td> 
       		 		<td style='width:15%' align='center'><img src='http://$host/SGI/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		<tr>
        		<tr>
	        		<td colspan='2' style='height:20px;vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragüi V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
	        		</td>	        		
        		</tr>        		
        	</table>

        	<br>

        	<table width='425px' border='0' cellpadding='0' cellspacing='0'>	
        		<tr> 
        			<td style='width:75px; height:25px'><b><u>Inmueble</u>: </b></td>        			
        			<td style='width:350px;height:25px'>&nbsp; $direccion</td>

        		</tr>
        	</table>
        	<br>
        	<table  style='width:385px' border='0' cellpadding='0' cellspacing='0'>			
        	
        		<tr>
        			<td style='width:300px; height:25px'><b><u>Valor Alquiler</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp;&nbsp; $valor1,00</td>
        		</tr>

        		<tr>
        			<td style='width:300px; height:25px'><b><u>Sellado de contrato(50%)</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$sellado</td>
        		</tr> 

        		<tr>
        			<td style='width:300px; height:25px'><b><u>Comisión Inmobiliaria</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$comision,00</td>
        		</tr>         		

        		<tr>
        			<td style='width:300px; height:25px'><b><u>Certificación de Firmas(2 firmas)</u>: </b></td>      			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$certificacion,00</td>
        		</tr>         		

        		<tr>        			
        			<td style='width:300px; height:25px'><b><u>Veraz (Locatario y Garante)</u>: </b></td>        			
        			<td style='width:85px;height:25px' align='right'>&nbsp; &nbsp;$veraz,00</td>      			
        		</tr>
        		</table>					

        		<table width='400px' border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
	        			<td style='width:280px; height:25px'><b><u>Monto Total para firmar el Contrato</u>: </b></td>       			
	        			<td style='width:120px;height:25px;border-top: 1px solid black;' align='right'><b style='font-size:18px'>$ $monto_total</b></td>
        			</tr>  
        		</table>

        		<br>
        		<br>
        		<br>

        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>	
        		<tr>
        			<td style='height:35px'>
        				Contrato de locación por: <b>$duracion meses</b> - Ajuste del alquiler: <b>$tipo_ajuste</b>        				
        		</tr>";

		if ($i == 1) {
			$html .= $valores = "<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
		} elseif ($i == 3) {
			$html .= $valores = "<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2, $ $valor3</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
		} elseif ($i == 4) {
			$html .= $valores = "<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2, $ $valor3, $ $valor4</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
		} elseif ($i == 6) {
			$html .= $valores = "<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2, $ $valor3, $ $valor4, $ $valor5, $ $valor6</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE.</td>
        			</tr>";
		} elseif ($i == 2) {
			$html .= $valores = "<tr><td style='height:35px'>Valores de los alquileres: <b>$ $valor1, $ $valor2</b>, APARTE DEL ALQUILER SE ABONA MENSUALMENTE. </td>
					</tr>";
		}

		$html .= $pie = "<tr>	  
        			<td style='height:35px'>Expensas aproximadamente: $.................... incluye.......................................+ LUZ + AGUA + CSP
        			</td>     			
        		</tr>
        		<tr>	  
        			<td style='height:35px'><b>Depósito en garantía:</b> No se exige en efectivo, se reemplaza por 1 pagares por 2 meses de alquiler.
        			</td>     			
        		</tr>

        		</table>
        		";


		$html .= $fin = "</table></body>";

		$pdfFilePath = $direccion . ".pdf";

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

	public function estado_contrato($value, $row)
	{
		$idC = $row->idContrato;
		$idI = $this->buscar_datos_model->buscar_idI($idC);

		$datos_rescinde = $this->buscar_datos_model->rescinde_dentro($idC);

		$rescinde_fecha = ', Rescinde en ' . $datos_rescinde[1];

		$nro_pago = $this->buscar_datos_model->nro_de_pago_idC($idC);

		if ($nro_pago <> "") {
			$ultimo_pago = $this->buscar_datos_model->buscar_ultimo_pago($idC);
		}


		if ($value == 'VIGENTE') {
			return '<b style="background: green" class="badge badge-pill"">' . $value . '</b>';
		} elseif ($value == 'FINALIZA') {
			return '<b style="background: red" class="badge badge-pill">' . $value . '</b>';
		} elseif ($value == 'RENUEVA') {
			return '<b style="background: orange" class="badge badge-pill">' . $value . '</b>';
		} elseif ($value == 'RESCINDIDO' or $value == 'VIG.RESCINDE' or $value == 'RESCINDE') {
			return '<b style="background: red" class="badge badge-pill"><abbr title="Inmueble:' . $idI . $rescinde_fecha . '">' . $value . '</abbr></b>';
		} elseif ($value == 'FINALIZADO') {
			return '<b style="background: red" class="badge badge-pill">' . $value . '</b>';
		}
	}

	public function estado_alquiler($value, $row)
	{
		if ($value == "DEUDA") {
			$estado = '<span id="estado" style="background: red" class="badge badge-pill">' . $value . '</span>';
		} else {
			$estado = '<span id="estado" style="background: green" class="badge badge-pill">' . $value . '</span>';
		}
		return $estado;
	}

	public function cant_pagos($value, $row)
	{
		if ($row->estado_alquiler == "DEUDA") {
			$cant_pagos = '<span id="cant_pagos" style="background: red" class="badge badge-pill">' . $value . '</span>';
		} else {
			$cant_pagos = '<span id="cant_pagos" style="background: green" class="badge badge-pill">' . $value . '</span>';
		}
		return $cant_pagos;
	}

	public function primer_pago($post_array, $pk)
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$fecha = $post_array['fechaInicio'];
		$primer_periodo = $this->buscar_datos_model->formato_fecha($fecha);

		$id = $post_array['idInmueble'];
		$valor1 = $post_array['valor1'];

		$this->db->set('estado', 1); //estado 1 es alquilado
		$this->db->set('renueva', 0); //SETEA renueva
		$this->db->where('idInmueble', $id);
		$this->db->update('inmuebles');

		$this->db->set('valor', $valor1);
		$this->db->where('idInmueble', $id);
		$this->db->update('inmuebles');

		$this->db->set('reserva', 0);
		$this->db->where('idInmueble', $id);
		$this->db->update('inmuebles');

		//actualizo cantidad de alquileres vigentes del locador
		$locador = $post_array['locador'];
		$this->db->set('pendientes', "NO");
		$this->db->where('dni', $locador);
		$this->db->update('personas');

		$this->buscar_datos_model->eliminar_reserva($id);

		$operacion = $post_array['operacion'];
		$this->db->set('operacion', $operacion);
		$this->db->where('idInmueble', $id);
		$this->db->update('inmuebles');

		return true;
	}

	public function buscar_contacto($value, $row)
	{
		$locatario = $row->locatario1;
		$contacto_locatario = $this->buscar_datos_model->buscar_telefono_locatario($locatario);
		return $contacto_locatario;
	}


	public function buscar_edificio($value, $row)
	{
		$idC = $row->idContrato;
		$idI = $this->buscar_datos_model->buscar_idI($idC);
		$idE = $this->buscar_datos_model->buscar_idE($idI);
		if (isset($idE)) {
			$this->load->model('buscar_datos_model');
			$edificio = $this->buscar_datos_model->buscar_edificio($idE);
			return $edificio['edificio'];
		} else {
			return $value;
		}
	}

	public function buscar_direccion($value, $row)
	{
		$idI = $row->idInmueble;
		$direccion = $this->buscar_datos_model->buscar_inmueble($idI);
		return $direccion;
	}

	public function unir_locatarios($value, $row)
	{
		$idC = $row->idContrato;
		$locatarios = $this->buscar_datos_model->buscar_locatarios($idC);
		return $locatarios;
	}


	public function fecha_fin($value, $row)
	{
		return $value;
	}

	public function hay_reclamos($value, $row)
	{
		$idC = $row->idContrato;
		$reclamo_si = 0;
		$this->db->select('estado');
		$this->db->from('reclamos');
		$this->db->where('idContrato', $idC);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$estado = $row->estado;
			if ($estado == "EN PROCESO" or $estado == "PENDIENTE") {
				$reclamo_si = $reclamo_si + 1;
			}
		}
		if ($reclamo_si > 0) {
			$estado = '<span style="background: #00D159" class="badge badge-pill">' . "SI" . '</span>';
		} else {
			$estado = '<span style="background: #3498db" class="badge badge-pill">' . "NO" . '</span>';
		}
		return $estado;
	}

	public function contar_estados($value, $row)
	{
		$idC = $row->idContrato;
		//$idC=$this->buscar_datos_model->buscar_idC($idI);	
		$estado['pendiente'] = 0;
		$estado['proceso'] = 0;
		$estado['finalizado'] = 0;
		$this->db->select('estado');
		$this->db->from('reclamos');
		$this->db->where('idContrato', $idC);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$estado_temp = $row->estado;
			if ($estado_temp == 'PENDIENTE') {
				$estado['pendiente'] = $estado['pendiente'] + 1;
			} elseif ($estado_temp == 'EN PROCESO') {
				$estado['proceso'] = $estado['proceso'] + 1;
			} elseif ($estado_temp == 'FINALIZADO') {
				$estado['finalizado'] = $estado['finalizado'] + 1;
			}
		}
		$estados_reclamos = '<span style="background: orange" class="badge badge-pill">' . $estado['pendiente'] . '</span>' . ' - ' . '<span style="background: #00D159" class="badge badge-pill">' . $estado['proceso'] . '</span>' . ' - ' . '<span style="background: red" class="badge badge-pill">' . $estado['finalizado'] . '</span>';
		return $estados_reclamos;
	}

	public function contar_reclamos($value, $row)
	{
		$tecnico = $row->encargado;
		$this->db->select('*');
		$this->db->from('reclamos');
		$this->db->where('encargado', $tecnico);
		$this->db->where('estado <>', 'FINALIZADO');
		$this->db->where('estado <>', 'ANULADO');
		$query = $this->db->get();
		$count = $query->result();
		return count($count);
	}


	public function fechaCreacion_Usuario($post_array)
	{
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$post_array['fecha_creacion'] = date('d/m/Y G:i');

		$sesion = $this->session->userdata('usuario');
		$usuario = $sesion[1];

		$post_array['usuario_creacion'] = $usuario;
		//$saldoInicial=$post_array['comision_inmo_debe'];
		//$post_array['saldo_inicial_CI']=$saldoInicial;
		$post_array['estado_contrato'] = "VIGENTE";
		$post_array['estado_alquiler'] = "DEUDA";
		$post_array['pendientes'] = "SI";

		return $post_array;
	}

	public function pagar()
	{
		$id = $this->uri->segment(4);
		redirect('Pago/pago');
	}

	public function estado_inmueble($post_array, $pk)
	{
		//$id=$this->uri->segment(4);
		$id = $post_array['idInmueble'];
		$this->db->set('estado', 'ALQUILADO');
		$this->db->where('idInmueble', $id);
		$this->db->update('inmuebles');

		//actualizo cantidad de alquileres vigentes del locador
		$locador = $post_array['locador'];
		/*$this->db->select('idContrato');
				$this->db->from('alquileres');
				$this->db->where('locador',$locador);

				$cant_alquileres= $this->db->count_all_results();*/

		$this->db->set('pendientes', "NO");
		$this->db->where('dni', $locador);
		$this->db->update('personas');
	}

	public function read()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('alquileres');
		$crud->set_subject('Alquiler');

		$crud->display_as('idInmueble', 'Inmueble');
		$crud->display_as('punitorio', 'Mora diaria en %');
		$crud->display_as('comision_admin', 'Comision por Admin. en %');
		$crud->display_as('comision_inmo_a_pagar', 'Comisión Inmobiliaria');

		$crud->display_as('fechaPago', 'Dia de Pago');
		$crud->display_as('fechaInicio', 'Inicio');
		$crud->display_as('fechaFin', 'Fin');
		$crud->display_as('duracion', 'Duracion');
		$crud->display_as('estado_alquiler', 'Estado');
		$crud->display_as('proxVenc', 'Próximo-Vto');
		$crud->display_as('ajuste', 'Ajuste en %:');
		$crud->display_as('tipo_ajuste', 'Tipo de Ajuste');
		$crud->display_as('valor1', 'Alquiler 1er período');
		$crud->display_as('valor2', 'Alquiler 2do período');
		$crud->display_as('valor3', 'Alquiler 3er período');
		$crud->display_as('valor4', 'Alquiler 4to período');
		$crud->display_as('valor5', 'Alquiler 5to período');
		$crud->display_as('valor6', 'Alquiler 6to período');
		$crud->display_as('rescinde_dentro', 'Rescinde dentro de (meses)');

		//$crud->field_type('comision_inmo_debe','hidden');
		//$crud->field_type('comision_inmo_paga','hidden');
		//$crud->field_type('saldo_inicial_CI','hidden');
		$crud->field_type('pendientes', 'hidden');
		$crud->field_type('reclamos', 'hidden');
		$crud->field_type('rescision', 'hidden');
		$crud->field_type('cant_pagos', 'hidden');

		$crud->callback_read_field('idInmueble', function ($value, $primary_key) {
			$idI = $value;
			$direccion = $this->buscar_datos_model->buscar_inmueble($value);
			return $direccion;
		});

		$crud->callback_read_field('edificio', function ($value, $primary_key) {
			$idC = $primary_key;
			$idI = $this->buscar_datos_model->buscar_idI($idC);
			$idE = $this->buscar_datos_model->buscar_idE($idI);
			if (isset($idE)) {
				$this->load->model('buscar_datos_model');
				$edificio = $this->buscar_datos_model->buscar_edificio($idE);
				return $edificio['edificio'];
			} else {
				return $value;
			}
		});

		$crud->callback_read_field('locador', function ($value, $primary_key) {
			$locador = $this->buscar_datos_model->buscar_persona($value);
			return $locador;
		});

		$crud->callback_read_field('locatario1', function ($value, $primary_key) {

			$locatario1 = $this->buscar_datos_model->buscar_persona($value);
			return $locatario1;
		});

		$crud->callback_read_field('locatario2', function ($value, $primary_key) {

			$locatario2 = $this->buscar_datos_model->buscar_persona($value);
			return $locatario2;
		});

		$crud->callback_read_field('garante1', function ($value, $primary_key) {
			$garante1 = $this->buscar_datos_model->buscar_persona($value);
			return $garante1;
		});

		$crud->callback_read_field('garante2', function ($value, $primary_key) {
			$garante2 = $this->buscar_datos_model->buscar_persona($value);
			return $garante2;
		});

		$crud->callback_read_field('garante3', function ($value, $primary_key) {
			$garante3 = $this->buscar_datos_model->buscar_persona($value);
			return $garante3;
		});

		$crud->callback_read_field('garante4', function ($value, $primary_key) {
			$garante4 = $this->buscar_datos_model->buscar_persona($value);
			return $garante4;
		});

		$crud->callback_read_field('fechaInicio', function ($value, $primary_key) {
			//$fecha_inicio=$this->buscar_datos_model->formato_fecha($value);
			//return $fecha_inicio;
			return $value;
		});

		$crud->callback_read_field('fechaFin', function ($value, $primary_key) {
			//$fecha_fin=$this->buscar_datos_model->formato_fecha($value);
			//return $fecha_fin;
			return $value;
		});

		$crud->set_crud_url_path(site_url('Alquiler/alquiler'));


		$output = $crud->render();
		$this->_example_output($output);

		/*if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}*/
	}

	public function alquiler_edit()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('alquileres');
		$crud->set_subject('Alquiler');

		$crud->display_as('idContrato', 'Inmueble');
		$crud->display_as('punitorio', 'Mora diaria en %');
		$crud->display_as('comision_admin', 'Comision por Admin. en %');
		$crud->display_as('comision_inmo_a_pagar', 'Comisión Inmobiliaria');

		$crud->display_as('fechaPago', 'Dia de Pago');
		$crud->display_as('fechaInicio', 'Inicio');
		$crud->display_as('fechaFin', 'Fin');
		$crud->display_as('duracion', 'Duracion');
		$crud->display_as('estado_alquiler', 'Estado');
		$crud->display_as('proxVenc', 'Próximo-Vto');
		$crud->display_as('ajuste', 'Ajuste en %:');
		$crud->display_as('tipo_ajuste', 'Tipo de Ajuste');
		/*$crud->display_as('valor1','Alquiler 1er período');
			$crud->display_as('valor2','Alquiler 2do período');
			$crud->display_as('valor3','Alquiler 3er período');
			$crud->display_as('valor4','Alquiler 4to período');
			$crud->display_as('valor5','Alquiler 5to período');
			$crud->display_as('valor6','Alquiler 6to período');*/

		//$crud->field_type('tipo_ajuste','enum',array('SEMESTRAL','OCTOMESTRAL','ANUAL'));
		//$crud->field_type('duracion','enum',array('24','36'));


		$crud->set_relation('locatario1', 'personas', 'apellidoNombre');
		$crud->set_relation('locatario2', 'personas', 'apellidoNombre');
		$crud->set_relation('garante1', 'personas', 'apellidoNombre');
		$crud->set_relation('garante2', 'personas', 'apellidoNombre');
		$crud->set_relation('garante3', 'personas', 'apellidoNombre');
		$crud->set_relation('garante4', 'personas', 'apellidoNombre');
		$crud->set_relation('locador', 'personas', 'apellidoNombre');

		//$crud->field_type('comision_inmo_debe','hidden');
		//$crud->field_type('comision_inmo_paga','hidden');
		//$crud->field_type('saldo_inicial_CI','hidden');
		/*$crud->field_type('pendientes','hidden');
			$crud->field_type('reclamos','hidden');
			$crud->field_type('rescision','hidden');
			$crud->field_type('cant_pagos','hidden');*/

		$crud->set_rules('duracion', 'Duracion', 'numeric');

		//$crud->field_type('estado_alquiler','invisible');

		//$crud->field_type('pendientes','invisible');
		$crud->field_type('rescision', 'invisible');
		$crud->field_type('estado_contrato', 'invisible');

		$crud->field_type(
			'comision_paga',
			'dropdown',
			array('AMBOS' => 'AMBOS', 'PROPIETARIO' => 'PROPIETARIO')
		);

		//$idI=$this->buscar_datos_model->buscar_idI($idC);
		$idC = $this->uri->segment(4);
		$nro_pago = $this->buscar_datos_model->cant_pagos($idC);

		$crud->edit_fields('idInmueble', 'locador', 'operacion', 'locatario1', 'locatario2', 'garante1', 'garante2', 'garante3', 'garante4', 'fechaInicio', 'duracion', 'fechaFin', 'fechaPago', 'proxVenc', 'punitorio', 'comision_admin', 'tipo_ajuste', 'ajuste', 'valor1', 'valor2', 'valor3', 'valor4', 'valor5', 'valor6', 'comision_inmo_a_pagar', 'comision_paga', 'sellado_contrato', 'fecha_firma', 'escribano', 'rescinde_dentro', 'rescinde_fecha', 'estado_contrato');

		$crud->required_fields('idInmueble', 'locador', 'operacion', 'locatario1', 'garante1', 'fechaInicio', 'punitorio', 'comision_admin', 'fechaPago', 'duracion', 'valor1', 'tipo_ajuste', 'ajuste', 'comision_paga');



		//aca se precarga el inmueble, el propietario y el valor			

		$crud->callback_edit_field('idInmueble', function ($value, $primary_key) {
			$idI = $value;
			//BUSCAR DIRECCION DEL INMUEBLE
			//$idI=$this->buscar_datos_model->buscar_idI($idC);
			$direccion_inmueble = $this->buscar_datos_model->buscar_inmueble($idI);

			$datos_contrato = $this->buscar_datos_model->datos_contrato($idI);

			$cant_pagos = $datos_contrato['cant_pagos'];

			$idC = $this->buscar_datos_model->buscar_idC_idI($idI);

			$prox_venc = $this->buscar_datos_model->prox_venc($idC);

			$idE = $this->buscar_datos_model->buscar_idE($idI);
			if (isset($idE)) {
				$A_edificio = $this->buscar_datos_model->buscar_edificio($idE);
				$edificio = ' - ' . $A_edificio['edificio'];
				$barrio = ', Barrio: ' . $A_edificio['barrio'];
			} else {
				$edificio = "";
				$barrio = " ";
			}
			$combo = '<select id="field-idInmueble" name="idInmueble" class="chosen-select" data-placeholder="Seleccionar" value="Inmueble">';
			$fincombo = '</select>';

			$nro_pago = '<span id="nro_pago" style="visibility:hidden">' . $cant_pagos . '</span>';

			$proxvencsig = '<span id="venc_periodo" style="visibility:hidden">' . $prox_venc . '</span>';


			$combo .= '<option value="' . $value . '">' . $direccion_inmueble . '</option>';
			//return $direccion_inmueble.$edificio.$barrio;
			return $combo . $fincombo . $nro_pago . $proxvencsig;
		});


		//se carga el combo de locador y el enlace de Añadir
		$crud->callback_edit_field('locador', function ($value, $primary_key) {
			$dni = $value;
			$combo = '<select id="field-locador" name="locador" class="chosen-select" data-placeholder="Seleccionar Locatario" value="Locatario">';
			$fincombo = '</select>';

			$locador = $this->buscar_datos_model->buscar_persona($dni);

			$combo .= '<option value="' . $dni . '">' . $locador . '</option>';

			return $combo . $fincombo;
		});

		//se carga el combo de locatario y el enlace de Añadir
		$crud->callback_edit_field('locatario1', function ($value, $primary_key) {
			$dni = $value;
			$locatario1 = $this->buscar_datos_model->buscar_persona($dni);
			$combo = '<select id="field-locatario1" name="locatario1" class="chosen-select" data-placeholder="Seleccionar"><option value="' . $dni . '">' . $locatario1 . '';
			$fincombo = '</select>';

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->order_by('apellidoNombre', 'asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$combo .= '</option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
			}
			return $combo . $fincombo . '&nbsp;&nbsp;<a href="' . base_url('Persona/persona') . '"> Añadir</a>';
		});

		//se carga el combo de locatario y el enlace de Añadir
		$crud->callback_edit_field('locatario2', function ($value, $primary_key) {
			$dni = $value;
			$locatario2 = $this->buscar_datos_model->buscar_persona($dni);
			$combo = '<select id="field-locatario2" name="locatario2" class="chosen-select" data-placeholder="Seleccionar"><option value="' . $dni . '">' . $locatario2 . '';
			$fincombo = '</select>';

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->order_by('apellidoNombre', 'asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$combo .= '</option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
			}
			return $combo . $fincombo;
		});

		$crud->callback_edit_field('garante1', function ($value, $primary_key) {
			$dni = $value;
			$garante1 = $this->buscar_datos_model->buscar_persona($dni);
			$combo = '<select id="field-garante1" name="garante1" class="chosen-select" data-placeholder="Seleccionar"><option value="' . $dni . '">' . $garante1 . '';
			$fincombo = '</select>';

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->order_by('apellidoNombre', 'asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$combo .= '</option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
			}
			return $combo . $fincombo;
		});

		$crud->callback_edit_field('garante2', function ($value, $primary_key) {
			$dni = $value;
			$garante2 = $this->buscar_datos_model->buscar_persona($dni);
			$combo = '<select id="field-garante2" name="garante2" class="chosen-select" data-placeholder="Seleccionar"><option value="' . $dni . '">' . $garante2 . '';
			$fincombo = '</select>';

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->order_by('apellidoNombre', 'asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$combo .= '</option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
			}
			return $combo . $fincombo;
		});

		$crud->callback_edit_field('garante3', function ($value, $primary_key) {
			$dni = $value;
			$garante3 = $this->buscar_datos_model->buscar_persona($dni);
			$combo = '<select id="field-garante3" name="garante3" class="chosen-select" data-placeholder="Seleccionar"><option value="' . $dni . '">' . $garante3 . '';
			$fincombo = '</select>';

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->order_by('apellidoNombre', 'asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$combo .= '</option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
			}
			return $combo . $fincombo;
		});

		$crud->callback_edit_field('garante4', function ($value, $primary_key) {
			$dni = $value;
			$garante4 = $this->buscar_datos_model->buscar_persona($dni);
			$combo = '<select id="field-garante4" name="garante4" class="chosen-select" data-placeholder="Seleccionar"><option value="' . $dni . '">' . $garante4 . '';
			$fincombo = '</select>';

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->order_by('apellidoNombre', 'asc');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$combo .= '</option><option value="' . $row->dni . '">' . $row->apellidoNombre . '</option>';
			}
			return $combo . $fincombo;
		});

		$crud->callback_edit_field('duracion', function ($value, $primary_key) {
			$lapso = '<input id="field-duracion" name="duracion" type="text" value="' . $value . '" maxlength="2" class="numerico" style="width:40px;height:30px" class="numeric form-control" onclick="vaciar_input(this.id)" onchange="fin_contato()">';
			return $lapso;
		});

		$crud->callback_edit_field('fechaFin', function ($value, $primary_key) {
			$fecha_fin = '<input id="field-fechaFin" name="fechaFin" type="text" value="' . $value . '" maxlength="10" style="width:100px;height:30px" />' . '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<label id="mensaje" style="color:red"></label>';
			return $fecha_fin;
		});

		$crud->callback_edit_field('fechaPago', function ($value, $primary_key) {
			$paga = '<input id="field-fechaPago" name="fechaPago" type="text" value="' . $value . '" maxlength="10" class="numerico" style="width:30px;height:30px"  onclick="vaciar_input(this.id)" onchange="proxvenc()" />';
			return $paga;
		});


		$crud->callback_edit_field('proxVenc', function ($value, $primary_key) {
			$prox_venc = '<input id="field-proxVenc" name="proxVenc" type="text"  maxlength="10" style="width:100px;height:30px" value="' . $value . '" onclick="proxvenc()" />';
			return $prox_venc;
		});

		$crud->callback_edit_field('comision_admin', function ($value, $primary_key) {
			$comi_admin = '<input id="field-comision_admin" name="comision_admin" type="text" value="' . $value . '" maxlength="3" style="width:40px;height:30px" class="numeric form-control" onclick="vaciar_input(this.id)" />';
			return $comi_admin;
		});

		$crud->callback_edit_field('tipo_ajuste', function ($value, $primary_key) {
			$combo = '<select id="field-tipo_ajuste" name="tipo_ajuste" class="chosen-select" data-placeholder="Seleccionar Ajuste">
								  <option value=""></option>';
			if ($value == "SEMESTRAL") {
				$combo .= '<option value="SEMESTRAL" selected>SEMESTRAL</option>
								  			<option value="OCTOMESTRAL">OCTOMESTRAL</option>
								  			<option value="ANUAL">ANUAL</option>	
								  			<option value="SIN AJUSTE">SIN AJUSTE</option>';
			}
			if ($value == "OCTOMESTRAL") {
				$combo .= '<option value="OCTOMESTRAL" selected>OCTOMESTRAL</option>
								  			<option value="SEMESTRAL">SEMESTRAL</option>
								  			<option value="ANUAL">ANUAL</option>	
								  			<option value="SIN AJUSTE">SIN AJUSTE</option>';
			}
			if ($value == "ANUAL") {
				$combo .= '<option value="ANUAL" selected>ANUAL</option>
								  			<option value="OCTOMESTRAL">OCTOMESTRAL</option>
								  			<option value="SEMESTRAL">SEMESTRAL</option>	
								  			<option value="SIN AJUSTE">SIN AJUSTE</option>';
			}
			if ($value == "SIN AJUSTE") {
				$combo .= '<option value="SIN AJUSTE" selected>SIN AJUSTE</option>
								  			<option value="OCTOMESTRAL">OCTOMESTRAL</option>
								  			<option value="SEMESTRAL">SEMESTRAL</option>	
								  			<option value="ANUAL">ANUAL</option>';
			}

			$fin = '</select>' . '<span id=mensaje_tipo_ajuste style=color:red></span>';

			return $combo . $fin;
		});



		$crud->callback_edit_field('punitorio', function ($value, $primary_key) {
			$punitorio = '<input id="field-punitorio" name="punitorio" type="text" value="' . $value . '" maxlength="3" style="width:40px;height:30px" class="numerico" onclick="vaciar_input(this.id)" />';
			return $punitorio;
		});

		$crud->callback_edit_field('ajuste', function ($value, $primary_key) {
			$ajuste = '  <input id="field-ajuste" name="ajuste" type="text" value="' . $value . '" maxlength="4" style="width:50px;height:30px" class="numeric form-control" onclick="vaciar_input(this.id)"/>';
			return $ajuste;
		});

		$crud->callback_edit_field('valor1', function ($value, $primary_key) {

			return '<input id="field-valor1" name="valor1" type="text" value=' . $value . ' class="numerico" maxlength="8" style="width:80px;height:30px"/>'; //.$sena
		});

		$crud->callback_edit_field('valor2', function ($value, $primary_key) {
			return '<input id="field-valor2" name="valor2" type="text" value=' . $value . ' class="numerico" maxlength="8" style="width:80px;height:30px"/>' . '&nbsp<span id=valor2><span/>';
		});

		$crud->callback_edit_field('valor3', function ($value, $primary_key) {
			return '<input id="field-valor3" name="valor3" type="text" value=' . $value . ' class="numerico" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor3><span/>';
		});

		$crud->callback_edit_field('valor4', function ($value, $primary_key) {
			return '<input id="field-valor4" name="valor4" type="text" value="' . $value . '" class="numerico" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor4><span/>';
		});

		$crud->callback_edit_field('valor5', function ($value, $primary_key) {
			return '<input id="field-valor5" name="valor5" type="text" value="' . $value . '" class="numerico" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor5><span/>';
		});

		$crud->callback_edit_field('valor6', function ($value, $primary_key) {
			return '<input id="field-valor6" name="valor6" type="text" value="' . $value . '" class="numerico" maxlength="8" style="width:80px;height:30px" onfocus="calcular_ajuste(this.id)"/>' . '&nbsp<span id=valor6><span/>';
		});

		$crud->callback_edit_field('comision_inmo_a_pagar', function ($value, $primary_key) {
			$comision_a_pagar = '<input id="field-comision_inmo_a_pagar" name="comision_inmo_a_pagar" value="' . $value . '" type="text"  maxlength="10" style="width:80px;height:30px" onfocus="calcular_comision(this)"/>'; //
			return $comision_a_pagar;
		});

		$crud->callback_edit_field('sellado_contrato', function ($value, $primary_key) {
			$idI = $this->uri->segment(4);
			//$valor1=$post_array['valor1'];	

			return '<input id="field-sellado_contrato" name="sellado_contrato" type="text" value="' . $value . '" class="numeric form-control" maxlength="8" style="width:80px;height:30px" onfocus="calcular_sellado(this)" />';
		});

		$crud->callback_edit_field('fecha_firma', function ($value, $primary_key) {
			$firma = '<input id="field-fecha_firma" name="fecha_firma" type="date" value="' . $value . '"  style="width:150px;height:30px"/>';
			return $firma;
		});

		$crud->callback_edit_field('escribano', function ($value, $primary_key) {
			$escribano = '  <input id="field-escribano" name="escribano" type="text" value="' . $value . '" maxlength="25" style="width:275px;height:30px"/>';
			return $escribano;
		});

		$crud->callback_edit_field('rescinde_dentro', function ($value, $primary_key) {

			$rescinde_dentro = '<input id="field-rescinde_dentro" name="rescinde_dentro" type="text" min="0" max="6" value="' . $value . '" maxlength="1" style="width:50px;height:30px"  onkeyup="calcular_periodo_rescision()" class="numerico"/>';

			return $rescinde_dentro . ' meses ,ponga 0 para cancelar la rescisión';
		}); //fin callback_add_field	


		$crud->callback_edit_field('rescinde_fecha', function ($value, $primary_key) {

			$periodo = '<input id="field-rescinde_fecha" type="text" maxlength="8" name="rescinde_fecha" value="' . $value . '" style="width:80px;height:30px"/>';

			return $periodo;
		});  //fin callback_add_field	

		/*$crud->callback_after_update(array($this,'primer_pago_update'));

					$crud->callback_before_update(array($this,'fechaCreacion_Usuario_update'));	*/
		$crud->set_lang_string(
			'update_success_message',
			'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
				<script type="text/javascript">
					window.location = "' . site_url('Alquiler/alquiler') . '";
				</script>
				<div style="display:none">'
		);

		$crud->set_crud_url_path(site_url('Alquiler/alquiler'));


		$output = $crud->render();
		$this->_example_output($output);

		/*if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}*/
	}

	public function primer_pago_update($post_array, $pk)
	{
		//date_default_timezone_set('America/Argentina/Buenos_Aires');
		//$fecha=$post_array['fechaInicio'];
		//$primer_periodo=$this->buscar_datos_model->formato_fecha($fecha);

		$id = $post_array['idInmueble'];
		$valor1 = $post_array['valor1'];

		/*$this->db->set('estado',1);//estado 1 es alquilado
				$this->db->where('idInmueble',$id);
				$this->db->update('inmuebles');*/


		$operacion = $post_array['operacion'];
		$this->db->set('operacion', $operacion);
		$this->db->set('valor', $valor1);
		$this->db->where('idInmueble', $id);
		$this->db->update('inmuebles');

		$this->db->set('reserva', 0);
		$this->db->where('idInmueble', $id);
		$this->db->update('inmuebles');

		//actualizo cantidad de alquileres vigentes del locador
		$locador = $post_array['locador'];
		$this->db->set('pendientes', "NO");
		$this->db->where('dni', $locador);
		$this->db->update('personas');

		if ($post_array['rescinde_dentro'] > 0) {
			$this->db->set('estado', 7); //ALQ.RESCINDE						
			$this->db->where('idInmueble', $id);
			$this->db->update('inmuebles');
		} elseif ($post_array['rescinde_dentro'] == 0) {
			$this->db->set('estado', 1); //ALQLQUILADO						
			$this->db->where('idInmueble', $id);
			$this->db->update('inmuebles');
		}

		return true;
	}

	public function fechaCreacion_Usuario_update($post_array, $pk)
	{

		/*$state = $crud->getState();
			$state_info = $crud->getStateInfo();	

			if($state=="update"){
				$idC=$this->uri->segment(4);
			}*/

		//$idC=$this->uri->segment(4);
		$idC = $post_array['idContrato'];


		/*$post_array['estado_contrato']="VIGENTE";
			$post_array['estado_alquiler']="DEUDA";
			$post_array['pendientes']="SI";*/
		$valor2 = $post_array['valor2'];
		$valor3 = $post_array['valor3'];
		$valor4 = $post_array['valor4'];
		$valor5 = $post_array['valor5'];
		$valor6 = $post_array['valor6'];

		if ($valor2 == "") $post_array['valor2'] = "0";
		if ($valor3 == "") $post_array['valor3'] = "0";
		if ($valor4 == "") $post_array['valor4'] = "0";
		if ($valor5 == "") $post_array['valor5'] = "0";
		if ($valor6 == "") $post_array['valor6'] = "0";

		$rescinde_dentro = $post_array['rescinde_dentro'];
		$rescinde_fecha = $post_array['rescinde_fecha'];


		if ($rescinde_dentro != 0) {
			$post_array['rescision'] = 1;
			$post_array['estado_contrato'] = "VIG.RESCINDE";
		} elseif ($rescinde_dentro == 0) {
			$post_array['rescision'] = 0;
			$post_array['estado_contrato'] = "VIGENTE";
		}

		return $post_array;
	}

	public function finalizar_contrato($idC)
	{
		//$idC=$row->idContrato;
		$idI = $this->buscar_datos_model->buscar_idI($idC);
		$estado = $this->buscar_datos_model->estado_contrato($idI);
		$reserva = $this->buscar_datos_model->buscar_reserva($idI);
		if ($estado == "FINALIZA") {
			if ($reserva == 1) {
				$this->db->set('estado', 3);
			} else {
				$this->db->set('estado', 0);
			}
		} elseif ($estado == "RENUEVA") {
			$this->db->set('estado', 6);
		}
		$this->db->where('idInmueble', $idI);
		$this->db->update('inmuebles');


		$this->db->set('estado_contrato', "FINALIZADO");
		$this->db->where('idContrato', $idC);
		$this->db->update('alquileres');

		redirect('Alquiler/alquiler');
	}

	public function cancelar_renueva($idC)
	{
		$idI = $this->buscar_datos_model->buscar_idI($idC);
		$this->db->set('estado_contrato', 'FINALIZA');
		$this->db->where('idContrato', $idC);
		$this->db->update('alquileres');

		$this->db->set('estado', 5);
		$this->db->set('renueva', 0);
		$this->db->where('idInmueble', $idI);
		$this->db->update('inmuebles');

		redirect('Alquiler/alquiler');
	}

	function _example_output($output = null)
	{
		$login = $this->session->userdata('usuario');
		if ($login) {
			$this->load->view('inicio', (array)$output);
		} else {
			redirect('login');
		}
	}
}
