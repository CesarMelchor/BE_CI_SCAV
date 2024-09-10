<?php 
namespace App\Models;
use CodeIgniter\Model;


class OrganizacionModel extends Model{
    protected $table      = 'organizaciones';
    protected $primaryKey = 'id_organizacion';
    protected $returnedType = 'array';
    protected $allowedFields = ['id_organizacion','representante', 'nombre_organizacion', 'rfc',
    'calle','num_exterior','num_interior','cp','id_region','id_distrito',
'id_municipio','id_localidad','tel_fijo','tel_celular','correo','num_integrantes','hombres',
'mujeres','activo','descripcion','tipo_org','tipo','created_at','updated_at','id_rama','id_tecnica'];



     public function searching($buscar){
        $builder = $this->db->table($this->table);
        $builder->select('organizaciones.id_organizacion,organizaciones.representante,
        organizaciones.nombre_organizacion,organizaciones.rfc,organizaciones.calle, 
        tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,organizaciones.created_at');
        $builder->join('tregion', 'tregion.id_region = organizaciones.id_region','left');
        $builder->join('tdistrito', 'tdistrito.id_distrito = organizaciones.id_distrito','left');
        $builder->join('tmunicipio', 'tmunicipio.id_municipio = organizaciones.id_municipio','left');
        $builder->join('tlocalidad', 'tlocalidad.id_localidad = organizaciones.id_localidad','left');
        $where = "representante like '%$buscar%' or nombre_organizacion like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }

     public function getAgrupaciones(){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $where = "id_organizacion like 'OR%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }

     
    

     public function getAll(){
      $builder = $this->db->table($this->table);
      $builder->select('organizaciones.id_organizacion,organizaciones.representante,
      organizaciones.nombre_organizacion,organizaciones.rfc,organizaciones.calle, 
      tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,organizaciones.created_at');
      $builder->join('tregion', 'tregion.id_region = organizaciones.id_region','left');
      $builder->join('tdistrito', 'tdistrito.id_distrito = organizaciones.id_distrito','left');
      $builder->join('tmunicipio', 'tmunicipio.id_municipio = organizaciones.id_municipio','left');
      $builder->join('tlocalidad', 'tlocalidad.id_localidad = organizaciones.id_localidad','left');


      $query = $builder->get();
      return $query->getResult();

   }

     public function getOrganizacion($id = null){

      $builder = $this->db->table($this->table);

      $builder->select('organizaciones.id_organizacion,organizaciones.representante,
      organizaciones.nombre_organizacion,organizaciones.rfc,organizaciones.calle,
      organizaciones.num_exterior,organizaciones.num_interior,organizaciones.cp,
      organizaciones.tel_fijo,organizaciones.tel_celular,organizaciones.correo,organizaciones.num_integrantes,
      organizaciones.hombres,organizaciones.mujeres,organizaciones.activo,organizaciones.id_region,
      organizaciones.id_distrito,organizaciones.id_municipio,organizaciones.id_localidad,
      organizaciones.created_at,organizaciones.updated_at,organizaciones.descripcion,
      organizaciones.tipo_org,organizaciones.tipo,organizaciones.id_rama,organizaciones.id_tecnica
      tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,
      ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama');
      
      $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = organizaciones.id_rama');
      $builder->join('sub_ramas_artesanales', 'sub_ramas_artesanales.id_subrama = organizaciones.id_tecnica');
      $builder->join('tregion', 'tregion.id_region = organizaciones.id_region');
      $builder->join('tdistrito', 'tdistrito.id_distrito = organizaciones.id_distrito');
      $builder->join('tmunicipio', 'tmunicipio.id_municipio = organizaciones.id_municipio');
      $builder->join('tlocalidad', 'tlocalidad.id_localidad = organizaciones.id_localidad');
     
      $where = "organizaciones.id_organizacion = '".$id."'";
      $builder->where($where);

      $query = $builder->get();
      return $query->getResult();

   }

   
public function actualizarRegistrosRamas($totales,$organizaciones){

   $actualiza = $this->db->table($this->table);
   
      for ($i=0; $i < $totales ; $i++) { 

          $actual = strval($organizaciones[$i]['id_organizacion']);
          $rama = $this->getRama($actual);
          if (empty($rama)) {
            $rama = 'NA_00_0000';
          }
          else{

            $rama = json_encode($rama[0]);
            $rama = json_decode($rama,true);
            $rama = $rama['id_rama'];
          }

          $actualiza->set('id_rama',$rama);
          $actualiza->where('id_organizacion',$actual);
          $actualiza->update();          
          
      }
   return 'exito';
   }

   
public function actualizarRegistrosTecnicas($totales,$organizaciones){

   $actualiza = $this->db->table($this->table);
   
      for ($i=0; $i < $totales ; $i++) { 

          $actual = strval($organizaciones[$i]['id_organizacion']);
          $rama = $this->getTecnica($actual);
          if (empty($rama)) {
            $rama = 'SR_NA_0000';
          }
          else{

            $rama = json_encode($rama[0]);
            $rama = json_decode($rama,true);
            $rama = $rama['id_subrama'];
          }

          $actualiza->set('id_tecnica',$rama);
          $actualiza->where('id_organizacion',$actual);
          $actualiza->update();          
          
      }
   return 'exito';
   }

   public function getRama($organizacion){

      $registros = $this->db->table('ramas_organizaciones');
      $registros->select('id_rama');
      $registros->where("id_organizacion = '".$organizacion."'"); 
      
      $query = $registros->get();
      return $query->getResult();

   }

   public function getTecnica($organizacion){

      $registros = $this->db->table('ramas_organizaciones');
      $registros->select('id_subrama');
      $registros->where("id_organizacion = '".$organizacion."'"); 
      
      $query = $registros->get();
      return $query->getResult();

   }
    
}