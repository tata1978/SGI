moment.locale('ES');

function hora(){
	var fecha= new Date();
	var horas= fecha.getHours();
	var hora = horas > 9 ? horas : '0' + horas;

	var minutos = fecha.getMinutes();
	var minutes = minutos > 9 ? minutos : '0' + minutos;

	var segundos = fecha.getSeconds();
	var seconds = segundos > 9 ? segundos : '0' + segundos;

	//document.getElementById('hora').innerHTML=''+hora+':'+minutes+'';//:'+seconds+'';
	
	setTimeout('hora()',1000);
	$(".horario").text(''+hora+':'+minutes+'');
}

//function para desloguear despues de un cierto tiempo
   
  /*  document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;

    function logout() {
        //alert("You are now logged out.")
       window.location.href = "http://"+host+"/SGI/login/logout";
    }
    function resetTimer() {
    	var t;
        clearTimeout(t);
        t = setTimeout(logout, 600000)// se desloguea despues de 10 minutos en milisegundos
        // 1000 milisec = 1 sec
    }*/
//////////////fin///////////////////////

//////////OCULTA EL BOTONCITO REFRESH DEL LISTADO DE CADA VISTA
 $("button[class$='refresh-data']").hide();

//////VALIDANDO LOS INPUT DE LOS FORMULARIOS, NUMEROS DECIMALES POSITIVOS Y NEGATIVOS
	function validateFloatKeyPress(el, evt) {
		  var charCode = (evt.which) ? evt.which : event.keyCode;
		  var number = el.value.split('.');
		  // permitir el signo de - (45)
		  if (charCode != 45 && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		    return false;
		  }
		  //just one dot
		  if (number.length > 1 && charCode == 46) {
		    return false;
		  }
		  //get the carat position
		  var caratPos = getSelectionStart(el);
		  // no permitir que se ponega el - en una posicion diferente de la inicial
		  if (caratPos > 0 && charCode == 45) {
		    return false;
		  }
		  // no permtir mas de un - en el numero
		  if (charCode == 45 && el.value.charAt(0) == "-") {
		    return false;
		  }
		  var dotPos = el.value.indexOf(",");
		  if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
		    return false;
		  }
		  return true;
	}
///////FIN//////////////////

/////validar numeros decimales, solo positivos
	$('.numerico').on('input', function () { 
		this.value = this.value.replace(/[^0-9\\.]/g,'');
	});
//////fin//////////////
/////validar numeros decimales, solo positivos
	$('input[type="number"]').on('input', function () { 
		this.value = this.value.replace(/[^0-9\\.]/g,'');
	});
//////fin//////////////


//OCULTA EL BOTON GUARDAR DE TODAS LAS VISTAS
$("#form-button-save").hide();


$("tr[id^='row']").hover(
  function () {
    $(this).css("background","#9bffca");
  }, 
  function () {
    $(this).css("background","");
  }
);


var today=moment().format("dddd, DD MMMM  YYYY"); 	
var fecha=today.charAt(0).toUpperCase() + today.slice(1);
$(".fecha").append(fecha);
$("#header").css("color","white");
$(".texto").css("color","yellow");
$("#header").css("text-align","left");
$("#header").css('background-color', '#045FB4');
$("#header").css("font-weight","bolder");
$("#header").css('text-align','center');
$('input').css({'height':'30px' });	
	
	function getAbsolutePath() {
	    var loc = window.location;
	    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
	    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
	}

	function getAbsolutePath1() {
	    var loc = window.location;
	    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('') + 1);
	    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
	}

	var url = getAbsolutePath();
	var url1 = getAbsolutePath1();

	var path=window.location.pathname;
	var host = window.location.host;


	if(path == "/SGI/Inmueble/reservar"){
		$(".datatables-add-button").hide();	

	}	

	//$('a[href="http://'+host+'/SGI/Inmueble/inmueble/add"]').css("background","#ADD8E6");//#AFEEEE

	$(':button').css("background","#a6edff");


	$("#comision").width(30);
	
	
	//CARGA DE TITULOS 
	if(url1 == "http://"+host+"/SGI/Usuario/usuario"){
		$("#texto_centro").text("Usuarios");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','12px');


		$(".datatables-add-button").css('margin-top','49px');
		$(".datatables-add-button").css('margin-left','190px');			
	}

	if(url1 == "http://"+host+"/SGI/Persona/persona"){
		$("#texto_centro").text("Administración de Personas");
		$("#link1").text("Datos Generales");		
		$("#link1").css("color", "yellow");	
		$("#link2").text("Personas");			
		$('#link2').attr("href","javascript:history.go(0)");
		$("#link2").css("color", "yellow");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','12px');


		$(".datatables-add-button").css('margin-top','49px');
		$(".datatables-add-button").css('margin-left','190px');

		$('a[onclick*="javascript"]').css('background','#fffd9a');
		$('a[href*="Persona/persona/add"]').css('background','#9af4ff');

	}	

	if(url1 == "http://"+host+"/SGI/Edificio/edificio"){
		$("#texto_centro").text("Administración de Edificios");
		$("#link1").text("Datos Generales");		
		$("#link1").css("color", "yellow");	
		$("#link2").text("Edificios");			
		$('#link2').attr("href","javascript:history.go(0)");
		$("#link2").css("color", "yellow");	


		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','12px');

		$(".datatables-add-button").css('margin-top','49px');
		$(".datatables-add-button").css('margin-left','190px');

		$('a[onclick*="javascript"]').css('background','#fffd9a');

		$('a[href*="Edificio/edificio/add"]').css('background','#9af4ff');
	}
	

	if(url1 == "http://"+host+"/SGI/Barrio/barrio"){
		$("#texto_centro").text("Administración de Barrios");
		$("#link1").text("Datos Generales");		
		$("#link1").css("color", "yellow");	
		$("#link2").text("Barrios");			
		$('#link2').attr("href","javascript:history.go(0)");
		$("#link2").css("color", "yellow");


		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','12px');


		$(".datatables-add-button").css('margin-top','49px');
		$(".datatables-add-button").css('margin-left','190px');		

		$('a[onclick*="javascript"]').css('background','#fffd9a');	
		$('a[href*="Barrio/barrio/add"]').css('background','#9af4ff');
	}

	if(url == "http://"+host+"/SGI/Alquiler/read/"){	
		$("#texto_centro").text("Detalle de Alquiler");
		$("#link1").text("Atrás");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');
	}

	if(url == "http://"+host+"/SGI/Comodato/read/"){	
		$("#texto_centro").text("Detalle de Comodato");
		$("#link1").text("Atrás");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');
	}

if(url == "http://"+host+"/SGI/Comodato/comodato_edit/edit/"){	
		$("#texto_centro").text("Modificar Comodato");
		$("#link1").text("Cancelar");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');

			$('.numerico').on('input', function () { 
			    this.value = this.value.replace(/[^0-9\\.]/g,'');
			});

		$("#operacion_input_box").on('change', function() {//SI CAMBIO EL VALOR EN OPERACION "ALQUILER/COMODATO" ME SETEA DURACION
			$("#field-duracion").val("");
			var meses_duracion=$('#field-duracion').val();
			if(meses_duracion<24){

				//$(".chosen-single").trigger('click');	//field_tipo_ajuste_chosen
				$( "a[class^='chosen-single']" ).click();
				//$("#field_tipo_ajuste_chosen span").text("Seleccionar Ajuste");
			}
		});	

		var nro_pago=$("#nro_pago").text();



		$("#field-tipo_ajuste").on('change', function() {//SI CAMBIO EL VALOR EN TIPO AJUSTE SE SETEA LOS VALORES DE  LOS PERIODOS
			$("#field-ajuste").val("");
			$("#field-valor2").val("");
			$("#field-valor3").val("");
			$("#field-valor4").val("");
			$("#field-valor5").val("");
			$("#field-valor6").val("");
			$("#field-comision_inmo_a_pagar").val("");
			$("#field-sellado_contrato").val("");

		});		



		function fin_contato(){
			//alert("adasd");
			moment.locale('es');
			var meses_duracion=$('#field-duracion').val();	

			var operacion=$("#field-operacion").val();
			$("#mensaje").text("");
			//alert(operacion);
			var inicio=$("#field-fechaInicio").val();

			if(inicio !=""){

				if (operacion=="ALQUILER"  || operacion=="COMERCIAL"){	
					if(meses_duracion == 36){
						//var fin_contrato=$('#field-fechaFin').val();	
						var fecha_inicio = $('#field-fechaInicio').val();
						var fecha_inicio_m=moment(fecha_inicio,"DD-MM-YYYY");

						var fin = fecha_inicio_m.add(meses_duracion, 'month');
						var fin_f=fin.format('DD/MM/YYYY');

						var finalizacion=moment(fin).subtract(1, 'days');
						var fin_format=finalizacion.format('DD/MM/YYYY');
						$('#field-fechaFin').val(fin_format);
					}else{
						$("#mensaje").text(" Duración 36 meses para Alquiler/Comercial");
						$("#field-duracion").val("");
						$("#field-fechaFin").val("");
						$("#field-duracion").focus();
						$("#field-duracion").select();					
					}	
				}else if (operacion=="COMODATO"){

						$("#mensaje_tipo_ajuste").text("Si duración es menor de 24 seleccione SIN AJUSTE y 0 en Ajuste%");					

						var fecha_inicio = $('#field-fechaInicio').val();
						var fecha_inicio_m=moment(fecha_inicio,"DD-MM-YYYY");

						var fin = fecha_inicio_m.add(meses_duracion, 'month');
						var fin_f=fin.format('DD/MM/YYYY');

						var finalizacion=moment(fin).subtract(1, 'days');
						var fin_format=finalizacion.format('DD/MM/YYYY');
						$('#field-fechaFin').val(fin_format);
				}else{
					$("#mensaje").text(" SELECCIONE OPERACION");
					$("#field-duracion").val("");
					$("#field-operacion").focus();
					$("#field-operacion").select();

				}
			}else{
				$("#field-duracion").val("");
				$("#mensaje").text(" INGRESE FECHA DE INICIO");				
			}			
		}

		function comprobar_fecha_pago(){
			var fechaPago=$("#field-fechaPago").val();
			var fecha_inicio=$("#field-fechaInicio").val();

			if(fecha_inicio ==""){
				$("#mensaje").text("INGRESE FECHA DE INICIO");
				$("#field-proxVenc").val("");
				$("#field-fechaPago").val("");
				
			}		

			if(fechaPago ==""){
				$("#mensaje").text("INGRESE FECHA DE PAGO");
				$("#field-fechaPago").focus();
				$("#field-proxVenc").val("");
				$("#field-fechaPago").val("");
			}
		}		

		function proxvenc(){

			moment.locale('es');
			var prox_venc = document.getElementById('field-proxVenc');		
			var dia_paga = document.getElementById('field-fechaPago').value;
			var fecha_inicio = document.getElementById('field-fechaInicio').value;
			
			var datearray = fecha_inicio.split("/");
			var fecha = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
			var fecha_f =new Date(fecha);
			mes_f=fecha_f.getMonth()+1;
			ano_f=fecha_f.getFullYear();

			if(mes_f==13){
				mes_f=1;
				ano_f=ano_f+1;
			}

			if(mes_f < 10 ){
				prox_venc.value = dia_paga+"/0"+mes_f +"/"+ ano_f;	
			}else
				prox_venc.value = dia_paga+"/"+mes_f +"/"+ ano_f;		
		}

		function vaciar_input(id){
			/*$("#field-valor2").val("");
			$("#field-valor3").val("");
			$("#field-valor4").val("");
			$("#field-valor5").val("");
			$("#field-valor6").val("");*/

				$("#"+id+"").val("");					

		}

			// pone el cero cuando dejamos vacio los input
			function input_ceros(id){
				var  valor = $("#"+id+"").val();
				if(valor == ""){
					$("#"+id+"").val("0.00");
				}
			}		

		//CALCULO LOS VALORES DEL ALQUILER SEGUN LA DURACION, TIPO DE AJUSTE, LO MUESTRO A MODO DE INFORMACION AL LADO DEL INPUT

		function calcular_ajuste(id){

			var operacion=$("#field-operacion").val();

			var duracion=$("#field-duracion").val();
			var tipo_ajuste=$("#field-tipo_ajuste").val();
			var ajuste_alquiler=parseFloat($("#field-ajuste").val());
			var porc_ajuste=parseFloat(ajuste_alquiler/100)+1;

			var valor1=$("#field-valor1").val();
			$("#"+id+"").val("");			

			///ACA
		if(duracion==24){

			if(operacion=="COMODATO"){

					if(tipo_ajuste != 0 && ajuste_alquiler != ""){
							if(tipo_ajuste =="ANUAL"){

								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);
								$("#field-valor3").prop('disabled',true);
								$("#field-valor4").prop('disabled',true);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);

							}else if(tipo_ajuste =="SEMESTRAL"){

								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);
								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);
								var valor3=$("#field-valor3").val();
								var valor4=parseFloat(valor3*porc_ajuste);
								var valor4_f=valor4.toFixed(2);
								$("#valor4").text(valor4_f);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);

							}else if(tipo_ajuste=="OCTOMESTRAL"){

								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);

								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);

								var valor3=$("#field-valor3").val();
								var valor4=parseFloat(valor3*porc_ajuste);
								var valor4_f=valor4.toFixed(2);
								$("#valor4").text(valor4_f);

								$("#field-valor4").prop('disabled',true);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);							
							}
					}else{
						alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
					}				
			}

		}else if(duracion == 36){

				if(operacion=="COMERCIAL" || operacion=="COMODATO"){

						if(tipo_ajuste != 0 && ajuste_alquiler != ""){
							if(tipo_ajuste =="ANUAL"){
								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);

								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);

								$("#field-valor4").prop('disabled',true);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);

							}else if(tipo_ajuste =="SEMESTRAL"){
								$("#field-valor4").prop('disabled',false);
								$("#field-valor5").prop('disabled',false);
								$("#field-valor6").prop('disabled',false);			
								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);

								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);

								var valor3=$("#field-valor3").val();
								var valor4=parseFloat(valor3*porc_ajuste);
								var valor4_f=valor4.toFixed(2);
								$("#valor4").text(valor4_f);

								var valor4=$("#field-valor4").val();
								var valor5=parseFloat(valor4*porc_ajuste);
								var valor5_f=valor5.toFixed(2);
								$("#valor5").text(valor5_f);

								var valor5=$("#field-valor5").val();
								var valor6=parseFloat(valor5*porc_ajuste);
								var valor6_f=valor6.toFixed(2);
								$("#valor6").text(valor6_f);
							}
						}else {
							alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
						}
					}	
						 		
		}	////ACA

			
		}

		//FUNCION PARA CALCULAR LA COMISION DE ALQUILER A ABONAR EN EL INGRESO
		function calcular_comision(x){//en comodato_edit
			//alert("aca1");
			var duracion,ajuste,valor1,valor2,valor3,valor4,valor5,valor6,total,comision,suma,comision_inmo;
			duracion=$("#field-duracion").val();
			ajuste=$("#field-tipo_ajuste").val();
			valor1=$("#field-valor1").val();
			valor_1=parseInt(valor1);
			valor2=$("#field-valor2").val();
			valor_2=parseInt(valor2);
			valor3=$("#field-valor3").val();
			valor_3=parseInt(valor3);
			valor4=$("#field-valor4").val();
			valor_4=parseInt(valor4);
			valor5=$("#field-valor5").val();
			valor_5=parseInt(valor5);
			valor6=$("#field-valor6").val();
			valor_6=parseInt(valor6);

			var operacion=$("#field-operacion").val();
			var comision_inmo_por=0.0415;

			if(operacion =="ALQUILER"){
					if(duracion==36){
						if(ajuste=="ANUAL"){
							comision_inmo=(valor_1*duracion)*comision_inmo_por;			
						}else if(ajuste=="SEMESTRAL"){
							if(valor1 !="" && valor2 !="" && valor3 !="" && valor4 !="" && valor5 !="" && valor6!=""){
							var seis=6;
							comision_inmo=((valor_1*seis)+(valor_2*seis)+(valor_3*seis)+(valor_4*seis)+(valor_5*seis)+(valor_6*seis))*comision_inmo_por;
							//un_alquiler=total_alquiler/6;	
							//medio_alquiler=un_alquiler/2;
							//comision_inmo=un_alquiler*1.5;				
							}else{
							alert("Faltan valores");
							$("#field-valor6").focus();
							}				
						}
					}
				}else if(operacion == "COMODATO"){
					if(valor6 !=""){
						comision_inmo=(valor_6*duracion)*comision_inmo_por;
					}else if(valor5 !=""){
						comision_inmo=(valor_5*duracion)*comision_inmo_por;
					}else if(valor4 !=""){
						comision_inmo=(valor_4*duracion)*comision_inmo_por;
					}else if(valor3 !=""){
						comision_inmo=(valor_3*duracion)*comision_inmo_por;
					}else if(valor2 !=""){
						comision_inmo=(valor_2*duracion)*comision_inmo_por;
					}else if(valor1 !=""){
						comision_inmo=(valor_1*duracion)*comision_inmo_por;
					}
				}else if(operacion == "COMERCIAL"){
					var total_alquiler,promedio_alquiler,un_alquiler,medio_alquiler;
					if(ajuste=="ANUAL"){
						if(valor1 !="" && valor2 !="" && valor3 !=""){
							var doce=12;
							comision_inmo=((valor_1*doce)+(valor_2*doce)+(valor_3*doce))*comision_inmo_por;
							//un_alquiler=total_alquiler/3;	
							//medio_alquiler=un_alquiler/2;
							//comision_inmo=un_alquiler*1.5;				
						}else{
							alert("Faltan valores");
							$("#field-valor3").focus();
						}
					}else if(ajuste=="SEMESTRAL"){
						if(valor1 !="" && valor2 !="" && valor3 !="" && valor4 !="" && valor5 !="" && valor6!=""){
							var seis=6;
							comision_inmo=((valor_1*seis)+(valor_2*seis)+(valor_3*seis)+(valor_4*seis)+(valor_5*seis)+(valor_6*seis))*comision_inmo_por;
							//un_alquiler=total_alquiler/6;	
							//medio_alquiler=un_alquiler/2;
							//comision_inmo=un_alquiler*1.5;				
						}else{
							alert("Faltan valores");
							$("#field-valor6").focus();
						}
					}else{
						alert("Tipo de Ajuste debe ser SEMESTRAL o ANUAL");

					}

				}	
				var comision_inmo_d=comision_inmo.toFixed(2);
				$("#field-comision_inmo_a_pagar").val(comision_inmo_d);		

		}

	}
