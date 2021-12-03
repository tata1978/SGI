<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Pago extends CI_Controller{	

		public function __construct(){			
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('grocery_CRUD');
			$this->load->library('session');
			$this->load->model('pdfs_model');
			$this->load->model('buscar_datos_model');
			
		}

		public function index(){			
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
		}


		public function cancelar($idPago){
				$host=$_SERVER['SERVER_NAME'];
				//verificos si tiene liquidaciones pendientes a propietarios

					$idC=$this->buscar_datos_model->buscar_idC_P($idPago);
					$this->buscar_datos_model->cancelar_pago($idPago);

					$this->buscar_datos_model->update_cantPagos_proxVenc($idC);//aca ajusto cant_pagos y prox_venc, como se anulo un pago

					echo "<script type='text/javascript'> alert('Pago Anulado');
							window.location='http://$host/SGI/Verpago/verpago/$idC';
					 </script>" ;				
						
		}

		public function caja_reportes(){
			$this->config->load('grocery_crud');

			$output = $this->caja_reportes_management();		

			$js_files =$output->js_files; 
			$css_files =  $output->css_files; 
			$output = "".$output->output;

			$this->_example_output((object)array(
					'js_files' => $js_files,
					'css_files' => $css_files,
					'output'	=> $output
			));
		}

		public function caja_reportes_management(){
			$crud=new grocery_CRUD();
			$crud->set_table('pagos');	
			$crud->set_subject('Deudores');

			//$crud->set_relation('idContrato','alquileres','locatario1');

			$crud->where('idPago','0');

			$crud->unset_operations();

			$crud->columns('Dia','Inmueble','Locatario');


			$output = $crud->render();				
				//$this->_example_output($output);
			if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}			
		}



		public function descargar_pdf(){
			$data = [];

			$hoy = date("dmyhis");

        	//load the view and saved it into $html variable
        	$html = 
        	"<style>@page {
			    margin-top: 0.5cm;
			    margin-bottom: 0.5cm;
			    margin-left: 0.5cm;
			    margin-right: 0.5cm;
			}
			</style>".
       		 "<body>
        	<div style='color:black;'><h1> Taragüi Propiedades </h1></div>".
        		"<div> Córdoba 682 - Corrientes Capital - Tel: 0379-4423771 - correo electrónico: admtaragui@gmail.com</div>
        			<hr>
        		<table border='0' cellpadding='0' cellspacing='0'>
        			<tr >
        				<td style='height:50px'><u>RECIBO:</u></td>
        				<td style='height:50px'>fghfghfg</td>
        			</tr>
        			<tr >
        				<td style='height:50px'><u>LOCADOR: </u></td>
        				<td style='height:50px'> Acosta Olga Elizabet</td>
        			</tr>
        			<tr>
        				<td style='height:50px'><u>LOCATARIO: </u></td>
        				<td style='height:50px'> Alejandro Martin Ferrer</td>
        			</tr>        			
        		</table>	
        	</body>";

        	// $html = $this->load->view('v_dpdf',$date,true);
 		
 			//$html="asdf";
        	//this the the PDF filename that user will get to download
        	$pdfFilePath = "cipdf_".$hoy.".pdf";
 
        	//load mPDF library
        	$this->load->library('M_pdf');
       	 	$mpdf = new mPDF('c', 'A4-L'); 
 			$mpdf->WriteHTML($html);
			$mpdf->Output($pdfFilePath, "D");
       		// //generate the PDF from the given html
       		//  $this->m_pdf->pdf->WriteHTML($html);
 
       		//  //download it.
       		//  $this->m_pdf->pdf->Output($pdfFilePath, "D"); 
		}

		public function pagar(){
			$crud = new grocery_CRUD();
			
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');	

			//$crud->unset_back_to_list();

			$crud->set_relation('idContrato','alquileres','locatario1');
			$crud->set_relation('idContrato','alquileres','proxVenc');

			/*$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler','mora_dias','paga_mora','punitorios','ci_a_pagar','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','veraz','expensas','expensas_detalle','csp','csp_detalle','luz','luz_detalle','agua','agua_detalle','saldos_otros','detalle_otros','total_pagar','observaciones','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario','renueva');*/

			$crud->required_fields('total_pagar','valor_alquiler');

			$crud->field_type('nro_pago','invisible');
			$crud->field_type('locador','invisible');
			$crud->field_type('locatario1','invisible');
			$crud->field_type('usuario_creacion','invisible');
			$crud->field_type('fecha_pago','invisible');
			$crud->field_type('prox_venc_sig','invisible');
			$crud->field_type('pagado_propietario','invisible');
			//$crud->field_type('rescinde_periodo','invisible');
			//$crud->field_type('comision_inmo_paga','invisible');

			$crud->field_type('paga_mora','enum',array('SI','NO'));

			$state = $crud->getState();
			$state_info = $crud->getStateInfo();	

			if($state=="add"){
				$idC=$this->uri->segment(4);
			}elseif($state=="success"){
						$idP=$this->uri->segment(4);
						$this->db->select('idContrato');
						$this->db->from('pagos');
						$this->db->where('idpago',$idP);
						$query = $this->db->get();
						foreach ($query->result() as $row){
							$idC=$row->idContrato;
						}					
			}

			
			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			if($operacion=="COMODATO"){
				$crud->display_as('idContrato','Comodato');
				$propietario="Comodatario";
				$persona="Comodante";
			}else if($operacion=="ALQUILER"){
				$crud->display_as('idContrato','Alquiler');
				$propietario="Locador";
				$persona="Locatario";
			}else{
				$crud->display_as('idContrato','Comercial');
				$propietario="Locador";
				$persona="Locatario";				
			}


			$crud->display_as('periodo','Periodo a Pagar');
			$crud->display_as('valor_alquiler','Importe');
			$crud->display_as('mora_dias','Mora a la fecha');
			$crud->display_as('paga_mora','¿Paga mora?');
			$crud->display_as('saldos_otros','Otros Gastos');
			$crud->display_as('detalle_otros','Detalle');
			$crud->display_as('total_pagar','<b>Total a Cobrar</b> ');
			$crud->display_as('ci_a_pagar','CI Debe');
			$crud->display_as('comision_inmo_paga','CI Paga, ¿Cuánto?');
			$crud->display_as('comision_inmo_debe','CI Deuda Actualizada');
			$crud->display_as('fechaUltimoPago','Ultimo Pago');	
			$crud->display_as('renueva','¿Renueva?');
			$crud->display_as('sellado_paga','Sellados Inquilino');	
			$crud->display_as('certi_firma','Certificación de Firmas');	
			$crud->display_as('impuesto_inmob','Impuesto Inmob.');	
			$crud->display_as('inmob_desc','I-I detalle');
			$crud->display_as('exp_extra','Exp.Extraord.');	
			$crud->display_as('rescinde_dentro','<b style="color:red">Rescisión de Contrato</b> Rescinde dentro de');	
			$crud->display_as('exp_extra_detalle','Extraord.Detalle');				
				
			//$crud->set_crud_url_path(site_url('Alquiler/alquiler'));
			//id de contrato de la url
			$idC=$this->uri->segment(4);	
			//aca completo datos del inmueble, locatario y locador				
				if(is_numeric($idC)){			
					$crud->callback_add_field('idContrato', function () {
						$idC=$this->uri->segment(4);
						$this->db->select('idInmueble,locatario1');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$id_inmueble = $row->idInmueble;
							$dni_locatario1=$row->locatario1;											
						}

						$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$id_inmueble);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$dni_locador=$row->dni;
							$id_tipoinmueble=$row->idTipoInmueble;
							$idE=$row->idEdificio;
						}

						$direccion=$this->buscar_datos_model->buscar_inmueble($id_inmueble);

						$this->db->select('nombreTipo');
						$this->db->from('tipoinmuebles');
						$this->db->where('idTipoInmueble',$id_tipoinmueble);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$tipo_inmueble = $row->nombreTipo;
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni_locador);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre_locador = $row->apellidoNombre;
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni_locatario1);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre_locatario1 = $row->apellidoNombre;
						}

						if(isset($idE)){
							$this->load->model('buscar_datos_model');
							$edificio=$this->buscar_datos_model->buscar_edificio($idE);
							
							$nombreE=$edificio['edificio'];
							$Nedificio='&nbsp- <b><u>Edificio</u>:</b><span style="color:blue; font-size:14px"> '.$nombreE.'</span>';
						}else{
							$Nedificio="";
						}

						$operacion=$this->buscar_datos_model->tipo_operacion($idC);

						if($operacion=="COMODATO"){							
							$propietario="Comodante";
							$persona="Comodatario";
						}else{							
							$propietario="Locador";
							$persona="Locatario";
						}

						$combo= '<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto" ><option value = '.$idC.'>'.strtoupper($direccion).'</option></select>';

						return $combo.'&nbsp<b><u>'.$persona.'</u>:</b><span style="color:blue; font-size:15px"> '.strtoupper($nombre_locatario1).'</span>  -  '.'   <b><u>'.$propietario.'</u>:</b> <span style="color:blue; font-size:15px">'.strtoupper($nombre_locador).'</span>'.$Nedificio;

					});//cierro callback_add_field

					$crud->callback_add_field('fechaUltimoPago', function () {	
						$idC=$this->uri->segment(4);					
						$this->db->select('periodo,total_pagar');
						$this->db->from('pagos');
						$this->db->where('idContrato',$idC);
						$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado
						$this->db->limit(1);
						$this->db->order_by('idpago','DESC');

						$query=$this->db->get();
						if($query->num_rows() > 0){								
							foreach ($query->result() as $row){							
									$periodo_anterior = $row->periodo;
									$importe_anterior = $row->total_pagar;								
							}
						}else{
							$periodo_anterior = " - ";
							$importe_anterior = " 0 ";
						}

						$ultimo_pago = '<input id="field-fechaUltimoPago" name="fechaUltimoPago" type="text" value="'.$periodo_anterior.'" maxlength="10" style="width:100px;height:30px" />';				
						$texto='&nbsp&nbsp <b><u>Importe pagado</u>: </b>'.$importe_anterior;
						return $ultimo_pago.$texto;

					});//cierro callback_add_field

					$crud->callback_add_field('periodo', function () {
						$idC=$this->uri->segment(4);
						$this->db->select('proxVenc,valor1,punitorio,fechaInicio,fechaFin, cant_pagos,comision_paga');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();
						foreach ($query->result() as $row){
							$prox_venc = $row->proxVenc;							
							$punitorio_porc=$row->punitorio;
							$fecha_inicio=$row->fechaInicio;
							$fecha_fin=$row->fechaFin;
							$cant_pagos=$row->cant_pagos;
							$comision_inmo_quien_paga=$row->comision_paga;
						}

						if($cant_pagos>0){
							$this->db->select('prox_venc_sig');
							$this->db->from('pagos');
							$this->db->where('idContrato',$idC);
							$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado
							$this->db->limit(1);
							$this->db->order_by('idpago','DESC');
							$query=$this->db->get();
							foreach ($query->result() as $row){
								$prox_venc = $row->prox_venc_sig;
							}
						}	

						$datos=$this->buscar_datos_model->n_pagos($idC);
						$dife=$datos['duracion']-$datos['nro_pago'];
						if($dife == 1){
							$pagos=$datos['nro_pago']+1;
							$pago_texto='&nbsp; - <u>Pago</u>: <span id=nro_pago style="color:red"> '.$pagos.'</span> de <span id="duracion"> '.$datos['duracion'] .'</span> - <span id="pago">¡¡¡ÚLTIMO PAGO!!!</span>';
						}else{	
							$pagos=$datos['nro_pago']+1;
							$pago_texto='&nbsp; - <span id="restan"> <u>Pago</u>: '.'<span id=nro_pago style="color:red">'.$pagos.'</span>'.' de <span id="duracion"> '.$datos['duracion'] .'</span>';
						}

						$quien_paga='<span id="quien_paga" style="visibility:hidden">'.$comision_inmo_quien_paga.'</span>';

						$venc_periodo='<span id="venc_periodo" style="visibility:hidden">'.$prox_venc.'</span>';

						$proxvenc=date("d/m/Y", strtotime($prox_venc));
						$texto='<input id="field-periodo" class="form-control" name="periodo" type="text" value="'.$prox_venc.'" maxlength="10" style="width:100px;height:30px"/> '.'<b> &nbsp&nbsp <u>Vencimiento del periodo</u>: </b><span id="venc">'.$proxvenc.'</span> &nbsp- <b><u>Período locativo</u>:</b> <span id="inicio">'.$fecha_inicio.'</span> - <span id="fin">'.$fecha_fin.'</span>'.$pago_texto.$venc_periodo.$quien_paga;					

						return $texto;	
					});//fin callback_add_field

					$crud->callback_add_field('valor_alquiler', function () {	
						$idC=$this->uri->segment(4);
						$n=$this->buscar_datos_model->importe_a_cobrar($idC);
						if($n==1){
							$this->db->select('valor1');
						}elseif ($n==2) {
							$this->db->select('valor2');
						}elseif ($n==3) {
							$this->db->select('valor3');
						}elseif ($n==4) {
							$this->db->select('valor4');
						}elseif ($n==5) {
							$this->db->select('valor5');
						}elseif ($n==6) {
							$this->db->select('valor6');
						}								
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();	

						foreach ($query->result() as $row){							
							if($n==1){
								$valor = $row->valor1;
							}elseif ($n==2) {
								$valor = $row->valor2;
							}elseif ($n==3) {
								$valor = $row->valor3;
							}elseif ($n==4) {
								$valor = $row->valor4;
							}elseif ($n==5) {
								$valor = $row->valor5;
							}elseif ($n==6) {
								$valor = $row->valor6;
							}							
						}

						$operacion=$this->buscar_datos_model->tipo_operacion($idC);//ALQUILER O COMODATO O COMERCIAL

						if($operacion=="COMODATO"){							
							$ajuste="Comodato";
						}else{							
							$ajuste="Alquiler";
						}						
				
						$ajustes=$this->buscar_datos_model->periodo_ajuste($idC);

						$ajustes ='&nbsp;&nbsp;&nbsp;<b><u>Ajustes del '.$ajuste.'</u> :<span style="color:red">'.$ajustes['0'].'</span> - <u>Próximo ajuste desde</u> </b><span id="fecha_ajuste">'.$ajustes['1'].'</span>: '.'<span id="valor_ajuste">'.$ajustes['2'].'</span>';

						$valor_alquiler = '<input id="field-valor_alquiler" name="valor_alquiler" type="text" value="'.$valor.'" maxlength="10" style="width:100px;height:30px" class="numerico">';	

						$datos_rescinde=$this->buscar_datos_model->rescinde_dentro($idC);

						$rescinde_dentro=$datos_rescinde[0];

						$rescinde_fecha=$datos_rescinde[1];	

						if($rescinde_dentro<>0){
							$rescinde_dentro_texto=" <span id='rescinde_dentro' style='color:red'>&nbsp&nbsp&nbsp <b> RESCINDE CONTRATO EN <span id='rescinde_fecha'>$rescinde_fecha</span></b></span>";
						}else{
							$rescinde_dentro_texto="";
						}
											
						return $valor_alquiler.$ajustes.$rescinde_dentro_texto;
					});//cierro callback_add_field


					$crud->callback_add_field('mora_dias', function () {
						$idC=$this->uri->segment(4);
						$this->db->select('punitorio');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();
						foreach ($query->result() as $row){						
							$punitorio_porc=$row->punitorio;
						}

						$mora_dias = '<input id="field-mora_dias" name="mora_dias" type="number"  min="0" maxlength="3" style="width:70px;height:30px" class="numerico" onchange="calcular_punitorios()"/> dias por  &nbsp<input id="field-valor-diario" name="valor-diario" type="text" maxlength="5" class="numeric form-control" style="width:70px;height:30px" value="0"/ disabled> <b id="texto-total-punitorio">&nbsp = </b><span id="total-mora">0</span> - <b><u> Mora diaria</u>: </b><span id="porcentaje">'.$punitorio_porc.'</span>%'.'<span id="mora_dias"></span>';	
						return $mora_dias;
					});//fin callback_add_field

					$crud->callback_add_field('paga_mora', function () {
						$combo_mora = '	<select id="field-paga_mora" name="paga_mora" class="chosen-select" data-placeholder="Seleccionar Paga mora" onchange="pagamora()" disabled><option value=""  ></option><option value="SI"  >SI</option><option value="NO"  >NO</option>  </select>';	
						return $combo_mora;
					});	//fin callback_add_field

					$crud->callback_add_field('punitorios', function () {					
						$mora_importe='<span id="b"></span><input id="field-punitorios" class="numerico" name="punitorios" type="text" value="0.00" maxlength="10" style="width:100px;height:30px"/>';
						return $mora_importe;	
					});//fin callback_add_field

					$idC=$this->uri->segment(4);					
					$datos=$this->buscar_datos_model->n_pagos($idC);
					$pagos=$datos['pagos']+1;
					$comision_quien_paga=$datos['comision_paga'];					


				//if($comision_quien_paga=="AMBOS"){	
					if($pagos==1 && $comision_quien_paga=="AMBOS"){//'mora_dias','paga_mora','punitorios',
							$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler', 'mora_dias','paga_mora','punitorios','ci_a_pagar','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','veraz','expensas','expensas_detalle','csp','csp_detalle','impuesto_inmob','inmob_desc','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','total_pagar','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario1','renueva');

							$crud->callback_add_field('ci_a_pagar', function () {
								$idC=$this->uri->segment(4);
								$this->db->select('valor1,punitorio,comision_inmo_a_pagar,comision_paga');
								$this->db->from('alquileres');
								$this->db->where('idContrato',$idC);
								$query=$this->db->get();
								foreach ($query->result() as $row){							
									$valor=$row->valor1;
									$punitorio_porc=$row->punitorio;
									$comision_inmo_total=$row->comision_inmo_a_pagar;
									$comision_quien_paga=$row->comision_paga;
								}

								
									$CI=number_format($comision_inmo_total/2,2,'.','');

									$ci_a_pagar='<input id="field-ci_a_pagar" name="ci_a_pagar" type="text" value="'.$CI.'" style="width:100px;height:30px"/disabled>'.'&nbsp50% de <span style="font-size:16px">'.$comision_inmo_total.'</span>';

									$saldo = '&nbsp&nbsp ¿Cuánto?:<span id="b"></span>&nbsp<input id="field-ci_a_pagar" name="ci_a_pagar" type="text" value="0.00" maxlength="10" style="width:100px;height:30px"  onkeyup="validar_comision()" onclick="vaciar(this.id)"/>';


									$input_resta='&nbsp&nbsp Resta </span>&nbsp<input id="field-comision_inmo_debe" type="text" name="field-comision_inmo_debe" maxlength="10"    onblur ="input_ceros(this.id)" class="numerico"  style="width:100px;height:30px"/>';									

								
									/*$CI=0.00;
									$ci_a_pagar='<input id="field-ci_a_pagar" name="ci_a_pagar" type="text" value="'.$CI.'" style="width:100px;height:30px"/disabled>'.'<span style="font-size:16px">No paga Comisión</span>';*/
							return $ci_a_pagar;

						});
					}else{
							
						if($pagos==1 and $comision_quien_paga=="PROPIETARIO"){
							$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler', 'mora_dias','paga_mora','punitorios','sellado_paga','certi_firma','veraz','expensas','expensas_detalle','csp','csp_detalle','impuesto_inmob','inmob_desc','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','total_pagar','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario1','renueva');									

						}else{


						$idC=$this->uri->segment(4);
						$rescinde_dentro=$this->buscar_datos_model->rescinde_dentro($idC);

						if($pagos<7){
							$crud->field_type('rescinde_dentro','invisible');	
							$crud->field_type('rescinde_periodo','invisible');	
						}
						
						$ci=$this->buscar_datos_model->ci_debe($idC);
						//if(!isset($ci))$ci="";

							if($ci==0.00){

								$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler','mora_dias','paga_mora','punitorios','expensas','expensas_detalle','csp','csp_detalle','impuesto_inmob','inmob_desc','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','total_pagar','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario1','renueva','rescinde_dentro','rescinde_periodo');
							}else{
								
								$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler','mora_dias','paga_mora','punitorios','ci_a_pagar','comision_inmo_paga','comision_inmo_debe','expensas','expensas_detalle','csp','csp_detalle','impuesto_inmob','inmob_desc','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','total_pagar','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario1','renueva','rescinde_dentro','rescinde_periodo');					
							}
						}
						
						$crud->callback_add_field('ci_a_pagar', function () {
							$idC=$this->uri->segment(4);
							$datos=$this->buscar_datos_model->n_pagos($idC);

							$ultimo_pago_CI=$this->buscar_datos_model->ultimo_pago_ci($idC);

							$idP=$datos['idpago'];

							$deuda_ci=$this->buscar_datos_model->deuda_ci($idP);								

							$ci_a_pagar='<input id="field-ci_a_pagar" name="ci_a_pagar" type="text" value="'.$deuda_ci.'" style="width:100px;height:30px"/disabled>';	

							return $ci_a_pagar.$ultimo_pago_CI;	
						});	

						/*$crud->field_type('sellado_paga','hidden');			
						$crud->field_type('certi_firma','hidden');
						$crud->field_type('veraz','hidden');*/
					}
				/*}elseif($comision_quien_paga=="PROPIETARIO"){
					if($pagos==1){
						$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler', 'mora_dias','paga_mora','punitorios','sellado_paga','certi_firma','veraz','expensas','expensas_detalle','csp','csp_detalle','impuesto_inmob','inmob_desc','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','total_pagar','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario1','renueva');
					}else{
							$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler','mora_dias','paga_mora','punitorios','expensas','expensas_detalle','csp','csp_detalle','impuesto_inmob','inmob_desc','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','total_pagar','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario1','renueva','rescinde_dentro','rescinde_periodo');						
					}

						if($pagos<7){
							$crud->field_type('rescinde_dentro','invisible');	
							$crud->field_type('rescinde_periodo','invisible');	
						}
				}*/		

					$crud->callback_add_field('comision_inmo_paga', function () {
						$comision_paga = '<input id="field-comision_inmo_paga" name="comision_inmo_paga" value="0.00" type="text" onblur ="input_ceros(this.id)" onclick="vaciar(this.id)" onkeyup="validar_comision()" maxlength="10" style="width:100px;height:30px" class="numerico"/>';	

							$paga_todo='<input id="paga_todo_ci" type="button" value="Paga todo" onclick="pagatodo_ci()"/>';
						return $comision_paga.$paga_todo;
					});

					$crud->callback_add_field('comision_inmo_debe', function () {
						$idC=$this->uri->segment(4);
						$datos=$this->buscar_datos_model->n_pagos($idC);

						$idP=$datos['idpago'];

						$comision_quien_paga=$datos['comision_paga'];

						$deuda_ci=$this->buscar_datos_model->deuda_ci($idP);

						/*if($comision_quien_paga=="PROPIETARIO"){
							$deuda_ci=0.00;
						}*/
						

						$comision_debe = '<input id="field-comision_inmo_debe" name="comision_inmo_debe" value="'.$deuda_ci.'" type="text" maxlength="10" value=""style="width:100px;height:30px" class="numerico" readonly/>';	
						return $comision_debe;
					});						


					$crud->callback_add_field('sellado_paga', function () {
						$idC=$this->uri->segment(4);
						$sellado_contrato=$this->buscar_datos_model->sellado_contrato($idC);

						$datos=$this->buscar_datos_model->n_pagos($idC);
					
						$comision_quien_paga=$datos['comision_paga'];	
						if($comision_quien_paga=="PROPIETARIO"){
							$no_paga="<b style='color:blue'>NO PAGA COMISION</b>";
						}else{
							$no_paga="";
						}						

						$sellado_paga= '<span class="required"></span><input id="field-sellado_paga" name="sellado_paga" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" class="numerico" onfocus="paga_sellado(this)"/>'.'&nbsp50% de <span id="sellado_contrato" style="font-size:16px">'.$sellado_contrato.'</span>';	
						return $sellado_paga.' - '.$no_paga;
					});//fin callback_add_field

					$crud->callback_add_field('certi_firma', function () {
						$certi_firma= '<span class="required"></span><input id="field-certi_firma" name="certi_firma" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" class="numerico"  onblur ="input_ceros(this.id)" onclick="vaciar(this.id)"/>';	
						return $certi_firma;
					});//fin callback_add_field

					$crud->callback_add_field('veraz', function () {
						$veraz= '<span class="required"></span><input id="field-veraz" name="veraz" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" class="numerico"  onblur ="input_ceros(this.id)" onclick="vaciar(this.id)"/>';
						return $veraz;
					});//fin callback_add_field											




					$crud->callback_add_field('expensas', function () {
						$idC=$this->uri->segment(4);
						$ci=$this->buscar_datos_model->ci_debe($idC);	

						
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='Pago anterior: '.$anterior_impuestos['expensas'].' - Detalle: '.$anterior_impuestos['expensas_detalle'];							

						$expensas = '<span id="b"></span><input id="field-expensas" name="expensas" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />'.'<span id="ci_debe" style="visibility:hidden">'.$ci.'</span>';	
						return $expensas.$ant_impuestos;
					});

					$crud->callback_add_field('expensas_detalle', function () {					
					$expensas_detalle = '<textarea name="expensas_detalle" maxlength="300" id="field-expensas_detalle" onfocus="insertar_periodo_expensas(this)" style="width: 1100px; height: 26px;"></textarea>';
						return $expensas_detalle;
					});	//fin callback_add_field											


					$crud->callback_add_field('csp', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Pago anterior: '.$anterior_impuestos['csp'].' - Detalle: '.$anterior_impuestos['csp_detalle'];

						$csp = '<span id="b"></span><span class="required"></span><input id="field-csp"  name="csp" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $csp.$ant_impuestos;
					});//fin callback_add_field

					$crud->callback_add_field('csp_detalle', function () {						
					$csp_detalle = '<textarea name="csp_detalle" maxlength="300" id="field-csp_detalle" onfocus="insertar_periodo_csp(this)" style="width: 1100px; height: 26px;"></textarea>';	
						return $csp_detalle;
					});	//fin callback_add_field	


					$crud->callback_add_field('impuesto_inmob', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Pago anterior: '.$anterior_impuestos['impuesto_inmob'].' - Detalle: '.$anterior_impuestos['inmob_desc'];

						$inmob = '</span><span class="required"></span><input id="field-impuesto_inmob"  name="impuesto_inmob" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur="input_ceros(this.id)" />';	
						return $inmob.$ant_impuestos;
					});//fin callback_add_field

					$crud->callback_add_field('inmob_desc', function () {						
					$inmo_detalle = '<textarea name="inmob_desc" maxlength="300" id="field-inmob_desc" onfocus="insertar_periodo_inmob(this)" style="width: 1100px; height: 26px;"></textarea>';	
						return $inmo_detalle;
					});	//fin callback_add_field


					$crud->callback_add_field('luz', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Pago anterior: '.$anterior_impuestos['luz'].' - Detalle: '.$anterior_impuestos['luz_detalle'];

						$luz = '<span id="b"></span><span class="required"></span><input id="field-luz" name="luz" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)"  />';	
						return $luz.$ant_impuestos;
					});//fin callback_add_field

					$crud->callback_add_field('luz_detalle', function () {						
					$luz_detalle = '<textarea name="luz_detalle" maxlength="300" id="field-luz_detalle" onfocus="insertar_periodo_luz(this)" style="width: 1100px; height: 26px;"></textarea>';	
						return $luz_detalle;
					});	//fin callback_add_field										

					$crud->callback_add_field('agua', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Pago anterior: '.$anterior_impuestos['agua'].' - Detalle: '.$anterior_impuestos['agua_detalle'];

						$agua = '<span id="b"></span><span class="required"></span><input id="field-agua" name="agua" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $agua.$ant_impuestos;
					});//fin callback_add_field

					$crud->callback_add_field('agua_detalle', function () {
					$agua_detalle = '<textarea name="agua_detalle" maxlength="300" id="field-agua_detalle" onfocus="insertar_periodo_agua(this)" style="width: 1100px; height: 26px;"></textarea>';	
						return $agua_detalle;
					});	//fin callback_add_field	


					$crud->callback_add_field('exp_extra', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Pago anterior: '.$anterior_impuestos['exp_extra'].' - Detalle: '.$anterior_impuestos['exp_extra_detalle'];

						$exp_extra = '<span id="b"></span><span class="required"></span><input id="field-exp_extra" name="exp_extra" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $exp_extra.$ant_impuestos;
					});//fin callback_add_field

					$crud->callback_add_field('exp_extra_detalle', function () {
					$exp_extra_detalle = '<textarea name="exp_extra_detalle" maxlength="300" id="field-exp_extra_detalle" style="width: 1100px; height: 26px;"></textarea>';	
						return $exp_extra_detalle;
					});	//fin callback_add_field											

					$crud->callback_add_field('saldos_otros', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Pago anterior:<b> '.$anterior_impuestos['saldos_otros'].'</b> - Detalle: <b>'.$anterior_impuestos['otros_detalle'].'</b>';

						$saldos = '<span id="b"></span><span class="required"></span><input id="field-saldos_otros" onkeypress="return validateFloatKeyPress(this,event);" name="saldos_otros" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $saldos.$ant_impuestos;
					});//fin callback_add_field

					$crud->callback_add_field('detalle_otros', function () {
					$detalle = '<textarea name="detalle_otros" maxlength="300" id="field-detalle_otros" style="width: 1100px; height: 26px;"></textarea>';	
						return $detalle;
					});	//fin callback_add_field		




					$crud->callback_add_field('varios1', function () {
						$idC=$this->uri->segment(4);
						//$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						/*$ant_impuestos='&nbsp Pago anterior:<b> '.$anterior_impuestos['saldos_otros'].'</b> - Detalle: <b>'.$anterior_impuestos['otros_detalle'].'</b>';*/

						$varios1 = '<span id="b"></span><span class="required"></span><input id="field-varios1" onkeypress="return validateFloatKeyPress(this,event);" name="varios1" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $varios1;
					});//fin callback_add_field

					$crud->callback_add_field('varios1_detalle', function () {
						$varios1_detalle = '<textarea name="varios1_detalle" maxlength="300" id="field-varios1_detalle" style="width: 1100px; height: 26px;"></textarea>';	
						return $varios1_detalle;
					});	//fin callback_add_field	


					$crud->callback_add_field('varios2', function () {
						$idC=$this->uri->segment(4);
						//$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						/*$ant_impuestos='&nbsp Pago anterior:<b> '.$anterior_impuestos['saldos_otros'].'</b> - Detalle: <b>'.$anterior_impuestos['otros_detalle'].'</b>';*/

						$varios2 = '<span id="b"></span><span class="required"></span><input id="field-varios2" onkeypress="return validateFloatKeyPress(this,event);" name="varios2" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $varios2;
					});//fin callback_add_field

					$crud->callback_add_field('varios2_detalle', function () {
						$varios2_detalle = '<textarea name="varios2_detalle" maxlength="300" id="field-varios2_detalle" style="width: 1100px; height: 26px;"></textarea>';	
						return $varios2_detalle;
					});	//fin callback_add_field														

					$crud->callback_add_field('total_pagar', function () {
						$total = '<span id="b"></span><span class="required"></span><span id="cero"><input  id="field-total_pagar" name="total_pagar" type="text" value="" maxlength="8" style="width:120px;height:40px"  style="font-weight:bold" class="numerico" readonly/></span>';
						$boton_sumar='&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="sumar" value="SUMAR" class="ui-input-button">';	

						$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

						$boton_imprimir = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="imprime" value="IMPRIMIR" class="ui-input-button">';						

						return $total.$boton_sumar.$boton_limpiar;
					});//fin callback_add_field		

					$crud->callback_add_field('observaciones', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Observaciones: '.$anterior_impuestos['observaciones'];						
					$obserc = '<textarea name="observaciones" maxlength="300" id="field-observaciones" style="width: 400px; height: 64px;"></textarea>';	
						return $obserc.$ant_impuestos;
					});	//fin callback_add_field	


					$crud->callback_add_field('rescinde_dentro', function (){
						$idC=$this->uri->segment(4);
						$datos_rescinde=$this->buscar_datos_model->rescinde_dentro($idC);

						$rescinde_dentro=$datos_rescinde[0];

						$rescinde_dentro='<input id="field-rescinde_dentro" name="rescinde_dentro" type="numeric" min="0" max="6" value="'.$rescinde_dentro.'" maxlength="1" style="width:50px;height:30px"  onkeyup="calcular_periodo_rescision()" onkeypress="return validateFloatKeyPress(this,event);"/>';

						$periodo='<input id="field-rescinde_periodo" name="rescinde_periodo" style="width:80px;height:30px" readonly/>';

						return $rescinde_dentro.' meses ,ponga 0 para cancelar la rescisión';	
					});//fin callback_add_field		

					$crud->callback_add_field('rescinde_periodo', function (){
						$idC=$this->uri->segment(4);
						$datos_rescinde=$this->buscar_datos_model->rescinde_dentro($idC);

						$rescinde_periodo=$datos_rescinde[1];	
											
						$periodo='<input id="field-rescinde_periodo" name="rescinde_periodo" value="'.$rescinde_periodo.'" style="width:80px;height:30px" readonly/>';

						return $periodo;
					});//fin callback_add_field		

					//ACA CHEQUEO LA CANTIDAD DE PAGOS QUE TIENE EL ALQUILER PARA HABILITAR LA OPCION RENUEVA
					$idC=$this->uri->segment(4);
					$diferencia=$this->buscar_datos_model->habilitar_renueva($idC);

					if($diferencia>=2){
						$crud->field_type('renueva','invisible');
					}else{
						$crud->required_fields('renueva');
					}
					/////////////////////////////////////////////////////////////////////////////////////////

					$crud->callback_add_field('renueva', function () {
						/*$idC=$this->uri->segment(4);
						$datos=$this->buscar_datos_model->n_pagos($idC);
						$dife=$datos['duracion']-$datos['nro_pago'];
						if($dife==1){*/
							$renueva = '<select id="field-renueva" name="renueva" required class="chosen-select" data-placeholder="Renueva Contrato"><option value=""  ></option><option value="SI">SI</option><option value="NO">NO</option></select>';
							$mensaje='<b style="color:red"><span id="texto_renueva" ></span></b>';
						/*}else{
							$renueva = '<select id="field-renueva" style="visibility:hidden" name="renueva" class="chosen-select" data-placeholder="Renueva Contrato"><option value=""  ></option><option value="SI">SI</option><option value="NO" selected>NO</option></select>';
						}*/
						return $renueva.$mensaje;
					});	//fin callback_add_field						


				}//cierro if
				

				$crud->callback_after_insert(array($this,'update_saldo_comision'));

				$crud->callback_before_insert(array($this,'fecha_pago'));


				$state = $crud->getState();
				$state_info = $crud->getStateInfo();				

			if($state == 'success'){
						$idP=$this->uri->segment(4);
						$this->db->select('idContrato');
						$this->db->from('pagos');
						$this->db->where('idpago',$idP);
						$query = $this->db->get();
						foreach ($query->result() as $row){
							$idC=$row->idContrato;
						}						
						//redirect('Alquiler/alquiler');		
						redirect('Verpago/verpago/'.$idC);	//echo "aca es el ADD";	//Do your cool stuff here . You don't need any State info you are in add
			}						

				$output = $crud->render();
				if($crud->getState() != 'list') {
					$this->_example_output($output);
				} else {
					return $output;
				}	

		}//cierro function pago

			public function update_saldo_comision($post_array,$pk){			
				$idC = $post_array["idContrato"];

				//busco el dni del locador para actualizar el campo pendientes en personas
				$this->db->select('locador,duracion,cant_pagos');
				$this->db->from('alquileres');
				$this->db->where('idContrato',$idC);
				$query = $this->db->get();
				foreach ($query->result() as $row){							
					$locador = $row->locador;
					$duracion = $row->duracion;
					$cant_pagos=$row->cant_pagos;
				}
				$rescinde=0;
				if($cant_pagos>6){

					$rescinde_fecha=$post_array['rescinde_periodo'];
					$rescinde_dentro=$post_array['rescinde_dentro'];

					$periodo_a_pagar=$post_array['periodo'];

					$datos_rescinde=$this->buscar_datos_model->rescinde_dentro($idC);

					$rescinde_dentro_model=$datos_rescinde[0];

							if($rescinde_dentro_model<>0){								
									if($rescinde_dentro<>$rescinde_dentro_model){
										if($rescinde_dentro<>0){
											$rescinde=1;
											$this->db->set('rescinde_dentro',$rescinde_dentro);	
											$this->db->set('rescinde_fecha',$rescinde_fecha);
											$this->db->set('rescision',1);
											$this->db->set('estado_contrato',"VIG.RESCINDE");
											$this->db->where('idContrato',$idC);
											$this->db->update('alquileres');											
										}elseif($rescinde_dentro==0){
											$rescinde=2;
											$this->db->set('rescinde_dentro',$rescinde_dentro);	
											$this->db->set('rescinde_fecha',"");
											$this->db->set('rescision',0);
											$this->db->set('estado_contrato',"VIGENTE");
											$this->db->where('idContrato',$idC);
											$this->db->update('alquileres');	
										}											
									}
								
							}else{
								if($rescinde_dentro<>0){
									$rescinde=1;
									$this->db->set('rescinde_dentro',$rescinde_dentro);	
									$this->db->set('rescinde_fecha',$rescinde_fecha);
									$this->db->set('rescision',1);
									$this->db->set('estado_contrato',"VIG.RESCINDE");
									$this->db->where('idContrato',$idC);
									$this->db->update('alquileres');								
								}
							}
							
						if($periodo_a_pagar==$rescinde_fecha){
							$this->db->set('estado_contrato',"RESCINDE");
							$this->db->where('idContrato',$idC);
							$this->db->update('alquileres');							
						}							
				}

				if($rescinde==1){
					$idI=$this->buscar_datos_model->buscar_idI($idC);
						$this->db->set('estado',7); //ALQ.RESCINDE						
						$this->db->where('idInmueble',$idI);
						$this->db->update('inmuebles');					
				}elseif($rescinde==2){
					$idI=$this->buscar_datos_model->buscar_idI($idC);
						$this->db->set('estado',1); //ALQLQUILADO						
						$this->db->where('idInmueble',$idI);
						$this->db->update('inmuebles');	
				}	

				$importe_alquiler=$post_array['valor_alquiler'];

				$operacion=$this->buscar_datos_model->tipo_operacion($idC);
				if($cant_pagos==13 and $operacion=="ALQUILER"){
					$importe_alquiler=$post_array['valor_alquiler'];
					$this->db->set('valor2',$importe_alquiler);										
				}elseif($cant_pagos==25 and $operacion=="ALQUILER"){
					$importe_alquiler=$post_array['valor_alquiler'];
					$this->db->set('valor3',$importe_alquiler);	
				} 	


				$hoy = date("Y-m-d"); 	
				$prox_venc=$this->buscar_datos_model->buscar_prox_venc($idC);				


				$venc= new DateTime($prox_venc);
				$fecha_hoy= new DateTime($hoy);

				$res = ($venc > $fecha_hoy) ? "AL DIA" : (($venc < $fecha_hoy) ? "DEUDA" : "AL DIA");
				

				
				$this->db->set('estado_alquiler',$res);


				$this->db->set('pendientes',"SI");				
				$this->db->where('idContrato',$idC);
				$this->db->update('alquileres');

				$this->db->select('*');
				$this->db->from('pagos');
				$this->db->where('idContrato',$idC);
				$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado
				$query=$this->db->get();
				$cant_pagos=$query->num_rows();				

				$this->db->select('renueva');
				$this->db->from('pagos');
				$this->db->where('idContrato',$idC);
				$query=$this->db->get();
				foreach ($query->result() as $row){							
					$renueva = $row->renueva;
				}

				$idI=$this->buscar_datos_model->buscar_idI($idC);	
				$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);

				if($cant_pagos == $duracion){								
					if($renueva=='SI'){						
						$locatario1=$datos_contrato['locatario1'];
						$idC=$datos_contrato['idC'];
						$this->db->set('valor',$importe_alquiler);
						$this->db->set('estado',4);//ALQ.RENUEV
						$this->db->set('renueva',$locatario1);
						$this->db->where('idInmueble',$idI);						
						$this->db->update('inmuebles');

						$this->db->set('estado_contrato',"RENUEVA");
						$this->db->where('idInmueble',$idI);
						$this->db->where('idContrato',$idC);
						$this->db->update('alquileres');
					}elseif ($renueva=='NO') {
						$importe_alquiler=$post_array['valor_alquiler'];
						$this->db->set('valor',$importe_alquiler);
						$this->db->set('estado',5); //ALQ.NO.REN
						$this->db->set('renueva',0);
						$this->db->where('idInmueble',$idI);
						$this->db->update('inmuebles');

						$this->db->set('estado_contrato',"FINALIZA");
						$this->db->where('idContrato',$idC);
						$this->db->update('alquileres');						
					}
				}elseif($cant_pagos==13){
						$importe_alquiler=$post_array['valor_alquiler'];
						$this->db->set('valor',$importe_alquiler);
						$this->db->where('idInmueble',$idI);
						$this->db->update('inmuebles');						
				}elseif($cant_pagos==25){
						$importe_alquiler=$post_array['valor_alquiler'];
						$this->db->set('valor',$importe_alquiler);
						$this->db->where('idInmueble',$idI);
						$this->db->update('inmuebles');						
				}

				return true;				

			}

			public function fecha_pago($post_array){				
				date_default_timezone_set('America/Argentina/Buenos_Aires');
				$post_array['fecha_pago']=date('d/m/Y G:i');


				$sesion= $this->session->userdata('usuario');
				$usuario=$sesion[1];			
				$post_array['usuario_creacion']=$usuario;

				//$post_array['field-rescinde_fecha']=$post_array['field-rescinde_periodo'];

				//busco la fecha proxvenc para incremetarla en un mes y actualizar despues en alquileres
				$idC = $post_array["idContrato"];

				$this->db->select('nro_pago');
				$this->db->from('pagos');
				$this->db->where('idContrato',$idC);
				$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado	
				$query=$this->db->get();
				foreach ($query->result() as $row) {
					$nro_pago=$row->nro_pago;
				}

				$post_array['nro_pago']=$nro_pago + 1;


				$this->db->select('proxVenc,locador,locatario1');
				$this->db->from('alquileres');
				$this->db->where('idContrato',$idC);		
					$query=$this->db->get();								
						foreach ($query->result() as $row){
							$proxvenc = $row->proxVenc;	
							$dniL=$row->locador;	
							$locatario1=$row->locatario1;				
						}
				$f=date($proxvenc);		
				$vencsig = strtotime('+1 month',strtotime($f));
				$fecha= date('Y-m-d',$vencsig);
				$post_array['prox_venc_sig']=$fecha;

				$post_array['locador']=$dniL;
				$post_array['locatario1']=$locatario1;

				$cant_pagos=$post_array['nro_pago']=$nro_pago + 1;

				$this->db->set('proxVenc',$fecha);
				$this->db->set('cant_pagos',$cant_pagos);
				$this->db->where('idContrato',$idC);
				$this->db->update('alquileres');

				$post_array['pagado_propietario']="NO";

				//$post_array['comision_inmo_paga']=$post_array['paga_c_inmo'];
				//$post_array['comision_inmo_debe_p']=$post_array['resta_saldocomision'];

				return $post_array;
			}
