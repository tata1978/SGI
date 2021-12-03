<!DOCTYPE html>
<html lang="es">
<head>
  	<title>Taragui Propiedades</title>
  	<meta charset="UTF-8">    
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-3.3.1.js'); ?>"></script>
          <script type="text/javascript" src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"> </script>

      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->    


      <script type="text/javascript"   src="<?php echo base_url('assets/js/moment.min.js'); ?>"></script>
      <script type="text/javascript"  src="<?php echo base_url('assets/js/moment-with-locales.js'); ?>"></script>
          <!-- Bootstrap CSS -->
      <script type="text/javascript"  src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>          
       <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
        <!-- Latest compiled and minified JavaScript -->
        <!-- Optional theme -->
        <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-theme.min.css'); ?>">-->

       

      <!--SEPARACION ENTRE LA BARRA DE NAVEGACION Y EL GRID DE GC-->
      <style type="text/css">
          body {
            padding-top: 44px;
            
          }
          .contenido{
            padding: 10px;
          }
          .loader {
              position: fixed;
              left: 0px;
              top: 0px;
              width: 100%;
              height: 100%;
              z-index: 9999;
              background: url("<?php echo site_url('assets/images/loading.gif')?>") 50% 50% no-repeat rgb(249,249,249);
              opacity: .8;
          }          
       </style>
  <?php 
  foreach($css_files as $file): ?>
  	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
  <?php endforeach; ?>
  <?php foreach($js_files as $file): ?>
  	<script src="<?php echo $file; ?>"></script>
  <?php endforeach; ?>
      <style>
        .dropdown:hover > .dropdown-menu {
          display: block;
        }

        label[for=navbar-toggle-cbox] {
          cursor: pointer;
        }

        #navbar-toggle-cbox:checked ~ .collapse {
          display: block;
        }

        #navbar-toggle-cbox {
          display: none
        }
    body {
      background-color: #FAFAFA;

    }
    </style>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/encabezado.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/main.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/caja.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/formularios.css'); ?>">
</head>
<body onload="hora()"> 
  
<div id="body">
  <?php $this->load->view('header/menu');?>
  <?php $this->load->view('header/encabezado');?>
</div>

    <div>       
		  <?php echo $output; ?>
    </div> 
   
<script type="text/javascript">
  var baseurl = "<?php echo base_url(); ?>"
</script>  
  <?php include_once('modal/disponibles.php') ?>
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.tablesorter.js'); ?>"> </script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.quicksearch.js'); ?>"> </script>
 <script type="text/javascript" src="<?php echo base_url('assets/js/mi.js'); ?>"> </script>
</body>


