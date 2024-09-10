<?php 
namespace App\Models;

use CodeIgniter\Model;

class LenguasModel extends Model{
    protected $table      = 'lenguas_indigenas';
    protected $primaryKey = 'id_lengua';
    protected $returnedType = 'array';
    protected $allowedFields = ['lengua'];

    
}