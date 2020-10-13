<?php

require_once __DIR__.'./Archivos.php';
class Vehiculo extends ManejadorArchivos
{
    public $marca;
    public $modelo;
    public $patente;
    public $precio;

    public function __construct($marca, $modelo, $patente, $precio)
    {
        $this->patente=$patente;
        $this->modelo=$modelo;
        $this->marca=$marca;
        $this->precio=$precio;
    }
    
    public function __toString()
     {
        return $this->patente.'*'.$this->modelo.'*'.$this->marca.'*'.$this->precio;//.$this->clave; 
     }

     public function __get($name)
     {
         echo $this->$name;
     }

     public function __set($name, $value)
     {
         $this->$name=$value;
     }


     static function PeticionesVehiculo($metodo)
     {
         switch($metodo)
         {
             case 'POST':

                $patente=$_POST['patente']??'';
                $marca=$_POST['marca']??'';
                $modelo=$_POST['modelo']??'';
                $precio=$_POST['precio']??'';
                //$horaIngreso=date("d/m/y H:i a");

                //echo $patente;
                //$email=Usuario::ObtenerMailToken(ObtenerToken());

                //echo $email.PHP_EOL;
                //echo $horaIngreso;
                
                $Vehiculo = new Vehiculo($marca, $modelo, $patente, $precio);                

                $lista=array();

                //var_dump($Vehiculo);
               
                $listaVehiculosJSON=parent::LeerJSON('./Archivos/Vehiculos.json');

                $listaVehiculos=Vehiculo::CrearVehiculoJSON($listaVehiculosJSON);

                if(Vehiculo::ValidarVehiculo($listaVehiculos,$Vehiculo))
                {
                    array_push($lista,$Vehiculo);

                    parent::GuardarJSON('./Archivos/Vehiculos.json',$lista);

                    //move_uploaded_file($origen,$destino);
                }
                else
                {
                    echo "Ya existe un vehiculo con esa patente";
                }

               

                //parent::Serializar('./Archivos/users-srl.txt',$lista);

                //return $usuario;

                break;

             case 'GET':


                $path=$_SERVER['PATH_INFO'];

                //echo $path;

                $pathArray=explode('/',$path);

                if(count($pathArray)>1)
                {        
                    $dato=$pathArray[1];   
                    //echo $dato;        
                    switch ($dato) {
                        case 'patente':
    
                            $dato=$pathArray[2];

                            //echo $patente;
    
                            $listaDatosVehiculos=parent::LeerJSON('./Archivos/Vehiculos.json');
    
                            $listaVehiculos = Vehiculo::CrearVehiculoJSON($listaDatosVehiculos);
                            //var_dump($listaVehiculos);
    
                            //echo "hola";
    
                            $contador=Vehiculo::BuscarVehiculoPorPatente($listaVehiculos,$dato);
                            if($contador==0)
                            {
                                echo "No existe $dato";
                            }
                            
                            
    
 
                            break;
                        
                        case 'marca':
                            $dato=$pathArray[2];

                            //echo $patente;
    
                            $listaDatosVehiculos=parent::LeerJSON('./Archivos/Vehiculos.json');
    
                            $listaVehiculos = Vehiculo::CrearVehiculoJSON($listaDatosVehiculos);
                            //var_dump($listaVehiculos);
    
                            //echo "hola";
    
                            $contador=Vehiculo::BuscarVehiculoPorMarca($listaVehiculos,$dato);
                            if($contador==0)
                            {
                                echo "No existe $dato";
                            }
                            break;
                            
                            case 'modelo':

                                $dato=$pathArray[2];

                                //echo $patente;
        
                                $listaDatosVehiculos=parent::LeerJSON('./Archivos/Vehiculos.json');
        
                                $listaVehiculos = Vehiculo::CrearVehiculoJSON($listaDatosVehiculos);
                                //var_dump($listaVehiculos);
        
                                //echo "hola";
        
                                $contador=Vehiculo::BuscarVehiculoPorModelo($listaVehiculos,$dato);
                                if($contador==0)
                                {
                                    echo "No existe $dato";
                                }
                                
                                break;
        
                        default:
                            // echo "nadda";
                            break;
                    }
                }
                else
                {
                    $listaDatosVehiculos=parent::LeerJSON('./Archivos/Vehiculos.json');
                    $listaVehiculos = Vehiculo::CrearVehiculoJSON($listaDatosVehiculos);
                    Vehiculo::MostrarTodos($listaVehiculos);
                }

               
                if($pathArray[1]=='retiro')
                    {
                        $path='/patenteRetiro';
                    }
                    elseif($pathArray[1]=='ingreso')
                    {
                        $path='/patenteIngreso';
                    }
                
                //$patente=$pathRetiro[1];
                
                

                break;
               

                
         }
     }
    