/////fin aca		

	if(url == "http://"+host+"/SGI/Alquiler/alquiler_edit/edit/"){	
		$("#texto_centro").text("Modificar Alquiler");
		$("#link1").text("Cancelar");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');

			$('.numerico').on('input', function () { 
			    this.value = this.value.replace(/[^0-9\\.]/g,'');
			});

		$("#operacion_input_box").on('change', function() {//SI CAMBIO EL VALOR EN OPERACION "ALQUILER/COMODATO" ME SETEA DURACION
			$("#field-duracion").val("");
		});

		var nro_pago=$("#nro_pago").text();

	

		$("#field-tipo_ajuste").on('change', function() {//SI CAMBIO EL VALOR EN TIPO AJUSTE SE SETEA LOS VALORES DE  LOS PERIODOS
			$("#field-ajuste").val("");
			$("#field-valor2").val("");
			$("#field-valor3").val("");
			$("#field-valor4").val("");
			$("#field-valor5").val("");
			$("#field-valor6").val("");
			$("#field-comision_inmo_a_pagar").val("");
			$("#field-sellado_contrato").val("");

		});

		var url = getAbsolutePath1();
		//var idC=url.substring(url.lastIndexOf('/') + 1);
		//alert(idC);

			var nro_pago=$("#nro_pago").text();
			



		function calcular_periodo_rescision(){
			
			var periodo_fecha=$("#venc_periodo").text();
			var periodo_formateado=moment(periodo_fecha);
			var rescinde_dentro=$("#field-rescinde_dentro").val();

			var duracion=$("#field-duracion").val();

			var nro_pago=$("#nro_pago").text();
			var dif=duracion-nro_pago;	


			var fecha = new Date();			 
			var dia=fecha.getDate();	

			//alert(periodo_formateado);

			if(rescinde_dentro != 0 && rescinde_dentro != ""){
				if((rescinde_dentro<=(dif+1)) && dia < 16) {
					
						var periodo_rescinde = periodo_formateado.add((rescinde_dentro-1), 'month');
						var periodo_f=moment(periodo_rescinde).format('MMM-YY');
						var rescinde_periodo_f=periodo_f.toUpperCase();								
				}else if((rescinde_dentro<=dif) && dia >= 16){
						var dif_2=dif-1;

						var periodo_rescinde = periodo_formateado.add((rescinde_dentro), 'month');
						var periodo_f=moment(periodo_rescinde).format('MMM-YY');
						var rescinde_periodo_f=periodo_f.toUpperCase();						
				}else{
					alert("Supera el fin de contrato!!!!");
					$("#field-rescinde_dentro").val("");
				}
				$("#field-rescinde_fecha").val(rescinde_periodo_f);
			}else{
				$("#field-rescinde_fecha").val("");
			}
		}
		


		function fin_contato(){			
			moment.locale('es');
			var meses_duracion=$('#field-duracion').val();	

			var operacion=$("#field-operacion").val();
			$("#mensaje").text("");
			//alert(meses_duracion);
			var inicio=$("#field-fechaInicio").val();

			if(inicio !=""){

				if (operacion=="ALQUILER" || operacion=="COMERCIAL"){	
					if(meses_duracion == 36){
						//var fin_contrato=$('#field-fechaFin').val();	
						var fecha_inicio = $('#field-fechaInicio').val();
						var fecha_inicio_m=moment(fecha_inicio,"DD-MM-YYYY");

						var fin = fecha_inicio_m.add(meses_duracion, 'month');
						var fin_f=fin.format('DD/MM/YYYY');

						var finalizacion=moment(fin).subtract(1, 'days');
						var fin_format=finalizacion.format('DD/MM/YYYY');
						$('#field-fechaFin').val(fin_format);
					}else{
						$("#mensaje").text(" Duración 36 meses para Alquiler/Comercial");
						$("#field-duracion").val("");
						$("#field-fechaFin").val("");
						$("#field-duracion").focus();
						$("#field-duracion").select();					
					}	
				}else if (operacion=="COMODATO"){

						$("#mensaje_tipo_ajuste").text("Si duración es menor de 24 seleccione SIN AJUSTE");					

						var fecha_inicio = $('#field-fechaInicio').val();
						var fecha_inicio_m=moment(fecha_inicio,"DD-MM-YYYY");

						var fin = fecha_inicio_m.add(meses_duracion, 'month');
						var fin_f=fin.format('DD/MM/YYYY');

						var finalizacion=moment(fin).subtract(1, 'days');
						var fin_format=finalizacion.format('DD/MM/YYYY');
						$('#field-fechaFin').val(fin_format);
				}else{
					$("#mensaje").text(" SELECCIONE OPERACION");
					$("#field-duracion").val("");
					$("#field-operacion").focus();
					$("#field-operacion").select();
				}
			}else{
				$("#field-duracion").val("");
				$("#mensaje").text(" INGRESE FECHA DE INICIO");				
			}			
		}

		function proxvenc(){

			moment.locale('es');
			var prox_venc = document.getElementById('field-proxVenc');		
			var dia_paga = document.getElementById('field-fechaPago').value;
			var fecha_inicio = document.getElementById('field-fechaInicio').value;
			
			var datearray = fecha_inicio.split("/");
			var fecha = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
			var fecha_f =new Date(fecha);
			mes_f=fecha_f.getMonth()+1;
			ano_f=fecha_f.getFullYear();

			if(mes_f==13){
				mes_f=1;
				ano_f=ano_f+1;
			}

			if(mes_f < 10 ){
				prox_venc.value = dia_paga+"/0"+mes_f +"/"+ ano_f;	
			}else{
				prox_venc.value = dia_paga+"/"+mes_f +"/"+ ano_f;						
			}
		}

		function vaciar_input(id){

			$("#"+id+"").val("");					

		}

			// pone el cero cuando dejamos vacio los input
			function input_ceros(id){
				var  valor = $("#"+id+"").val();
				if(valor == ""){
					$("#"+id+"").val("0.00");
				}
			}		

		//CALCULO LOS VALORES DEL ALQUILER SEGUN LA DURACION, TIPO DE AJUSTE, LO MUESTRO A MODO DE INFORMACION AL LADO DEL INPUT
		function calcular_ajuste(id){

			var duracion=$("#field-duracion").val();
			var tipo_ajuste=$("#field-tipo_ajuste").val();
			var ajuste_alquiler=parseFloat($("#field-ajuste").val());
			var porc_ajuste=parseFloat(ajuste_alquiler/100)+1;

			var operacion=$("#field-operacion").val();

			var valor1=$("#field-valor1").val();
			$("#"+id+"").val("");

				if(duracion==24){

					if(operacion=="COMODATO"){
						
							if(tipo_ajuste != 0 && ajuste_alquiler != ""){
									if(tipo_ajuste =="ANUAL"){

										var valor2=parseFloat(valor1*porc_ajuste);
										var valor2_f=valor2.toFixed(2);
										$("#valor2").text(valor2_f);
										$("#field-valor3").prop('disabled',true);
										$("#field-valor4").prop('disabled',true);
										$("#field-valor5").prop('disabled',true);
										$("#field-valor6").prop('disabled',true);

									}else if(tipo_ajuste =="SEMESTRAL"){

										var valor2=parseFloat(valor1*porc_ajuste);
										var valor2_f=valor2.toFixed(2);
										$("#valor2").text(valor2_f);
										var valor2=$("#field-valor2").val();
										var valor3=parseFloat(valor2*porc_ajuste);
										var valor3_f=valor3.toFixed(2);
										$("#valor3").text(valor3_f);
										var valor3=$("#field-valor3").val();
										var valor4=parseFloat(valor3*porc_ajuste);
										var valor4_f=valor4.toFixed(2);
										$("#valor4").text(valor4_f);
										$("#field-valor5").prop('disabled',true);
										$("#field-valor6").prop('disabled',true);

									}else if(tipo_ajuste=="OCTOMESTRAL"){										

										var valor2=parseFloat(valor1*porc_ajuste);
										var valor2_f=valor2.toFixed(2);
										$("#valor2").text(valor2_f);

										var valor2=$("#field-valor2").val();
										var valor3=parseFloat(valor2*porc_ajuste);
										var valor3_f=valor3.toFixed(2);
										$("#valor3").text(valor3_f);

										var valor3=$("#field-valor3").val();
										var valor4=parseFloat(valor3*porc_ajuste);
										var valor4_f=valor4.toFixed(2);
										$("#valor4").text(valor4_f);

										$("#field-valor4").prop('disabled',true);
										$("#field-valor5").prop('disabled',true);
										$("#field-valor6").prop('disabled',true);							
									}
							}else{
								alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
							}				
					}

				}else if(duracion == 36){

							if(operacion=="COMERCIAL" || operacion=="COMODATO"){

								if(tipo_ajuste != 0 && ajuste_alquiler != ""){
									if(tipo_ajuste =="ANUAL"){
										var valor2=parseFloat(valor1*porc_ajuste);
										var valor2_f=valor2.toFixed(2);
										$("#valor2").text(valor2_f);

										var valor2=$("#field-valor2").val();
										var valor3=parseFloat(valor2*porc_ajuste);
										var valor3_f=valor3.toFixed(2);
										$("#valor3").text(valor3_f);

										$("#field-valor4").prop('disabled',true);
										$("#field-valor5").prop('disabled',true);
										$("#field-valor6").prop('disabled',true);

									}else if(tipo_ajuste =="SEMESTRAL"){
										$("#field-valor4").prop('disabled',false);
										$("#field-valor5").prop('disabled',false);
										$("#field-valor6").prop('disabled',false);			
										var valor2=parseFloat(valor1*porc_ajuste);
										var valor2_f=valor2.toFixed(2);
										$("#valor2").text(valor2_f);

										var valor2=$("#field-valor2").val();
										var valor3=parseFloat(valor2*porc_ajuste);
										var valor3_f=valor3.toFixed(2);
										$("#valor3").text(valor3_f);

										var valor3=$("#field-valor3").val();
										var valor4=parseFloat(valor3*porc_ajuste);
										var valor4_f=valor4.toFixed(2);
										$("#valor4").text(valor4_f);

										var valor4=$("#field-valor4").val();
										var valor5=parseFloat(valor4*porc_ajuste);
										var valor5_f=valor5.toFixed(2);
										$("#valor5").text(valor5_f);

										var valor5=$("#field-valor5").val();
										var valor6=parseFloat(valor5*porc_ajuste);
										var valor6_f=valor6.toFixed(2);
										$("#valor6").text(valor6_f);
									}
								}else {
									alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
								}
							}else if(operacion=="ALQUILER"){

								if(tipo_ajuste != 0 && ajuste_alquiler != ""){

									if(tipo_ajuste =="SEMESTRAL"){

										$("#field-valor4").prop('disabled',false);
										$("#field-valor5").prop('disabled',false);
										$("#field-valor6").prop('disabled',false);			
										var valor2=parseFloat(valor1*porc_ajuste);
										var valor2_f=valor2.toFixed(2);
										$("#valor2").text(valor2_f);

										var valor2=$("#field-valor2").val();
										var valor3=parseFloat(valor2*porc_ajuste);
										var valor3_f=valor3.toFixed(2);
										$("#valor3").text(valor3_f);

										var valor3=$("#field-valor3").val();
										var valor4=parseFloat(valor3*porc_ajuste);
										var valor4_f=valor4.toFixed(2);
										$("#valor4").text(valor4_f);

										var valor4=$("#field-valor4").val();
										var valor5=parseFloat(valor4*porc_ajuste);
										var valor5_f=valor5.toFixed(2);
										$("#valor5").text(valor5_f);

										var valor5=$("#field-valor5").val();
										var valor6=parseFloat(valor5*porc_ajuste);
										var valor6_f=valor6.toFixed(2);
										$("#valor6").text(valor6_f);
									}

								}else {
									alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
								}
							}								 		
				}	
			
		}

		//FUNCION PARA CALCULAR LA COMISION DE ALQUILER A ABONAR EN EL INGRESO
		function calcular_comision(x){
			//alert("tata"); ALQUILER/EDIT
			var duracion,ajuste,valor1,valor2,valor3,valor4,valor5,valor6,total,comision,suma,comision_inmo;
			duracion=$("#field-duracion").val();
			ajuste=$("#field-tipo_ajuste").val();
			valor1=$("#field-valor1").val();
			valor_1=parseInt(valor1);
			valor2=$("#field-valor2").val();
			valor_2=parseInt(valor2);
			valor3=$("#field-valor3").val();
			valor_3=parseInt(valor3);
			valor4=$("#field-valor4").val();
			valor_4=parseInt(valor4);
			valor5=$("#field-valor5").val();
			valor_5=parseInt(valor5);
			valor6=$("#field-valor6").val();
			valor_6=parseInt(valor6);

			var operacion=$("#field-operacion").val();
			var comision_inmo_por=0.0415;

			if(operacion =="ALQUILER"){
					if(duracion==36){
						if(ajuste=="ANUAL"){
							comision_inmo=(valor_1*duracion)*comision_inmo_por;			
						}else if(ajuste=="SEMESTRAL"){
							if(valor1 !="" && valor2 !="" && valor3 !="" && valor4 !="" && valor5 !="" && valor6!=""){
							var seis=6;
							comision_inmo=((valor_1*seis)+(valor_2*seis)+(valor_3*seis)+(valor_4*seis)+(valor_5*seis)+(valor_6*seis))*comision_inmo_por;
							//un_alquiler=total_alquiler/6;	
							//medio_alquiler=un_alquiler/2;
							//comision_inmo=un_alquiler*1.5;				
							}else{
							alert("Faltan valores");
							$("#field-valor6").focus();
							}				
						}
					}
				}else if(operacion == "COMODATO"){
					if(valor6 !=""){
						comision_inmo=(valor_6*duracion)*comision_inmo_por;
					}else if(valor5 !=""){
						comision_inmo=(valor_5*duracion)*comision_inmo_por;
					}else if(valor4 !=""){
						comision_inmo=(valor_4*duracion)*comision_inmo_por;
					}else if(valor3 !=""){
						comision_inmo=(valor_3*duracion)*comision_inmo_por;
					}else if(valor2 !=""){
						comision_inmo=(valor_2*duracion)*comision_inmo_por;
					}else if(valor1 !=""){
						comision_inmo=(valor_1*duracion)*comision_inmo_por;
					}
				}else if(operacion == "COMERCIAL"){
					var total_alquiler,promedio_alquiler,un_alquiler,medio_alquiler;
					if(ajuste=="ANUAL"){
						if(valor1 !="" && valor2 !="" && valor3 !=""){
							var doce=12;
							comision_inmo=((valor_1*doce)+(valor_2*doce)+(valor_3*doce))*comision_inmo_por;
							//un_alquiler=total_alquiler/3;	
							//medio_alquiler=un_alquiler/2;
							//comision_inmo=un_alquiler*1.5;				
						}else{
							alert("Faltan valores");
							$("#field-valor3").focus();
						}
					}else if(ajuste=="SEMESTRAL"){
						if(valor1 !="" && valor2 !="" && valor3 !="" && valor4 !="" && valor5 !="" && valor6!=""){
							var seis=6;
							comision_inmo=((valor_1*seis)+(valor_2*seis)+(valor_3*seis)+(valor_4*seis)+(valor_5*seis)+(valor_6*seis))*comision_inmo_por;
							//un_alquiler=total_alquiler/6;	
							//medio_alquiler=un_alquiler/2;
							//comision_inmo=un_alquiler*1.5;				
						}else{
							alert("Faltan valores");
							$("#field-valor6").focus();
						}
					}else{
						alert("Tipo de Ajuste debe ser SEMESTRAL o ANUAL");

					}

				}	
				var comision_inmo_d=comision_inmo.toFixed(2);
				$("#field-comision_inmo_a_pagar").val(comision_inmo_d);	
		}
	}


	if(url == "http://"+host+"/SGI/Verpago/read/"){	
		$("#texto_centro").text("Cobro de Alquiler/Comodato - Detalle");
		$("#link1").text("Atrás");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");
	}

	if(url == "http://"+host+"/SGI/Pago/pagos_edit/edit/"){	
		$("#texto_centro").text("Cobro de Alquiler/Comodato - Modificar");
		$("#link1").text("Cancelar");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');		
	}	


	if(url == "http://"+host+"/SGI/Verpago/verpago/"){
		$("#texto_centro").text("Pagos");
		var dato=$(".texto").html();
		$("#datos").html(dato);

		var operacion=$("#operacion").text();//ALQUILER / COMODATO / COMERCIAL
		
		$('a[href*="Pago/pagos_edit/edit/"]').css('background','#fffd9a');
		$('a[href*="Verpago/descargar_pdf/"]').css('background','#70ff46');




		if(operacion=="Alquiler" || operacion=="Comercial"){
			$("#link1").text("Alquileres Vigentes");
			$('#link1').attr("href","http://"+host+"/SGI/Alquiler/alquiler");	
		}else{
			$("#link1").text("Comodatos Vigentes");	
			$('#link1').attr("href","http://"+host+"/SGI/Comodato/comodato");		
		}

		
		$("#link1").css("color", "yellow");
		$("#link2").text("Pagos");
		$('#link2').attr("href","");
		$("#link2").css("color", "yellow");

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');	

	 		
		///////ELIMINO O NO EL BOTON LIQUIDAR SEGUN SE HAYA PAGADO O NO A PROPIETARIO
	 		var id,i;	
	 	 	var n =$("tr[id*='row-']").length;

	 		for (i=0; i < n; i++ ){
			 	 idpago = $('#row-'+i).find('td').eq(1).text();

			 	 orden = $('#row-'+i).find('td').eq(0).text();

			 	if(orden=="x"){
			 		$('#row-'+i).css('color','red');
			 		$('#row-'+i).find('td').attr('align','left');
			 		$('a[href="http://'+host+'/SGI/Pago/pagos_edit/edit/'+idpago+'"]').remove();
			 	}




		 	 	var pagado=$('#row-'+i).find('td').eq(13).text();
		 	 	var link_liquidar = $('#row-'+i).find('a').eq(0).attr('href');//obtiene el href del boton liquidar

			 	if(pagado=="SI"){
			 		$('a[href="http://'+host+'/SGI/Pago/pagos_edit/edit/'+idpago+'"]').remove();
			 	}

				if(i==n-1 && pagado=="NO"){
					//var boton='&nbsp<span title="Anular Pago" id="anularPago" style="background: red" class="badge badge-pill">X</span>';
					var boton="<img src='"+baseurl+"/assets/images/cancelar.png' title='Anular Pago' id='anularPago'>";
					$('a[href$="Pago/pagos_edit/edit/'+idpago+'"]').after(boton);

				    $('#anularPago').hover(function() {
				        $(this).css('cursor','pointer');
				    });						
				}

						
					 	$('#anularPago').click(function(){
								var opcion = confirm("¿Cancelar Pago N°"+idpago+' ?');
								if (opcion == true) {
						        	window.location="http://"+host+"/SGI/Pago/cancelar/"+idpago;
								} else {				    	
							    	
							    	return false;
								}
	 					});
	 		}
	 	////////////FIN///////////////////

	}	

      /*$(function () {
          $('#buscar').quicksearch('#tablaDisponibles tbody tr');               
      });*/    

    // captura el evento keyup cuando escribes en el input
    $("#buscar").keyup(function(){
        _this = this;
        // Muestra los tr que concuerdan con la busqueda, y oculta los demás.
        $.each($("#tablaDisponibles tbody tr"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
               $(this).hide();
            else
               $(this).show();                
        });
    }); 


	if(path == "/SGI/Main"){
			$("#texto_centro").text("Sistema de Gestión Inmobiliaria");
			$("#link1").text("Inicio");
			$('#link1').attr("href","");
			$("#link1").css("color", "yellow");

			$("html").css('height','100%');
			$("body").css('height','100%');		

			function disponibles(id){					
				$('.tbody').html("");	
				$("#buscar").val("");	
				$('#cantidad').html("");
				$("#loading-image").show();
				$.post(baseurl+"main/inmueblesDisponibles/"+id,
					function(data){		
						//alert(data);	
						$("#loading-image").hide();
						var obj=JSON.parse(data);	
						
						$.each(obj,function(i,item){
							$("#titulo").html(item.tipo);
							//var caract=item.caract;
							if(item.estado==1){
								$('#tablaDisponibles').append('<tr class="disponibles"><td><span class="badge" style="background:#FF5100">'+item.idI+'</span></td><td>'+item.inmueble+'</td><td>'+item.edificio+'</td><td>'+item.barrio+'</td><td>'+item.dorm+'</td><td>'+item.cochera+'</td><td align=right>'+item.valor+'</td><td>'+item.operacion+'</td><td>'+item.locador+'</td><td><a href="#ver_mas" data-toggle="modal"><img class="vermas" title="Ver mas" src="'+baseurl+'/assets/images/ver.png"  onclick="ver_mas('+item.idI+')"></a></td><td><a href="javascript:void(0);"><img class="vermas" title="Imprimir" src="'+baseurl+'/assets/images/print.png" onclick="imprimir_carac_inmueble('+item.idI+')"></a></td></tr>');	
							}else{
								$('#tablaDisponibles').append('<tr class="disponibles"><td><span class="badge" style="background:#10af00">'+item.idI+'</span></td><td>'+item.inmueble+'</td><td>'+item.edificio+'</td><td>'+item.barrio+'</td><td>'+item.dorm+'</td><td>'+item.cochera+'</td><td align=right>'+item.valor+'</td><td>'+item.operacion+'</td><td>'+item.locador+'</td><td><a href="#ver_mas" data-toggle="modal"><img class="vermas" title="Ver mas" src="'+baseurl+'/assets/images/ver.png"  onclick="ver_mas('+item.idI+')"></a></td><td><a href="javascript:void(0);"><img class="vermas" title="Imprimir" src="'+baseurl+'/assets/images/print.png" onclick="imprimir_carac_inmueble('+item.idI+')"></a></td></tr>');	
							}	
							if(item.cantidad){
								$('#cantidad').html(item.cantidad+' registros');								
							}

						})	
					
					});			
				$('#cantidad').html('Sin registros');	
				
				$('#disponibles').modal('show');				
				//alert(id);
			}

			function ver_mas(idI){
				$(".modal-body1").html("");
				$.post(baseurl+"main/getCaracteristicasInmuebles/"+idI,
					function(data){
						$(".modal-body1").html(data);
					})
				
			}

				$('.modal-child').on('show.bs.modal', function () {
				    var modalParent = $(this).attr('data-modal-parent');
				    $(modalParent).css('opacity', 0);
				    
				});
				 
				$('.modal-child').on('hidden.bs.modal', function () {
				    var modalParent = $(this).attr('data-modal-parent');
				    $(modalParent).css('opacity', 1);
				});

			$('.nuevo').hover(function() {
				$(this).css('cursor','pointer');
			});				




	}

			function requisitos_alquilar(){
				//$(".areaRequisitos").val("");
				$.post(baseurl+"main/getRequisitosAlquilar/",
					function(data){
						$("#areaRequisitos").html(data);
					})

					$('#requisitos').modal('show');				
			}

	if(url1 == "http://"+host+"/SGI/Tecnico/tecnico"){
			$("#texto_centro").text("Técnicos");
			$("#link1").text("Datos Generales");			
			$("#link1").css("color", "yellow");

			$("#link2").text("Técnicos");
			$('#link2').attr("href","");
			$("#link2").css("color", "yellow");

			$(".dataTablesContainer").css('position','relative');
			$(".dataTablesContainer").css('margin-top','12px');
			

			$(".datatables-add-button").css('margin-top','49px');
			$(".datatables-add-button").css('margin-left','190px');

			$('a[href*="Tecnico/tecnico/add"]').css('background','#9af4ff');
			$('a[onclick*="javascript"]').css('background','#fffd9a');
	
	}	
	

	if(url1 == "http://"+host+"/SGI/Persona/ver_propietarios"){
			$("#texto_centro").text("Propietarios");
			$("#link1").text("Propietarios");
			$('#link1').attr("href","");
			$("#link1").css("color", "yellow");

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');		

		$('a[href*="Propietario/ver_alquileres/"]').css('background','#9af4ff');		
	}

	if(url1 == "http://"+host+"/SGI/Alquiler/alquiler_reclamos"){
			$("#texto_centro").text("Administración de Reclamos");
			$("#link1").text("Alquileres");
			
			$("#link1").css("color", "yellow");

			$("#link2").text("Reclamos");
			$('#link2').attr("href","");
			$("#link2").css("color", "yellow");

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');		

		var button = '<input type="button" value="Reportes" id="id_reporte" name="reporte_reclamo" class="reporte"/>';

		//$( ".tabla" ).append(button);
		$(".reporte").css('margin-top','10px');
		$(".reporte").css('margin-left','190px');
		$(".reporte").css('background', 'white');
		$(".reporte").css('border-radius', '5px');

		$(".reporte").hover(function(){
		  $(this).css("background-color", "yellow");
		  }, function(){
		  $(this).css("background-color", "white");
		});	

		$('.reporte').click(function() {
   			window.location = "http://"+host+"/SGI/Alquiler/reclamos_reportes";
		});


		$('a[href*="Reclamo/reclamo/add"]').css('background','#9af4ff');
		
	}

	if(url1 == "http://"+host+"/SGI/Alquiler/reclamos_reportes"){
				$("#texto_centro").text("Reportes: Reclamos");

				$("#link1").text("Reportes");				
				$("#link1").css("color", "yellow");				

				$("#link2").text("Reclamos");
				$('#link2').attr("href","");
				$("#link2").css("color", "yellow");

			$(".dataTablesContainer").css('position','relative');
			$(".dataTablesContainer").css('margin-top','35px');	

			$('a[href*="Alquiler/imprimir_reportes/"]').css('background','#9af4ff');	
	}

	if(url1 == "http://"+host+"/SGI/Pago/caja_reportes"){
				$("#texto_centro").text("Reportes: Caja");

				$("#link1").text("Reportes");				
				$("#link1").css("color", "yellow");				

				$("#link2").text("Caja");
				$('#link2').attr("href","");
				$("#link2").css("color", "yellow");

			$(".dataTablesContainer").css('position','relative');
			$(".dataTablesContainer").css('margin-top','35px');		
	}	

	if(url1 == "http://"+host+"/SGI/Alquiler/deudores_reportes"){

				$("#texto_centro").text("Reportes: Inquilinos Morosos");

				$("#link1").text("Reportes");				
				$("#link1").css("color", "yellow");				

				$("#link2").text("Inquilinos morosos");
				$('#link2').attr("href","");
				$("#link2").css("color", "yellow");

				moment.locale('es');
				var venc_actual=moment().format('MMM-YY');
				
				$("#link4").text('Vencimiento Actual: '+venc_actual.toUpperCase());
				$("#link4").css("color", "yellow");

			$(".dataTablesContainer").css('position','relative');
			$(".dataTablesContainer").css('margin-top','35px');	

		var button = '<input type="button" value="Imprimir Reporte " id="id_deudores" name="deudores_reportes" class="deudores_reportes"/>';

		$( ".tabla" ).append(button);

		$(".deudores_reportes").css('margin-top','10px');
		$(".deudores_reportes").css('margin-left','190px');
		$(".deudores_reportes").css('background', 'white');
		$(".deudores_reportes").css('border-radius', '5px');
		$(".deudores_reportes").css('z-index','-1');

		$(".deudores_reportes").hover(function(){
		  $(this).css("background-color", "#dbd6ff");
		  }, function(){
		  $(this).css("background-color", "white");
		});	

		$('.deudores_reportes').click(function() {
   			window.location = "http://"+host+"/SGI/Alquiler/imprimir_deudores_reportes";
   			//alert("hola");
		});				
	}	

	if(url1 == "http://"+host+"/SGI/Reporte/reporte_caja"){
				$("#texto_centro").text("Reportes: Caja");

				$("#link1").text("Reportes");				
				$("#link1").css("color", "yellow");				

				$("#link2").text("Caja");
				$('#link2').attr("href","");
				$("#link2").css("color", "yellow");

			$(".dataTablesContainer").css('position','relative');
			$(".dataTablesContainer").css('margin-top','35px');

			$(function(){
			    $("#imprimirdiario").click(function(){
			    	var dia;
			    	dia=$("#dia").text();

			        window.location = "http://"+host+"/SGI/Reporte/imprimir_caja_diaria/"+dia;
			        //alert(dia);
			    });
			});

			$(function(){
			    $("#excel").click(function(){
			    	/*var dia;
			    	dia=$("#dia").text();*/

			        window.location = "http://"+host+"/SGI/Reporte/excel";
			        //alert("Hola");
			    });
			});

			/*$("table").tablesorter({
				sortList:[[0,0],[2,1]]
			})
			$("tbody tr").mouseenter(function(){
				$($this).css("background-color","#CCC");
			})
			$("tbody tr").mouseleave(function(){
				$($this).css("background-color","#6E6E6E");
			})*/	
			$(function () {
			  	$('#search').quicksearch('#caja tbody tr');								
			});			
				
	}				



	if(url1 == "http://"+host+"/SGI/Alquiler/alquileres_finalizados"){
			$("#texto_centro").text("Alquileres Finalizados - Rescindidos");
			$("#link1").text("Alquileres");
			
			$("#link1").css("color", "yellow");

			$("#link2").text("Alquileres Finalizados");
			$('#link2').attr("href","");
			$("#link2").css("color", "yellow");

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');		

	 		var i,url,e;
	 		var id;	
	 	 	e=4;
	 	 	var n =$("tr[id*='row-']").length;

	 	 	//$('a[href*="Alquiler/eliminar_alquiler"]').css('background','#fffd9a');
	 	 	$('a[href*="eliminar_alquiler"]').css('background','#FF8181');	

	 		for (i=0; i < n; i++ ){
		 	 	id = $('#row-'+i).find('td').eq(0).text();

			 	 $('a[href$="Alquiler/eliminar_alquiler/'+id+'"]').click(function(){
					var opcion = confirm("¿Eliminar Alquiler?");
					if (opcion == true) {
			        	return true;
					} else {				    	
				    	//alert(id);
				    	return false;
					}
				});			 	 	
	 		}	
	}

	if(url == "http://"+host+"/SGI/Verpago/verpago_finalizados/"){
			$("#texto_centro").text("Alquileres Finalizados - Rescindidos: Pagos");
			$("#link1").text("Alquileres");
			
			$("#link1").css("color", "yellow");

			$("#link2").text("Alquileres Finalizados");
			$('#link2').attr("href","http://"+host+"/SGI/Alquiler/alquileres_finalizados");
			$("#link2").css("color", "yellow");

			$("#link3").text("Pagos");
			$('#link3').attr("href","javascript:history.go(0)");
			$("#link3").css("color", "yellow");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');

		var n =$("tr[id*='row-']").length;
		var id,i;

		for (i=0; i < n; i++ ){
			 orden = $('#row-'+i).find('td').eq(0).text();

			 	if(orden=="x"){
			 		$('#row-'+i).css('color','red');
			 		$('#row-'+i).find('td').attr('align','left');
			 		//$('a[href="http://'+host+'/SGI/Pago/pagos_edit/edit/'+idpago+'"]').remove();
			 	}

			var pagado=$('#row-'+i).find('td').eq(13).text();

				if(i==n-1 && pagado=="NO"){
					 idpago = $('#row-'+i).find('td').eq(1).text();
					//var boton='&nbsp<span title="Anular Pago" id="anularPago" style="background: red" class="badge badge-pill">X</span>';
					var boton="<img src='"+baseurl+"/assets/images/cancelar.png' title='Anular Pago' id='anularPago'>";
					$('a[href$="Verpago/read/'+idpago+'"]').after(boton);

				    $('#anularPago').hover(function() {
				        $(this).css('cursor','pointer');
				    });		

					 	$('#anularPago').click(function(){
								var opcion = confirm("¿Cancelar Pago N°"+idpago+' ?');
								if (opcion == true) {
						        	window.location="http://"+host+"/SGI/Pago/cancelar/"+idpago;
								} else {							    	
							    	return false;
								}
	 					});

				}
		}

			
	}			

	if(url == "http://"+host+"/SGI/Reclamo/ver_reclamos/"){
			$("#texto_centro").text("Reclamos");
			$("#link1").text("Reclamos");
			$('#link1').attr("href","http://"+host+"/SGI/Alquiler/alquiler_reclamos");
			$("#link1").css("color", "yellow");

			$("#link2").text("Ver");
			$('#link2').attr("href","javascript:history.go(0)");
			$("#link2").css("color", "yellow");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');

			var dato=$(".texto").html();

			$("#datos").html(dato);

		$('a[href*="Reclamo/atender_reclamos/edit"]').css('background','#9af4ff');	
		$('a[onclick*="javascript"]').css('background','#fffd9a');
	}

	if(url == "http://"+host+"/SGI/Reclamo/reclamo/add/"){
			$("#texto_centro").text("Reclamos - Nuevo");
			$("#link1").text("Cancelar");
			$('#link1').attr("href","javascript:history.go(-1)");
			$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');				

	}



	if(url == "http://"+host+"/SGI/Propietario/ver_alquileres/"){
			$("#texto_centro").text("Liquidación de Alquileres/Comodatos - Propietario: ");
			$("#link1").text("Propietarios");
			$('#link1').attr("href","http://"+host+"/SGI/Persona/ver_propietarios");
			$("#link1").css("color", "yellow");

			$("#link2").text("Alquileres/Comodatos");
			$('#link2').attr("href","");
			$("#link2").css("color", "yellow");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');				

			var dato=$(".texto").html();

			$("#datos").html(dato);

			$('a[href*="Liquidacion/pagos/"]').css('background','#fffd9a');
	}	

	if(url == "http://"+host+"/SGI/Liquidacion/pagos/"){
			$("#texto_centro").text("Liquidación");
			$("#link1").text("Propietarios");
			$('#link1').attr("href","http://"+host+"/SGI/Persona/ver_propietarios");
			$("#link1").css("color", "yellow");

			var operacion=$("#operacion").text();

			if(operacion=="Comodato"){$("#link2").text("Comodatos");}
			if(operacion=="Alquiler"){$("#link2").text("Alquileres");}
			
			$('#link2').attr("href","javascript:history.go(-1)");
			$("#link2").css("color", "yellow");	

			$("#link3").text("Liquidaciones Pendientes");
			$('#link3').attr("href","javascript:history.go(0)");
			$("#link3").css("color", "yellow");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');						

			var dato=$(".texto").html();

			$("#datos").html(dato);

			$('a[href*="Liquidacion/liquidar/add/"]').css('background','#70ff46');
	}

	if(url == "http://"+host+"/SGI/Liquidacion/liquidar/add/"){
			$("#texto_centro").text("Propietarios - Nueva Liquidación");
			$("#link1").text("Cancelar");
			$('#link1').attr("href","javascript:history.go(-1)");
			$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');				
	}

	if(url == "http://"+host+"/SGI/Liquidacion/liquidar_read/read/"){
		$("#texto_centro").text("Liquidaciones Anteriores");
		$("#link1").text("Atrás");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");
	}

	if(path == "/SGI/Usuario/usuario"){
			$("#texto_centro").text("Administración de Usuarios");
			$("#link1").text("Usuarios");
			$('#link1').attr("href","");
			$("#link1").css("color", "yellow");

			$('a[onclick*="javascript"]').css('background','#fffd9a');
			$('a[href*="Usuario/usuario/add"]').css('background','#9af4ff');
	}

	if(path == "/SGI/Inmueble/inmueble"){

		$("#texto_centro").text("Administración de Inmuebles");
		$("#link1").text("Inmuebles");
		$('#link1').attr("href","http://"+host+"/SGI/Inmueble/inmueble");
		$("#link1").css("color", "yellow");

		$("#link2").text("Listado de Inmuebles");
		$('#link2').attr("href","");
		$("#link2").css("color", "yellow");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','12px');
		//$(".dataTablesContainer").css('float','left');

		$(".datatables-add-button").css('margin-top','49px');
		$(".datatables-add-button").css('margin-left','190px');		

		$('a[href*="Alquiler/alquiler/"]').css('background','#70ff46');	
		$('a[href*="Inmueble/reservar/"]').css('background','#9af4ff');	
		$('a[href*="Inmueble/inmueble/edit"]').css('background','#fffd9a');	
		$('a[class*="delete"]').css('background','#FF8181');	

		$('input[onclick^=cancelar_reserva]').css('background-color','orange');

		$('button[onclick^=cancelar_renovacion]').css('background-color','orange');

		$('a[href*="Inmueble/inmueble/add"]').css('background','#9af4ff');

		var dato=$(".texto").html();

		$("#datos").html(dato);
			


	 

// aca tomo la url para saber en q pagina estoy y dejo sin efecto el boton alquilar cuando el inmueble ya se encuentra alquilado
		var valor = $("tr td")[11].innerHTML;

	 	//var n = $('tbody >tr').length; 
	 	var estado;
	 	var i,url,e;
	 	var id;
	 	var n =$("tr[id*='row-']").length;




	 	//$('button[onclick^=cancelar_renovacion]').css('background','#fffd9a');

	 	for (i=0; i < n; i++ ){
	 		id = $('#row-'+i).find('td').eq(0).text();
	 	 	estado=$('#row-'+i).find('td').eq(1).text();

	 	 	//alert(id);
	 	 	if(estado =='ALQUILADO'){ 	//estado=1	
	 	 		
	 	 		$('a[href="http://'+host+'/SGI/Alquiler/alquiler/add/'+id+'"]').remove();	 	 		
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/reservar/add/'+id+'"]').remove();
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/cancelar_renovacion/'+id+'"]').remove();
	 	 		//$('#row-'+i).find('a[onclick]').remove();
	 	 		
	 	 	}else if(estado =='ALQ.RESERV' ){ //estado=2
	 	 		var boton="<input type='button' onclick='cancelar_reserva("+id+")' value='Cancelar Reserva'>";

	 	 		$('a[href="http://'+host+'/SGI/Alquiler/alquiler/add/'+id+'"]').remove();
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/reservar/add/'+id+'"]').remove();
	 	 		$('#row-'+i).find('a[onclick]').remove();
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/cancelar_renovacion/'+id+'"]').remove();

	 	 		$('a[href$="Inmueble/inmueble/edit/'+id+'"]').after(boton);

	 	 	}else if(estado =="DISP.RESERV" ){ //estado=3
	 	 		//var boton="<button onclick='cancelar_reserva("+id+")'>Cancelar Reserva</button>";	
	 	 		var boton="<input type='button' onclick='cancelar_reserva("+id+")' value='Cancelar Reserva'>";

	 	 		$('a[href="http://'+host+'/SGI/Inmueble/reservar/add/'+id+'"]').remove();
	 	 		$('#row-'+i).find('a[onclick]').remove();	
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/cancelar_renovacion/'+id+'"]').remove();
	 	 		$('a[href$="Inmueble/inmueble/edit/'+id+'"]').after(boton);

	 	 	}else if(estado =='ALQ.RENUEV' ){ //estado=4
	 	 		var boton="<button onclick='cancelar_renovacion("+id+")'>No Renueva</button>";
	 	 		

	 	 		$('a[href="http://'+host+'/SGI/Alquiler/alquiler/add/'+id+'"]').remove();
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/reservar/add/'+id+'"]').remove();

	 	 		$('a[href$="Inmueble/inmueble/edit/'+id+'"]').after(boton);
	 	 		$('#row-'+i).find('a[onclick]').remove();

	 	 	}else if(estado =='ALQ.NO.REN' ){ //estado=5

	 	 		$('a[href="http://'+host+'/SGI/Alquiler/alquiler/add/'+id+'"]').remove();	 	 		
	 	 		//$('#row-'+i).find('a[onclick]').remove();
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/cancelar_renovacion/'+id+'"]').remove();
	 	 	}else if(estado =='ALQ.RESCINDE' ){ //estado=5

	 	 		$('a[href="http://'+host+'/SGI/Alquiler/alquiler/add/'+id+'"]').remove();	 	 		
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/reservar/add/'+id+'"]').remove();
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/cancelar_renovacion/'+id+'"]').remove();
	 	 	}else if(estado =='DISP.RENUEV' ){ //estado=6
	 	 		//alert(id)
	 	 		var boton="<button onclick='cancelar_renovacion("+id+")'>No Renueva</button>";
	 	 		$('a[href="http://'+host+'/SGI/Inmueble/reservar/add/'+id+'"]').remove();
	 	 		$('#row-'+i).find('a[onclick]').remove();
	 	 		$('a[href$="Inmueble/inmueble/edit/'+id+'"]').after(boton);
	 	 		//$('a[href="http://'+host+'/SGI/Inmueble/inmueble/edit/'+id+'"]').after(boton);
	 	 		
	 	 	}

	 	}

	 	 				function cancelar_renovacion(id){
	 	 					//alert(id);
								var opcion = confirm("¿Cancelar Renovación?");
								if (opcion == true) {									;	
						        	window.location="http://"+host+"/SGI/Inmueble/cancelar_renovacion/"+id;						        	
								} else {							    	
							    	return false;
								}
	 					};	

	 	 				function cancelar_reserva(id){
	 	 					//alert(id);
								var opcion = confirm("¿Cancelar Reserva?");
								if (opcion == true) {									;	
						        	window.location="http://"+host+"/SGI/Inmueble/cancelar_reserva/"+id;						        	
								} else {							    	
							    	return false;
								}
	 					};		 					

	 					





	 	
	}//fin if

	if(url == "http://"+host+"/SGI/Inmueble/inmueble/read/"){
/*	 	$("#imprimir_inmueble").click(function(){
			window.location.href = "http://"+host+"/SGI/Pago/descargar_pdf";
		});	*/
		alert("aca");
	}

	
	//fin
	if(path == "/SGI/Inmueble/reservar"){	
		$("#texto_centro").text("Reservas de Inmuebles");
		$("#link1").text("Inmuebles");
		
		$("#link1").css("color", "yellow");
		$("#link2").text("Reservas");
		$('#link2').attr("href","");
		$("#link2").css("color", "yellow");

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');		


		var valor = $("tr td")[8].innerHTML;

	 	var n =$("tr[id*='row-']").length;
	 	
	 	var estado;
	 	var i,url,e;
	 	var id;	
	 	 e=4;

	 	for (i=0; i < n; i++ ){	 		
	 	 	//estado = document.getElementById("row-".concat(i)).cells[3].innerHTML;
	 	 	//id = document.getElementById("row-".concat(i)).cells[0].innerHTML;	
	 	 	//estado=$("#estado").text();

	 	 	id = $('#row-'+i).find('td').eq(0).text();
	 	 	estado=$('#row-'+i).find('td').eq(3).text();
	 	 	

	 	 	if(estado =="ALQUILADO"){
	 	 	 $("td:eq(" + e + ")").css( "color", "red" ).css("font-weight","bolder"); 

	 	 	 e=e+9;
	 	 	}else if(estado =="DISPONIBLE"){
				$("td:eq(" + e + ")").css( "color", "green" ).css("font-weight","bolder"); 
				e=e+9;	 	 	
	 	 	}

		 	 $('a[href$="Inmueble/eliminar_reserva/'+id+'"]').click(function(){
				var opcion = confirm("¿Cancelar reserva?");
				if (opcion == true) {
		        	return true;
				} else {
			    	return false;
				}
			});		 	 	
	 	}

	}

	// para poner estado del alquiler, AL DIA, CON DEUDA

	if(url1 == "http://"+host+"/SGI/Alquiler/alquiler" ){// || url1 == "http://"+host+"/SGI/Alquiler/alquiler#" 
		$("#texto_centro").text("Administración de Alquileres");
		$("#link1").text("Alquileres");		
		$("#link1").css("color", "yellow");
		$("#link2").text("Listado de Alquileres");
		$('#link2').attr("href","");
		$("#link2").css("color", "yellow");
	
		$("#link4").text("Ir a Comodatos");
		$('#link4').attr("href","http://"+host+"/SGI/Comodato/comodato");
		$("#link4").css("color", "yellow");

		$("#flag").text("alquiler");
		$("#flag").css('display','none');		

		
		//$(".menu_centro").css('margin-left','50%');

		$(".datatables-add-button").hide();	//OCULTAR EL BOTON AÑADIR ALQUILER

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');	


	 	//var n = $('tbody >tr').length;	 
	 	var n =$("tr[id*='row-']").length;

	 	
	 	var estado;
	 	var i,x,y,c;
	 	var id;
	 	var hoy = moment();	

	 	var hoy_comparar=moment(hoy.format('YYYY/MM/DD'));	





	 	var fecha_vto; 	 	
	 	var fecha; 
	 	x=9;//8
	 	y=2;
	 	c=0;

	 	
	

	 	for (i=0; i < n; i++ ){	
	 	 	id = $('#row-'+i).find('td').eq(0).text();
	 	 	estado=$('#row-'+i).find('td').eq(9).text();//9	

	 	 	var cant_pagos=$('#row-'+i).find('td').eq(10).text();


	 	 	if(cant_pagos==0){
	 	 		var boton='&nbsp<span id="anular" style="background: red" class="badge badge-pill" onclick="anular_contrato('+id+')">Anular</span>';

	 	 		$('a[href$="Alquiler/edit/'+id+'"]').after(boton);

			    $('#anular').hover(function() {
			        $(this).css('cursor','pointer');
			    });		 	 		
	 	 	}

 	 		$('a[href$="Pago/pagar/add/'+id+'"]').css('background','#70ff46');

 	 		$('a[href$="Verpago/verpago/'+id+'"]').css('background','#9af4ff');

 	 		$('a[href*="Alquiler/alquiler_edit/"]').css('background','#fffd9a');

 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').css('background','orange');	
 	 		$('a[href$="Alquiler/finalizar_contrato/'+id+'"]').css('background','#FF8181');	
	 	 	$('a[href$="Alquiler/cancelar_renueva/'+id+'"]').css('background','orange');

	 	 	if(cant_pagos<6){
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();
	 	 	}	 	 	

			$('a[href$="Alquiler/alquiler/edit/'+id+'"]').remove();

			if(estado == "FINALIZA"){	
			 	//alert(i);	
	 	 		$('a[href$="Alquiler/cancelar_renueva/'+id+'"]').remove();
	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();


	 	 		
	 	 		$('a[href$="Alquiler/finalizar_contrato/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Confirma Finalizar Alquiler?");
				 	if(opcion == true){
	        			return true;
					}else{
		    			return false;
					}
				});				
	 	 	}else if(estado == "VIGENTE"){	 

	 	 		$('a[href$="Alquiler/cancelar_renueva/'+id+'"]').remove();	 	 		
	 	 		$('a[href$="Alquiler/finalizar_contrato/'+id+'"]').remove();

	 	 	}else if(estado == "VIG.RESCINDE"){	 

	 	 		$('a[href$="Alquiler/cancelar_renueva/'+id+'"]').remove();	 	 		
	 	 		$('a[href$="Alquiler/finalizar_contrato/'+id+'"]').remove();

	 	 	}else if(estado == "RESCINDE"){	 
	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Alquiler/cancelar_renueva/'+id+'"]').remove();	 	 		
	 	 		$('a[href$="Alquiler/finalizar_contrato/'+id+'"]').remove();

	 	 	}else if(estado == "RENUEVA"){
	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();


	 	 		$('a[href$="Alquiler/finalizar_contrato/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Confirma Finalizar Alquiler?");
				 	if (opcion == true) {
	        			return true;
					} else {
		    			return false;
					}
				});

				$('a[href$="Alquiler/cancelar_renueva/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Cancela la Renovación?");
				 	if (opcion == true) {
	        			return true;
					} else {
		    			return false;
					}
				});
	 	 	}else if(estado == "RESCINDIDO"){
	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();
	 	 		$('a[href$="Alquiler/cancelar_renueva/'+id+'"]').remove();

	 	 		$('a[href$="Alquiler/finalizar_contrato/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Confirma Finalizar Alquiler?");
				 	if (opcion == true) {
	        			return true;
					} else {
		    			return false;
					}
				});	 	 		
	 	 	}


	 	 	fecha = $("td:eq( " + x + ")").html();//columna prox. vencimiento
	 	 	//alert(fecha);

