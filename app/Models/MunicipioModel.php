<?php 
namespace App\Models;

use CodeIgniter\Model;

class MunicipioModel extends Model{
    protected $table      = 'tmunicipio';
    protected $primaryKey = 'id_municipio';
    protected $returnedType = 'array';
    protected $allowedFields = ['municipio', 'id_distrito','tipo'];
     public function searching($buscar){
        $builder = $this->db->table($this->table);
        $builder->select('tmunicipio.id_municipio,tmunicipio.municipio,tmunicipio.id_distrito,tdistrito.distrito');
        $builder->join('tdistrito', 'tdistrito.id_distrito = tmunicipio.id_distrito');
        $where = "tmunicipio.municipio
        like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }

     public function getAllMunicipios($id = null){

        $builder = $this->db->table($this->table);
        $builder->select('tmunicipio.id_municipio,tmunicipio.municipio,tmunicipio.id_distrito,tdistrito.distrito,tmunicipio.tipo');
        $builder->join('tdistrito', 'tdistrito.id_distrito = tmunicipio.id_distrito');
        
        
        $query = $builder->get();
        return $query->getResult();
  
     }

     public function getAllMunicipiosByDistrito($distrito){

      $builder = $this->db->table($this->table);
      $builder->select('tmunicipio.id_municipio,tmunicipio.municipio,tmunicipio.id_distrito,tdistrito.distrito,tmunicipio.tipo');
      $builder->join('tdistrito', 'tdistrito.id_distrito = tmunicipio.id_distrito');
      $builder->where("tmunicipio.id_distrito='".$distrito."'");
      
      $query = $builder->get();
      return $query->getResult();

   }

    
}