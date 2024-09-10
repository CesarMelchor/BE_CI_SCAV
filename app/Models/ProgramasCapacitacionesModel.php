<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProgramasCapacitacionesModel extends Model{
    protected $table      = 'programas_capacitaciones';
    protected $primaryKey = 'id';
    protected $returnedType = 'array';
    protected $allowedFields = ['nombre_programa','created_at','updated_at'];

 
    
}