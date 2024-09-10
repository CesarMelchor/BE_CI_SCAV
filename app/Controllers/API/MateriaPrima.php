<?php 
namespace App\Controllers\API;

use App\Models\MateriaPrimaModel;
use CodeIgniter\RESTful\ResourceController;

class MateriaPrima extends ResourceController{

    public function __construct(){
        $this->model = new MateriaPrimaModel();
    }

    public function getAll()
    {

       $materias = $this->model->findAll();

        return $this->respond($materias);
    }



    public function obtenerMateriasExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/materia_prima.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $documento = $reader->load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $totalregistros = $hojaActual->getHighestDataRow();
        $informacion = array();

        for ($i = 2; $i <= $totalregistros ; $i++) { 
          
                        $item = array(
                            strval($hojaActual->getCell('A1')) => strval($hojaActual->getCell('A'.$i)),
                            strval($hojaActual->getCell('B1')) => strval($hojaActual->getCell('B'.$i))
                        );
        
                        array_push($informacion,$item);

        }
        
        json_encode($informacion);

            
        return $this->respond($informacion, 200);

    }
    
    public function detail(){
        try {
            $id = $this->request->getGet('materia');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $materia = $this->model->find($id);
                if ($materia == null) {
                    return $this->failNotFound("No se ha encontrado un materia con el id : ".$id);
                }else{
                    return $this->respond($materia);
                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }



    public function update($id = null){
        try {
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $materiaVerificado = $this->model->find($id);
                if ($materiaVerificado == null) {
                    return $this->failNotFound("No se ha encontrado un materia con el id : ".$id);
                }else{
                    $materia = $this->request->getJSON();
                    
            if ($this->model->update($id,$materia)) {
                $materia->id = $id;
                return $this->respondUpdated($materia);
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