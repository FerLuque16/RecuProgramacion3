<?php

use Firebase\JWT\JWT;

require_once __DIR__.'./Archivos.php';

 class Usuario extends ManejadorArchivos
 {
     public $email;
     public $clave;
     public $tipo;
     public $foto;

     public function __construct($email, $clave, $tipo, $foto)
     {
         $this->email=$email;
         $this->clave=$clave;
         $this->tipo=$tipo;
         $this->foto=$foto;
     }

     public function __toString()
     {
        return $this->email.'*'.$this->clave; 
     }

     public function __get($name)
     {
         echo $this->$name;
     }

     public function __set($name, $value)
     {
         $this->$name=$value;
     }

     static function PeticionesUsuario($metodo)
     {
         $path=$_SERVER['PATH_INFO'];

         switch($metodo)
         {
             case 'POST':

                switch ($path) 
                {
                    case '/registro':
                        
                        $email=$_POST['email']??'';
                        $clave=$_POST['password']??'';
                        $tipo=$_POST['tipo']??'';
                        $foto=$_FILES['imagen']["name"];

                        $origen = $_FILES['imagen']["tmp_name"];

                        $destino="./Imagenes/".$_FILES['imagen']["name"];


                        $usuario = new Usuario($email, $clave, $tipo, $foto);

                        //parent::GuardarArchivo('./Archivos/listaUsuarios.txt', $usuario);

                        $lista=array();

                    

                        $listaUsuariosJSON=parent::LeerJSON('./Archivos/users.json');

                        $listaUsuarios=Usuario::CrearUsuarioJSON($listaUsuariosJSON);

                        if(Usuario::ValidarUsuario($listaUsuarios,$usuario))
                        {
                            array_push($lista,$usuario);

                            parent::GuardarJSON('./Archivos/users.json',$lista);

                            //move_uploaded_file($origen,$destino);
                            Usuario::GuardarImagenes($origen, $destino);
                        }
                        else
                        {
                            echo "Ya existe un usuario con ese email";
                        }

                        break;

                    case '/users':
                        $email=Usuario::ObtenerMailToken(ObtenerToken());

                        $listaUsuariosJSON=parent::LeerJSON('./Archivos/users.json');

                        $listaUsuarios=Usuario::CrearUsuarioJSON($listaUsuariosJSON);

                        $foto=Usuario::BuscarImagenUsuario($listaUsuarios,$email);

                        rename('./Imagenes/'.$foto,'./Backups/'.$foto);
                        //Usuario::GuardarImagenes('./Imagenes/'.$foto,'./Backups');

                        echo $foto;


                        break;
                    
                    default:
                        # code...
                        break;
                }

                
               

                //parent::Serializar('./Archivos/users-srl.txt',$lista);

                //return $usuario;

                break;

             case 'GET':

                $listaDatosUsuarios=parent::LeerJSON('./Archivos/users.json');

                $listaUsuarios = Usuario::CrearUsuarioJSON($listaDatosUsuarios);

                /*foreach ($listaUsuarios as $value) 
                {
                    echo $value;
                }*/
                //var_dump($listaUsuarios);

                Usuario::MostrarTodos($listaUsuarios);

                var_dump($listaUsuarios);


                break;
         }
     }
     
     static function BuscarImagenUsuario($listaUsuarios,$email)
     {
         foreach ($listaUsuarios as $value) 
         {
            if($value->email == $email)
            {
                return $value->foto;
                break;

            }
         }

     }

     static function CrearUsuario($listaDatosUsuario)
     {
        $listaUsuarios=array();

        foreach ($listaDatosUsuario as $key => $value) 
        {
            $usuario = new Usuario($value[0],$value[1],$value[2],$value[3]);

            array_push($listaUsuarios, $usuario);
        }

         return $listaUsuarios;
     }

     static function CrearUsuarioJSON($listaDatosUsuario)
     {
        $listaUsuarios=array();

        //$listaUsuarios=parent::LeerJSON('./Archivos/users.json');

        foreach ($listaDatosUsuario as $value) 
        {
            $usuario = new Usuario($value->email,$value->clave, $value->tipo, $value->foto);

            array_push($listaUsuarios, $usuario);
        }

         return $listaUsuarios;
     }

     static function MostrarUsuario($usuario)
     {
        echo 'Email:'.$usuario->email.'||'.'Clave:'.$usuario->clave.PHP_EOL;
     }

     static function MostrarTodos($listaUsuarios)
     {
        foreach ($listaUsuarios as $value) 
        {
            Usuario::MostrarUsuario($value);
        }
     }

     static function BuscarNombreImagen($email)
     {
        $listaUsuariosJSON=parent::LeerJSON('./Archivos/users.json');

        $listaUsuarios=Usuario::CrearUsuarioJSON($listaUsuariosJSON);

        foreach ($listaUsuarios as $value) 
        {
            if($value->emailUsuairio==$email)
            {
                return $value->foto;
                break;
            }
        }
     }

     static function BuscarUsuario($email, $clave)
     {
        $listaUsuariosJSON=parent::LeerJSON('./Archivos/users.json');

        $listaUsuarios=Usuario::CrearUsuarioJSON($listaUsuariosJSON);

        //var_dump($listaUsuarios);
    
        $payload=array();

        $esCorrecto=false;

        if(count($listaUsuarios)>0)
        {
           // var_dump( $email);
             //   var_dump($clave);
            foreach ($listaUsuarios as $value) 
            {
               /* echo "fercito1";

                echo $value->email;
                echo '<br>';
                echo $value->clave;
                echo "<br>";
                echo $email;
                echo "<br>";
                echo $clave;
                echo "<br>";*/
                
                //var_dump( $value->email);
                //var_dump($value->clave);
                //echo $value->clave;

                if($email == $value->email && $clave == $value->clave)
                {
                    //echo "fercito";

                    $payload=array(
                    
                        "email"=>$email,
                        "tipo"=>$value->tipo
                        //"clave"=>$value->clave,
                        //"foto" =>$value->foto
                        

                    );

                    //echo "fer";
                    $esCorrecto=JWT::encode($payload,'primerparcial');
                    break;
                }
            }
        }
        else
        {
            echo "Debe cargar usuarios primero";
        }


        return $esCorrecto;
        
     }

     public static function LoginUsuario($metodo)
     {

        if($metodo == 'POST')
        {
           
            $email=$_POST['email']??'';
            $clave=$_POST['password']??'';
           
            

            $usuarioValido=Usuario::BuscarUsuario($email,$clave);

            if($usuarioValido != false)
            {
                echo $usuarioValido;
            }
            else
            {
                echo "Clave o mail invalidos";
            }

        }
        else
        {
            echo "Metodo incorrecto";
        }



     }

     public static function PermitirPermiso($token)
    {
        $retorno = false;
        try 
        {
            $decodeado=JWT::decode($token,"pro3-parcial",array('HS256'));

            if(isset($decodeado))
            {
                $retorno = true;
            }
            
            
        } catch (\Throwable $th) 
        {
            echo 'Excepcion:'.$th->getMessage();
        }
        return $retorno;
    }

    static function ValidarUsuario($listaUsuarios, $usuario)
        {
            $esValido=true;

            foreach ($listaUsuarios as $value) 
            {
                if($usuario->email == $value->email) 
                {
                    $esValido=false;
                }
            }

            return $esValido;

        }

        static function GuardarImagenes($origen,$destino)
        {
            $path=explode('/',$destino);
            $dirImagenes='./Imagenes';
            echo $origen;
            //var_dump($path);

            if(file_exists($destino) && file_exists($dirImagenes))
            {
                move_uploaded_file($origen,$destino);
            }
            else
            {
                mkdir('./'.$path[1]);
                move_uploaded_file($origen,$destino);
            }
        }

        //static function 


        public static function PermitirPermisoAdmin($token)
        {
            $retorno = false;
            try 
            {
                $payload = JWT::decode($token, "primerparcial", array('HS256'));
                //var_dump($payload);
                foreach ($payload as $value) 
                {
                    if ($value == 'admin') 
                    {

                        $retorno = true;
                    }
                }
            }catch (\Throwable $th)
                {
                    echo 'Excepcion:' . $th->getMessage();
                }
                return $retorno;
    }

    public static function PermitirPermisoUser($token)
    {
        $retorno = false;
        try 
        {
            $payload = JWT::decode($token, "primerparcial", array('HS256'));
            //var_dump($payload);
            foreach ($payload as $value) {
                if ($value == 'user') {

                    $retorno = true;
                }
            }
        } catch (\Throwable $th) {
            echo 'Error:'; //. $th->getMessage();
        }
        return $retorno;
    }

    public static function ObtenerMailToken($token)
    {
        //$retorno = false;
        try {
            $payload = JWT::decode($token, "primerparcial", array('HS256'));
            //var_dump($payload);
            foreach ($payload as $key => $value) 
            {
                if ($key == "email") 
                {
                    //echo $value;
                    return $value;
                }
            }

           // return $payload[0];
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        //return $retorno;
    }
 }







