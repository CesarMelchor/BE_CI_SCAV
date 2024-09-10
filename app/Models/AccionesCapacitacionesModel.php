<?php 
namespace App\Models;

use CodeIgniter\Model;

class AccionesCapacitacionesModel extends Model{
    protected $table      = 'acciones_capacitaciones';
    protected $primaryKey = 'id';
    protected $returnedType = 'array';
    protected $allowedFields = ['id_programa', 'area','nombre','capacitador','capacitador2','cargo','cargo2','texto_constancia',
    'objetivo','poblacion_objetivo','duracion','nivel','id_trimestre','annio',
    'created_at','updated_at'];

    public function searching($buscar){

        $builder = $this->db->table($this->table);

        $builder->select('acciones_capacitaciones.id,acciones_capacitaciones.id_programa,
        acciones_capacitaciones.area,acciones_capacitaciones.nombre,acciones_capacitaciones.capacitador,
        acciones_capacitaciones.objetivo,acciones_capacitaciones.poblacion_objetivo,
        acciones_capacitaciones.duracion,acciones_capacitaciones.nivel,acciones_capacitaciones.id_trimestre,
        acciones_capacitaciones.annio,programas_capacitaciones.nombre_programa,trimestres_capacitaciones.meses');

        $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa');
        $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre');
    
        $where = " acciones_capacitaciones.nombre like '%$buscar%'";
 
        $builder->where($where);
 
        $query = $builder->get();
        return $query->getResult();
  
     }

    public function getAccionesHome(){

        $builder = $this->db->table($this->table);

        $builder->select('acciones_capacitaciones.id,acciones_capacitaciones.id_programa,
        acciones_capacitaciones.area,acciones_capacitaciones.nombre,acciones_capacitaciones.capacitador,
        acciones_capacitaciones.objetivo,acciones_capacitaciones.poblacion_objetivo,
        acciones_capacitaciones.duracion,acciones_capacitaciones.nivel,acciones_capacitaciones.id_trimestre,
        acciones_capacitaciones.annio,programas_capacitaciones.nombre_programa,trimestres_capacitaciones.meses,
        acciones_capacitaciones.texto_constancia');

        $builder->join('programas_capacitaciones', 'programas_capacitaciones.id = acciones_capacitaciones.id_programa');
        $builder->join('trimestres_capacitaciones', 'trimestres_capacitaciones.id = acciones_capacitaciones.id_trimestre');
    
  
        $query = $builder->get();
        return $query->getResult();
  
     }
  
}