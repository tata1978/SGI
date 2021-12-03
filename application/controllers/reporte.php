<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Reporte extends CI_Controller{

		function __construct()
		{
		   parent::__construct();
		   $this->load->helper('form');		   
		   $this->load->model('buscar_datos_model');
		   $this->load->library('export_excel');
		   $this->load->library('session');
		}

		public function excel(){			
			$edificio=$this->buscar_datos_model->personas();
			$this->export_excel->to_excel($edificio, 'listado');	

		}		
	
		public function reporte_caja(){				
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));		

			$edificios=$this->buscar_datos_model->edificios();	

			$data['edificio']=$edificios;

				if($_POST){					
					$diario=$this->input->post('buscar_diario');	
					$mensual=$this->input->post('mes');
					$ano=$this->input->post('ano');
					$edificio=$this->input->post('edificio');

					if($diario <> ""){
						$data['caja_mensual']="";
						$data['caja_diaria']=$this->buscar_datos_model->buscar_reporte_caja_diaria($diario);			
					}elseif ($mensual <> "") {
						$data['caja_diaria']="";
						$data['caja_mensual']=$this->buscar_datos_model->buscar_reporte_caja_mensual($mensual,$ano);
					}else{
						$data['caja_diaria']="";	
						$data['caja_mensual']="";	
					}	
				}else{
					$data['caja_diaria']="";	
					$data['caja_mensual']="";							
				}				

				
			$this->load->view('caja', $data);
		}


		public function imprimir_caja_diaria(){
			$fecha=$this->uri->segment(3);

			$filtro_mes=substr($fecha, 0,3); //determino si es diario o mensual
			$filtro_ano=substr($fecha, 5,4);
			if($filtro_mes=="ENE" OR $filtro_mes=="FEB" OR $filtro_mes=="MAR" OR $filtro_mes=="ABR" OR $filtro_mes=="MAY" OR $filtro_mes=="JUN" OR $filtro_mes=="JUL" OR $filtro_mes=="AGO" OR $filtro_mes=="SEP" OR $filtro_mes=="OCT" OR $filtro_mes=="NOV" OR $filtro_mes=="DIC"){

						if($filtro_mes=="ENE") $mes="01";
						if($filtro_mes=="FEB") $mes="02";
						if($filtro_mes=="MAR") $mes="03";
						if($filtro_mes=="ABR") $mes="04";
						if($filtro_mes=="MAY") $mes="05";
						if($filtro_mes=="JUN") $mes="06";
						if($filtro_mes=="JUL") $mes="07";
						if($filtro_mes=="AGO") $mes="08";
						if($filtro_mes=="SEP") $mes="09";
						if($filtro_mes=="OCT") $mes="10";
						if($filtro_mes=="NOV") $mes="11";
						if($filtro_mes=="DIC") $mes="12";
						$periodo=$mes.'/'.$filtro_ano;
						$reporte="Mensual";
			}else{
				$periodo=date('d/m/Y',strtotime($fecha));
				$reporte="Diario";
			}

			

			$reporte_diario=$this->buscar_datos_model->imprimir_reporte_caja_diaria($periodo);

			$locatario=$reporte_diario[0];
			$inmueble=$reporte_diario[1];
			$fecha_pago=$reporte_diario[2];
			$monto=$reporte_diario[3];
			$registros=$reporte_diario[4];
			$sumatoria=$reporte_diario[5];
			$dia=$reporte_diario[6];
			$user=$reporte_diario[7];
			$edificio=$reporte_diario[8];



			$html = 
			        	"<style>@page {        		
						    margin-top: 1cm;
						    margin-bottom: 1cm;
						    margin-left: 1.27cm;
						    margin-right: 1.27cm;
						}

						    .reporte_pdf {
								font-family: Verdana, Arial, Helvetica, sans-serif;
								font-size:11px;	
								border-collapse:collapse;															

								}
							.reporte_pdf th{
								border: 0px solid #333;
								padding: 2px;
								background-color: #006688;
								color: #FDFDFD;	
								text-align: center;	
							}	

							.filas_reporte td{
								padding: 3px;								
							}

							.usuario{
								text-align: center;
							}

							.monto{
								text-align: right;
							}															

						</style>".
			       		 "<body>
			       		 <table width='100%' border='0' cellpadding='0' cellspacing='0'>
			       		 	<tr>
			       		 		<td align='center' valign='bottom'><b style='font-size:26px'>Taragüi Propiedades</b> - <b style='font-size:18px'>Reporte de Caja $reporte</b></td> 			       		 		
			        		</tr>
			        		<tr>
				        		<td colspan='2' align='center' style='vertical-align:text-top; border-bottom: 1px solid black;'><b style='font-size:12px'>Carlos Pellegrini 1557, Taragui V - Corrientes Capital - Tel:0379-4423771 - correo: admtaragui@gmail.com</b>
				        		</td>	        		
			        		</tr>        		
			        	</table>	
			        	<br>
		
						<table   border='0' cellpadding='0' cellspacing='0'>
							<tr >
								<td height='40' width='175px'><b>$dia</b></td>
								<td height='40' width='250px'><b>Cantidad de Registros: $registros</b></td>
								<td height='40' width='250px'><b>Sumatoria: $sumatoria</b></td>								
							</tr>
						</table>

						<table class='reporte_pdf' border='1' cellpadding='0' cellspacing='0'>
							<tr>
								<thead>
									<th><b>#</b></th>
									<th><b>Pago</b></th>
									<th><b>Fecha</b></th>
									<th><b>Locatario</b></th>
									<th><b>Inmueble</b></th>
									<th><b>Edificio</b></th>
									<th><b>Monto</b></th>
									<th><b>Usuario</b></th>
								</thead>	
							</tr>";				
				asort($locatario);
				$i=0;			
				foreach($locatario as $idP => $nombre){				
				
				$i=$i+1;							
				$dato_requi.=					
						"<tr class='filas_reporte' height='25'>
							<td>$i</td>
							<td>$idP</td>
							<td>$fecha_pago[$idP]</td>							
							<td>$nombre</td>							
							<td>$inmueble[$idP]</td>
							<td class='edificio'>$edificio[$idP]</td>
							<td class='monto'>$monto[$idP]</td>
							<td class='usuario'>$user[$idP]</td>
						</tr>";						
				}

			$html.=$dato_requi;	

			$html.=$fin="</table></body>";			
			
			$pdfFilePath = "reporte_caja_".$reporte.".".$dia.".pdf";	
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

		function _example_output($output = null){
			$login= $this->session->userdata('usuario');
			if($login){
				$this->load->view('inicio',(array)$output);		
			}else{
				redirect('login');
			}				
		}
	}

	