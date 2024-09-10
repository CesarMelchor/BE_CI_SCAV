<?php 
namespace App\Controllers\API;
use App\Models\ArtesanoModel;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;


class Artesano extends ResourceController{

    public function __construct(){
        $this->model = new ArtesanoModel(); 
    }


    public function actualizarRamas()
    {
        $totales = $this->model->countAll();
        $artesanos = $this->model->findAll();
       $resultado = $this->model->actualizarRegistrosRamas($totales,$artesanos);

       return $this->respond(['mensaje' => $resultado]);

    }

    public function actualizarSubRamas()
    {
$totales = $this->model->countAll();
$artesanos = $this->model->findAll();
       $resultado = $this->model->actualizarRegistrosSubRamas($totales,$artesanos);

       return $this->respond(['mensaje' => $resultado]);

    }

    public function actualizarMaterias()
    {
$totales = $this->model->countAll();
$artesanos = $this->model->findAll();
       $resultado = $this->model->actualizarRegistrosMaterias($totales,$artesanos);

       return $this->respond(['mensaje' => $resultado]);

    }

    public function actualizarCanales()
    {
$totales = $this->model->countAll();
$artesanos = $this->model->findAll();
       $resultado = $this->model->actualizarRegistrosCanales($totales,$artesanos);

       return $this->respond(['mensaje' => $resultado]);

    }

    public function actualizarFechasCredenciales()
    {
$totales = $this->model->countAll();
$artesanos = $this->model->findAll();
       $resultado = $this->model->actualizarRegistrosFechasCredenciales($totales,$artesanos);

       return $this->respond(['mensaje' => $resultado]);

    }

    // -------------------------------------------------------------

    
    public function getAllArtesanos()
    {

       $artesanos = $this->model->orderBy('id_artesano', 'ASC')->findAll();

        return $this->respond($artesanos);
    }

