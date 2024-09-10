<?php 
namespace App\Models;

use CodeIgniter\Model;

class ArtesanoModel extends Model{
    protected $table      = 'artesanos';
    protected $primaryKey = 'id_artesano';
    protected $returnedType = 'array';
    protected $allowedFields = [
        'id_artesano','nombre', 'primer_apellido', 'segundo_apellido', 'sexo', 'fecha_nacimiento',
'edo_civil','curp','clave_ine','rfc','calle','num_exterior','num_interior','cp','id_region','id_distrito',
'id_municipio','id_localidad','seccion','tel_fijo','tel_celular','correo','redes_sociales','escolaridad',
'id_grupo','gpo_pertenencia','id_organizacion','id_materia_prima','id_venta_producto','id_tipo_comprador',
'id_rama','id_tecnica','id_materiaprima','id_canal','fecha_entrega_credencial',
'folio_cuis','foto','activo','nombre_archivo','comentarios','longitud','latitud','created_at','updated_at','id_lengua',
'telefono_recados','id_rama','id_tecnica','id_materiaprima','id_canal','fecha_entrega_credencial',
'proveedor'];


public function actualizarRegistrosRamas($totales,$artesanos){

   $actualiza = $this->db->table($this->table);
   
      for ($i=0; $i < $totales ; $i++) { 

          $actual = strval($artesanos[$i]['id_artesano']);
          $rama = $this->getRama($actual);
          if (empty($rama)) {
            $rama = 'SN';
          }
          else{

            $rama = json_encode($rama[0]);
            $rama = json_decode($rama,true);
            $rama = $rama['id_rama'];
          }

          $actualiza->set('id_rama',$rama);
          $actualiza->where('id_artesano',$actual);
          $actualiza->update();          
          
      }
   return 'exito';
   }

   
public function actualizarRegistrosSubRamas($totales,$artesanos){

   $actualiza = $this->db->table($this->table);
   
      for ($i=0; $i < $totales ; $i++) { 

          $actual = strval($artesanos[$i]['id_artesano']);
          $rama = $this->getSubRama($actual);
          if (empty($rama)) {
            $rama = 'SN';
          }
          else{

            $rama = json_encode($rama[0]);
            $rama = json_decode($rama,true);
            $rama = $rama['id_subrama'];
          }

          $actualiza->set('id_tecnica',$rama);
          $actualiza->where('id_artesano',$actual);
          $actualiza->update();          
          
      }
   return 'exito';
   }

   
public function actualizarRegistrosMaterias($totales,$artesanos){


   $actualiza = $this->db->table($this->table);
   

      for ($i=0; $i < $totales ; $i++) { 

          $actual = strval($artesanos[$i]['id_artesano']);
          $rama = $this->getMateriaPrima($actual);
          if (empty($rama)) {
            $rama = 'SN';
          }
          else{

            $rama = json_encode($rama[0]);
            $rama = json_decode($rama,true);
            $rama = $rama['id_materiap'];
          }

          $actualiza->set('id_materiaprima',$rama);
          $actualiza->where('id_artesano',$actual);
          $actualiza->update();          
          
      }

   return 'exito';
   
}


public function actualizarRegistrosCanales($totales,$artesanos){


   $actualiza = $this->db->table($this->table);
   

      for ($i=0; $i < $totales ; $i++) { 

          $actual = strval($artesanos[$i]['id_artesano']);
          $rama = $this->getTipoComprador($actual);
          if (empty($rama)) {
            $rama = 'SN';
          }
          else{

            $rama = json_encode($rama[0]);
            $rama = json_decode($rama,true);
            $rama = $rama['id_tipo_comprador'];
          }

          $actualiza->set('id_canal',$rama);
          $actualiza->where('id_artesano',$actual);
          $actualiza->update();          
          
      }
   return 'exito';
   }

   
public function actualizarRegistrosFechasCredenciales($totales,$artesanos){


   $actualiza = $this->db->table($this->table);
   

      for ($i=0; $i < $totales ; $i++) { 

          $actual = strval($artesanos[$i]['id_artesano']);
          $rama = $this->getEntregaCredencial($actual);
          if (empty($rama)) {
            $rama = 'SN';
          }
          else{

            $rama = json_encode($rama[0]);
            $rama = json_decode($rama,true);
            $rama = $rama['fecha_entrega'];
          }

          $actualiza->set('fecha_entrega_credencial',$rama);
          $actualiza->where('id_artesano',$actual);
          $actualiza->update();          
          
      }
   return 'exito';
   }


