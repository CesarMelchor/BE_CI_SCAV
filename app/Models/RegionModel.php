<?php 
namespace App\Models;

use CodeIgniter\Model;

class RegionModel extends Model{
    protected $table      = 'tregion';
    protected $primaryKey = 'id_region';
    protected $returnedType = 'array';
    protected $allowedFields = ['region,tipo'];

    public function searching($buscar){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $where = "region
        like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }
    
}