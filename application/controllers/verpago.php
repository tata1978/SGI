<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Verpago extends CI_Controller{	

		public function __construct(){			
			parent::__construct();
			$this->load->database();
			$this->load->helper('url');
			$this->load->library('grocery_CRUD');
			$this->load->library('session');
			$this->config->load('grocery_crud');
			$this->load->model('buscar_datos_model');
			$this->load->helper('numeros');
		}

		public function index(){			
			//$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
			redirect();
		}		

		public function descargar_pdf($idP){
			$data = [];

			$this->db->select('periodo,anulado');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get(); 
			foreach ($query->result() as $row){
				$periodo = $row->periodo;
				$anulado=$row->anulado;
			}

			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idC = $row->idContrato;
			}

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			$this->db->select('idInmueble,locatario1,locatario2,fechaInicio,fechaFin,duracion,fecha_firma,escribano,punitorio,comision_admin,sellado_contrato,estado_contrato,comision_inmo_a_pagar');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$idI = $row->idInmueble;
				//$locador = $row->locador;
				$locatario1=$row->locatario1;
				$locatario2=$row->locatario2;
				$fechaIn=$row->fechaInicio;
				$fechaFin=$row->fechaFin;
				$fecha_F=$row->fecha_firma;
				$sellado_c=$row->sellado_contrato;
				$escribano=$row->escribano;		
				$mora_porc=$row->punitorio;
				$comi_admin_porc=$row->comision_admin;
				$estado_contrato=$row->estado_contrato;				
				$duracion=$row->duracion;
				$deuda_comision_total=$row->comision_inmo_a_pagar;			
			}

			$deuda_comision_propietario=number_format($deuda_comision_total/2,2,'.','');

			$valor_por=$mora_porc/100;

			setlocale(LC_TIME,"es_RA");
			$fecha_firma=date("d/m/Y", strtotime($fecha_F));
			/*$fechaI = date("m/y", strtotime($fechaIn));
			$fechaF = date("m/y", strtotime($fechaFin));

			$fechaI=$this->buscar_datos_model->formato_fecha($fechaIn);
			$fechaF=$this->buscar_datos_model->formato_fecha($fechaFin);*/
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

			$this->db->select('nombreTipo');
			$this->db->from('tipoinmuebles');
			$this->db->where('idTipoInmueble',$id_tipoinmueble);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$tipoinmueble = $row->nombreTipo;		
			}



			/*$this->db->select('apellidoNombre');
			$this->db->from('personas');
			$this->db->where('dni',$locatario1);		
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$locatario1 = $row->apellidoNombre;
			}*/

			$inquilino1=$this->buscar_datos_model->buscar_persona($locatario1);
			$inquilino2=$this->buscar_datos_model->buscar_persona($locatario2);

			if($inquilino2<>""){
				$locatario=$inquilino1.', '.$locatario1.' - '.$inquilino2.', '.$locatario2;
			}else{
				$locatario=$inquilino1.', '.$locatario1;
			}

			$this->db->select('idpago,valor_alquiler,punitorios,expensas,expensas_detalle,csp,csp_detalle,impuesto_inmob,inmob_desc,luz,luz_detalle,agua,agua_detalle,exp_extra,exp_extra_detalle,varios1,varios1_detalle,varios2,varios2_detalle,saldos_otros,detalle_otros,total_pagar,periodo,mora_dias,comision_inmo_paga,sellado_paga,certi_firma,veraz,comision_inmo_debe,fecha_pago,nro_pago,observaciones,locador,usuario_creacion');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$id_pago=$row->idpago;
				$locador=$row->locador;
				$alquiler = $row->valor_alquiler;
				$punitorios=$row->punitorios;
				$expensas=$row->expensas;
				$expensas_detalle=$row->expensas_detalle;
				$csp=$row->csp;
				$csp_detalle=$row->csp_detalle;
				$impuesto_inmob=$row->impuesto_inmob;
				$inmob_desc=$row->inmob_desc;				
				$luz=$row->luz;
				$luz_detalle=$row->luz_detalle;
				$agua=$row->agua;
				$agua_detalle=$row->agua_detalle;
				$exp_extra=$row->exp_extra;
				$exp_extra_detalle=$row->exp_extra_detalle;
				$varios1=$row->varios1;
				$varios1_detalle=$row->varios1_detalle;
				$varios2=$row->varios2;
				$varios2_detalle=$row->varios2_detalle;				
				$saldos=$row->saldos_otros;
				$detalle_otros=$row->detalle_otros;
				$total=$row->total_pagar;
				$periodo=$row->periodo;
				$dias_mora=$row->mora_dias;
				$comision_paga=$row->comision_inmo_paga;
				$comision_debe=$row->comision_inmo_debe;				
				$sellado_p=$row->sellado_paga;
				$firma=$row->certi_firma;
				$veraz=$row->veraz;
				$nro_pago=$row->nro_pago;
				$observ=$row->observaciones;
				$fecha_pago=$row->fecha_pago;
				$cobrador=$row->usuario_creacion;
			}


			$this->db->select('apellidoNombre,cuit_cuil');
			$this->db->from('personas');
			$this->db->where('dni',$locador);		
			$query=$this->db->get();			
			foreach ($query->result() as $row){
				$locador = $row->apellidoNombre;
				$cuit=$row->cuit_cuil;
			}			

			$fecha=substr($fecha_pago, 0,10);

			if($dias_mora=="") $dias_mora="0";

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			if($operacion=="ALQUILER" || $operacion=="COMERCIAL"){

				$this->db->select('*');
				$this->db->from('pagos');	
				$this->db->where('idContrato',$idC);
				$this->db->where('anulado',0);
				$query=$this->db->get();
				$cant_pagos=$query->num_rows();


				if($alquiler==0.00){
					$texto="No paga rescisión";
				}else{

					if($cant_pagos >= 6 and $cant_pagos < 12){
						$this->db->select('valor_alquiler');
						$this->db->from('pagos');
						$this->db->where('nro_pago',$cant_pagos-1);					
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();	
						foreach ($query->result() as $row){					
							$valor_alquiler=$row->valor_alquiler;					
						}

						$texto =$valor_alquiler." * 1,5 - un mes y medio de alquiler, tomando como referencia el último período abonado ";
					}else{				
						$texto="1 (un) mes de alquiler, tomando como referencia el último período abonado";
					}
				}	
			/*if($operacion=="ALQUILER"){
				$this->db->select('*');
				$this->db->from('pagos');	
				$this->db->where('idContrato',$idC)
				$query=$this->db->get();
				$cant_pagos=$query->num_rows();								
							
				if($cant_pagos >= 6 and $cant_pagos < 12){
					$this->db->select('valor_alquiler');
					$this->db->from('pagos');
					$this->db->where('nro_pago',$cant_pagos-1);
					$query=$this->db->get();	
					foreach ($query->result() as $row){					
						$valor_alquiler=$row->valor_alquiler;					
					}



					$texto =$valor_alquiler." * 1,5 - un mes y medio de alquiler, tomando como referencia el último período abonado ";
				}else{				
					$texto="1 (un) mes de alquiler, tomando como referencia el último período abonado";
				}			

			}elseif($operacion=="COMODATO"){

			}*/
			}elseif($operacion=="COMODATO"){
				$this->db->select('*');
				$this->db->from('pagos');	
				$this->db->where('idContrato',$idC);
				$query=$this->db->get();
				$cant_pagos=$query->num_rows();

				if($cant_pagos >= 1){
				$this->db->select('valor_alquiler');
				$this->db->from('pagos');
				$this->db->where('idContrato',$idC);
				$this->db->where('nro_pago',$cant_pagos);
				$query=$this->db->get();	
					foreach ($query->result() as $row){					
						$valor_alquiler=$row->valor_alquiler;					
					}
				}	

				if($valor_alquiler==0.00){
					$texto="No paga rescision";				
				}else{
					$texto="Paga rescision";				
				}
			}	

			$valor_letra=num_to_letras($total);
			setlocale(LC_TIME,"es_RA");
			/*setlocale(LC_ALL,"es_RA");
			setlocale(LC_TIME,"es_RA");*/
			date_default_timezone_set ("America/Argentina/Buenos_Aires");

			$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
			$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");			

			//$fecha_liquidacion=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;

			$fecha_liquidacion=$fecha_pago;

			//$hora_liquidacion=substr($fecha_pago, 11,5);

			$sesion= $this->session->userdata('usuario');
			$usuario=$sesion[1];			

			$user=$usuario;
			$this->db->select('nombreUsuario');
			$this->db->from('usuarios');
			$this->db->where('NyA',$user);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$usuario=$row->nombreUsuario;
			}

			$ajustes=$this->buscar_datos_model->periodo_ajuste($idC);

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			if($operacion=="ALQUILER" || $operacion=="COMERCIAL"){
				$propietario="LOCADOR";
				$persona="LOCATARIO";	
				$accion="Alquiler";
			}else{
				$propietario="COMODANTE";
				$persona="COMODATARIO";
				$accion="Comodato";
			}


			if($ajustes['1']!=""){
				$fecha_ajuste=date("m-Y", strtotime($ajustes['1']));
				//$fecha_ajuste=$this->buscar_datos_model->formato_fecha($ajustes['1']);						
			}			


			$hoy = date("dmyhis");
			//$fecha=date("d/m/y");

			$this->db->select('rescision');
			$this->db->from('pagos');
			$this->db->where('idpago',$idP);
			$query=$this->db->get();	
			foreach ($query->result() as $row){					
				$rescision=$row->rescision;
			}

			$host=$_SERVER['SERVER_NAME'];

        	//load the view and saved it into $html variable
        	$html = 
        	"<style>@page {        		
			    margin-top: 0.2cm;
			    margin-bottom: 0cm;
			    margin-left: 1.27cm;
			    margin-right: 1.27cm;
			}
			</style>";

			if($rescision == '1'){

				$datos=
		       		 "
		       		 <!-- DUPLICADO -->
		       		 <body>
		       		 <table width='100%' border='1' cellpadding='0' cellspacing='-1'>
		       		 	<tr>		       	 	 		
		       		 		<td align='center' ><b style='font-size:26px'>Taragüi Propiedades</b></td>
		       		 		<td  align='center'><b style='font-size:28px;font-family:arial-black'>X</b><br><p style='font-size:12px'>Recibo por cuenta y orden de terceros</p></td>
							<td align='center'><b>Fecha:</b>$fecha</td>	
							<td align='center'><b>Recibo Nro:</b>$id_pago</td>		       		 			       		 		
		        		</tr> 
		        		<tr>
      						<td colspan='2' align='center'><b style='font-size:14px'>Licia Beatriz Ferrer - CUIT:27-21366018-2 - Responsable Monotributo</b></td>
      						<td colspan='2' align='center'>DOCUMENTO NO VALIDO COMO FACTURA</td>      						
    					</tr>
		        		<tr>
			        		<td colspan='4' align='center'><p style='font-size:14px'>Carlos Pellegrini 1557 - Corrientes - Tel:0379-4423771 - correo: admtaragui@gmail.com</p>
			        		</td>			        		
		        		</tr>        		
		        	</table>

		        	<br>

		        		<table border='0' cellpadding='0' cellspacing='-1'>		        			
		        			<tr>
		        				<td style='width:auto'><u><b>$propietario</b></u>: </td>
		        				<td>$locatario </td>
		        			</tr> 	
		        			<tr>
		        				<td ><u><b>$persona</b></u>: </td>
		        				<td>  $locador - <b>CUIT: </b>$cuit </td>
		        			</tr>		        		
		        			<tr>        				
		        				<td colspan='2'><b><u>INMUEBLE</u>:</b> <b>$direccion $nombreE</b> - <u>Período Locativo</u>: $fechaI a $fechaF - <u> Sistema de Bonificación</u>: $ajustes[0] - <u> Nueva bonificación desde</u>: $fecha_ajuste : $ajustes[2] 
		        				</td>
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
		        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Rescición Contrato</td>
		        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'>$texto</td>
		        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $alquiler</td>
		        			</tr>";
	        }else{
				$datos=
		       		 "
		       		 <!-- DUPLICADO -->
		       		 <body>
		       		 <table width='100%' border='1' cellpadding='0' cellspacing='-1'>
		       		 	<tr>		       	 	 		
		       		 		<td align='center' ><b style='font-size:26px'>Taragüi Propiedades</b></td>
		       		 		<td  align='center'><b style='font-size:28px;font-family:arial-black'>X</b><br><p style='font-size:12px'>Recibo por cuenta y orden de terceros</p></td>
							<td align='center'><b>Fecha:</b>$fecha</td>	
							<td align='center'><b>Recibo Nro:</b>$id_pago</td>		       		 			       		 		
		        		</tr> 
		        		<tr>
      						<td colspan='2' align='center'><b style='font-size:14px'>Licia Beatriz Ferrer - CUIT:27-21366018-2 - Responsable Monotributo</b></td>
      						<td colspan='2' align='center'>DOCUMENTO NO VALIDO COMO FACTURA</td>      						
    					</tr>
		        		<tr>
			        		<td colspan='4' align='center'><p style='font-size:14px'>Carlos Pellegrini 1557 - Corrientes - Tel:0379-4423771 - correo: admtaragui@gmail.com</p>
			        		</td>			        		
		        		</tr>        		
		        	</table>

		        	<br>

		        		<table border='0' cellpadding='0' cellspacing='-1'>		        			
		        			<tr>
		        				<td style='width:auto'><u><b>$persona</b></u>: </td>
		        				<td>  $locatario </td>
		        			</tr> 	
		        			<tr>
		        				<td ><u><b>$propietario</b></u>:</td>
		        				<td> $locador - <b>CUIT: </b>$cuit </td>
		        			</tr>		        		
		        			<tr>        				
		        				<td colspan='2'><b><u>INMUEBLE</u>:</b> $direccion $nombreE - <u>Período Locativo</u>: $fechaI a $fechaF - <u> Sistema de Bonificación</u>: $ajustes[0] - <u> Nueva bonificación desde</u>: $fecha_ajuste : $ajustes[2] 
		        				</td>
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
		        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> $accion</td>
		        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> Período $periodo, Pago nro: $nro_pago de $duracion </td>
		        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $alquiler</td>
		        			</tr>";	        	

	        }			
        			$datos_html=$html.$datos;
        			if($punitorios<>0){        				
		        		$datos_html.=$punit="<tr>	
		        			<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Punitorios</td>
		        			<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px' align='left'> $mora_porc % diario x valor de alquiler x $dias_mora días de mora, ($valor_por x $alquiler x $dias_mora) </td>
		        			<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $punitorios </td>
		        		</tr>";
	        		}
        			
	        		if($comision_paga<>0){
	        			if($comision_debe=="0"){$comision_debe="0.00";}
	        			$datos_html.=$comision="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Comision Inmobiliaria </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>Deuda Original: $deuda_comision_propietario - Saldo Deudor: $comision_debe</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'>$comision_paga</td>
	        			</tr> ";       		  
        			}

        			if($sellado_p<>0){
	        			$datos_html.=$sellado_cont="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Gastos Escribanía</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>Sellados Contrato - 50% de $sellado_c</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $sellado_p </td>
	        			</tr>";
        			}

        			if($firma<>0){
	        			$datos_html.=$certifi="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Gastos Escribanía</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>Certificación de Firmas</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $firma </td>
	        			</tr>";
	        		}
	        		
					if($veraz<>0){	        			
	        			$datos_html.=$veraz_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Otros</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>Veraz</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $veraz </td>
	        			</tr>";
        			}

					if($expensas<>0){	        			
			        	$datos_html.=$expen="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Expensas </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$expensas_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $expensas </td>
	        			</tr>";
	        		}
	        			
        			if($csp<>0){	        			
		        		$datos_html.=$csp_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> CSP </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$csp_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $csp </td>
	        			</tr>";
        			}

        			if($impuesto_inmob<>0){	        			
		        		$datos_html.=$inmo_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Inmp. Inmobiliario </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$inmob_desc</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $impuesto_inmob </td>
	        			</tr>";
        			}        			

        			if($luz<>0){	        			
		        		$datos_html.=$luz_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Luz </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$luz_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $luz </td>
	        			</tr>";
        			}

        			if($agua<>0){	        			
		        		$datos_html.=$agua_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Agua </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$agua_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $agua </td>
	        			</tr>";
        			}

        			if($exp_extra<>0){	        			
		        		$datos_html.=$exp_extra_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Exp.Extraordinarias </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$exp_extra_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $exp_extra </td>
	        			</tr>";
        			}  

        			if($varios1<>0){	        			
		        		$datos_html.=$varios1_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Varios1 </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$varios1_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $varios1 </td>
	        			</tr>";
        			}           			      			

        			if($varios2<>0){	        			
		        		$datos_html.=$varios2_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'> Varios2 </td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$varios2_detalle</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $varios2 </td>
	        			</tr>";
        			} 

        			if($saldos<>0){	        			
		        		$datos_html.=$saldos_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Otros</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$detalle_otros</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> $saldos </td>
	        			</tr>";
        			}

         			if($observ<>""){	        			
		        		$datos_html.=$observaciones_dato="<tr>	
	        				<td style='width:20%;border-bottom: 1px solid #000000;font-size:12px'>Observaciones</td>
	        				<td style='width:70%;border-bottom: 1px solid #000000;font-size:12px'>$observ</td>
	        				<td style='width:10%;border-bottom: 1px solid #000000;font-size:12px' align='right'> </td>
	        			</tr>";
        			}       			

        		    $datos_html.=$fin="<tr> 
        				<td colspan='2' style='height:20px;width:auto' align='right'><b style='font-size:16px'> Importe Total: $</b></td>
        				<td style='height:20px;width:auto' align='right' ><b style='font-size:16px'>$total</b></td>
        			</tr>
        			<tr>       				       				
        				<td colspan='3' style='font-size:12'>
        					Recibi por cuenta y orden del locador la suma de pesos: <b>$valor_letra</b>
        				</td>        				
        			</tr>
        			<tr>
        				<td colspan='3' style='height:40px;vertical-align:text-top;font-size:12' >Liquidado el $fecha_liquidacion por $cobrador</td>
        			</tr>
        		</table>
        		<br>
        		<br>
        		<table width='100%' border='0' cellpadding='0' cellspacing='0' style='font-size:12'>	 
        			<tr>
        				<td style='vertical-align:text-top;width:60%'></td>
        				<td style='height:20px;vertical-align:baseline' align='center'>.....................................................</td>
        			</tr>
        			<tr>        			
        				<td></td>
        				<td align='center'>Firma y Sello</td>
        			</tr>         			      			
        		</table>
        		<br>
        		<br>
        		<br>        		   
        		    		        		
			<!-- DUPLICADO -->

        	</body>";

        	// $html = $this->load->view('v_dpdf',$date,true);
 		
 			//$html="asdf";
        	//this the the PDF filename that user will get to download
        	$pdfFilePath = "alquiler_".$locatario1.'-'.$fecha.".pdf";
 
        	//load mPDF library
        	$this->load->library('M_pdf');
       	 	$mpdf = new mPDF('c', 'A4-P'); 
 			$mpdf->WriteHTML($datos_html);
 			
 			$mpdf->WriteHTML($datos_html);

 			if($anulado==1){
				$mpdf->Image('assets/images/anulado.png', 0, 0, 210, 297, 'png', '', true, true);
 			}
 			
 			//$mpdf->Image('assets/images/anulado.png', 0, 0, 210, 297, 'png', '', true, true);
 			

 			//$mpdf->WriteHTML('<img src="assets/images/anulado.png" />');
 			//$mpdf->SetAlpha(0);




			$mpdf->Output($pdfFilePath, "D");
       		// //generate the PDF from the given html
       		//  $this->m_pdf->pdf->WriteHTML($html);
 
       		//  //download it.
       		//  $this->m_pdf->pdf->Output($pdfFilePath, "D"); 
		}


	public function verpago_finalizados($id){
		/*$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);	*/

		$output = $this->verpago_finalizados_management($id);		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 

		$this->db->select('idInmueble,locador,locatario1');
		$this->db->where('idContrato',$id);
		$this->db->from('alquileres');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;	
			$locador=$row->locador;
			$locatario1=$row->locatario1;		
		}

		$inmueble=$this->buscar_datos_model->buscar_inmueble($idI);

		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locatario1);
		$this->db->from('personas');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$locatario1=$row->apellidoNombre;		
		}	
		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locador);
		$this->db->from('personas');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$locador=$row->apellidoNombre;		
		}

		$output = "<div class='texto' style='display:none;' >- <b style='font-weight: normal;color:white'>Inmueble:</b> $inmueble<b style='font-weight: normal;color:white'> - Locatario:</b> $locatario1 <b style='font-weight: normal;color:white'> - Locador:</b> $locador</div>".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}


		public function verpago_finalizados_management($id){
			//$this->config->set_item('grocery_crud_dialog_forms',true);
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');				

			$crud->where('idContrato',$id);
			$crud->order_by('nro_pago','asc');
			$crud->unset_add();

			$crud->unset_operations();

			//$crud->set_relation('idContrato','alquileres','locatario');
			//$crud->set_relation('idContrato','alquileres','proxVenc');			


			$crud->columns('nro_pago','idpago','periodo','fecha_pago','valor_alquiler','punitorios','expensas','csp','impuesto_inmob','luz','agua','comision_inmo_paga','total_pagar','pagado_propietario');				

			$crud->display_as('nro_pago','#');
			$crud->display_as('idpago','Recibo');
			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('periodo','Periodo');
			$crud->display_as('fecha_pago','Pagado');
			$crud->display_as('valor_alquiler','Alquiler');
			$crud->display_as('punitorios','Punit.');
			$crud->display_as('impuesto_inmob','I-I');
			
			$crud->display_as('mora_dias','Mora a la fecha');
			$crud->display_as('paga_mora','¿Paga mora?');
			$crud->display_as('saldos_otros','Varios');
			$crud->display_as('total_pagar','Total');
			$crud->display_as('comision_inmo_paga','CI');
			$crud->display_as('fechaUltimoPago','Ultimo Pago, Periodo');
			$crud->display_as('pagado_propietario','¿Pagado a Prop.?');


			
			//$crud->add_action('Ver', '', 'Verpago/read',' ui-icon-document');
			$crud->add_action('Imprimir', '', 'Verpago/descargar_pdf','ui-icon-print');
			$crud->add_action('Ver', '', 'Verpago/read',' ui-icon-folder-open');
			//$crud->add_action('Editar', '', 'Pago/pagos_edit/edit','ui-icon-pencil');		
			
			$crud->callback_column('nro_pago',array($this,'esta_anulado_pago'));
			$crud->callback_column('periodo',array($this,'periodo_column'));

			$output = $crud->render();	
			//$this->_example_output($output);
			if($crud->getState() != 'list') {
				$this->_example_output($output);
			} else {
				return $output;
			}			
		}		

	public function verpago($id){
		/*$this->config->load('grocery_crud');
		$this->config->set_item('grocery_crud_dialog_forms',true);
		$this->config->set_item('grocery_crud_default_per_page',10);	*/

		$output = $this->verpago_management($id);		

		$js_files =$output->js_files; 
		$css_files =  $output->css_files; 

		$this->db->select('idInmueble,locador,locatario1');
		$this->db->where('idContrato',$id);
		$this->db->from('alquileres');
		$query=$this->db->get();
		foreach ($query->result() as $row) {																														
			$idI=$row->idInmueble;	
			$locador=$row->locador;
			$locatario1=$row->locatario1;		
		}

		$inmueble=$this->buscar_datos_model->buscar_inmueble($idI);

		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locatario1);
		$this->db->from('personas');							
		$query=$this->db->get();							
		foreach ($query->result() as $row) {
			$locatario1=$row->apellidoNombre;		
		}	
		$this->db->select('apellidoNombre');
		$this->db->where('dni',$locador);
		$this->db->from('personas');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$locador=$row->apellidoNombre;		
		}

		$operacion=$this->buscar_datos_model->tipo_operacion($id);
		if($operacion=="ALQUILER" ){
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


		$output = "<div class='texto' style='display:none;' >- <b style='font-weight: normal;color:white'><span id='operacion'>".$accion."</span>:</b> $inmueble<b style='font-weight: normal;color:white'> - ".$propietario.":</b> $locador <b style='font-weight: normal;color:white'> - ".$persona.":</b> $locatario1</div>".$output->output;

		$this->_example_output((object)array(
				'js_files' => $js_files,
				'css_files' => $css_files,
				'output'	=> $output
		));
	}


		public function verpago_management($id){
			//$this->config->set_item('grocery_crud_dialog_forms',true);
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');				

			$crud->where('idContrato',$id);
			$crud->order_by('nro_pago','asc');
			$crud->unset_add();

			$crud->unset_operations();

			//$crud->set_relation('idContrato','alquileres','locatario');
			//$crud->set_relation('idContrato','alquileres','proxVenc');

			$crud->columns('nro_pago','idpago','periodo','fecha_pago','valor_alquiler','punitorios','expensas','csp','impuesto_inmob','luz','agua','comision_inmo_paga','total_pagar','pagado_propietario');				

			$crud->display_as('nro_pago','#');
			$crud->display_as('idpago','Recibo');
			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('periodo','Periodo');
			$crud->display_as('fecha_pago','Pagado');
			$crud->display_as('valor_alquiler','Alquiler');
			$crud->display_as('punitorios','Punit.');
			$crud->display_as('impuesto_inmob','I-I');
			
			$crud->display_as('mora_dias','Mora a la fecha');
			$crud->display_as('paga_mora','¿Paga mora?');
			$crud->display_as('saldos_otros','Varios');
			$crud->display_as('total_pagar','Total');
			$crud->display_as('comision_inmo_paga','CI');
			$crud->display_as('fechaUltimoPago','Ultimo Pago, Periodo');
			$crud->display_as('pagado_propietario','¿Liquidado?');

			$sesion= $this->session->userdata('usuario');
			/*if($sesion[0]==1 or $sesion[0]==3){
				$crud->add_action('Liquidar', '', 'Liquidacion/liquidar/add',' ui-icon-calculator');
			}*/
			$crud->add_action('Imprimir', '', 'Verpago/descargar_pdf','ui-icon-print');
			$crud->add_action('Ver', '', 'Verpago/read',' ui-icon-folder-open');
			$crud->add_action('Editar', '', 'Pago/pagos_edit/edit','ui-icon-pencil');			


			$crud->callback_column('nro_pago',array($this,'esta_anulado_pago'));

			$crud->callback_column('periodo',array($this,'periodo_column'));

			$crud->callback_column('pagado_propietario',array($this,'pagado_propietario'));

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

	public function pagado_propietario($value,$row){
			if($value=="SI"){
				$estado = '<span id="estado" style="background: green" class="badge badge-pill">'.$value.'</span>';
			}else{
				$estado = '<span id="estado" style="background: red" class="badge badge-pill">'.$value.'</span>';
			}
			return $estado;
	}

	public function esta_anulado_pago($value,$row){
		$host=$_SERVER['SERVER_NAME'];
		$anulado=$row->anulado;
		if($anulado==1){
			return '<span title="Anulado" style="background: red" class="badge badge-pill"><b>x</b></span>';
			//return "<img title='Anulado' src='http://$host/SGI/assets/images/cancelar.png'>";
		}else{
			return $value;
		}
	}	

		public function read(){
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables');
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');	

			$crud->display_as('idContrato','Inmueble');
			
			//$crud->display_as('paga_c_inmo','Deuda CI');
			$crud->display_as('prox_venc_sig','Prox. Vencimiento');
			$crud->display_as('total_pagar','Total Pagado');
			$crud->display_as('comision_inmo_paga','CI pago parcial');
			$crud->display_as('comision_inmo_debe','CI deuda');
			$crud->display_as('certi_firma','Certificación de Firmas');
			$crud->display_as('sellado_paga','Sellado Contrato');


			


			//$crud->field_type('paga_c_inmo','hidden');
			$crud->field_type('rescision','hidden');
			$crud->field_type('renueva','hidden');
			$crud->field_type('fechaUltimoPago','hidden');
			$crud->field_type('paga_mora','hidden');

			//$crud->fields('idContrato','periodo','valor_alquiler','paga_mora','mora_dias','punitorios','expensas','csp','luz','agua','saldos_otros','paga_c_inmo','total_pagar','observaciones','pagado_propietario','fecha_pago','usuario_creacion');


			$id=$this->uri->segment(3);

			$this->db->select('idContrato');
			$this->db->from('pagos');
			$this->db->where('idpago',$id);
			$query=$this->db->get();								
			foreach ($query->result() as $row){
				$idC=$row->idContrato;
			}	

			$operacion=$this->buscar_datos_model->tipo_operacion($idC);

			if($operacion=="COMODATO"){
				$crud->display_as('locador','Comodante');
				$crud->display_as('locatario1','Comodatario');
				$crud->display_as('valor_alquiler','Valor Comodato');					
			}

			$crud->set_crud_url_path(site_url('Verpago/verpago/'.$idC));

			$crud->callback_read_field('nro_pago',array($this,'es_nulo'));

			$crud->callback_read_field('idContrato',array($this,'buscar_inmueble'));

			//$crud->callback_read_field('periodo',array($this,'periodo_column'));

			$crud->callback_read_field('locador', function ($value, $primary_key) {				
				$locador=$this->buscar_datos_model->buscar_persona($value);
				return $locador;
			});				

			$crud->callback_read_field('locatario1', function ($value, $primary_key) {				
				$locatario1=$this->buscar_datos_model->buscar_persona($value);
				return $locatario1;
			});	

			$output = $crud->render();
			$this->_example_output($output);	
		}

		public function es_nulo($value,$primary_key){
				$anulado=$this->buscar_datos_model->es_pago_anulado($primary_key);
				if($anulado==1){
					$mensaje="<b style=color:red>".$value." - Anulado"."</b>";
				}else{
					$mensaje=$value;
				}
				return $mensaje;
			
		}

		public function buscar_inmueble($value,$primary_key){						
			$idI=$this->buscar_datos_model->buscar_idI($value);
			$idE=$this->buscar_datos_model->buscar_idE($idI);
			if(isset($idE)){
				$edificio=$this->buscar_datos_model->buscar_edificio($idE);
				$direccionE=$this->buscar_datos_model->buscar_inmueble($idI);
				$nombreE=$edificio['edificio'];
				$barrio=$edificio['barrio'];
				$direccion=$direccionE.'&nbsp,&nbsp '.$nombreE.'&nbsp- &nbspBarrio: &nbsp'.$barrio;
			}else{
				$direccion=$this->buscar_datos_model->buscar_inmueble($idI);				
			}	
			return $direccion;
		}

		public function edit(){
			$crud = new grocery_CRUD();			
			$crud->set_table('pagos');	
			$crud->set_subject('Pago');				

			$crud->set_relation('idContrato','alquileres','locatario1');
			$crud->set_relation('idContrato','alquileres','proxVenc');

		$crud->fields('idContrato','fechaUltimoPago','periodo','valor_alquiler','mora_dias','paga_mora','punitorios','paga_c_inmo','expensas','csp','luz','agua','saldos_otros','total_pagar','observaciones','fecha_pago','usuario_creacion','prox_venc_sig','pagado_propietario','locador');

			//$crud->required_fields('idContrato');

			$crud->field_type('locador','invisible');
			$crud->field_type('usuario_creacion','invisible');
			$crud->field_type('fecha_pago','invisible');
			$crud->field_type('prox_venc_sig','invisible');
			$crud->field_type('pagado_propietario','invisible');			

			$crud->field_type('paga_mora','enum',array('SI','NO'));


			$crud->display_as('idContrato','Inmueble');
			$crud->display_as('periodo','Periodo Actual');
			$crud->display_as('valor_alquiler','Importe Alquiler');
			$crud->display_as('mora_dias','Mora a la fecha');
			$crud->display_as('paga_mora','¿Paga mora?');
			$crud->display_as('saldos_otros','Saldos Varios');
			$crud->display_as('total_pagar','Total a Cobrar ');
			$crud->display_as('paga_c_inmo','Saldo por CI');
			$crud->display_as('fechaUltimoPago','Ultimo Pago, Periodo');	
				
			//$crud->set_crud_url_path(site_url('Alquiler/alquiler'));
			//id de contrato de la url

				
			//aca completo datos del inmueble, locatario y locador				
			
					$crud->callback_edit_field('idContrato', function ($value, $primary_key) {
						$idP=$this->uri->segment(3);
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



						$combo= '<select id="field-idContrato" class="form-control" name="idContrato" style="width:auto" ><option value = '.$idC.'>'.strtoupper($direccion).'</option></select>';

						return $combo.'&nbsp<b>Locatario:</b> '.strtoupper($nombre_locatario1).'  -  '.'   <b>Locador:</b> '.strtoupper($nombre_locador).$Nedificio;

					});//cierro callback_add_field

					$crud->callback_edit_field('fechaUltimoPago', function ($value, $primary_key) {		
						$idP=$this->uri->segment(3);
						$idC=$this->buscar_datos_model->buscar_idC_P($idP);												
						$this->db->select('periodo,total_pagar');
						$this->db->from('pagos');
						$this->db->where('idContrato',$idC);
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
						$ultimo_pago = '<input id="field-fechaUltimoPago" name="fechaUltimoPago" type="text" value="'.$value.'" maxlength="10" style="width:80px;height:30px" />';				
						$texto='&nbsp&nbsp <b>Importe pagado: $ </b>'.$importe_anterior;
						return $ultimo_pago;

					});///cierro callback_add_field



					$crud->callback_edit_field('periodo', function ($value, $primary_key) {
						$idP=$this->uri->segment(3);
						$idC=$this->buscar_datos_model->buscar_idC_P($idP);
						$this->db->select('periodo');
						$this->db->from('pagos');
						$this->db->where('idpago',$idP);
						$query=$this->db->get();
						foreach ($query->result() as $row){										
							$periodo=$row->periodo;
						}
						$this->db->select('valor,punitorio');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();
						foreach ($query->result() as $row){														
							$punitorio_porc=$row->punitorio;
						}						
						//$proxvenc=date("d/m/Y", strtotime($prox_venc));
						$texto='<input id="field-periodo" class="form-control" name="periodo" type="text" value="'.$periodo.'" maxlength="10" style="width:85px;height:30px" readonly="readonly"/>'.'</span> &nbsp&nbsp <b>Mora diaria:</b> <span id="porcentaje"> '.$punitorio_porc.'</span> % ';				

						return $texto;	
					});//fin callback_add_field



					$crud->callback_edit_field('valor_alquiler', function ($value, $primary_key) {

						$valor_alquiler = '<sapn id="b">$</span><input id="field-valor_alquiler" name="valor_alquiler" type="text" value="'.$value.'" maxlength="10" style="width:80px;height:30px" readonly="readonly" />';	
						
						return $valor_alquiler;
					});//cierro callback


					$crud->callback_edit_field('mora_dias', function ($value, $primary_key) {
						$idP=$this->uri->segment(3);
						$idC=$this->buscar_datos_model->buscar_idC_P($idP);
						$this->db->select('mora_dias');
						$this->db->from('pagos');
						$this->db->where('idpago',$idP);
						$query=$this->db->get();
						foreach ($query->result() as $row){										
							$mora_d=$row->mora_dias;
						}						
						$mora_dias = '<input id="field-mora_dias" name="mora_dias" type="text" value="'.$mora_d.'" maxlength="3" style="width:30px;height:30px" class="numeric form-control"/> dias por  $&nbsp<input id="field-valor-diario" name="valor-diario" type="text" maxlength="5" class="numeric form-control" style="width:40px;height:30px" value="0"/> <b id="texto-total-punitorio">&nbspTotal $: </b><span id="total-mora">0</span> ';	
						return $mora_dias;
					});//fin callback_add_field

					$crud->callback_edit_field('paga_mora', function ($value, $primary_key) {						
						$combo_mora = '	<select id="field-paga_mora" name="paga_mora" class="chosen-select" data-placeholder="Seleccionar Paga mora" onchange="pagamora()"> value="'.$value.'"<option value="SI"  >SI</option><option value="NO"  >NO</option>  </select>';	
						return $combo_mora;
					});	//fin callback_add_field

					$crud->callback_edit_field('punitorios', function ($value, $primary_key) {					
						$mora_importe='<sapn id="b">$</span><input id="field-punitorios" class="numerico" name="punitorios" type="text" value="'.$value.'" maxlength="10" style="width:80px;height:30px" readonly="readonly"/>';
						return $mora_importe;	
					});///fin callback_add_field

					
					$crud->callback_edit_field('paga_c_inmo', function ($value, $primary_key) {	
						$idP=$this->uri->segment(3);
						$idC=$this->buscar_datos_model->buscar_idC_P($idP);										
						$this->db->select('valor1,punitorio,comision_inmo_debe');
						$this->db->from('alquileres');
						$this->db->where('idContrato',$idC);
						$query=$this->db->get();
						foreach ($query->result() as $row){							
							$valor=$row->valor1;
							$punitorio_porc=$row->punitorio;
							$comision_saldo=$row->comision_inmo_debe;
						}
						$this->db->select('paga_c_inmo');	
						$this->db->from('pagos');	
						$this->db->where('idpago',$idP);
						$query=$this->db->get();
						foreach ($query->result() as $row) {
							$paga_CI=$row->paga_c_inmo;
						}
						$saldoInicial=$comision_saldo+$paga_CI;

						$saldo_comision='<sapn id="b">$</span><input id="saldo_comision" name="saldo_comision" type="text" value="'.$comision_saldo.'" style="width:80px;height:30px"> &nbsp&nbsp ¿Paga? <b>&nbsp</b><select id="comision" name="saldo-comision" data-placeholder="Seleccionar Paga mora" onchange="pagasaldo()" disabled><option value=" "></option><option value="SI"  >SI</option><option value="NO"  >NO</option>  </select> ';

						$paga_c_inmo = '<sapn id="b">$</span>&nbsp<input id="field-resta_saldocomision" name="resta_saldocomision" type="text" value="'.$value.'" maxlength="8" style="width:80px;height:30px" class="numerico" onclick="vaciar(this.id)"  onblur ="input_ceros(this.id)" onkeyup="validar_comision()" disabled />';


						$input_resta='&nbsp&nbsp Resta<sapn id="b">  $</span>&nbsp<input id="field-paga_c_inmo" type="text" value="'.$value.'" name="paga_c_inmo" maxlength="8"  style="width:80px;height:30px" readonly="readonly"/>'.'&nbsp; Tenia un saldo inicial de CI de: $'.$saldoInicial;						


						return $saldo_comision.$paga_c_inmo.$input_resta;
					});	



					$crud->callback_edit_field('expensas', function ($value, $primary_key) {
						$expensas = '<sapn id="b">$</span><input id="field-expensas" name="expensas" type="text" value="'.$value.'" maxlength="8" style="width:80px;height:30px" class="numerico" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $expensas;
					});						


					$crud->callback_edit_field('csp', function ($value, $primary_key) {
						$csp = '<sapn id="b">$</span><span class="required"></span><input id="field-csp"  name="csp" type="text" value="'.$value.'" maxlength="8" style="width:80px;height:30px" class="numerico" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $csp;
					});//fin callback_add_field

					$crud->callback_edit_field('luz', function ($value, $primary_key) {
						$luz = '<sapn id="b">$</span><span class="required"></span><input id="field-luz" name="luz" type="text" value="'.$value.'" maxlength="8" style="width:80px;height:30px" class="numerico" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)"  />';	
						return $luz;
					});//fin callback_add_field					

					$crud->callback_edit_field('agua', function ($value, $primary_key) {
						$agua = '<sapn id="b">$</span><span class="required"></span><input id="field-agua" name="agua" type="text" value="'.$value.'" maxlength="8" style="width:80px;height:30px" class="numerico" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $agua;
					});//fin callback_add_field	

					$crud->callback_edit_field('saldos_otros', function () {
				$saldos = '<sapn id="b">$</span><span class="required"></span><input id="field-saldos_otros" name="saldos_otros" type="text" value="0" maxlength="8" style="width:80px;height:30px" class="numerico" onclick="vaciar(this.id)" onblur ="input_ceros(this.id)" />';	
						return $saldos;
					});//fin callback_add_field

					$crud->callback_edit_field('total_pagar', function ($value, $primary_key) {
						$total = '<sapn id="b">$</span><span class="required"></span><span id="cero"><input  id="field-total_pagar" name="total_pagar" type="text" value="'.$value.'" maxlength="8" style="width:100px;height:40px"  style="font-weight:bold" class="numerico" readonly="readonly"/></span>';
						$boton_sumar='&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="sumar" value="SUMAR" class="ui-input-button">';	

						$boton_limpiar = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="limpiar" value="LIMPIAR" class="ui-input-button">';

						$boton_imprimir = '&nbsp&nbsp&nbsp&nbsp&nbsp<input type="button" name="button" id="imprime" value="IMPRIMIR" class="ui-input-button">';						

						return $total.$boton_sumar.$boton_limpiar;
					});//fin callback_add_field		

					$crud->callback_edit_field('observaciones', function ($value, $primary_key) {
					$obserc = '<textarea name="observaciones" maxlength="300" id="field-observaciones" onkeypress="mayuscula(this)" value="'.$value.'"></textarea>';	
						return $obserc;
					});	//fin callback_add_field	


				$crud->callback_after_update(array($this, 'update_saldo_comision'));

				$crud->callback_before_update(array($this,'fecha_pago'));								

			$output = $crud->render();
			$this->_example_output($output);			
		}

			public function update_saldo_comision_update($post_array,$pk){
				$id_contrato = $post_array["idContrato"];
				$comision_inmo = $post_array["paga_c_inmo"];				
				$this->db->set('comision_inmo_debe',$comision_inmo);				
				$this->db->where('idContrato',$id_contrato);
				$this->db->update('alquileres');

				//busco el dni del locador para actualizar el campo pendientes en personas
				$this->db->select('locador');
				$this->db->from('alquileres');
				$this->db->where('idContrato',$id_contrato);
				$query = $this->db->get();
				foreach ($query->result() as $row){							
					$locador = $row->locador;
				}

				$this->db->set('pendientes',"SI");
				$this->db->where('dni',$locador);
				$this->db->update('personas');
			}

			public function fecha_pago_update($post_array){				
				date_default_timezone_set('America/Argentina/Buenos_Aires');
				$post_array['fecha_pago']=date('d/m/Y G:i');

				$sesion= $this->session->userdata('usuario');
				$usuario=$sesion[1];								
				$post_array['usuario_creacion']=$usuario;
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