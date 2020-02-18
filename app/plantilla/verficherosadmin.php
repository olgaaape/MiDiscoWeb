<?php
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();

?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<center id='usuariosC'>
<table id='tusuarios'>
	<tr>
<?php
$auto = $_SERVER['PHP_SELF'];

// identificador => Nombre, email, plan y Estado
?>
<h1>Ficheros del usuario <?= $_SESSION['user'] ?></h1>
<?php foreach ($ficheros as $clave => $datosfichero) : ?>
<tr>	

<td  id='usuarios'><?= $clave ?></td> 
	<?php for  ($j=0; $j < count($datosfichero); $j++) :?>
     <td id='usuarios'><?=$datosfichero[$j] ?></td>
	<?php endfor;?>
<td  id='usuarios'><a href="#" onclick="confirmarBorrarFile('<?= $clave?>');"><div id="flotante">Eliminar</div><img class="eliminar" src="web/img/basura.png"></a></td>
<td  id='usuarios'><a href="#" onclick="confirmarRenombrarFile('<?= $clave?>');"><div id="flotanteM">Renombrar</div><img class="modificar" src="web/img/lapiz.png"></a></td>
<td  id='usuarios'><a href="<?= $auto?>?orden=Descargar&file=<?= $clave?>">Descargar</a><div id="flotanteD"></div>
<td  id='usuarios'><a href="<?= $auto?>?orden=Compartir&file=<?= $clave?>">Compartir</a><div id="flotanteD"></div>

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
        <form  enctype="multipart/form-data" method="post" action="index.php?orden=SubirFichero">
         	<input type="file" name="archivo">
        	<input  type='hidden' name='orden' value='Enviar'> 
        	<input id='cerrar' type='submit' value='Subir Fichero'>
        </form>
        </td>
        
        
        <td>
        <form action='index.php'>
        	<input  type='hidden' name='orden' value='VerUsuarios'> 
        	<input id='cerrar' type='submit' value='Volver'>
        </form>
        </td>
    </tr>
</table>
<h2 style="color:white" > Ficheros: <?= $numFicheros ?> </h2>
<h2 style="color:white" > Espacio ocupado: <?= $espacioOcupado ?> </h2>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>
