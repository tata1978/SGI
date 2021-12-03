<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidacion extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->config->load('grocery_crud');
		$this->load->model('buscar_datos_model');
		$this->load->helper('numeros');
		$this->load->library('session');
	}
	public function index(){	
			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	
	}
	public function descargar_pdf($idL){
			$data = [];

			$this->db->select('idpago,nro_liq,idContrato,locador,locatario1,alquiler,punitorios,comiAdmin,comision_inmo_paga,comision_inmo_debe,sellado_paga,certi_firma,expensas,expensas_detalle,agua,agua_detalle,impuesto_csp,csp_desc,impuesto_inmob,inmob_desc,descArreglos,arreglos_desc,expExtras,expextra_desc,saldos_varios,detalle_saldos,varios1,varios1_detalle,varios2,varios2_detalle,observaciones,totalPagar,fecha,usuario');
			$this->db->from('liquidaciones');
			$this->db->where('idLiquida',$idL);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idP = $row->idpago;
				$nro_liq=$row->nro_liq;
				$idC=$row->idContrato;
				$locador=$row->locador;
				$locatario1=$row->locatario1;
				$alquiler=$row->alquiler;
				$punitorios=$row->punitorios;
				$comiAdmin=$row->comiAdmin;
				$comision_inmo_paga=$row->comision_inmo_paga;
				$comision_inmo_debe=$row->comision_inmo_debe;
				$sellado=$row->sellado_paga;
				$firma=$row->certi_firma;
				$expensas=$row->expensas;
				$expensas_detalle=$row->expensas_detalle;	
				$agua=$row->agua;
				$agua_detalle=$row->agua_detalle;							
				$imp_csp=$row->impuesto_csp;
				$csp_desc=$row->csp_desc;
				$imp_inmob=$row->impuesto_inmob;
				$inmob_desc=$row->inmob_desc;
				$descArreglos=$row->descArreglos;
				$arreglos_desc=$row->arreglos_desc;
				$expExtras=$row->expExtras;
				$expextra_desc=$row->expextra_desc;
				$saldos_varios=$row->saldos_varios;
				$detalle_saldos=$row->detalle_saldos;
				$varios1=$row->varios1;
				$varios1_detalle=$row->varios1_detalle;
				$varios2=$row->varios2;
				$varios2_detalle=$row->varios2_detalle;				
				$observaciones=$row->observaciones;
				$totalPagar=$row->totalPagar;
				$fecha_liquidacion=$row->fecha;
				$liquido=$row->usuario;
			}	

			$valor_letra=num_to_letras($totalPagar);

			$this->db->select('periodo,mora_dias,rescision');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$periodo = $row->periodo;		
				$dias_mora=$row->mora_dias;	
				$rescision=$row->rescision;
			}

			if($dias_mora=="") $dias_mora="0";	

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);


			if($rescision==1){
				if($operacion=="ALQUILER"){
					$rescinde=" - RESCISIÓN DE CONTRATO";
				}else{
					$rescinde=" - RESCISIÓN DE COMODATO";
				}
			}else{
				$rescinde="";
			}

			$this->db->select('idInmueble,locador,locatario1,fechaInicio,fechaFin,fecha_firma,escribano,punitorio,comision_admin,comision_inmo_a_pagar,sellado_contrato,estado_contrato');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idI = $row->idInmueble;
				//$locador = $row->locador;
				$locatario1=$row->locatario1;
				$fechaIn=$row->fechaInicio;
				$fechaFin=$row->fechaFin;
				$fecha_F=$row->fecha_firma;
				$escribano=$row->escribano;		
				$mora_porc=$row->punitorio;
				$comi_admin_porc=$row->comision_admin;		
				$sellado_contrato=$row->sellado_contrato;
				$deuda_comision_total=$row->comision_inmo_a_pagar;
				$estado_contrato=$row->estado_contrato;
			}
			//number_format($comision_inmo_total/2,2,'.','');
			$deuda_comision_propietario=number_format($deuda_comision_total/2,2,'.','');
			/*setlocale(LC_TIME,"es_RA");
			date_default_timezone_set ("America/Argentina/Buenos_Aires");*/
			setlocale(LC_TIME, 'es_ES.UTF-8');
			$fecha_firma=date("d/m/Y", strtotime($fecha_F));

			//$fechaI=$this->buscar_datos_model->formato_fecha($fechaIn);
			//$fechaF=$this->buscar_datos_model->formato_fecha($fechaFin);
			$fechaI=date("m-Y", strtotime($fechaIn));
			$fechaF=date("m-Y", strtotime($fechaFin));

			$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
			$this->db->from('inmuebles');
			$this->db->where('idInmueble',$idI);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$dni_locador=$row->dni;
				$id_tipoinmueble=$row->idTipoInmueble;
				$idE=$row->idEdificio;
			}
			if(isset($idE)){
				$edificio=$this->buscar_datos_model->buscar_edificio($idE);
				$nombreE='-'.$edificio['edificio'];	
			}else $nombreE="";

			$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
			$tipoinmueble = $this->buscar_datos_model->buscar_tipoInmueble($id_tipoinmueble);


			$this->db->select('apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$locador);		
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$locador = $row->apellidoNombre;
			}

			$this->db->select('apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$locatario1);		
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$locatario1 = $row->apellidoNombre;
			}

			$valor_por=$mora_porc/100;	

			setlocale(LC_ALL,"es_RA");
			setlocale(LC_TIME,"es_RA");
			date_default_timezone_set ("America/Argentina/Buenos_Aires");

			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");			

			//$fecha_liquidacion=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;

			//$hora_liquidacion=strftime("%H:%M");


			$sesion= $this->session->userdata('usuario');
			$user=$sesion[1];
			
			$this->db->select('nombreUsuario');
			$this->db->from('usuarios');
			$this->db->where('NyA',$user);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$usuario=$row->nombreUsuario;
			}

			$hoy = $locador.'-'.$direccion.'-'.$periodo;

			$host=$_SERVER['SERVER_NAME'];

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			if($operacion=="ALQUILER"){
				$propietario="LOCADOR";
				$persona="LOCATARIO";
				$accion="alquiler";
				$contrato="Alquiler";	
			}else if($operacion=="COMERCIAL"){
				$propietario="LOCADOR";
				$persona="LOCATARIO";
				$accion="alquiler";
				$contrato="Comercial";				
			}else{
				$propietario="COMODANTE";
				$persona="COMODATARIO";
				$accion="comodato";
				$contrato="Comodato";
				$escribano=" -";
			}	

			if($estado_contrato=="RESCINDIDO"){
				$periodo="";
			}		

        	//load the view and saved it into $html variable
        	$html = 
        	"<style>@page {        		
			    margin-top: 1cm;
			    margin-bottom: 1cm;
			    margin-left: 1.27cm;
			    margin-right: 1.27cm;
			}
			hr {
		 		 width: 100%;
		 		 color:black;
			}
			</style>";

       		 $datos="<body>
       		 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
       		 	<tr>
       		 		<td align='center'  valign='bottom'><b style='font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:20px'>Liquidación a Propietarios</b> - <b style='font-size:12px'>Liquid. nro: $nro_liq</b></td> 
       		 		<td align='right'><img src='http://$host/SGI/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		<tr>
        		<tr>
	        		<td align='center' colspan='2' style='height:20px;vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:14px'>Carlos Pellegrini 1557, Taragüi V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>	        		
	        		</td>	        		
        		</tr>        		
        	</table>
        	<br>
        			
        		<table width='100%' border='0' cellpadding='0' cellspacing='0' style='font-size:14px'>
        			<tr >        				
        				<td style='height:50px;vertical-align:text-top'><u><b>RECIBO</b></u>:  de cobro de $accion, correspondiente al <u>período</u>: <b>$periodo</b> - <u>Inmueble</u>: <b>$direccion $nombreE</b> - <u>$contrato</u>:$fecha_firma - <u>Período Locativo</u>: $fechaI a $fechaF - <u>Escribano</u>: $escribano 
        				</td>
        			</tr>
        		</table>
        		<table border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
        				<td ><u><b>$propietario</b></u>:</td>
        				<td > $locador </td>
        			</tr>
        			<tr>
        				<td style='height:25px'><u><b>$persona</b></u>:</td>
        				<td style='height:25px'> $locatario1 </td>
        			</tr> 
        		</table>

        		<br>

        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
        				<td style='width:20%;font-size:12px'><b>CONCEPTO</b></td>
        				<td style='width:70%;font-size:12px' align='left'><b>DESCRIPCIÓN</b></td>
        				<td style='width:10%;font-size:12px' align='right'><b>VALORES</b></td>
        			</tr>
        			<tr>	
        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> $contrato</td>
        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> Período: $periodo $rescinde </td>
        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $alquiler.00 </td>
        			</tr>";
        			$datos_html=$html.$datos;
        			if($punitorios<>0){        				
		        		$datos_html.=$punit="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Punitorios</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> $mora_porc % diario x $dias_mora días de mora, ($valor_por x $alquiler x $dias_mora) </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $punitorios </td>
	        			</tr>"; 
	        		}
	        			
	        		if($comiAdmin<>0){	        			
	        			$datos_html.=$comision="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Comisión por Admin.</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$comi_admin_porc% de $alquiler </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $comiAdmin.00 </td>
	        			</tr>";
        			}

	        		if($comision_inmo_paga<>0){	        			
	        			$datos_html.=$comision_paga="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Comisión Inmob.</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>Deuda Original: $deuda_comision_propietario - Saldo Deudor: $comision_inmo_debe</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $comision_inmo_paga</td>
	        			</tr>";
        			}


	        		if($sellado<>0){	        			
	        			$datos_html.=$comision="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Gastos Escribanía</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> Sellado de contrato, 50% de $sellado_contrato </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $sellado </td>
	        			</tr>";
        			}

	        		if($firma<>0){	        			
	        			$datos_html.=$comision="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Gastos Escriabnía</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> Certificación de firmas </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $firma </td>
	        			</tr>";
        			}

	        		if($expensas<>0){	        			
	        			$datos_html.=$csp="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Expensas</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$expensas_detalle </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$expensas </td>
	        			</tr>";
        			}    

	        		if($agua<>0){	        			
	        			$datos_html.=$csp="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Agua</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$agua_detalle </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$agua </td>
	        			</tr>";
        			}         			    			         			        			

	        		if($imp_csp<>0){	        			
	        			$datos_html.=$csp="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Impuestos CSP</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$csp_desc </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$imp_csp </td>
	        			</tr>";
        			}

	        		if($imp_inmob<>0){	        			
	        			$datos_html.=$inmo=        			
	        			"<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Impuesto Inmobiliario</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> $inmob_desc </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $imp_inmob </td>
	        			</tr>";
        			}

	        		if($descArreglos<>0){	        			
	        			$datos_html.=$arreglos="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Reparaciones</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> $arreglos_desc </td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$descArreglos</td>
	        			</tr>";
	        		}

	        		if($expExtras<>0){	        			
	        			$datos_html.=$extra="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Exp.Extraordinarias</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$expextra_desc</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$expExtras </td>
	        			</tr>";
        			}

	        		if($saldos_varios<>0){	        			
	        			$datos_html.=$saldos="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Otros</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$detalle_saldos</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$saldos_varios </td>
	        			</tr>";
        			}

	        		if($varios1<>0){	        			
	        			$datos_html.=$varios1_temp="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Varios1</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$varios1_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$varios1 </td>
	        			</tr>";
        			}  

	        		if($varios2<>0){	        			
	        			$datos_html.=$varios2_temp="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Varios2</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$varios2_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$varios2 </td>
	        			</tr>";
        			}         			      			

	        		if($observaciones<>""){	        			
	        			$datos_html.=$observac="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Observaciones</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$observaciones</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'></td>
	        			</tr>";
        			}        			

        			$datos_html.=$fin="<tr>        		    	
        				<td colspan='2' style='height:20px;width:auto' align='right'><b style='font-size:16px'> Importe neto a recibir: $</b></td>
        				<td style='height:20px;width:auto' align='right'><b style='font-size:16px'>$totalPagar</b></td>
        			</tr>        			
        			<tr>       				       				
        				<td colspan='3'  style='font-size:12'>
        				Son pesos: <b>$valor_letra</b>
        				</td>        				
        			</tr>
        			<tr>
        				<td colspan='3' style='height:40px;vertical-align:text-top' >Liquidado el : $fecha_liquidacion  por $liquido</td>
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
        		<br>     			
        	</body>";

        	// $html = $this->load->view('v_dpdf',$date,true);
 		
 			//$html="asdf";
        	//this the the PDF filename that user will get to download
        	$pdfFilePath = $hoy.".pdf";
 
        	//load mPDF library
        	$this->load->library('M_pdf');
       	 	$mpdf = new mPDF('c', 'A4-P'); 
 			$mpdf->WriteHTML($datos_html);
 			$mpdf->WriteHTML($datos_html);
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

		$this->db->select('idInmueble,locatario1,locador');
		$this->db->where('idContrato',$id);
		$this->db->from('alquileres');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$locatario1=$row->locatario1;
			$dniL=$row->locador;
		}

		$direccion=$this->buscar_datos_model->buscar_inmueble($idI);

		$this->db->select('apellidoNombre');
		$this->db->where('dni',$dniL);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locador = $row->apellidoNombre;
		}	


		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locatario1);
		$this->db->from('personas');
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$locatario1 = $row->apellidoNombre;
		}	

		$operacion=$this->buscar_datos_model->tipo_operacion($id);
		if($operacion=="ALQUILER"){
			$propietario="Locador";
			$persona="Locatario";
			$accion="Alquiler";
		}else if($operacion=="COMERCIAL"){
			$propietario="Locador";
			$persona="Locatario";
			$accion="Comercial";			
		}else{
			$propietario="Comodante";
			$persona="Comodatario";
			$accion="Comodato";
		}					

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 
		$output = "<div class='texto' style='display:none;' >- <b id='operacion' style='font-weight: normal;color:white'>$accion</b>: $direccion<b style='font-weight: normal;color:white'> - $propietario:</b> $locador <b style='font-weight: normal;color:white'> - $persona:</b> $locatario1</div>".$output->output;

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
			$crud->order_by('nro_pago','asc');
			$crud->unset_operations();

			$crud->columns('nro_pago','periodo','fecha_pago','valor_alquiler','punitorios','expensas','csp','impuesto_inmob','luz','agua','exp_extra','saldos_otros');				

			$crud->display_as('nro_pago','#');
			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('periodo','Periodo');
			$crud->display_as('fecha_pago','Pagado');
			$crud->display_as('valor_alquiler','Alquiler');
			$crud->display_as('punitorios','Punit.');
			$crud->display_as('impuesto_inmob','I-I');
			$crud->display_as('mora_dias','Mora a la fecha');
			$crud->display_as('paga_mora','¿Paga mora?');
			$crud->display_as('saldos_otros','Otros Gastos');
			$crud->display_as('total_pagar','Total');
			$crud->display_as('exp_extra','Exp-Extra');
			
			
			$crud->display_as('fechaUltimoPago','Ultimo Pago, Periodo');
			$crud->display_as('pagado_propietario','¿Pagado a Propietario?');

			$crud->callback_column('periodo',array($this,'periodo_column'));

			$crud->add_action('Liquidar', '', 'Liquidacion/liquidar/add','ui-icon-calculator');

			//$crud->set_crud_url_path(site_url('Liquidacion/pagos/'));

			$output = $crud->render();	
			//$this->_example_output($output);
			if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}
	}

	public function periodo_column($value,$row){
		$rescinde= $row->rescision;
		if($rescinde ==1){
			return '<b style="color:red">RESCISION</b>';
		}else{
			return $value;
		}
	}


	public function buscar_periodo($value,$row){
		$idP=$row->idpago;	
		$periodo=$this->buscar_datos_model->buscar_periodo($idP);
		return $periodo;		
	}