//pruebas

		var fecha_f1=moment(fecha,'DD/MM/YYYY');
		var fecha_f2=fecha_f1.format('YYYY/MM/DD');

	 	var diferencia=hoy_comparar.diff(fecha_f2,'days');


//pruebas	 	 	

	 	 	
	 	 	if(fecha != null){	 	 	
		 	 	var fecha_f = moment(fecha).format('DD/MM/YYYY');
		 	 	//fecha_vto=Date.parse(moment(fecha_f));	

		 	 	var fecha_vto=Date.parse(moment(fecha_f).format('MM/DD/YYYY'));
		 	 	//alert(fecha_vto);
		 	 	x=x+12; //11	

		 	 	//if(fecha_vto >= hoy_comparar){
		 	 	if(diferencia<=0){
		 	 		
		 	 		//$("td:eq( " + y + ")").html("AL DIA").css( "color", "green" ).css("font-weight","bolder"); 

		 	 		$("td:eq( " + y + ")").html("<span style='background: green' class='badge badge-pill'>AL DIA</span>"); 

		 	 		c=y+7;//6
		 	 		$("td:eq(" + c + ")").css( "color", "green" ).css("font-weight","bolder");	 //procVenci	

		 	 		c=c-2;//locatario en rojo

		 	 		$("td:eq(" + c + ")").css( "color", "green" ).css("font-weight","bolder");	

		 	 		c=y+9;

		 	 		$("td:eq( " + c + ")").html("<span style='background: green' class='badge badge-pill'>"+cant_pagos+"</span>");

		 	 	}else {		 	 		

		 	 		$("td:eq( " + y + ")").html("<span style='background: red' class='badge badge-pill'>DEUDA</span>");

		 	 		c=y+7;//7
		 	 		$("td:eq(" + c + ")").css( "color", "red" ).css("font-weight","bolder");//proxVenci

		 	 		c=c-2;//locatario en rojo

		 	 		$("td:eq(" + c + ")").css( "color", "red" ).css("font-weight","bolder");

		 	 		c=y+9;

		 	 		$("td:eq( " + c + ")").html("<span style='background: red' class='badge badge-pill'>"+cant_pagos+"</span>");

		 	 	}
		 	 	y=y+12;//11
		 	 }	
	 	 }//CIERRE DE FOR

	 	 function anular_contrato(id){
			var opcion = confirm("¿Anular Contrato?");
			if (opcion == true) {									;	
				window.location="http://"+host+"/SGI/Alquiler/anular_contrato/"+id;						        	
			}else{							    	
				return false;
			}
	 	 }

	}
	//FIN

