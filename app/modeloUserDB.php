<?php

include_once 'configDB.php';
//include_once 'util.php';


class ModeloUserDB {
    
    private static $dbh = null;
    private static $consulta_user = "Select * from Usuarios where id = ?";
    private static $consulta_email = "Select email from Usuarios where email= ? and not (id = ?)";
    private static $borrar_user = "DELETE FROM Usuarios WHERE id = ?";
    private static $alta_user = "INSERT INTO Usuarios VALUES(?,?,?,?,?,?)";
    private static $update_user = "UPDATE Usuarios SET clave =?, nombre=?, email=?, plan=?, estado=? WHERE id=?";
    static private $ivcod = "1CpHOm+2qHjdFvNV4VJuvg==";
    static private $metodo = 'aes-256-cbc';
    static private $clave  = 'El módulo de Desarrollo Web en entorno servidor es lo más';
    public static function init(){
        
        if (self::$dbh == null){
            try {
                // Cambiar  los valores de las constantes en config.php
                $dsn = "mysql:host=". DBSERVER  .";dbname=". DBNAME .";charset=utf8";
                self::$dbh = new PDO($dsn, DBUSER, DBPASSWORD);
                // Si se produce un error se genera una excepción;
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e){
                echo "Error de conexión ".$e->getMessage();
                exit();
            }
            
        }
        
    }
    function modeloUserEncriptar($clave){
        
        return password_hash($clave, PASSWORD_DEFAULT, ['cost' => 10]);
        
    }

    public static function encripta($texto){
        $iv =  base64_decode (self::$ivcod);
        return openssl_encrypt($texto,self::$metodo,self::$clave,false,$iv);
    }
    

    
    public static function desencripta($texto){
        $iv =  base64_decode (self::$ivcod);
        return openssl_decrypt($texto,self::$metodo,self::$clave,false,$iv);
    }
    
