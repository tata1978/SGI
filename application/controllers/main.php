<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->config->load('grocery_crud');
		$this->load->model('buscar_datos_model');
		$this->load->library('session');
	}	

	public function index(){			
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));

///CANTIDAD DE ALQUILERES
		$sql="select * from alquileres where (operacion='ALQUILER' or operacion='COMERCIAL') AND (estado_contrato !='FINALIZADO' and estado_contrato!='RESCINDIDO')";		
		$query=$this->db->query($sql);			
		$alqui= $query->num_rows();
		
		$dato['alqui']=$alqui;

///CANTIDAD DE COMODATOS
		$sql="select * from alquileres where operacion='COMODATO' AND (estado_contrato <>'FINALIZADO'  and estado_contrato<>'RESCINDIDO')";
		$query=$this->db->query($sql);			
		$comodato= $query->num_rows();

		$dato['comodato']=$comodato;		

//CANTIDAD DE DEPARTAMENTOS
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('idTipoInmueble','1');	
		$query=$this->db->get();			
		$deptos= $query->num_rows();		

		$dato['deptos']=$deptos;

///CANTIDAD DE DEPARTAMENTOS ALQUILADOS
		$this->db->select('idInmueble');
		$this->db->from('alquileres');	
		/*$this->db->where('estado_contrato','VIGENTE');
		$this->db->or_where('estado_contrato','FINALIZA');
		$this->db->or_where('estado_contrato','VIG.RESCINDE');
		$this->db->or_where('estado_contrato','RENUEVA');
		$this->db->or_where('estado_contrato','FINALIZA');*/
  		$st="estado_contrato<>'FINALIZADO' and estado_contrato<>'RESCINDIDO'";
  		$this->db->where($st, NULL, FALSE); 



		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$idI_A[$idI]=$idI;
		}
		$deptos_alquilados=0;
		$this->db->select('idInmueble,idTipoInmueble');
		$this->db->from('inmuebles');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI_I=$row->idInmueble;
			$idTI=$row->idTipoInmueble;
			if(isset($idI_A[$idI_I]) and $idTI == '1'){
				$deptos_alquilados=$deptos_alquilados+1;
			}
		}
		$dato['depto_alqui']=$deptos_alquilados;


///DEPTOS DISPONIBLES
		/*$this->db->select('*');
		$this->db->from('inmuebles');			
		$this->db->where('idTipoInmueble',1);
		$this->db->where('estado',0);
		$this->db->or_where('estado',5);*/	
$sql="SELECT * FROM inmuebles  WHERE idTipoInmueble=1 AND (estado=0)";		
		$query=$this->db->query($sql);			
		$deptos_disponibles= $query->num_rows();

		$dato['deptos_disp']=$deptos_disponibles;


///CANTIDAD DE DUPLEX
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('idTipoInmueble','3');	
		$query=$this->db->get();			
		$cant_duplex= $query->num_rows();		

		$dato['duplex']=$cant_duplex;		

///CANTIDAD DE DUPLEX ALQUILADOS
		$this->db->select('idInmueble');
		$this->db->from('alquileres');	
  		$st="estado_contrato <>'RESCINDIDO' and estado_contrato <>'FINALIZADO'";
  		$this->db->where($st, NULL, FALSE); 	
		$query = $this->db->get();

			foreach ($query->result() as $row) {
				$idI=$row->idInmueble;
				$idI_A[$idI]=$idI;
			}
			$duplex_alquilados=0;
			$this->db->select('idInmueble,idTipoInmueble');
			$this->db->from('inmuebles');
			$query = $this->db->get();

			$hay_duplex_alquilados= $query->num_rows();
			//if($hay_duplex_alquilados>0){
				foreach ($query->result() as $row) {
					$idI_I=$row->idInmueble;
					$idTI=$row->idTipoInmueble;
					if(isset($idI_A[$idI_I]) and $idTI == '3'){
						$duplex_alquilados=$duplex_alquilados+1;
					}
				}

			//}	


		$dato['duplex_alqui']=$duplex_alquilados;

///DUPLEX DISPONIBLES
		$sql="SELECT * FROM inmuebles  WHERE idTipoInmueble=3 AND (estado=0)";		
		$query=$this->db->query($sql);			
		$duplex_disponibles= $query->num_rows();

		$dato['duplex_disp']=$duplex_disponibles;			

///CANTIDAD DE MONOAMBIENTES
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('idTipoInmueble','4');	
		$query=$this->db->get();			
		$cant_duplex= $query->num_rows();			

		$dato['mono']=$cant_duplex;	

