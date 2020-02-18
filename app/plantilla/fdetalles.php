<?php

// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
// FORMULARIO DE ALTA DE USUARIOS
$auto = $_SERVER['PHP_SELF'];
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<center id='detalles'>
<h1>Detalles del <?= $clave ?></h1>
<table id='tablas'>
		<tr>
			<td id='dapartado'>Nombre:</td>
			<td id='dcontenido'><?= $nombre ?></td>
		</tr>
		<tr>
			<td id='dapartado'>Correo electronico:</td>
			<td id='dcontenido'><?= $correo ?></td>
		</tr>
		<tr>
			<td id='dapartado'>Plan:</td>
			<td id='dcontenido'><?= $plan?></td>
		</tr>
        <tr>
			<td id='dapartado'>Numero de ficheros:</td>
			<td id='dcontenido'><?= $numFicheros?></td>
		</tr>
        <tr>
			<td id='dapartado'>Espacio ocupado:</td>
			<td id='dcontenido'><?= $espacioOcupado?></td>
		</tr>
		
	</table>
	<button id='atras'> <a class="enlaceDetalles" href="<?= $auto?>?orden=VerUsuarios">Volver</a></button>
	</center>
<?php 
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";



?>
