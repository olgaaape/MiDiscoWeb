<?php 
include_once 'configDB.php';
/* DATOS DE USUARIO
â€¢ Identificador ( 5 a 10 caracteres, no debe existir previamente, solo letras y nÃºmeros)
â€¢ ContraseÃ±a ( 8 a 15 caracteres, debe ser segura)
â€¢ Nombre ( Nombre y apellidos del usuario
â€¢ Correo electrÃ³nico ( Valor vÃ¡lido de direcciÃ³n correo, no debe existir previamente)
â€¢ Tipo de Plan (0-BÃ¡sico |1-Profesional |2- Premium| 3- MÃ¡ster)
â€¢ Estado: (A-Activo | B-Bloqueado |I-Inactivo )
*/
// Inicializo el modelo 
// Cargo los datos del fichero a la session
function modeloUserInit(){
    
    /*
    $tusuarios = [ 
         "admin"  => ["12345"      ,"Administrado"   ,"admin@system.com"   ,3,"A"],
         "user01" => ["user01clave","Fernando PÃ©rez" ,"user01@gmailio.com" ,0,"A"],
         "user02" => ["user02clave","Carmen GarcÃ­a"  ,"user02@gmailio.com" ,1,"B"],
         "yes33" =>  ["micasa23"   ,"Jesica Rico"    ,"yes33@gmailio.com"  ,2,"I"]
        ];
    */
    
    $datosjson = @file_get_contents(FILEUSER) or die("ERROR al abrir fichero de usuarios");
    $tusuarios = json_decode($datosjson, true);
    $_SESSION['tusuarios'] = $tusuarios;
   

      
}

function modeloUserEncriptar($clave){ //PASADO
   
        return password_hash($clave, PASSWORD_DEFAULT, ['cost' => 10]);
    
}
// Comprueba usuario y contraseÃ±a (boolean)
function modeloOkUser($user,$clave){//PASADO
    
    if(password_verify($clave, $_SESSION['tusuarios'][$user][0])){//COMPRONAR QUE LA CLAVE ENCRIPTADA ES IGUAL A LA NORMAL
        return true;
    } else {
        return false;
    }
}

// Devuelve el plan de usuario (String)
function modeloObtenerTipo($user){ //COPIADO
    
    $codplan = $_SESSION['tusuarios'][$user][3];
    
    return PLANES[$codplan];
}

function modeloObtenerEstado($user){
    $codestado = $_SESSION['tusuarios'][$user][4];
    
    return ESTADOS[$codestado];
}

// Borrar un usuario (boolean)
function modeloUserDel($user){//COPIADO
    unset($_SESSION['tusuarios'][$user]);
    rmdir("./app/dat/".$user);
    return true;
}
//comprobamos requisitos al dar de alta
function modeloUserComprobar($user, $nuevo){
    $msg= '';
    $contrase�a=$nuevo[0];
    $contrase�a2 = $nuevo[5];
    $nombre =$nuevo[1];
    $correo =$nuevo[2];
    var_dump($correo);
    if (array_key_exists($user, $_SESSION['tusuarios'])) {
        $msg ='El usuario ya exist�a.';
    }
    if($contrase�a != $contrase�a2 ){
        $msg .=' Ambas contrase�as deben coincidir';
    }
    foreach($_SESSION['tusuarios'] as $clave=>$value){
       // if(in_array($correo, $value)){
        if($_SESSION['tusuarios'][$clave][$value][2]==$correo){
            
            $msg .=' El correo introducido ya est� asociado a otra cuenta.';
            break;
        }
    }
    if(strlen($user)<=5 || strlen($user)>=10){
        $msg .= ' El usuario debe tener un nombre entre cinco y diez caracteres.';
    }
    if(!ctype_alnum($user)){
        $msg .=' La contrase�a que ha introducido no es correcta.';
    }
    if(strlen($nombre)>20){
        $msg .= ' El nombre no puede ocupar m�s de veinte caracteres.';
    }
    if(strlen($contrase�a)<=8 || strlen($contrase�a)>15){
        $msg .= ' La contrase�a debe tener entre 8 y 15 caracteres';
    }
    if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $msg .= ' El modelo del correo no es el adecuado';
    }
    return $msg;
}

//comprobamos requisitos al modificar
function modeloUserComprobarModi($user, $nuevo,$oldcorreo){
    $msg= '';
    $contraseña=$nuevo[0];
    $nombre =$nuevo[1];
    $newcorreo =$nuevo[2];
    
    
        foreach($_SESSION['tusuarios'] as $clave=>$value){
            if($_SESSION['tusuarios'][$clave][$value][2]==$newcorreo){
                if($newcorreo!=$oldcorreo){
                    $msg .=' El correo introducido ya est� asociado a otra cuenta.';
                    break;
                }
            }
        }
    
    if(strlen($nombre)>20 || strlen($nombre) <= 0){
        $msg .= ' El nombre no puede ocupar m�s de veinte caracteres.';
    }
    if(strlen($contraseña)<=8 || strlen($contraseña)>15){
        $msg .= ' La contrase�a debe tener entre 8 y 15 caracteres';
    }
    if(!filter_var($newcorreo, FILTER_VALIDATE_EMAIL)){
        $msg .= ' El modelo del correo no es el adecuado';
    }
    return $msg;
}
// AÃ±adir un nuevo usuario (boolean)
function modeloUserAdd($user, $array){
    $_SESSION['tusuarios'][$user]=$array;
    mkdir("./app/dat/".$user, 0777);
    chmod("./app/dat/".$user, 0777);
    return true;
}