///CANTIDAD DE MONOAMBIENTES ALQUILADOS
		$this->db->select('idInmueble');
		$this->db->from('alquileres');	
  		$st="estado_contrato<>'FINALIZADO' and estado_contrato<>'RESCINDIDO'";
  		$this->db->where($st, NULL, FALSE); 
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$idI_A[$idI]=$idI;
		}
		$mono_alquilados=0;
		$this->db->select('idInmueble,idTipoInmueble');
		$this->db->from('inmuebles');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI_I=$row->idInmueble;
			$idTI=$row->idTipoInmueble;
			if(isset($idI_A[$idI_I]) and $idTI == '4'){
				$mono_alquilados=$mono_alquilados+1;
			}
		}

		$dato['mono_alqui']=$mono_alquilados;	

///MONOAMBIENTES DISPONIBLES
		$sql="SELECT * FROM inmuebles  WHERE idTipoInmueble=4 AND (estado=0)";		
		$query=$this->db->query($sql);			
		$mono_disponibles= $query->num_rows();

		$dato['mono_disp']=$mono_disponibles;					

///CANTIDAD DE CASAS
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('idTipoInmueble','2');		

		$query=$this->db->get();			
		$cant_casas= $query->num_rows();

		$dato['casas']=$cant_casas;		

///CANTIDAD DE CASAS ALQUILADOS
		$this->db->select('idInmueble');
		$this->db->from('alquileres');	
  		$st="estado_contrato<>'FINALIZADO' and estado_contrato<>'RESCINDIDO'";
  		$this->db->where($st, NULL, FALSE); 
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$idI_A[$idI]=$idI;
		}
		$casas_alquilados=0;
		$this->db->select('idInmueble,idTipoInmueble');
		$this->db->from('inmuebles');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI_I=$row->idInmueble;
			$idTI=$row->idTipoInmueble;
			if(isset($idI_A[$idI_I]) and $idTI == '2'){
				$casas_alquilados=$casas_alquilados+1;
			}
		}
		$dato['casas_alqui']=$casas_alquilados;

///CASAS DISPONIBLES
		$sql="SELECT * FROM inmuebles  WHERE idTipoInmueble=2 AND (estado=0)";		
		$query=$this->db->query($sql);			
		$casas_disponibles= $query->num_rows();

		$dato['casas_disp']=$casas_disponibles;	



///CANTIDAD DE COCHERAS COMERCIALES
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('idTipoInmueble','10');	
		$query=$this->db->get();			
		$cant_cocheras= $query->num_rows();			

		$dato['cocheras']=$cant_cocheras;			

///CANTIDAD DE COCHERAS ALQUILADOS
		$this->db->select('idInmueble');
		$this->db->from('alquileres');	
  		$st="estado_contrato<>'FINALIZADO' and estado_contrato<>'RESCINDIDO'";
  		$this->db->where($st, NULL, FALSE); 
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$idI_A[$idI]=$idI;
		}
		$cocheras_alquilados=0;
		$this->db->select('idInmueble,idTipoInmueble');
		$this->db->from('inmuebles');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI_I=$row->idInmueble;
			$idTI=$row->idTipoInmueble;
			if(isset($idI_A[$idI_I]) and $idTI == '10'){
				$cocheras_alquilados=$cocheras_alquilados+1;
			}
		}

		$dato['cocheras_alqui']=$cocheras_alquilados;			

///COCHERAS DISPONIBLES
		$sql="SELECT * FROM inmuebles  WHERE idTipoInmueble=10 AND (estado=0)";		
		$query=$this->db->query($sql);			
		$cocheras_disponibles= $query->num_rows();

		$dato['cocheras_disp']=$cocheras_disponibles;	




///CANTIDAD DE LOCALES COMERCIALES
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('idTipoInmueble','5');	
		$query=$this->db->get();			
		$cant_local= $query->num_rows();			

		$dato['local']=$cant_local;			

///CANTIDAD DE LOCALES ALQUILADOS
		$this->db->select('idInmueble');
		$this->db->from('alquileres');	
  		$st="estado_contrato<>'FINALIZADO' and estado_contrato<>'RESCINDIDO'";
  		$this->db->where($st, NULL, FALSE); 
		$query = $this->db->get();
		foreach ($query->result() as $row){
			$idI=$row->idInmueble;
			$idI_A[$idI]=$idI;
		}
		$local_alquilados=0;
		$this->db->select('idInmueble,idTipoInmueble');
		$this->db->from('inmuebles');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI_I=$row->idInmueble;
			$idTI=$row->idTipoInmueble;
			if(isset($idI_A[$idI_I]) and $idTI == '5'){
				$local_alquilados=$local_alquilados+1;
			}
		}

		$dato['local_alqui']=$local_alquilados;			

