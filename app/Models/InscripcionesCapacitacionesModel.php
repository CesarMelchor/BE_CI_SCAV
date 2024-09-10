<?php 
namespace App\Models;

use CodeIgniter\Model;

class InscripcionesCapacitacionesModel extends Model{
    protected $table      = 'inscripciones_capacitaciones';
    protected $primaryKey = 'id';
    protected $returnedType = 'array';
    protected $allowedFields = ['id_artesano', 'solicitud','observaciones','id_accion','asistencia','cargo','created_at','updated_at'];

    public function searching($buscar){

        $builder = $this->db->table($this->table);

        $builder->select('
        inscripciones_capacitaciones.id,inscripciones_capacitaciones.solicitud,inscripciones_capacitaciones.observaciones,
        artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
        artesanos.segundo_apellido,artesanos.curp, acciones_capacitaciones.nombre as nombreaccion,
        acciones_capacitaciones.annio as annioaccion,
        programas_capacitaciones.nombre_programa as nombreprograma ,trimestres_capacitaciones.id as trimestreid');

        $builder->join('artesanos', 'artesanos.id_artesano = inscripciones_capacitaciones.id_artesano','left');
        $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = inscripciones_capacitaciones.id_accion','left');
        $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa','left');
        $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre','left');
      
        $where = "concat(artesanos.nombre,' ', artesanos.primer_apellido,' ', artesanos.segundo_apellido) 
        like '%$buscar%' or curp like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }


     public function filters($municipio,$nombre,$region,$rama,$etnia, $accion){

      if ($region == "") {
         $eqregion = "!=";
      }else{
         $eqregion = "=";
      }
      
      
      if ($municipio == "") {
         $eqmunicipio = "!=";
      }else{
         $eqmunicipio = "=";
      }
      
      if ($etnia == "") {
         $eqetnia = "!=";
      }else{
         $eqetnia = "=";
      }
      
      if ($rama == "") {
         $eqrama = "!=";
      }else{
         $eqrama = "=";
      }
      
      if ($accion == "") {
         $eqaccion = "!=";
      }else{
         $eqaccion = "=";
      }

        $builder = $this->db->table($this->table);

        $builder->select('
        inscripciones_capacitaciones.id,inscripciones_capacitaciones.solicitud,inscripciones_capacitaciones.observaciones,
        artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
        artesanos.segundo_apellido,artesanos.curp, acciones_capacitaciones.nombre as nombreaccion,
        acciones_capacitaciones.annio as annioaccion,
        programas_capacitaciones.nombre_programa as nombreprograma ,trimestres_capacitaciones.id as trimestreid,
        trimestres_capacitaciones.meses,
        tregion.region,tmunicipio.municipio,ramas_artesanales.nombre_rama,grupos_etnicos.nombre_etnia,
        programas_capacitaciones.nombre_programa, tlocalidad.localidad, artesanos.tel_celular , artesanos.correo,
        lenguas_indigenas.lengua');

        $builder->join('artesanos', 'artesanos.id_artesano = inscripciones_capacitaciones.id_artesano','left');
        $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = inscripciones_capacitaciones.id_accion','left');
        $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa','left');
        $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre','left');
        $builder->join('lenguas_indigenas', 'lenguas_indigenas.id_lengua = artesanos.id_lengua', 'left');
        
        $builder->join('tmunicipio', 'tmunicipio.id_municipio = artesanos.id_municipio','left');
        $builder->join('tlocalidad', 'tlocalidad.id_localidad = artesanos.id_localidad','left');
        $builder->join('tregion', 'tregion.id_region = artesanos.id_region','left');
        $builder->join('grupos_etnicos', 'grupos_etnicos.id_grupo = artesanos.id_grupo','left');
        $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = artesanos.id_rama','left');
        $builder->join('sub_ramas_artesanales', 'sub_ramas_artesanales.id_subrama = artesanos.id_tecnica','left');
        
        $where = "concat(artesanos.nombre,' ', artesanos.primer_apellido,' ', artesanos.segundo_apellido) 
        like '$nombre%' and tregion.id_region $eqregion '$region' and tmunicipio.id_municipio $eqmunicipio '$municipio' 
        and ramas_artesanales.id_rama $eqrama '$rama' and grupos_etnicos.id_grupo $eqetnia '$etnia'
        and acciones_capacitaciones.id $eqaccion '$accion' 
        ";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }
 
    public function findAllRegisters(){

        $builder = $this->db->table($this->table);

        $builder->select('
        inscripciones_capacitaciones.id,inscripciones_capacitaciones.id_accion,inscripciones_capacitaciones.asistencia
        ,inscripciones_capacitaciones.solicitud,inscripciones_capacitaciones.observaciones,
        artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
        artesanos.segundo_apellido,artesanos.curp, acciones_capacitaciones.nombre as nombreaccion,
        acciones_capacitaciones.annio as annioaccion,
        programas_capacitaciones.nombre_programa as nombreprograma ,trimestres_capacitaciones.id as trimestreid');

        $builder->join('artesanos', 'artesanos.id_artesano = inscripciones_capacitaciones.id_artesano','left');
        $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = inscripciones_capacitaciones.id_accion','left');
        $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa','left');
        $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre','left');
        
    

        $query = $builder->get();
        return $query->getResult();
  
     }

     public function getArtesanosConstancias($accion, $asistencia){

      $builder = $this->db->table($this->table);

      if ($asistencia == '1') {
         
         $builder->select('inscripciones_capacitaciones.id, artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
         artesanos.segundo_apellido,artesanos.curp');
   
         $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = inscripciones_capacitaciones.id_accion');
         $builder->join('artesanos', 'artesanos.id_artesano = inscripciones_capacitaciones.id_artesano');
         
         $where = "inscripciones_capacitaciones.id_accion = '".$accion."' and asistencia = '1'";
    
         $builder->where($where);
   
         $query = $builder->get();
         return $query->getResult();  
      }else{

         $builder->select('inscripciones_capacitaciones.id, artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
         artesanos.segundo_apellido,artesanos.curp');
   
         $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = inscripciones_capacitaciones.id_accion');
         $builder->join('artesanos', 'artesanos.id_artesano = inscripciones_capacitaciones.id_artesano');
         
         $where = "inscripciones_capacitaciones.id_accion = '".$accion."'";
    
         $builder->where($where);
   
         $query = $builder->get();
         return $query->getResult();  
      }

      

     }
     
    public function getInfo($artesano){

      $builder = $this->db->table($this->table);

      $builder = $this->db->table($this->table);

      $builder->select('
      inscripciones_capacitaciones.id,inscripciones_capacitaciones.solicitud,inscripciones_capacitaciones.observaciones,
      artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
      artesanos.segundo_apellido,artesanos.curp, acciones_capacitaciones.nombre as nombreaccion,
      acciones_capacitaciones.annio as annioaccion,
      programas_capacitaciones.nombre_programa as nombreprograma ,trimestres_capacitaciones.id as trimestreid,
      tregion.region,tmunicipio.municipio,ramas_artesanales.nombre_rama,grupos_etnicos.nombre_etnia,
      programas_capacitaciones.nombre_programa
      ');

      $builder->join('artesanos', 'artesanos.id_artesano = inscripciones_capacitaciones.id_artesano','left');
      $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = inscripciones_capacitaciones.id_accion','left');
      $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa','left');
      $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre','left');
      
      $builder->join('tmunicipio', 'tmunicipio.id_municipio = artesanos.id_municipio');
      $builder->join('tregion', 'tregion.id_region = artesanos.id_region');
      $builder->join('grupos_etnicos', 'grupos_etnicos.id_grupo = artesanos.id_grupo');
      $builder->join('ramas_artesanos', 'ramas_artesanos.id_artesano = artesanos.id_artesano');
      $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = ramas_artesanos.id_rama');
      $builder->join('sub_ramas_artesanales', 'sub_ramas_artesanales.id_subrama = ramas_artesanos.id_subrama');
      
      $where = "inscripciones_capacitaciones.id_artesano = '".$artesano."'";
 
      $builder->where($where);

      $query = $builder->get();
      return $query->getResult();

   }
  
   public function getConstancia($id = null){

      $builder = $this->db->table($this->table);

      $builder->select('artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
      artesanos.segundo_apellido, artesanos.curp,acciones_capacitaciones.nombre as nombrecapacitacion,
      acciones_capacitaciones.capacitador,acciones_capacitaciones.cargo');
      
      $builder->join('artesanos', 'artesanos.id_artesano = inscripciones_capacitaciones.id_artesano');
      $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = inscripciones_capacitaciones.id_accion');
      
      $where = "inscripciones_capacitaciones.id = ".$id."";
      $builder->where($where);

      $query = $builder->get();
      return $query->getResult();

   }

    
}