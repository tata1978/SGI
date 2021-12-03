<!DOCTYPE html>
<html>
<head>
	<title>SGI - Inmuebles</title>
	<meta charset="utf-8">
<?php foreach ($css_files as $file): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $file; ?>">	
<?php endforeach; ?>
<?php foreach ($js_files as $file): ?>
<script src="<?php echo $file; ?>" ></script>	
<?php endforeach; ?>
</head>
<body>
	<div> <?php echo $output; ?>	</div>
</body>
</html>