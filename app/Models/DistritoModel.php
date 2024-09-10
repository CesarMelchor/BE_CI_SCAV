<?php 
namespace App\Models;

use CodeIgniter\Model;

class DistritoModel extends Model{
    protected $table      = 'tdistrito';
    protected $primaryKey = 'id_distrito';
    protected $returnedType = 'array';
    protected $allowedFields = ['distrito', 'id_region'];

   
    public function searching($buscar){
        $builder = $this->db->table($this->table);
        $builder->select('tdistrito.id_distrito,tdistrito.distrito,tdistrito.id_region,
        tregion.region');
        $builder->join('tregion', 'tregion.id_region = tdistrito.id_region');
        $where = "tdistrito.distrito
        like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }

     public function getAllDistritos(){

        $builder = $this->db->table($this->table);
        $builder->select('tdistrito.id_distrito,tdistrito.distrito,tdistrito.id_region,
        tregion.region');
        $builder->join('tregion', 'tregion.id_region = tdistrito.id_region');
        
        $query = $builder->get();
        
        return $query->getResult();
  
     }

     public function getAllDistritosByRegion($region){

      $builder = $this->db->table($this->table);
      $builder->select('tdistrito.id_distrito,tdistrito.distrito,tdistrito.id_region,
      tregion.region');
      $builder->join('tregion', 'tregion.id_region = tdistrito.id_region');
      $builder->where("tdistrito.id_region = '".$region."'");
      $query = $builder->get();
      
      return $query->getResult();

   }
 
    
}