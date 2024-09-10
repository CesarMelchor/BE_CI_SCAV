<?php 
namespace App\Controllers\API;

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use App\Models\AccionesCapacitacionesModel;
use CodeIgniter\RESTful\ResourceController;

class AccionesCapacitaciones extends ResourceController{

    public function __construct(){
        $this->model = new AccionesCapacitacionesModel;
    }

    public function getAll()
    {

       $data = $this->model->findAll();

        return $this->respond($data);
    }

    public function getAllHome()
    {

       $data = $this->model->getAccionesHome();

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
    public function obtenerArtesanosAccionesExcel(){

        $accion = $this->request->getGet('accion');

        $rutaArchivo = WRITEPATH.'/assets/excel/inscripciones_capacitaciones.csv';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $documento = $reader->load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $totalregistros = $hojaActual->getHighestDataRow();
        $informacion = array();
        
        $rutaArchivo2 = WRITEPATH.'/assets/excel/artesanos.csv';
        $reader2 = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $documento2 = $reader2->load($rutaArchivo2);
        $hojaActual2 = $documento2->getSheet(0);
        $totalregistros2 = $hojaActual2->getHighestDataRow();

        for ($i = 2; $i <= $totalregistros ; $i++) { 

            if (strval($hojaActual->getCell('E'.$i)) == $accion) {

                $artesano = strval($hojaActual->getCell('B'.$i));

                for ($j = 1; $j <= $totalregistros2; $j++) { 

                    if (strval($hojaActual2->getCell('A'.$j)) == $artesano) {

                        $item = array(
                            'nombre' => strval($hojaActual2->getCell('B'.$j)).' '.strval($hojaActual2->getCell('C'.$j)).' '.strval($hojaActual2->getCell('D'.$j)),
                            'curp' => strval($hojaActual2->getCell('H'.$j)),
                        );
        
                        array_push($informacion,$item);

                    }
                }

            }

        }
        
        json_encode($informacion);

            
        return $this->respond($informacion, 200);

    }


    
    public function obtenerAccionesExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/acciones_capacitaciones.csv';
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
                            strval($hojaActual->getCell('G1')) => strval($hojaActual->getCell('G'.$i)),
                            strval($hojaActual->getCell('H1')) => strval($hojaActual->getCell('H'.$i)),
                            strval($hojaActual->getCell('I1')) => strval($hojaActual->getCell('I'.$i)),
                            strval($hojaActual->getCell('J1')) => strval($hojaActual->getCell('J'.$i)),
                            strval($hojaActual->getCell('K1')) => strval($hojaActual->getCell('K'.$i)),
                            strval($hojaActual->getCell('L1')) => strval($hojaActual->getCell('L'.$i)),
                            strval($hojaActual->getCell('M1')) => strval($hojaActual->getCell('M'.$i)),
                            strval($hojaActual->getCell('N1')) => strval($hojaActual->getCell('N'.$i)),
                            strval($hojaActual->getCell('O1')) => strval($hojaActual->getCell('O'.$i)),
                            strval($hojaActual->getCell('P1')) => strval($hojaActual->getCell('P'.$i)),
                            strval($hojaActual->getCell('Q1')) => strval($hojaActual->getCell('Q'.$i))
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