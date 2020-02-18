

<?php 
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
$auto = $_SERVER['PHP_SELF'];
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<form name='ACCESO' method="POST" action="index.php">
<center id='acceso'>
	<a class="enlaceAlta" href="<?= $auto?>?orden=Registro"> Darse de alta</a>
	<table id='tacceso'>
		<tr>
			<td>Usuario</td>
			<td><input id='facceso' type="text" name="user"
				value="<?= $user ?>"></td>
		</tr>
		<tr>
			<td>Contraseña:</td>
			<td><input id='facceso' type="password" name="clave"
				value="<?= $clave ?>"></td>
		</tr>
	</table>
	<input id='entrar' type="submit" name="orden" value="Entrar">
	</center>
</form>
<?php 
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>
