<?php 
namespace App\Models;

use CodeIgniter\Model;

class TrimestresCapacitacionesModel extends Model{
    protected $table      = 'trimestres_capacitaciones';
    protected $primaryKey = 'id';
    protected $returnedType = 'array';
    protected $allowedFields = ['mes_inicio', 'mes_termino','activo','created_at','updated_at'];

    
}