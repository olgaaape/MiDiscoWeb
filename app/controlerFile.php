<?php
// ------------------------------------------------
// Controlador que realiza la gestión de usuarios
// ------------------------------------------------
include_once 'configDB.php';
include_once 'modeloUser.php';
include_once 'modeloUserDB.php';

function ctFileVerFicheros (){
    $ruta = "app/dat/". $_SESSION['user'];
    $ficheros = modeloUserGetFicheros($ruta);
    
    $numFicheros = modeloDatos($ruta);
    $espacioOcupado = modeloDirectorio($ruta);
    if($_SESSION['user'] =="admin"){
        include_once 'plantilla/verficherosadmin.php';
    } else {
    include_once 'plantilla/verficheros.php';
    }
}

function ctFileSubirFicheros(){
    $msg="";
     $ruta = "app/dat/". $_SESSION['user'];
    $archivo = (isset($_FILES['archivo'])) ? $_FILES['archivo'] : null;
  
    $nombreArchivo=$_FILES['archivo']['name'];
    $tmpArchivo=$_FILES['archivo']['tmp_name'];

    $listadetalles = modeloUserDB::UserGet($_SESSION['user']);
    $plannumero=$listadetalles[4];
    
    $numFicheros = modeloDatos($ruta);
    $espacioOcupado = modeloDirectorio($ruta);
    $tamañoFichero = $_FILES['archivo']['size']; 
    
    if(!modeloFileSave($nombreArchivo,$tmpArchivo,$numFicheros,$espacioOcupado,$tamañoFichero,$plannumero)){
        $msg="Error al subir el fichero";
    }
    $ruta = "app/dat/". $_SESSION['user'];
    $ficheros = modeloUserGetFicheros($ruta);
    $numFicheros = modeloDatos($ruta);
    $espacioOcupado = modeloDirectorio($ruta);
    $espacioOcupado =round(($espacioOcupado/1000),2);
    include_once 'plantilla/verficheros.php';
}

//function ctFileBorrarFicheros(){
  //  if (isset($_GET['file'])){
    //    $fichero=$_GET['file'];
      //  modeloFileDel($fichero);
        //if(modeloFileDel($fichero)){
          //  include_once 'plantilla/verficheros.php';
        //}else{
          //  $msg="Error al borrar el fichero";
            //include_once 'plantilla/verficheros.php';
        //}
    //}
    
    //$ruta = "app/dat/". $_SESSION['user'];
    //$numFicheros = modeloDatos($ruta);
    //$espacioOcupado = modeloDirectorio($ruta);
//}
function ctFileBorrarFicheros(){
    $usuario = $_SESSION['user'];
    $nombre= RUTA_FICHEROS."/".$usuario."/".$_GET["id"];
    unlink($nombre);
    header('Location:index.php?operacion=VerFicheros');
}

function ctFileRenombrarFicheros(){
    if (isset($_GET['id'])){
        $fichero=$_GET['id'];
        $nuevoNombre=$_GET['nombre'];
        if(modeloFileRenombrar($fichero,$nuevoNombre)){
            $msg="Error al renombrar el fichero";
        }
        $ruta = "app/dat/". $_SESSION['user'];
        $ficheros = modeloUserGetFicheros($ruta);
        $numFicheros = modeloDatos($ruta);
        $espacioOcupado = modeloDirectorio($ruta);
        include_once 'plantilla/verficheros.php';
        }
}
function ctFileDescargar(){
    $fichero = $_GET['file'];
    $usuario = $_SESSION['user'];
    $rutaArchivo= RUTA_FICHEROS."/".$usuario."/".$fichero;
    modeloFileDescargar($fichero, $rutaArchivo);
}





function ctFileCompartir(){
    $fichero = $_GET['file'];
    $usuario = $_SESSION['user'];
    $rutaArchivo= RUTA_FICHEROS."/".$usuario."/".$fichero;
    $rutaencriptada = modeloUserDB::encripta($rutaArchivo);
    
    // Genero la ruta de descarga
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $link = "https";
        else
            $link = "http";
            $link .= "://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
            $link .="?orden=DescargaDirecta&fdirecto=".urlencode($rutaencriptada);
            echo "<script type='text/javascript'>alert('Fichero [$fichero]:. Enlace de descarga:$link');".
                "document.location.href='index.php?operacion=VerFicheros';</script>";
            
            
}

function ctFileDescargaDirecta(){
    if (!empty($_GET['fdirecto'])) {
        $rutaArchivo = modeloUserDB::desencripta($_GET['fdirecto']);
        $pos = strrpos ( $rutaArchivo , "/");
        $fichero = substr($rutaArchivo,$pos+1);
        modeloFileDescargar($fichero,$rutaArchivo);
    }
}









?>