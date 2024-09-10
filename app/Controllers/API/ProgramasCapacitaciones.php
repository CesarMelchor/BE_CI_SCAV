<?php 
namespace App\Controllers\API;

use App\Models\ProgramasCapacitacionesModel;
use CodeIgniter\RESTful\ResourceController;

class ProgramasCapacitaciones extends ResourceController{

    public function __construct(){
        $this->model = new ProgramasCapacitacionesModel;
    }

    public function getAll()
    {

       $data = $this->model->findAll();

        return $this->respond($data);
    }

 
}