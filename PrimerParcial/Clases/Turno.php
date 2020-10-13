<?php
require_once __DIR__.'./Archivos.php';
require_once __DIR__.'./Vehiculo.php';
require_once __DIR__.'./Servicios.php';


class Turno extends ManejadorArchivos
{
    public $patente;
    public $fecha;
    public $tipo;
    public $precio;
    public $marca;
    public $modelo;
    

    public function __construct($fecha,$patente,$marca,$modelo,$precio,$tipo)
    {
        
        $this->fecha=$fecha;
        $this->tipo=$tipo;
        $this->patente=$patente;
        $this->precio=$precio;
        $this->marca=$marca;
        $this->modelo=$modelo;
        

    }

    public function __toString()
    {
        return $this->fecha.'*'.$this->patente.'*'.$this->marca.'*'.$this->modelo.'*'.$this->precio.'*'.$this->tipo;//.$this->clave; 
    }

    public function __get($name)
    {
        echo $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name=$value;
    }

    static function PeticionesTurno($metodo)
    {
        switch ($metodo) 
        {
            case 'POST':

                $patente=$_POST['patente'];
                $fecha=$_POST['fecha'];
                $idServicio=$_POST['idServicio'];



                $listaVehiculosJSON=parent::LeerJSON('./Archivos/Vehiculos.json');               
                $listaServiciosJSON=parent::LeerJSON('./Archivos/Servicios.json');

                $listaVehiculos=Vehiculo::CrearVehiculoJSON($listaVehiculosJSON);
                $listaServicios=Servicio::CrearServicioJSON($listaServiciosJSON);




                $vehiculo=Vehiculo::BuscarVehiculo($listaVehiculos,$patente); 
                $servicio=Servicio::BuscarServicio($listaServicios,$idServicio);

                if(isset($vehiculo) && isset($servicio))                
                {
                    $turno=new Turno($fecha,$patente,$vehiculo->marca,$vehiculo->modelo,$servicio->precio,$servicio->tipo);

                    $lista=array();

                    //var_dump($Vehiculo);
                   
                    $listaTurnosJSON=parent::LeerJSON('./Archivos/Turnos.json');
    
                    $listaTurnos=Turno::CrearTurnoJSON($listaTurnosJSON);
    
                    
                        array_push($lista,$turno);
    
                        parent::GuardarJSON('./Archivos/Turnos.json',$lista);

                        echo "Guardado con exito";
    
                }
                else
                {
                    echo "No se encontro el vehiculo o el servicio";
                }

               

                
                break;
            
            
            case 'GET':


                
                break;
            default:
                # code...
                break;
        }
    }

    static function CrearturnoJSON($listaDatosTurno)
    {
        $listaTurnos=array();

        //$listaUsuarios=parent::LeerJSON('./Archivos/users.json');

        foreach ($listaDatosTurno as $value) 
        {
            $turno = new Turno($value->fecha,$value->patente, $value->marca, $value->modelo, $value->precio, $value->tipo);
            //$turno=new Turno($fecha,$patente,$vehiculo->marca,$vehiculo->modelo,$servicio->precio,$servicio->tipo);

            array_push($listaTurnos, $turno);
        }

         return $listaTurnos;
    }



}
?>