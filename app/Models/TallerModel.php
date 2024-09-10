<?php 
namespace App\Models;

use CodeIgniter\Model;

class TallerModel extends Model{
    protected $table      = 'talleres';
    protected $primaryKey = 'id_artesano';
    protected $returnedType = 'array';
    protected $allowedFields = [];
     protected $useTimestamps = true;
     protected $createdField = 'created_at';
     protected $updatedField = 'updated_at';



    
}