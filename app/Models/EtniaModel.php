<?php 
namespace App\Models;

use CodeIgniter\Model;

class EtniaModel extends Model{
    protected $table      = 'grupos_etnicos';
    protected $primaryKey = 'id_grupo';
    protected $returnedType = 'array';
    protected $allowedFields = ['nombre_etnia'];
     protected $useTimestamps = true;
     protected $createdField = 'created_at';
     protected $updatedField = 'updated_at';


    
}