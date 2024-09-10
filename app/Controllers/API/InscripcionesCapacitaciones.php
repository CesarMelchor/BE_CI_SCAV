<?php 
namespace App\Controllers\API;

use App\Models\InscripcionesCapacitacionesModel;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

class InscripcionesCapacitaciones extends ResourceController{

    public function __construct(){
        $this->model = new InscripcionesCapacitacionesModel;
    }

    public function getAll()
    {

       $data = $this->model->findAllRegisters();

        return $this->respond($data);
    }

    public function getInfo()
    {
        $artesano = $this->request->getGet('artesano');

       $data = $this->model->getInfo($artesano);

        return $this->respond($data);
    }

    
    public function obtenerArtesanosInscripciones(){

        $accion = $this->request->getGet('accion');
        $asistencia = $this->request->getGet('asistencia');
        
        $informacion = $this->model->getArtesanosConstancias($accion,$asistencia);

        json_encode($informacion);

        return $this->respond($informacion, 200);

    }

    public function constancia(){
        try {
            $id = $this->request->getGet('inscripcion');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $artesano = $this->model->getConstancia($id);
                if ($artesano == null) {
                    return $this->failNotFound("No se ha encontrado un artesano con el id : ".$id);
                }else{
                    return $this->respond($artesano[0]);
                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }

    public function agregarInscripcionExcel(){

        $artesano = $this->request->getGet('id_artesano');
        $solicitud = $this->request->getGet('solicitud');
        $observaciones = $this->request->getGet('observaciones');
        $accion = $this->request->getGet('id_accion');
        $creado = $this->request->getGet('created_at');
        $actualizado = $this->request->getGet('updated_at');


        $rutaArchivo = WRITEPATH.'/assets/excel/inscripciones_capacitaciones.xlsx';
        $documento = IOFactory::load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $celdaAgregar = $hojaActual->getHighestDataRow();
        $celdaAgregar = intval($celdaAgregar) + 1;

        $hojaActual->setCellValue('A'.$celdaAgregar, 'DEFAULT');
        $hojaActual->setCellValue('B'.$celdaAgregar, strval($artesano));
        $hojaActual->setCellValue('C'.$celdaAgregar, strval($solicitud));
        $hojaActual->setCellValue('D'.$celdaAgregar, strval($observaciones));
        $hojaActual->setCellValue('E'.$celdaAgregar, strval($accion));
        $hojaActual->setCellValue('F'.$celdaAgregar, strval($creado));
        $hojaActual->setCellValue('G'.$celdaAgregar, strval($actualizado));

        $writer = IOFactory::createWriter($documento, "Xlsx");
        $writer->save(WRITEPATH.'/assets/excel/inscripciones_capacitaciones.xlsx');

        return $this->respond(['mensaje' => 'exito'], 200);

    }
    
   
    public function obtenerInscripcionesExcel(){

        $rutaArchivo = WRITEPATH.'/assets/excel/inscripciones_capacitaciones.csv';
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
                            strval($hojaActual->getCell('G1')) => strval($hojaActual->getCell('G'.$i))
                        );
        
                        array_push($informacion,$item);

        }
        
        json_encode($informacion);

            
        return $this->respond($informacion, 200);

    }

