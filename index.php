<?php
session_start();
include_once 'app/configDB.php';

include_once 'app/controlerFile.php';
include_once 'app/controlerUser.php';
include_once 'app/modeloUser.php';
include_once 'app/modeloUserDB.php';
// Inicializo el modelo
modeloUserDB::init();
modeloUserInit();

// Enrutamiento
// Relación entre peticiones y función que la va a tratar
// Versión sin POO no manejo de Clases ni objetos
$rutasUser = [
    "Inicio"           => "ctlUserInicio",
    "Alta"             => "ctlUserAlta",
    "Detalles"         => "ctlUserDetalles",
    "Modificar"        => "ctlUserModificar",
    "Borrar"           => "ctlUserBorrar",
    "Cerrar"           => "ctlUserCerrar",
    "VerUsuarios"      => "ctlUserVerUsuarios",
    "Registro"         => "ctUserRegistro",
    "VerFicheros"      => "ctFileVerFicheros",
    "SubirFichero"     => "ctFileSubirFicheros",
    "BorrarFichero"    => "ctFileBorrarFicheros",
    "Renombrar"        => "ctFileRenombrarFicheros",
    "Descargar"        => "ctFileDescargar",
    "ModificarUsuario" => "ctUserModificarUsuario",
    "Compartir"        => "ctFileCompartir",
    "DescargaDirecta"  => "ctFileDescargaDirecta"
];
// Si no hay usuario a Inicio
if (!isset($_SESSION['user'])){
    $procRuta = "ctlUserInicio";
    
    if (isset($_GET['orden']) && $_GET['orden'] == 'Registro'){
        $procRuta = $rutasUser[$_GET['orden']];
    }
} else {
    if ( $_SESSION['modo'] == GESTIONUSUARIOS){
        if (isset($_GET['orden'])){
            // La orden tiene una funcion asociada 
            if ( isset ($rutasUser[$_GET['orden']]) ){
                $procRuta =  $rutasUser[$_GET['orden']];
            }
            else {
                // Error no existe función para la ruta
                header('Status: 404 Not Found');
                echo '<html><body><h1>Error 404: No existe la ruta <i>' .
                    $_GET['ctl'] .
                    '</p></body></html>';
                    exit;
            }
        }
        else {
            $procRuta = "ctlUserVerUsuarios";
        }
    }

    else {
        if (isset($_GET['orden'])){
            // La orden tiene una funcion asociada
            if ( isset ($rutasUser[$_GET['orden']]) ){
                $procRuta =  $rutasUser[$_GET['orden']];
            }
            else {
                // Error no existe función para la ruta
                header('Status: 404 Not Found');
                echo '<html><body><h1>Error 404: No existe la ruta <i>' .
                    $_GET['ctl'] .
                    '</p></body></html>';
                    exit;
            }
        } else {
            $procRuta = "ctFileVerFicheros";
        }
    }
}

// Llamo a la función seleccionada
$procRuta();
?>