<?php 
namespace App\Models;

use CodeIgniter\Model;

class LocalidadModel extends Model{
    protected $table      = 'tlocalidad';
    protected $primaryKey = 'id_localidad';
    protected $returnedType = 'array';
    protected $allowedFields = ['localidad', 'id_municipio'];
    
     public function searching($buscar){
        $builder = $this->db->table($this->table);
        $builder->select('tlocalidad.id_localidad,tlocalidad.localidad,tlocalidad.id_municipio,
        tmunicipio.municipio');
        $builder->join('tmunicipio', 'tmunicipio.id_municipio = tlocalidad.id_municipio');
        $where = "tlocalidad.localidad
        like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get(100);
        return $query->getResult();
  
     }

     public function getAllLocalidades($id = null){

        $builder = $this->db->table($this->table);
        $builder->select('tlocalidad.id_localidad,tlocalidad.localidad,tlocalidad.id_municipio,
        tmunicipio.municipio');
        $builder->join('tmunicipio', 'tmunicipio.id_municipio = tlocalidad.id_municipio');
        
        
        $query = $builder->get(150);
        
        return $query->getResult();
  
     }

     public function getAllLocalidadesByMun($municipio){

      $builder = $this->db->table($this->table);
      $builder->select('tlocalidad.id_localidad,tlocalidad.localidad,tlocalidad.id_municipio,
      tmunicipio.municipio');
      $builder->join('tmunicipio', 'tmunicipio.id_municipio = tlocalidad.id_municipio');
      $builder->where("tlocalidad.id_municipio='".$municipio."'");
      
      $query = $builder->get(150);
      
      return $query->getResult();

   }

    
}