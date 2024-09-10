<?php 
namespace App\Models;

use CodeIgniter\Model;

class RamaModel extends Model{
    protected $table      = 'ramas_artesanales';
    protected $primaryKey = 'id_rama';
    protected $returnedType = 'array';
    protected $allowedFields = ['id_rama','nombre_rama', 'descripcion', 'activo','created_at','updated_at'];

     public function searching($buscar){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $where = "nombre_rama
        like '%$buscar%' and activo = 1";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }
    
}