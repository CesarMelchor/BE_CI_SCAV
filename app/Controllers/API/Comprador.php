<?php 
namespace App\Controllers\API;

use App\Models\CompradorModel;
use CodeIgniter\RESTful\ResourceController;

class Comprador extends ResourceController{

    public function __construct(){
        $this->model = new CompradorModel();
    }

    public function getAll()
    {

       $compradores = $this->model->findAll();

        return $this->respond($compradores);
    }


    public function obtenerCompradoresExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/tipo_compradores.csv';
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
    
}