if(url1 == "http://"+host+"/SGI/Comodato/comodato" ){// || url1 == "http://"+host+"/SGI/Alquiler/alquiler#" 
		$("#texto_centro").text("Administración de Comodatos");
		$("#link1").text("Alquileres");		
		$("#link1").css("color", "yellow");
		$("#link2").text("Listado de Comodatos");
		$('#link2').attr("href","");
		$("#link2").css("color", "yellow");
	
		$("#link4").text("Ir a Alquileres");
		$('#link4').attr("href","http://"+host+"/SGI/Alquiler/alquiler");
		$("#link4").css("color", "yellow");

		$("#flag").text("comodato");
		$("#flag").css('display','none');
		
		//$(".menu_centro").css('margin-left','50%');

		$(".datatables-add-button").hide();	//OCULTAR EL BOTON AÑADIR ALQUILER

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');	

		//$(".search_edificio").val("nala g");

	 	//var n = $('tbody >tr').length;	 
	 	var n =$("tr[id*='row-']").length;

	 	
	 	var estado;
	 	var i,x,y,c;
	 	var id;
	 	var hoy = moment();	

	 	var hoy_comparar=moment(hoy.format('YYYY/MM/DD'));	

	 	var fecha_vto; 	 	
	 	var fecha; 
	 	x=9;//6
	 	y=2;
	 	c=0;

	 	//$('#row-0').find('td').eq(0).attr('width','5px'); ajusta el ancho de la primer columna

	 	for (i=0; i < n; i++ ){	
	 	 	id = $('#row-'+i).find('td').eq(0).text();
	 	 	estado=$('#row-'+i).find('td').eq(9).text();//9	
	 	 	//alert(estado);

	 	 	var cant_pagos=$('#row-'+i).find('td').eq(10).text();

	 	 	$('a[href$="Comodato/finalizar_contrato/'+id+'"]').css('background','#FF8181');	 
	 	 	$('a[href$="Comodato/cancelar_renueva/'+id+'"]').css('background','#fffd9a');
	 	 	$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').css('background','orange');

	 	 	$('a[href$="Verpago/verpago/'+id+'"]').css('background','#9af4ff');

	 	 	$('a[href$="Pago/pagar/add/'+id+'"]').css('background','#70ff46');

	 	 	$('a[href*="Comodato/comodato_edit/edit/"]').css('background','#fffd9a');


	 	 	if(cant_pagos==0){
	 	 		var boton='&nbsp<span id="anular" style="background: red" class="badge badge-pill" onclick="anular_contrato('+id+')">Anular</span>';

	 	 		$('a[href$="Comodato/comodato_edit/edit/'+id+'"]').after(boton);

			    $('#anular').hover(function() {
			        $(this).css('cursor','pointer');
			    });		 	 		
	 	 	}

	 	 	if(cant_pagos==0){
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();
	 	 	}	 	 	

			$('a[href$="Comodato/comodato/edit/'+id+'"]').remove();

			if(estado == "FINALIZA"){	
			 	//alert(i);	
	 	 		$('a[href$="Comodato/cancelar_renueva/'+id+'"]').remove();
	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();

	 	 		
	 	 		
	 	 		$('a[href$="Comodato/finalizar_contrato/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Confirma Finalizar Comodato?");
				 	if(opcion == true){
	        			return true;
					}else{
		    			return false;
					}
				});				
	 	 	}else if(estado == "VIGENTE"){	 	 		
	 	 		$('a[href$="Comodato/cancelar_renueva/'+id+'"]').remove();	 	 		
	 	 		$('a[href$="Comodato/finalizar_contrato/'+id+'"]').remove();

	 	 	}else if(estado == "VIG.RESCINDE"){	 

	 	 		$('a[href$="Comodato/cancelar_renueva/'+id+'"]').remove();	 	 		
	 	 		$('a[href$="Comodato/finalizar_contrato/'+id+'"]').remove();

	 	 	}else if(estado == "RESCINDE"){	 
	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Comodato/cancelar_renueva/'+id+'"]').remove();	 	 		
	 	 		$('a[href$="Comodato/finalizar_contrato/'+id+'"]').remove();

	 	 	}else if(estado == "RENUEVA"){

	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();	 	 		

	 	 		$('a[href$="Comodato/finalizar_contrato/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Confirma Finalizar Comodato?");
				 	if (opcion == true) {
	        			return true;
					} else {
		    			return false;
					}
				});

				$('a[href$="Comodato/cancelar_renueva/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Cancela la Renovación?");
				 	if (opcion == true) {
	        			return true;
					} else {
		    			return false;
					}
				});
	 	 	}else if(estado == "RESCINDIDO"){
	 	 		$('a[href$="Pago/pagar/add/'+id+'"]').remove();
	 	 		$('a[href$="Pago/rescindir_contrato/add/'+id+'"]').remove();
	 	 		$('a[href$="Comodato/cancelar_renueva/'+id+'"]').remove();

	 	 		$('a[href$="Comodato/finalizar_contrato/'+id+'"]').click(function(){
				 	var opcion = confirm("¿Confirma Finalizar Comodato?");
				 	if (opcion == true) {
	        			return true;
					} else {
		    			return false;
					}
				});	 	 		
	 	 	}


	 	 fecha = $("td:eq( " + x + ")").html();//columna prox. vencimiento


		var fecha_f1=moment(fecha,'DD/MM/YYYY');
		var fecha_f2=fecha_f1.format('YYYY/MM/DD');

	 	var diferencia=hoy_comparar.diff(fecha_f2,'days');

	 	 	
	 	 	if(fecha != null){	 	 	
		 	 	var fecha_f = moment(fecha).format('DD/MM/YYYY');
		 	 	//fecha_vto=Date.parse(moment(fecha_f));	

		 	 	var fecha_vto=Date.parse(moment(fecha_f).format('MM/DD/YYYY'));
		 	 	//alert(fecha_vto);
		 	 	x=x+12; //11	

		 	 	//if(fecha_vto >= hoy_comparar){
		 	 	if(diferencia<=0){		 	 		

		 	 		//$("td:eq( " + y + ")").html("AL DIA").css( "color", "green" ).css("font-weight","bolder"); 

		 	 		$("td:eq( " + y + ")").html("<span style='background: green' class='badge badge-pill'>AL DIA</span>"); 

		 	 		c=y+7;//6
		 	 		$("td:eq(" + c + ")").css( "color", "green" ).css("font-weight","bolder");	 	

		 	 		c=c-2;//locatario en rojo

		 	 		$("td:eq(" + c + ")").css( "color", "green" ).css("font-weight","bolder");	

		 	 		c=y+9;

		 	 		$("td:eq( " + c + ")").html("<span style='background: green' class='badge badge-pill'>"+cant_pagos+"</span>");		 	 		

		 	 	}else{		 	 		

		 	 		//$("td:eq( " + y + ")").html("DEUDA").css( "color", "red" ).css("font-weight","bolder");
		 	 		$("td:eq( " + y + ")").html("<span style='background: red' class='badge badge-pill'>DEUDA</span>");

		 	 		c=y+7;//7
		 	 		$("td:eq(" + c + ")").css( "color", "red" ).css("font-weight","bolder");

		 	 		c=c-2;//locatario en rojo

		 	 		$("td:eq(" + c + ")").css( "color", "red" ).css("font-weight","bolder");

		 	 		c=y+9;

		 	 		$("td:eq( " + c + ")").html("<span style='background: red' class='badge badge-pill'>"+cant_pagos+"</span>");		 	 		

		 	 	}
		 	 	y=y+12;//11
		 	 }		
	 	 }//CIERRE DE FOR

	 	 function anular_contrato(id){
			var opcion = confirm("¿Anular Comodato?");
			if (opcion == true) {									;	
				window.location="http://"+host+"/SGI/Comodato/anular_comodato/"+id;						        	
			}else{							    	
				return false;
			}
	 	 }	 	 

	}
	//FIN





//ACAAAAAAAAAAAAAAAAA
/*if(url == "http://localhost/SGI/Alquiler/alquiler/read/"){

			$("body").prepend("<table  border='2' width='100%' id='table-header'><tbody><tr><th id='header'><h4>LIQUIDACIÓN A PROPIETARIOS</h4></th></tr></tbody></table>");
			$("body").css("padding-top","75px");

		$("#header").css("color","white");
		$(".texto").css("color","yellow");
		$("#header").css("text-align","left");
		$("#header").css('background-color', '#016700');
		$("#header").css("font-weight","bolder");
		$("#header").css('text-align','center');
}*/

/*if(url == "http://localhost/SGI/Liquidacion/liquidar/"){
	$(".datatables-add-button").hide();
}*/

/*if(url1 == "http://localhost/SGI/Alquiler/alquiler"){
	//window.location.href = "http://localhost/SGI/Alquiler/alquiler";
	alert("hola");	
}*/


if(url == "http://"+host+"/SGI/Liquidacion/liquidar/"){
	$(".datatables-add-button").hide();	
	$(".ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only floatR refresh-data").hide();
	
	$('a[href*="Liquidacion/descargar_pdf/"]').css('background','#9af4ff');

	var n =$("tr[id*='row-']").length;
	var x=n;
	for (i=0; i < n; i++ ){
		//alert(i);

			/*$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();			
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();*/

		if(i==0){
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();			
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();			
		}else{
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();			
			$("tbody tr#row-"+i+" td.actions a:eq(3)").remove();
			$("tbody tr#row-"+i+" td.actions a:eq(2)").remove();

		}


		$('a[href*="Liquidacion/liquidar_edit/edit"]').css('background','#fffd9a');


		//$("td.actions a:eq(4)").remove();
		//$("td.actions a:eq(3)").remove();
		//alert(n);	
		var href=$("a[href*='liquidar_edit/edit']").attr("href");			
	}	



		$("#texto_centro").text("Liquidación");
		$("#link1").text("Propietarios");
		$('#link1').attr("href","http://"+host+"/SGI/Persona/ver_propietarios");
		$("#link1").css("color", "yellow");

		
		var dni;
		dni=$('.dni').html();

		var operacion=$("#operacion").text();
		if(operacion=="Comodato"){
			$("#link2").text("Comodatos");
		}else{$("#link2").text("Alquileres");}

		$('#link2').attr("href","http://"+host+"/SGI/Propietario/ver_alquileres/"+dni);
		$("#link2").css("color", "yellow");	

		$("#link3").text("Liquidaciones Anteriores");
		$('#link3').attr("href","javascript:history.go(0)");
		$("#link3").css("color", "yellow");	

		$(".dataTablesContainer").css('position','relative');
		$(".dataTablesContainer").css('margin-top','35px');						

		var dato=$(".texto").html();

		$("#datos").html(dato);

	 	$('a[href*="Liquidacion/eliminar_liquidacion/"]').click(function(){
			var opcion = confirm("¿Confirma eliminar?");
			if (opcion == true) {
	        	return true;
			} else {
		    	return false;
			}
		});		

}




/*if(url == "http://localhost/SGI/Liquidacion/liquidaciones_anteriores/"){
	$(".ui-button-text").hide(); // ocultar boton editar, ver y eliminar
}*/

/*if(url == "http://"+host+"/SGI/Alquiler/edit/"){
	
	$("body").prepend("<table  border='0' width='100%' id='table-header'><tbody><tr><th bgcolor='#016700' width='15%' style='color: #FFFFFF; text-align: center; font-size:17px;font-weight: normal'><div class='fecha'></div></th><th id='header'><h4>Alquileres: Modificar</h4></th><th  id='hora' bgcolor='#016700' width='10%' style='color: #FFFFFF; text-align: center;font-size:17px;font-weight: normal'><div id='contenedor'></div></th></tr></tbody></table>");
	$("body").css("padding-top","75px");
	var today=moment().format("dddd, DD MMMM  YYYY"); 	
	$(".fecha").append(today);
	$("#header").css("color","white");
	$(".texto").css("color","yellow");
	$("#header").css("text-align","left");
	$("#header").css('background-color', '#016700');
	$("#header").css("font-weight","bolder");
	$("#header").css('text-align','center');
}*/


if(url == "http://"+host+"/SGI/Pago/pagos_edit/edit/"){
	moment.locale('ES');

	///CAMBIO EL ATRIBUTO DEL BOTON CANCELAR Y VUELVO ATRAS////TENIA PROBLEMAS CON EL $crud->set_crud_url_path(site_url());
	$("#cancel-button").attr("id","cancelar");
	$("#cancelar").attr("onclick","history.back()");

	 //var set_periodo = document.getElementById('field-periodo');
	 //var periodo = document.getElementById('field-periodo').value;
	 
	// var per=moment(periodo).format('MMM-YY');
	 //set_periodo.value=per.toUpperCase();

	 //var prox_venc_siguiente = moment(periodo).add(1, 'month').format("YYYY/MM/DD");
	 //$("#field-prox_venc_sig").val(prox_venc_siguiente);

	 	$('.numerico').on('input', function () { 
	    	this.value = this.value.replace(/[^0-9\\.]/g,'');
		});	
	 

	 var mora=document.getElementById('field-mora_dias');
	 var hoy = moment();	 

	 var hoy_comparar=Date.parse(hoy.format('MM/DD/YYYY'));

	 var periodo_comparar=Date.parse(moment(periodo).format('MM/DD/YYYY'));
	 
	 var monto_alquiler = parseInt($('#field-valor_alquiler').val());
	 var porc_punitorio = parseFloat($('#porcentaje').text());//tomo el porcentaje del  span
	  var valor_diario = document.getElementById('field-valor-diario');

	  

		//var mora_dias=hoy.diff(periodo, 'days');
		var mora_dias=$("#field-mora_dias").val();
		 var multi_punitorio=(porc_punitorio/100)*mora_dias;

		 var importe_punitorio=document.getElementById('field-punitorios');		  
		 var valorD = monto_alquiler * (porc_punitorio/100);
		 $("#field-valor-diario").val(valorD);
		 var totalmora=parseFloat(monto_alquiler*multi_punitorio);
		 var totalmora_f=totalmora.toFixed(2);
		 $("#total-mora").text(totalmora_f);
		 var mora = $("#total-mora").text();	

	function calcular_punitorios(){
		var mora_dias=$("#field-mora_dias").val();
		var valor_diario=$("#field-valor-diario").val();
		var total_punitorio=mora_dias*valor_diario;
		var total_punitorio_f=total_punitorio.toFixed(2);
		$("#total-mora").text(total_punitorio_f);
	}			  		 
	 

	if(mora_dias > 0){
		$("#field-paga_mora").removeAttr("disabled");
		function pagamora(){
			var paga_mora = document.getElementById('field-paga_mora').value;	
			if(paga_mora =="NO"){
				$("#field-punitorios").val(0);				
			}else if (paga_mora =="SI"){	

					 var mora_dias=$("#field-mora_dias").val();	
					 var multi_punitorio=(porc_punitorio/100)*mora_dias;
					 valor_diario.value=(porc_punitorio/100)*monto_alquiler;
					 var importe_punitorio=document.getElementById('field-punitorios');

					 var totalpunitorio=parseFloat(monto_alquiler*multi_punitorio);
					 var totalpunitorio_f=totalpunitorio.toFixed(2);
					 importe_punitorio.value=totalpunitorio_f;					 
					 mora.value=mora_dias;	

			}	
		}
	}
	var paga=$("#field-paga_c_inmo").val();
	var saldo=$("#saldo_comision").val();
	//$("#field-comision_inmo_debe_p").val(saldo-paga);
	$("#field-comision_inmo_debe_p").val(saldo);
	if(saldo > 0){
		$("#comision").removeAttr("disabled");
		$("#field-paga_c_inmo").removeAttr("disabled");		
		function pagasaldo(){					
			if($("#comision").val()=="SI"){
				$("#field-paga_c_inmo").val(saldo);
				 $("#field-comision_inmo_debe_p").val("0");
				$("#field-paga_c_inmo").focus();				
			}else if($("#comision").val()=="NO"){
				$("#field-comision_inmo_debe_p").val(saldo);	
				$("#field-paga_c_inmo").val("0");					
			}
		}
	}
	//poner en mayusculas al momento de escribir en el text area de PAGOS
	function mayuscula(e) {
	     e.value = e.value.toUpperCase();
	}

	var comision_debe=$("#field-comision_inmo_debe").val();
	var comision_paga=$("#field-comision_inmo_paga").val();

	var ci_a_pagar_anterior=parseFloat(comision_debe) + parseFloat(comision_paga) ;
	var ci_a_pagar_anterior_f=ci_a_pagar_anterior.toFixed(2);

	$("#ci_a_pagar_anterior").val(ci_a_pagar_anterior_f);

	var nro_pago=$("#nro_pago").text();
	if(nro_pago==1){		
			// PAGAR ALQUILER: que el input no supere la comision inmobiaria
		function validar_comision() {
			var saldo = parseFloat($("#ci_a_pagar").val());
			var saldo_d=saldo.toFixed(2);
		    var input = parseFloat($("#field-comision_inmo_paga").val());
		    var input_d=input.toFixed(2);
		    if(input_d > saldo){	    	
		    	$("#field-comision_inmo_paga").val("0");
		    	$("#field-comision_inmo_debe").val(saldo_d);
		    }else{
		    	var resta=saldo_d - input_d;
		    	var resta_d=resta.toFixed(2);
		    $("#field-comision_inmo_debe").val(resta_d);
			}
		}		
	}else{
			// PAGAR ALQUILER: que el input no supere la comision inmobiaria
		function validar_comision() {
			var saldo = parseFloat($("#ci_a_pagar_anterior").val());
			var saldo_d=saldo.toFixed(2);
		    var input = parseFloat($("#field-comision_inmo_paga").val());
		    var input_d=input.toFixed(2);
		    if(input_d > saldo){	    	
		    	$("#field-comision_inmo_paga").val("0");
		    	$("#field-comision_inmo_debe").val(saldo_d);
		    }else{
		    	var resta=saldo_d - input_d;
		    	var resta_d=resta.toFixed(2);
		    $("#field-comision_inmo_debe").val(resta_d);
			}
		}			
	}	
	//ACA HAGO CLICK EN SUMAR Y SUMO TODOS LOS CAMPOS NUMERICOS PARA OBTENER EL TOTAL A PAGAR
	var nro_pago=$("#nro_pago").text();
	if(nro_pago==1){		
		$("#sumar").click(function() {
			var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());
			var punitorio_tmp=parseFloat($('#field-punitorios').val());
			var comision_paga=parseFloat($('#field-comision_inmo_paga').val());
			var sellado_paga=parseFloat($('#field-sellado_paga').val());
			var expensas_tmp=parseFloat($('#field-expensas').val());
			var csp_tmp=parseFloat($('#field-csp').val());	
			var impuesto_inmob=parseFloat($('#field-impuesto_inmob').val());		
			var luz_tmp=parseFloat($('#field-luz').val());
			var agua_tmp=parseFloat($('#field-agua').val());
			var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
			var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());
			var varios1_tmp=parseFloat($('#field-varios1').val());	
			var varios2_tmp=parseFloat($('#field-varios2').val());		
			var certi_firma_tmp=parseFloat($('#field-certi_firma').val());
			var veraz_tmp=parseFloat($('#field-veraz').val());

			var total = monto_alquiler_tmp + punitorio_tmp + expensas_tmp + sellado_paga + comision_paga + certi_firma_tmp + veraz_tmp + csp_tmp + impuesto_inmob + luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp;
			var total_pagar = total.toFixed(2);
	  		$("#field-total_pagar").val(total_pagar);
		}); 
	}else{
		$("#sumar").click(function() {
			var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());
			var punitorio_tmp=parseFloat($('#field-punitorios').val());
			var comision_paga=parseFloat($('#field-comision_inmo_paga').val());
			
			var expensas_tmp=parseFloat($('#field-expensas').val());
			var csp_tmp=parseFloat($('#field-csp').val());	
			var impuesto_inmob=parseFloat($('#field-impuesto_inmob').val());		
			var luz_tmp=parseFloat($('#field-luz').val());
			var agua_tmp=parseFloat($('#field-agua').val());
			var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
			var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());	
			var varios1_tmp=parseFloat($('#field-varios1').val());			
			var varios2_tmp=parseFloat($('#field-varios2').val());
			

			var total = monto_alquiler_tmp + punitorio_tmp + expensas_tmp +  comision_paga +  csp_tmp + impuesto_inmob +  luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp;
			var total_pagar = total.toFixed(2);
	  		$("#field-total_pagar").val(total_pagar);
		});		
	}
	

	$("#imprime").click(function(){
		window.location.href = "http://"+host+"/SGI/Pago/descargar_pdf";
	});



	
}//FIN IF