///LOCALES DISPONIBLES
		$sql="SELECT * FROM inmuebles  WHERE idTipoInmueble=5 AND (estado=0)";		
		$query=$this->db->query($sql);			
		$local_disponibles= $query->num_rows();

		$dato['local_disp']=$local_disponibles;	


///CANTIDAD DE OFICINAS
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('idTipoInmueble','11');	
		$query=$this->db->get();			
		$cant_oficinas= $query->num_rows();			

		$dato['oficinas']=$cant_oficinas;	

///CANTIDAD DE OFICINAS ALQUILADOS
		$this->db->select('idInmueble');
		$this->db->from('alquileres');	
  		$st="estado_contrato<>'FINALIZADO' and estado_contrato<>'RESCINDIDO'";
  		$this->db->where($st, NULL, FALSE); 
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$idI_A[$idI]=$idI;
		}
		$oficinas_alquilados=0;
		$this->db->select('idInmueble,idTipoInmueble');
		$this->db->from('inmuebles');
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$idI_I=$row->idInmueble;
			$idTI=$row->idTipoInmueble;
			if(isset($idI_A[$idI_I]) and $idTI == '11'){
				$oficinas_alquilados=$oficinas_alquilados+1;
			}
		}
		$dato['oficinas_alqui']=$oficinas_alquilados;	

///OFICINAS DISPONIBLES
		$sql="SELECT * FROM inmuebles  WHERE idTipoInmueble=11 AND (estado=0)";		
		$query=$this->db->query($sql);			
		$oficinas_disponibles= $query->num_rows();

		$dato['oficinas_disp']=$oficinas_disponibles;					

///CANTIDAD DE INMUBLES
		$this->db->select('*');
		$this->db->from('inmuebles');

		$query=$this->db->get();			
		$inmu= $query->num_rows();			

		$dato['inmu']=$inmu;

///INMUEBLES DISPONIBLES
		$this->db->select('*');
		$this->db->from('inmuebles');			
		$this->db->where('estado',0);
		$this->db->or_where('estado',5);	
		$this->db->or_where('estado',7);		
		$query=$this->db->get();			
		$inmuebles_disponibles= $query->num_rows();

		$dato['inmu_disp']=$inmuebles_disponibles;

///INQUILINOS CON DEUDA			
		setlocale (LC_ALL,"es_RA");
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$hoy=date('Y-m-d');

		/*$this->db->select('locatario1');
		$this->db->from('alquileres');	
		$this->db->where('proxVenc <',$hoy);
		$this->db->where('estado_contrato <','VIGENTE');*/
		$sql="select * from alquileres where proxVenc < '$hoy' and (estado_contrato='VIGENTE' or estado_contrato='VIG.RESCINDE')";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {			
					$dni=$row->locatario1;
					$idC=$row->idContrato;
					$nombre_locatario=$this->buscar_datos_model->buscar_persona($dni);
					$Locador_datos[$idC] = $nombre_locatario;
			}
		}else{
			$idC=1;
			$Locador_datos[$idC]="";	
		}		
		$dato['deudaalquiler']=$Locador_datos;


///CANTIDAD DE ALQUILERES CON DEUDA	

		/*$this->db->select('*');
		$this->db->from('alquileres');		
		$this->db->where('proxVenc <',$hoy);
		$this->db->where('estado_contrato <','VIGENTE');*/	

		$sql="select * from alquileres where proxVenc < '$hoy' and (estado_contrato='VIGENTE' or estado_contrato='VIG.RESCINDE')";	
		$db_results = $this->db->query($sql);
		$results = $db_results->result();
		$deuda = $db_results->num_rows();

		$dato['deuda']=$deuda;
		


//busco liquidaciones pendientes de pago a propietarios
		$this->db->select('locador');
		$this->db->from('pagos');
		$this->db->where('pagado_propietario','NO');
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado
		$this->db->distinct('locador');
		$query = $this->db->get();	

		$n= $query->num_rows();	
		$i=0;
		$j=0;


		foreach ($query->result() as $row) {			
				$locador=$row->locador;				
				$nombre=$this->buscar_datos_model->buscar_persona($locador);
				$n=$this->buscar_datos_model->liquidaciones_pendientes($locador);
				$AyN_Locador[$locador] =$nombre.' - <span style="background: red" class="badge badge-pill">'.$n.'</span>';						
		}

		if(isset($AyN_Locador)){
			$dato['propietario']=$AyN_Locador;
		}

