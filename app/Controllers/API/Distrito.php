<?php 
namespace App\Controllers\API;

use App\Models\DistritoModel;
use CodeIgniter\RESTful\ResourceController;

class Distrito extends ResourceController{

    public function __construct(){
        $this->model = new DistritoModel();
    }

    public function getAll()
    {

       $distritos = $this->model->getAllDistritos();

        return $this->respond($distritos);
    }

    public function getAllByReg($region)
    {

       $distritos = $this->model->getAllDistritosByRegion($region);

        return $this->respond($distritos);
    }


    public function obtenerDistritosExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/tdistrito.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $documento = $reader->load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $totalregistros = $hojaActual->getHighestDataRow();
        $informacion = array();

        for ($i = 2; $i <= $totalregistros ; $i++) { 
          
                        $item = array(
                            strval($hojaActual->getCell('A1')) => strval($hojaActual->getCell('A'.$i)),
                            strval($hojaActual->getCell('B1')) => strval($hojaActual->getCell('B'.$i)),
                            strval($hojaActual->getCell('C1')) => strval($hojaActual->getCell('C'.$i))
                        );
        
                        array_push($informacion,$item);

        }
        
        json_encode($informacion);

            
        return $this->respond($informacion, 200);

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