if(url == "http://"+host+"/SGI/Pago/pagar/add/"){
	moment.locale('ES');
	//ANIMACION PARA EL TEXTO ULTIMO PAGO
	animacion = function(){	  
	    $("#pago").fadeTo(500, .1)
	              .fadeTo(500, 1);

	    $("#rescinde_dentro").fadeTo(500, .1)
	              .fadeTo(500, 1);	              
	}
	setInterval(animacion, 1000);

	$("#save-and-go-back-button").one("click", function (){
		//alert("aca");
		return false;
 	});

	///CAMBIO EL ATRIBUTO DEL BOTON CANCELAR Y VUELVO ATRAS////TENIA PROBLEMAS CON EL $crud->set_crud_url_path(site_url());
	$("#cancel-button").attr("id","cancelar");
	$("#cancelar").attr("onclick","history.back()");

	$("#save-and-go-back-button").click(function(){
		var renueva=$("#field-renueva").val();
		if(renueva==""){
			$("#texto_renueva").text("Complete Aquí");
			$("#field-renueva").focus();
			e.preventDefault();
			return false;
		}
 	});	

	var operacion=$("#idContrato_display_as_box").text(); //tomo del primer campo la etiqueta: Comodato: o Alquiler:


		$("#texto_centro").text("Cobro de"+operacion);
		$("#link1").text("Cancelar");		
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');		


		function calcular_periodo_rescision(){
			var periodo_fecha=$("#venc_periodo").text();
			var periodo_formateado=moment(periodo_fecha);
			var rescinde_dentro=$("#field-rescinde_dentro").val();

			var duracion=$("#duracion").text();			
			var nro_pago=$("#nro_pago").text();
			var dif=duracion-nro_pago;			

			var fecha = new Date();			 
			var dia=fecha.getDate();	

			if(rescinde_dentro != 0 && rescinde_dentro != ""){
				if((rescinde_dentro<=(dif+1)) && dia < 16) {
					
						var periodo_rescinde = periodo_formateado.add((rescinde_dentro-1), 'month');
						var periodo_f=moment(periodo_rescinde).format('MMM-YY');
						var rescinde_periodo_f=periodo_f.toUpperCase();								
				}else if((rescinde_dentro<=dif) && dia >= 16){
						var dif_2=dif-1;

						var periodo_rescinde = periodo_formateado.add((rescinde_dentro), 'month');
						var periodo_f=moment(periodo_rescinde).format('MMM-YY');
						var rescinde_periodo_f=periodo_f.toUpperCase();						
				}else{
					alert("Supera el fin de contrato!!!!");
					$("#field-rescinde_dentro").val("");
				}
				$("#field-rescinde_periodo").val(rescinde_periodo_f);
			}else{
				$("#field-rescinde_periodo").val("");
			}
		}


	/*var rescinde_fecha=$("#rescinde_fecha").text();
	 var rescinde_fecha_f=moment(rescinde_fecha).format('MMM-YY');
	 fecha_rescision=rescinde_fecha_f.toUpperCase();	 
	 $("#rescinde_fecha").text(fecha_rescision);*/		



	 var set_periodo = document.getElementById('field-periodo');
	 var periodo = document.getElementById('field-periodo').value;
	 
	 var per=moment(periodo).format('MMM-YY');
	 set_periodo.value=per.toUpperCase();

	 //var prox_venc_siguiente = moment(periodo).add(1, 'month').format("YYYY/MM/DD");
	 //$("#field-prox_venc_sig").val(prox_venc_siguiente);



	/* $("#field-csp_detalle").val(texto);
	 $("#field-expensas_detalle").val(texto);
	 $("#field-luz_detalle").val(texto);
	 $("#field-agua_detalle").val(texto);*/

	function getSelectionStart(o) {
		  if (o.createTextRange) {
		    var r = document.selection.createRange().duplicate()
		    r.moveEnd('character', o.value.length)
		    if (r.text == '') return o.value.length
		    return o.value.lastIndexOf(r.text)
		  } else { return o.selectionStart }
	}


	function pagatodo_ci(){
		$("#field-comision_inmo_paga").val($("#field-ci_a_pagar").val());
		$("#field-comision_inmo_debe").val("0.00");
	}


	 function insertar_periodo_expensas(x){
	 	var expensas = $("#field-expensas").val();

		var periodo = $("#field-fechaUltimoPago").val();
	 	var texto="Período "+periodo;	 	

	 	if	(expensas != 0){
	 		$("#field-expensas_detalle").val(texto);
	 	} 		 			 		
	 }

	 function insertar_periodo_csp(x){
	 	var csp = $("#field-csp").val();

		var periodo = $("#field-periodo").val();
	 	var texto="Período "+periodo; 	

	 	if	(csp != 0){
	 		$("#field-csp_detalle").val(texto);
	 	}	 			 		
	 }

	 function insertar_periodo_inmob(x){
	 	var impuesto_inmob = $("#field-impuesto_inmob").val();
		 var periodo = $("#field-fechaUltimoPago").val();
	 	var texto="Período "+periodo;	 	

	 	if	(impuesto_inmob != 0){
	 		$("#field-inmob_desc").val(texto);
	 	} 			 		
	 }	

	 function insertar_periodo_luz(x){
	 	var luz = $("#field-luz").val();
		 var periodo = $("#field-fechaUltimoPago").val();
	 	var texto="Período "+periodo;	 

	 	if	(luz != 0){
	 		$("#field-luz_detalle").val(texto);
	 	} 		 			 		
	 }

	 function insertar_periodo_agua(x){
	 	var agua = $("#field-agua").val();
		 var periodo = $("#field-fechaUltimoPago").val();
	 	var texto="Período "+periodo; 

	 	if	(agua != 0){
	 		$("#field-agua_detalle").val(texto);
	 	} 		 			 		
	 }

	 var inicio=$("#inicio").text();
	 var f_inicio=moment(inicio).format('MMM-YY');
	 inicioU=f_inicio.toUpperCase();	 
	 $("#inicio").text(inicioU);	

	var fin=$("#fin").text();	
	var fin=moment(fin).format('MMM-YY');
	 finU=fin.toUpperCase();	
	$("#fin").text(finU);	 


	 var inicio=$("#fecha_ajuste").text();
	 if(inicio!=""){
		 var f_inicio=moment(inicio).format('MMM-YY');
		 inicioU=f_inicio.toUpperCase();	 
		 $("#fecha_ajuste").text(inicioU);
	 }


	 var mora=document.getElementById('field-mora_dias');
	 var hoy = moment();	 

	 var hoy_comparar=Date.parse(hoy.format('MM/DD/YYYY'));

	 var periodo_comparar=Date.parse(moment(periodo).format('MM/DD/YYYY'));
	 
	 var monto_alquiler = parseInt($('#field-valor_alquiler').val());
	 var porc_punitorio = parseFloat($('#porcentaje').text());//tomo el porcentaje del  span
	  var valor_diario = document.getElementById('field-valor-diario');
	 // alert(porc_punitorio);
	  
	 if(periodo_comparar <= hoy_comparar){
		 var mora_dias=hoy.diff(periodo, 'days');
		  mora.value=mora_dias;

		//tomamos la cantidad de dias y asignamos al span y luego lo 
		//OCULTAMOS EL SPAN CON EL VALOR MORA DIAS, AL LADO DEL "MORA DIARIA PORCENTAJE"
		//Y AGREGAMOS EL ATRIBUTO MAX EL VALOR DE MORA DIAS ACTUAL	 	
	 	$("#mora_dias").text($("#field-mora_dias").val());
	 	var max=$("#mora_dias").text();
	 	$("#mora_dias").hide();
	 	$("#field-mora_dias").attr("max",max);

	 	//cargo en el span oculto la cant de dias de mora original
	 	$("#mora_dias_orig").val($("#field-mora_dias").val());
	 	$("#mora_dias_orig").hide();

		 var multi_punitorio=(porc_punitorio/100)*mora_dias;
		// valor_diario.value=(porc_punitorio/100)*monto_alquiler;
		 var valor_diario=(porc_punitorio/100)*monto_alquiler;
		 var valor_diario_f=valor_diario.toFixed(2);
		 $("#field-valor-diario").val(valor_diario_f);

		 var importe_punitorio=document.getElementById('field-punitorios');		  
		
		 var total_mora=parseFloat(monto_alquiler*multi_punitorio);
		 var total_mora_f=total_mora.toFixed(2);
		 $("#total-mora").text();
		  $("#total-mora").text(total_mora_f);
		 var mora = $("#total-mora").text();	 		 
	}

	function calcular_punitorios(){
		var paga_mora=$("#field-paga_mora").val();		
		var mora_dias=$("#field-mora_dias").val();
		var valor_diario=$("#field-valor-diario").val();
		var total_punitorio=mora_dias*valor_diario;
		var total_punitorio_f=total_punitorio.toFixed(2);
		$("#total-mora").text(total_punitorio_f);
		if(paga_mora=="SI") $("#field-punitorios").val(total_punitorio_f);
	}	 

	if(mora_dias > 0){
		$("#field-paga_mora").removeAttr("disabled");
		function pagamora(){//pago/pagar/add
			//alert("aca");
			var paga_mora = document.getElementById('field-paga_mora').value;	
			if(paga_mora =="NO"){
				$("#field-punitorios").val(0);				
			}else if (paga_mora =="SI"){		 		
					 var mora_dias=hoy.diff(periodo, 'days');
					 mora.value=mora_dias;
					 mora_dias=$("#field-mora_dias").val();

					 var multi_punitorio=(porc_punitorio/100)*mora_dias;
					 valor_diario.value=(porc_punitorio/100)*monto_alquiler;
					 var importe_punitorio=document.getElementById('field-punitorios');
					 var total_punitorio=parseFloat(monto_alquiler*multi_punitorio);
					 var total_punitorio_f=total_punitorio.toFixed(2);
					
					 importe_punitorio.value=total_punitorio_f;									 					
			}	
		}
	}
	var saldo=$("#saldo_comision").val();
	$("#field-comision_inmo_debe_p").val(saldo);
	if(saldo > 0){
		$("#comision").removeAttr("disabled");
		$("#field-comision_inmo_debe_p").removeAttr("disabled");		
		function pagasaldo(){					
			if($("#comision").val()=="SI"){
				$("#field-paga_c_inmo").val(saldo);
				 $("#field-comision_inmo_debe_p").val("0");
				$("#field-paga_c_inmo").focus();				
			}else if($("#comision").val()=="NO"){
				$("#field-comision_inmo_debe_p").val(saldo);	
				$("#field-paga_c_inmo").val("0");					
			}
		}
	}


	//poner en mayusculas al momento de escribir en el text area de PAGOS
	function mayuscula(e) {
	     e.value = e.value.toUpperCase();
	}

		 $("#field-comision_inmo_debe").val(parseFloat($("#field-ci_a_pagar").val()).toFixed(2));
		// PAGAR ALQUILER: que el input no supere la comision inmobiaria	
		function validar_comision() {
			var saldo = parseFloat($("#field-ci_a_pagar").val());
			var saldo_d=saldo.toFixed(2);
		    var input = parseFloat($("#field-comision_inmo_paga").val());
		    var input_d=input.toFixed(2);
		    if(input_d > saldo){	    	
		    	$("#field-comision_inmo_paga").val("0.00");
		    	$("#field-comision_inmo_debe").val(saldo_d);
		    }else{
		    	var resta=saldo_d - input_d;
		    	var resta_d=resta.toFixed(2);
		    $("#field-comision_inmo_debe").val(resta_d);
			}
		}

	//ACA HAGO CLICK EN SUMAR Y SUMO TODOS LOS CAMPOS NUMERICOS PARA OBTENER EL TOTAL A PAGAR
	var monto_alquiler_tmp = $('#field-valor_alquiler').val();
	var nro_pago=$("#nro_pago").text();
	var quien_paga=$("#quien_paga").text();


	if ((monto_alquiler_tmp=="" || monto_alquiler_tmp==0) && (nro_pago==13 || nro_pago==25)){
		alert ("Debe Cargar Nuevo Importe de Alquiler");
		$('#field-valor_alquiler').val("");
		$('#field-valor_alquiler').focus();
	}


	if(quien_paga=="AMBOS"){
		if(nro_pago==1){
			$("#sumar").click(function() {
				var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());
				var punitorio_tmp=parseFloat($('#field-punitorios').val());
				var comision_paga=parseFloat($('#field-comision_inmo_paga').val());
				var sellado_inquilino=parseFloat($('#field-sellado_paga').val());
				var firma=parseFloat($('#field-certi_firma').val());
				var veraz=parseFloat($('#field-veraz').val());

				var expensas_tmp=parseFloat($('#field-expensas').val());
				var csp_tmp=parseFloat($('#field-csp').val());	
				var impuesto_i=parseFloat($('#field-impuesto_inmob').val());	
				var luz_tmp=parseFloat($('#field-luz').val());
				var agua_tmp=parseFloat($('#field-agua').val());
				var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
				var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());
				var varios1_tmp=parseFloat($('#field-varios1').val());
				var varios2_tmp=parseFloat($('#field-varios2').val());

				var total = monto_alquiler_tmp + punitorio_tmp + expensas_tmp + comision_paga + sellado_inquilino + firma + veraz + csp_tmp + impuesto_i + luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp;
				var total_pagar = total.toFixed(2);
		  		$("#field-total_pagar").val(total_pagar);
			}); 
		}else{

			var ci_debe=$("#ci_debe").text();
			if(ci_debe==0.00){
				$("#sumar").click(function() {
					var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());
					var punitorio_tmp=parseFloat($('#field-punitorios').val());
					
					var expensas_tmp=parseFloat($('#field-expensas').val());
					var csp_tmp=parseFloat($('#field-csp').val());	
					var impuesto_i=parseFloat($('#field-impuesto_inmob').val());		
					var luz_tmp=parseFloat($('#field-luz').val());
					var agua_tmp=parseFloat($('#field-agua').val());
					var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
					var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());
					var varios1_tmp=parseFloat($('#field-varios1').val());
					var varios2_tmp=parseFloat($('#field-varios2').val());

					var total = monto_alquiler_tmp + punitorio_tmp + expensas_tmp +  csp_tmp + impuesto_i + luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp;
					var total_pagar = total.toFixed(2);
			  		$("#field-total_pagar").val(total_pagar);
				});				

			}else{
				$("#sumar").click(function() {
					var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());
					var punitorio_tmp=parseFloat($('#field-punitorios').val());
					var comision_paga=parseFloat($('#field-comision_inmo_paga').val());
					var expensas_tmp=parseFloat($('#field-expensas').val());
					var csp_tmp=parseFloat($('#field-csp').val());	
					var impuesto_i=parseFloat($('#field-impuesto_inmob').val());		
					var luz_tmp=parseFloat($('#field-luz').val());
					var agua_tmp=parseFloat($('#field-agua').val());
					var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
					var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());
					var varios1_tmp=parseFloat($('#field-varios1').val());
					var varios2_tmp=parseFloat($('#field-varios2').val());

					var total = monto_alquiler_tmp + punitorio_tmp + comision_paga + expensas_tmp + csp_tmp + impuesto_i + luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp;
					var total_pagar = total.toFixed(2);
			  		$("#field-total_pagar").val(total_pagar);
				});			
			}
			
		}
	}else if(quien_paga=="PROPIETARIO"){
		if(nro_pago==1){
			$("#sumar").click(function() {
				var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());
				var punitorio_tmp=parseFloat($('#field-punitorios').val());				
				var sellado_inquilino=parseFloat($('#field-sellado_paga').val());
				var firma=parseFloat($('#field-certi_firma').val());
				var veraz=parseFloat($('#field-veraz').val());

				var expensas_tmp=parseFloat($('#field-expensas').val());
				var csp_tmp=parseFloat($('#field-csp').val());	
				var impuesto_i=parseFloat($('#field-impuesto_inmob').val());	
				var luz_tmp=parseFloat($('#field-luz').val());
				var agua_tmp=parseFloat($('#field-agua').val());
				var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
				var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());
				var varios1_tmp=parseFloat($('#field-varios1').val());
				var varios2_tmp=parseFloat($('#field-varios2').val());

				var total = monto_alquiler_tmp + punitorio_tmp + expensas_tmp +  sellado_inquilino + firma + veraz + csp_tmp + impuesto_i + luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp;
				var total_pagar = total.toFixed(2);
		  		$("#field-total_pagar").val(total_pagar);
			}); 
		}else{
			$("#sumar").click(function() {
				var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());
				var punitorio_tmp=parseFloat($('#field-punitorios').val());
							
				var expensas_tmp=parseFloat($('#field-expensas').val());
				var csp_tmp=parseFloat($('#field-csp').val());	
				var impuesto_i=parseFloat($('#field-impuesto_inmob').val());		
				var luz_tmp=parseFloat($('#field-luz').val());
				var agua_tmp=parseFloat($('#field-agua').val());
				var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
				var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());
				var varios1_tmp=parseFloat($('#field-varios1').val());
				var varios2_tmp=parseFloat($('#field-varios2').val());

				var total = monto_alquiler_tmp + punitorio_tmp + expensas_tmp +  csp_tmp + impuesto_i + luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp;
				var total_pagar = total.toFixed(2);
				$("#field-total_pagar").val(total_pagar);
			});				
		}
	}		


	$("#nro_pago").css("font-size",20);
	$("#imprime").click(function(){
		window.location.href = "http://"+host+"/SGI/Pago/descargar_pdf";
	});



	
}//FIN IF

/*if(url == "http://localhost/SGI/Pago/pagar/"){
	window.location = "http://localhost/SGI/Alquiler/alquiler";
}*/

