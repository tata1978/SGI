function editar_fecha(fecha, intervalo, dma, simbolo) {
 
  var simbolo = simbolo || "-";
  var arrayFecha = fecha.split(simbolo);
  var dia = arrayFecha[0];
  var mes = arrayFecha[1];
  var anio = arrayFecha[2]; 
  
  var fechaInicial = new Date(anio, mes - 1, dia);
  var fechaFinal = fechaInicial;
  if(dma=="m" || dma=="M"){
    fechaFinal.setMonth(fechaInicial.getMonth()+parseInt(intervalo));
  }else if(dma=="y" || dma=="Y"){
    fechaFinal.setFullYear(fechaInicial.getFullYear()+parseInt(intervalo));
  }else if(dma=="d" || dma=="D"){
    fechaFinal.setDate(fechaInicial.getDate()+parseInt(intervalo));
  }else{
    return fecha;
  }
  dia = fechaFinal.getDate();
  mes = fechaFinal.getMonth() + 1;
  anio = fechaFinal.getFullYear();
 
  dia = (dia.toString().length == 1) ? "0" + dia.toString() : dia;
  mes = (mes.toString().length == 1) ? "0" + mes.toString() : mes;
 
  return dia + "-" + mes + "-" + anio;
}
