<?php

// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
// FORMULARIO DE ALTA DE USUARIOS
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<form  name='REGISTRO' method="POST" action="index.php?orden=Registro">
<center  id='registro'>
<table id='tablas'>
		<tr>
			<td>Usuario:</td>
			<td><input id='formu' type="text" name="user"
				value=""></td>
		</tr>
		<tr>
			<td>Nombre:</td>
			<td><input id='formu' type="text" name="nombre"
				value=""></td>
		</tr>
		<tr>
			<td>Contraseña:</td>
			<td><input id='formu' type="password" name="clave"
				value=""></td>
		</tr>
		
		<tr>
			<td>Repita la contrase�a:</td>
			<td><input id='formu' type="password" name="clave2"
				value=""></td>
		</tr>
		
	
        <tr>
			<td>Correo:</td>
			<td><input id='formu' type="text" name="correo"
				value=""></td>
		</tr>
        <tr>
			<td>Plan:</td>
			<td><select id='select' name="tipo">
				<option value="0">Basico</option>
				<option value="1">Profesional</option>
				<option value="2">Premium</option>
				</select>
			</td>
		</tr>
		
	</table>
	<input type="hidden" name="orden" value="Enviar">
	<input id='boton' type="submit"  value="Enviar datos">
	<input id='boton' onClick="javascript:window.history.back();" type="button" name="Submit" value="Atrás" />
</center>
</form>

<?php 
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>