    // Comprueba usuario y contraseña son correctos (boolean)
    public static function OkUser($user,$clave){//HECHO
        $solucion = false;
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$user);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $fila = $stmt->fetch();
            $clavecifrada = $fila['clave'];
            if ( password_verify($clave,$clavecifrada)){
                $solucion = true;
            }
        }
        return $solucion;
    }
    
    // Comprueba si ya existe un usuario con ese identificar
    public static function existeID(String $user):bool{ //HECHO
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$user);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
           return true;
        } else {
            return false;
        }
    }
    
    //Comprueba si existe en email en la BD
    public static function existeEmail(String $email, $user){ //HECHO
        $stmt = self::$dbh->prepare(self::$consulta_email);
        $stmt->bindValue(1,$email);
        $stmt->bindValue(2,$user);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
            
            return true;
        } else {
            return false;
        }
    }
    
    
    /*
     * Chequea si hay error en el datos antes de guardarlos
     */
    public static function errorValoresAlta ($user,$nuevo){
        $clave1 = $nuevo[0];
        $clave2 = $nuevo[5];
        $email = $nuevo[2];
        if ( self::existeID($user))                         return TMENSAJES['USREXIST'];
        if ( preg_match("/^[a-zA-Z0-9]+$/", $user) == 0)    return TMENSAJES['USRERROR'];
        if ( $clave1 != $clave2 )                           return TMENSAJES['PASSDIST'];
        if (! self::EsClaveSegura($clave1) )                return TMENSAJES['PASSEASY'];
        if ( !filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR'];
        if ( self::existeEmail($email,$user))                     return TMENSAJES['MAILREPE'];
        return false;
    }
    
    public static function errorValoresModificar($user, $modificado,$oldcorreo){ //HECHO

        $email=$modificado[2];

        if ( !filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR'];
        // SI se cambia el email
        $emailantiguo = self::getEmail($email,$user);
        if ( $email != $emailantiguo && self::existeEmail($email,$user))   return TMENSAJES['MAILREPE'];
        return false;
    }
    
    /*
     * Comprueba que la contraseña es segura
     */
    public static function getEmail($email,$user){
        $stmt = self::$dbh->prepare(self::$consulta_email);
        $stmt->bindValue(1,$email);
        $stmt->bindValue(2,$user);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $fila = $stmt->fetch();
            $email = $fila['email'];
            return $email;
        } 
    }
    
    public static function hayMayusculas($clave){
        $resultado = false;
        for ($i=0; $i < strlen($clave); $i++){
            if(ctype_upper($clave[$i])){
                $resultado = true;
                break;
            }
        }
        return $resultado;
    }
    
    public static function hayMinusculas($clave){
        $resultado = false;
        for ($i=0; $i < strlen($clave); $i++){
            if(ctype_lower($clave[$i])){
                $resultado = true;
                break;
            }
        }
        return $resultado;
    }
    
    public static function hayDigito($clave){
        $resultado = false;
        for ($i=0; $i < strlen($clave); $i++){
            if(is_numeric($clave[$i])){
                $resultado = true;
                break;
            }
        }
        return $resultado;
    }
    
    public static function EsClaveSegura (String $clave):bool { //HECHO
        if ( empty($clave))         return false;
        if (  strlen($clave) < 8 )  return false;
        if ( !self::hayMayusculas($clave) || !self::hayMinusculas($clave)) return false;
        if ( !self::hayDigito($clave))         return false;
        if ( !ctype_alnum($clave)) return false;
        
        return true;
    }

    
    
    // Devuelve el plan de usuario (String)
    public static function ObtenerTipo($user):string{ //HECHO
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$user);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $fila = $stmt->fetch();
            $numeroplan = $fila['plan'];
            
        }
        
        return PLANES[$numeroplan]; /******* PENDIENTE ***********/
    }
    
    public static function ObtenerEstado($user):string{ //HECHO
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$user);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $fila = $stmt->fetch();
            $estado = $fila['estado'];
            
        }
        
        return ESTADOS[$estado]; /******* PENDIENTE ***********/
    }
    
    // Borrar un usuario (boolean)
    public static function UserDel($userid){
        $stmt = self::$dbh->prepare(self::$borrar_user);
        $stmt->bindValue(1,$userid);
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
        
    }
    // Añadir un nuevo usuario (boolean)
    public static function UserAdd($userid, $userdat):bool{
        $stmt = self::$dbh->prepare(self::$alta_user);
        $stmt->bindValue(1,$userid);
        $stmt->bindValue(2,$userdat[0]);
        $stmt->bindValue(3,$userdat[1]);
        $stmt->bindValue(4,$userdat[2]);
        $stmt->bindValue(5,$userdat[3]);
        $stmt->bindValue(6,$userdat[4]);
        if($stmt->execute()){
            mkdir("./app/dat/".$userid, 0777);
            chmod("./app/dat/".$userid, 0777);
            return true;
        } else  {
            return false;
        }
    }
    
    // Actualizar un nuevo usuario (boolean)
    public static function UserUpdate ($userid, $userdat){
        $stmt = self::$dbh->prepare(self::$update_user);
        $stmt->bindValue(1,$userdat[0]);
        $stmt->bindValue(2,$userdat[1]);
        $stmt->bindValue(3,$userdat[2]);
        $stmt->bindValue(4,$userdat[3]);
        $stmt->bindValue(5,$userdat[4]);
        $stmt->bindValue(6,$userid);
        if($stmt->execute()){
            return true;
        } else  {
            return false;
        }
     
    }
    
    
    // Tabla de todos los usuarios para visualizar
    public static function GetAll ():array{
        // Genero los datos para la vista que no muestra la contraseña ni los códigos de estado o plan
        // sino su traducción a texto  PLANES[$fila['plan']],
        $stmt = self::$dbh->query("select * from Usuarios");
        
        $tUserVista = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ( $fila = $stmt->fetch()){
            $datosuser = [
                $fila['nombre'],
                $fila['email'],
                PLANES[$fila['plan']],
                ESTADOS[$fila['estado']]
            ];
            $tUserVista[$fila['id']] = $datosuser;
        }
        return $tUserVista;
    }
    
    
    
    // Datos de un usuario para visualizar
    public static function UserGet ($userid){ //RESUELTO, CREO QUE BIEN
        $solucion= [];
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$userid);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $fila = $stmt->fetch();
            $solucion = [ $fila['id'],
                $fila['clave'],
                $fila['nombre'],
                $fila['email'],
                $fila['plan']
            ];
        }
        
        return $solucion;
    }
    
    public static function closeDB(){
        self::$dbh = null;
    }
    
} // class