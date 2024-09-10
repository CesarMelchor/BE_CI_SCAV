<?php 
namespace App\Controllers\API;

use App\Models\ComprobacionesCapacitacionesModel;
use CodeIgniter\RESTful\ResourceController;

class ComprobacionesCapacitaciones extends ResourceController{

    public function __construct(){
        $this->model = new ComprobacionesCapacitacionesModel;
    }

    public function getAll()
    {

       $data = $this->model->findAllRegisters();

        return $this->respond($data);
    }

    
    public function create(){
        try {

            $register = $this->request->getJSON();
              
                $this->model->insert($register);
                return $this->respond(['mensaje' => 'exito'],200);    
            
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }


    public function search(){

        $busqueda = $this->request->getGet('buscar');
        
        $result = $this->model->searching($busqueda);

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

                $rowVerified = $this->model->find($id);
                if ($rowVerified == null) {
                    return $this->failNotFound("No se ha encontrado un registro con el id : ".$id);
                } else {
                    
            $data = $this->request->getJSON();
                    
            if ($this->model->update($id,$data)) {
                
                return $this->respond(['mensaje' => 'exito'],200);
                
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