if(url == "http://"+host+"/SGI/Pago/pagos_edit/edit/"){

	/*$("#cancel-button").click(function(){
		window.location.href = "http://localhost/SGI/Alquiler/alquiler";
		//alert("hola");
	});*/
	//$("#cancel-button").hide();

	//background
	$("#field-impuesto_inmob,#field-comision_inmo_paga,#field-certi_firma,#field-veraz,#field-sellado_paga,#field-locador,#field-locatario1,#field-locatario2,#field-valor_alquiler,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#field-paga_c_inmo,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2").css('background-color', '#FDFF93');

	//bolder
	//$("#field-valor_alquiler,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#saldocomision,#sumar,#total-mora,#saldo_comision,#field-expensas,#field-csp,#field-luz,#field-agua,#field-saldos_otros,#venc,#field-paga_c_inmo,#field-resta_saldocomision,#limpiar,#field-fechaUltimoPago,#field-valor-diario,#field-mora_dias").css("font-weight","bolder");

	//font-size
	$("#field-impuesto_inmob,#ci_a_pagar_anterior,#field-comision_inmo_debe,#field-comision_inmo_paga,#ci_a_pagar,#field-certi_firma,#field-veraz,#field-sellado_contrato,#field-sellado_paga,#field-locatario1,#field-locatario2,#field-locador,#field-valor_alquiler,#field-total_pagar,#field-punitorios,#saldocomision,#saldo_comision,#field-periodo,#field-idContrato,#venc,#total-mora,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2,#field-paga_c_inmo,#field-comision_inmo_debe_p").css("font-size",18);

	//color:red
	$("#field-impuesto_inmob,#field-comision_inmo_paga,#field-certi_firma,#field-veraz,#field-sellado_contrato,#field-sellado_paga,#field-valor_alquiler,#field-punitorios,#saldocomision,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2,#saldo_comision,#texto_saldo,#texto-total-punitorio,#total-mora,#field-paga_c_inmo,#field-comision_inmo_debe_p,#venc").css("color",'red');	

	$("#field-ci_a_pagar,#field-comision_inmo_debe").css("color",'blue');	

	$("#field-total_pagar").css("color","green");
	$("#field-total_pagar,#b").css("font-size",20);
	$("#field-total_pagar,#sumar,#limpiar").css("font-weight","bolder");
	//ALIGN CENTER
	$("#field-impuesto_inmob,#ci_a_pagar_anterior,#field-comision_inmo_debe,#field-comision_inmo_paga,#field-certi_firma,#field-veraz,#field-sellado_paga,#ci_a_pagar,#field-valor_alquiler,#field-valor-diario,#field-mora_dias,#field-fechaUltimoPago,#field-paga_c_inmo,#saldo_comision,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#field-comision_inmo_debe_p,#field-expensas,#field-csp,#field-luz,#field-agua,field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2").css("text-align","center");

	//impedir ingresar un valor mayor al saldo de comision de inmobiliaria y solo numeros en pagos
	$('.numerico').on('input', function () { 
	    this.value = this.value.replace(/[^0-9\\.]/g,'');
	});

	//limpiar los input de pago
	$(document).ready(function() {
  		$("#limpiar").click(function() {
   		 	//$('.numerico').val('0');   
   		 	location.reload();		 	
  		});
	});


	// PAGAR ALQUILER: sacar el cero cuando hago click en cada input
	function vaciar(id){
		//$("#field-rescinde_periodo").val("");
		$("#"+id+"").val("");

	}

	// pone el cero cuando dejamos vacio los input
	function input_ceros(id){
		var  valor = $("#"+id+"").val();
		if(valor == ""){
			$("#"+id+"").val("0.00");
		}
	}


	
}//FIN IF





if(url == "http://"+host+"/SGI/Pago/pagar/add/" || url == "http://"+host+"/SGI/Verpago/edit/"){

	/*$("#cancel-button").click(function(){
		window.location.href = "http://localhost/SGI/Alquiler/alquiler";
		//alert("hola");
	});*/
	//$("#cancel-button").hide();

	//background
	$("#field-fechaUltimoPago,#field-rescinde_periodo,#field-rescinde_dentro,#field-impuesto_inmob,#field-certi_firma,#field-veraz,#field-sellado_paga,#field-comision_inmo_paga,#field-locador,#field-locatario1,#field-locatario2,#field-valor_alquiler,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#field-paga_c_inmo,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2").css('background-color', '#FDFF93');

	//bolder
	//$("#field-valor_alquiler,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#saldocomision,#sumar,#total-mora,#saldo_comision,#field-expensas,#field-csp,#field-luz,#field-agua,#field-saldos_otros,#venc,#field-paga_c_inmo,#field-resta_saldocomision,#limpiar,#field-fechaUltimoPago,#field-valor-diario,#field-mora_dias").css("font-weight","bolder");

	//font-size
	$("#nro_pago").css("font-size",20);
	$("#field-fechaUltimoPago,#field-rescinde_periodo,#field-impuesto_inmob,#field-certi_firma,#field-veraz,#field-sellado_paga,#field-comision_inmo_debe,#field-comision_inmo_paga,#field-ci_a_pagar,#pago,#rescinde_dentro,#field-rescinde_dentro,#valor_ajuste,#fecha_ajuste,#inicio,#fin,#field-locatario1,#field-locatario2,#field-locador,#field-valor_alquiler,#field-total_pagar,#field-punitorios,#saldocomision,#saldo_comision,#field-periodo,#field-idContrato,#venc,#total-mora,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2,#field-paga_c_inmo,#field-comision_inmo_debe_p").css("font-size",18);

	//color:red
	$("#field-periodo,#field-rescinde_periodo,#field-rescinde_dentro,#field-impuesto_inmob,#nro_pa#field-impuesto_inmob,#field-certi_firma,#field-veraz,#field-sellado_paga,#field-comision_inmo_paga,#field-ci_a_pagar,#pago,#valor_ajuste,#fecha_ajuste,#inicio,#fin,#field-valor_alquiler,#field-punitorios,#saldocomision,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2,#saldo_comision,#texto_saldo,#texto-total-punitorio,#total-mora,#field-paga_c_inmo,#field-comision_inmo_debe_p,#venc").css("color",'red');	

	$("#field-ci_a_pagar,#field-comision_inmo_debe,#field-fechaUltimoPago").css("color",'blue');

	$("#field-total_pagar").css("color","green");
	$("#field-total_pagar,#b").css("font-size",20);
	$("#field-periodo,#restan,#pago,#field-total_pagar,#sumar,#limpiar").css("font-weight","bolder");
	//align center
	$("#field-rescinde_periodo,#field-rescinde_dentro,#field-impuesto_inmob,#field-certi_firma,#field-veraz,#field-sellado_paga,#field-comision_inmo_debe,#field-comision_inmo_paga,#field-ci_a_pagar,#field-valor_alquiler,#field-valor-diario,#field-mora_dias,#field-fechaUltimoPago,#field-paga_c_inmo,#saldo_comision,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#field-comision_inmo_debe_p,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2").css("text-align","center");

	//impedir ingresar un valor mayor al saldo de comision de inmobiliaria y solo numeros en pagos
	/*$('.numerico').on('input', function () { 
	    this.value = this.value.replace(/[^0-9\\.]/g,'');
	});*/

	//limpiar los input de pago
	$(document).ready(function() {
  		$("#limpiar").click(function() {
   		 	//$('.numerico').val('0');   
   		 	location.reload();		 	
  		});
	});


	// PAGAR ALQUILER: sacar el cero cuando hago click en cada input
	function vaciar(id){
		$("#"+id+"").val("");
	}

	// pone el cero cuando dejamos vacio los input
	function input_ceros(id){
		var  valor = $("#"+id+"").val();
		if(valor == ""){
			$("#"+id+"").val("0.00");
		}
	}

	function input_negativo(id){
		var imp_inmob=$("#field-impuesto_inmob").val();
		if(imp_inmob != ""){				
			$("#field-impuesto_inmob").val(-imp_inmob);	
		}else{
			$("#field-impuesto_inmob").val("0.00");
			
		}
		
	}	

}//FIN IF	

if(url == "http://"+host+"/SGI/Alquier/alquiler/add/"){
	$('.numerico').on('input', function () { 
	    this.value = this.value.replace(/[^0-9\\.]/g,'');
	});
}

if(url == "http://"+host+"/SGI/Pago/rescindir_contrato/add/"){

	/*$("#cancel-button").click(function(){
		window.location.href = "http://localhost/SGI/Alquiler/alquiler";
		//alert("hola");
	});*/
	//$("#cancel-button").hide();

	var rescinde = $("#NORESCINDE").text();

	if(rescinde == "NO PUEDE RESCINDIR"){
		$(".buttons-box").hide();
	}


	///
	function cancelar_rescision(idC){	
			var opcion = confirm("¿Confirma Cancelar la Rescisión?");
			if (opcion == true) {
				window.location="http://"+host+"/SGI/Pago/anular_rescision/"+idC;	        	
			} else {
		    	return false;
			}		
	}

//ANIMACION TEXTO RESCINDE, NO RESCINDE
	animacion = function(){	  
	    $("#RESCINDE").fadeTo(500, .1)
	              .fadeTo(500, 1);
	}
	setInterval(animacion, 1000);	

	animacion = function(){	  
	    $("#NORESCINDE").fadeTo(500, .1)
	              .fadeTo(500, 1);
	}
	setInterval(animacion, 1000);	


	//background
	$("#field-punitorios,#field-comision_inmo_paga,#field-fechaUltimoPago,#field-valor_alquiler,#field-total_pagar, #field-periodo,#field-idContrato,#field-paga_c_inmo,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2").css('background-color', '#FDFF93');

	//bolder
	//$("#field-valor_alquiler,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#saldocomision,#sumar,#total-mora,#saldo_comision,#field-expensas,#field-csp,#field-luz,#field-agua,#field-saldos_otros,#venc,#field-paga_c_inmo,#field-resta_saldocomision,#limpiar,#field-fechaUltimoPago,#field-valor-diario,#field-mora_dias").css("font-weight","bolder");

	//font-size
	$("#field-punitorios,#field-comision_inmo_paga,#field-fechaUltimoPago,#field-locatario1,#field-locatario2,#fin,#inicio,#field-locador,#field-valor_alquiler,#field-total_pagar,#saldocomision,#saldo_comision,#field-periodo,#field-idContrato,#venc,#total-mora,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2,#field-paga_c_inmo").css("font-size",18);

	//color:red
	$("#field-comision_inmo_paga,#field-periodo,#field-valor_alquiler,#fin,#inicio,#field-punitorios,#saldocomision,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2,#saldo_comision,#texto_saldo,#texto-total-punitorio,#total-mora,#field-paga_c_inmo,#field-comision_inmo_debe_p,#venc").css("color",'red');	

	$("#field-total_pagar").css("color","green");
	$("#field-fechaUltimoPago").css("color","blue");
	$("#field-total_pagar,#b").css("font-size",20);
	$("#field-periodo,#field-total_pagar,#sumar,#limpiar").css("font-weight","bolder");
	//ALIGN CENTER
	$("#field-comision_inmo_paga,#field-valor_alquiler,#field-valor-diario,#field-mora_dias,#field-fechaUltimoPago,#saldo_comision,#field-total_pagar, #field-periodo,#field-idContrato,#field-punitorios,#field-comision_inmo_debe_p,#field-expensas,#field-csp,#field-luz,#field-agua,#field-exp_extra,#field-saldos_otros,#field-varios1,#field-varios2").css("text-align","center");

	//impedir ingresar un valor mayor al saldo de comision de inmobiliaria y solo numeros en pagos
	$('.numerico').on('input', function () { 
	    this.value = this.value.replace(/[^0-9\\.]/g,'');
	});

	//limpiar los input de pago
	$(document).ready(function() {
  		$("#limpiar").click(function() {
   		 	//$('.numerico').val('0');   
   		 	location.reload();		 	
  		});
	});


	// PAGAR ALQUILER: sacar el cero cuando hago click en cada input
	function vaciar(id){
		$("#"+id+"").val("");

	}

	// pone el cero cuando dejamos vacio los input
	function input_ceros(id){
		var  valor = $("#"+id+"").val();
		if(valor == ""){
			$("#"+id+"").val("0");
		}
	}
}//FIN IF	

if(url == "http://"+host+"/SGI/Pago/rescindir_contrato/add/"){
	moment.locale('ES');
	///CAMBIO EL ATRIBUTO DEL BOTON CANCELAR Y VUELVO ATRAS////TENIA PROBLEMAS CON EL $crud->set_crud_url_path(site_url());
	$("#cancel-button").attr("id","cancelar");
	$("#cancelar").attr("onclick","history.back()");

	$("#texto_centro").text("Rescisión de Alquiler");
	$("#link1").text("Cancelar");
	$('#link1').attr("href","javascript:history.go(-1)");
	$("#link1").css("color", "yellow");

		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');	

		

	 var set_periodo = document.getElementById('field-periodo');
	 var periodo = document.getElementById('field-periodo').value;
	 
	var per=moment(periodo).format('MMM-YY');
	set_periodo.value=per.toUpperCase();


	 var inicio=$("#inicio").text();
	 var f_inicio=moment(inicio).format('MMM-YY');
	 inicioU=f_inicio.toUpperCase();	 
	 $("#inicio").text(inicioU);	

	var fin=$("#fin").text();	
	var fin=moment(fin).format('MMM-YY');
	 finU=fin.toUpperCase();	
	$("#fin").text(finU);

	 //var prox_venc_siguiente = moment(periodo).add(1, 'month').format("YYYY/MM/DD");
	 //$("#field-prox_venc_sig").val(prox_venc_siguiente);

	 var hoy = moment();	 

	 var hoy_comparar=Date.parse(hoy.format('MM/DD/YYYY'));

	 var periodo_comparar=Date.parse(moment(periodo).format('MM/DD/YYYY'));
	
	 var monto_alquiler = parseInt($('#field-valor_alquiler').val());
	 var porc_punitorio = parseInt($('#porcentaje').text());//tomo el porcentaje del  span
	  var valor_diario = document.getElementById('field-valor-diario');


	 /*if(periodo_comparar <= hoy_comparar){
		 var mora_dias=hoy.diff(periodo, 'days');
		$("#field-mora_dias").val(mora_dias);
		 var multi_punitorio=(porc_punitorio/100)*mora_dias;
		 valor_diario.value=(porc_punitorio/100)*monto_alquiler;
		 var importe_punitorio=document.getElementById('field-punitorios');		  
		 mora.value=mora_dias;
		 $("#total-mora").text(parseInt(monto_alquiler*multi_punitorio));
		 var mora = $("#total-mora").text();

	}*/	 

	/*if(mora_dias > 0){
		$("#field-paga_mora").removeAttr("disabled");
		function pagamora(){
			var paga_mora = document.getElementById('field-paga_mora').value;	
			if(paga_mora =="NO"){
				$("#field-punitorios").val(0);				
			}else if (paga_mora =="SI"){		 		
					 var mora_dias=hoy.diff(periodo, 'days');
					 var multi_punitorio=(porc_punitorio/100)*mora_dias;
					 valor_diario.value=(porc_punitorio/100)*monto_alquiler;
					 var importe_punitorio=document.getElementById('field-punitorios');
					 importe_punitorio.value=parseInt(monto_alquiler*multi_punitorio);					 
					 mora.value=mora_dias;					 					
			}	
		}
	}*/

	var saldo=$("#saldo_comision").val();
	$("#field-comision_inmo_debe_p").val(saldo);
	if(saldo > 0){
		$("#comision").removeAttr("disabled");
		$("#field-comision_inmo_debe_p").removeAttr("disabled");		
		function pagasaldo(){					
			if($("#comision").val()=="SI"){
				$("#field-paga_c_inmo").val(saldo);
				 $("#field-comision_inmo_debe_p").val("0");
				$("#field-paga_c_inmo").focus();				
			}else if($("#comision").val()=="NO"){
				$("#field-comision_inmo_debe_p").val(saldo);	
				$("#field-paga_c_inmo").val("0");					
			}
		}
	}
	//poner en mayusculas al momento de escribir en el text area de PAGOS
	function mayuscula(e) {
	     e.value = e.value.toUpperCase();
	}

		// PAGAR ALQUILER: que el input no supere la comision inmobiaria
	function validar_comision() {
		var saldo = parseInt($("#saldo_comision").val());
	    var input = parseInt($("#field-paga_c_inmo").val());
	    if(input > saldo){	    	
	    	$("#field-paga_c_inmo").val("0");
	    	$("#field-comision_inmo_debe").val(saldo);
	    }else{
	    $("#field-comision_inmo_debe").val(saldo - input);
		}
	}		

	//////ACA HAGO CLICK EN SUMAR Y SUMO TODOS LOS CAMPOS NUMERICOS PARA OBTENER EL TOTAL A PAGAR
	$("#sumar_rescindir").click(function() {		
		var monto_alquiler_tmp = parseFloat($('#field-valor_alquiler').val());		
		//var punitorio_tmp=parseFloat($('#field-punitorios').val());
		var ci_comision_paga=parseFloat($('#field-comision_inmo_paga').val());

		if(typeof ci_comision_paga !== 'undefined'){
			ci_comision_paga=0;
		}

		var expensas_tmp=parseFloat($('#field-expensas').val());
		var csp_tmp=parseFloat($('#field-csp').val());		
		var luz_tmp=parseFloat($('#field-luz').val());
		var agua_tmp=parseFloat($('#field-agua').val());
		var exp_extra_tmp=parseFloat($('#field-exp_extra').val());
		var saldos_varios_tmp=parseFloat($('#field-saldos_otros').val());
		//var varios1_tmp=parseFloat($('#field-varios1').val());
		//var varios2_tmp=parseFloat($('#field-varios2').val());

		var total = monto_alquiler_tmp  + expensas_tmp + ci_comision_paga + csp_tmp + luz_tmp + agua_tmp + exp_extra_tmp + saldos_varios_tmp;
		var total_pagar = total.toFixed(2);
  		$("#field-total_pagar").val(total_pagar);
	}); 

	$("#imprime").click(function(){
		window.location.href = "http://"+host+"/SGI/Pago/descargar_pdf";
	});	
}//FIN IF