////////////aca////////////////
	public function liquidar(){	

		$output = $this->liquidar_management();	
		
		$idC=$this->uri->segment(3);//Liquidacion/liquidar/38
		if(is_numeric($idC)){			
			$this->db->select('idInmueble,locatario1,locador');
			$this->db->where('idContrato',$idC);
			$this->db->from('alquileres');
			$query=$this->db->get();
			foreach ($query->result() as $row) {
				$idI=$row->idInmueble;
				$locatario_dni=$row->locatario1;
				$locador_dni=$row->locador;
			}
			
			$direccion = $this->buscar_datos_model->buscar_inmueble($idI);
			$locador=$this->buscar_datos_model->buscar_persona($locador_dni);
			$locatario1=$this->buscar_datos_model->buscar_persona($locatario_dni);

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);
			if($operacion=="ALQUILER"){
				$propietario="Locador";
				$persona="Locatario";
				$accion="Alquiler";
			}else if( $operacion=="COMERCIAL"){
				$propietario="Locador";
				$persona="Locatario";
				$accion="Comercial";				
			}else{
				$propietario="Comodante";
				$persona="Comodatario";
				$accion="Comodato";
			}						

			$js_files =$output->js_files; 
			$css_files =  $output->css_files; 
			$output = "<div class='texto' style='display:none;' >- <b id='operacion' style='font-weight: normal;color:white' >$accion</b>: $direccion<b style='font-weight: normal;color:white'> - $propietario:</b> $locador <b style='font-weight: normal;color:white'> - $persona:</b> $locatario1</div><div class='dni'  style='display:none;'>$locador_dni</div>".$output->output;

			$this->_example_output((object)array(
					'js_files' => $js_files,
					'css_files' => $css_files,
					'output'	=> $output
			));

		}
	}
	public function liquidar_management(){
		/*$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);*/
		$crud=new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('liquidaciones');	
		$crud->set_subject('Liquidacion');

		$crud->set_relation('idpago','pagos','periodo');

		$crud->set_relation('idContrato','alquileres','idContrato');
		$crud->set_relation('locador','personas','apellidoNombre');
		$crud->set_relation('locatario1','personas','apellidoNombre');

		
		//$crud->unset_operations();
		$crud->unset_print();
		$crud->unset_export();
		

		$idC=$this->uri->segment(3);
		$crud->where('liquidaciones.idContrato',$idC);
		$crud->order_by('idLiquida','desc');

		$crud->columns('nro_liq','idpago','alquiler','punitorios','comiAdmin','expensas','agua','impuesto_csp','impuesto_inmob','descArreglos','expExtras','totalPagar');

		/*$crud->fields('nro_liq','idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','sellado_paga','certi_firma','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','totalPagar','usuario','fecha');*/

		$crud->display_as('nro_liq','#');

			$idP=$this->uri->segment(4);			
			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idC = $row->idContrato;															
			}

		$operacion=$this->buscar_datos_model->tipo_operacion($idC);

		if($operacion=="ALQUILER" || $operacion=="COMERCIAL"){
			$crud->display_as('idContrato','Alquiler');	
		}elseif($operacion=="COMODATO"){
			$crud->display_as('idContrato','Comodato');	
			$crud->display_as('locatario1','Comodatario');	
			$crud->display_as('locador','Comodante');	
		}


		$crud->display_as('idpago','Periodo');		
		$crud->display_as('comiAdmin','Com-Admin');
		$crud->display_as('sellado_paga','Sellado Contrato Propietario');
		$crud->display_as('certi_firma','Certificación Firma');
		$crud->display_as('punitorios','Punit.');
		$crud->display_as('expensas','Exp.');


		$crud->display_as('ci_a_pagar','CI Debe');
		$crud->display_as('comision_inmo_paga','CI Paga, ¿Cuánto?');
		$crud->display_as('comision_inmo_debe','CI Deuda Actualizada');		

		//$crud->display_as('comodin','<b><u>Descuentos al Propietario</b></u>');
		//$crud->display_as('comodin2','<b><u>Total a Pagar al Propietario</b></u>');
		$crud->display_as('impuesto_csp','CSP');
		$crud->display_as('impuesto_inmob','I-I');
		$crud->display_as('descArreglos','Reparaciones.');
		$crud->display_as('expExtras','Exp.Extaord.');
		$crud->display_as('saldos_varios','Otros');
		$crud->display_as('totalPagar','A Pagar');
		
		$crud->display_as('inmob_desc','Imp-Inmob detalle');			

		//$crud->field_type('periodo','invisible');
		$crud->field_type('nro_liq','invisible');
		$crud->field_type('fecha','invisible');
		$crud->field_type('usuario','invisible');
		$crud->field_type('csp_desc','invisible');		
		$crud->field_type('expensas_detalle','invisible');		
		$crud->field_type('inmob_desc','invisible');
		$crud->field_type('arreglos_desc','invisible');
		$crud->field_type('expextra_desc','invisible');
		$crud->field_type('detalle_saldos','invisible');
		$crud->field_type('agua_detalle','invisible');
		$crud->field_type('varios1_detalle','invisible');
		$crud->field_type('varios2_detalle','invisible');

		$crud->required_fields('alquiler','totalPagar');

		$crud->add_action('Imprimir', '', 'Liquidacion/descargar_pdf','ui-icon-print');
		$crud->add_action('Ver', '', 'Liquidacion/liquidar_read/read','ui-icon-document');
		$crud->add_action('Editar', '', 'Liquidacion/liquidar_edit/edit','ui-icon-pencil');
		//$crud->add_action('Eliminar', '', 'Liquidacion/eliminar_liquidacion','ui-icon-circle-minus'); // ESTE ANDA, ESTA OCULTO NOMAS

		//$crud->callback_column('idpago',array($this,'buscar_periodo'));


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

			$direccion=$this->buscar_datos_model->buscar_inmueble($idI);
			$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
			$this->db->from('inmuebles');
			$this->db->where('idInmueble',$idI);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$dni_locador=$row->dni;
				$id_tipoinmueble=$row->idTipoInmueble;
				$idE=$row->idEdificio;
			}

			if(isset($idE)){
				$edificio=$this->buscar_datos_model->buscar_edificio($idE);	
				$Nedificio=$edificio['edificio'];
					$nombreE='&nbsp; Edificio:<span style="color:blue; font-size:17px">'.$Nedificio.'</span>';
			}else{
				$nombreE="";
			}

			$tipo_inmueble = $this->buscar_datos_model->buscar_tipoInmueble($id_tipoinmueble);

			$combo = '<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto"><option value = "'.$idC.'">'.strtoupper($direccion).'</option></select>'.$nombreE;
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

		$crud->callback_add_field('locatario1', function () {
			$idP=$this->uri->segment(4);
			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idC = $row->idContrato;															
			}

			$this->db->select('locatario1');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idL = $row->locatario1;															
			}

			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$idL);							
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$dni = $row->dni;															
				$locatario1 = $row->apellidoNombre;
			}
			return '<select id="field-locatario1" class="form-control"  name="locatario1"><option value="'.$dni.'">'.$locatario1.'</option></select>';	
		});//fin callback_add_field		



		$crud->callback_add_field('idpago', function () {
			$idP=$this->uri->segment(4);
			$this->db->select('periodo,rescision');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$periodo = $row->periodo;	
				$rescision=$row->rescision;														
			}
			if($rescision==1){
				$mes_rescision="&nbsp;&nbsp;<b style='color:red'>MES DE RESCISIÓN</b>";
			}else{
				$mes_rescision="";
			}
			return '<select id="field-idpago" class="chosen-select"  name="idpago"><option value="'.$idP.'">'.$periodo.'</option></select>'.$mes_rescision;
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

			$valor_alquiler = '<input id="field-alquiler" name="alquiler" type="text" value="'.$valor.'" maxlength="10" style="width:100px;height:30px" readonly />';						
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
			$input_punitorios = '<input id="field-punitorios" name="punitorios" type="text" value="'.$punitorios.'" maxlength="10" style="width:100px;height:30px" onkeyup="calcular_alquiler()"  onblur ="input_ceros(this.id)"/>';	
			$subtotal=' <b>Subtotal: </b>'.'<span id="b">'.($punitorios + $valor);						
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
				$valor_alquiler = $row->valor_alquiler;							
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
			$comision=round(($valor_alquiler + $punitorios) * ($comi_porc / 100))*(-1);
			$input_comi_admin = '<input id="field-comiAdmin" name="comiAdmin" type="text" value="'.$comision.'" maxlength="10" style="width:100px;height:30px" onfocus="calcular_comision()" readonly/>';			
			$comi_admin= '<span id="comision">'.$comi_porc.'</span> % ';
			return $input_comi_admin.$comi_admin;
		}); //cierro callback_add_field	

		//verifico si la liquidacion corresponde al primer pago, para liquidar, sellados de contrato y certificacion de firma si correspondiere, agergando por unica vez los campos sellado_paga y certi_firma

		$idP=$this->uri->segment(4);
		
		$nro_pago=$this->buscar_datos_model->nro_de_pago($idP);

		//$idC=$this->buscar_datos_model->buscar_idC_P($idP);
		$comision_inmo_debe=$this->buscar_datos_model->debe_comision_inmo($idC);

		/*$idC=$this->uri->segment(3);//Liquidacion/pagos/38
		$nro_pago=$this->buscar_datos_model->nro_de_pago_idC($idC);*/
		

		//$nro_pago=1;	

		if($nro_pago==1){

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			$crud->fields('nro_liq','idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','ci_a_pagar','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','totalPagar','usuario','fecha');

		}else if($comision_inmo_debe>0){

			$crud->fields('nro_liq','idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','ci_a_pagar','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','totalPagar','usuario','fecha');

				$crud->field_type('sellado_paga','invisible');
				$crud->field_type('certi_firma','invisible');	
		}else{
				$crud->fields('nro_liq','idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','ci_a_pagar','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','observaciones','totalPagar','usuario','fecha');

				$crud->field_type('comision_inmo_paga','invisible');
				$crud->field_type('comision_inmo_debe','invisible');
				$crud->field_type('sellado_paga','invisible');
				$crud->field_type('certi_firma','invisible');
				$crud->field_type('ci_a_pagar','invisible');			
		}//fin si		

				$crud->callback_add_field('sellado_paga', function () {	
					$idP=$this->uri->segment(4);
					$nro_pago=$this->buscar_datos_model->nro_de_pago($idP);		

					$idC=$this->buscar_datos_model->buscar_idC_P($idP);
				
					$sellado_contrato=$this->buscar_datos_model->sellado_contrato($idC);

					$sellado_propietario=round($sellado_contrato/2,2);

					//$sellado_propietario_d=number_format($sellado_propietario, 2, '.', '' );


					$sellado_paga = '</span><input id="field-sellado_paga" name="sellado_paga" type="text" value="'.-$sellado_propietario.'" maxlength="8" style="width:100px;height:30px" readonly/>'.'<span id="nro_pago" style="visibility:hidden">'.$nro_pago.'</span>'.'&nbsp50% de <span id="sellado_contrato" style="font-size:16px">'.$sellado_contrato.'</span>';	
					return $sellado_paga;
				});//cierro callback_add_field	

				$crud->callback_add_field('certi_firma', function () {			
					$certi_firma = '</span><input id="field-certi_firma" name="certi_firma" type="text" value="0" maxlength="8" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)"/>';
					return $certi_firma;
				});//cierro callback_add_field					

			$crud->callback_add_field('ci_a_pagar', function () {

				$idP=$this->uri->segment(4);
				$idC=$this->buscar_datos_model->buscar_idC_P($idP);

				$nro_pago=$this->buscar_datos_model->nro_de_pago($idP);

				$comision_inmo_debe=$this->buscar_datos_model->debe_comision_inmo($idC);

				/*$datos=$this->buscar_datos_model->n_pagos($idC);

				$ultimo_pago_CI=$this->buscar_datos_model->ultimo_pago_ci($idC);

				$idP=$datos['idpago'];*/

				$deuda_ci=$this->buscar_datos_model->comision_inmo_deuda($idC);	

				$comision_propietario=$deuda_ci/2;

				$CI_propietario=number_format($comision_propietario,2,'.','');			

				if($nro_pago==1){
					$ci_a_pagar='<input id="field-ci_a_pagar" name="ci_a_pagar" type="text" value="'.$CI_propietario.'" style="width:100px;height:30px" class="numerico"/disabled>'.' de '.$CI_propietario.' = 50% de '.$deuda_ci;	
				}else if($comision_inmo_debe>0){
					$ci_a_pagar='<input id="field-ci_a_pagar" name="ci_a_pagar" type="text" value="'.$comision_inmo_debe.'" style="width:100px;height:30px" class="numerico"/disabled>'.' de '.$CI_propietario.' = 50% de '.$deuda_ci;				
				}else{
					$ci_a_pagar="";
				}			

				
				return $ci_a_pagar;	
			});	

			$crud->callback_add_field('comision_inmo_paga', function () {	
				$comision_paga = '<input id="field-comision_inmo_paga" name="comision_inmo_paga" value="0" type="text" onblur ="input_ceros(this.id)" onclick="vaciar(this.id)" onkeyup="validar_comision()"  maxlength="10" style="width:100px;height:30px"/>';	

					$paga_todo='<input id="paga_todo_ci" type="button" value="Paga todo" onclick="pagatodo_ci()"/>';

				return $comision_paga.$paga_todo.' <b>Ingresar con signo negativo, el punto (<b>.</b>) es la coma decimal</b>';
			});

			$crud->callback_add_field('comision_inmo_debe', function () {
				/*$idC=$this->uri->segment(4);
				$datos=$this->buscar_datos_model->n_pagos($idC);

				$idP=$datos['idpago'];

				$deuda_ci=$this->buscar_datos_model->deuda_ci($idP);*/

				$comision_debe = '<input id="field-comision_inmo_debe" name="comision_inmo_debe" value="" type="text" maxlength="10" value=""style="width:100px;height:30px" class="numerico" readonly/>';	

				return $comision_debe;
			});	



		$crud->callback_add_field('expensas', function () {
			//$idP=$this->uri->segment(4);
			//$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);

			$expensas = '<input id="field-expensas" name="expensas" type="text" value="0" maxlength="8" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)" onkeypress="return validateFloatKeyPress(this,event);"/>';
			$textarea='<textarea id="field-expensas_detalle" name="expensas_detalle" style="width:400px;height:60px"></textarea>';
			return $expensas.$textarea;
		});//cierro callback_add_field	

		$crud->callback_add_field('agua', function () {
			$idP=$this->uri->segment(4);
			$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);

			$agua = '<input id="field-agua" name="agua" type="text" value="'.$impuestos_inquilino['agua'].'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>';
			$textarea='<textarea id="field-agua_detalle" name="agua_detalle" style="width:400px;height:60px">'.$impuestos_inquilino['agua_detalle'].'</textarea>';
			return $agua.$textarea;
		});//cierro callback_add_field	


		$crud->callback_add_field('impuesto_csp', function () {	
			$idP=$this->uri->segment(4);
			$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);

			$csp = '<input id="field-impuesto_csp" name="impuesto_csp" type="text" value="'.$impuestos_inquilino['csp'].'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)"/>';
			$textarea='<textarea name="csp_desc_temp" style="width:400px;height:60px">'.$impuestos_inquilino['csp_detalle'].'</textarea>';
			return $csp.$textarea;
		});//cierro callback_add_field		

		$crud->callback_add_field('impuesto_inmob', function () {	
			$idP=$this->uri->segment(4);
			$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);

			$inmob = '<input id="field-impuesto_inmob" name="impuesto_inmob" type="text" value="'.$impuestos_inquilino['impuesto_inmob'].'" maxlength="8" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event)"/>';
			$textarea='<textarea name="inmo_desc_temp" style="width:400px;height:60px">'.$impuestos_inquilino['inmob_desc'].'</textarea>';
			return $inmob.$textarea;
		});//cierro callback_add_field			

		$crud->callback_add_field('descArreglos', function () {		
			$idP=$this->uri->segment(4);	
			$idC=$this->buscar_datos_model->buscar_idC_P($idP);

			$reclamos=$this->buscar_datos_model->datos_reclamos($idC);

			if(isset($reclamos)){
				if((($reclamos['quien_paga']=='INMOBILIARIA' or $reclamos['quien_paga']=='INQUILINO')) && $reclamos['liquidado']=='NO'){
					$dinero_dado=$reclamos['dinero_dado'];
					$descripcion=$reclamos['descripcion'];
					$idR=$reclamos['idR'];
					$tecnico=' - Sr: '.$reclamos['tecnico'];
					$fecha=$reclamos['fecha'];
					$pago=$reclamos['quien_paga'];

				}else{
					$dinero_dado="";
					$descripcion="";
					$idR="";
					$tecnico="";
					$fecha="";
					$pago="";
				}
				$desc_arreglos = '<input id="field-descArreglos" name="descArreglos" type="text" value="'.-$dinero_dado.'" maxlength="8" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)" onkeypress="return validateFloatKeyPress(this,event)"/>';
					$textarea='<textarea name="arreglo_desc_temp" style="width:400px;height:60px">'.$descripcion.$tecnico.'</textarea>'.'Reclamo:'.$idR.' - Fecha:'.$fecha.' - Pagado por:'.$pago;	
			}
			return $desc_arreglos.$textarea;
		});//cierro callback_add_field		

		$crud->callback_add_field('expExtras', function () {
			$idP=$this->uri->segment(4);
			$idC=$this->buscar_datos_model->buscar_idC_P($idP);
			$impuestos=$this->buscar_datos_model->anterior_impuestos($idC);			
			$exp_extras = '<input id="field-expExtras" name="expExtras" type="text" value="'.$impuestos['exp_extra'].'" maxlength="8" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)" onkeypress="return validateFloatKeyPress(this,event)"/>';
			$textarea='<textarea name="extra_desc_temp" style="width:400px;height:60px">'.$impuestos['exp_extra_detalle'].'</textarea>';
			return $exp_extras.$textarea;			
		});//cierro callback_add_field	

		$crud->callback_add_field('saldos_varios', function () {
			$idP=$this->uri->segment(4);
			$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);	
							
			$saldos = '<input id="field-saldos_varios" name="saldos_varios" type="text" value="'.$impuestos_inquilino['saldos_otros'].'" maxlength="10" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)" onkeypress="return validateFloatKeyPress(this,event)" />';			
			$textarea='<textarea name="detalle_saldos_temp" style="width:400px;height:60px">'.$impuestos_inquilino['otros_detalle'].'</textarea>';
			return $saldos.$textarea;				
		});//cierro callback_add_field	


		$crud->callback_add_field('varios1', function () {
			$idP=$this->uri->segment(4);
			$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);	
							
			$varios1 = '<input id="field-varios1" name="varios1" type="text" value="'.$impuestos_inquilino['varios1'].'" maxlength="10" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)" onkeypress="return validateFloatKeyPress(this,event)" />';			
			$textarea='<textarea name="varios1_detalle_temp" style="width:400px;height:60px">'.$impuestos_inquilino['varios1_detalle'].'</textarea>';
			return $varios1.$textarea;				
		});//cierro callback_add_field	

		$crud->callback_add_field('varios2', function () {
			$idP=$this->uri->segment(4);
			$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);	
							
			$varios2 = '<input id="field-varios2" name="varios2" type="text" value="'.$impuestos_inquilino['varios2'].'" maxlength="10" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)" onkeypress="return validateFloatKeyPress(this,event)" />';			
			$textarea='<textarea name="varios2_detalle_temp" style="width:400px;height:60px">'.$impuestos_inquilino['varios2_detalle'].'</textarea>';
			return $varios2.$textarea;				
		});//cierro callback_add_field			

				
		$crud->callback_add_field('observaciones', function () {
			$idP=$this->uri->segment(4);
			$impuestos_inquilino=$this->buscar_datos_model->impuestos_inquilino($idP);						
			
			$textarea='<textarea name="observaciones" style="width:400px;height:60px"></textarea>';
			return $textarea;				
		});//cierro callback_add_field	


		$crud->callback_add_field('totalPagar', function () {			
			$total = '<input id="field-totalPagar" name="totalPagar" type="text" value=" " maxlength="10" style="width:110px;height:40px" style="font-weight:bold" class="numerico"/>';

		$boton_calcular='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="calcular" value="CALCULAR" class="ui-input-button">';

		$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

			return $total.$boton_calcular.$boton_limpiar;
		});//cierro callback_add_field*/		
		

		//$crud->callback_before_insert(array($this,'set_periodo'));

		$crud->callback_after_insert(array($this,'update_pendiente'));

		$crud->callback_before_insert(array($this,'fechaCreacion_Usuario'));	

		//$crud->callback_after_update(array($this,'update_pendiente_update'));
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();		
		
		if($state == 'success') {
			$idL=$this->uri->segment(4);
			$this->db->select('idContrato');
			$this->db->from('liquidaciones');
			$this->db->where('idLiquida',$idL);
			$query = $this->db->get();
			foreach ($query->result() as $row){
				$idC=$row->idContrato;
			}				
				redirect('Liquidacion/liquidar/' . $idC);
		}	

		$output = $crud->render();

		if($crud->getState() != 'list') {
			$this->_example_output($output);
		} else {
			return $output;
		}	





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


	/*public function buscar_periodo($value,$row){
		$idP=$row->idpago;		
		$this->db->select('periodo');
		$this->db->from('pagos');		
		$this->db->where('idpago',$idP);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$periodo = $row->periodo;
		}
		return $periodo;		
	}	*/

		public function fechaCreacion_Usuario($post_array){				
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$post_array['fecha']=date('d/m/Y G:i');

			$sesion= $this->session->userdata('usuario');
			$usuario=$sesion[1];			
			$post_array['usuario']=$usuario;
			$post_array['csp_desc']=$post_array['csp_desc_temp'];
			$post_array['inmob_desc']=$post_array['inmo_desc_temp'];
			$post_array['arreglos_desc']=$post_array['arreglo_desc_temp'];
			$post_array['expextra_desc']=$post_array['extra_desc_temp'];
			$post_array['detalle_saldos']=$post_array['detalle_saldos_temp'];
			$post_array['varios1_detalle']=$post_array['varios1_detalle_temp'];
			$post_array['varios2_detalle']=$post_array['varios2_detalle_temp'];			
			$idC=$post_array['idContrato'];			

			$this->db->select('nro_liq');
			$this->db->from('liquidaciones');
			$this->db->where('idContrato',$idC);
			$query=$this->db->get();
			foreach ($query->result() as $row) {
				$nro_liq=$row->nro_liq;
			}
			$post_array['nro_liq']=$nro_liq + 1;

			$cant_liquid=$this->buscar_datos_model->cantidad_liquidaciones($idC);
			if($cant_liquid ==23){
				$this->db->set('pendientes',"NO");				
				$this->db->where('idContrato',$idC);
				$this->db->update('alquileres');	
			}	
			return $post_array;
		}

	public function update_pendiente($post_array){
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

		$this->db->set('liquidado',"SI");				
		$this->db->where('idContrato',$idC);
		$this->db->update('reclamos');		

		//si es la ultima liquidacion de un contrato finalizado, debo eliminar el alquiler	
		/*$cant_pagos=$this->buscar_datos_model->cant_total_pagos($idC);
		if($cant_pagos == "SI"){
			$this->buscar_datos_model->eliminar_alquiler($idC);
		}	*/	
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

	public function liquidar_read(){
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('liquidaciones');	
		$crud->set_subject('Liquidaciones');	

		$crud->display_as('idContrato','Inmueble');
		$crud->display_as('idpago','Período');		
		$crud->display_as('totalPagar','Pagado a Propietario');
		$crud->display_as('sellado_paga','Sellado Contrato');
		$crud->display_as('certi_firma','Certificación Firma');


		$crud->display_as('impuesto_csp','CSP');
		$crud->display_as('csp_desc','CSP detalle');	
		$crud->display_as('impuesto_inmob','Impuesto Inmob.');	
		$crud->display_as('inmob_desc','I-I detalle');
		$crud->display_as('descArreglos','Arreglos');	
		$crud->display_as('arreglos_desc','Arreglos detalle');
		$crud->display_as('expExtras','Expensas Extraordinarias');
		$crud->display_as('expextra_desc','Extraordinarias detalle');	

		$idL=$this->uri->segment(4);
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

		$crud->callback_read_field('idContrato', function ($value, $primary_key) {
			$idC=$value;	
			$idI=$this->buscar_datos_model->buscar_idI($idC);
			$inmueble=$this->buscar_datos_model->buscar_inmueble($idI);
			$idE=$this->buscar_datos_model->buscar_idE($idI);
			if(isset($idE)){
				$edificio = $this->buscar_datos_model->buscar_edificio($idE);
				$nombreE=$edificio['edificio'];
				return $inmueble=$inmueble.' &nbsp;Edificio:'.$nombreE;
			}else{
				return $inmueble;
			}	
		});	

		$crud->callback_read_field('locador', function ($value, $primary_key) {
			$locador=$this->buscar_datos_model->buscar_persona($value);
			return $locador;
		});

		$crud->callback_read_field('locatario1', function ($value, $primary_key) {
			$locatario1=$this->buscar_datos_model->buscar_persona($value);
			return $locatario1;
		});

		$crud->callback_read_field('idpago', function ($value, $primary_key) {
			$periodo=$this->buscar_datos_model->buscar_periodo($value);
			return $periodo;
		});

		$output = $crud->render();	
		$this->_example_output($output);
	}

	public function liquidar_edit(){
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('liquidaciones');	
		$crud->set_subject('Liquidaciones');

		$crud->set_relation('idpago','pagos','periodo');

		$crud->set_relation('idContrato','alquileres','idContrato');
		$crud->set_relation('locador','personas','apellidoNombre');
		$crud->set_relation('locatario1','personas','apellidoNombre');
		

		/*$idC=$this->uri->segment(3);
		$crud->where('liquidaciones.idContrato',$idC);*/

		//$crud->columns('idpago','alquiler','punitorios','comiAdmin','descArreglos','expExtras','totalPagar');

		/*$crud->edit_fields('idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','comisionn_inmo_debe','comision_inmo_paga','sellado_paga','certi_firma','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','totalPagar','fecha_update','usuario_update');*/
		$crud->display_as('idContrato','Inmueble');	
		$crud->display_as('idpago','Periodo');		
		$crud->display_as('comiAdmin','Comisión x Admin');
		//$crud->display_as('comodin','<b><u>Descuentos al Propietario</b></u>');
		//$crud->display_as('comodin2','<b><u>Total a Pagar al Propietario</b></u>');
		$crud->display_as('impuesto_csp','CSP');
		$crud->display_as('csp_desc','CSP Detalle');		
		$crud->display_as('impuesto_inmob','Imp-Inmob.');
		$crud->display_as('inmob_desc','Imp-Inmob. Detalle');
		$crud->display_as('impuesto_inmob','Impuesto Inmob.');	
		$crud->display_as('inmob_desc','I-I detalle');		
		$crud->display_as('descArreglos','Reparaciones');
		$crud->display_as('arreglos_desc','Reparaciones Detalle');
		$crud->display_as('expExtras','Expensas Extra.');
		$crud->display_as('expextra_desc','Expensas Extra. Detalle');
		$crud->display_as('saldos_varios','Otros');
		$crud->display_as('detalle_saldos','Otros Saldos Detalle');
		$crud->display_as('totalPagar','Monto a Pagar');

		$crud->field_type('fecha_update','invisible');
		$crud->field_type('usuario_update','invisible');
		$crud->field_type('nro_liq','invisible');
		$crud->field_type('fecha','invisible');
		$crud->field_type('usuario','invisible');

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
			$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
			$this->db->from('inmuebles');
			$this->db->where('idInmueble',$idI);		
			$query=$this->db->get();								
			foreach ($query->result() as $row){				
				$dni_locador=$row->dni;				
				$idE=$row->idEdificio;
			}
			if(isset($idE)){
				$edificio=$this->buscar_datos_model->buscar_edificio($idE);
				$nombreE=$edificio['edificio'];	
			}else $nombreE="-";

			$direccion=$this->buscar_datos_model->buscar_inmueble($idI);		

			$combo = '<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto"><option value = "'.$value.'">'.strtoupper($direccion).'</option></select> Edificio: '.$nombreE;
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

		$crud->callback_edit_field('locatario1', function ($value,$id) {
			$this->db->select('dni,apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$value);							
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$dni = $row->dni;															
				$locatario1 = $row->apellidoNombre;
			}
			return '<select id="field-locatario1" class="form-control"  name="locatario1"><option value="'.$dni.'">'.$locatario1.'</option></select>';	
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
			$valor_alquiler = '<input id="field-alquiler" name="alquiler" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" readonly/>';						
			return $valor_alquiler;
		});//cierro callback_edit_field

		$crud->callback_edit_field('punitorios', function ($value,$id) {
			$input_punitorios = '<input id="field-punitorios" name="punitorios" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" readonly/>';									
			return $input_punitorios;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('comiAdmin', function ($value,$id) {			
			$input_comi_admin = '<input id="field-comiAdmin" name="comiAdmin" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px"/> ';			
			return $input_comi_admin;
		});//cierro callback_edit_field

//SI ES LA PRIMERA LIQUIDACION, DONDE SE LIQUIDA SELLADO, CERTIFICACION DE FIRMAS, A SOLO INFORMATIVO, NO SE PUEDE EDITAR ACA
		$idL=$this->uri->segment(4);
		$idP=$this->buscar_datos_model->buscar_idP_L($idL);
		$nro_pago=$this->buscar_datos_model->nro_de_pago($idP);

		$comision_propietario=$this->buscar_datos_model->debe_comision_inmo_idL($idL);
		$debe_CI=$comision_propietario[0];
		$paga_CI=$comision_propietario[1];

		if($nro_pago==1){
			$crud->edit_fields('idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','totalPagar','fecha_update','usuario_update');

			$crud->callback_edit_field('comision_inmo_paga', function ($value, $id) {
				$idL=$this->uri->segment(4);


					/*$ci_a_pagar='<input id="ci_a_pagar" name="ci_a_pagar" type="text" value="" style="width:100px;height:30px" readonly/>';*/

					$ci_a_pagar='<span id="ci_a_pagar"></span>';

					$comision_paga = '<input id="field-comision_inmo_paga" name="comision_inmo_paga" type="text" onblur ="input_ceros(this.id)" onclick="vaciar(this.id)" onkeyup="validar_comision()" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>'.'&nbspde '.$ci_a_pagar.' ,Ingresar con signo negativo, el punto (<b>.</b>) es la coma decimal';		
					return $comision_paga;							
			});	

			$crud->callback_edit_field('comision_inmo_debe', function ($value, $id) {
				$comision_debe = '<input id="field-comision_inmo_debe" name="comision_inmo_debe" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" class="numerico" readonly/>';	
					return $comision_debe;
			});


			$crud->callback_edit_field('sellado_paga', function ($value,$id) {	
				$idL=$this->uri->segment(4);
				$idP=$this->buscar_datos_model->buscar_idP_L($idL);
				$nro_pago=$this->buscar_datos_model->nro_de_pago($idP);

				$sellado_paga = '</span><input id="field-sellado_paga" name="sellado_paga" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px"/>'.'<span id="nro_pago" style="visibility:hidden">'.$nro_pago.'<span/>';
				return $sellado_paga;
			});//cierro callback_add_field	

			$crud->callback_edit_field('certi_firma', function ($value,$id) {			
				$certi_firma = '</span><input id="field-certi_firma" name="certi_firma" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)"/>';
				return $certi_firma;
			});//cierro callback_add_field
		}else if($paga_CI<>0){

			$crud->edit_fields('idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','comision_inmo_paga','comision_inmo_debe','sellado_paga','certi_firma','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','totalPagar','fecha_update','usuario_update');
			$crud->field_type('sellado_paga','invisible');
			$crud->field_type('certi_firma','invisible');

			$crud->callback_edit_field('comision_inmo_paga', function ($value, $id) {
				$idL=$this->uri->segment(4);


					/*$ci_a_pagar='<input id="ci_a_pagar" name="ci_a_pagar" type="text" value="" style="width:100px;height:30px" readonly/>';*/

					$ci_a_pagar='<span id="ci_a_pagar"></span>';
					//$flag_ci_a_pagar='<span id="flag_ci_a_pagar" style="visibility:hidden">'.$paga_CI.'</span>';

					$comision_paga = '<input id="field-comision_inmo_paga" name="comision_inmo_paga" type="text" onblur ="input_ceros(this.id)" onclick="vaciar(this.id)" onkeyup="validar_comision()" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>'.'&nbspde '.$ci_a_pagar.' ,Ingresar con signo negativo, el punto (<b>.</b>) es la coma decimal';		
					return $comision_paga;							
			});	

			$crud->callback_edit_field('comision_inmo_debe', function ($value, $id) {
				$comision_debe = '<input id="field-comision_inmo_debe" name="comision_inmo_debe" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" class="numerico" readonly/>';	
					return $comision_debe;
			});

		}else{
			$crud->edit_fields('idContrato','locador','locatario1','idpago','alquiler','punitorios','comiAdmin','expensas','expensas_detalle','agua','agua_detalle','impuesto_csp','csp_desc','impuesto_inmob','inmob_desc','descArreglos','arreglos_desc','expExtras','expextra_desc','saldos_varios','detalle_saldos','varios1','varios1_detalle','varios2','varios2_detalle','totalPagar','fecha_update','usuario_update');			
			$crud->field_type('comision_inmo_debe','invisible');
			$crud->field_type('comision_inmo_paga','invisible');
			$crud->field_type('sellado_paga','invisible');
			$crud->field_type('certi_firma','invisible');
		}//fin si


		$crud->callback_edit_field('expensas', function ($value,$id) {			
			$expensas = '<input id="field-expensas" name="expensas" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onblur ="input_ceros(this.id)"  onclick="vaciar(this.id)" onkeypress="return validateFloatKeyPress(this,event);"/>';			
			return $expensas;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('expensas_detalle', function ($value,$id) {			
			$expensas_detalle = '<span id="b"></span><textarea id="field-expensas_detalle" name="expensas_detalle" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';			
			return $expensas_detalle;
		});//cierro callback_edit_field	


		$crud->callback_edit_field('agua', function ($value,$id) {			
			$agua = '<input id="field-agua" name="agua" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onblur ="input_ceros(this.id)" onkeypress="return validateFloatKeyPress(this,event);"/>';			
			return $agua;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('agua_detalle', function ($value,$id) {			
			$agua_detalle = '<span id="b"></span><textarea id="field-agua_detalle" name="agua_detalle" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';			
			return $agua_detalle;
		});//cierro callback_edit_field			


		$crud->callback_edit_field('impuesto_csp', function ($value,$id) {			
			$csp = '<input id="field-impuesto_csp" name="impuesto_csp" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>';			
			return $csp;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('csp_desc', function ($value,$id) {			
			$csp_desc_temp = '<span id="b"></span><textarea id="field-csp_desc" name="csp_desc" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';			
			return $csp_desc_temp;
		});//cierro callback_edit_field				

		$crud->callback_edit_field('impuesto_inmob', function ($value,$id) {			
			$inmob = '<input id="field-impuesto_inmob" name="impuesto_inmob" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/> ';			
			return $inmob;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('inmob_desc', function ($value,$id) {			
			$inmob_desc_temp = '<span id="b"></span><textarea id="field-inmob_desc" name="inmob_desc_" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';		
			return $inmob_desc_temp;
		});//cierro callback_edit_field					

		$crud->callback_edit_field('descArreglos', function ($value,$id) {			
			$desc_arreglos = '<input id="field-descArreglos" name="descArreglos" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);" />';
			$textarea='</br><textarea></textarea>';
			return $desc_arreglos;
		});//cierro callback_edit_field

		$crud->callback_edit_field('arreglos_desc', function ($value,$id) {			
			$arreglos_desc_temp = '<span id="b"></span><textarea id="field-arreglos_desc" name="arreglos_desc" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';		
			return $arreglos_desc_temp;
		});//cierro callback_edit_field				

		$crud->callback_edit_field('expExtras', function ($value,$id) {			
			$exp_extras = '<input id="field-expExtras" name="expExtras" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>';
			return $exp_extras;			
		});//cierro callback_edit_field

		$crud->callback_edit_field('expextra_desc', function ($value,$id) {			
			$expextra_desc_temp = '<span id="b"></span><textarea id="field-expextra_desc" name="expextra_desc" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';		
			return $expextra_desc_temp;
		});//cierro callback_edit_field

		$crud->callback_edit_field('saldos_varios', function ($value,$id) {			
			$saldos = '<input id="field-saldos_varios" name="saldos_varios" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>';
			return $saldos;			
		});//cierro callback_edit_field

		$crud->callback_edit_field('detalle_saldos', function ($value,$id) {			
			$saldos_detalle = '<span id="b"></span><textarea id="field-detalle_saldos" name="detalle_saldos" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';		
			return $saldos_detalle;
		});//cierro callback_edit_field							

		$crud->callback_edit_field('varios1', function ($value,$id) {			
			$varios1 = '<input id="field-varios1" name="varios1" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>';
			return $varios1;			
		});//cierro callback_edit_field

		$crud->callback_edit_field('varios1_detalle', function ($value,$id) {			
			$varios1_detalle = '<span id="b"></span><textarea id="field-varios1_detalle" name="varios1_detalle" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';		
			return $varios1_detalle;
		});//cierro callback_edit_field	

		$crud->callback_edit_field('varios2', function ($value,$id) {			
			$varios2 = '<input id="field-varios2" name="varios2" type="text" value="'.$value.'" maxlength="10" style="width:100px;height:30px" onkeypress="return validateFloatKeyPress(this,event);"/>';
			return $varios2;			
		});//cierro callback_edit_field

		$crud->callback_edit_field('varios2_detalle', function ($value,$id) {			
			$varios2_detalle = '<span id="b"></span><textarea id="field-varios2_detalle" name="varios2_detalle" maxlength="500" style="width:400px;height:60px">'.$value.'</textarea> ';		
			return $varios2_detalle;
		});//cierro callback_edit_field			

		$crud->callback_edit_field('totalPagar', function ($value,$id) {			
			$total = '<input id="field-totalPagar" name="totalPagar" type="text" value="'.$value.'" maxlength="12" style="width:120px;height:40px" style="font-weight:bold" class="numerico"/>';

		$boton_calcular='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="calcular" value="CALCULAR" class="ui-input-button">';

		$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

			return $total.$boton_calcular.$boton_limpiar;
		});///cierro callback_edit_field	


		$crud->callback_before_update(array($this,'update_fechaCreacion_Usuario'));		


		$idL=$this->uri->segment(4);
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
		$this->_example_output($output);		
	}


	public function update_fechaCreacion_Usuario($post_array,$pk){
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$post_array['fecha_update']=date('d/m/Y G:i');

		$sesion= $this->session->userdata('usuario');
		$usuario=$sesion[1];		
		$post_array['usuario_update']=$usuario;

		return $post_array;
	}

	public function eliminar_liquidacion($id){
		$idL=$this->uri->segment(3);

		$idC=$this->buscar_datos_model->buscar_idC_L($idL);

		$this->db->where('idLiquida',$id);
		$this->db->delete('liquidaciones');

		redirect('Liquidacion/liquidar/' . $idC);		
	}

		function _example_output($output = null){
			$login= $this->session->userdata('usuario');
			if($login){
				$this->load->view('inicio',(array)$output);		
			}else{
				redirect('login');
			}				
		}
}//CIERRA CLASE	