<?php 
namespace App\Models;

use CodeIgniter\Model;

class CompradorModel extends Model{
    protected $table      = 'tipo_compradores';
    protected $primaryKey = 'id_tipo_comprador';
    protected $returnedType = 'array';
    protected $allowedFields = ['nombre', 'descripcion'];


 
    
}