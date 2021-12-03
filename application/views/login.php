<!DOCTYPE html>
<html lang="esp">
<head>
	<title>Taragui Propiedades</title>
	<meta charset="UTF-8">
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-3.3.1.min.js'); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/login.css'); ?>">
	<script type="text/javascript" src="<?php echo base_url('assets/js/login.js'); ?>"></script>
        <script type="text/javascript">
function hora(){
    var fecha= new Date();
    var horas= fecha.getHours();

    var minutos = fecha.getMinutes();
    var minutes = minutos > 9 ? minutos : '0' + minutos;

    var segundos = fecha.getSeconds();
    var seconds = segundos > 9 ? segundos : '0' + segundos;

    document.getElementById('contenedor').innerHTML=''+horas+':'+minutes+':'+seconds+'';
    setTimeout('hora()',1000);
}
        </script>
</head>
<body onload="javascript:hora()">  

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
        <td bgcolor="" width="20%"  style="color: #FFFFFF; text-align: center; font-size:17px">
            <?php
                setlocale(LC_TIME,'spanish');
                $dateutf = strftime("%A, %d de %B de %Y");
                $dateutf = ucfirst(iconv("ISO-8859-1","UTF-8",$dateutf));
                echo $dateutf;
            ?>          
         </td>
      <th bgcolor="" width="60%" style="color: #FFFFFF; text-align: center;"><h1><font color="#FFFFFF" style="font-family:Book Antiqua;">Taragüi Propiedades</font></h1></th>
      <td  id="hora "bgcolor="" width="20%" style="color: #FFFFFF; text-align: center;font-size:17px"><div id="contenedor"></div></td>
    </tr>
  </tbody>
</table>
<hr>
    <div class="container">
        <div class="card card-container">
            <? $host=$_SERVER['SERVER_NAME']; ?>
            <p style="text-align:center;"><img id="profile-img"  src="<?php echo base_url('assets/images/icon.png'); ?>" width="200" height="125"></p>
            <p id="profile-name" class="profile-name-card"></p>
            <form action="" method="GET"  class="form-signin">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="text" id="inputEmail" name="usuario" class="form-control" placeholder="Usuario" required autofocus>
                <input type="password" id="inputPassword" name="clave" class="form-control" placeholder="Clave" required>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Ingresar</button>
            </form><!-- /form -->

        </div><!-- /card-container -->
    </div><!-- /container -->

</body>

</html>
