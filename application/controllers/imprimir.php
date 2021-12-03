<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Imprimir extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
        $this->load->library('session');
		$this->config->load('grocery_crud');
		$this->load->model('buscar_datos_model');
		$this->load->helper('numeros');
        $this->load->library('session');

	}
	public function index(){	
			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	
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
            $user=$sesion[1];            
			
			$this->db->select('NyA');
			$this->db->from('usuarios');
			$this->db->where('nombreUsuario',$user);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$usuario=$row->NyA;
			}

			$hoy = 'reserva-'.$interesado.'-'.$direccion;
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
       		 		<td><b style='font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:20px'>Reserva de Inmueble</b></td> 
       		 		<td align='right'><img src='http://localhost/SGI-2/assets/images/logo_TP.jpg' width='75' height='70'></td>
        		<tr>
        		<tr>
	        		<td style='height:20px;vertical-align:text-top'><b style='font-size:14px'>Córdoba 682 - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
	        		</td>
	        		<td align='center' style='font-size:12px'>Original</td>
        		</tr>        		
        	</table>
        	<hr>
        			
        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>
        			<tr>
        				<td><b>N° de reserva: $idR</b></td>
        			</tr>
        			<tr >        				
        				<td style='height:50px;vertical-align:text-top'><u><b>RECIBO</b></u>: seña en concepto de reserva, para el <u>Inmueble</u>: <b>$direccion $nombreE</b>, válido por 5 (cinco) días hábiles, con previa presentación de documentación y aprobación por parte del propietario.  
        				</td>
        			</tr>
        		</table>
        		<table border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
        				<td ><u><b>Interesado</b></u>:</td>
        				<td > $interesado </td>
        				<td ><u><b>Teléfono</b></u>:</td>
        				<td > $telefono </td>        				
        			</tr>
        		</table>
        		<br>
        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>	
        			<tr>
        				<td style='width:auto'><b></b></td>
        				<td align='center'><b>DETALLE</b></td>
        				<td align='center'><b>VALOR</b></td>
        			</tr>
        			<tr>	
        				<td style='width:auto' style='border-bottom: 1px solid #000000'> </td>
        				<td align='center' style='border-bottom: 1px solid #000000'>Seña en concepto por reserva de inmueble   </td>
        				<td align='right' style='border-bottom: 1px solid #000000' > $sena </td>
        			</tr>	
        		    <tr>        		    	
        				<td colspan='2' style='height:20px;width:auto' align='right'><b style='font-size:17px'> Importe : $</b></td>
        				<td style='height:20px;width:auto' align='right' ><b style='font-size:17px'>$sena</b></td>
        			</tr>        			
        			<tr>       				       				
        				<td colspan='3'>
        				Recibi conforme la cantidad de pesos: <b>$sena</b> - son pesos: <b>$valor_letra.</b>
        				</td>        				
        			</tr>
        			<tr>
        				<td colspan='3' style='height:40px;vertical-align:text-top' >Emitido el : $fecha_sena a las $hora_sena por $usuario</td>
        			</tr> 
        		</table>

        		<br>
        		<table width='100%' border='0' cellpadding='0' cellspacing='0'>	 
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
}	