if(url == "http://"+host+"/SGI/Liquidacion/liquidar/add/"){

	$("#save-and-go-back-button").one("click", function (){
		//$("#save-and-go-back-button").prop('disabled', true);
		return false;
 	});	

	$("#cancel-button").attr("id","cancelar");
	$("#cancelar").attr("onclick","history.back()");	

		function validar_comision() {//liquidar/add

			///AGREGO SIGNO NEGATIVO
			var comision_paga=$("#field-comision_inmo_paga").val();


			var saldo = parseFloat($("#field-ci_a_pagar").val());
			var saldo_d=saldo.toFixed(2);
		    var input = parseFloat($("#field-comision_inmo_paga").val());
		    var input_d=(input.toFixed(2))*-1;
		    if(input_d > saldo){	    	
		    	$("#field-comision_inmo_paga").val("");
		    	$("#field-comision_inmo_debe").val(saldo_d);
		    }else{
		    	var resta=saldo_d - input_d;
		    	var resta_d=resta.toFixed(2);
		    $("#field-comision_inmo_debe").val(resta_d);
			}
		}

		function input_negativo(id){
			var comision_paga=$("#field-comision_inmo_paga").val();
			if(comision_paga != ""){				
				$("#field-comision_inmo_paga").val(-comision_paga);	
			}else{
				$("#field-comision_inmo_paga").val("0.00");
				
			}
			//alert("aca");	
		}
		function pagatodo_ci(){
			var comision_paga=$("#field-ci_a_pagar").val();
			$("#field-comision_inmo_paga").val(-comision_paga);

			$("#field-comision_inmo_debe").val("0.00");
		}


	function calcular_alquiler(){
		var comision=$("#comision").text();
		var valor_alquiler=parseFloat($("#field-alquiler").val());
		var comision_porc=parseFloat(comision/100);
		var punitorio=parseFloat($("#field-punitorios").val());
		var total_pagar=valor_alquiler+punitorio;		
		$("#b").text(total_pagar);
		$("#field-comiAdmin").val("");
	}

	function calcular_comision(){// en liquidar/add
		//alert("aca2");
		var comision=$("#comision").text();
		var comision_porc=parseFloat(comision/100);
		var subtotal = parseFloat($("#b").text());//CAPTURO EL SUBTOTAL
		var comision_calculo=parseFloat(comision_porc*subtotal);
		var subtotal_d=comision_calculo.toFixed(2);
		var comision_admin=parseFloat(subtotal_d);
		var valor_redondeado=Math.round(comision_admin)*(-1);
		$("#field-comiAdmin").val(valor_redondeado);

	}


	var nro_pago=$("#nro_pago").text();
	if(nro_pago==1){
		$("#calcular").click(function() {
			var monto_alquiler_tmp = parseFloat($('#field-alquiler').val());
			var punitorio_tmp=parseFloat($('#field-punitorios').val());	

			var sellado_tmp=parseFloat($('#field-sellado_paga').val());	
			var firma_tmp=parseFloat($('#field-certi_firma').val());	

			var comi_admin_tmp=parseFloat($('#field-comiAdmin').val());

			var punitorios_tmp=parseFloat($('#field-punitorios').val());

			var comi_inmo_tmp=parseFloat($('#field-comision_inmo_paga').val());

			var desc_arreglos=parseFloat($('#field-descArreglos').val());
			var expensas=parseFloat($('#field-expensas').val());
			var csp=parseFloat($('#field-impuesto_csp').val());	
			var inmob=parseFloat($('#field-impuesto_inmob').val());	
			var exp_extras_tmp=parseFloat($('#field-expExtras').val());
			var saldos_varios_tmp=parseFloat($('#field-saldos_varios').val());
			var varios1_tmp=parseFloat($('#field-varios1').val());
			var varios2_tmp=parseFloat($('#field-varios2').val());		

			var total = monto_alquiler_tmp + sellado_tmp + firma_tmp +  comi_admin_tmp + punitorios_tmp + comi_inmo_tmp +  expensas + csp + desc_arreglos + inmob +  exp_extras_tmp + saldos_varios_tmp + varios1_tmp + varios2_tmp ;
			var total_pagar = total.toFixed(2);
	  		$("#field-totalPagar").val(total_pagar);
		});	
	}else{
		//ACA HAGO CLICK EN CALCULAR Y SUMO TODOS LOS CAMPOS NUMERICOS PARA OBTENER EL TOTAL A PAGAR AL PROPIETARIO
		$("#calcular").click(function() {
			var monto_alquiler_tmp = parseFloat($('#field-alquiler').val());
			var punitorios_tmp=parseFloat($('#field-punitorios').val());	

			var comi_inmo_tmp=parseFloat($('#field-comision_inmo_paga').val());

			if(!comi_inmo_tmp){
				comi_inmo_tmp=0;
			}

			var comi_admin_tmp=parseFloat($('#field-comiAdmin').val());
			var expensas=parseFloat($('#field-expensas').val());
			var agua=parseFloat($('#field-agua').val());
			var csp_desc_arreglos=parseFloat($('#field-descArreglos').val());
			var csp=parseFloat($('#field-impuesto_csp').val());	
			var inmob=parseFloat($('#field-impuesto_inmob').val());	
			var exp_extras_tmp=parseFloat($('#field-expExtras').val());
			var saldos_varios_tmp=parseFloat($('#field-saldos_varios').val());
			var varios1_tmp=parseFloat($('#field-varios1').val());
			var varios2_tmp=parseFloat($('#field-varios2').val());		

			var total = monto_alquiler_tmp + punitorios_tmp + comi_admin_tmp + comi_inmo_tmp +  expensas + agua + csp + inmob + csp_desc_arreglos + exp_extras_tmp + saldos_varios_tmp +varios1_tmp + varios2_tmp;
			var total_pagar = total.toFixed(2);
	  		$("#field-totalPagar").val(total_pagar);
		});
	}//fin else 	

	$(document).ready(function() {
  		$("#limpiar").click(function() {
   		 	$('.numerico').val('0.00');   
   		 	//location.reload();		 	
  		});
	});

	function input_ceros(id){
			var  valor = $("#"+id+"").val();
			if(valor == ""){
				$("#"+id+"").val("0");
			}
	}
	// PAGAR ALQUILER: sacar el cero cuando hago click en cada input
	function vaciar(id){
		$("#"+id+"").val("");

	}
	$('.numerico').on('input', function () { 
	    this.value = this.value.replace(/[^0-9\\.]/g,'');
	});

	//font-size
	$("#field-agua,#field-expensas,#field-saldos_varios,#field-detalle_saldos,#field-ci_a_pagar,#field-comision_inmo_debe,#field-comision_inmo_paga,#field-sellado_paga,#field-certi_firma,#field-impuesto_csp,#field-impuesto_inmob,#field-idpago,#field-locatario1,#field-locatario2,#field-locador,#field-alquiler,#field-punitorios,#field-periodo,#field-idContrato,#field-expExtras,#field-descArreglos,#field-comiAdmin,#field-varios1,#field-varios2").css("font-size",18);	
	
	//BACKGROUND	
	$("#field-agua,#field-expensas,#field-saldos_varios,#field-detalle_saldos,#field-comision_inmo_paga,#field-sellado_paga,#field-certi_firma,#field-impuesto_csp,#field-impuesto_inmob,#field-idpago,#field-locador,#field-locatario1,#field-locatario2,#field-alquiler,#field-totalPagar, #field-periodo,#field-comiAdmin,#field-punitorios,#field-descArreglos,#field-idContrato,#field-expExtras,#field-varios1,#field-varios2").css('background-color', '#FDFF93');

	//BOLDER
	$("#field-total_pagar,#calcular,#limpiar").css("font-weight","bolder");	

	//color:red
	$("#field-agua,#field-expensas,#field-saldos_varios,#field-alquiler,#b,#field-punitorios,#field-comision_inmo_paga,#field-detalle_saldos,#field-sellado_paga,#field-certi_firma,#field-impuesto_csp,#field-impuesto_inmob,#field-expExtras,#field-descArreglos,#field-comiAdmin,#field-varios1,#field-varios2").css("color",'red');	

	$("#field-ci_a_pagar,#field-comision_inmo_debe").css("color",'blue');

	$("#field-totalPagar").css("color","green");	
	$("#field-totalPagar,#b").css("font-size",22);
	//ALIGN CENTER
	$("#field-agua,#field-expensas,#field-saldos_varios,#field-sellado_paga,#field-ci_a_pagar,#field-comision_inmo_debe,#field-comision_inmo_paga,#field-certi_firma,#field-impuesto_csp,#field-impuesto_inmob,#field-totalPagar,#field-periodo,#field-alquiler,#field-comiAdmin,#field-punitorios,#field-descArreglos,#field-expExtras,#field-varios1,#field-varios2").css("text-align","center");
}//FIN IF de if(url == "http://localhost/SGI/Liquidacion/liquidar/add/"){


if(url == "http://"+host+"/SGI/Liquidacion/liquidar_edit/edit/"){
		$("#texto_centro").text("Liquidación a Propietarios - Modificar");
		$("#link1").text("Cancelar");
		$('#link1').attr("href","javascript:history.go(-1)");
		$("#link1").css("color", "yellow");
		$("#crudForm").css('position','relative');
		$("#crudForm").css('margin-top','25px');			
		//ACA HAGO CLICK EN CALCULAR Y SUMO TODOS LOS CAMPOS NUMERICOS PARA OBTENER EL TOTAL A PAGAR AL PROPIETARIO
		var debe_CI=parseFloat($('#field-comision_inmo_debe').val());
		var paga_CI=parseFloat($('#field-comision_inmo_paga').val());
		var nro_pago=$("#nro_pago").text();
		if(nro_pago==1 || !isNaN(paga_CI)){//paga_CI!=0

			var CI = parseFloat($('#field-comision_inmo_paga').val())*-1 + parseFloat($('#field-comision_inmo_debe').val());

			var CI_d=CI.toFixed(2);
			$("#ci_a_pagar").text(CI_d);

			$("#calcular").click(function() {
				//alert(paga_CI);
				var monto_alquiler_tmp = parseFloat($('#field-alquiler').val());
				var punitorio_tmp=parseFloat($('#field-punitorios').val());		
				var comi_admin_tmp=parseFloat($('#field-comiAdmin').val());

				var comi_inmo_tmp=parseFloat($('#field-comision_inmo_paga').val());	

				if(nro_pago==1){
					var sellado=parseFloat($('#field-sellado_paga').val());
					var firma=parseFloat($('#field-certi_firma').val());					
				}else{
					var sellado=0;
					var firma=0;
				}

				var expensas=parseFloat($('#field-expensas').val());	
				var agua=parseFloat($('#field-agua').val());
				var csp=parseFloat($('#field-impuesto_csp').val());
				var inmob=parseFloat($('#field-impuesto_inmob').val());			
				var csp_desc_arreglos=parseFloat($('#field-descArreglos').val());		
				var exp_extras_tmp=parseFloat($('#field-expExtras').val());
				var otros_saldos=parseFloat($('#field-saldos_varios').val());
				var varios1=parseFloat($('#field-varios1').val());
				var varios2=parseFloat($('#field-varios2').val());		

				var total = monto_alquiler_tmp + punitorio_tmp + comi_admin_tmp + comi_inmo_tmp + sellado + agua +  firma + csp_desc_arreglos + expensas + csp + inmob + exp_extras_tmp + otros_saldos + varios1 + varios2;
				var total_pagar = total.toFixed(2);
		  		$("#field-totalPagar").val(total_pagar);
			});
		}else{
			$("#calcular").click(function() {
				//alert("aca");
				var monto_alquiler_tmp = parseFloat($('#field-alquiler').val());
				var punitorio_tmp=parseFloat($('#field-punitorios').val());		
				var comi_admin_tmp=parseFloat($('#field-comiAdmin').val());
				var expensas=parseFloat($('#field-expensas').val());
				var agua=parseFloat($('#field-agua').val());
				var csp=parseFloat($('#field-impuesto_csp').val());	
				var inmob=parseFloat($('#field-impuesto_inmob').val());			
				var csp_desc_arreglos=parseFloat($('#field-descArreglos').val());		
				var exp_extras_tmp=parseFloat($('#field-expExtras').val());
				var otros_saldos=parseFloat($('#field-saldos_varios').val());
				var varios1=parseFloat($('#field-varios1').val());
				var varios2=parseFloat($('#field-varios2').val());						

				var total = monto_alquiler_tmp + punitorio_tmp + comi_admin_tmp  + csp_desc_arreglos + expensas + agua + csp + inmob + exp_extras_tmp + otros_saldos + varios1 + varios2;
				var total_pagar = total.toFixed(2);
		  		$("#field-totalPagar").val(total_pagar);
			});			
		}

	var nro_pago=$("#nro_pago").text();
	
	//if(nro_pago==1 || debe_CI>0){		
	if(nro_pago==1 || paga_CI!=0){		
		function validar_comision() {// CALCULAR COMISION EN liquidar_edit/edit

			/*var comision_paga=$("#field-comision_inmo_paga").val();
			if(comision_paga != ""){				
				$("#field-comision_inmo_paga").val(-comision_paga);	
			}else{
				$("#field-comision_inmo_paga").val("0.00");
				
			}*/

			var saldo = parseFloat($("#ci_a_pagar").text());
			var saldo_d=saldo.toFixed(2);
		    var input = parseFloat($("#field-comision_inmo_paga").val());
		    var input_d=input.toFixed(2)*-1;
		    if(input_d > saldo){	    	
		    	$("#field-comision_inmo_paga").val("0");
		    	$("#field-comision_inmo_debe").val(saldo_d);
		    }else{
		    	var resta=saldo_d - input_d;
		    	var resta_d=resta.toFixed(2);
		    $("#field-comision_inmo_debe").val(resta_d);
			}
		}		
	}else{
			// PAGAR ALQUILER: que el input no supere la comision inmobiaria
		function validar_comision() {
			var saldo = parseFloat($("#ci_a_pagar_anterior").val());
			var saldo_d=saldo.toFixed(2);
		    var input = parseFloat($("#field-comision_inmo_paga").val());
		    var input_d=input.toFixed(2);
		    if(input_d > saldo){	    	
		    	$("#field-comision_inmo_paga").val("0");
		    	$("#field-comision_inmo_debe").val(saldo_d);
		    }else{
		    	var resta=saldo_d - input_d;
		    	var resta_d=resta.toFixed(2);
		    $("#field-comision_inmo_debe").val(resta_d);
			}
		}			
	}


//////VALIDANDO LOS INPUT DE LOS FORMULARIOS, NUMEROS DECIMALES POSITIVOS Y NEGATIVOS
	function validateFloatKeyPress(el, evt) {
		  var charCode = (evt.which) ? evt.which : event.keyCode;
		  var number = el.value.split('.');
		  // permitir el signo de - (45)
		  if (charCode != 45 && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		    return false;
		  }
		  //just one dot
		  if (number.length > 1 && charCode == 46) {
		    return false;
		  }
		  //get the carat position
		  var caratPos = getSelectionStart(el);
		  // no permitir que se ponega el - en una posicion diferente de la inicial
		  if (caratPos > 0 && charCode == 45) {
		    return false;
		  }
		  // no permtir mas de un - en el numero
		  if (charCode == 45 && el.value.charAt(0) == "-") {
		    return false;
		  }
		  var dotPos = el.value.indexOf(",");
		  if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
		    return false;
		  }
		  return true;
	}
///////FIN//////////////////		

	$(document).ready(function() {
  		$("#limpiar").click(function() {
   		 	$('.numerico').val('0');   
   		 	//location.reload();		 	
  		});
	});

	function input_ceros(id){
		var  valor = $("#"+id+"").val();
		if(valor == ""){
			$("#"+id+"").val("0");
		}
	}
	// PAGAR ALQUILER: sacar el cero cuando hago click en cada input
	function vaciar(id){
		$("#"+id+"").val("");
	}
	$('.numerico').on('input', function () { 
	    this.value = this.value.replace(/[^0-9\\.]/g,'');
	});

	//font-size
	$("#field-agua,#field-expensas,#field-sellado_paga,#field-certi_firma,#field-comision_inmo_paga,#field-comision_inmo_debe,#ci_a_pagar,#field-saldos_varios,#field-impuesto_csp,#field-impuesto_inmob,#field-idpago,#field-locatario1,#field-locatario2,#field-locador,#field-alquiler,#field-punitorios,#field-periodo,#field-idContrato,#field-expExtras,#field-descArreglos,#field-comiAdmin,#field-varios1,#field-varios2").css("font-size",18);	
	
	//BACKGROUND	
	$("#field-comision_inmo_paga,#field-agua,#field-expensas,#field-sellado_paga,#field-certi_firma,#field-saldos_varios,#field-impuesto_csp,#field-impuesto_inmob,#field-idpago,#field-locador,#field-locatario1,#field-locatario2,#field-alquiler,#field-totalPagar, #field-periodo,#field-comiAdmin,#field-punitorios,#field-descArreglos,#field-idContrato,#field-expExtras,#field-varios1,#field-varios2").css('background-color', '#FDFF93');

	//BOLDER
	$("#field-total_pagar,#calcular,#limpiar").css("font-weight","bolder");	

	//color:red
	$("#field-alquiler,#field-punitorios,#field-comision_inmo_paga,#field-agua,#field-expensas,#field-sellado_paga,#field-certi_firma,#field-saldos_varios,#field-impuesto_csp,#field-impuesto_inmob,#field-expExtras,#field-descArreglos,#field-comiAdmin,#field-varios1,#field-varios2").css("color",'red');	

	$("#ci_a_pagar,#field-comision_inmo_debe").css("color",'blue');

	$("#field-totalPagar").css("color","green");	
	$("#field-totalPagar,#b").css("font-size",22);
	//ALIGN CENTER
	$("#field-comision_inmo_paga,#field-comision_inmo_debe,#ci_a_pagar,#field-agua,#field-expensas,#field-sellado_paga,#field-certi_firma,#field-saldos_varios,#field-impuesto_csp,#field-impuesto_inmob,#field-totalPagar,#field-periodo,#field-alquiler,#field-comiAdmin,#field-punitorios,#field-descArreglos,#field-expExtras,#field-varios1,#field-varios2").css("text-align","center");
}//FIN IF


	function proxvenc(){//alquiler/add
		moment.locale('es');
		$("#mensaje").text("");

			var prox_venc = document.getElementById('field-proxVenc');		
			var dia_paga = document.getElementById('field-fechaPago').value;
			var fecha_inicio = document.getElementById('field-fechaInicio').value;
			
			var datearray = fecha_inicio.split("/");
			var fecha = datearray[1] + '/' + datearray[0] + '/' + datearray[2];
			var fecha_f =new Date(fecha);
			mes_f=fecha_f.getMonth()+1;
			ano_f=fecha_f.getFullYear();

			if(mes_f==13){
				mes_f=1;
				ano_f=ano_f+1;
			}

			if(mes_f < 10 ){
				prox_venc.value = dia_paga+"/0"+mes_f +"/"+ ano_f;	
			}else
				prox_venc.value = dia_paga+"/"+mes_f +"/"+ ano_f;
	}	

	function comprobar_fecha_pago(){
		var fechaPago=$("#field-fechaPago").val();
		var fecha_inicio=$("#field-fechaInicio").val();

		if(fecha_inicio ==""){
			$("#mensaje").text("INGRESE FECHA DE INICIO");
			$("#field-proxVenc").val("");
			$("#field-fechaPago").val("");
			
		}		

		if(fechaPago ==""){
			$("#mensaje").text("INGRESE FECHA DE PAGO");
			$("#field-fechaPago").focus();
			$("#field-proxVenc").val("");
			$("#field-fechaPago").val("");
		}
	}


//FUNCION PARA CALCULAR LA COMISION DE ALQUILER A ABONAR EN EL INGRESO , ESTO ES EN alquiler/add
function calcular_comision(x){
	//alert("tata");
	var duracion,ajuste,valor1,valor2,valor3,valor4,valor5,valor6,total,comision,suma,comision_inmo;
	duracion=$("#field-duracion").val();
	ajuste=$("#field-tipo_ajuste").val();
	valor1=$("#field-valor1").val();
	valor_1=parseInt(valor1);
	valor2=$("#field-valor2").val();
	valor_2=parseInt(valor2);
	valor3=$("#field-valor3").val();
	valor_3=parseInt(valor3);
	valor4=$("#field-valor4").val();
	valor_4=parseInt(valor4);
	valor5=$("#field-valor5").val();
	valor_5=parseInt(valor5);
	valor6=$("#field-valor6").val();
	valor_6=parseInt(valor6);

	var operacion=$("#field-operacion").val();

	var comision_inmo_por=0.0415;

	if(operacion =="ALQUILER"){
		if(duracion==36){
			if(ajuste=="ANUAL"){
				comision_inmo=(valor_1*duracion)*comision_inmo_por;			
			}else if(ajuste=="SEMESTRAL"){
				if(valor1 !="" && valor2 !="" && valor3 !="" && valor4 !="" && valor5 !="" && valor6!=""){
				var seis=6;
				comision_inmo=((valor_1*seis)+(valor_2*seis)+(valor_3*seis)+(valor_4*seis)+(valor_5*seis)+(valor_6*seis))*comision_inmo_por;
				//un_alquiler=total_alquiler/6;	
				//medio_alquiler=un_alquiler/2;
				//comision_inmo=un_alquiler*1.5;				
				}else{
				alert("Faltan valores");
				$("#field-valor6").focus();
				}				
			}
		}
	}else if(operacion == "COMODATO"){
		if(valor6 !=""){
			comision_inmo=(valor_6*duracion)*comision_inmo_por;
		}else if(valor5 !=""){
			comision_inmo=(valor_5*duracion)*comision_inmo_por;
		}else if(valor4 !=""){
			comision_inmo=(valor_4*duracion)*comision_inmo_por;
		}else if(valor3 !=""){
			comision_inmo=(valor_3*duracion)*comision_inmo_por;
		}else if(valor2 !=""){
			comision_inmo=(valor_2*duracion)*comision_inmo_por;
		}else if(valor1 !=""){
			comision_inmo=(valor_1*duracion)*comision_inmo_por;
		}
	}else if(operacion == "COMERCIAL"){
		var total_alquiler,promedio_alquiler,un_alquiler,medio_alquiler;
		if(ajuste=="ANUAL"){
			if(valor1 !="" && valor2 !="" && valor3 !=""){
				var doce=12;
				comision_inmo=((valor_1*doce)+(valor_2*doce)+(valor_3*doce))*comision_inmo_por;
				//un_alquiler=total_alquiler/3;	
				//medio_alquiler=un_alquiler/2;
				//comision_inmo=un_alquiler*1.5;				
			}else{
				alert("Faltan valores");
				$("#field-valor3").focus();
			}
		}else if(ajuste=="SEMESTRAL"){
			if(valor1 !="" && valor2 !="" && valor3 !="" && valor4 !="" && valor5 !="" && valor6!=""){
				var seis=6;
				comision_inmo=((valor_1*seis)+(valor_2*seis)+(valor_3*seis)+(valor_4*seis)+(valor_5*seis)+(valor_6*seis))*comision_inmo_por;
				//un_alquiler=total_alquiler/6;	
				//medio_alquiler=un_alquiler/2;
				//comision_inmo=un_alquiler*1.5;				
			}else{
				alert("Faltan valores");
				$("#field-valor6").focus();
			}
		}else{
			alert("Tipo de Ajuste debe ser SEMESTRAL o ANUAL");

		}

	}	
	var comision_inmo_d=comision_inmo.toFixed(2);
	$("#field-comision_inmo_a_pagar").val(comision_inmo_d);	

}

//CALCULO DEL VALOR DE SELLADO DE CONTRATO: 1% DE LA SUMATORIA DE LOS VALORES MENSUALES QUE TOMA EL ALQUILER EN SU DURACION
function calcular_sellado(id){
	var duracion=$("#field-duracion").val();
	var tipo_ajuste=$("#field-tipo_ajuste").val();
	var valor1=$("#field-valor1").val();
	var valor2=$("#field-valor2").val();
	var valor3=$("#field-valor3").val();
	var valor4=$("#field-valor4").val();
	var valor5=$("#field-valor5").val();
	var valor6=$("#field-valor6").val();
	
	var ajuste,sellado,sellado_f;
	var porc_sellado=0.01;
	var iva=1.21;

	var operacion=$("#field-operacion").val();

	if (tipo_ajuste=='SEMESTRAL'){
		ajuste==6;
	}else if(tipo_ajuste=='OCTOMESTRAL'){
		ajuste==8;
	}else if(tipo_ajuste=='ANUAL'){
		ajuste==12;
	}
	var n=duracion/ajuste;

	if(operacion=="ALQUILER" && duracion==36){

			if(tipo_ajuste=='SEMESTRAL'){
				sellado=parseFloat((((valor1*6)+(valor2*6)+(valor3*6)+(valor4*6)+(valor5*6)+(valor6*6))*porc_sellado));
				sellado_f=sellado.toFixed(2);
				$("#field-sellado_contrato").val(sellado_f);
			}else if(tipo_ajuste=='ANUAL'){
				sellado=parseFloat(((valor1*duracion))*porc_sellado);
				sellado_f=sellado.toFixed(2);
				$("#field-sellado_contrato").val(sellado_f);
			}

	}else if(operacion=="COMERCIAL" && duracion==36){			
	
			if(tipo_ajuste=='SEMESTRAL'){
				sellado=parseFloat((((valor1*6)+(valor2*6)+(valor3*6)+(valor4*6)+(valor5*6)+(valor6*6))*porc_sellado)*iva);
				sellado_f=sellado.toFixed(2);
				$("#field-sellado_contrato").val(sellado_f);
			}else if(tipo_ajuste=='ANUAL'){
				sellado=parseFloat((((valor1*12)+(valor2*12)+(valor3*12))*porc_sellado)*iva);
				sellado_f=sellado.toFixed(2);
				$("#field-sellado_contrato").val(sellado_f);
			}

	}else if(operacion=="COMODATO"){
		$("#field-sellado_contrato").val("");
	}
	
}

	$('.numerico').on('input', function () { 
	    this.value = this.value.replace(/[^0-9\\.]/g,'');
	});