        static function BuscarVehiculoPorMarca($listaVehiculos, $dato)
        {
            $contador=0;
            foreach ($listaVehiculos as $value) 
            {
               if($dato==$value->marca)
               {
                $contador++;
                Vehiculo::MostrarVehiculo($value);
                                
               }


            }
            return $contador;
        }
        static function BuscarVehiculoPorModelo($listaVehiculos,$dato)
        {
            $contador=0;

            foreach ($listaVehiculos as $value) 
            {
               if($dato == $value->modelo)
               {
                $contador++;   
                Vehiculo::MostrarVehiculo($value);
                    
               }
              
            }

            return $contador;
        }
          /*  foreach ($listaVehiculos as $value) 
            {
               if($dato==$value->modelo)
               {
                   
                Vehiculo::MostrarVehiculo($value);
                    
               }

               
            }*/

            //echo "No se encontró el Vehiculo con esa patente";

            /*$esValido=true;

            foreach ($listaUsuarios as $value) 
            {
                if($usuario->email == $value->email) 
                {
                    $esValido=false;
                }
            }

            return $esValido;*/


        //}

        static function ValidarVehiculo($listaVehiculos, $vehiculo)
        {
            $esValido=true;

            foreach ($listaVehiculos as $value) 
            {
                if($vehiculo->patente == $value->patente) 
                {
                    $esValido=false;
                }
            }

            return $esValido;

        }

     static function CrearVehiculoJSON($listaDatosVehiculo)
     {
        $listaVehiculos=array();

        //$listaUsuarios=parent::LeerJSON('./Archivos/users.json');

        foreach ($listaDatosVehiculo as $value) 
        {
            $usuario = new Vehiculo($value->marca,$value->modelo, $value->patente, $value->precio);

            array_push($listaVehiculos, $usuario);
        }

         return $listaVehiculos;
     }

     static function BuscarVehiculoPorPatente($listaVehiculos, $patente)
        {
            $contador=0;
            foreach ($listaVehiculos as $value) 
            {
               if($patente == $value->patente)
               {
                    $contador++;
                    Vehiculo::MostrarVehiculo($value);
               }
            }

            return $contador;

            //echo "No se encontró el Vehiculo con esa patente";

            /*$esValido=true;

            foreach ($listaUsuarios as $value) 
            {
                if($usuario->email == $value->email) 
                {
                    $esValido=false;
                }
            }

            return $esValido;*/


        }

        static function BuscarVehiculo($listaVehiculos, $patente)
        {
           // $contador=0;
            foreach ($listaVehiculos as $value) 
            {
               if($patente == $value->patente)
               {
                   
                    return $value;
                    break;
               }
            }

                      
        }

    /*static function MostrarVehiculoOrdenado($Vehiculo)
        {
            echo 'Patente:'.$Vehiculo->patente.'||'.'Fecha de Ingreso:'.$Vehiculo->modelo.PHP_EOL;
        }*/
       

    static function MostrarVehiculo($Vehiculo)
        {
            echo 'Patente: '.$Vehiculo->patente.'||'.'Modelo:'.$Vehiculo->modelo.'||'.'Marca: '.$Vehiculo->marca.'||'.'Precio: '.$Vehiculo->precio.PHP_EOL;
         }

    static function MostrarTodos($listaVehiculos)
        {
            foreach ($listaVehiculos as $value) 
            {
                Vehiculo::MostrarVehiculo($value);
            }
        }
}

?>