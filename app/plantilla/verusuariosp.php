<?php
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();

?>

<center id='usuariosC'>
<table id='tusuarios'>
	<tr>
<?php
$auto = $_SERVER['PHP_SELF'];
// identificador => Nombre, email, plan y Estado
?>
<?php foreach ($usuarios as $clave => $datosusuario) : ?>
<tr>	
	
<td  id='login'><?= $clave ?></td> 
	<?php for  ($j=0; $j < count($datosusuario); $j++) :?>
     <td id='usuarios'><?=$datosusuario[$j] ?></td>
	<?php endfor;?>
<td  id='usuarios'><a href="#" onclick="confirmarBorrar('<?= $datosusuario[0]."','".$clave."'"?>);"><div id="flotante">Eliminar</div><img class="eliminar" src="web/img/basura.png"></a></td>
<td  id='usuarios'><a href="<?= $auto?>?orden=Modificar&id=<?= $clave ?>"><div id="flotanteM">Modificar</div><img class="modificar" src="web/img/lapiz.png"></a></td>
<td  id='usuarios'><a href="<?= $auto?>?orden=Detalles&id=<?= $clave?>"><div id="flotanteD">Detalles</div><img class="detalles" src="web/img/papel.png"></a></td>
</tr>

<?php endforeach; ?>
</table>
<table>
	<tr>
       <td> <form action='index.php'>
        	<input  type='hidden' name='orden' value='Cerrar'> 
        	<input id='cerrar' type='submit' value='Cerrar Sesión'>
        	
        </form>
        </td>
        <td>
        <form action='index.php'>
        	<input  type='hidden' name='orden' value='Alta'> 
        	<input id='cerrar' type='submit' value='nuevo usuario'>
        </form>
        </td>
        
         <td>
        <form action='index.php'>
        	<input  type='hidden' name='orden' value='VerFicheros'> 
        	<input id='cerrar' type='submit' value='Ver ficheros'>
        </form>
        </td>
    </tr>
</table>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>
