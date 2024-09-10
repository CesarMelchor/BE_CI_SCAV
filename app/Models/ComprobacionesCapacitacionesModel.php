<?php 
namespace App\Models;

use CodeIgniter\Model;

class ComprobacionesCapacitacionesModel extends Model{
    protected $table      = 'comprobaciones_capacitaciones';
    protected $primaryKey = 'id';
    protected $returnedType = 'array';
    protected $allowedFields = ['monto','updated_at','id_artesano','id_accion','created_at'];

    
    public function searching($buscar){

        $builder = $this->db->table($this->table);

        $builder->select('
        comprobaciones_capacitaciones.id, artesanos.id_artesano,
        comprobaciones_capacitaciones.monto,artesanos.nombre,artesanos.primer_apellido,
        artesanos.segundo_apellido,artesanos.curp, acciones_capacitaciones.nombre as nombreaccion,
        acciones_capacitaciones.annio as annioaccion,
        programas_capacitaciones.nombre_programa as nombreprograma ,trimestres_capacitaciones.id as trimestreid');

        $builder->join('artesanos', 'artesanos.id_artesano = comprobaciones_capacitaciones.id_artesano','left');
        $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = comprobaciones_capacitaciones.id_accion','left');
        $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa','left');
        $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre','left');
     
        $where = "concat(artesanos.nombre,' ', artesanos.primer_apellido,' ', artesanos.segundo_apellido) 
        like '%$buscar%' or curp like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }
 
    public function findAllRegisters(){

        $builder = $this->db->table($this->table);

        $builder->select('
        comprobaciones_capacitaciones.id, artesanos.id_artesano,
        comprobaciones_capacitaciones.monto,artesanos.nombre,artesanos.primer_apellido,
        artesanos.segundo_apellido,artesanos.curp, acciones_capacitaciones.nombre as nombreaccion,
        acciones_capacitaciones.annio as annioaccion,
        programas_capacitaciones.nombre_programa as nombreprograma ,trimestres_capacitaciones.id as trimestreid,
        comprobaciones_capacitaciones.created_at,comprobaciones_capacitaciones.updated_at');

        $builder->join('artesanos', 'artesanos.id_artesano = comprobaciones_capacitaciones.id_artesano','left');
        $builder->join('acciones_capacitaciones', 'acciones_capacitaciones.id = comprobaciones_capacitaciones.id_accion','left');
        $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa','left');
        $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre','left');
     
  
        $query = $builder->get();
        return $query->getResult();
  
     }
  
}