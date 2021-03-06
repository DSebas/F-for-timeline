<?php
include_once (__DIR__ . '/../../forms/usuariosForm.php');
include_once (__DIR__ . '/../models/write2txt.php');
include_once (__DIR__ . '/../models/fetchAllUser.php');
include_once (__DIR__ . '/../models/fetchUser.php');
include_once (__DIR__ . '/../models/createUser.php');
include_once (__DIR__ . '/../models/updateUser.php');
include_once (__DIR__ . '/../models/hydrateUser.php');
include_once (__DIR__ . '/../models/deleteUser.php');
include_once (__DIR__ . '/../../../../Core/src/Forms/model/filterForm.php');
include_once (__DIR__ . '/../../../../Core/src/Forms/model/validate.php');
include_once (__DIR__ . '/../../../../Core/src/Forms/model/drawForm.php'); 
include_once (__DIR__ . '/../../forms/usuariosdeleteForm.php');

switch ($request['action'])
{
    case 'insert':
        if ($_POST)
        {            
            $postfilter = filterForm($_POST, $usuarios_form);     
            $validate = validate($postfilter, $usuarios_form);                        
            if($validate['valid'])
            {
                // Guardar en un archivo separado por pipes                
                createUser($postfilter, $config);
                header('Location: /usuarios/select');                 
            }
        }     
        else 
        {
            // Formulario de insert                        
            include ('/../views/usuarios/insert.phtml');
        }
    break;
    case 'update':
        if($_POST)
        {
            // Filtrar
            // Validar             
            $postfilter = filterForm($_POST, $usuarios_form);
            $validate = validate($postfilter, $usuarios_form);
           
            // Si es valido
            if($validate['valid'])
            {
                    $usuarios = updateUser($postfilter, $config);             
            }
            // Ir a select
            header('Location: /usuarios/select');                
        }
        else 
        {
            $usuario = fetchUser($request['params']['id'], $config);  
            $usuario['id']=$request['params']['id'];
            $data_usuario = hydrateUser($usuario, $config);           
            // Dibujar el formulario con los datos del usuario
            include ('/../views/usuarios/update.phtml');            
        }
       
    break;
    case 'delete':
        echo "esto es el delete";
        if($_POST)
        {
            // Filtrar
            // Validar              
            $postfilter = filterForm($_POST, $usuariosdelete_form);
            $validate = validate($postfilter, $usuariosdelete_form);
            // Si es valido
            if($validate['valid'])
            {
                // Eliminar la linea ID
                if($postfilter['enviar']=='Si')
                {
                    deleteUser($postfilter['id'], $config);
                }     
                 // Ir a select
                 header('Location: /usuarios/select'); 
            }            
        }
        else
        {
            $usuario = fetchUser($request['params']['id'],$config);            
            if($config['repository']=='db')
            $data_usuario = array (
                'name'=>$usuario['name'],
                'id'=>$request['params']['id']
            );
            elseif($config['repository']=='txt')
            $data_usuario = array (
                'name'=>$usuario[1],
                'id'=>$request['params']['id']
            );
            // Dibujar el formulario de delete con los datos del usuario
            // Dibujar el formulario con los datos del usuario
            include ('/../views/usuarios/delete.phtml');
        }
    break;
    default:
    case 'select':
        $data = fetchAllUser($config);
        include ('/../views/usuarios/select.phtml');
    break;
}


