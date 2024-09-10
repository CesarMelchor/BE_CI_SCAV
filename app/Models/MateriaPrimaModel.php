<?php 
namespace App\Models;

use CodeIgniter\Model;

class MateriaPrimaModel extends Model{
    protected $table      = 'materia_prima';
    protected $primaryKey = 'id_materiap';
    protected $returnedType = 'array';
    protected $allowedFields = ['nombre', 'descripcion'];

    
}