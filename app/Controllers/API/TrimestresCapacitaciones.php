<?php 
namespace App\Controllers\API;

use App\Models\TrimestresCapacitacionesModel;
use CodeIgniter\RESTful\ResourceController;

class TrimestresCapacitaciones extends ResourceController{

    public function __construct(){
        $this->model = new TrimestresCapacitacionesModel;
    }

    public function getAll()
    {

       $data = $this->model->findAll();

        return $this->respond($data);
    }

    
}