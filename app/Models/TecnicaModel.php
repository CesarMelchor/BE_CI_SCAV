<?php 
namespace App\Models;

use CodeIgniter\Model;

class TecnicaModel extends Model{
    protected $table      = 'sub_ramas_artesanales';
    protected $primaryKey = 'id_subrama';
    protected $returnedType = 'array';
    protected $allowedFields = ['id_subrama','id_rama', 'nombre_subrama', 'descripcion', 
    'variedad', 'activo','created_at','updated_at'];
     

     public function searching($buscar){
        $builder = $this->db->table($this->table);
        
      $builder->select('sub_ramas_artesanales.id_subrama,sub_ramas_artesanales.id_rama,
      sub_ramas_artesanales.nombre_subrama,sub_ramas_artesanales.variedad,ramas_artesanales.nombre_rama');
      $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = sub_ramas_artesanales.id_rama');

        $where = "nombre_subrama
        like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }

     public function getAllTecnicas($id = null){

      $builder = $this->db->table($this->table);
      $builder->select('sub_ramas_artesanales.id_subrama,sub_ramas_artesanales.id_rama,
      sub_ramas_artesanales.nombre_subrama,sub_ramas_artesanales.variedad,ramas_artesanales.nombre_rama');
      $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = sub_ramas_artesanales.id_rama');
      
      
      $query = $builder->get();
      return $query->getResult();

   }

    
}