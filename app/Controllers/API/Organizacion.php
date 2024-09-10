<?php 
namespace App\Controllers\API;

use App\Models\OrganizacionModel;
use CodeIgniter\RESTful\ResourceController;

class Organizacion extends ResourceController{

    public function __construct(){
        $this->model = new OrganizacionModel();
    }

    public function getAll()
    {

       $organizaciones = $this->model->getAll();

        return $this->respond($organizaciones);
    }

    public function actualizarRamas()
    {
        $totales = $this->model->countAll();
        $organizaciones = $this->model->findAll();
       $resultado = $this->model->actualizarRegistrosRamas($totales,$organizaciones);

       return $this->respond(['mensaje' => $resultado]);

    }

    public function actualizarTecnicas()
    {
        $totales = $this->model->countAll();
        $organizaciones = $this->model->findAll();
       $resultado = $this->model->actualizarRegistrosTecnicas($totales,$organizaciones);

       return $this->respond(['mensaje' => $resultado]);

    }

    public function obtenerOrganizacionesExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/organizaciones.csv';
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
                            strval($hojaActual->getCell('Q1')) => strval($hojaActual->getCell('Q'.$i)),
                            strval($hojaActual->getCell('R1')) => strval($hojaActual->getCell('R'.$i)),
                            strval($hojaActual->getCell('S1')) => strval($hojaActual->getCell('S'.$i)),
                            strval($hojaActual->getCell('T1')) => strval($hojaActual->getCell('T'.$i)),
                            strval($hojaActual->getCell('U1')) => strval($hojaActual->getCell('U'.$i)),
                            strval($hojaActual->getCell('V1')) => strval($hojaActual->getCell('V'.$i)),
                            strval($hojaActual->getCell('W1')) => strval($hojaActual->getCell('W'.$i)),
                            strval($hojaActual->getCell('X1')) => strval($hojaActual->getCell('X'.$i)),
                            strval($hojaActual->getCell('Y1')) => strval($hojaActual->getCell('Y'.$i)),
                            strval($hojaActual->getCell('Z1')) => strval($hojaActual->getCell('Z'.$i)),
                        );
        
                        array_push($informacion,$item);

        }
        
        json_encode($informacion);

            
        return $this->respond($informacion, 200);

    }
    public function getOrganizaciones()
    {

       $organizaciones = $this->model->getAgrupaciones();

        return $this->respond($organizaciones);
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
    
    public function detail(){
        try {
            $id = $this->request->getGet('organizacion');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $organizacion = $this->model->getOrganizacion($id);
                if ($organizacion == null) {
                    return $this->failNotFound("No se ha encontrado un artesano con el id : ".$id);
                }else{
                    return $this->respond($organizacion[0]);
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
                $organizacionVerificado = $this->model->find($id);
                if ($organizacionVerificado == null) {
                    return $this->failNotFound("No se ha encontrado un organizacion con el id : ".$id);
                }else{
                    $organizacion = $this->request->getJSON();
                    
            if ($this->model->update($id,$organizacion)) {
                $organizacion->id = $id;
                return $this->respondUpdated($organizacion);
            } else{
                return $this->failValidationErrors($this->model->validation->listErrors());
            }

                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }
    

    public function create(){
        try {

            $register = $this->request->getJSON();

            $count = $this->model->countAllResults();
            $register->id_organizacion = $register->id_organizacion.($count + 1);

                $this->model->insert($register);
                return $this->respond(['mensaje' => $register->id_organizacion],200);
            
            
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }

    
}