/////////////ALQUILERES CON RECLAMOS
		$this->db->select('idReclamo,idContrato,prioridad,estado,problema,locatario1,encargado,fecha_atencion');
		$this->db->from('reclamos');
		$this->db->where('estado','EN ESPERA');
		$this->db->or_where('estado','EN PROCESO');
		$this->db->or_where('estado','PENDIENTE');
		$this->db->order_by('prioridad');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$idR=$row->idReclamo;
				$idC=$row->idContrato;
				$idI=$this->buscar_datos_model->buscar_idI($idC);
				$inmueble=$this->buscar_datos_model->buscar_inmueble($idI);
				$prioridad=$row->prioridad;
				$estado=$row->estado;
				$problema=$row->problema;
				$locatario=$row->locatario1;	
				$encargado=$row->encargado;
				$fecha=$row->fecha_atencion;

				$fecha_atencion=substr($fecha, 0,10);

				if($prioridad=="ALTA") $prioridad='<span style="background: red"  class="badge badge-pill">'.$prioridad.'</span>';		
				if($prioridad=="MEDIA") $prioridad='<span style="background: #ff5100" class="badge badge-pill">'.$prioridad.'</span>';	
				if($prioridad=="BAJA") $prioridad='<span style="background: #ffb602" class="badge badge-pill">'.$prioridad.'</span>';

				if($estado=="PENDIENTE"){
					$datos_reclamo[$idC]='<a id="'.$idR.'" style="color:black" href="javascript:void(0);" onclick="atender_reclamos(this.id)"><span style="background: #FF5100" class="badge badge-pill">'.$idR.'</span> '.$inmueble.' - '.$prioridad.'</a><br>'.$locatario.' - '.$encargado;
				}elseif ($estado=="EN PROCESO") {
					$datos_reclamo[$idC]='<a id="'.$idR.'" style="color:black" href="javascript:void(0);" onclick="atender_reclamos(this.id)"><span style="background: #10af00" class="badge badge-pill" id="'.$idR.'">'.$idR.'</span> '.$inmueble.' - '.$prioridad.'</a><br>'.$locatario.' - '.$encargado;
				}
				$reclamo_desc[$idC]=$problema;

			}
			$dato['reclamos']=$datos_reclamo;
			$dato['descripcion']=$reclamo_desc;
		}
			

/////////ALQUILERES PROXIMOS A VENCER////////////
		$contratosporvencer=$this->buscar_datos_model->contratos_por_vencer();

		$cant_pagos=$this->buscar_datos_model->cantidad_periodos_pagos();				

		//$dato['cont_por_vencer']=$contratosporvencer;

		$cantidad_pagos_alquiler=$this->buscar_datos_model->cantidad_periodos_pagos();

		$dato['alquileresxvencer']=$contratosporvencer;


/////INQUILINOS QUE RENUEVAN

$inquilinos_renuevan=$this->buscar_datos_model->inquilinos_renuevan();
$dato['inqui_renuevan']=$inquilinos_renuevan;



		////////////////RESERVAS///////////////////
		$cant_reservas=$this->buscar_datos_model->cant_reservas();
		$dato['cant_reservas']=$cant_reservas;

		$this->db->select('idReserva,idInmueble,apellidoyNombre,telefono');
		$this->db->from('reservas');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row) {
				$idR=$row->idReserva;
				$idI=$row->idInmueble;
				$interesado=$row->apellidoyNombre;
				$tel=$row->telefono;

				$inmueble=$this->buscar_datos_model->buscar_inmueble($idI);

				$datos_reserva[$idR]='<span style="background: #424242" class="badge badge-pill">'.$idI.'</span> '.$inmueble.' - '.$interesado;	
			}	
			$dato['reservas']=$datos_reserva;		
		}

		$this->load->view('main',$dato);
	}

	public function inmueblesDisponibles($id){
		echo json_encode($this->buscar_datos_model->inmueblesDisponibles($id));
		//return $this->buscar_datos_model->inmueblesDisponibles($id);
	}	

	public function getCaracteristicasInmuebles($idI){
		return $this->buscar_datos_model->getCaracteristicasInmuebles($idI);
	}

	public function getRequisitosAlquilar(){
		return $this->buscar_datos_model->getRequisitosAlquilar();
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