// Actualizar un nuevo usuario (boolean)
function modeloUserUpdate ($user, $array){

    $_SESSION['tusuarios'][$user]=$array;
    
    return true;
}

// Tabla de todos los usuarios para visualizar
function modeloUserGetAll (){
    // Genero lo datos para la vista que no muestra la contraseÃ±a ni los cÃ³digos de estado o plan
    // sino su traducciÃ³n a texto
    $tuservista=[];
    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario){
        $tuservista[$clave] = [$datosusuario[1],
                               $datosusuario[2],
                               PLANES[$datosusuario[3]],
                               ESTADOS[$datosusuario[4]]
                               ];
    }
    return $tuservista;
}
// Tabla de los ficheros del usuario
function modeloUserGetFicheros ($ruta){
    $tficherovista=[];
    if (is_dir($ruta)){
        
        $dir_cursor = @opendir($ruta) or die("ERROR al abrir fichero de usuarios");
        
        
        $entrada=readdir($dir_cursor);
        
        while($entrada !== false){
            
            if (!is_dir($ruta."/".$entrada)){
                
                $tamaño=filesize($ruta."/".$entrada);
                $tipo = filetype($ruta."/".$entrada);
                $fecha= date("F d Y", filectime($ruta."/".$entrada));
               
                $tficherovista[$entrada] = [$tipo,
                                            $fecha,
                                            $tamaño
                                            ];
            }
           
            $entrada=readdir($dir_cursor);
            
        }
        
        closedir($dir_cursor); // cerramos el directorio
        $_SESSION['tficheros']= $tficherovista;
        return $tficherovista;
    }
    
   
}

// Datos de un usuario para visualizar
function modeloUserGet ($user){
    $usuariodetalles =$_SESSION['tusuarios'][$user];
 
    return $usuariodetalles;
    
}

// Vuelca los datos al fichero
function modeloUserSave(){
    
    $datosjon = json_encode($_SESSION['tusuarios']);
    file_put_contents(FILEUSER, $datosjon) or die ("Error al escribir en el fichero.");
}

function modeloFileSave($nombreArchivo,$tmpArchivo,$numFicheros,$espacioOcupado,$tamañoFichero,$plan){
    $rutaDestino = "./app/dat/".$_SESSION['user'] . '/' . $nombreArchivo;
    $tamañoFichero = $tamañoFichero/1000;
    $archivoOk=false;
    $limiteFicheros =LIMITE_FICHEROS[$plan];
  
    $limiteEspacio =LIMITE_ESPACIO[$plan];

    if($numFicheros+1 <= $limiteFicheros){
        
        if($espacioOcupado+$tamañoFichero<=$limiteEspacio){
            
            if($tamañoFichero<TAMMAXIMOFILE){
               $archivoOk = move_uploaded_file($tmpArchivo,
            $rutaDestino);
            }
            
        }
        
        
    }
    
     
    return $archivoOk;
   
}


function modeloFileDel($fichero){
    $rutaDestino = "./app/dat/".$_SESSION['user'] . '/' . $fichero;
    unset($_SESSION['tficheros'][$fichero]);
    unlink($rutaDestino);
    return true;

}


function modeloFileRenombrar($fichero,$nuevoNombre){
    $rutaDestino = "./app/dat/".$_SESSION['user'] . '/' . $fichero;
    $nuevoNombre ="./app/dat/".$_SESSION['user'] . '/' . $nuevoNombre;  
    rename($rutaDestino,$nuevoNombre);
}

function modeloFileDescargar($fichero,$rutaArchivo){
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"".$fichero."\"");
    readfile($rutaArchivo);
}

function modeloDatos($dir){
    $explorar = scandir($dir);
    $numFicheros = count($explorar) - 2;
    return $numFicheros;
}

function modeloDirectorio($dir){
    $arrayficheros = [];
    $nfiles=0;
    $tamañototal = 0;
    $directorio = RUTA_FICHEROS."/".$_SESSION['user'];
    if (is_dir($directorio)){
        if ($dh = opendir($directorio)){
            while (($fichero = readdir($dh)) !== false){
                $rutayfichero = $directorio.'/'.$fichero;
                if ( is_file($rutayfichero)){
                  
                    $tamaño = filesize($rutayfichero);
                    $arrayficheros[$nfiles]['tamaño'] = $tamaño;
                    
                    
                    $tamañototal += $tamaño;
                    
                }
            }
            closedir($dh);
        }
  
    return $tamañototal;
}

}

