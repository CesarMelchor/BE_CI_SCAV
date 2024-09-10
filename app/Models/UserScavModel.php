<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserScavModel extends Model{
    protected $table      = 'usuarios_scav';
    protected $primaryKey = 'id_usuario';
    protected $returnedType = 'array';
    protected $allowedFields = ['email', 'password', 'rol', 'activo', 
    'ap_paterno', 'ap_materno', 'nombre', 'telefono','tipo'];

     public function searching($buscar,$tipo){
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $where = "concat(nombre,' ', ap_paterno,' ', ap_materno) 
        like '%$buscar%' or email like '%$buscar%' and tipo='".$tipo."'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }

    
}