   public function getRama($artesano){

      $registros = $this->db->table('ramas_artesanos');
      $registros->select('id_rama');
      $registros->where("id_artesano = '".$artesano."'"); 
      
      $query = $registros->get();
      return $query->getResult();

   }

   public function getSubRama($artesano){

      $registros = $this->db->table('ramas_artesanos');
      $registros->select('id_subrama');
      $registros->where("id_artesano = '".$artesano."'"); 
      
      $query = $registros->get();
      return $query->getResult();

   }

   public function getMateriaPrima($artesano){

      $registros = $this->db->table('artesanos_materia_prima');
      $registros->select('id_materiap');
      $registros->where("id_artesano = '".$artesano."'"); 
      
      $query = $registros->get();
      return $query->getResult();

   }

   public function getTipoComprador($artesano){

      $registros = $this->db->table('artesanos_tipo_comprador');
      $registros->select('id_tipo_comprador');
      $registros->where("id_artesano = '".$artesano."'"); 
      
      $query = $registros->get();
      return $query->getResult();

   }
   public function getEntregaCredencial($artesano){

      $registros = $this->db->table('entrega_credencial');
      $registros->select('fecha_entrega');
      $registros->where("id_artesano = '".$artesano."'"); 
      
      $query = $registros->get();
      return $query->getResult();

   }



// ----------------------------------------------------------------------------


public function filtersFerias($municipio,$region,$distrito,$localidad,$rama){

   $builder = $this->db->table($this->table);

   $builder->select('artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
   artesanos.segundo_apellido,
   artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,
   ramas_artesanales.nombre_rama,tregion.region, tmunicipio.municipio,tlocalidad.localidad');
   
   $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = artesanos.id_rama');
  
   $builder->join('tregion', 'tregion.id_region = artesanos.id_region');
   $builder->join('tlocalidad', 'tlocalidad.id_localidad = artesanos.id_localidad');
   $builder->join('tmunicipio', 'tmunicipio.id_municipio = artesanos.id_municipio');
   $builder->join('tdistrito', 'tdistrito.id_distrito = artesanos.id_distrito');
 
   $where = "tregion.region like '%$region%' and tmunicipio.municipio like '%$municipio%' 
   and ramas_artesanales.nombre_rama like '%$rama%' and tdistrito.distrito like '%$distrito%'
   and tlocalidad.localidad like '%$localidad%' ";

   $builder->where($where);
   $query = $builder->get(700);
   return $query->getResult();

}

public function getListartesanos(){

   $builder = $this->db->table($this->table);

   $builder->select('artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
   artesanos.segundo_apellido,
   artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,
   artesanos.clave_ine,artesanos.rfc,artesanos.calle,artesanos.num_exterior,
   artesanos.num_interior,artesanos.cp,
   artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,
   artesanos.activo,artesanos.comentarios,
   artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
   artesanos.gpo_pertenencia, artesanos.id_organizacion,artesanos.created_at,
   artesanos.fecha_entrega_credencial,tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,
   ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama');
   
   $builder->join('tregion', 'tregion.id_region = artesanos.id_region','left');
   $builder->join('tdistrito', 'tdistrito.id_distrito = artesanos.id_distrito','left');
   $builder->join('tmunicipio', 'tmunicipio.id_municipio = artesanos.id_municipio','left');
   $builder->join('tlocalidad', 'tlocalidad.id_localidad = artesanos.id_localidad','left');
   $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = artesanos.id_rama','left');
   $builder->join('sub_ramas_artesanales', 'sub_ramas_artesanales.id_subrama = artesanos.id_tecnica','left');
   $builder->orderBy('id_artesano','desc');
   $query = $builder->get(400);

   return $query->getResult();

}

     public function searching($buscar,$region,$distrito,$municipio,$localidad,$rama,$tecnica,$tipo){

      $db = db_connect();

      if ($region == "") {
         $eqregion = "!=";
      }else{
         $eqregion = "=";
      }
      
      if ($distrito == "") {
         $eqdistrito = "!=";
      }else{
         $eqdistrito = "=";
      }
      
      if ($municipio == "") {
         $eqmunicipio = "!=";
      }else{
         $eqmunicipio = "=";
      }
      
      if ($localidad == "") {
         $eqlocalidad = "!=";
      }else{
         $eqlocalidad = "=";
      }
      
      if ($rama == "") {
         $eqrama = "!=";
      }else{
         $eqrama = "=";
      }
      
      if ($tecnica == "") {
         $eqtecnica = "!=";
      }else{
         $eqtecnica = "=";
      }
      
      $query = $db->query("
      
      select artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,artesanos.segundo_apellido,
      artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,artesanos.clave_ine,
      artesanos.rfc,artesanos.calle,artesanos.num_exterior,artesanos.num_interior,artesanos.cp,
      artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,artesanos.activo,artesanos.comentarios,
      artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
      artesanos.id_region, artesanos.id_distrito,artesanos.id_localidad,artesanos.id_localidad,
      artesanos.gpo_pertenencia, artesanos.id_organizacion,artesanos.created_at,artesanos.fecha_entrega_credencial,
      tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,
      ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama
      from artesanos
      left join tregion on tregion.id_region = artesanos.id_region 
      left join tdistrito on tdistrito.id_distrito = artesanos.id_distrito
      left join tmunicipio on tmunicipio.id_municipio  = artesanos.id_municipio
      left join tlocalidad on tlocalidad.id_localidad = artesanos.id_localidad
      left join ramas_artesanales on ramas_artesanales.id_rama = artesanos.id_rama
      left join sub_ramas_artesanales on sub_ramas_artesanales.id_subrama = artesanos.id_tecnica
      where 
      concat(artesanos.nombre,' ', artesanos.primer_apellido,' ', artesanos.segundo_apellido) like '$buscar%' and
      tregion.id_region $eqregion '$region' and 
      tdistrito.id_distrito $eqdistrito '$distrito' and 
      tmunicipio.id_municipio $eqmunicipio '$municipio' and 
      tlocalidad.id_localidad $eqlocalidad '$localidad' and 
      artesanos.id_rama $eqrama '$rama' and 
      artesanos.id_tecnica $eqtecnica '$tecnica' and 
      tdistrito.id_region $eqregion '$region' and
      tmunicipio.id_distrito $eqdistrito '$distrito' and
      tlocalidad.id_municipio $eqmunicipio '$municipio' 
      limit 600
      ");

      if ($tipo == 'Renovados') {
         
      $query = $db->query("
      select artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,artesanos.segundo_apellido,
      artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,artesanos.clave_ine,
      artesanos.rfc,artesanos.calle,artesanos.num_exterior,artesanos.num_interior,artesanos.cp,
      artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,artesanos.activo,artesanos.comentarios,
      artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
      artesanos.id_region, artesanos.id_distrito,artesanos.id_localidad,artesanos.id_localidad,
      artesanos.gpo_pertenencia, artesanos.id_organizacion,artesanos.created_at,artesanos.fecha_entrega_credencial,
      tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,
      ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama
      from artesanos
      left join tregion on tregion.id_region = artesanos.id_region 
      left join tdistrito on tdistrito.id_distrito = artesanos.id_distrito
      left join tmunicipio on tmunicipio.id_municipio  = artesanos.id_municipio
      left join tlocalidad on tlocalidad.id_localidad = artesanos.id_localidad
      left join ramas_artesanales on ramas_artesanales.id_rama = artesanos.id_rama
      left join sub_ramas_artesanales on sub_ramas_artesanales.id_subrama = artesanos.id_tecnica
      where 
      concat(artesanos.nombre,' ', artesanos.primer_apellido,' ', artesanos.segundo_apellido) like '$buscar%' and
      tregion.id_region $eqregion '$region' and 
      tdistrito.id_distrito $eqdistrito '$distrito' and 
      tmunicipio.id_municipio $eqmunicipio '$municipio' and 
      tlocalidad.id_localidad $eqlocalidad '$localidad' and 
      artesanos.id_rama $eqrama '$rama' and 
      artesanos.id_tecnica $eqtecnica '$tecnica' and 
      tdistrito.id_region $eqregion '$region' and
      tmunicipio.id_distrito $eqdistrito '$distrito' and
      tlocalidad.id_municipio $eqmunicipio '$municipio' and
      artesanos.fecha_entrega_credencial != '0000-00-00' limit 600
      ");

      }

      if ($tipo == 'Nuevos') {
         
      $query = $db->query("
      select artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,artesanos.segundo_apellido,
      artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,artesanos.clave_ine,
      artesanos.rfc,artesanos.calle,artesanos.num_exterior,artesanos.num_interior,artesanos.cp,
      artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,artesanos.activo,artesanos.comentarios,
      artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
      artesanos.id_region, artesanos.id_distrito,artesanos.id_localidad,artesanos.id_localidad,
      artesanos.gpo_pertenencia, artesanos.id_organizacion,artesanos.created_at,artesanos.fecha_entrega_credencial,
      tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,
      ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama
      from artesanos
      left join tregion on tregion.id_region = artesanos.id_region 
      left join tdistrito on tdistrito.id_distrito = artesanos.id_distrito
      left join tmunicipio on tmunicipio.id_municipio  = artesanos.id_municipio
      left join tlocalidad on tlocalidad.id_localidad = artesanos.id_localidad
      left join ramas_artesanales on ramas_artesanales.id_rama = artesanos.id_rama
      left join sub_ramas_artesanales on sub_ramas_artesanales.id_subrama = artesanos.id_tecnica
      where 
      concat(artesanos.nombre,' ', artesanos.primer_apellido,' ', artesanos.segundo_apellido) like '$buscar%' and
      tregion.id_region $eqregion '$region' and 
      tdistrito.id_distrito $eqdistrito '$distrito' and 
      tmunicipio.id_municipio $eqmunicipio '$municipio' and 
      tlocalidad.id_localidad $eqlocalidad '$localidad' and 
      artesanos.id_rama $eqrama '$rama' and 
      artesanos.id_tecnica $eqtecnica '$tecnica' and 
      tdistrito.id_region $eqregion '$region' and
      tmunicipio.id_distrito $eqdistrito '$distrito' and
      tlocalidad.id_municipio $eqmunicipio '$municipio' and
      artesanos.created_at >= '2023-01-01' limit 600
      ");

      }

     $query->getResult();

      
      return $query->getResult();
  
     }

    
     public function getNameRegion($id){
      $builder = $this->db->table('tregion');
      $builder->select('region');
      $where = "id_region = $id";

      $builder->where($where);

      $query = $builder->get();
      return $query->getResult();

   }

   public function getNameMunicipio($id){
      $builder = $this->db->table('tmunicipio');
      $builder->select('municipio');
      $where = "id_municipio = '$id'";

      $builder->where($where);

      $query = $builder->get();
      return $query->getResult();

   }



     public function getArtesano($id = null){

        $builder = $this->db->table($this->table);

        $builder->select('artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
        artesanos.segundo_apellido,
        artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,
        artesanos.clave_ine,artesanos.rfc,artesanos.calle,artesanos.num_exterior,
        artesanos.num_interior,artesanos.cp,artesanos.id_region,artesanos.id_distrito,artesanos.id_municipio,
        artesanos.id_localidad,artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,
        artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
        artesanos.gpo_pertenencia,
        artesanos.id_organizacion,artesanos.id_materia_prima,artesanos.id_venta_producto,
        artesanos.id_tipo_comprador,artesanos.folio_cuis,artesanos.activo,artesanos.foto,
        artesanos.comentarios,artesanos.nombre_archivo,
        artesanos.longitud,artesanos.latitud,artesanos.created_at,artesanos.updated_at,
        artesanos.telefono_recados,
        ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama,
        materia_prima.nombre as materiap,tipo_compradores.nombre as tipocomp, tregion.region,
        tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,grupos_etnicos.nombre_etnia,
        organizaciones.nombre_organizacion, lenguas_indigenas.id_lengua, lenguas_indigenas.lengua');

        
        $builder->join('organizaciones', 'organizaciones.id_organizacion = artesanos.id_organizacion','left');
        $builder->join('tregion', 'tregion.id_region = artesanos.id_region','left');
        $builder->join('tdistrito', 'tdistrito.id_distrito = artesanos.id_distrito','left');
        $builder->join('tmunicipio', 'tmunicipio.id_municipio = artesanos.id_municipio','left');
        $builder->join('tlocalidad', 'tlocalidad.id_localidad = artesanos.id_localidad','left');
        $builder->join('grupos_etnicos', 'grupos_etnicos.id_grupo = artesanos.id_grupo','left');
        $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = artesanos.id_rama','left');
        $builder->join('sub_ramas_artesanales', 'sub_ramas_artesanales.id_subrama = artesanos.id_tecnica','left');
        
        $builder->join('materia_prima', 'materia_prima.id_materiap = artesanos.id_materiaprima','left');

        $builder->join('tipo_compradores', 'tipo_compradores.id_tipo_comprador = artesanos.id_canal','left');
        $builder->join('lenguas_indigenas', 'lenguas_indigenas.id_lengua = artesanos.id_lengua','left');
        
        $where = "artesanos.id_artesano = '".$id."'";
        $builder->where($where);
  
        $query = $builder->get();
        return $query->getResult();
  
     }

     
     public function getArtesanoByCurp($curp = null){

      $builder = $this->db->table($this->table);

      $builder->select('artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
      artesanos.segundo_apellido,artesanos.curp,');
      
      $where = "artesanos.curp = '".$curp."'";
      $builder->where($where);
      
      $query = $builder->get();
      return $query->getResult();

   }

     public function getArtesanoCon($id = null){

      $builder = $this->db->table($this->table);

      $builder->select('artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
      artesanos.segundo_apellido,
      artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,
      artesanos.clave_ine,artesanos.rfc,artesanos.calle,artesanos.num_exterior,
      artesanos.num_interior,artesanos.cp,artesanos.id_region,artesanos.id_distrito,artesanos.id_municipio,
      artesanos.id_localidad,artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,
      artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
      artesanos.gpo_pertenencia,
      artesanos.id_organizacion,artesanos.id_materia_prima,artesanos.id_venta_producto,
      artesanos.id_tipo_comprador,artesanos.folio_cuis,artesanos.activo,artesanos.foto,
      artesanos.comentarios,artesanos.nombre_archivo,
      artesanos.longitud,artesanos.latitud,artesanos.created_at,artesanos.updated_at,
      ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama,
      materia_prima.nombre as materiap,tipo_compradores.nombre as tipocomp,
      tregion.region,tdistrito.distrito,tmunicipio.municipio,tlocalidad.localidad,grupos_etnicos.nombre_etnia,
      organizaciones.nombre_organizacion');
      
      $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = artesanos.id_rama');
      $builder->join('sub_ramas_artesanales', 'sub_ramas_artesanales.id_subrama = artesanos.id_tecnica');
      
    $builder->join('materia_prima', 'materia_prima.id_materiap = artesanos.id_materiaprima');
      $builder->join('organizaciones', 'organizaciones.id_organizacion = artesanos.id_organizacion', 'left');
      $builder->join('tregion', 'tregion.id_region = artesanos.id_region');
      $builder->join('tdistrito', 'tdistrito.id_distrito = artesanos.id_distrito');
      $builder->join('tmunicipio', 'tmunicipio.id_municipio = artesanos.id_municipio');
      $builder->join('tlocalidad', 'tlocalidad.id_localidad = artesanos.id_localidad');
      $builder->join('grupos_etnicos', 'grupos_etnicos.id_grupo = artesanos.id_grupo');
     $builder->join('tipo_compradores', 'tipo_compradores.id_tipo_comprador = artesanos.id_canal');
      
      
      $where = "artesanos.id_artesano = '".$id."'";
      $builder->where($where);

      $query = $builder->get();
      return $query->getResult();

   }

     public function getArtesanosExcel($periodo){

      $db = db_connect();

      if ($periodo == 'General') {
         $query = $db->query("select artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
         artesanos.segundo_apellido,
         artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,
         artesanos.clave_ine,artesanos.rfc,artesanos.calle,artesanos.num_exterior,
         artesanos.num_interior,artesanos.cp,
         artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,
         artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
         artesanos.gpo_pertenencia   
         from artesanos
    order by artesanos.id_artesano desc
   ");  
}else{
   $query = $db->query("select artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
         artesanos.segundo_apellido,
         artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,
         artesanos.clave_ine,artesanos.rfc,artesanos.calle,artesanos.num_exterior,
         artesanos.num_interior,artesanos.cp,
         artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,
         artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
         artesanos.gpo_pertenencia
           from artesanos
       where artesanos.created_at >= '$periodo-01-01' and artesanos.created_at <= '$periodo-12-31'
    order by artesanos.id_artesano desc
    ");
}
     
     $query->getResult();

      
      return $query->getResult();


   }

   public function getArtesanosExcel2($periodo){

      $db = db_connect();

      if ($periodo == 'General') {
         $query = $db->query("select artesanos.id_artesano,
         artesanos.id_organizacion,artesanos.created_at,artesanos.fecha_entrega_credencial,
         ramas_artesanales.nombre_rama,sub_ramas_artesanales.nombre_subrama,
         materia_prima.nombre as materiap, tdistrito.distrito, tregion.region
           from artesanos
       left join ramas_artesanales on ramas_artesanales.id_rama = artesanos.id_rama
       left join sub_ramas_artesanales on sub_ramas_artesanales.id_subrama = artesanos.id_tecnica
       left join materia_prima on materia_prima.id_materiap = artesanos.id_materiaprima
       left join tdistrito on tdistrito.id_distrito = artesanos.id_distrito
       left join tregion on tregion.id_region = artesanos.id_region
    order by artesanos.id_artesano desc
   ");  
}else{
   $query = $db->query("select artesanos.id_artesano,artesanos.id_organizacion,artesanos.created_at,
         artesanos.fecha_entrega_credencial, ramas_artesanales.nombre_rama,
         sub_ramas_artesanales.nombre_subrama, materia_prima.nombre as materiap, tdistrito.distrito, 
         tregion.region
           from artesanos
       left join ramas_artesanales on ramas_artesanales.id_rama = artesanos.id_rama
       left join sub_ramas_artesanales on sub_ramas_artesanales.id_subrama = artesanos.id_tecnica
       left join materia_prima on materia_prima.id_materiap = artesanos.id_materiaprima
       left join tdistrito on tdistrito.id_distrito = artesanos.id_distrito
       left join tregion on tregion.id_region = artesanos.id_region
       where artesanos.created_at >= '$periodo-01-01' and artesanos.created_at <= '$periodo-12-31'
    order by artesanos.id_artesano desc
    ");
}
     
     $query->getResult();

      
      return $query->getResult();


   }

   public function getArtesanosExcel3($periodo){

      $db = db_connect();
      if ($periodo == 'General') {
         $query = $db->query("select artesanos.id_artesano,
         tipo_compradores.nombre as tipocomp,
         tmunicipio.municipio, tlocalidad.localidad,
         grupos_etnicos.nombre_etnia, organizaciones.nombre_organizacion,organizaciones.id_region as region_org, 
         (select '.')  as region_name,  organizaciones.id_municipio as muni_org, 
         (select '.')  as fecha_entrega
   
           from artesanos
   
       left join tipo_compradores on tipo_compradores.id_tipo_comprador = artesanos.id_canal
       left join tmunicipio on tmunicipio.id_municipio = artesanos.id_municipio
       left join tlocalidad on tlocalidad.id_localidad = artesanos.id_localidad
       left join grupos_etnicos on grupos_etnicos.id_grupo = artesanos.id_grupo
       left join organizaciones on organizaciones.id_organizacion = artesanos.id_organizacion
   
       order by artesanos.id_artesano desc
   ");
      }else{
         $query = $db->query("select artesanos.id_artesano,
         tipo_compradores.nombre as tipocomp,
         tmunicipio.municipio, tlocalidad.localidad,
         grupos_etnicos.nombre_etnia, organizaciones.nombre_organizacion,organizaciones.id_region as region_org, 
         (select '.')  as region_name,  organizaciones.id_municipio as muni_org, 
         (select '.')  as fecha_entrega
   
           from artesanos
   
       left join tipo_compradores on tipo_compradores.id_tipo_comprador = artesanos.id_canal
       left join tmunicipio on tmunicipio.id_municipio = artesanos.id_municipio
       left join tlocalidad on tlocalidad.id_localidad = artesanos.id_localidad
       left join grupos_etnicos on grupos_etnicos.id_grupo = artesanos.id_grupo
       left join organizaciones on organizaciones.id_organizacion = artesanos.id_organizacion
       where artesanos.created_at >= '$periodo-01-01' and artesanos.created_at <= '$periodo-12-31'
       order by artesanos.id_artesano desc
      
       ");
      }
     
     
     $query->getResult();

      
      return $query->getResult();


   }


   public function getArtesanosExcelOrganizacion($periodo){
if ($periodo == 'General') {
 
   $builder = $this->db->table($this->table);
   $builder->select('artesanos.id_artesano,organizaciones.id_organizacion,organizaciones.nombre_organizacion,
   tregion.region, tmunicipio.municipio');

   $builder->join('organizaciones', 'organizaciones.id_organizacion = artesanos.id_organizacion', 'left');
   $builder->join('tregion', 'organizaciones.id_region = tregion.id_region', 'left');
   $builder->join('tmunicipio', 'organizaciones.id_municipio = tmunicipio.municipio', 'left');
   $builder->orderBy('artesanos.id_artesano','desc');
}else{

   $builder = $this->db->table($this->table);
   $builder->select('artesanos.id_artesano,organizaciones.id_organizacion,organizaciones.nombre_organizacion,
   tregion.region, tmunicipio.municipio');

   $builder->join('organizaciones', 'organizaciones.id_organizacion = artesanos.id_organizacion', 'left');
   $builder->join('tregion', 'organizaciones.id_region = tregion.id_region', 'left');
   $builder->join('tmunicipio', 'organizaciones.id_municipio = tmunicipio.municipio', 'left');
   $builder->where("artesanos.created_at >= '$periodo-01-01' and artesanos.created_at <= '$periodo-12-31'");
   $builder->orderBy('artesanos.id_artesano','desc');
}
     
      $query = $builder->get();
      return $query->getResult();

   }


     public function getArtesanoCredencial($id = null){

      $builder = $this->db->table($this->table);

      $builder->select('artesanos.id_artesano,artesanos.nombre,artesanos.primer_apellido,
      artesanos.segundo_apellido,
      artesanos.sexo,artesanos.fecha_nacimiento,artesanos.edo_civil,artesanos.curp,
      artesanos.clave_ine,artesanos.rfc,artesanos.calle,artesanos.num_exterior,
      artesanos.num_interior,artesanos.cp,artesanos.id_region,artesanos.id_distrito,artesanos.id_municipio,
      artesanos.id_localidad,artesanos.seccion,artesanos.tel_fijo,artesanos.tel_celular,
      artesanos.correo,artesanos.redes_sociales,artesanos.escolaridad,artesanos.id_grupo,
      artesanos.gpo_pertenencia,
      artesanos.id_organizacion,artesanos.id_materia_prima,artesanos.id_venta_producto,
      artesanos.id_tipo_comprador,artesanos.folio_cuis,artesanos.activo,artesanos.foto,
      artesanos.comentarios,artesanos.nombre_archivo,
      artesanos.longitud,artesanos.latitud,artesanos.created_at,artesanos.updated_at,
      ramas_artesanales.nombre_rama,tregion.region, tmunicipio.municipio,tlocalidad.localidad');
      
      $builder->join('ramas_artesanales', 'ramas_artesanales.id_rama = artesanos.id_rama');
     
      $builder->join('tregion', 'tregion.id_region = artesanos.id_region');
      $builder->join('tlocalidad', 'tlocalidad.id_localidad = artesanos.id_localidad');
      $builder->join('tmunicipio', 'tmunicipio.id_municipio = artesanos.id_municipio');

    
      
      $where = "artesanos.id_artesano = '".$id."'";
      $builder->where($where);

      $query = $builder->get();
      return $query->getResult();

   }

  
  

}