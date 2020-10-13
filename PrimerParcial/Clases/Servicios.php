<?php

require_once __DIR__.'./Archivos.php';
class Servicio extends ManejadorArchivos
{
    public $id;
    public $tipo;
    public $precio;
    public $demora;

    public function __construct($id,$tipo, $precio, $demora)
    {
        $this->id=$id;
        $this->tipo=$tipo;
        $this->precio=$precio;
        $this->demora=$demora;

    }

    public function __toString()
    {
        return $this->id.'*'.$this->tipo.'*'.$this->precio.'*'.$this->demora;//.$this->clave; 
    }

    public function __get($name)
    {
        echo $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name=$value;
    }

    static function PeticionServicio($metodo)
    {
        switch ($metodo) {
            case 'POST':
                $id=$_POST['id']??'';
                $tipo=$_POST['tipo']??'';
                $precio=$_POST['precio']??'';
                $demora=$_POST['demora']??'';

                $servicio=new Servicio($id, $tipo, $precio, $demora);

                //$Vehiculo = new Vehiculo($marca, $modelo, $patente, $precio);                

                $lista=array();

                //var_dump($Vehiculo);
               
                $listaServiciosJSON=parent::LeerJSON('./Archivos/tiposServicio.json');

                $listaServicios=Servicio::CrearServicioJSON($listaServiciosJSON);

                if(Servicio::ValidarServicio($listaServicios,$servicio))
                {
                    array_push($lista,$servicio);

                    parent::GuardarJSON('./Archivos/tiposServicio.json',$lista);

                   
                }
                else
                {
                    echo "Ya existe un servicio con ese id";
                }
                break;
            
            case 'GET':

                $path=$_SERVER['PATH_INFO'];

                //echo $path;

                $pathArray=explode('/',$path);

                var_dump($pathArray);

                if(count($pathArray)>1)
                {        
                    $dato=$pathArray[1];

                    $listaServiciosJSON=parent::LeerJSON('./Archivos/tiposServicio.json');

                    $listaServicios=Servicio::CrearServicioJSON($listaServiciosJSON);


                }
                else
                {
                    $listaServiciosJSON=parent::LeerJSON('./Archivos/tiposServicio.json');

                    $listaServicios=Servicio::CrearServicioJSON($listaServiciosJSON);

                    var_dump($listaServicios);

                    Servicio::MostrarTodosServicios($listaServicios);

                }


            break;
            default:
                # code...
                break;
        }

        
    }
    static function CrearServicioJSON($listaDatosServicio)
        {
            $listaServicios=array();

            //$listaUsuarios=parent::LeerJSON('./Archivos/users.json');
    
            foreach ($listaDatosServicio as $value) 
            {
                $servicio = new Servicio($value->id,$value->tipo, $value->precio, $value->demora);
    
                array_push($listaServicios, $servicio);
            }
    
             return $listaServicios;
        }

        static function ValidarServicio($listaServicios, $servicio)
        {
            $esValido=true;

            foreach ($listaServicios as $value) 
            {
                if($servicio->id == $value->id) 
                {
                    $esValido=false;
                }
            }

            return $esValido;

        }

        static function BuscarServicio($listaServicios, $id)
        {
            
            foreach ($listaServicios as $value) 
            {
               if($id == $value->id)
               {
                   
                    return $value;
                    break;
               }
            }
                      
        }

        static function MostrarServicioPorTipo($listaServicios,$tipo)
        {
            echo "Servicios de tipo $tipo";
            foreach ($listaServicios as $value) 
            {
               if($value->tipo==$tipo)
               {
                   Servicio::MostrarServicio($value);
               }
            }
        }

        static function MostrarServicio($servicio)
        {
            echo $servicio->precio.' '.$servicio->demora;
        }

        static function MostrarTodosServicios($listaServicios)
        {
            foreach ($listaServicios as $value) 
            {
                Servicio::MostrarServicio($value);
            }
        }

    



}

?>