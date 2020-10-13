<?php

require_once './Clases/Archivos.php';
require_once './Clases/Usuario.php';
require_once './Clases/Servicios.php';
require_once './Clases/Turno.php';
//require_once './Clases/Profesor.php';
//require_once './Clases/Materia.php';
//require_once './Clases/Asignacion.php';
require_once './vendor/Autoload.php';
require_once './Clases/Vehiculo.php';

use \Firebase\JWT\JWT;

//$key = "example_key";

//$payload = array(
    //"email" => "http://example.org",
    //"clave" => "http://example.com",    
//);

/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
//$jwt = JWT::encode($payload, $key);

//$token=$_SERVER['HTTP_TOKEN'];
//$decoded = JWT::decode($token, $key, array('HS256'));
//print_r($decoded);  

function ObtenerToken()
{
    try 
    {
        $headers = getallheaders();
        return $headers['token'];
    }
    catch (\Throwable $th) 
    {
        echo 'Excepcion:'. $th->getMessage();
    }
    
}

//print_r($jwt);    

    $metodo = $_SERVER['REQUEST_METHOD'];
    $path=$_SERVER['PATH_INFO'];

    $pathRetiro=explode('/',$path);

    //'/Vehiculo/aaa123'

    //$pathNuevo[1]


    //var_dump($pathRetiro);

    if(count($pathRetiro)>1)
    {
        
        if($pathRetiro[1]=='patente')
        {
            $path='/patente';
        }
        elseif($pathRetiro[1]=='modelo')
        {
            $path='/modelo';
        }
        elseif($pathRetiro[1]=='marca')
        {
            $path='/marca';
        }
        elseif($pathRetiro[1]=='stats')
        {
            $path='/stats';
        }
    }

    //var_dump($path);

    


    switch ($path) {
        case '/registro':

            Usuario::PeticionesUsuario($metodo);            
            break;
        
        
        case '/login':
            Usuario::LoginUsuario($metodo);
            break;

         case '/vehiculo':

            if(Usuario::PermitirPermisoUser(ObtenerToken()))
            {
                Vehiculo::PeticionesVehiculo($metodo);
            }
            else
            {
                echo "Permiso invalido. Solo users";
            }
            break;
    
        case '/patente':
            if(Usuario::PermitirPermisoUser(ObtenerToken()))
            {
                
                Vehiculo::PeticionesVehiculo($metodo);
            }
            else
            {
                echo "Permiso invalido";
            }
            
            break;
            
        case '/marca':
            if(Usuario::PermitirPermisoUser(ObtenerToken()))
            {
                Vehiculo::PeticionesVehiculo($metodo);
            }
            else
            {
                echo "Permiso invalido";
            }
            

            break;

            case '/modelo':
                if(Usuario::PermitirPermisoUser(ObtenerToken()))
                {
                    Vehiculo::PeticionesVehiculo($metodo);
                }
                else
                {
                    echo "Permiso invalido. D";
                }
                
    
                break;

        case '/servicio':

            Servicio::PeticionServicio($metodo);

            break;

        case '/turno':

            Turno::PeticionesTurno($metodo);

        break;
        case '/stats':

            if(Usuario::PermitirPermisoAdmin(ObtenerToken()))
            {
                Servicio::PeticionServicio($metodo);    
            }
            else
            {
                echo "Permiso invalido";
            }
                    
            
        break;
    
        default:
               
            break;
                
        
        
    }


?>



