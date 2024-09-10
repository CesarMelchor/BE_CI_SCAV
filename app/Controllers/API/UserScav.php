<?php 


namespace App\Controllers\API;

use App\Models\UserScavModel;
use CodeIgniter\RESTful\ResourceController;

class UserScav extends ResourceController{

    public function __construct(){
        $this->model = new UserScavModel();
    }

    public function getAll()
    {
        $tipo = $this->request->getGet('tipo');

       $usuarios = $this->model->where(['tipo' => $tipo])->findAll();

        return $this->respond($usuarios);
    }

    
    public function create(){
        try {

            $user = $this->request->getJSON();
            
            $user->password = password_hash($user->password, PASSWORD_BCRYPT);

            if ($this->model->insert($user)) {
                $user->id = $this->model->insertID();

                return $this->respondCreated($user);
            } else{
                return $this->failValidationErrors($this->model->validation->listErrors());
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }

    public function login(){
        try {

            $usuarioData = $this->request->getJSON();
            $usuario = $this->model->where("email = '".$usuarioData->email."' and tipo = '".$usuarioData->tipo."'")->first();
            if ($usuario == null) {
                return $this->respond(['mensaje' => 'Usuario no encontrado'], 203);
            }
            if (password_verify($usuarioData->password, $usuario['password'])) {
              return $this->respond($usuario);
            } else
            return $this->respond(['mensaje' => 'Contraseña incorrecta'], 203);
            
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }


    public function search(){

        $busqueda = $this->request->getGet('buscar');
        $tipo = $this->request->getGet('tipo');
        
        $result = $this->model->searching($busqueda,$tipo);

        if ($result == null) {
            return $this->respond(['mensaje' => 'Sin resultados'], 203);
        }else{
            return $this->respond($result, 200);
        }
    }

    

    public function update($id = null){
        try {
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $usuarioVerificado = $this->model->find($id);
                if ($usuarioVerificado == null) {
                    return $this->failNotFound("No se ha encontrado un usuario con el id : ".$id);
                }else{
                    
            
            $usuario = $this->request->getJSON();
                    
            if ($this->model->update($id,$usuario)) {
                $usuario->id = $id;
                return $this->respondUpdated($usuario);
            } else{
                return $this->failValidationErrors($this->model->validation->listErrors());
            }

                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }

    
    public function detail(){
        try {
            $id = $this->request->getGet('usuario');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $usuario = $this->model->find($id);
                if ($usuario == null) {
                    return $this->failNotFound("No se ha encontrado un usuario con el id : ".$id);
                }else{
                    return $this->respond($usuario);
                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }



    public function updatePass($id = null){
        try {
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $usuarioVerificado = $this->model->find($id);
                if ($usuarioVerificado == null) {
                    return $this->failNotFound("No se ha encontrado un usuario con el id : ".$id);
                }else{
                    
            
            $usuario = $this->request->getJSON();
            $usuario->password = password_hash($usuario->password, PASSWORD_BCRYPT);
                    
            if ($this->model->update($id,$usuario)) {
                $usuario->id = $id;
                return $this->respondUpdated($usuario);
            } else{
                return $this->failValidationErrors($this->model->validation->listErrors());
            }

                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }


    
}