    public function create(){
        try {

            $register = $this->request->getJSON();
            
            $data = $this->model->where("id_artesano = '".$register->id_artesano."' and id_accion = '".$register->id_accion."'")->first();
            if ($data == null) {
              
                $this->model->insert($register);
                return $this->respond(['mensaje' => 'exito'],200);
            }else{
                  return $this->respond(['mensaje' => 'Ya existe un registro con este artesano y acción'], 203);
            }

            
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }


    public function search(){

        $municipio = $this->request->getGet('municipio');
        $region = $this->request->getGet('region');
        $nombre = $this->request->getGet('nombre');
        $rama = $this->request->getGet('rama');
        $etnia = $this->request->getGet('etnia');
        $accion = $this->request->getGet('accion');
        
        $result = $this->model->filters($municipio,$nombre,$region,$rama,$etnia,$accion);

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

    public function updateAsistencias(){

        $data = file_get_contents('php://input');
        $data = json_decode($data,true);
        $alta = $data['alta'];
        $baja = $data['baja'];
        
        if (count($alta) > 0) {
            for ($i=0; $i < count($alta); $i++) { 
                $data = [
                    'asistencia' => 1,
                ];
    
                $this->model->update($alta[$i]['id'],$data);
            } 
        }

        if (count($baja) > 0) {

            for ($i=0; $i < count($baja); $i++) { 
                $data = [
                    'asistencia' => 0,
                ];
    
                $this->model->update($baja[$i]['id'],$data);
            }
        }

        return $this->respond(['mensaje' => 'exito'],200);
       

    }

    
    public function reporte(){
        
        $excel = new Spreadsheet();
        
        $excel->setActiveSheetIndex(0);
        
        
        $hoja_activa= $excel->getActiveSheet();
        
        $hoja_activa->getColumnDimension('A')->setAutoSize(true);
        $hoja_activa->getColumnDimension('B')->setAutoSize(true);
        $hoja_activa->getColumnDimension('C')->setAutoSize(true);
        $hoja_activa->getColumnDimension('D')->setAutoSize(true);
        $hoja_activa->getColumnDimension('E')->setAutoSize(true);
        $hoja_activa->getColumnDimension('F')->setAutoSize(true);
        $hoja_activa->getColumnDimension('G')->setAutoSize(true);
        $hoja_activa->getColumnDimension('H')->setAutoSize(true);
        $hoja_activa->getColumnDimension('I')->setAutoSize(true);
        $hoja_activa->getColumnDimension('J')->setAutoSize(true);
        $hoja_activa->getColumnDimension('K')->setAutoSize(true);
        $hoja_activa->getColumnDimension('L')->setAutoSize(true);
        $hoja_activa->getColumnDimension('M')->setAutoSize(true);
        $hoja_activa->getColumnDimension('N')->setAutoSize(true);
        
        $excel ->getProperties()->setCreator("Ing. César Melchor García");
        
        $hoja_activa->setTitle("REPORTE");
        
        $hoja_activa->setCellValue('A1','REGION');
        $hoja_activa->setCellValue('B1','GRUPO ETNICO');
        $hoja_activa->setCellValue('C1','MUNICIPIO');
        $hoja_activa->setCellValue('D1','LOCALIDAD');
        $hoja_activa->setCellValue('E1','NOMBRE');
        $hoja_activa->setCellValue('F1','CURP');
        $hoja_activa->setCellValue('G1','RAMA ARTESANAL');
        $hoja_activa->setCellValue('H1','TELEFONO');
        $hoja_activa->setCellValue('I1','CORREO');
        $hoja_activa->setCellValue('J1','LENGUA INDIGENA');
        $hoja_activa->setCellValue('K1','ACCION');
        $hoja_activa->setCellValue('L1','PROGRAMA');
        $hoja_activa->setCellValue('M1','AÑO');
        $hoja_activa->setCellValue('N1','TRIMESTRE');
        
        
        $fila = 2;
        $idContador = 1;
        
        
        $municipio = $this->request->getGet('municipio');
        $region = $this->request->getGet('region');
        $nombre = $this->request->getGet('nombre');
        $rama = $this->request->getGet('rama');
        $etnia = $this->request->getGet('etnia');
        $accion = $this->request->getGet('accion');
        
        $data = $this->model->filters($municipio,$nombre,$region,$rama,$etnia,$accion);
            
        
            foreach ($data as $array) {
                $rows = json_decode(json_encode($array), true);
            
        
                $hoja_activa->setCellValue('A'.$fila, $rows['region']);
                $hoja_activa->setCellValue('B'.$fila,$rows['nombre_etnia']);
                $hoja_activa->setCellValue('C'.$fila,$rows['municipio']);
                $hoja_activa->setCellValue('D'.$fila,$rows['localidad']);
                $hoja_activa->setCellValue('E'.$fila,$rows['nombre'].' '.$rows['primer_apellido'].' '.$rows['segundo_apellido']);
                $hoja_activa->setCellValue('F'.$fila,$rows['curp']);
                $hoja_activa->setCellValue('G'.$fila,$rows['nombre_rama']);
                $hoja_activa->setCellValue('H'.$fila,$rows['tel_celular']); 
                $hoja_activa->setCellValue('I'.$fila,$rows['correo']);
                $hoja_activa->setCellValue('J'.$fila,$rows['lengua']);
                $hoja_activa->setCellValue('K'.$fila,$rows['nombreaccion']);
                $hoja_activa->setCellValue('L'.$fila,$rows['nombre_programa']);
                $hoja_activa->setCellValue('M'.$fila,$rows['annioaccion']);
                $hoja_activa->setCellValue('N'.$fila,$rows['meses']);
                
                    $fila++;
                    $idContador++;
            
            }
        
        
        
        $sharedStyle = new Style();
        
        $sharedStyle->applyFromArray(
            [       
                
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFB5B5B5'],
                    ],
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'right' => ['borderStyle' => Border::BORDER_MEDIUM],
                        'left' => ['borderStyle' => Border::BORDER_MEDIUM],
                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ]
                ]
        );
        
        
        $sharedStyle2 = new Style();
        
        $sharedStyle2->applyFromArray(
            [       
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'right' => ['borderStyle' => Border::BORDER_MEDIUM],
                        'left' => ['borderStyle' => Border::BORDER_MEDIUM],
                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
        
                ]
        );
        
        
        $excel->getActiveSheet()->duplicateStyle($sharedStyle, 'A1:N1');
        $excel->getActiveSheet()->duplicateStyle($sharedStyle2, 'A2:'.'N'.$fila - 1);
        
        
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="REPORTE_CAPACITACIONES.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save('php://output');
        exit;
        
            }
        

    
}