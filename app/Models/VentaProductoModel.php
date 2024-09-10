<?php 
namespace App\Models;

use CodeIgniter\Model;

class VentaProductoModel extends Model{
    protected $table      = 'venta_productos';
    protected $primaryKey = 'id_venta';
    protected $returnedType = 'array';
    protected $allowedFields = ['nombre', 'descripcion'];


 
    
}