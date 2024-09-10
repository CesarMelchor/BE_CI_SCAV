<?php 
namespace App\Controllers\API;

use App\Models\EtniaModel;
use CodeIgniter\RESTful\ResourceController;

class Etnia extends ResourceController{

    public function __construct(){
        $this->model = new EtniaModel();
    }

    public function getAll()
    {

       $etnias = $this->model->findAll();

        return $this->respond($etnias);
    }



    public function obtenerEtniasExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/grupos_etnicos.csv';
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
            $id = $this->request->getGet('etnia');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $etnia = $this->model->find($id);
                if ($etnia == null) {
                    return $this->failNotFound("No se ha encontrado un etnia con el id : ".$id);
                }else{
                    return $this->respond($etnia);
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
                $etniaVerificado = $this->model->find($id);
                if ($etniaVerificado == null) {
                    return $this->failNotFound("No se ha encontrado un etnia con el id : ".$id);
                }else{
                    $etnia = $this->request->getJSON();
                    
            if ($this->model->update($id,$etnia)) {
                $etnia->id = $id;
                return $this->respondUpdated($etnia);
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