////////////////////EDITAR PAGO//////////////////////////
////////////////////////////////////////////////////////
		public function pagos_edit(){
			$crud = new grocery_CRUD();			
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');				

			//$crud->where('idpago',$id);

			$crud->set_relation('idContrato','alquileres','idContrato');	

/*$crud->fields('nro_pago','idContrato','fechaUltimoPago','periodo','valor_alquiler','mora_dias','paga_mora','punitorios','ci_a_pagar','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','veraz','expensas','expensas_detalle','csp','csp_detalle','luz','luz_detalle','agua','agua_detalle','saldos_otros','detalle_otros','total_pagar','observaciones','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador','locatario','renueva')*/


		$crud->edit_fields('idContrato','periodo','valor_alquiler','mora_dias','paga_mora','punitorios','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','veraz','expensas','expensas_detalle','csp','csp_detalle','impuesto_inmob','inmob_desc','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','total_pagar','fecha_update','usuario_update','estado_alquiler');

			//$crud->required_fields('idContrato');

			//$crud->field_type('locador','invisible');
			$crud->field_type('usuario_update','invisible');
			$crud->field_type('fecha_update','invisible');	
			$crud->field_type('estado_alquiler','invisible');				

			$crud->field_type('paga_mora','enum',array('SI','NO'));

			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('periodo','Periodo');
			$crud->display_as('valor_alquiler','Importe');
			$crud->display_as('mora_dias','Mora');
			$crud->display_as('paga_mora','¿Paga mora?');
			$crud->display_as('saldos_otros','Otros Gastos');
			$crud->display_as('detalle_otros','Detalle');
			$crud->display_as('total_pagar','Total a Cobrar ');
			$crud->display_as('comision_inmo_paga','CI pagada');
			$crud->display_as('comision_inmo_debe', 'CI adeudada');
			$crud->display_as('impuesto_inmob','Impuesto Inmob.');	
			$crud->display_as('inmob_desc','I-I detalle');				

			//$crud->field_type('comision_inmo_paga','invisible');				
				
			//$crud->set_crud_url_path(site_url('Alquiler/alquiler'));
			//id de contrato de la url				
			//	
			$idP=$this->uri->segment(4);
			$idC=$this->buscar_datos_model->buscar_idC_P($idP);	

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);//ALQUILER O COMODATO

						if($operacion=="COMODATO"){	
							$crud->display_as('idContrato','Comodato');							
						}else if($operacion=="COMERCIAL"){			
							$crud->display_as('idContrato','Comercial');							
						}else{
							$crud->display_as('idContrato','Alquiler');							
						}


					$crud->callback_edit_field('idContrato', function ($value, $primary_key) {
						$idP=$this->uri->segment(4);
						$idC=$this->buscar_datos_model->buscar_idC_P($idP);
						$this->db->select('idInmueble,locatario1');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$id_inmueble = $row->idInmueble;
							$dni_locatario1=$row->locatario1;											
						}

						$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$id_inmueble);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$dni_locador=$row->dni;
							$id_tipoinmueble=$row->idTipoInmueble;
							$idE=$row->idEdificio;
						}

						$direccion=$this->buscar_datos_model->buscar_inmueble($id_inmueble);

						$this->db->select('nombreTipo');
						$this->db->from('tipoinmuebles');
						$this->db->where('idTipoInmueble',$id_tipoinmueble);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$tipo_inmueble = $row->nombreTipo;
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni_locador);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre_locador = $row->apellidoNombre;
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni_locatario1);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre_locatario1 = $row->apellidoNombre;
						}

						if(isset($idE)){
							$this->load->model('buscar_datos_model');
							$edificio=$this->buscar_datos_model->buscar_edificio($idE);
							
							$nombreE=$edificio['edificio'];
							$Nedificio='&nbsp- <b>Edificio:</b> '.$nombreE;
						}else{
							$Nedificio="";
						}

						$operacion=$this->buscar_datos_model->tipo_operacion($idC);//ALQUILER O COMODATO

						if($operacion=="ALQUILER" || $operacion=="COMERCIAL"){
							$propietario="Locador";
							$persona="Locatario";
						}else{
							$propietario="Comodante";
							$persona="Comodatario";
						}

						$combo= '<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto" ><option value = '.$value.'>'.strtoupper($direccion).'</option></select>';

						return $combo.'&nbsp<b>'.$persona.':</b> '.strtoupper($nombre_locatario1).'  -  '.'   <b>'.$propietario.':</b> '.strtoupper($nombre_locador).$Nedificio;

					});//cierro callback_add_field


					$crud->callback_edit_field('periodo', function ($value, $primary_key) {
						$idP=$this->uri->segment(4);
						$idC=$this->buscar_datos_model->buscar_idC_P($idP);
						$this->db->select('periodo,nro_pago');
						$this->db->from('pagos');
						$this->db->where('idpago',$idP);
						$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado	
						$query=$this->db->get();
						foreach ($query->result() as $row){										
							$periodo=$row->periodo;
							$nro_pago=$row->nro_pago;
						}
						$this->db->select('valor1,punitorio');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();
						foreach ($query->result() as $row){														
							$punitorio_porc=$row->punitorio;
						}						
						//$proxvenc=date("d/m/Y", strtotime($prox_venc));
						$texto='<input id="field-periodo" class="form-control" name="periodo" type="text" value="'.$value.'" maxlength="10" style="width:85px;height:30px" />'.'<b>'.'&nbspPago nro: '.'<span id="nro_pago">'.$nro_pago.'</b></span>';				

						return $texto;	
					});//fin callback_add_field


					$crud->callback_edit_field('valor_alquiler', function ($value, $primary_key) {

						$valor_alquiler = '<input id="field-valor_alquiler" name="valor_alquiler" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" readonly/>';	
						
						return $valor_alquiler;
					});//cierro callback

					$crud->callback_edit_field('mora_dias', function ($value, $primary_key) {
						$idP=$primary_key;
						$datos_pagos=$this->buscar_datos_model->impuestos_inquilino($idP);
						$idC=$datos_pagos['idC'];
						$datos_contrato=$this->buscar_datos_model->n_pagos($idC);
						$idI=$datos_contrato['idI'];
						$datos_contrato=$this->buscar_datos_model->datos_contrato($idI);

						$punitorio_porc=$datos_contrato['punitorio'];

						$mora_dias = '  <input id="field-mora_dias" name="mora_dias" type="number" value="'.$value.'" min="0"  maxlength="3" style="width:70px;height:30px" class="numerico" onchange="calcular_punitorios()"/> dias por  &nbsp<input id="field-valor-diario" name="valor-diario" type="text" maxlength="5" class="numerico" style="width:75px;height:30px" readonly="readonly"> <b id="texto-total-punitorio">&nbspTotal $: </b><span id="total-mora">0</span> - <b><u> Mora diaria</u>: </b><span id="porcentaje">'.$punitorio_porc.'</span>%';	
						return $mora_dias;
					});//fin callback_add_field

						/*$mora_dias = '  <input id="field-mora_dias" name="mora_dias" type="number"  min="0" maxlength="3" style="width:70px;height:30px" class="numerico" onchange="calcular_punitorios()"/> dias por  &nbsp<input id="field-valor-diario" name="valor-diario" type="text" maxlength="5" class="numeric form-control" style="width:70px;height:30px" value="0"/ disabled> <b id="texto-total-punitorio">&nbspTotal $: </b><span id="total-mora">0</span> - <b><u> Mora diaria</u>: </b><span id="porcentaje">'.$punitorio_porc.'</span>%'.'<span id="mora_dias"></span>';	*/
					$crud->callback_edit_field('paga_mora', function ($value, $primary_key) {
						$combo_mora = '	<select id="field-paga_mora" name="paga_mora" class="chosen-select" data-placeholder="Seleccionar Paga mora" onchange="pagamora()" disabled><option value="'.$value.'" selected ></option>'.$value.'<option value="SI"  >SI</option><option value="NO"  >NO</option>  </select>';	
						return $combo_mora;
					});	//fin callback_add_field

					$crud->callback_edit_field('punitorios', function ($value, $primary_key) {					
						$mora_importe='<span id="b"></span><input id="field-punitorios" class="numerico" name="punitorios" type="text" value="0" maxlength="10" style="width:100px;height:30px"/>';
						return $mora_importe;	
					});//fin callback_add_field					

					$idP=$this->uri->segment(4);
					$nro_pago=$this->buscar_datos_model->nro_de_pago($idP);

					if($nro_pago==1){

						$crud->callback_edit_field('comision_inmo_paga', function ($value, $primary_key) {
							$idP=$this->uri->segment(4);
							$idC=$this->buscar_datos_model->buscar_idC_P($idP);
							$this->db->select('valor1,punitorio,comision_inmo_a_pagar');
							$this->db->from('alquileres');
							$this->db->where('idContrato',$idC);
							$query=$this->db->get();
							foreach ($query->result() as $row){							
								$valor=$row->valor1;
								$punitorio_porc=$row->punitorio;
								$comision_inmo=$row->comision_inmo_a_pagar;
							}	

							$CI=$comision_inmo/2; //MITAD INQUILINO, MITAD PROPIETARIO

							$ci_a_pagar='<input id="ci_a_pagar" name="ci_a_pagar" type="text" value="'.$CI.'" style="width:100px;height:30px" readonly/>';

							$comision_paga = '<input id="field-comision_inmo_paga" name="comision_inmo_paga" type="text" onblur ="input_ceros(this.id)" onclick="vaciar(this.id)" onkeyup="validar_comision()" value="'.$value.'" maxlength="10" style="width:100px;height:30px" class="numerico"/>'.'&nbspde '.$ci_a_pagar;	
							return $comision_paga;							
						});		

						$crud->callback_edit_field('comision_inmo_debe', function ($value, $primary_key) {
							$comision_debe = '<input id="field-comision_inmo_debe" name="comision_inmo_debe" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" class="numerico" readonly/>';	
							return $comision_debe;
						});
					
					}else{



						$crud->callback_edit_field('comision_inmo_paga', function ($value, $primary_key) {
							
							$ci_a_pagar='<input id="ci_a_pagar_anterior" name="ci_a_pagar" type="text" value="" style="width:100px;height:30px" readonly/>';

							$comision_paga = '<input id="field-comision_inmo_paga" name="comision_inmo_paga" type="text" onblur ="input_ceros(this.id)" onclick="vaciar(this.id)" onkeyup="validar_comision()" value="'.$value.'" maxlength="10" style="width:100px;height:30px" class="numerico"/>'.'&nbspde '.$ci_a_pagar;	
							return $comision_paga;
						});

						$crud->field_type('sellado_paga','invisible');			
						$crud->field_type('certi_firma','invisible');
						$crud->field_type('veraz','invisible');										
					}

		

					$crud->callback_edit_field('sellado_paga', function ($value, $primary_key) {					
						$idP=$this->uri->segment(4);
						$idC=$this->buscar_datos_model->buscar_idC_P($idP);
						$sellado_contrato=$this->buscar_datos_model->sellado_contrato($idC);

						$sellado_paga= '<span class="required"></span><input id="field-sellado_paga" name="sellado_paga" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" class="numerico" readonly/>'.'&nbsp50% de <span id="sellado_contrato" style="font-size:16px"/>'.$sellado_contrato.'</span>';
						return $sellado_paga;
					});//fin callback_add_field

					$crud->callback_edit_field('certi_firma', function ($value, $primary_key) {
						$certi_firma= '<span class="required"></span><input id="field-certi_firma" name="certi_firma" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" class="numerico"  onblur ="input_ceros(this.id)" onclick="vaciar(this.id)"/>';	
						return $certi_firma;
					});//fin callback_add_field

					$crud->callback_edit_field('veraz', function ($value, $primary_key) {
						$veraz= '<span class="required"></span><input id="field-veraz" name="veraz" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" class="numerico"  onblur ="input_ceros(this.id)" onclick="vaciar(this.id)"/>';
						return $veraz;
					});//fin callback_add_field															

					$idP=$this->uri->segment(4);
					$nro_pago=$this->buscar_datos_model->nro_de_pago($idP);						
					

					$crud->callback_edit_field('expensas', function ($value, $primary_key) {
						$expensas = '<span id="b"></span><input id="field-expensas" name="expensas" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $expensas;
					});

					$crud->callback_edit_field('expensas_detalle', function ($value, $primary_key) {
					$expensas_detalle = '<textarea name="expensas_detalle" maxlength="300" id="field-expensas_detalle" style="width: 1100px; height: 26px;" >'.$value.'</textarea>';	
						return $expensas_detalle;
					});	//fin callback_add_field											


					$crud->callback_edit_field('csp', function ($value, $primary_key) {
						$csp = '<span id="b"></span><span class="required"></span><input id="field-csp"  name="csp" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $csp;
					});//fin callback_add_field

					$crud->callback_edit_field('csp_detalle', function ($value, $primary_key) {
					$csp_detalle = '<textarea name="csp_detalle" maxlength="300" id="field-csp_detalle" style="width: 1100px; height: 26px;">'.$value.'</textarea>';	
						return $csp_detalle;
					});	//fin callback_add_field

					$crud->callback_edit_field('impuesto_inmob', function ($value, $primary_key) {
						$impuesto_inmob = '<span id="b"></span><span class="required"></span><input id="field-impuesto_inmob"  name="impuesto_inmob" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" />';	
						return $impuesto_inmob;
					});//fin callback_add_field

					$crud->callback_edit_field('inmob_desc', function ($value, $primary_key) {
					$inmob_desc = '<textarea name="inmob_desc" maxlength="300" id="field-inmob_desc" style="width: 1100px; height: 26px;">'.$value.'</textarea>';	
						return $inmob_desc;
					});	//fin callback_add_field	



					$crud->callback_edit_field('luz', function ($value, $primary_key) {
						$luz = '<span id="b"></span><span class="required"></span><input id="field-luz" name="luz" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)"  />';	
						return $luz;
					});//fin callback_add_field	

					$crud->callback_edit_field('luz_detalle', function ($value, $primary_key) {
					$luz_detalle = '<textarea name="luz_detalle" maxlength="300" id="field-luz_detalle" style="width: 1100px; height: 26px;">'.$value.'</textarea>';
						return $luz_detalle;
					});	//fin callback_add_field									

					$crud->callback_edit_field('agua', function ($value, $primary_key) {
						$agua = '<span id="b"></span><span class="required"></span><input id="field-agua" name="agua" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $agua;
					});//fin callback_add_field	

					$crud->callback_edit_field('agua_detalle', function ($value, $primary_key) {
					$agua_detalle = '<textarea name="agua_detalle" maxlength="300" id="field-agua_detalle" style="width: 1100px; height: 26px;">'.$value.'</textarea>';	
						return $agua_detalle;
					});	//fin callback_add_field

					$crud->callback_edit_field('exp_extra', function ($value, $primary_key) {
						$exp_extra = '<span id="b"></span><span class="required"></span><input id="field-exp_extra" name="exp_extra" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $exp_extra;
					});//fin callback_add_field

					$crud->callback_edit_field('exp_extra_detalle', function ($value, $primary_key) {
					$exp_extra_detalle = '<textarea name="exp_extra_detalle" maxlength="300" id="field-exp_extra_detalle" style="width: 1100px; height: 26px;">'.$value.'</textarea>';	
						return $exp_extra_detalle;
					});	//fin callback_add_field

					$crud->callback_edit_field('saldos_otros', function ($value, $primary_key) {
				$saldos = '<span id="b"></span><span class="required"></span><input id="field-saldos_otros" name="saldos_otros" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $saldos;
					});//fin callback_add_field

					$crud->callback_edit_field('detalle_otros', function ($value, $primary_key) {
					$detalle = '<textarea name="detalle_otros" maxlength="300" id="field-detalle_otros" style="width: 1100px; height: 26px;">'.$value.'</textarea>';	
						return $detalle;
					});	//fin callback_add_field

					$crud->callback_edit_field('varios1', function ($value, $primary_key) {
						$varios1 = '<span id="b"></span><span class="required"></span><input id="field-varios1" name="varios1" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $varios1;
					});//fin callback_add_field

					$crud->callback_edit_field('varios1_detalle', function ($value, $primary_key) {
					$varios1_detalle = '<textarea name="varios1_detalle" maxlength="300" id="field-varios1_detalle" style="width: 1100px; height: 26px;">'.$value.'</textarea>';	
						return $varios1_detalle;
					});	//fin callback_add_field

					$crud->callback_edit_field('varios2', function ($value, $primary_key) {
						$varios2 = '<span id="b"></span><span class="required"></span><input id="field-varios2" name="varios2" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $varios2;
					});//fin callback_add_field

					$crud->callback_edit_field('varios2_detalle', function ($value, $primary_key) {
					$varios2_detalle = '<textarea name="varios2_detalle" maxlength="300" id="field-varios2_detalle" style="width: 1100px; height: 26px;">'.$value.'</textarea>';	
						return $varios2_detalle;
					});	//fin callback_add_field


					$crud->callback_edit_field('observaciones', function ($value, $primary_key) {
					$obserc = '<textarea name="observaciones" maxlength="300" id="field-observaciones" style="width: 650px; height: 60px;">'.$value.'</textarea>';	
						return $obserc;
					});	//fin callback_add_field					


					$crud->callback_edit_field('total_pagar', function ($value, $primary_key) {
						$total = '<span id="b"></span><span class="required"></span><span id="cero"><input  id="field-total_pagar" name="total_pagar" type="text" value="'.$value.'" maxlength="8" style="width:120px;height:65px"  style="font-weight:bold" class="numerico" /></span>';
						$boton_sumar='&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="sumar" value="SUMAR" class="ui-input-button">';	

						$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

						$boton_imprimir = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="imprime" value="IMPRIMIR" class="ui-input-button">';						

						return $total.$boton_sumar.$boton_limpiar;
					});//fin callback_add_field		

	

				//$crud->callback_before_update(array($this, 'update_CI_update'));
				$crud->callback_after_update(array($this, 'update_saldo_comision_update'));
				$crud->callback_before_update(array($this,'fecha_pago_update'));

					$state = $crud->getState();
					$state_info = $crud->getStateInfo();					
					if($state == 'success') {
						$idP=$this->uri->segment(4);
						$this->db->select('idContrato');
						$this->db->from('pagos');
						$this->db->where('idpago',$idP);
						$query = $this->db->get();
						foreach ($query->result() as $row){
							$idC=$row->idContrato;
						}			
							redirect('Verpago/verpago/'.$idC);
					}

				$output = $crud->render();
				$this->_example_output($output);				
		}
			public function update_CI_update($post_array,$pk){
				/*$id_contrato = $post_array["idContrato"];
				$this->db->select('comision_inmo');
				$this->db->from('alquileres');
				$this->db->where('idContrato',$id_contrato);
				$query=$this->db->get();
				foreach ($query->result() as $row){
					$comision_saldo=$row->comision_inmo;
				}
				$this->db->select('paga_c_inmo');	
				$this->db->from('pagos');	
				$this->db->where('idpago',$pk);
				$query=$this->db->get();
				foreach ($query->result() as $row) {
					$paga_CI=$row->paga_c_inmo;
				}								
				$saldoInicial=$comision_saldo+$paga_CI;				

				$this->db->set('comision_inmo',$saldoInicial);				
				$this->db->where('idContrato',$id_contrato);
				$this->db->update('alquileres');*/
			
				//return $post_array;		
			}				

			public function update_saldo_comision_update($post_array,$pk){
//////////////////////////////////////////////
						$idC=$post_array['idContrato'];
						//$idC=$this->buscar_datos_model->buscar_idC_P($idP);
				/*$this->db->select('comision_inmo');
				$this->db->from('alquileres');
				$this->db->where('idContrato',$id_contrato);
				$query=$this->db->get();
				foreach ($query->result() as $row){
					$comision_saldo=$row->comision_inmo;
				}

				$comision_paga = $post_array["paga_c_inmo"];
				$CI_saldo=$comision_saldo-$comision_paga;*/				
				$this->db->set('estado_alquiler',"AL DIA");				
				$this->db->where('idContrato',$idC);
				$this->db->update('alquileres');
////////////////////////////////////////
				/*$idC = $post_array["idContrato"];
				$this->db->select('comision_inmo_debe,saldo_inicial_CI');
				$this->db->from('alquileres');
				$this->db->where('idContrato',$idC);
				$query=$this->db->get();
				foreach ($query->result() as $row){
					$comision_saldo=$row->comision_inmo_debe;
					$saldo_ICI=$row->saldo_inicial_CI;
				}
				$diferencia_CI=$saldo_inicial_CI-$comision_saldo;

				$this->db->select('paga_c_inmo');	
				$this->db->from('pagos');	
				$this->db->where('idContrato',$idC);
				$query=$this->db->get();
				
				$n=0;
				foreach ($query->result() as $row) {					
					$n=$n+1;
					$paga_CI[$n]=$row->paga_c_inmo;
				}
				$CI_saldo_update=$saldo_ICI;

				for($i=1;$i<=$n;$i++){
					$CI_saldo_update=$CI_saldo_update - $paga_CI[$i];
				}*/
				
				//$comision_paga_update = $post_array["paga_c_inmo"];	

				//$CI_saldo_update=$saldoInicial-$comision_paga_update;

				/*$this->db->set('sellado_contrato',$post_array['sellado_contrato']);
				$this->db->set('sellado_paga',$post_array['sellado_paga']);
				$this->db->set('certi_firma',$post_array['certi_firma']);
				$this->db->set('veraz',$post_array['veraz']);
				$this->db->set('comision_inmo_paga',$post_array['comision_inmo_paga_p']);
				$this->db->set('comision_inmo_debe',$post_array['comision_inmo_debe_p']);

				//$this->db->set('comision_inmo_debe',$CI_saldo_update);
				$this->db->where('idContrato',$idC);
				$this->db->update('alquileres');*/	
				return true;			
			}

			public function fecha_pago_update($post_array,$primary_key){				
				date_default_timezone_set('America/Argentina/Buenos_Aires');
				$post_array['fecha_update']=date('d/m/Y G:i');		

				$sesion= $this->session->userdata('usuario');
				$usuario=$sesion[1];						
				$post_array['usuario_update']=$usuario;
				
				//$post_array['comision_inmo_paga_p']=$post_array['paga_c_inmo'];	
				return $post_array;
			}

		public function rescindir_contrato(){
			$crud = new grocery_CRUD();			
			$crud->set_table('pagos');	
			$crud->set_subject('Rescisión');

			//$crud->unset_back_to_list();
			$crud->set_relation('idContrato','alquileres','locatario1');
			$crud->set_relation('idContrato','alquileres','proxVenc');


			$idC=$this->uri->segment(4);
			$datos=$this->buscar_datos_model->n_pagos($idC);

			$idP=$datos['idpago'];

			$deuda_ci=$this->buscar_datos_model->deuda_ci($idP);
			if($deuda_ci>0){
				$crud->fields('idContrato','periodo','fechaUltimoPago','valor_alquiler','comision_inmo_debe','comision_inmo_paga','expensas','expensas_detalle','csp','csp_detalle','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','total_pagar','observaciones','fecha_pago','usuario_creacion','pagado_propietario','locador','locatario1','rescision','nro_pago');
			}else{
				$crud->fields('idContrato','periodo','fechaUltimoPago','valor_alquiler','expensas','expensas_detalle','csp','csp_detalle','luz','luz_detalle','agua','agua_detalle','exp_extra','exp_extra_detalle','saldos_otros','detalle_otros','total_pagar','observaciones','fecha_pago','usuario_creacion','pagado_propietario','locador','locatario1','rescision','nro_pago');				
			}



			
			$crud->required_fields('idContrato','total_pagar');

			$crud->field_type('nro_pago','invisible');
			$crud->field_type('rescision','invisible');
			$crud->field_type('locatario1','invisible');
			$crud->field_type('locador','invisible');
			$crud->field_type('usuario_creacion','invisible');
			$crud->field_type('fecha_pago','invisible');
			$crud->field_type('prox_venc_sig','invisible');
			$crud->field_type('pagado_propietario','invisible');
			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('periodo','Periodo a Pagar');
			$crud->display_as('valor_alquiler','Importe de Rescisión');		
			//$crud->display_as('comision_inmo_debe','CI Actualizado');
			$crud->display_as('comision_inmo_debe','CI debe');						
			
			$crud->display_as('saldos_otros','Saldos Varios');
			$crud->display_as('detalle_otros','Detalle');
			$crud->display_as('total_pagar','Total a Cobrar ');
			//$crud->display_as('paga_c_inmo','Saldo por CI');
			$crud->display_as('fechaUltimoPago','Ultimo pago');
			$crud->display_as('exp_extra','Exp.Extraord.');	
			$crud->display_as('exp_extra_detalle','Extraord.Detalle');			
			

			$idC=$this->uri->segment(4);	

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			if($operacion=="COMODATO"){
				$crud->display_as('idContrato','Comodato');
				$propietario="Comodatario";
				$persona="Comodante";
			}else if($operacion=="ALQUILER"){
				$crud->display_as('idContrato','Alquiler');
				$propietario="Locador";
				$persona="Locatario";
			}else{
				$crud->display_as('idContrato','Comercial');
				$propietario="Locador";
				$persona="Locatario";				
			}


			//aca completo datos del inmueble, locatario y locador				
				if(is_numeric($idC)){
					$crud->callback_add_field('idContrato', function () {
						$idC=$this->uri->segment(4);
						$this->db->select('idInmueble,locatario1');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$id_inmueble = $row->idInmueble;
							$dni_locatario1=$row->locatario1;											
						}

						$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
						$this->db->from('inmuebles');
						$this->db->where('idInmueble',$id_inmueble);		
						$query=$this->db->get();								
						foreach ($query->result() as $row){
							$dni_locador=$row->dni;
							$id_tipoinmueble=$row->idTipoInmueble;
							$idE=$row->idEdificio;
						}

						$direccion=$this->buscar_datos_model->buscar_inmueble($id_inmueble);

						$this->db->select('nombreTipo');
						$this->db->from('tipoinmuebles');
						$this->db->where('idTipoInmueble',$id_tipoinmueble);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$tipo_inmueble = $row->nombreTipo;
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni_locador);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre_locador = $row->apellidoNombre;
						}

						$this->db->select('apellidoNombre');
						$this->db->from('personas');
						$this->db->where('dni',$dni_locatario1);		
						$query=$this->db->get();			
						foreach ($query->result() as $row){
							$nombre_locatario1 = $row->apellidoNombre;
						}

						if(isset($idE)){
							$this->load->model('buscar_datos_model');
							$edificio=$this->buscar_datos_model->buscar_edificio($idE);
							
							$nombreE=$edificio['edificio'];
							$Nedificio='&nbsp- <b>Edificio:</b><span style="color:blue; font-size:15px">'.$nombreE.'</span>';
						}else{
							$Nedificio="";
						}

						$operacion=$this->buscar_datos_model->tipo_operacion($idC);

						if($operacion=="COMODATO"){							
							$propietario="Comodante";
							$persona="Comodatario";
						}else{							
							$propietario="Locador";
							$persona="Locatario";
						}

						$combo= '<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto" ><option value = '.$idC.'>'.strtoupper($direccion).'</option></select>';

						return $combo.'&nbsp<b>'.$persona.':</b><span style="color:blue; font-size:15px"> '.strtoupper($nombre_locatario1).'</span>  -  '.'   <b><span >'.$propietario.':</b> <span style="color:blue; font-size:15px">'.strtoupper($nombre_locador).'</span>'.$Nedificio;

					});//cierro callback_add_field

					$crud->callback_add_field('periodo', function () {
						$idC=$this->uri->segment(4);
						$this->db->select('proxVenc,valor1,punitorio,fechaInicio,fechaFin');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();
						foreach ($query->result() as $row){
							$prox_venc = $row->proxVenc;							
							$punitorio_porc=$row->punitorio;
							$fecha_inicio=$row->fechaInicio;
							$fecha_fin=$row->fechaFin;
						}
						setlocale(LC_TIME,"spanish");
						date_default_timezone_set('America/Argentina/Buenos_Aires');

						$proxvenc=date("d-m-Y", strtotime($prox_venc));

						$texto='<input id="field-periodo" class="form-control" name="periodo" type="text" value="'.$prox_venc.'" maxlength="10" style="width:85px;height:30px"/> '.'<b> &nbsp&nbsp Vencimiento del periodo: </b><span id="venc">'.$proxvenc.'</span>';

						$perdio_locativo=' - <b>Período Locativo:</b> <span id="inicio">'.$fecha_inicio.'</span> - '.'<span id="fin">'.$fecha_fin.'</span>';	
											
						return $texto.$perdio_locativo;	
					});//fin callback_add_field	


					$crud->callback_add_field('fechaUltimoPago', function () {	
						$idC=$this->uri->segment(4);					
						$this->db->select('periodo,total_pagar');
						$this->db->from('pagos');
						$this->db->where('idContrato',$idC);
						$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado	
						$this->db->limit(1);
						$this->db->order_by('idpago','DESC');

						$query=$this->db->get();
						if($query->num_rows() > 0){								
							foreach ($query->result() as $row){							
								$periodo_anterior = $row->periodo;
								$importe_anterior = $row->total_pagar;								
							}
						}else{
							$periodo_anterior = " - ";
							$importe_anterior = " 0 ";
						}
						//ACA TOMO EL PERIODO LOCATIVO Y VEO SI ESTA EN CONDICIONES DE RESCINDIR EL CONTRATO
						$fechaI_F = $this->buscar_datos_model->periodo_locativo($idC);
						$fechaI = date('d-m-Y',strtotime($fechaI_F['inicio']));


						$operacion=$this->buscar_datos_model->tipo_operacion($idC);

						if($operacion=="ALQUILER" || $operacion=="COMERCIAL"){
							$fecha_requisito_temp = strtotime ( '+5 month' , strtotime ( $fechaI ) ) ;
							$fechaI_temp = date ( 'd-m-Y' , $fecha_requisito_temp );


							$fecha_requisito = date("m-Y", strtotime($fechaI_temp));

							//$fecha_requisito = $this->buscar_datos_model->formato_fecha($fechaI_temp);

							$puede_rescindir=$this->buscar_datos_model->cantidad_pagos($idC);
							if($puede_rescindir == "NO"){
								$rescindir = '<span id="NORESCINDE"><b style="font-size:18px;color:#FF0004">NO PUEDE RESCINDIR</b></span>';
							}else{
								$rescindir = '<span id="RESCINDE"><b style="font-size:18px;color:#028B16">PUEDE RESCINDIR</b></span>';
							}							

							$ultimo_pago = '<input id="field-fechaUltimoPago" name="fechaUltimoPago" type="text" value="'.$periodo_anterior.'" maxlength="10" style="width:100px;height:30px"/>&nbsp; <b>- PERÍODO MÍNIMO PARA RESCINDIR (6 períodos pagos):</b> <b style="font-size:18px;color:#FF0004">'.$fecha_requisito.'</b> - '.$rescindir;						


						}elseif ($operacion=="COMODATO"){
							$fecha_requisito_temp = strtotime ( '+0 month' , strtotime ( $fechaI ) ) ;
							$fechaI_temp = date ( 'd-m-Y' , $fecha_requisito_temp );

							$fecha_requisito = date("m-Y", strtotime($fechaI_temp));
							//$fecha_requisito = $this->buscar_datos_model->formato_fecha($fechaI_temp);

							$puede_rescindir=$this->buscar_datos_model->cantidad_pagos($idC);
							if($puede_rescindir == "NO"){
								$rescindir = '<span id="NORESCINDE"><b style="font-size:18px;color:#FF0004">NO PUEDE RESCINDIR</b></span>';
								


							}else{
								$rescindir = '<span id="RESCINDE"><b style="font-size:18px;color:#028B16">PUEDE RESCINDIR</b></span>';
							}							

							$ultimo_pago = '<input id="field-fechaUltimoPago" name="fechaUltimoPago" type="text" value="'.$periodo_anterior.'" maxlength="10" style="width:100px;height:30px"/>&nbsp; <b>- PERÍODO MÍNIMO PARA RESCINDIR (1 períodos pagos):</b> <b style="font-size:18px;color:#FF0004">'.$fecha_requisito.'</b> - '.$rescindir;						
						}				

						
						return $ultimo_pago;
					});//cierro callback_add_field

					$puede_rescindir=$this->buscar_datos_model->cantidad_pagos($idC);
						if($puede_rescindir == "NO"){
							$rescindir = '<span id="NORESCINDE"><b style="font-size:18px;color:#FF0004">NO PUEDE RESCINDIR</b></span>';
							$crud->field_type('valor_alquiler','invisible');
							$crud->field_type('mora_dias','invisible');
							$crud->field_type('paga_mora','invisible');
							$crud->field_type('punitorios','invisible');
							$crud->field_type('comision_inmo_debe','invisible');
							$crud->field_type('comision_inmo_paga','invisible');
							$crud->field_type('expensas','invisible');
							$crud->field_type('expensas_detalle','invisible');
							$crud->field_type('csp','invisible');
							$crud->field_type('csp_detalle','invisible');	
							$crud->field_type('luz','invisible');
							$crud->field_type('luz_detalle','invisible');
							$crud->field_type('agua','invisible');
							$crud->field_type('agua_detalle','invisible');
							$crud->field_type('exp_extra','invisible');
							$crud->field_type('exp_extra_detalle','invisible');							
							$crud->field_type('saldos_otros','invisible');
							$crud->field_type('detalle_otros','invisible');	
							$crud->field_type('total_pagar','invisible');
							$crud->field_type('observaciones','invisible');

						}else{
							$rescindir = '<b style="font-size:18px;color:#028B16">PUEDE RESCINDIR</b>';
						}											
					

					$crud->callback_add_field('valor_alquiler', function () {	
						$idC=$this->uri->segment(4);					
						$this->db->select('valor_alquiler');
						$this->db->from('pagos');
						$this->db->where('idContrato',$idC);
						$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado							
						$query=$this->db->get();																		
						foreach ($query->result() as $row){							
								$valor = $row->valor_alquiler;							
						}

						$this->db->select('periodo');
						$this->db->from('pagos');
						$this->db->where('idContrato',$idC);
						$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado	
						$query=$this->db->get();
						$cant_pagos=$query->num_rows();	

						$operacion=$this->buscar_datos_model->tipo_operacion($idC);	

						$datos_rescinde=$this->buscar_datos_model->rescinde_dentro($idC);						

						$ultimo_periodo_pago=$this->buscar_datos_model->buscar_ultimo_pago($idC);

						$rescinde_dentro=$datos_rescinde[0];
						$rescinde_periodo=$datos_rescinde[1];

						/*if($ultimo_periodo_pago==$rescinde_periodo){
							
						}*/


						if($operacion=="ALQUILER" || $operacion=="COMERCIAL"){
							if($cant_pagos >= 6 and $rescinde_dentro==3 and ($ultimo_periodo_pago==$rescinde_periodo)){
								$valor_rescision = 0;
								$valor_rescision=number_format( $valor_rescision, 2, '.', '' );
								$texto = '&nbsp;<b style="font-size:18px;color:red">NO PAGA RESCISION, avisó con '.$rescinde_dentro.' meses de antelación </b>';
							}else if($cant_pagos >= 6 and $cant_pagos < 12){
								$valor_rescision = $valor * 1.5;
								$valor_rescision=number_format( $valor_rescision, 2, '.', '' );
								$texto = "&nbsp;<b> = ".$valor." * 1,5 - UN MES Y MEDIO DE ALQUILER, TOMANDO COMO REFERENCIA EL ÚLTIMO VALOR ABONADO </b>";
							}else{
								if(isset($valor)){
									$valor_rescision = $valor;
									$texto="&nbsp; - <b>UN MES DE ALQUILER, TOMANDO COMO REFERENCIA EL ÚLTIMO VALOR ABONADO</b>";
								}else{
									$valor_rescision = "";
									$texto="";
								}	
							}
						}elseif ($operacion=="COMODATO") {
							if($cant_pagos > 0 && isset($valor)){
								$valor_rescision = $valor;
								$texto="&nbsp; - <b>UN MES DE ALQUILER, TOMANDO COMO REFERENCIA EL ÚLTIMO VALOR ABONADO</b>";
							}else{
								$valor_rescision = "";
								$texto="";
							}
						}

						$datos_rescinde=$this->buscar_datos_model->rescinde_dentro($idC);

						$rescinde_dentro=$datos_rescinde[0];

						if($rescinde_dentro<>0){
							$boton_cancelar_rescision='&nbsp&nbsp&nbsp<button onclick="cancelar_rescision('.$idC.')">CANCELAR RESCISION </button>';	
						}else{
							$boton_cancelar_rescision='';
						}

						

						$valor_alquiler = '<input id="field-valor_alquiler" name="valor_alquiler" type="text" value="'.$valor_rescision.'" maxlength="10" style="width:100px;height:30px"/>'.$texto.$boton_cancelar_rescision;				
						
						return $valor_alquiler;
					});//cierro callback_add_field	


					$crud->callback_add_field('comision_inmo_paga', function () {
						$idC=$this->uri->segment(4);
						$datos=$this->buscar_datos_model->n_pagos($idC);

						$idP=$datos['idpago'];

						$deuda_ci=$this->buscar_datos_model->deuda_ci($idP);

						$comision_paga = '<input id="field-comision_inmo_paga" name="comision_inmo_paga" value="" type="text" maxlength="10" style="width:100px;height:30px" class="numerico"/>';	
						return $comision_paga;
					});

					/*$crud->callback_add_field('mora_dias', function () {
						$mora_dias = '  <input id="field-mora_dias" name="mora_dias" type="text" value="0" maxlength="3" style="width:40px;height:30px" class="numeric form-control"/> dias por  &nbsp<input id="field-valor-diario" name="valor-diario" type="text" maxlength="5" class="numeric form-control" style="width:60px;height:30px" value="0"/ disabled> <b id="texto-total-punitorio">&nbspTotal $: </b><span id="total-mora">0</span> ';	
						return $mora_dias;
					});//fin callback_add_field

					$crud->callback_add_field('paga_mora', function () {
						$combo_mora = '	<select id="field-paga_mora" name="paga_mora" class="chosen-select" data-placeholder="Seleccionar Paga mora" onchange="pagamora()" disabled><option value=""  ></option><option value="SI"  >SI</option><option value="NO"  >NO</option>  </select>';	
						return $combo_mora;
					});	//fin callback_add_field

					$crud->callback_add_field('punitorios', function () {					
						$mora_importe='<span id="b"></span><input id="field-punitorios" class="numerico" name="punitorios" type="text" value="0" maxlength="10" style="width:100px;height:30px" readonly="readonly"/>';
						return $mora_importe;	
					});*///fin callback_add_field


					$crud->callback_add_field('comision_inmo_debe', function () {
						$idC=$this->uri->segment(4);
						$datos=$this->buscar_datos_model->n_pagos($idC);

						$idP=$datos['idpago'];

						$deuda_ci=$this->buscar_datos_model->deuda_ci($idP);


						$comision_debe = '<input id="field-comision_inmo_debe" name="comision_inmo_debe" value="'.$deuda_ci.'" type="text" maxlength="10" value=""style="width:100px;height:30px" class="numerico" readonly/>';	
						return $comision_debe;
					});									


					$crud->callback_add_field('expensas', function () {
						$expensas = '<input id="field-expensas" name="expensas" type="text" value="0" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $expensas;
					});
					$crud->callback_add_field('expensas_detalle', function () {						
						$expensas_detalle = '<textarea name="expensas_detalle" maxlength="300" id="field-expensas_detalle"></textarea>';	
						return $expensas_detalle;
					});	//fin callback_add_field						

					$crud->callback_add_field('csp', function () {
						$csp = '<span class="required"></span><input id="field-csp"  name="csp" type="text" value="0" maxlength="8" style="width:100px;height:100px" onkeypress="return validateFloatKeyPress(this,event);" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $csp;
					});//fin callback_add_field

					$crud->callback_add_field('csp_detalle', function () {
					$csp_detalle = '<textarea name="csp_detalle" maxlength="300" id="field-csp_detalle"></textarea>';	
						return $csp_detalle;
					});	//fin callback_add_field					

					$crud->callback_add_field('luz', function () {
						$luz = '<span class="required"></span><input id="field-luz" name="luz" type="text" value="0" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)"  />';	
						return $luz;
					});//fin callback_add_field		

					$crud->callback_add_field('luz_detalle', function () {
					$luz_detalle = '<textarea name="luz_detalle" maxlength="300" id="field-luz_detalle"></textarea>';	
						return $luz_detalle;
					});	//fin callback_add_field								

					$crud->callback_add_field('agua', function () {
						$agua = '<span class="required"></span><input id="field-agua" name="agua" type="text" value="0" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $agua;
					});//fin callback_add_field	

					$crud->callback_add_field('agua_detalle', function () {
					$agua_detalle = '<textarea name="agua_detalle" maxlength="300" id="field-agua_detalle"></textarea>';	
						return $agua_detalle;
					});	//fin callback_add_field

					$crud->callback_add_field('exp_extra', function () {
						$idC=$this->uri->segment(4);
						$anterior_impuestos=$this->buscar_datos_model->anterior_impuestos($idC);
						$ant_impuestos='&nbsp Pago anterior: '.$anterior_impuestos['exp_extra'].' - Detalle: '.$anterior_impuestos['exp_extra_detalle'];

						$exp_extra = '<span id="b"></span><span class="required"></span><input id="field-exp_extra" name="exp_extra" type="text" value="0.00" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $exp_extra.$ant_impuestos;
					});//fin callback_add_field

					$crud->callback_add_field('exp_extra_detalle', function () {
					$exp_extra_detalle = '<textarea name="exp_extra_detalle" maxlength="300" id="field-exp_extra_detalle"></textarea>';	
						return $exp_extra_detalle;
					});	//fin callback_add_field										

					$crud->callback_add_field('saldos_otros', function () {
				$saldos = '<span class="required"></span><input id="field-saldos_otros" name="saldos_otros" type="text" value="0" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $saldos;
					});//fin callback_add_field

					$crud->callback_add_field('detalle_otros', function () {
					$detalle = '<textarea name="detalle_otros" maxlength="300" id="field-detalle_otros"></textarea>';	
						return $detalle;
					});	//fin callback_add_field					

					$crud->callback_add_field('total_pagar', function () {
						$total = '<span class="required"></span><span id="cero"><input  id="field-total_pagar" name="total_pagar" type="text" value="" maxlength="8" style="width:130px;height:65px"  style="font-weight:bold; font-size:25px" class="numerico" readonly/></span>';
						$boton_sumar='&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="sumar_rescindir" value="SUMAR" class="ui-input-button">';	

						$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

						$boton_imprimir = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="imprime" value="IMPRIMIR" class="ui-input-button">';						

						return $total.$boton_sumar.$boton_limpiar;
					});//fin callback_add_field		

					$crud->callback_add_field('observaciones', function () {
					$obserc = '<textarea name="observaciones" maxlength="300" id="field-observaciones"></textarea>';	
						return $obserc;
					});	//fin callback_add_field

				}//cierro if	

				//$crud->callback_after_insert(array($this, 'update_saldo_comision'));
				$crud->callback_before_insert(array($this, 'update_rescision'));
				$crud->callback_after_insert(array($this, 'update_alquiler'));

				$state = $crud->getState();
				$state_info = $crud->getStateInfo();					
				if($state == 'success') {	
					$idP=$this->uri->segment(4);
					$this->db->select('idContrato');
					$this->db->from('pagos');
					$this->db->where('idpago',$idP);
					$query = $this->db->get();
					foreach ($query->result() as $row){
						$idC=$row->idContrato;
					}			
					redirect('Verpago/verpago/'.$idC);
				}


			$output = $crud->render();
			$this->_example_output($output);			
		}


		public function update_rescision($post_array){
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$post_array['fecha_pago']=date('d/m/Y G:i');

			$sesion= $this->session->userdata('usuario');
			$usuario=$sesion[1];							
			$post_array['usuario_creacion']=$usuario;
			$post_array['pagado_propietario']="NO";				
			$post_array['rescision'] = 1;
			$post_array['comision_inmo_debe'] = 0.00;

			$idC=$post_array['idContrato'];
			$this->db->select('locador,locatario1');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);
			$query = $this->db->get();
			foreach ($query->result() as $row){							
				$locador = $row->locador;
				$locatario1 = $row->locatario1;
			}

			$idI=$this->buscar_datos_model->buscar_idI($idC);
			$this->db->set('estado',0);				
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');			

			$post_array['locador']=$locador;
			$post_array['locatario1']=$locatario1;

			$this->db->select('nro_pago');
			$this->db->from('pagos');
			$this->db->where('idContrato',$idC);
			$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado	
			$query=$this->db->get();
			foreach ($query->result() as $row) {
				$nro_pago=$row->nro_pago;
			}

			$post_array['nro_pago']=$nro_pago + 1;

			return $post_array;
		}

		public function update_alquiler($post_array,$primary_key){
			$idC=$post_array['idContrato'];
			$this->db->set('rescision',1);
			$this->db->set('estado_contrato','RESCINDIDO');				
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');			
		}

		public function anular_rescision($idC){
			$idI=$this->buscar_datos_model->buscar_idI($idC);			

			$this->db->set('estado',1);
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');

			$this->db->set('estado_contrato','VIGENTE');
			$this->db->set('rescision',0);
			$this->db->set('rescinde_dentro',0);
			$this->db->set('rescinde_fecha',"");
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');			
			
			redirect('Alquiler/alquiler');
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