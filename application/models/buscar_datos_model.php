<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class buscar_datos_model extends CI_Model{

	public $variable;

	public function __construct(){
		parent::__construct();
		
	}

	public function nuevoReclamo(){
		$this->load->library('session');
		$idC=$_POST['idC'];
		$locatario=$_POST['locatario'];
		$locador=$_POST['locador'];
		$telefono=$_POST['telefono'];
		$especialidad=$_POST['especialidad'];
		$problema=$_POST['problema'];		
		$prioridad=$_POST['prioridad'];

		$fechaReclamo=date('d/m/Y G:i');
		$estado="PENDIENTE";

		$session= $this->session->userdata('usuario');
		$usuario=$session[1];

		$data=array(
			'idContrato'=> $idC,
			'locador'=>$locador,
			'locatario1'=>$locatario,
			'tipoProblema'=>$especialidad,
			'problema'=>$problema,
			'prioridad'=>$prioridad,
			'fechaReclamo'=>$fechaReclamo,	
			'usuario'=>$usuario,		
			'estado'=>$estado,
			'telefono'=>$telefono

		);
		$this->db->insert('reclamos',$data);

		return true;
	}

	public function getEspecialidad(){
	    $type = $this->db->query( "SHOW COLUMNS FROM reclamos WHERE Field = 'tipoProblema'" )->row( 0 )->Type;
	    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
	    $enum = explode("','", $matches[1]);
	    return $enum;

	}

	public function buscarInmueble($consultaBusqueda){//DNI
		$sql="select * from alquileres where locatario1=".$consultaBusqueda;
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) {
			$idC=$row->idContrato;
			$idI=$row->idInmueble;
			$locador=$row->locador;
		}
		if(isset($idI)){
			$propietario=$this->buscar_persona($locador);
			$direccion=$this->buscar_inmueble($idI);
			$datos['direccion']=$direccion;
			$datos['locador']=$propietario;
			$datos['idC']=$idC;

			return $datos;
		}
		
	}

		public function buscarPersonas($consultaBusqueda){
			$mensaje="";
			if ($consultaBusqueda<>"") {
				$sql = "select apellidoNombre,dni from personas where apellidoNombre LIKE '$consultaBusqueda%'";
					
				$query = $this->db->query($sql);
				
				foreach ($query->result() as $row) {
					$nombre=$row->apellidoNombre;
					$dni=$row->dni;
					$telefono=$this->buscar_telefono_locatario($dni);	

					$mensaje .= '
					<a href="#" class="a" id="'.$dni.'" telefono="'.$telefono.'">' .$nombre .'</a> - '. $dni . '<br>';
				}
				echo $mensaje;
			}			
		}	

	public function update_reclamo(){
		$idR=$_POST['idR'];
		$problema=$_POST['problema'];
		$tecnico=$_POST['tecnico'];
		$descripcion=$_POST['descripcion'];
		$costo=$_POST['costo'];
		if($costo =="")$costo=0;
		$para=$_POST['para'];
		$paga=$_POST['paga'];		
		$finaliza=$_POST['finaliza'];

		if($finaliza=="NO"){
			$estado="EN PROCESO";
		}elseif($finaliza=="SI"){
			$estado="FINALIZADO";
		}elseif($finaliza=="ANULAR"){
			$estado="ANULADO";
		}
		
		/*$sql="update reclamos set problema=".$problema.", encargado=".$tecnico.", descripcion=".$descripcion.", dinero_dado=".$costo.", dinero_desc=".$para.", quien_paga=".$paga.", finalizar=".$finaliza." where idReclamo=".$idR;*/
		$fecha_atencion=date('d/m/Y G:i');

		$this->db->set('problema',$problema);
		$this->db->set('encargado',$tecnico);
		$this->db->set('descripcion',$descripcion);
		$this->db->set('dinero_dado',$costo);
		$this->db->set('dinero_desc',$para);
		$this->db->set('quien_paga',$paga);
		$this->db->set('finalizar',$finaliza);
		$this->db->set('estado',$estado);
		$this->db->set('fecha_atencion',$fecha_atencion);
		$this->db->where('idReclamo',$idR);
		$this->db->update('reclamos');
		
		return true;
	}

	public function getTecnicos(){
		$sql="select * from tecnicos";
		$query=$this->db->query($sql);
		return $query->result();		
	}

	public function buscar_reclamo($idR){
		$sql="select * from reclamos where idReclamo=".$idR;
		$query=$this->db->query($sql);
		return $query->result();
	}

	public function liquidaciones_pendientes($locador){
		$sql="select * from pagos where pagado_propietario='NO' and locador=".$locador;
		$query=$this->db->query($sql);
		 return $query->num_rows();
	}


	public function guardar_requisitos(){
		$editor_data=$_POST['content'];
		$this->db->set('requisitos',$editor_data);
		$this->db->update('requisitos');
		return true;
	}

	public function getRequisitosAlquilar(){
		$data="";
		$sql="select * from requisitos";
		$query=$this->db->query($sql);
		foreach ($query->result() as $row) {
			$requisitos=$row->requisitos;
		}

		echo $requisitos;
	}


	public function getCaracteristicasInmuebles($idI){
		$data="";
		$sql="select caracteristicas,caract_adicional,valor, observaciones from inmuebles where idInmueble=".$idI;
		$query=$this->db->query($sql);

		foreach ($query->result() as $row) {
			$inmueble=$this->buscar_inmueble($idI);
			$valor=$row->valor;
			$data.="<h4>".$inmueble."- $".$valor."</h4><b>Caracteristicas</b><p>".$row->caracteristicas."</p><b>Otros</b><br><p>".$row->caract_adicional."</p><b>Observaciones</b><br><p>".$row->observaciones."</p>";
		}
		echo $data;
		
	}

	public function inmueblesDisponibles($id){
		/*$data="";
$data.="<table id='tablaDisponibles' class='table table-hover' cellspacing='15' cellpadding='15' width='100%''><thead><tr><th>ID</th><th>Dirección</th><th>Edificio</th><th>Barrio</th><th>Cant.Dor</th><th>Cochera</th><th>Valor</th></tr></thead><tbody>";*/

	//$sql="select * from inmuebles where estado=0";

	$sql="select * from inmuebles";

	if($id=="registrados"){
		$sql="select * from inmuebles";
		$tipo="Inmuebles Registrados";			
	}	

	if($id=="disponibles"){
		$sql.=" where estado = 0";	
		$tipo="Inmuebles Disponibles";		
	}

	if($id=="deptos"){
		$sql.=" where idTipoInmueble = 1";	
		$tipo="Departamentos Registrados";		
	}	

	if($id=="1"){
		$sql.=" where estado = 0 and idTipoInmueble=".$id;
		$tipo="Departamentos Disponibles";
	}

	if($id=="monos"){
		$sql.=" where idTipoInmueble = 4";	
		$tipo="Monoambientes Registrados";		
	}

	if($id=="4"){
		$sql.=" where estado = 0 and idTipoInmueble=".$id;
		$tipo="Monoambientes Disponibles";
	}

	if($id=="duplex"){
		$sql.=" where idTipoInmueble = 3";	
		$tipo="Dúplex Registrados";		
	}

	if($id=="3"){
		$sql.=" where estado = 0 and idTipoInmueble=".$id;
		$tipo="Dúplex Disponibles";
	}

	if($id=="casas"){
		$sql.=" where idTipoInmueble = 2";	
		$tipo="Casas Registradas";		
	}

	if($id=="2"){
		$sql.=" where estado = 0 and idTipoInmueble=".$id;
		$tipo="Casas Disponibles";
	}

	if($id=="cocheras"){
		$sql.=" where idTipoInmueble = 10";	
		$tipo="Cocheras Registradas";		
	}

	if($id=="10"){
		$sql.=" where estado = 0 and idTipoInmueble=".$id;
		$tipo="Cocheras Disponibles";
	}

	if($id=="locales"){
		$sql.=" where idTipoInmueble = 5";	
		$tipo="Locales Comerciales Registrados";		
	}

	if($id=="5"){
		$sql.=" where estado = 0 and idTipoInmueble=".$id;
		$tipo="Locales Comerciales Disponibles";
	}	

	if($id=="oficinas"){
		$sql.=" where idTipoInmueble = 11";	
		$tipo="Oficinas Registradas";		
	}

	if($id=="11"){
		$sql.=" where estado = 0 and idTipoInmueble=".$id;
		$tipo="Oficinas Disponibles";
	}
		$query=$this->db->query($sql);
		 if ($query->num_rows() > 0) {
		 	$cantidad=$query->num_rows();
			foreach ($query->result() as $row) {
				$idI=$row->idInmueble;
				$inmueble=$this->buscar_inmueble($idI);
				$idB=$row->idBarrio;
				$barrio=$this->buscar_barrio($idB);
				$cant_dorm=$row->cant_dorm;
				$cochera=$row->cochera;
				$valor=$row->valor;
				$idE=$row->idEdificio;
				$edificio=$this->buscar_edificio($idE);
				$caract=$row->caract_adicional;
				$estado=$row->estado;
				$operacion=$row->operacion;
				$locador=$row->dni;

				$propietario=$this->buscar_persona($locador);
				$idTI=$row->idTipoInmueble;	

			
					

				if(!empty($edificio['edificio'])){
					$nombre_edficio=$edificio['edificio'];
				}else{
					$nombre_edficio="-";
				}



				$data[$idI] = array('idI' =>$idI ,'inmueble'=>$inmueble,'edificio'=>$nombre_edficio,'barrio'=>$barrio,'dorm'=>$cant_dorm,'cochera'=>$cochera,'valor'=>$valor,'tipo'=>$tipo, 'caract'=>$caract, 'cantidad'=>$cantidad,'estado'=>$estado,'operacion'=>$operacion,'locador'=>$propietario);

				//$data.='<tr><td>'.$idI.'</td><td>'.$inmueble.'</td><td>'.$nombre_edficio.'</td><td>'.$barrio.'</td><td>'.$cant_dorm.'</td><td>'.$cochera.'</td><td align=right>'.$valor.'</td></tr>';
			}
			//$data.='</tbody></table>';
			
			//echo $sql;
		}else{
			//$data.="<tr><td><b>Sin Disponibilidad</b></td></tr>";
		}	
		return $data;
	}

	public function es_pago_anulado($idP){
		$this->db->select('anulado');
		$this->db->from('pagos');
		$this->db->where('idpago',$idP);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$anulado=$row->anulado;
		}
		return $anulado;
	}

	public function prox_venc($idC){
		$this->db->select('prox_venc_sig');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		if($query->num_rows()>0){
			foreach ($query->result() as $row) {
				$prox_venc=$row->prox_venc_sig;
			}
		}else{
			$prox_venc=0;
		}	
		return $prox_venc;
	}	

	public function update_cantPagos_proxVenc($idC){

		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$rescision=$row->rescision;
		}
		if($rescision<>1){

			
			$this->db->select('*');
			$this->db->from('alquileres');
			$this->db->where('idContrato',$idC);
			$query=$this->db->get();
			foreach ($query->result() as $row) {
				$cant_pagos=$row->cant_pagos;
				$proxVenc=$row->proxVenc;
				$duracion=$row->duracion;
				$idI=$row->idInmueble;
			}

			$fecha=strtotime ($proxVenc);
			$proxVenc_update = date('Y,m,d',strtotime ( '-1 month' ,$fecha) );

			if($cant_pagos==$duracion){
				$this->db->set('estado',1);
				$this->db->where('idInmueble',$idI);
				$this->db->update('inmuebles');
			}

			if($cant_pagos==$duracion){
				$this->db->set('estado_contrato','VIGENTE');
			}
			$this->db->set('cant_pagos',$cant_pagos-1);		
			$this->db->set('proxVenc',$proxVenc_update);
			
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');

		}	
		return true;
	}	


	public function cancelar_renovacion($idI){
		$estado=$this->buscar_estado_inmueble($idI);

		if($estado==6){ //SI EL ESTADO DEL INMUEBLE ES DISP.RENUEVA PASA A DISPONIBLE
			$this->db->set('estado',0);
		}elseif ($estado==4) { //SI EL ESTADO DEL INMUEBLE ES ALQ.RENUEVA PASA A ALQ.NO.RENUEVA
			$contrato=$this->datos_contrato($idI);
			$idC=$contrato['idC'];

			$this->db->set('estado_contrato',"FINALIZA");
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');
			
			$this->db->set('estado',5);
		}

		$this->db->set('renueva',0);		
		$this->db->where('idInmueble',$idI);
		$this->db->update('inmuebles');
		return true;
	}


	public function cancelar_alquiler(){
		return "SI";
	}
	public function verificar_alquileres($idI){
		$mensaje="";
		$contrato=$this->datos_contrato($idI);
		$idC=$contrato['idC'];
		if($idC){
			$data[0]=$this->buscar_pagos_pendientes($idC);//HAY PAGOS PENDIENTES POR LIQUIDAR:SI/NO
			$data[1]=$this->debe_comision($idC);//VALOR

		}else{
			$data[0]="";
			$data[1]="";
		}		

		return $data;

	}

	public function debe_comision($idC){
		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$debe_comision=$row->comision_inmo_debe;
		}	
		return $debe_comision;			
	}

	public function cancelar_pago($idPago){

		$this->db->set('anulado',1);
		$this->db->set('pagado_propietario',"-");
		$this->db->where('idPago',$idPago);
		$this->db->update('pagos');


		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->where('idPago',$idPago);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$rescision=$row->rescision;
		}	

		
			$idC=$this->buscar_idC_P($idPago);
			$idI=$this->buscar_idI($idC);

			$this->db->select('*');
			$this->db->from('pagos');			
			$this->db->where('pagado_propietario','NO');
			$this->db->where('idPago <>',$idPago);
			$query=$this->db->get();
			if($query->num_rows() > 0){
				$this->db->set('pendientes','SI'); //HAY PAGOS PENDIENTES A PROPIETARIOS
			}

			$this->db->set('estado_contrato','VIGENTE');
			$this->db->set('rescision',0);		
			$this->db->where('idContrato',$idC);
			$this->db->update('alquileres');

			$this->db->set('estado',1);					
			$this->db->where('idInmueble',$idI);
			$this->db->update('inmuebles');

		return true;		
	}

	public function ultimo_valor_alquiler($idC){
		$this->db->select('valor_alquiler');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$valor=$row->valor_alquiler;
		}	
		return $valor;		
	}

	public function tipo_operacion($idC){
		$this->db->select('operacion');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$operacion=$row->operacion;
		}	
		return $operacion;
	}

	public function personas(){
		//$this->db->select('dni,apellidoNombre,fechaNac');
		$this->db->select('*');
		$this->db->from('personas');
		$query=$this->db->get();

		return $query;
	}

	public function tecnicos(){
		$query = $this->db-> query('SELECT ApellidoyNombre FROM tecnicos');	

	    // si hay resultados
	    if ($query->num_rows() > 0) {
	        // almacenamos en una matriz bidimensional
	        foreach($query->result() as $row)
	           $arrDatos[htmlspecialchars($row->ApellidoyNombre, ENT_QUOTES)] = htmlspecialchars($row->ApellidoyNombre, ENT_QUOTES);

	        $query->free_result();
	        
	        return $arrDatos;
	     }		

	}

	public function edificios(){
		$this->db->select('*');
		$this->db->from('edificios');		
		$query=$this->db->get();
		$cant_registros=$query->num_rows();		
		foreach ($query->result() as $row) {
			$idE=$row->idEdificio;
			$nombre_edificio=$row->descEdificio;

			$edificios[$idE]=$nombre_edificio;
		}
		return $edificios;
	}

	public function imprimir_reporte_caja_diaria($periodo){
			$len=strlen($periodo);			
			
			if($len == 7){
				$filtro_mes=substr($periodo, 0,2); //determino si es diario o mensual
				$ano=substr($periodo, 3,4); //determino si es diario o mensual

					if($filtro_mes=="01" OR $filtro_mes=="02" OR $filtro_mes=="03" OR $filtro_mes=="04" OR $filtro_mes=="05" OR $filtro_mes=="06" OR $filtro_mes=="07" OR $filtro_mes=="08" OR $filtro_mes=="09" OR $filtro_mes=="10" OR $filtro_mes=="11" OR $filtro_mes=="12"){		if($filtro_mes=="01") $mes="ENE";
							if($filtro_mes=="02") $mes="FEB";
							if($filtro_mes=="03") $mes="MAR";
							if($filtro_mes=="04") $mes="ABR";
							if($filtro_mes=="05") $mes="MAY";
							if($filtro_mes=="06") $mes="JUN";
							if($filtro_mes=="07") $mes="JUL";
							if($filtro_mes=="08") $mes="AGO";
							if($filtro_mes=="09") $mes="SET";
							if($filtro_mes=="10") $mes="OCT";
							if($filtro_mes=="11") $mes="NOV";
							if($filtro_mes=="12") $mes="DIC";
									
							$reporte[6]='Período: '.$mes.'.-'.$ano;
					}
				}else{
					$fecha_f=DateTime::createFromFormat('d/m/Y', $periodo);
					$fecha=$fecha_f->format('d-m-Y');
					$reporte[6]='Día: '.$fecha;			
				}
				

			$sumatoria=0;
			$this->db->select('*');
			$this->db->from('pagos');
			$this->db->like('fecha_pago',$periodo);
			$query=$this->db->get();
			$cant_registros=$query->num_rows();
			if($query->num_rows()>0){
				foreach ($query->result() as $row){
					$idP=$row->idpago;
					$dni=$row->locatario1;
					$idC=$row->idContrato;
					$fecha_p=$row->fecha_pago;
					$total=$row->total_pagar;
					$usuario=$row->usuario_creacion;

					$sumatoria=$sumatoria+$total;

					$locatario=$this->buscar_persona($dni);
					$idI=$this->buscar_idI($idC);
					$inmueble=$this->buscar_inmueble($idI);

					$idE=$this->buscar_idE($idI);
					if(isset($idE)){
						$edificio=$this->buscar_edificio($idE);
						$nombre_edificio=$edificio['edificio'];
					}else{
						$nombre_edificio="-";
					} 					

					$reporte_locatario[$idP]=$locatario;
					$reporte_inmueble[$idP]=$inmueble;
					$reporte_fecha[$idP]=$fecha_p;
					$reporte_total[$idP]=number_format($total, 2, ',', '.');
					$reporte_usuario[$idP]=$usuario;					
					$reporte_edificio[$idP]=$nombre_edificio;

					$reporte[0]=$reporte_locatario;
					$reporte[1]=$reporte_inmueble;
					$reporte[2]=$reporte_fecha;
					$reporte[3]=$reporte_total;
					$reporte[4]=$cant_registros;
					$reporte[5]=number_format($sumatoria, 2, ',', '.');
					$reporte[7]=$reporte_usuario;	
					$reporte[8]=$reporte_edificio;										
				}				
				return $reporte;
			}
	}		

	public function buscar_reporte_caja_diaria($buscar){
			$fecha=date('d/m/Y',strtotime($buscar));
			$sumatoria=0;
			$this->db->select('*');
			$this->db->from('pagos');
			$this->db->like('fecha_pago',$fecha);
			$query=$this->db->get();
			$cant_registros=$query->num_rows();
			if($query->num_rows()>0){
				foreach ($query->result() as $row){
					$idP=$row->idpago;
					$dni=$row->locatario1;
					$idC=$row->idContrato;
					$fecha_p=$row->fecha_pago;
					$total=$row->total_pagar;
					$usuario=$row->usuario_creacion;
					$anulado=$row->anulado;

					if($anulado==0){
						$sumatoria=$sumatoria+$total;
					}
					

					$locatario=$this->buscar_persona($dni);
					$idI=$this->buscar_idI($idC);
					$inmueble=$this->buscar_inmueble($idI);

					$idE=$this->buscar_idE($idI);
					if(isset($idE)){
						$edificio=$this->buscar_edificio($idE);
						$nombre_edificio=$edificio['edificio'];
					}else{
						$nombre_edificio="-";
					} 
						
					$reporte_locatario[$idP]=$locatario;
					$reporte_inmueble[$idP]=$inmueble;
					$reporte_fecha[$idP]=$fecha_p;
					$reporte_total[$idP]=number_format($total, 2, ',', '.');
					$reporte_usuario[$idP]=$usuario;
					$reporte_edificio[$idP]=$nombre_edificio;
					$reporte_anulado[$idP]=$anulado;

					$reporte[0]=$reporte_locatario;
					$reporte[1]=$reporte_inmueble;
					$reporte[2]=$reporte_fecha;
					$reporte[3]=$reporte_total;
					$reporte[4]=$cant_registros;
					$reporte[5]=number_format($sumatoria, 2, ',', '.');
					$fecha=date('d-m-Y',strtotime($buscar));
					$reporte[6]=$fecha;
					$reporte[7]=$reporte_usuario;
					$reporte[8]=$reporte_edificio;
					$reporte[9]=$reporte_anulado;
				}
				//return $query->result();		
				return $reporte;
			}
	}


	public function buscar_reporte_caja_mensual($mensual,$ano){		
		if($mensual=="01") $mes="ENE.";
		if($mensual=="02") $mes="FEB.";
		if($mensual=="03") $mes="MAR.";
		if($mensual=="04") $mes="ABR.";
		if($mensual=="05") $mes="MAY.";
		if($mensual=="06") $mes="JUN.";
		if($mensual=="07") $mes="JUL.";
		if($mensual=="08") $mes="AGO.";
		if($mensual=="09") $mes="SEP.";
		if($mensual=="10") $mes="OCT.";
		if($mensual=="11") $mes="NOV.";
		if($mensual=="12") $mes="DIC.";
		$periodo=$mes.'-'.$ano;

		$buscar=$mensual.'/'.$ano;
		$sumatoria=0;
		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->like('fecha_pago',$buscar);		
		$query=$this->db->get();

		$cant_registros=$query->num_rows();
			if($query->num_rows()>0){
				foreach ($query->result() as $row){
					$idP=$row->idpago;
					$dni=$row->locatario1;
					$idC=$row->idContrato;
					$fecha_p=$row->fecha_pago;
					$total=$row->total_pagar;
					$usuario=$row->usuario_creacion;
					$anulado=$row->anulado;

					if($anulado==0){
						$sumatoria=$sumatoria+$total;
					}

					$locatario=$this->buscar_persona($dni);
					$idI=$this->buscar_idI($idC);
					$inmueble=$this->buscar_inmueble($idI);

					$idE=$this->buscar_idE($idI);
					if(isset($idE)){
						$edificio=$this->buscar_edificio($idE);
						$nombre_edificio=$edificio['edificio'];
					}else{
						$nombre_edificio="-";
					} 					

					$reporte_locatario[$idP]=$locatario;
					$reporte_inmueble[$idP]=$inmueble;
					$reporte_fecha[$idP]=$fecha_p;
					$reporte_total[$idP]=number_format($total, 2, ',', '.');
					$reporte_usuario[$idP]=$usuario;
					$reporte_edificio[$idP]=$nombre_edificio;
					$reporte_anulado[$idP]=$anulado;

					$reporte[0]=$reporte_locatario;
					$reporte[1]=$reporte_inmueble;
					$reporte[2]=$reporte_fecha;
					$reporte[3]=$reporte_total;
					$reporte[4]=$cant_registros;
					$reporte[5]=number_format($sumatoria, 2, ',', '.');
					
					$reporte[6]=$periodo;
					$reporte[7]=$reporte_usuario;
					$reporte[8]=$reporte_edificio;
					$reporte[9]=$reporte_anulado;
				}
						
				return $reporte;
			}	
				
	}


	public function buscar_inquilinos_morosos(){
		$hoy=date('Y-m-d');
		$this->db->select('*');
		$this->db->from('alquileres');
		$this->db->where('proxVenc <',$hoy);
		$this->db->where('estado_contrato','VIGENTE');
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$idI=$row->idInmueble;
			$dni_locatario=$row->locatario1;
			$idC=$row->idContrato;

			$ult_pago=$this->buscar_ultimo_pago($idC);



			$direccion_inmueble=$this->buscar_inmueble($idI);
			$nombre=$this->buscar_persona($dni_locatario);
			$telefono=$this->buscar_telefono_locatario($dni_locatario);

			$inmueble[$idI]=$direccion_inmueble;
			$locatario[$idI]=$nombre;
			$contacto[$idI]=$telefono;
			$ultimo_pago[$idI]=$ult_pago;

			$venc_actual=$this->vencimiento_actual();


			$morosos[0]=$inmueble;
			$morosos[1]=$locatario;
			$morosos[2]=$contacto;
			$morosos[3]=$ultimo_pago;	
			$morosos[4]=$venc_actual;	

		}	
		return $morosos;	


	}

	public function buscar_datos_reclamos_individual($idR){
		$this->db->select('*');
		$this->db->from('reclamos');
		$this->db->where('idReclamo',$idR);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$tecnico=$row->encargado;
			
			$idC=$row->idContrato;
			$idR=$row->idReclamo;
			$especialidad=$row->tipoProblema;
			$prioridad_reclamo=$row->prioridad;
			$estado_reclamo=$row->estado;
			$descripcion_reclamo=$row->descripcion;
			$rubro_reclamo=$row->tipoProblema;
			$telefono=$row->telefono;
			
			$dni_locatario=$this->buscar_dni_locatario($idC);

			//$telefono=$this->buscar_telefono_locatario($dni_locatario);


			$idI=$this->buscar_idI($idC);
			$direccion=$this->buscar_inmueble($idI);

			$contrato[$idR]=$direccion;
			$locatario[$idR]=$row->locatario1;
			$problemas[$idR]=$row->problema;
			$contacto[$idR]=$telefono;
			$prioridad[$idR]=$prioridad_reclamo;
			$estado[$idR]=$estado_reclamo;
			$descripcion[$idR]=$descripcion_reclamo;
			$rubro[$idR]=$rubro_reclamo;


			$datos[0]=$rubro;
			$datos[1]=$problemas;
			$datos[2]=$contrato;
			$datos[3]=$locatario;
			$datos[4]=$contacto;
			$datos[5]=$prioridad;
			$datos[6]=$estado;
			$datos[7]=$descripcion;
			$datos[8]=$tecnico;
		}		
		return $datos;
	}












	public function buscar_datos_reclamos($idR){
		$this->db->select('idContrato, encargado');
		$this->db->from('reclamos');
		$this->db->where('idReclamo',$idR);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$tecnico=$row->encargado;
		}

		$this->db->select('*');
		$this->db->from('reclamos');
		$this->db->where('encargado',$tecnico);
		$this->db->where('estado <>','FINALIZADO');
		$this->db->where('estado <>','ANULADO');
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$idC=$row->idContrato;
			$idR=$row->idReclamo;
			$especialidad=$row->tipoProblema;
			$prioridad_reclamo=$row->prioridad;
			$estado_reclamo=$row->estado;
			$descripcion_reclamo=$row->descripcion;
			$rubro_reclamo=$row->tipoProblema;
			$telefono=$row->telefono;
			
			$dni_locatario=$this->buscar_dni_locatario($idC);

			//$telefono=$this->buscar_telefono_locatario($dni_locatario);

			$idI=$this->buscar_idI($idC);
			$direccion=$this->buscar_inmueble($idI);

			$contrato[$idR]=$direccion;
			$locatario[$idR]=$row->locatario1;
			$problemas[$idR]=$row->problema;
			$contacto[$idR]=$telefono;
			$prioridad[$idR]=$prioridad_reclamo;
			$estado[$idR]=$estado_reclamo;
			$descripcion[$idR]=$descripcion_reclamo;
			$rubro[$idR]=$rubro_reclamo;


			$datos[0]=$rubro;
			$datos[1]=$problemas;
			$datos[2]=$contrato;
			$datos[3]=$locatario;
			$datos[4]=$contacto;
			$datos[5]=$prioridad;
			$datos[6]=$estado;
			$datos[7]=$descripcion;
			$datos[8]=$tecnico;
		}		
		return $datos;
	}

	public function buscar_dni_locatario($idC){
		$this->db->select('locatario1');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$dni_locatario=$row->locatario1;	
		}
		return $dni_locatario;
	}

	public function buscar_telefono_locatario($dni_locatario){
		$this->db->select('telefono,celular');
		$this->db->from('personas');
		$this->db->where('dni',$dni_locatario);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$telefono=$row->telefono;	
			$celular=$row->celular;	
		}

		if(isset($telefono) and isset($celular)) return $telefono.' - '.$celular;

		if(!isset($telefono) and isset($celular)) return $celular;		

		if(isset($telefono) and !isset($celular)) return $telefono;

		if(!isset($telefono) and !isset($celular)) return '-';		
		
	}

	public function buscar_fechaFin($idI){
		$this->db->select('fechaFin');
		$this->db->from('alquileres');
		$this->db->where('idInmueble',$idI);
		$this->db->where('estado_contrato <>','RESCINDIDO');
		$this->db->where('estado_contrato <>','FINALIZADO');
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$fecha_fin = $row->fechaFin;
		}
		if(isset($fecha_fin)){
			$fecha_format=date('d-m-Y',strtotime($fecha_fin));
			return '<b style=color:red>'.$fecha_format.'</b>';		
		}else{
			$fecha_fin="<b style='color:green'>DISPONIBLE</b>";
			return $fecha_fin;
		}
	}

	public function buscar_locador_pendientes($idC){
		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('pagado_propietario','NO');
		$query=$this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$locador = $row->locador;
			}

		}else{
			$locador="";
		}
		return $locador;
	}

	public function buscar_pagos_pendientes($idC){
		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('pagado_propietario','NO');
		$query=$this->db->get();
		if($query->num_rows() > 0){
			return "SI"; //HAY PAGOS PENDIENTES A PROPIETARIOS
		}else{
			return "NO";
		}
	}	

	public function vencimiento_actual(){
		$hoy=date('d-m-Y');

		$dia_hoy=substr($hoy, 0,2);

		$mes=substr($hoy, 3,2);

		$ano=substr($hoy, 8,2);

		//if($dia_hoy < $dia){
			if($mes=="01") $mes="ENE.";
			if($mes=="02") $mes="FEB.";
			if($mes=="03") $mes="MAR.";
			if($mes=="04") $mes="ABR.";
			if($mes=="05") $mes="MAY.";
			if($mes=="06") $mes="JUN.";
			if($mes=="07") $mes="JUL.";
			if($mes=="08") $mes="AGO.";
			if($mes=="09") $mes="SEP.";
			if($mes=="10") $mes="OCT.";
			if($mes=="11") $mes="NOV.";
			if($mes=="12") $mes="DIC.";
			$periodo=$mes.'-'.$ano;

		//}else{

			/*$venc_actual = strtotime ( '+1 month' , strtotime ( $hoy ) ) ;
			$venc_actual = date ( 'd-m-Y' , $venc_actual);
			$mes_actual=substr($venc_actual, 3,2);
			$ano=$ano=substr($venc_actual, 8,2);
			if($mes_actual=="01") $mes="ENE.";
			if($mes_actual=="02") $mes="FEB.";
			if($mes_actual=="03") $mes="MAR.";
			if($mes_actual=="04") $mes="ABR.";
			if($mes_actual=="05") $mes="MAY.";
			if($mes_actual=="06") $mes="JUN.";
			if($mes_actual=="07") $mes="JUL.";
			if($mes_actual=="08") $mes="AGO.";
			if($mes_actual=="09") $mes="SEP.";
			if($mes_actual=="10") $mes="OCT.";
			if($mes_actual=="11") $mes="NOV.";
			if($mes_actual=="12") $mes="DIC.";
			$periodo=$mes.'-'.$ano;*/			
		//}
		return $periodo;
	}	

	public function inquilinos_renuevan(){
		$this->db->select('*');
		$this->db->from('inmuebles');
		$this->db->where('renueva <>',0);
		$query=$this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$idI=$row->idInmueble;
				$locatario=$row->renueva;
				$direccion = $row->direccion;
				$piso=$row->piso;			
				$depto=$row->depto;
				$dni_locador=$row->dni;
				$idTI=$row->idTipoInmueble;
				$idE=$row->idEdificio;
				

				$datos_alquiler=$this->datos_contrato($idI);
				$idC=$datos_alquiler['idC'];
				$estado_contrato=$datos_alquiler['estado_contrato'];

				$operacion=$datos_alquiler['operacion'];

				$oper = substr($operacion,0,1);

				if($estado_contrato=="RENUEVA"){
					$badge=$idI.'-'.$idC.$oper;
				}elseif($estado_contrato=="FINALIZADO"){
					$badge=$idI.'-'.$oper;
				}else{
					$datos_inmueble=$this->datos_inmueble($idI);
					$operacion=$datos_inmueble['operacion'];
					$oper = substr($operacion,0,1);
					$badge=$idI.'-'.$oper;
				}
			
					$this->db->select('nombreTipo');
					$this->db->from('tipoinmuebles');
					$this->db->where('idTipoInmueble',$idTI);		
					$query=$this->db->get();								
					foreach ($query->result() as $row){
						$tipoI = $row->nombreTipo;
					}
					if($tipoI=="DEPTO"){
						$tipoI="DEPTO";
					}

					$inquilino=$this->buscar_persona($locatario);
					$locador=$this->buscar_persona($dni_locador);

					if(isset($idE)){
						$this->db->select('direccion');
						$this->db->from('edificios');
						$this->db->where('idEdificio',$idE);
						$query=$this->db->get();
						foreach ($query->result() as $row){
							$direccionE=$row->direccion;
						}



						$direccion_inmueble='<span style="background: #424242" class="badge badge-pill">'.$badge.'</span>  '.$tipoI.' - '.$direccionE.' - '.$piso.' - '.$depto.' - '.$inquilino;			
					}elseif(isset($piso)){	
						$direccion_inmueble='<span style="background: #424242" class="badge badge-pill">'.$badge.'</span> '.$tipoI.' - '.$direccion.' - '.$piso.' - '.$depto.' - '.$inquilino;						
					}else{
						$direccion_inmueble='<span style="background: #424242" class="badge badge-pill">'.$badge.'</span> '.$tipoI.' - '.$direccion.' - '.$inquilino;			
					}
					$inquilinos_renuevan[$idI]=$direccion_inmueble;
			}
		}else{
				$inquilinos_renuevan="";
		}	
		return $inquilinos_renuevan;
	}	


	public function contratos_por_vencer(){
		$this->db->select('idContrato,fechaFin,idInmueble,locatario1,operacion, estado_contrato, rescinde_fecha, cant_pagos');
		$this->db->from('alquileres');
		$this->db->where('estado_contrato','VIGENTE');
		$this->db->or_where('estado_contrato','FINALIZA');
		$this->db->or_where('estado_contrato','RESCINDE');
		$this->db->or_where('estado_contrato','VIG.RESCINDE');		
		$this->db->order_by('fechaFin');
		$query=$this->db->get();
		$i=0;
		$hoy=date('d-m-Y');	
		foreach ($query->result() as $row) {
			$idI=$row->idInmueble;
			$locatario1=$row->locatario1;
			$fechaF=$row->fechaFin;
			$idC=$row->idContrato;
			$operacion=$row->operacion;
			$estado_contrato=$row->estado_contrato;
			$rescinde_fecha=$row->rescinde_fecha;
			$cant_pagos=$row->cant_pagos;
			//$fechaF='31-07-2018';
			$fecha_format=date('d-m-Y',strtotime($fechaF));
			$dif=round(abs(strtotime($fecha_format) - strtotime($hoy))/86400);
			if($cant_pagos>=34 or $estado_contrato=="RESCINDE" or $estado_contrato=="VIG.RESCINDE" or $estado_contrato=="FINALIZA"){
				$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
				$this->db->from('inmuebles');
				$this->db->where('idInmueble',$idI);		
				$query=$this->db->get();								
					foreach ($query->result() as $row){
						$direccion = $row->direccion;
						$piso=$row->piso;			
						$depto=$row->depto;
						$dni_locador=$row->dni;
						$idTI=$row->idTipoInmueble;
						$idE=$row->idEdificio;
					}

				$oper = substr($operacion,0,1);	
					
				$this->db->select('nombreTipo');
				$this->db->from('tipoinmuebles');
				$this->db->where('idTipoInmueble',$idTI);		
				$query=$this->db->get();								
				foreach ($query->result() as $row){
					$tipoI = $row->nombreTipo;
				}
				if($tipoI=="DEPTO"){
					$tipoI="DEPTO";
				}

				if(isset($idE)){
					$this->db->select('direccion');
					$this->db->from('edificios');
					$this->db->where('idEdificio',$idE);
					$query=$this->db->get();
					foreach ($query->result() as $row){
						$direccionE=$row->direccion;
					}
					$direccion_inmueble='<span style="background: #424242" class="badge badge-pill">'.$idC.$oper.'</span>  '.'<span style="background: #FF5100" class="badge badge-pill">Fin: '.$fecha_format.'</span> '.$tipoI.' - '.$direccionE.' - '.$piso.' - '.$depto;			
				}elseif(isset($piso)){	
					$direccion_inmueble='<span style="background: #424242" class="badge badge-pill">'.$idC.$oper.'</span> '.' <span style="background: #FF5100" class="badge badge-pill">Fin: '.$fecha_format.'</span> '.$tipoI.' - '.$direccion.' - '.$piso.' - '.$depto;			
				}else{
					$direccion_inmueble='<span style="background: #424242" class="badge badge-pill">'.$idC.$oper.'</span> '.' <span style="background: #FF5100" class="badge badge-pill">Fin: '.$fecha_format.'</span> '.$tipoI.' - '.$direccion;
				}

				

				if($cant_pagos>=34){
					$estado=$estado_contrato;
				}elseif($estado_contrato=="RESCINDE" OR $estado_contrato=="VIG.RESCINDE"){
					$estado=$estado_contrato.' en '.$rescinde_fecha;
				}elseif ($estado_contrato=="FINALIZA") {
					$estado=$estado_contrato;
				}

				$nombre=$this->buscar_persona($locatario1);
				$fechas_FC[$nombre.' - <b style="color:red;font-size:11px">'.$estado.'</b>']=$direccion_inmueble;
				$i=$i+1;
			}	
		}
		if(isset($fechas_FC)){return $fechas_FC;}
		
	}

	public function formato_fecha ($fecha){
		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");		
		$fecha_temp=str_replace("/", "-", $fecha);

		$fecha_mes = strftime("%b", strtotime($fecha_temp));
		if ($fecha_mes == 'ene') {			
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("ene.", "ENE", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'feb') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("feb.", "FEB", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'mar') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("mar.", "MAR", $fecha_periodo_temp);			
		}elseif ($fecha_mes == 'abr') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("abr.", "ABR", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'may') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("may.", "MAY", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'jun') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("jun.", "JUN", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'jul') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("jul.", "JUL", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'ago') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("ago.", "AGO", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'sep') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("sep.", "SEP", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'oct') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("oct.", "OCT", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'nov') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("nov.", "NOV", $fecha_periodo_temp);
		}elseif ($fecha_mes == 'dic') {
				$fecha_periodo_temp = strftime("%b.-%y", strtotime($fecha_temp));
				$fecha_periodo=str_replace("dic.", "DIC", $fecha_periodo_temp);
		}
		return $fecha_periodo;//va fecha_periodo
	}

	public function importe_a_cobrar($idC){
		$this->db->select('periodo');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);	
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado			
		$query=$this->db->get();
		$cant_pagos=$query->num_rows();		

		$this->db->select('tipo_ajuste,duracion');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$tipo_ajuste=$row->tipo_ajuste;
			$duracion=$row->duracion;
		}

		$operacion=$this->tipo_operacion($idC);

		$this->db->select('*');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$valor1=$row->valor1;
			$valor2=$row->valor2;
			$valor3=$row->valor3;
			$valor4=$row->valor4;
			$valor5=$row->valor5;
			$valor6=$row->valor6;
		}


		if($duracion==24 || $duracion==36){
			if($operacion=="ALQUILER" || $operacion=="COMODATO" || $operacion=="COMERCIAL"){		
					if($tipo_ajuste == "SEMESTRAL"){
						if($cant_pagos <6){
							$n=1;
						}elseif ($cant_pagos <12) {
							$n=2;
						}elseif ($cant_pagos <18) {
							$n=3;
						}elseif ($cant_pagos <24) {
							$n=4;
						}elseif ($cant_pagos <30) {
							$n=5;
						}elseif ($cant_pagos <36) {
							$n=6;
						}
					}elseif ($tipo_ajuste=="OCTOMESTRAL") {
						if($cant_pagos <8){
							$n=1;
						}elseif ($cant_pagos <16) {
							$n=2;
						}elseif ($cant_pagos <24) {
							$n=3;
						}
					}elseif ($tipo_ajuste=="ANUAL") {
						if($cant_pagos <12){
							$n=1;
						}elseif ($cant_pagos <24) {
							$n=2;
						}elseif ($cant_pagos <36) {
							$n=3;
						}
					}else if($tipo_ajuste="SIN AJUSTE"){
						$n=1;
					}
			}	
		}elseif ($duracion<24){

			if($valor2>0 && $valor3>0 && $valor4>0){
				$cant_valores=4;
				$coeficiente_mes=$duracion/$cant_valores;//12/4=3 por ejemplo

				if(($cant_pagos/$coeficiente_mes)<1){
					$n=1;
				}elseif(($cant_pagos/$coeficiente_mes)<2){
					$n=2;
				}elseif(($cant_pagos/$coeficiente_mes)<3){
					$n=3;
				}else{
					$n=4;
				}				

			}elseif ($valor2>0 && $valor3>0){

				$cant_valores=3;
				$coeficiente_mes=$duracion/$cant_valores;

				if(($cant_pagos/$coeficiente_mes)<1){
					$n=1;
				}elseif(($cant_pagos/$coeficiente_mes)<2){
					$n=2;
				}else{
					$n=3;
				}
			}elseif ($valor2>0){

				$cant_valores=2;
				$coeficiente_mes=$duracion/$cant_valores;

				if(($cant_pagos/$coeficiente_mes)<1){
					$n=1;
				}else{
					$n=2;
				}
			}else{
				$n=1;
			}
			
		}		
		return $n;
	}

	public function periodo_locativo($idC){	
		$this->db->select('fechaInicio,fechaFin');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);	
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$fechaI_F['inicio'] = $row->fechaInicio;
			$fechaI_F['fin'] = $row->fechaFin;			
		}
		return $fechaI_F;	
	}

	public function datos_contrato($idI){
		$this->db->select('fechaInicio,fechaFin,duracion,cant_pagos,locatario1,locador,idContrato,punitorio,garante1,operacion,estado_contrato,rescinde_fecha');
		$this->db->from('alquileres');
		$this->db->where('idInmueble',$idI);	
		$query=$this->db->get();
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$fechaF=$row->fechaFin;
				$fecha_format=date('d-m-Y',strtotime($fechaF));
				$datos_alquiler['inicio'] = $row->fechaInicio;
				$datos_alquiler['fin'] = $fecha_format ;
				$datos_alquiler['locatario1']=$row->locatario1;
				$datos_alquiler['locador']=$row->locador;
				$datos_alquiler['idC']=$row->idContrato;
				$datos_alquiler['punitorio']=$row->punitorio;
				$datos_alquiler['garante1']=$row->garante1;
				$datos_alquiler['cant_pagos']=$row->cant_pagos;
				$operacion=$row->operacion;					
				$datos_alquiler['duracion']=$row->duracion;	
				$datos_alquiler['operacion']=$row->operacion;	
				$datos_alquiler['rescinde_fecha']=$row->rescinde_fecha;	
				$datos_alquiler['estado_contrato']=$row->estado_contrato;	
				$datos_alquiler['oper']=substr($operacion, 0,1);

			}
			return $datos_alquiler;	
		}	
	}

	public function nro_de_pago($idP){
		$this->db->select('nro_pago');
		$this->db->from('pagos');
		$this->db->where('idPago',$idP);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$nro_pago=$row->nro_pago;
		}
		if(!isset($nro_pago)){
			$nro_pago="";
		}		
		return $nro_pago;
	}

	public function nro_de_pago_idC($idC){
		$this->db->select('nro_pago');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);//$idP
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$nro_pago=$row->nro_pago;
		}
		if(!isset($nro_pago)){
			$nro_pago="";
		}		
		return $nro_pago;
	}


	

	public function cantidad_pagos($idC){
		$this->db->select('periodo');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado				
		$query=$this->db->get();
		$cant_pagos=$query->num_rows();	

		$operacion=$this->tipo_operacion($idC);

		if($operacion=="ALQUILER" || $operacion=="COMERCIAL"){
			if ($cant_pagos > 5){
				return "SI";
			}
			else{
				return "NO";
			}
		}elseif($operacion=="COMODATO"){
			if ($cant_pagos > 0){
				return "SI";
			}
			else{
				return "NO";
			}			
		}	


	}

	public function cantidad_periodos_pagos(){
		$this->db->select('nro_pago,locatario1,idContrato');
		$this->db->from('pagos');
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$nro_pago=$row->nro_pago;
			$idC=$row->idContrato;
			$locatario1=$row->locatario1;
			if($nro_pago == 24){
				$datos_pago=null;			
			}elseif($nro_pago > 21){
				$datos_pago[$idC]=$locatario1;				
			}
		}

		if(isset($datos_pago)){
			foreach ($datos_pago as $idC => $locatario1) {
				$nombre_locatario1=$this->buscar_persona($locatario1);
				$idI=$this->buscar_idI($idC);
				$inmueble=$this->buscar_inmueble($idI);
				
				$this->db->select('renueva');
				$this->db->from('inmuebles');
				$this->db->where('idInmueble',$idI);
				$query=$this->db->get();
				foreach ($query->result() as $row) {
					$renueva=$row->renueva;	
					$resultado_pagos[$nombre_locatario1]=$idI.'-'.$inmueble.' - '.$renueva;			
				}				
			}
			return $resultado_pagos;
		}	
		
	}

	public function renueva($idI){
		$this->db->select('renueva');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$renueva=$row->renueva;
			return $renueva;
			/*if($renueva <> 0){
				return "RENUEVA";
			}*/
		}
	}

	public function n_pagos($idC){
		$this->db->select('duracion,idInmueble,comision_paga');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$duracion=$row->duracion;
			$idI=$row->idInmueble;
			$comision_paga=$row->comision_paga;
		}

		 $datos['duracion']=$duracion;
		 $datos['idI']=$idI;
		$datos['comision_paga']=$comision_paga;		 

		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);	
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado	
		$query=$this->db->get();	
		$cant_pagos=$query->num_rows();

		if($query->num_rows() > 0){
			$this->db->select('idpago,nro_pago,comision_inmo_debe');
			$this->db->from('pagos');
			$this->db->where('idContrato',$idC);
			$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado		
			$query=$this->db->get();
			$cant_pagos=$query->num_rows();	
			foreach ($query->result() as $row) {
				$idpago=$row->idpago;
				$nro_pago=$row->nro_pago;
				$comision_inmo_debe=$row->comision_inmo_debe;
			}
			
			$datos['pagos']=$cant_pagos;
			$datos['nro_pago']=$nro_pago;
			$datos['idpago']=$idpago;
			$datos['comision_inmo_debe']=$row->comision_inmo_debe;
			
		}else{
			$datos['pagos']="";
			$datos['nro_pago']="";
			$datos['idpago']="";
			$datos['comision_inmo_debe']="";			
		}
		return $datos;			
	}

	public function deuda_ci($idP){
		$this->db->select('comision_inmo_debe');
		$this->db->from('pagos');
		$this->db->where('idPago',$idP);
		$query=$this->db->get();		
		foreach ($query->result() as $row) {
			$comision_inmo_debe=$row->comision_inmo_debe;
		}
		if(!isset($comision_inmo_debe)) $comision_inmo_debe="";
		return $comision_inmo_debe;		
	}

	public function ultimo_pago_ci($idC){
		$this->db->select('comision_inmo_paga');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();		
		foreach ($query->result() as $row) {
			$comision_inmo_paga=$row->comision_inmo_paga;
		}
		if(!isset($comision_inmo_paga)) $comision_inmo_paga="";
		return '   Pago anterior: '.$comision_inmo_paga;			
	}			

	public function buscar_persona($dni){
		$this->db->select('apellidoNombre');
		$this->db->from('personas');		
		$this->db->where('dni',$dni);	

		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$persona = $row->apellidoNombre;
		}
		if(isset($persona)){
			return $persona;
		}else{
			return $persona="";
		}		
		
	}

	public function buscar_idC($idR){
		$this->db->select('idContrato');
		$this->db->from('reclamos');
		$this->db->where('idReclamo',$idR);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idC = $row->idContrato;
		}		
		return $idC;
	}

	public function datos_inmueble($idI){		
		$this->db->select('idInmueble,dni,caracteristicas,caract_adicional,cochera,observaciones,renueva,estado,idBarrio,ubicacion,cant_dorm,condicion,requisitos,valor,operacion');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);	
		$query=$this->db->get();
		foreach ($query->result() as $row){			
			$datos_inmueble['dni']=$row->dni;
			$datos_inmueble['caracteristicas'] = $row->caracteristicas;
			$datos_inmueble['caract_adicional']=$row->caract_adicional;
			$datos_inmueble['cochera'] = $row->cochera;
			$datos_inmueble['observaciones']=$row->observaciones;
			$datos_inmueble['renueva']=$row->renueva;
			$datos_inmueble['estado']=$row->estado;
			$datos_inmueble['idB']=$row->idBarrio;
			$datos_inmueble['ubicacion']=$row->ubicacion;
			$datos_inmueble['dorm']=$row->cant_dorm;
			$datos_inmueble['condicion']=$row->condicion;
			$datos_inmueble['requisitos']=$row->requisitos;
			$datos_inmueble['valor']=$row->valor;
			$datos_inmueble['idI']=$row->idInmueble;
			$datos_inmueble['operacion']=$row->operacion;
		}
		return $datos_inmueble;	
	}

	public function buscar_requisitos(){
		$this->db->select('requisitos');
		$this->db->limit(1);
		$this->db->from('inmuebles');		
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$requisitos=$row->requisitos;
		}
		return $requisitos;			
	}

	public function cantidad_liquidaciones($idC){
		$this->db->select('*');
		$this->db->from('liquidaciones');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		if($query->num_rows() == 23){
		 	$cant_pagos=$query->num_rows();	
		 	return $cant_pagos;
		}
	}

	public function debe_comision_inmo_idL($idL){
		$this->db->select('comision_inmo_debe, comision_inmo_paga');
		$this->db->from('liquidaciones');
		$this->db->where('idLiquida',$idL);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$comision_inmo_debe = $row->comision_inmo_debe;
			$comision_inmo_paga=$row->comision_inmo_paga;
		}
		if(!isset($comision_inmo_debe)){
			$comision_inmo_debe=0.00;
		}

		$comision_propietario[0]=$comision_inmo_debe;
		$comision_propietario[1]=$comision_inmo_paga;
		return $comision_propietario;			
	}	




	public function debe_comision_inmo($idC){
		$this->db->select('comision_inmo_debe');
		$this->db->from('liquidaciones');
		$this->db->where('idContrato',$idC);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$comision_inmo_debe = $row->comision_inmo_debe;
		}
		if(!isset($comision_inmo_debe)){
			$comision_inmo_debe=0;
		}
		return $comision_inmo_debe;			
	}	

	public function se_permite_mascota($idI){
		$idE=$this->buscar_idE($idI);
		if(isset($idE)){
			$this->db->select('mascotas');
			$this->db->from('edificios');
			$this->db->where('idEdificio',$idE);
			$query=$this->db->get();
			foreach ($query->result() as $row) {
				$mascotas=$row->mascotas;
			}						
		}else{
			$mascotas="";
		}
		return $mascotas;		
	}

	public function buscar_caract_inmueble($idI){
		$this->db->select('caracteristicas');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$caract = $row->caracteristicas;
		}
		return $caract;
	}

	public function buscar_idReserva($idI){		
		$this->db->select('idReserva,sena,apellidoyNombre,fecha_creacion');
		$this->db->from('reservas');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();
		if($query->num_rows() > 0){								
			foreach ($query->result() as $row){
				$reserva['idR']=$row->idReserva;
				$reserva['nombre']=$row->apellidoyNombre;
				$reserva['fecha']=$row->fecha_creacion;
				$reserva['sena']=$row->sena;
			}
			return $reserva;
		}	
	}

	public function cant_reservas(){
		$this->db->select('*');
		$this->db->from('reservas');	
		$query=$this->db->get();
		$cant_reservas=$query->num_rows();	
		return $cant_reservas;
	}

	public function buscar_idE($idI){
		$this->db->select('idEdificio');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idE = $row->idEdificio;
		}
		return $idE;
	}

	public function buscar_edificio($idE){
		$this->db->select('descEdificio,direccion,idBarrio');
		$this->db->from('edificios');
		$this->db->where('idEdificio',$idE);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$edificio['edificio'] = $row->descEdificio;
			$edificio['direccion']=$row->direccion;
			$edificio['idB']=$row->idBarrio;
			$idB=$row->idBarrio;
		}

		if(isset($idB)){
				$this->db->select('nombreBarrio');
				$this->db->from('barrios');
				$this->db->where('idBarrio',$idB);		
				$query=$this->db->get();								
				foreach ($query->result() as $row){
					$edificio['barrio'] = $row->nombreBarrio;
				}
			return $edificio;
		}else{
			$edificio['barrio']="";
			
		}
		return $edificio;		
	}

	public function buscar_barrio($idB){
		$this->db->select('nombreBarrio');
		$this->db->from('barrios');
		$this->db->where('idBarrio',$idB);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$barrio = $row->nombreBarrio;
		}		
		return $barrio;
	}

	public function buscar_tipoInmueble($idTI){
		$this->db->select('nombreTipo');
		$this->db->from('tipoinmuebles');
		$this->db->where('idTipoInmueble',$idTI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$tipoI = $row->nombreTipo;
		}		
		return $tipoI;
	}			

	public function buscar_inmueble($idI){
		$this->db->select('idInmueble,direccion,piso,depto,dni,idTipoInmueble,idEdificio');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idI=$row->idInmueble;
			$direccion = $row->direccion;
			$piso=$row->piso;			
			$depto=$row->depto;
			$dni_locador=$row->dni;
			$idTI=$row->idTipoInmueble;
			$idE=$row->idEdificio;
		}
		$this->db->select('nombreTipo');
		$this->db->from('tipoinmuebles');
		$this->db->where('idTipoInmueble',$idTI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$tipoI = $row->nombreTipo;
		}
		/*if($tipoI=="DEPTO"){
			$tipoI="DEPTO";
		}*/

		if(isset($idE)){
			$this->db->select('direccion');
			$this->db->from('edificios');
			$this->db->where('idEdificio',$idE);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$direccionE=$row->direccion;
			}
			$direccion_inmueble=$idI.' - '.$tipoI.' - '.$direccionE.' - '.$piso.' - '.$depto;			
		}elseif(isset($piso)){	
			$direccion_inmueble=$idI.' - '.$tipoI.' - '.$direccion.' - '.$piso.' - '.$depto;			
		}elseif(isset($depto)){	
			$direccion_inmueble=$idI.' - '.$tipoI.' - '.$direccion.' - '.$depto;			
		}else{
			$direccion_inmueble=$idI.' - '.$tipoI.' - '.$direccion;
		}		
		return $direccion_inmueble;
	}


	public function buscar_inmueble_2($idI){
		$this->db->select('direccion,piso,depto,dni,idTipoInmueble,idEdificio');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$direccion = $row->direccion;
			$piso=$row->piso;			
			$depto=$row->depto;
			$dni_locador=$row->dni;
			$idTI=$row->idTipoInmueble;
			$idE=$row->idEdificio;
		}

		if(isset($idE)){
			$this->db->select('direccion');
			$this->db->from('edificios');
			$this->db->where('idEdificio',$idE);
			$query=$this->db->get();
			foreach ($query->result() as $row){
				$direccionE=$row->direccion;
			}
			$direccion_inmueble=$direccionE.' - '.$piso.' - '.$depto;			
		}elseif(isset($piso)){	
			$direccion_inmueble=$direccion.' - '.$piso.' - '.$depto;			
		}elseif(isset($depto)){	
			$direccion_inmueble=$direccion.' - '.$depto;			
		}else{
			$direccion_inmueble=$direccion;
		}		
		return $direccion_inmueble;
	}


	public function buscar_datos_reserva($idI){
		$this->db->select('idReserva,idInmueble,apellidoyNombre,telefono,sena');
		$this->db->from('reservas');
		$this->db->where('idInmueble',$idI);
		$query=$this->db->get();
			foreach ($query->result() as $row){
				$datos_reserva['idI']=$row->idInmueble;
				$datos_reserva['interesado']=$row->apellidoyNombre;
				$datos_reserva['telefono']=$row->telefono;
				$datos_reserva['sena']=$row->sena;
				$datos_reserva['idR']=$row->idReserva;
			}
		if(!empty($datos_reserva)){
			return $datos_reserva;	
		}else{
			$datos_reserva="";
			return $datos_reserva;	
		}		
		
	}

	public function valor_alquiler($idI){
		$this->db->select('valor');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$valor_alquiler=$row->valor;
		}
		return $valor_alquiler;
	}



	public function buscar_idI_R($idR){
		$this->db->select('idInmueble');
		$this->db->from('reservas');
		$this->db->where('idReserva',$idR);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idI = $row->idInmueble;
		}
		return $idI;
	}

	public function buscar_idC_idI($idI){
		$this->db->select('idContrato');
		$this->db->from('alquileres');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idC = $row->idContrato;
		}
		return $idC;
	}	

	public function buscar_estado_inmueble($idI){
		$this->db->select('estado');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$estado = $row->estado;
		}
		return $estado;	
	}

	public function buscar_ajustes_alquiler($idC){
		$this->db->select('valor1,valor2,valor3,valor4,valor5,valor6');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);				
		$query=$this->db->get();
		if($query->num_rows()>0){
			foreach ($query->result() as $row){
					$valor['1']=$row->valor1;
					$valor['2']=$row->valor2;
					$valor['3']=$row->valor3;
					$valor['4']=$row->valor4;
					$valor['5']=$row->valor5;
					$valor['6']=$row->valor6;
				}
				return $valor;	
		}		
	}

	public function buscar_reserva($idI){
		$this->db->select('reserva');
		$this->db->from('inmuebles');
		$this->db->where('idInmueble',$idI);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$reserva=$row->reserva;
		}
		return $reserva;
	}

	public function estado_contrato($idI){
		$this->db->select('estado_contrato');
		$this->db->from('alquileres');
		$this->db->where('idInmueble',$idI);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$estado_C=$row->estado_contrato;
		}
		return $estado_C;
	}

	public function buscar_idI($idC){
		$this->db->select('idInmueble');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idI = $row->idInmueble;
		}
		return $idI;
	}

	public function buscar_periodo($idP){
		$this->db->select('periodo');
		$this->db->from('pagos');
		$this->db->where('idpago',$idP);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$periodo = $row->periodo;
		}
		return $periodo;
	}

	public function buscar_ultimo_pago($idC){
		$this->db->select('periodo');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado		
		$query=$this->db->get();	
		$filas=$query->num_rows();

		if($filas>0){
			foreach ($query->result() as $row){
				$periodo = $row->periodo;
			}
		}else{
			$periodo="";
		}
		return $periodo;
	}	

 	public function comision_inmo_deuda($idC){
	 	$this->db->select('comision_inmo_a_pagar');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$deuda_comision = $row->comision_inmo_a_pagar;
		}
		return $deuda_comision;
 	}

	public function buscar_idC_P($idP){
		$this->db->select('idContrato');
		$this->db->from('pagos');
		$this->db->where('idpago',$idP);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idC = $row->idContrato;
		}
		return $idC;
	}

	public function buscar_idC_L($idL){
		$this->db->select('idContrato');
		$this->db->from('liquidaciones');
		$this->db->where('idLiquida',$idL);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idC = $row->idContrato;
		}
		return $idC;
	}

	public function buscar_idP_L($idL){
		$this->db->select('idPago');
		$this->db->from('liquidaciones');
		$this->db->where('idLiquida',$idL);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$idP = $row->idPago;
		}
		return $idP;
	}	

	public function sellado_contrato($idC){
		$this->db->select('sellado_contrato');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$sellado_contrato = $row->sellado_contrato;
		}
		return $sellado_contrato;		
	}
	

	public function periodo_ajuste($idC){
		$this->db->select('fechaInicio,fechaFin,tipo_ajuste,valor1,valor2,valor3,valor4,valor5,valor6,duracion');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$fechaI=$row->fechaInicio;
			$fechaF=$row->fechaFin;
			$tipoAj=$row->tipo_ajuste;
			$duracion=$row->duracion;
			$valor1=$row->valor1;
			$valor2=$row->valor2;
			$valor3=$row->valor3;
			$valor4=$row->valor4;
			$valor5=$row->valor5;
			$valor6=$row->valor6;
		}
		$cant_valores=0;


		$this->db->select('periodo');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado			
		$query=$this->db->get();
		$cant_pagos=$query->num_rows();	

		$operacion=$this->tipo_operacion($idC);

    if($duracion==24 || $duracion==36){
		if($operacion=="ALQUILER" || $operacion=="COMODATO" || $operacion=="COMERCIAL" ){	
			if($tipoAj=="SEMESTRAL"){
				$ajustes['0']=$tipoAj;			
				if($cant_pagos <=6){
					$fecha= strtotime('6 month',strtotime($fechaI));
					 $fecha= date('Y-m-d',$fecha);				 
					$ajustes['1']=$fecha;
					$ajustes['2']='$'.$valor2;
				}elseif ($cant_pagos <=12) {
					$fecha= strtotime('12 month',strtotime($fechaI));
					$fecha= date('Y-m-d',$fecha);
					$ajustes['1']=$fecha;
					$ajustes['2']='$'.$valor3;
				}elseif ($cant_pagos <=18) {
					$fecha= strtotime('18 month',strtotime($fechaI));
					$fecha= date('Y-m-d',$fecha);
					$ajustes['1']=$fecha;
					$ajustes['2']='$'.$valor4;
				}else{
					 if($duracion == 24){
						$fecha= strtotime('18 month',strtotime($fechaI));
							$fecha= date('Y-m-d',$fecha);
						$ajustes['1']=$fecha;
						$ajustes['2']="Vigente y último";
					}elseif($duracion == 36){
						if ($cant_pagos <=24) {
							$fecha= strtotime('24 month',strtotime($fechaI));
							$fecha= date('Y-m-d',$fecha);
							$ajustes['1']=$fecha;
							$ajustes['2']='$'.$valor5;
						}elseif($cant_pagos <=30){
							$fecha= strtotime('30 month',strtotime($fechaI));
							$fecha= date('Y-m-d',$fecha);
							$ajustes['1']=$fecha;
							$ajustes['2']='$'.$valor6;						
						}else{
							$fecha= strtotime('30 month',strtotime($fechaI));
							$fecha= date('Y-m-d',$fecha);
							$ajustes['1']=$fecha;
							$ajustes['2']="Vigente y último";							
						}
					}	
				}	
			}elseif($tipoAj=="OCTOMESTRAL"){
				$ajustes['0']=$tipoAj;
				if($cant_pagos <=8){
					 $fecha= strtotime('8 month',strtotime($fechaI));
					 $fecha= date('Y-m-d',$fecha);				 
					 $ajustes['1']=$fecha;
					 $ajustes['2']='$'.$valor2;
				}elseif ($cant_pagos <=16) {
					 $fecha= strtotime('16 month',strtotime($fechaI));
					 $fecha= date('Y-m-d',$fecha);
					 $ajustes['1']=$fecha;
					 $ajustes['2']='$'.$valor3;
				}else{
					$fecha= strtotime('16 month',strtotime($fechaI));
					 $fecha= date('Y-m-d',$fecha);
					$ajustes['1']=$fecha;
					$ajustes['2']="Vigente y último";
				}
			}elseif($tipoAj=="ANUAL"){
				$ajustes['0']=$tipoAj;
				if($cant_pagos <=12){


						if($cant_pagos==12){
							$anterior_pago=$this->anterior_impuestos($idC);
							$ultimo_pago=', Valor anterior: '.$anterior_pago['valor_alquiler'];
						}else{
							$ultimo_pago="";
						}
						


					 $fecha= strtotime('12 month',strtotime($fechaI));
					 $fecha= date('Y-m-d',$fecha);				 
					 $ajustes['1']=$fecha;

					 if($operacion=="ALQUILER"){
					 	$ajustes['2']='A DEFINIR'.$ultimo_pago;
					 }else{
					 	$ajustes['2']='$'.$valor2;
					 }
					 
				}else{

						if ($cant_pagos==24){
							$anterior_pago=$this->anterior_impuestos($idC);
							$ultimo_pago=', Valor anterior: '.$anterior_pago['valor_alquiler'];
						}else{
							$ultimo_pago="";
						}

						if($duracion==24){
							$fecha= strtotime('12 month',strtotime($fechaI));
							$fecha= date('Y-m-d',$fecha);
							$ajustes['1']=$fecha;
							$ajustes['2']="Vigente y último";
						}elseif($duracion==36){
							if($cant_pagos <=24){
								$fecha= strtotime('24 month',strtotime($fechaI));
								$fecha= date('Y-m-d',$fecha);
								$ajustes['1']=$fecha;
								$ajustes['2']='A DEFINIR'.$ultimo_pago;
							}else{
								$fecha= strtotime('24 month',strtotime($fechaI));
								$fecha= date('Y-m-d',$fecha);
								$ajustes['1']=$fecha;
								$ajustes['2']="Vigente y último";
							}
						}	
					}	
				}else if($tipoAj=="SIN AJUSTE"){
					$ajustes['0']=$tipoAj;
					$ajustes['1']="";
					$ajustes['2']="-";
				}
		}		
	}elseif ($duracion < 24 && $operacion=="COMODATO") {
			if($valor2>0 && $valor3>0 && $valor4>0){

				$cant_valores=4;
				$coeficiente_mes=$duracion/$cant_valores;//4if(($cant_pagos/$coeficiente_mes)<1){
				$ajustes['0']=$this->buscar_ajuste($coeficiente_mes);

				if(($cant_pagos/$coeficiente_mes)<=1){
					$fecha= strtotime($coeficiente_mes.'month',strtotime($fechaI));
					$fecha= date('Y-m-d',$fecha);						
					$ajustes['1']=$fecha;
					$ajustes['2']='$'.$valor2;					
				}elseif (($cant_pagos/$coeficiente_mes)<=2){
					$fecha= strtotime(($coeficiente_mes*2).'month',strtotime($fechaI));
					$fecha= date('Y-m-d',$fecha);						
					$ajustes['1']=$fecha;
					$ajustes['2']='$'.$valor3;	
				}elseif (($cant_pagos/$coeficiente_mes)<=3){
					$fecha= strtotime(($coeficiente_mes*3).'month',strtotime($fechaI));
					$fecha= date('Y-m-d',$fecha);						
					$ajustes['1']=$fecha;
					$ajustes['2']='$'.$valor4;
				}			

			}elseif($valor1>0 && $valor2>0 && $valor3>0){

				$cant_valores=3;
				$coeficiente_mes=$duracion/$cant_valores;

				if(($cant_pagos/$coeficiente_mes)<=1){

						$ajustes['0']=$this->buscar_ajuste($coeficiente_mes);
						$fecha= strtotime($coeficiente_mes.'month',strtotime($fechaI));
						$fecha= date('Y-m-d',$fecha);				 
						$ajustes['1']=$fecha;
						$ajustes['2']='$'.$valor2;					
				}elseif (($cant_pagos/$coeficiente_mes)<=2) {
						$ajustes['0']=$this->buscar_ajuste($coeficiente_mes);
						$fecha= strtotime(($coeficiente_mes*2).'month',strtotime($fechaI));
						$fecha= date('Y-m-d',$fecha);				 
						$ajustes['1']=$fecha;
						$ajustes['2']='$'.$valor3;	
				}else{
						$ajustes['0']=$this->buscar_ajuste($coeficiente_mes);
						$fecha= strtotime(($coeficiente_mes*2).'month',strtotime($fechaI));
						$fecha= date('Y-m-d',$fecha);				 
						$ajustes['1']=$fecha;
						$ajustes['2']='$'.$valor3;					
				}				

			}elseif($valor1>0 && $valor2>0){

				$cant_valores=2;
				$coeficiente_mes=$duracion/$cant_valores;
				$this->buscar_ajuste($coeficiente_mes);

				if(($cant_pagos/$coeficiente_mes<=2)){
					$ajustes['0']=$this->buscar_ajuste($coeficiente_mes);
					$fecha= strtotime($coeficiente_mes.'month',strtotime($fechaI));
					$fecha= date('Y-m-d',$fecha);				 
					$ajustes['1']=$fecha;
					$ajustes['2']='$'.$valor2;					
				}

			}elseif($valor1>0){
				$cant_valores=1;
				$coeficiente_mes=$duracion/$cant_valores;
				$this->buscar_ajuste($coeficiente_mes);
				
					$ajustes['0']=$this->buscar_ajuste($coeficiente_mes);
					$fecha= strtotime($coeficiente_mes.'month',strtotime($fechaI));
					$fecha= date('Y-m-d',$fecha);				 
					$ajustes['1']="";
					$ajustes['2']="-";					
								
			}
		
		}	
		
		return $ajustes;
	}

	public function buscar_ajuste($coeficiente_mes){
		if($coeficiente_mes==12){
			return "ANUAL";
		}elseif($coeficiente_mes==2){
			return "BIMESTRAL";
		}elseif($coeficiente_mes==3){
			return "TRIMESTRAL";
		}elseif($coeficiente_mes==4){
			return "CUATRIMESTRAL";
		}elseif($coeficiente_mes==5){
			return "QUINQUEMESTRE";
		}elseif($coeficiente_mes==6){
			return "SEMESTRAL";
		}

	}

	public function eliminar_reserva($id){
				$this->db->select('idReserva');
				$this->db->from('reservas');
				$this->db->where('idInmueble',$id);
				$query=$this->db->get();
				if($query->num_rows() > 0){
					foreach ($query->result() as $row){
						$idR=$row->idReserva;
					}
				}
				$this->db->where('idReserva',$idR);
				$this->db->delete('reservas');
				return true;		
	}

	public function cant_total_pagos($idC){
		$this->db->select('*');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('pagado_propietario','SI');
		$query=$this->db->get();
		if ($query->num_rows() == 24) {
			return "SI";
		}else{
			return "NO";
		}
	}

	public function ci_debe($idC){
		$this->db->select('comision_inmo_debe');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$ci_debe = $row->comision_inmo_debe;
		}
		if(!isset($ci_debe)){
			$ci_debe="";
		}
		return $ci_debe;
	}

	public function eliminar_alquiler($idC){
		$this->db->where('idContrato',$idC);
		$this->db->delete('alquileres');
		return true;		
	}

	public function anterior_impuestos($idC){
		$this->db->select('valor_alquiler,expensas,expensas_detalle,csp,csp_detalle,impuesto_inmob,inmob_desc,luz,luz_detalle,agua,agua_detalle,exp_extra,exp_extra_detalle,saldos_otros,observaciones,detalle_otros');
		$this->db->from('pagos');
		$this->db->where('idContrato',$idC);
		$this->db->where('anulado',0);// aca verifico que el ultimo pago no este anulado		
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$impuestos['expensas']=$row->expensas;
			$impuestos['expensas_detalle']=$row->expensas_detalle;
			$impuestos['csp']=$row->csp;
			$impuestos['csp_detalle']=$row->csp_detalle;
			$impuestos['impuesto_inmob']=$row->impuesto_inmob;
			$impuestos['inmob_desc']=$row->inmob_desc;			
			$impuestos['luz']=$row->luz;
			$impuestos['luz_detalle']=$row->luz_detalle;						
			$impuestos['agua']=$row->agua;
			$impuestos['agua_detalle']=$row->agua_detalle;
			$impuestos['exp_extra']=$row->exp_extra;
			$impuestos['exp_extra_detalle']=$row->exp_extra_detalle;						
			$impuestos['saldos_otros']=$row->saldos_otros;
			$impuestos['otros_detalle']=$row->detalle_otros;
			$impuestos['observaciones']=$row->observaciones;
			$impuestos['valor_alquiler']=$row->valor_alquiler;

		}
		if(!isset($impuestos)){
			$impuestos['expensas']="";
			$impuestos['expensas_detalle']="";
			$impuestos['csp']="";
			$impuestos['csp_detalle']="";
			$impuestos['impuesto_inmob']="";
			$impuestos['inmob_desc']="";				
			$impuestos['luz']="";
			$impuestos['luz_detalle']="";						
			$impuestos['agua']="";
			$impuestos['agua_detalle']="";	
			$impuestos['exp_extra']="";
			$impuestos['exp_extra_detalle']="";					
			$impuestos['saldos_otros']="";
			$impuestos['otros_detalle']="";				
			$impuestos['observaciones']="";
			$impuestos['valor_alquiler']="";
		}
		return $impuestos;
	}	

	public function impuestos_inquilino($idP){
		$this->db->select('expensas,expensas_detalle,csp,csp_detalle,agua,agua_detalle,impuesto_inmob,inmob_desc,luz,luz_detalle,agua,agua_detalle,saldos_otros,detalle_otros,varios1,varios1_detalle,varios2,varios2_detalle,idContrato');
		$this->db->from('pagos');
		$this->db->where('idpago',$idP);
		$query=$this->db->get();								
		foreach ($query->result() as $row){
			$impuestos['expensas']=$row->expensas;
			$impuestos['expensas_detalle']=$row->expensas_detalle;
			$impuestos['agua']=$row->agua;
			$impuestos['agua_detalle']=$row->agua_detalle;			
			$impuestos['csp']=$row->csp;
			$impuestos['csp_detalle']=$row->csp_detalle;
			$impuestos['impuesto_inmob']=$row->impuesto_inmob;
			$impuestos['inmob_desc']=$row->inmob_desc;			
			$impuestos['luz']=$row->luz;
			$impuestos['luz_detalle']=$row->luz_detalle;						
			$impuestos['agua']=$row->agua;
			$impuestos['agua_detalle']=$row->agua_detalle;			
			$impuestos['saldos_otros']=$row->saldos_otros;
			$impuestos['otros_detalle']=$row->detalle_otros;
			$impuestos['varios1']=$row->varios1;
			$impuestos['varios1_detalle']=$row->varios1_detalle;
			$impuestos['varios2']=$row->varios2;
			$impuestos['varios2_detalle']=$row->varios2_detalle;						
			$impuestos['idC']=$row->idContrato;			
		}

		if(!isset($impuestos)){
			$impuestos['expensas']="";
			$impuestos['expensas_detalle']="";
			$impuestos['agua']="";
			$impuestos['agua_detalle']="";			
			$impuestos['csp']="";
			$impuestos['csp_detalle']="";
			$impuestos['impuesto_inmob']="";
			$impuestos['inmob_desc']="";				
			$impuestos['luz']="";
			$impuestos['luz_detalle']="";						
			$impuestos['agua']="";
			$impuestos['agua_detalle']="";			
			$impuestos['saldos_otros']="";
			$impuestos['otros_detalle']="";	
			$impuestos['varios1']="";
			$impuestos['varios1_detalle']="";
			$impuestos['varios2']="";
			$impuestos['varios2_detalle']="";			
			$impuestos['idC']="";			
		}
		return $impuestos;				
	}

	public function buscar_idC_id(){
		$this->db->select('idContrato');
		$this->db->from('alquileres');	
		$this->db->order_by("idContrato","desc");
		$this->db->limit("1");	
		$query=$this->db->get();			
		foreach ($query->result() as $row){
			$idC=$row->idContrato;
		}
		return $idC;
	}

	public function datos_reclamos($idC){
		$this->db->select('*');
		$this->db->from('reclamos');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();	
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				$reclamos['idR']=$row->idReclamo;
				$reclamos['fecha']=$row->fecha_atencion;
				$reclamos['tecnico']=$row->encargado;
				$reclamos['descripcion']=$row->descripcion;
				$reclamos['quien_paga']=$row->quien_paga;
				$reclamos['dinero_dado']=$row->dinero_dado;		
				$reclamos['liquidado']=$row->liquidado;				
			}			
		}else{
				$reclamos['idR']="";
				$reclamos['fecha']="";
				$reclamos['tecnico']="";
				$reclamos['descripcion']="";
				$reclamos['quien_paga']="";
				$reclamos['dinero_dado']="";
				$reclamos['liquidado']="";
		}	
		return $reclamos;
	}

	public function buscar_idI_gastos_alquiler($idI){
		$this->db->select('*');
		$this->db->from('gastos_alquiler');
		$this->db->where('idInmueble',$idI);
		$query=$this->db->get();	
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}

	public function cant_alquileres_propietarios($locador){
		$this->db->select('*');
		$this->db->from('alquileres');		
		$this->db->where('locador',$locador);
		$query=$this->db->get();
		$cant_alquileres=$query->num_rows();
		return $cant_alquileres;			
	}

	public function es_propietario($dni){
		$this->db->select('*');
		$this->db->from('personas');		
		$this->db->where('dni',$dni);
		$this->db->where('tipo_persona','PROPIETARIO');
		$query=$this->db->get();
		$propietario=$query->num_rows();	
		
		if($propietario > 0){
			return true;
		}else{
			return false;
		}

	}

	public function actualizar_alquiler($dni,$idI){
		$this->db->set('locador',$dni);
		$this->db->where('idInmueble',$idI);
		$this->db->update('alquileres');
		return true;
	}

	public function buscar_nombre_usuario($usuario,$clave){
		$this->db->select('NyA');
		$this->db->from('usuarios');
		$this->db->where('nombreUsuario',$usuario);
		$this->db->where('clave',$clave);
		$query=$this->db->get();

		foreach ($query->result() as $row){
			$nombre_usuario=$row->NyA;
		}	
		return $nombre_usuario;
	}


	public function verifica_dni($dni){
		$sql="select * from personas where dni=".$dni;
		$query=$this->db->query($sql);
		return $query->num_rows();
	}

	public function verifica_dni_edit($doc){
		$sql="select * from personas where dni=".$doc;
		$query=$this->db->query($sql);
		return $query->num_rows();
	}	

	public function verificar_inmuebles_alquileres($dni){
		$sql="select * from inmuebles where dni=".$dni;
		$query=$this->db->query($sql);
		$cant_inmuebles=$query->num_rows();

		$sql="select * from alquileres where locador=".$dni." or locatario1=".$dni." or locatario2=".$dni." or garante1=".$dni." or garante2=".$dni." or garante3=".$dni;
		$query=$this->db->query($sql);
		$cant_alquileres=$query->num_rows();

		if($cant_inmuebles>0 or $cant_alquileres>0){
			return "SI";
		}else{
			return "NO";
		}
	}

	public function verificar_inmuebles($idE){
		$sql="select * from inmuebles where idEdificio=".$idE;
		$query=$this->db->query($sql);
		$cant_inmuebles=$query->num_rows();

		if($cant_inmuebles>0){
			return "SI";
		}else{
			return "NO";
		}

	}

	public function verificar_inmuebles_barrio($idB){
		$sql="select * from inmuebles where idBarrio=".$idB;
		$query=$this->db->query($sql);
		$cant_inmuebles_barrios=$query->num_rows();

		$sql="select * from edificios where idBarrio=".$idB;
		$query=$this->db->query($sql);
		$cant_inmuebles_edificio=$query->num_rows();		

		if($cant_inmuebles_barrios>0 or $cant_inmuebles_edificio){
			return "SI";
		}else{
			return "NO";
		}		
	}

	public function actualizar_propietario($dni_pendiente){
		$sql="select * from inmuebles where dni=".$dni_pendiente;
		$query=$this->db->query($sql);

		if($query->num_rows()==1){
			$sql1="update personas set tipo_persona='' where dni=".$dni_pendiente;
			$query=$this->db->query($sql1);
		}
	}

	public function rescinde_dentro($idC){
	 	$this->db->select('rescinde_dentro,rescinde_fecha,cant_pagos');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);		
		$query=$this->db->get();	
		$hay_registros=$query->num_rows();	

		if($hay_registros>0){
			foreach ($query->result() as $row){
				$rescinde_dentro = $row->rescinde_dentro;
				$rescinde_fecha = $row->rescinde_fecha;				
			}
		}else{
			$rescinde_dentro=0;
			$rescinde_fecha="";			
		}
		$datos_rescinde[0]=$rescinde_dentro;
		$datos_rescinde[1]=$rescinde_fecha;
		return $datos_rescinde;	
	}

	public function habilitar_renueva($idC){
		$this->db->select('duracion,cant_pagos');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);		
		$query=$this->db->get();
		foreach ($query->result() as $row) {
			$duracion=$row->duracion;
			$cant_pagos=$row->cant_pagos;
		}	

		$diferencia=$duracion-$cant_pagos;
		return $diferencia;		
	}

	public function buscar_locatarios($idC){
		$this->db->select('locatario1, locatario2');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();	
		foreach ($query->result() as $row) {
			$dni1=$row->locatario1;
			$dni2=$row->locatario2;
		}
		$locatario1=$this->buscar_persona($dni1);

		if($dni2<>""){

			$locatario2=$this->buscar_persona($dni2);
			$loca1 = strstr($locatario1, ' ', true);
			$loca2 = strstr($locatario2, ' ', true);
			$locatarios=$loca1." / ".$loca2;

		}else{

			$locatarios=$locatario1;
		}

		return $locatarios;
	}

	public function quien_paga_comision($idC){
		$this->db->select('comision_inmo_quien_paga');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$comision_quien_paga=$row->comision_inmo_quien_paga;
		}
		return $comision_quien_paga;	
	}

	public function cant_pagos($idC){
		$this->db->select('cant_pagos');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$cant_pagos=$row->cant_pagos;
		}
		return $cant_pagos;		
	}

	public function buscar_prox_venc($idC){
		$this->db->select('proxVenc');
		$this->db->from('alquileres');
		$this->db->where('idContrato',$idC);
		$query=$this->db->get();
		foreach ($query->result() as $row){
			$prox_venc=$row->proxVenc;
		}
		return $prox_venc;		
	}
}