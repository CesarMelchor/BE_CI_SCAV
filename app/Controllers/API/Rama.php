<?php 
namespace App\Controllers\API;
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

use App\Models\RamaModel;
use CodeIgniter\RESTful\ResourceController;

class Rama extends ResourceController{

    public function __construct(){
        $this->model = new RamaModel();
    }

    public function getAll()
    {

       $ramas = $this->model->where(['activo' => 1])->findAll();

        return $this->respond($ramas);
    }

    public function obtenerRamasExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/ramas_artesanales.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $documento = $reader->load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $totalregistros = $hojaActual->getHighestDataRow();
        $informacion = array();

        for ($i = 2; $i <= $totalregistros ; $i++) { 
          
                        $item = array(
                            strval($hojaActual->getCell('A1')) => strval($hojaActual->getCell('A'.$i)),
                            strval($hojaActual->getCell('B1')) => strval($hojaActual->getCell('B'.$i)),
                            strval($hojaActual->getCell('C1')) => strval($hojaActual->getCell('C'.$i)),
                            strval($hojaActual->getCell('D1')) => strval($hojaActual->getCell('D'.$i)),
                            strval($hojaActual->getCell('E1')) => strval($hojaActual->getCell('E'.$i)),
                            strval($hojaActual->getCell('F1')) => strval($hojaActual->getCell('F'.$i)),
                        );
        
                        array_push($informacion,$item);

        }
        
        json_encode($informacion);

            
        return $this->respond($informacion, 200);

    }

    public function create(){
        try {
            $register = $this->request->getJSON();

            $count = $this->model->countAllResults();
            $register->id_rama = $register->id_rama.($count + 1);

                $this->model->insert($register);
                return $this->respond($register,200);
            
            
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
                }else{
                    
            $data = $this->request->getJSON();
            
            if ($this->model->update($id, $data) == true) {
                
            return $this->respond(["mensaje"=> 'exito'],200);
            }
            else {
                return $this->respond(["mensaje"=> 'error'],203);
            }
            

                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }
}