//CALCULO LOS VALORES DEL ALQUILER SEGUN LA DURACION, TIPO DE AJUSTE, LO MUESTRO A MODO DE INFORMACION AL LADO DEL INPUT
function calcular_ajuste(id){//para alquiler	
	var duracion=$("#field-duracion").val();
	var tipo_ajuste=$("#field-tipo_ajuste").val();
	var ajuste_alquiler=$("#field-ajuste").val();
	var porc_ajuste=parseFloat(ajuste_alquiler/100)+1;

	var operacion=$("#field-operacion").val();

	var valor1=$("#field-valor1").val();
	$("#"+id+"").val("");

		if(duracion==24){

			if(operacion=="COMODATO"){

					if(tipo_ajuste != 0 && ajuste_alquiler != ""){
							if(tipo_ajuste =="ANUAL"){

								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);
								$("#field-valor3").prop('disabled',true);
								$("#field-valor4").prop('disabled',true);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);

							}else if(tipo_ajuste =="SEMESTRAL"){

								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);
								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);
								var valor3=$("#field-valor3").val();
								var valor4=parseFloat(valor3*porc_ajuste);
								var valor4_f=valor4.toFixed(2);
								$("#valor4").text(valor4_f);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);

							}else if(tipo_ajuste=="OCTOMESTRAL"){

								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);

								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);

								var valor3=$("#field-valor3").val();
								var valor4=parseFloat(valor3*porc_ajuste);
								var valor4_f=valor4.toFixed(2);
								$("#valor4").text(valor4_f);

								$("#field-valor4").prop('disabled',true);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);							
							}
					}else{
						alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
					}				
			}

		}else if(duracion == 36){

				if(operacion=="COMERCIAL" || operacion=="COMODATO"){

						if(tipo_ajuste != 0 && ajuste_alquiler != ""){
							if(tipo_ajuste =="ANUAL"){
								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);

								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);

								$("#field-valor4").prop('disabled',true);
								$("#field-valor5").prop('disabled',true);
								$("#field-valor6").prop('disabled',true);

							}else if(tipo_ajuste =="SEMESTRAL"){
								$("#field-valor4").prop('disabled',false);
								$("#field-valor5").prop('disabled',false);
								$("#field-valor6").prop('disabled',false);			
								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);

								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);

								var valor3=$("#field-valor3").val();
								var valor4=parseFloat(valor3*porc_ajuste);
								var valor4_f=valor4.toFixed(2);
								$("#valor4").text(valor4_f);

								var valor4=$("#field-valor4").val();
								var valor5=parseFloat(valor4*porc_ajuste);
								var valor5_f=valor5.toFixed(2);
								$("#valor5").text(valor5_f);

								var valor5=$("#field-valor5").val();
								var valor6=parseFloat(valor5*porc_ajuste);
								var valor6_f=valor6.toFixed(2);
								$("#valor6").text(valor6_f);
							}
						}else {
							alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
						}
					}else if(operacion=="ALQUILER"){
						if(tipo_ajuste != 0 && ajuste_alquiler != ""){
							if(tipo_ajuste =="SEMESTRAL"){
								$("#field-valor4").prop('disabled',false);
								$("#field-valor5").prop('disabled',false);
								$("#field-valor6").prop('disabled',false);			
								var valor2=parseFloat(valor1*porc_ajuste);
								var valor2_f=valor2.toFixed(2);
								$("#valor2").text(valor2_f);

								var valor2=$("#field-valor2").val();
								var valor3=parseFloat(valor2*porc_ajuste);
								var valor3_f=valor3.toFixed(2);
								$("#valor3").text(valor3_f);

								var valor3=$("#field-valor3").val();
								var valor4=parseFloat(valor3*porc_ajuste);
								var valor4_f=valor4.toFixed(2);
								$("#valor4").text(valor4_f);

								var valor4=$("#field-valor4").val();
								var valor5=parseFloat(valor4*porc_ajuste);
								var valor5_f=valor5.toFixed(2);
								$("#valor5").text(valor5_f);

								var valor5=$("#field-valor5").val();
								var valor6=parseFloat(valor5*porc_ajuste);
								var valor6_f=valor6.toFixed(2);
								$("#valor6").text(valor6_f);
							}
						}else {
							alert("COMPLETE Tipo de Ajuste, Ajuste Alquiler");
						}

					}						 		
		}	
}

function vaciar_valores(){
	$("#field-valor3").val("");
	$("#field-valor4").val("");
	$("#field-valor5").val("");
	$("#field-valor6").val("");

	$("#valor3").text("");
	$("#valor4").text("");
	$("#valor5").text("");
	$("#valor6").text("");
}
	/*function vaciar(id){
		$("#"+id+"").val("");
	}*/




////////////////////////////////////////////////////
function saldo_comision(x){
	var comision_inmo,comision_paga,comision_debe,comision_debe_fixed,comision_inmo_fixed,comision_paga_fixed;
	comision_inmo=parseFloat($("#field-comision_inmo_a_pagar").val());
	comision_inmo_fixed=comision_inmo.toFixed(2);	

	comision_paga=parseFloat($("#field-comision_inmo_paga").val());
	comision_paga_fixed=comision_paga.toFixed(2);

	comision_debe=comision_inmo_fixed - comision_paga_fixed;

	comision_debe_fixed=comision_debe.toFixed(2);

	$("#field-comision_inmo_debe").val(comision_debe_fixed);
}

	function paga_sellado(x){
		var sellado_paga,sellado_contrato,nro_pago,sellado_paga_f;

		nro_pago=parseInt($("#nro_pago").text());
		sellado_contrato=parseFloat($("#sellado_contrato").text());
		sellado_paga=parseFloat(sellado_contrato/2);
		sellado_paga_f=sellado_paga.toFixed(2);
		
		if(nro_pago==1){
			$("#field-sellado_paga").val(sellado_paga_f);		
		}
	}

	function fin_contrato(){
		moment.locale('es');
		var meses_duracion=$('#field-duracion').val();	

		var operacion=$("#field-operacion").val();
		$("#mensaje").text("");
		//alert(operacion);
		var inicio=$("#field-fechaInicio").val();

		if(inicio !=""){

			if (operacion=="ALQUILER" || operacion=="COMERCIAL" ){	
				if(meses_duracion == 36){

					//$("#field-tipo_ajuste").val("ANUAL");
					var fecha_inicio = $('#field-fechaInicio').val();
					var fecha_inicio_m=moment(fecha_inicio,"DD-MM-YYYY");

					var fin = fecha_inicio_m.add(meses_duracion, 'month');
					var fin_f=fin.format('DD/MM/YYYY');

					var finalizacion=moment(fin).subtract(1, 'days');
					var fin_format=finalizacion.format('DD/MM/YYYY');
					$('#field-fechaFin').val(fin_format);
				}else{
					$("#mensaje").text(" Duración 36 meses para Alquiler/Comercial");
					$("#field-duracion").val("");
					$("#field-fechaFin").val("");
					$("#field-duracion").focus();
					$("#field-duracion").select();					
				}	
			}else if (operacion=="COMODATO"){

					$("#mensaje_tipo_ajuste").text("Si duración es menor de 24 seleccione SIN AJUSTE");
		

					//$("#field-tipo_ajuste option[value='0']").attr("selected",true);
					
					//document.getElementById("field-tipo_ajuste").value = 'SIN AJUSTE';

					//$("#field_tipo_ajuste_chosen span").val("SIN AJUSTE");					

					var fecha_inicio = $('#field-fechaInicio').val();
					var fecha_inicio_m=moment(fecha_inicio,"DD-MM-YYYY");

					var fin = fecha_inicio_m.add(meses_duracion, 'month');
					var fin_f=fin.format('DD/MM/YYYY');

					var finalizacion=moment(fin).subtract(1, 'days');
					var fin_format=finalizacion.format('DD/MM/YYYY');
					$('#field-fechaFin').val(fin_format);
			}else{
				$("#mensaje").text(" SELECCIONE OPERACION");
				$("#field-duracion").val("");
				$("#field-operacion").focus();
				$("#field-operacion").select();

			}
		}else{
			$("#field-duracion").val("");
			$("#mensaje").text(" INGRESE FECHA DE INICIO");				
		}		
	}
	///////////////////////////////////////////////

	
	/*$("#field_operacion_chosen").change(function(){
		alert("aca");
	});*/


	function disable_input_barrio(){
		if($("#field-idEdificio").val()!=""){
			$("#field-idBarrio").prop('disabled', true);
			$("#field-direccion").prop('disabled', true);			
			$("#idE").text($("#field-idEdificio").val());
		}else{
			$("#field-idBarrio").prop("disabled",false);
			$("#field-direccion").prop("disabled",false);			
		}
	}

	function imprimir_carac_inmueble(idI) {
    	window.location.href = "http://"+host+"/SGI/Inmueble/imprimir_inmueble/"+idI;
    	//alert(x);
	}

	function imprimir_requisitos_inmueble(idI) {
    	window.location.href = "http://"+host+"/SGI/Inmueble/imprimir_requisitos/"+idI;
    	//alert(x);
	}	

	function imprimir_gastos_alquiler() {
		var idI=$("#field-idInmueble").val();
		var duracion=$("#field-duracion").val();		
		var tipo_ajuste=$("#field-tipo_ajuste").val();

		var sellado=$("#field-sellado_contrato").val();
		var comision=$("#field-comision_inmo_a_pagar").val();
		var valor1=$("#field-valor1").val();
		var valor2=$("#field-valor2").val();
		var valor3=$("#field-valor3").val();
		var valor4=$("#field-valor4").val();
		var valor5=$("#field-valor5").val();
		var valor6=$("#field-valor6").val();
		var certificacion=$("#certificacion").val();
		var veraz=$("#veraz").val();
		if(sellado==""){
			sellado=0;
		}
		if(comision==""){
			comision=0;
		}
		if(certificacion==""){
			certificacion=0;
		}
		if(veraz==""){
			veraz=0;
		}				

 		window.location.href = "http://"+host+"/SGI/Alquiler/imprimir_gastos_alquiler/"+idI+'/'+duracion+'/'+tipo_ajuste+'/'+sellado+'/'+comision+'/'+certificacion+'/'+veraz+'/'+valor1+'/'+valor2+'/'+valor3+'/'+valor4+'/'+valor5+'/'+valor6;
    	//window.location.href = "http://"+host+"/SGI/Alquiler/imprimir_gastos_alquiler/"+idI
	}

	////MODAL PARA MOSTRAR REQUISITOS////////////////
			function requisitos_alquilar(){
				//$(".areaRequisitos").val("");
				$.post(baseurl+"main/getRequisitosAlquilar/",
					function(data){
						//$("#areaRequisitos").html(data);
						CKEDITOR.instances['areaRequisitos'].setData(data,function(){
							this.checkDirty();  // true
						});
					})

					$("#msg").hide();				
			}

			function guardar_requisitos(){
				var editor_data = CKEDITOR.instances.areaRequisitos.getData();
				//alert(editor_data);

				$.post(baseurl+"alquiler/guardar_requisitos/",{
					content:editor_data
				})
				//$(".msg").show(1500);
				//$('.msg').show("fast");

				$("#msg").delay(500).fadeIn("slow");				
				/*$('#msg').hide(3000);
				$('#msg').hide("fast");*/

			}

			function imprimir_requisitos(){
				//$.post(baseurl+"alquiler/imprimir_requisitos/")	
				window.location.href = "http://"+host+"/SGI/alquiler/imprimir_requisitos";			
			}
//////////////////////////////////////////////	

////////ATENDER RECLAMOS DESDE EL MAIN///
		function atender_reclamos(idR){	
				 $('#tecnico').html("");					
				$.post(baseurl+"/reclamo/getTecnicos",
					function(data){
						var obj=JSON.parse(data);

						$('#tecnico').append($('<option>', { 
							 	value: '',
							    text : 'Elija',
							    selected:true 
						}));

						$.each(obj,function(i,item){
						    $('#tecnico').append($('<option>', { 
						        value: item.ApellidoyNombre,
						        text : item.ApellidoyNombre 
						    }));							
						});
					});

			$.post(baseurl+"alquiler/buscar_reclamo/"+idR,

					function(datas){						 	
						var inmueble=$("a[id="+idR+"]").text();
							
						var obje=JSON.parse(datas);						
						$.each(obje,function(i,item){
							$("#locatario").text('  Reclamo de '+item.locatario1);
							$("#inmueble").text(inmueble);
							$("#telefono").text(' - Contacto: '+item.telefono);
							$("#inicio").text('Iniciado: '+item.fechaReclamo);							
							$("#update").text(' - Atendido: '+item.fecha_atencion+' - '+item.estado);
							$('#problema').val(item.problema);


							$("#tecnico option").each(function(){
								if($(this).val()==item.encargado){
									$("#tecnico option[value='"+item.encargado+"']").attr("selected","selected");
									return false;
								}
							})
							
							$("#idR").val(idR);					
							$('#descripcion').val(item.descripcion);
							$("#costo").val(item.dinero_dado);
							$("#para").val(item.dinero_desc);
							$("#paga").val(item.quien_paga);
						})
					});
			
			$('#reclamos').modal('show');	

		}
		

		$("#botonenviar").click( function() {			
			if(validarForm()){
				$("#msgTecnico").text("");
				$.post(baseurl+"reclamo/update_reclamo",$("#reclamo").serialize(),function(res){
	              if(res==1){
	                    $("#exito").delay(500).fadeIn("slow");
	                     $('#reclamos').on('hidden.bs.modal', function () { location.reload(); }) 
	                }else{
	                    $("#error").delay(500).fadeIn("slow"); 
	                }	
	               // alert(res);			
				});
			}	
		}); 


		$("#imprimir_reclamo").click( function() {	
			var idR=$("#idR").val();		
			window.location.href = "http://"+host+"/SGI/reclamo/imprimir_reclamo/"+idR;
			
		}); 



		function validarForm(){//validar formulario de reclamos
			if($("#tecnico").val()==""){
				 $("#msgTecnico").delay(200).fadeIn("slow");
				 $("#tecnico").focus();
				 return false;
			}			
			if($("#paga").val()==""){
				 $("#msgPaga").delay(200).fadeIn("slow");
				 $("#paga").focus();
				 return false;
			}		
			return true;
		}

		function borrar_msg(){
			if($("#tecnico").val()!=""){
				$("#msgTecnico").text("");
			}
			if($("#paga").val()!=""){
				$("#msgPaga").text("");
			}	
			if($("#especialidad").val()!=""){
				$("#msgEspecialidad").text("");
			}
			if($("#problemaNuevo").val()!=""){
				$("#msgProblema").text("");
			}
			if($("#prioridad").val()!=""){
				$("#msgPrioridad").text("");
			}									

		}

			///////////////////////////////////NUEVO RECLAMO EN EL MAIN////////////////////

			$('#m_nuevoReclamo').on('hidden.bs.modal', function (e) {//borra contenido del modal
			  $(this)
			    .find("input,textarea,select,div")			    	
			       	.val('')
			       	.end()
			    .find("input[type=checkbox], input[type=radio]")
			       .prop("checked", "")
			       .end();
			})	
					
			function nuevoReclamo(){
				$('#especialidad').html("");
			   	$.post(baseurl+"/reclamo/getEspecialidad",function(data){
					//var especialidad = data.split(",");

					$('#especialidad').append($('<option>', { 
						value: '',	 	
						text : 'Elija',
						selected:true 
					}));

					var obj=JSON.parse(data);
					$.each(obj, function(index, value) { 
  						$('#especialidad').append($('<option>', { 
						    value: value,
						    text : value 
						}));
					});

			        		          
			     });
					$(".resultadoPersonas").html("");
					$("#direccion").val("");
					$("#locador").val("");	
					$("#contacto").val("");
				$('#m_nuevoReclamo').modal('show');	

			};
			
			function buscarPersonas(){//////aca busco la persona y se carga para el nuevo reclamo					
					$("#direccion").val("");
					$("#locador").val("");					
					$("#contacto").val("");
					$("#msgbusquedaPersona").text("");
			   		var textoBusqueda = $("#busquedaPersona").val();
			        $.post(baseurl+"/reclamo/buscarPersonas", {valorBusqueda: textoBusqueda}, function(data){
			          $(".resultadoPersonas").html(data);			          			          
			        }); 			         
			};

		    $('.resultadoPersonas').click(function(event) {
		        var dni=event.target.id;
		        var nombre=$("#"+dni+"").text();
		        var contacto=$("#"+dni+"").attr('telefono');
		        $("#busquedaPersona").val(nombre);		                		        
		        $("input[dni]").attr("dni",dni);
		        $("#contacto").val(contacto);
		        
		      	//console.log("hola");
		      	$(".resultadoPersonas").html("");
			    //aca cargamos el inmueble

			   $.post(baseurl+"/reclamo/buscarInmueble", {valorBusqueda: dni}, function(data){
			      	var obj=JSON.parse(data);
					var direccion=obj.direccion;	
					var locador=obj.locador;
					var idC=obj.idC;			      	

					$("#direccion").val(direccion);
					$("#locador").val(locador);	
					$("#idC").val(idC);
			   }); 

		    });

			$("#enviarReclamo").click( function() {			
				if(validarNuevoReclamo()){
					$("#msgbusquedaPersona").text("");

					$.post(baseurl+"reclamo/nuevoReclamo",$("#nuevoReclamo").serialize(),function(res){
		              	if(res==1){
		                    $("#exitoReclamo").delay(500).fadeIn("slow");
		                     $('#m_nuevoReclamo').on('hidden.bs.modal', function () { location.reload(); }) 
		                     //alert("aca");
		                }else{
		                    $("#errorReclamo").delay(500).fadeIn("slow"); 
		                }	
		               //alert(res);			
					});
				}	
			}); 

			function validarNuevoReclamo(){//validar formulario de reclamos
				if($("#busquedaPersona").val()==""){
					 $("#msgbusquedaPersona").delay(200).fadeIn("slow");
					 $("#busquedaPersona").focus();
					 return false;
				}			
				if($("#contacto").val()==""){
					 $("#msgTelefono").delay(200).fadeIn("slow");
					 $("#contacto").focus();
					 return false;
				}
				if($("#especialidad").val()==""){
					 $("#msgEspecialidad").delay(200).fadeIn("slow");
					 $("#especialidad").focus();
					 return false;
				}	
				if(!$("#problemaNuevo").val()){
					 $("#msgProblema").delay(200).fadeIn("slow");
					 $("#problema").focus();
					 return false;
				}
				if(!$("#prioridad").val()){
					 $("#msgPrioridad").delay(200).fadeIn("slow");
					 $("#prioridad").focus();
					 return false;
				}														
				return true;
			}

			//////////////////////////////////////////////////////////////////////////////

		function calcular_periodo_rescision(){
			
			var periodo_fecha=$("#venc_periodo").text();
			var periodo_formateado=moment(periodo_fecha);
			var rescinde_dentro=$("#field-rescinde_dentro").val();

			var duracion=$("#field-duracion").val();

			var nro_pago=$("#nro_pago").text();
			var dif=duracion-nro_pago;	

			var fecha = new Date();			 
			var dia=fecha.getDate();	

			//alert(periodo_formateado);

			if(rescinde_dentro != 0 && rescinde_dentro != ""){
				if((rescinde_dentro<=(dif+1)) && dia < 16) {
					
						var periodo_rescinde = periodo_formateado.add((rescinde_dentro-1), 'month');
						var periodo_f=moment(periodo_rescinde).format('MMM-YY');
						var rescinde_periodo_f=periodo_f.toUpperCase();								
				}else if((rescinde_dentro<=dif) && dia >= 16){
						var dif_2=dif-1;

						var periodo_rescinde = periodo_formateado.add((rescinde_dentro), 'month');
						var periodo_f=moment(periodo_rescinde).format('MMM-YY');
						var rescinde_periodo_f=periodo_f.toUpperCase();						
				}else{
					alert("Supera el fin de contrato!!!!");
					$("#field-rescinde_dentro").val("");
				}
				$("#field-rescinde_fecha").val(rescinde_periodo_f);
			}else{
				$("#field-rescinde_fecha").val("");
			}
		}			


			function mayus(e) {
			    e.value = e.value.toUpperCase();
			}

		function is_negative_number(number){

        if( (is_numeric(number)) && (number<0) ){
            return true;
        }else{
        	alert("Debe Ingresar un numero negativo!!!");
            return false;
        }
}	