    public function obtenerInformacionArtesanoExcel(){

        $curp = $this->request->getGet('curp');

        $rutaArchivo = WRITEPATH.'/assets/excel/REPORTE_ARTESANOS.xlsx';
        
        $documento = IOFactory::load($rutaArchivo);
        $hojaActual = $documento->getSheet(0);
        $totalregistros = $hojaActual->getHighestDataRow();
        $artesano = 0;
        $informacion = array();

        for ($i = 1; $i <= $totalregistros ; $i++) { 
            if ($hojaActual->getCell('K'.$i) == $curp) {
                $item = array(
                    'id_artesano' => strval($hojaActual->getCell('B'.$i)),
                    'nombre' => strval($hojaActual->getCell('D'.$i)),
                    'primer_apellido' => strval($hojaActual->getCell('E'.$i)),
                    'segundo_apellido' => strval($hojaActual->getCell('F'.$i)),
                    'curp' => strval($hojaActual->getCell('K'.$i)),
                );

                array_push($informacion,$item);
                json_encode($informacion);

                $artesano = 1;  
            }
        }

        if ($artesano == 1) {
        
        
            
        return $this->respond($informacion[0], 200);
        } else
        
        {

            return $this->respond(['mensaje' => $artesano], 203);
        }
        

    }


    
    public function entrega($id = null){
        try {
            

            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                
                $Verificado = $this->model->find($id);
                if ($Verificado == null) {
                return $this->respond(['error' => 'el usuario no existe'],203);

               }else{

                $data = [
                    'fecha_entrega_credencial' => date("Y-m-d"),
                    'updated_at'    => date("Y-m-d"),
                    'seccion' => 'ENTREGADO'
                ];

                $this->model->update($id, $data);
                
                return $this->respond(['mensaje' => 'exito'],200);
                    
                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }
    

    public function getAll()
    {

       $artesanos = $this->model->findAll(20);


        return $this->respond($artesanos);
    }

    public function getListHome()
    {

       $artesanos = $this->model->getListartesanos();


        return $this->respond($artesanos);
    }

    public function sorteo()
    {
        $municipio = $this->request->getGet('municipio');
        $region = $this->request->getGet('region');
        $distrito = $this->request->getGet('distrito');
        $localidad = $this->request->getGet('localidad');
        $rama = $this->request->getGet('rama');
        $registros = $this->request->getGet('registros');
        $registros = intval($registros);

        $artesanos = $this->model->filtersFerias($municipio,$region,$distrito,$localidad,$rama);
        
        shuffle($artesanos);

        $artesanos = array_chunk($artesanos,$registros);

        return $this->respond($artesanos);
    }



    public function search(){

        $busqueda = $this->request->getGet('buscar');
        $region = $this->request->getGet('region');
        $distrito = $this->request->getGet('distrito');
        $municipio = $this->request->getGet('municipio');
        $localidad = $this->request->getGet('localidad');
        $rama = $this->request->getGet('rama');
        $tecnica = $this->request->getGet('tecnica');
        $tipo = $this->request->getGet('tipo');
        
        $result = $this->model->searching($busqueda,$region,$distrito,$municipio,$localidad,$rama,$tecnica,$tipo);

        if ($result == null) {
            return $this->respond(['mensaje' => 'Sin resultados'], 203);
        }else{
            return $this->respond($result, 200);
        }
    }

    public function create(){
        try {

            $register = $this->request->getJSON();

            $repeat = $this->model->where("id_artesano = '".$register->id_artesano."'")->first();
            if ($repeat != null) {
                return $this->respond(['mensaje' => 'Usuario repetido', 'id artesano' => $register->id_artesano,'curp guardada' => $repeat['curp'], 'curp enviada' => $register->curp ], 203);
            }
            else{

                $this->model->insert($register);
                return $this->respond(['mensaje' => $register->id_artesano],200);

            }


            
            
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }

    public function detailCredencial(){
        try {
            $id = $this->request->getGet('artesano');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $artesano = $this->model->getArtesanoCredencial($id);
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

   
    
    public function detail(){
        try {
            $id = $this->request->getGet('artesano');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $artesano = $this->model->getArtesano($id);
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

    public function detailByCurp(){
        try {
            $id = $this->request->getGet('curp');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $artesano = $this->model->getArtesanoByCurp($id);
                if ($artesano == null) {
                    
                    return $this->respond(['mensaje' => 'error'], 203);
                }else{
                    return $this->respond($artesano[0]);
                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }

    public function detailCon(){
        try {
            $id = $this->request->getGet('artesano');
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                $artesano = $this->model->getArtesanoCon($id);
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

    public function baja($id = null){
        try {
            if ($id == null) {
                return $this->failServerError("No se ha encontrado un ID válido");
            }else{
                
                $newRegister = $this->request->getJSON();
                $Verificado = $this->model->find($id);
                if ($Verificado == null) {

                return $this->respond(['mensaje' => 'usuario no encontrado'],203);

               }else{

                $this->model->update($id,$newRegister);
                
                return $this->respond(['mensaje' => 'exito'],200);
                    
                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
    }

    public function image(){

        try {
            
        $nombre = $this->request->getGet('artesano');

            if ($nombre == null) {

                return $this->failServerError("No se ha encontrado un ID válido");

            }else{
                
                $artesano = $this->model->getArtesanoCredencial($nombre);
                if ($artesano == null) {

                    return $this->failNotFound("No se ha encontrado un artesano con el id : ".$nombre);

                }else{
                    
                    $artesano = $artesano[0];
                    
                    $rows = json_decode(json_encode($artesano), true);
                    
                    $img = imagecreatefromjpeg(WRITEPATH.'/assets/credencial.jpg');
                    $white = imagecolorallocate($img,0,0,0);
                    $red = imagecolorallocate($img,157,36,73);
                    $id = $rows['id_artesano'];
                    $nombre = $rows['nombre'];
                    $apellidos = $rows['primer_apellido'].' '.$rows['segundo_apellido'];
                    $tipo_inscripcion = $rows['gpo_pertenencia'];
                    $texto = 'PERSONA ARTESANA';
                    if ($rows['sexo'] == 'H') {
                        $persona = 'PRODUCTOR DE ';
                    }else{
                        $persona = 'PRODUCTORA DE ';

                    }
                    
                    $rama = $rows['nombre_rama'];
                    $id_organizacion = $rows['id_organizacion'];
                    $domicilio = $rows['calle'].' '.$rows['num_exterior'];
                    $localidad = $rows['localidad'];
                    $municipio = $rows['municipio'];
                    $region = $rows['region'];
                    $cp = $rows['cp'];
                    $telefono = $rows['tel_celular'];
                    $ine = $rows['clave_ine'];
                    $curp = $rows['curp'];
                    $fecha_registro = $rows['created_at'];

                    if ($tipo_inscripcion != 'INDEPENDIENTE') {

                        # credencial de personas que son independientes
                           
                    $font = WRITEPATH.'/assets/Roboto/Roboto-Black.ttf';
                    imagettftext($img,7,0,330,55,$white,$font,$id);
                    
                    imagettftext($img,13,0,85,214,$red,$font,$nombre);
                    imagettftext($img,13,0,50,231,$red,$font,$apellidos);

                    imagettftext($img,6,0,61,246,$white,$font,$texto);
                    imagettftext($img,6,0,61,256,$white,$font,$persona.$rama);

                    imagettftext($img,7,0,280,105,$white,$font,'TIPO DE INSCRIPCIÓN: ');
                    imagettftext($img,7,0,380,105,$white,$font,$tipo_inscripcion);
                    imagettftext($img,7,0,280,117,$white,$font,'ID ORGANIZACIÓN: ');
                    imagettftext($img,7,0,360,117,$white,$font,$id_organizacion);
                    imagettftext($img,7,0,280,129,$white,$font,'DOMICILIO: ');
                    imagettftext($img,7,0,335,129,$white,$font,$domicilio);
                    imagettftext($img,7,0,280,141,$white,$font,'LOCALIDAD: ');
                    imagettftext($img,7,0,335,141,$white,$font,$localidad);
                    imagettftext($img,7,0,280,153,$white,$font,'MUNICIPIO: ');
                    imagettftext($img,7,0,335,153,$white,$font,$municipio);
                    imagettftext($img,7,0,280,165,$white,$font,'REGIÓN: ');
                    imagettftext($img,7,0,320,165,$white,$font,$region);
                    imagettftext($img,7,0,280,177,$white,$font,'CP: ');
                    imagettftext($img,7,0,300,177,$white,$font,$cp);
                    imagettftext($img,7,0,280,189,$white,$font,'TELÉFONO: ');
                    imagettftext($img,7,0,330,189,$white,$font,$telefono);
                    imagettftext($img,7,0,280,201,$white,$font,'NÚMERO DE INE: ');
                    imagettftext($img,7,0,353,201,$white,$font,$ine);
                    imagettftext($img,7,0,280,213,$white,$font,'CURP: ');
                    imagettftext($img,7,0,310,213,$white,$font,$curp);
                    imagettftext($img,7,0,280,225,$white,$font,'FECHA DE REGISTRO: ');
                    imagettftext($img,7,0,373,225,$white,$font,$fecha_registro);
                    imagettftext($img,7,0,280,237,$white,$font,'VIGENTE HASTA: 2028');
                     
                    header('Content-Type: image/jpeg');
                    header("Content-Disposition: attachment;filename=".$rows['id_artesano'].".jpeg");
                    header('Cache-Control: max-age=0');
                    imagejpeg($img,NULL,100);     

                    }
                    else{

                        # credencial de personas que pertenecen a una organizacion / taller
                        
                    $font = WRITEPATH.'/assets/Roboto/Roboto-Black.ttf';
                    imagettftext($img,7,0,330,55,$white,$font,$id);
                    
                    imagettftext($img,13,0,85,214,$red,$font,$nombre);
                    imagettftext($img,13,0,50,231,$red,$font,$apellidos);

                    imagettftext($img,6,0,61,246,$white,$font,$texto);
                    imagettftext($img,6,0,61,256,$white,$font,$persona.$rama);

                    imagettftext($img,7,0,280,105,$white,$font,'TIPO DE INSCRIPCIÓN: ');
                    imagettftext($img,7,0,380,105,$white,$font,$tipo_inscripcion);
                    imagettftext($img,7,0,280,117,$white,$font,'DOMICILIO: ');
                    imagettftext($img,7,0,335,117,$white,$font,$domicilio);
                    imagettftext($img,7,0,280,129,$white,$font,'LOCALIDAD: ');
                    imagettftext($img,7,0,335,129,$white,$font,$localidad);
                    imagettftext($img,7,0,280,141,$white,$font,'MUNICIPIO: ');
                    imagettftext($img,7,0,335,141,$white,$font,$municipio);
                    imagettftext($img,7,0,280,153,$white,$font,'REGIÓN: ');
                    imagettftext($img,7,0,320,153,$white,$font,$region);
                    imagettftext($img,7,0,280,165,$white,$font,'CP: ');
                    imagettftext($img,7,0,300,165,$white,$font,$cp);
                    imagettftext($img,7,0,280,177,$white,$font,'TELÉFONO: ');
                    imagettftext($img,7,0,330,177,$white,$font,$telefono);
                    imagettftext($img,7,0,280,189,$white,$font,'NÚMERO DE INE: ');
                    imagettftext($img,7,0,353,189,$white,$font,$ine);
                    imagettftext($img,7,0,280,201,$white,$font,'CURP: ');
                    imagettftext($img,7,0,310,201,$white,$font,$curp);
                    imagettftext($img,7,0,280,213,$white,$font,'FECHA DE REGISTRO: ');
                    imagettftext($img,7,0,373,213,$white,$font,$fecha_registro);
                    imagettftext($img,7,0,280,225,$white,$font,'VIGENTE HASTA: 2028');
                    
                    header('Content-Type: image/jpeg');
                    header("Content-Disposition: attachment;filename=".$rows['id_artesano'].".jpeg");
                    header('Cache-Control: max-age=0');
                    imagejpeg($img,NULL,100);  
  
                }
                    
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
                $artesanoVerificado = $this->model->find($id);
                if ($artesanoVerificado == null) {
                    return $this->failNotFound("No se ha encontrado un artesano con el id : ".$id);
                }else{

                    $artesano = $this->request->getJSON();
                    
            if ($this->model->update($id,$artesano)) {
                

            return $this->respond(["mensaje"=> 'exito'],200);
            }
             else{
                
                return $this->respond(["mensaje"=> 'error'],203);
            }

                }
            }
        } catch (\Exception $e) {
            return $this->failServerError("Ha ocurrido un error en el servidor");
        }
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
$hoja_activa->getColumnDimension('O')->setAutoSize(true);
$hoja_activa->getColumnDimension('P')->setAutoSize(true);
$hoja_activa->getColumnDimension('Q')->setAutoSize(true);
$hoja_activa->getColumnDimension('R')->setAutoSize(true);
$hoja_activa->getColumnDimension('S')->setAutoSize(true);
$hoja_activa->getColumnDimension('T')->setAutoSize(true);
$hoja_activa->getColumnDimension('U')->setAutoSize(true);
$hoja_activa->getColumnDimension('V')->setAutoSize(true);
$hoja_activa->getColumnDimension('W')->setAutoSize(true);
$hoja_activa->getColumnDimension('X')->setAutoSize(true);
$hoja_activa->getColumnDimension('Y')->setAutoSize(true);
$hoja_activa->getColumnDimension('Z')->setAutoSize(true);
$hoja_activa->getColumnDimension('AA')->setAutoSize(true);
$hoja_activa->getColumnDimension('AB')->setAutoSize(true);
$hoja_activa->getColumnDimension('AC')->setAutoSize(true);
$hoja_activa->getColumnDimension('AD')->setAutoSize(true);
$hoja_activa->getColumnDimension('AE')->setAutoSize(true);
$hoja_activa->getColumnDimension('AF')->setAutoSize(true);
$hoja_activa->getColumnDimension('AG')->setAutoSize(true);
$hoja_activa->getColumnDimension('AH')->setAutoSize(true);
$hoja_activa->getColumnDimension('AI')->setAutoSize(true);
$hoja_activa->getColumnDimension('AJ')->setAutoSize(true);
$hoja_activa->getColumnDimension('AK')->setAutoSize(true);
$hoja_activa->getColumnDimension('AL')->setAutoSize(true);

$excel ->getProperties()->setCreator("Ing. César Melchor García");

$hoja_activa->setTitle("ARTESANOS");

$hoja_activa->setCellValue('A1','No');
$hoja_activa->setCellValue('B1','FOLIO ARTESANO');
$hoja_activa->setCellValue('C1','NOMBRE ARTESANO');
$hoja_activa->setCellValue('D1','NOMBRE (S)');
$hoja_activa->setCellValue('E1','PRIMER APELLIDO');
$hoja_activa->setCellValue('F1','SEGUNDO APELLIDO');
$hoja_activa->setCellValue('G1','FECHA DE REGISTRO');
$hoja_activa->setCellValue('H1','SEXO');
$hoja_activa->setCellValue('I1','FECHA NACIMIENTO');
$hoja_activa->setCellValue('J1','ESTADO CIVIL');
$hoja_activa->setCellValue('K1','CURP');
$hoja_activa->setCellValue('L1','CLAVE INE');
$hoja_activa->setCellValue('M1','RFC');
$hoja_activa->setCellValue('N1','CALLE');
$hoja_activa->setCellValue('O1','NUM EXTERIOR');
$hoja_activa->setCellValue('P1','NUM INTERIOR');
$hoja_activa->setCellValue('Q1','CP');
$hoja_activa->setCellValue('R1','REGION');
$hoja_activa->setCellValue('S1','DISTRITO');
$hoja_activa->setCellValue('T1','MUNICIPIO');
$hoja_activa->setCellValue('U1','LOCALIDAD');
$hoja_activa->setCellValue('V1','TELEFONO FIJO');
$hoja_activa->setCellValue('W1','TELEFONO CELULAR');
$hoja_activa->setCellValue('X1','CORREO');
$hoja_activa->setCellValue('Y1','REDES SOCIALES');
$hoja_activa->setCellValue('Z1','GRUPO ETNICO');
$hoja_activa->setCellValue('AA1','ESCOLARIDAD');
$hoja_activa->setCellValue('AB1','ENTREGA CREDENCIAL');
$hoja_activa->setCellValue('AC1','FECHA ENTREGA');
$hoja_activa->setCellValue('AD1','RAMA ARTESANAL');
$hoja_activa->setCellValue('AE1','TECNICA O PRODUCTO');
$hoja_activa->setCellValue('AF1','GRUPO PERTENECE');
$hoja_activa->setCellValue('AG1','NOMBRE ORG/TALLER');
$hoja_activa->setCellValue('AH1','REGION ORG/TALLER');
$hoja_activa->setCellValue('AI1','MUNICIPIO ORG/TALLER');
$hoja_activa->setCellValue('AJ1','MATERIA PRIMA');
$hoja_activa->setCellValue('AK1','CANAL DE COMERCIALIZACION');



    $periodo = $this->request->getGet('periodo');

    
    $fila = 2;
    $idContador = 1;

    foreach ($this->model->getArtesanosExcel($periodo) as $array){

        $rows = json_decode(json_encode($array), true);

        
    $sexo = 'FEMENINO';
        if($rows['sexo'] == 'H'){
    $sexo = 'MASCULINO';
        }
    

        $hoja_activa->setCellValue('A'.$fila, $idContador);
        $hoja_activa->setCellValue('B'.$fila,$rows['id_artesano']);
        $hoja_activa->setCellValue('C'.$fila,$rows['nombre'].' '.$rows['primer_apellido'].' '.$rows['segundo_apellido']);
        $hoja_activa->setCellValue('D'.$fila,$rows['nombre']);
        $hoja_activa->setCellValue('E'.$fila,$rows['primer_apellido']);
        $hoja_activa->setCellValue('F'.$fila,$rows['segundo_apellido']);
        $hoja_activa->setCellValue('H'.$fila,$sexo); 
        $hoja_activa->setCellValue('I'.$fila,$rows['fecha_nacimiento']);
        $hoja_activa->setCellValue('J'.$fila,$rows['edo_civil']);
        $hoja_activa->setCellValue('K'.$fila,$rows['curp']);
        $hoja_activa->setCellValue('L'.$fila,$rows['clave_ine']);
        $hoja_activa->setCellValue('M'.$fila,$rows['rfc']);
        $hoja_activa->setCellValue('N'.$fila,$rows['calle']);
        $hoja_activa->setCellValue('O'.$fila,$rows['num_exterior']);
        $hoja_activa->setCellValue('P'.$fila,$rows['num_interior']);
        $hoja_activa->setCellValue('Q'.$fila,$rows['cp']);
        $hoja_activa->setCellValue('V'.$fila,$rows['tel_fijo']);
        $hoja_activa->setCellValue('W'.$fila,$rows['tel_celular']);
        $hoja_activa->setCellValue('X'.$fila,$rows['correo']);
        $hoja_activa->setCellValue('Y'.$fila,$rows['redes_sociales']); 
        $hoja_activa->setCellValue('AA'.$fila,$rows['escolaridad']); 
        $hoja_activa->setCellValue('AB'.$fila,$rows['seccion']);
        $hoja_activa->setCellValue('AF'.$fila,$rows['gpo_pertenencia']); 
        
        
        
            $fila++;
            $idContador++;
    }

    
$fila = 2;
$idContador = 1;
    
    
foreach ($this->model->getArtesanosExcel2($periodo) as $array){

    $rows = json_decode(json_encode($array), true); 

    $hoja_activa->setCellValue('G'.$fila,$rows['created_at']);
    $hoja_activa->setCellValue('R'.$fila,$rows['region']);
    $hoja_activa->setCellValue('S'.$fila,$rows['distrito']);
    $hoja_activa->setCellValue('T'.$fila,'.');
    $hoja_activa->setCellValue('U'.$fila,'.'); 
    $hoja_activa->setCellValue('Z'.$fila,'.');
    $hoja_activa->setCellValue('AC'.$fila,$rows['fecha_entrega_credencial']);
    $hoja_activa->setCellValue('AD'.$fila,$rows['nombre_rama']);
    $hoja_activa->setCellValue('AE'.$fila,$rows['nombre_subrama']);
    $hoja_activa->setCellValue('AG'.$fila,'.'); 
    $hoja_activa->setCellValue('AH'.$fila,'.'); 
    $hoja_activa->setCellValue('AJ'.$fila,$rows['materiap']);
    $hoja_activa->setCellValue('AK'.$fila,'.');
    
    
    
        $fila++;
        $idContador++;
}



    $fila = 2;
    $idContador = 1;
    
    foreach ($this->model->getArtesanosExcel3($periodo) as $array) {
        
        $rows = json_decode(json_encode($array), true); 

        $hoja_activa->setCellValue('T'.$fila,$rows['municipio']);
        $hoja_activa->setCellValue('U'.$fila,$rows['localidad']); 
        $hoja_activa->setCellValue('Z'.$fila,$rows['nombre_etnia']);
        $hoja_activa->setCellValue('AG'.$fila,$rows['nombre_organizacion']); 
        $hoja_activa->setCellValue('AK'.$fila,$rows['tipocomp']);
        
        
            $fila++;
            $idContador++;

    }

    
    $fila = 2;
    $idContador = 1;

    foreach ($this->model->getArtesanosExcelOrganizacion($periodo) as $array) {
        
        $rows = json_decode(json_encode($array), true); 

        $hoja_activa->setCellValue('AH'.$fila,$rows['region']); 
        $hoja_activa->setCellValue('AI'.$fila,$rows['municipio']);
        
            $fila++;
            $idContador++;
    }


$fila = 2;
$idContador = 1;





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


$excel->getActiveSheet()->duplicateStyle($sharedStyle, 'A1:AK1');
$excel->getActiveSheet()->duplicateStyle($sharedStyle2, 'A2:'.'AK'.$fila - 1);



header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=REPORTE_ARTESANOS_".$periodo.".xlsx");
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($excel, 'Xlsx');
$writer->save('php://output');
exit;

    }


   
}