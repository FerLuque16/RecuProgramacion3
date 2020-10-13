<?php

class ManejadorArchivos
{
    static function GuardarArchivo($ruta, $dato)
    {
       $retorno=false;

       if(isset($ruta) && isset($dato))
        {
            $archivo=fopen($ruta,"a+");

            fwrite($archivo,$dato.PHP_EOL);

            $retorno = fclose($archivo);
        }
        else
        {
           echo "No se pudo guardar el archivo";
        }

        return $retorno;
       

    }

    static function LeerArchivo($ruta)
    {
        if(file_exists($ruta))
        {
            $archivo = fopen($ruta, "r");

            $listaDatos=array();

            while(!feof($archivo))
            {
                $linea=fgets($archivo);

                $datos = explode('*',$linea);

                if(count($datos)>1)
                {
                    array_push($listaDatos,$datos); 
                }   

            }

            fclose($archivo);
        }
        else
        {
            echo "El archivo no existe";

        }
        

        return $listaDatos;
    }

    static function Serializar($ruta,$listaObjetos)
    {
        $retorno=false;

        if(file_exists($ruta) && isset($listaObjetos))
        {
            $arrayObjetos = ManejadorArchivos::Deserializar($ruta);

            if($arrayObjetos != null)
            {
                foreach ($listaObjetos as $value) 
                {
                 array_push($arrayObjetos, $value);
                }

                $archivo = fopen($ruta,"w+");
        
                fwrite($archivo,serialize($arrayObjetos));
    
                $retorno=fclose($archivo);
            }
     
        }
        else
        {         
            $archivo = fopen($ruta,"w+");
        
            fwrite($archivo,serialize($listaObjetos));
        
            $retorno=fclose($archivo);           
        }
     
        return $retorno;
    }

    static function Deserializar($ruta)
    {       

        if(file_exists($ruta))
        {
            $archivo=fopen($ruta,"r");
    
            $size=filesize($ruta);
    
            $listaObjetos=unserialize(fread($archivo,$size));
           
            fclose($archivo);
        }
        else
        {
            echo "El archivo no existe";
        }

        return $listaObjetos;
    }


    static function GuardarJSON($ruta, $listaObjetos)
    {
        $retorno = false;

        $dirArchivos='./Archivos';
        
        //self::LeerJSON($ruta);

        if(file_exists($ruta))// || file_exists($dirArchivos))
        {
            $arrayObjetos=ManejadorArchivos::LeerJSON($ruta);

            if($arrayObjetos!=null)
            {

                foreach ($listaObjetos as $value) 
                {
                 array_push($arrayObjetos, $value);

                }

                    $archivo=fopen($ruta,"w");

                    fwrite($archivo,json_encode($arrayObjetos));

                    fclose($archivo);
            }
        
        }
        else
        {
           // mkdir()

            $path=explode('/',$ruta);

            mkdir('./'.$path[1]);

            //var_dump($path);

            $archivo=fopen($ruta,"w");
    
            fwrite($archivo,json_encode(($listaObjetos)));
        
            $retorno=fclose($archivo);
        }
       
        return $retorno;
    }
    
    static function LeerJSON($ruta)
    {
        $listaDatos=array();

        if(file_exists($ruta))
        {
            $archivo=fopen($ruta,"r");

            $listaDatos=json_decode(fgets($archivo));

            return $listaDatos;
        }
        //else
       // {
       //     echo "El archivo no existe";
       // }

        return $listaDatos;
    }
}

?>