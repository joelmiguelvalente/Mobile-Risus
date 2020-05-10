<?php 
include '../header.php';
$URL = $tsCore->settings['url'] . '/install';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
	<title>[MOD] Risus Mobile</title>
</head>
<body>
	<header>
		<h2>Instalaci&oacute;n Risus Mobile por Kmario19 &copy;</h2>
	</header>
	<main>
		<div class="container">
			<?php if(isset($_GET['page']) == '') { ?>

			<p>
				<h3>Actualizaci&oacute;n: 25/07/2015</h3>
				<ul>
					<li>Compatible con Risus 1.2</li>
					<li>Ahora no es necesario copiar el archivo c.config.php ni cambiar el nombre de la carpeta default por el del tema que estas usando.</li>
					<li>Ahora se pueden compartir posts por <strong>Whatsapp</strong></li>
					<li>Al hacer un comentario aparece una firma "enviado desde la versi&oacute;n mobile"</li>
					<li>Header fixed para poder usar el men&uacute; sin necesidad de volver hasta el cielo de la p&aacute;gina.</li>
					<li>Los archivos .js y .css fueron comprimidos para agilizar a&uacute;n m&aacute;s la carga, sin embargo est&aacute;n disponibles los archivos originales para realizar nuevos themes.</li>
					<li>Al dar click en "Versi&oacute;n de escritorio" lleva a la web con la url actual.</li>
					<li>Multiples fixes en funciones, dise&ntilde;o, seguridad, etc.</li>
				</ul>
				<a class="btn" href="<?php echo $URL; ?>/procedimiento">Ver procedimiento</a>
			</p>

			<hr>

			<p>
				<h3>Actualizaci&oacute;n: 14/10/2019</h3>
				<ul>
					<li>Compatible con Risus 1.3</li>
					<li><s>Ya no contiene el Smarty</s></li>
					<li>El caché se almacenará en la misma carpeta que el theme normal</li>
					<li>PHP 7+</li>
					<li>Cambio un poco el aspecto al menú</li>
					<li>Se agregó los bbcode [JBBCode].</li>
					<li>No se agregó ningún editor</li>
					<li class="red">Falta reparar el Registro</li>
				</ul>
			</p>
			<hr>

			<p>
				<h3>Actualizaci&oacute;n: 10/05/2020</h3>
				<ul>
					<li>Smarty 3.1.34-dev-7</li>
					<li>Cambio un poco el aspecto al menú</li>
					<li>Actualización de JBBCode.</li>
					<li>Actualización de jQuery 3.5.0.</li>
					<li>No se agregó ningún editor</li>
					<li>Registro funcional y actualizado </li>
				</ul>
			</p>
		<?php  
			} else if(isset($_GET['page']) && $_GET['page'] ==  'procedimiento') {
				include 'procedimiento.php';

			}
		?>
			
		</div>
	</main>
	<footer>
		<p>Eso es todo, disfruta de tu web para dispositivos m&oacute;viles.</p>
	</footer>
</body>
</html>