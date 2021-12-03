<!DOCTYPE html>
<html>
<head>
	<title>Alquiler de Inmuebles</title>
	 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
</head>
<body>
	<div class="col-lg-5">
		<form method="post" action="">
			<div class="form-group col-lg-8">
				<label>Inmueble</label>
				<input type="text" name="inmueble" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Locatario</label>
				<input type="text" name="locatario" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Garante</label>
				<input type="text" name="garante" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Locador</label>
				<input type="text" name="locador" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Fecha Inicio</label>
				<input type="date" name="inicio" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Fecha Fin</label>
				<input type="date" name="fin" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Mora Diaria</label>
				<input type="text" name="mora" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Comision</label>
				<input type="text" name="comision" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Dia de pago</label>
				<input type="text" name="pago" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Duracion</label>
				<input type="text" name="duracion" class="form-control" required="true">
			</div>
			<div class="form-group col-lg-8">
				<label>Valor</label>
				<input type="text" name="valor" class="form-control" required="true">
			</div>

			<input type="submit" class="btn btn-primary" name="grabar" value="Grabar">
			<input type="reset" class="btn btn-danger" name="reset" value="Limpiar">
		</form>
	</div>
	

</body>
</html>
