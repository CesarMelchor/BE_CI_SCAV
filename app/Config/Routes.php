<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('scav/v1', ['namespace' => 'App\Controllers\API'], function(
    $routes
){

//artesanos

$routes->get('artesanos', 'Artesano::getAll');
$routes->get('artesanos/actualizar/registros/ramas', 'Artesano::actualizarRamas');
$routes->get('artesanos/actualizar/registros/subramas', 'Artesano::actualizarSubRamas');
$routes->get('artesanos/actualizar/registros/materias', 'Artesano::actualizarMaterias');
$routes->get('artesanos/actualizar/registros/canales', 'Artesano::actualizarCanales');
$routes->get('artesanos/actualizar/registros/fechas_credenciales', 'Artesano::actualizarFechasCredenciales');
$routes->get('artesanos/all', 'Artesano::getAllArtesanos');
$routes->get('artesanos/home', 'Artesano::getListHome');
$routes->get('artesanos/image', 'Artesano::image');
$routes->get('artesanos/search', 'Artesano::search');
$routes->get('artesanos/reporte', 'Artesano::reporte');
$routes->get('artesanos/detail', 'Artesano::detail');
$routes->get('artesanos/detail/consulta', 'Artesano::detailCon');
$routes->get('artesanos/detailCredencial', 'Artesano::detailCredencial');
$routes->post('artesano/update/(:any)', 'Artesano::update/$1');
$routes->post('artesano/credencial/(:any)', 'Artesano::entrega/$1');
$routes->post('artesano/create', 'Artesano::create');
$routes->post('artesanos/baja/(:any)', 'Artesano::baja/$1');
$routes->get('artesano/offline/data', 'Artesano::obtenerInformacionArtesanoExcel');
$routes->get('artesano/detail/curp', 'Artesano::detailByCurp');


//organizaciones
$routes->get('organizaciones', 'Organizacion::getOrganizaciones');
$routes->get('agrupacionesAll', 'Organizacion::getAll');
$routes->get('organizaciones/search', 'Organizacion::search');
$routes->post('organizacion/update/(:any)', 'Organizacion::update/$1');
$routes->post('organizacion/create', 'Organizacion::create');
$routes->get('organizacion/detail', 'Organizacion::detail');
$routes->get('organizaciones/actualizar/registros/ramas', 'Organizacion::actualizarRamas');
$routes->get('organizaciones/actualizar/registros/tecnicas', 'Organizacion::actualizarTecnicas');
$routes->get('organizaciones/excel', 'Organizacion::obtenerOrganizacionesExcel');

//usuarios
$routes->get('users', 'UserScav::getAll');
$routes->get('user/search', 'UserScav::search');
$routes->post('user/login', 'UserScav::login');
$routes->post('user/create', 'UserScav::create');
$routes->post('user/update/(:any)', 'UserScav::update/$1');
$routes->post('user/updatePass/(:any)', 'UserScav::updatePass/$1');

//regiones
$routes->get('regiones', 'Region::getAll');
$routes->get('regiones/search', 'Region::search');
$routes->post('region/update/(:any)', 'Region::update/$1');
$routes->get('regiones/excel', 'Region::obtenerRegionesExcel');


//distritos
$routes->get('distritos', 'Distrito::getAll');
$routes->get('distritos/(:any)', 'Distrito::getAllByReg/$1)');
$routes->get('distritos/search', 'Distrito::search');
$routes->post('distrito/update/(:alphanum)', 'Distrito::update/$1');
$routes->get('distritos/excel', 'Distrito::obtenerDistritosExcel');



//municipios
$routes->get('municipios', 'Municipio::getAll');
$routes->get('municipios/(:any)', 'Municipio::getAllByDis/$1)');
$routes->get('municipios/search', 'Municipio::search');
$routes->post('municipio/update/(:num)', 'Municipio::update/$1');
$routes->get('municipios/excel', 'Municipio::obtenerMunicipiosExcel');


//localidades
$routes->get('localidades/all', 'Localidad::getAll');
$routes->get('localidades/(:any)', 'Localidad::getAllByMun/$1)');
$routes->get('localidades/all', 'Localidad::getAllLocalidades');
$routes->get('localidades/search', 'Localidad::search');
$routes->post('localidad/update/(:num)', 'Localidad::update/$1');
$routes->get('localidades/excel', 'Localidad::obtenerLocalidadesExcel');



//grupos etnicos
$routes->get('etnias', 'Etnia::getAll');
$routes->get('etnias/excel', 'Etnia::obtenerEtniasExcel');


//ramas artesanales
$routes->get('ramas', 'Rama::getAll');
$routes->get('ramas/excel', 'Rama::obtenerRamasExcel');
$routes->get('ramas/search', 'Rama::search');
$routes->post('rama/update/(:any)', 'Rama::update/$1');
$routes->post('rama/create', 'Rama::create');
$routes->get('ramas/excel', 'Rama::obtenerRamasExcel');


//tecnicas artesanales
$routes->get('tecnicas', 'Tecnica::getAll');
$routes->get('tecnicas/excel', 'Tecnica::obtenerTecnicasExcel');
$routes->get('tecnicas/search', 'Tecnica::search');
$routes->post('tecnica/update/(:any)', 'Tecnica::update/$1');
$routes->post('tecnica/create', 'Tecnica::create');
$routes->get('tecnicas/excel', 'Tecnica::obtenerTecnicasExcel');


//tipos de comprador
$routes->get('compradores', 'Comprador::getAll');
$routes->get('compradores/excel', 'Comprador::obtenerCompradoresExcel');

//origen de materias primas
$routes->get('materias', 'MateriaPrima::getAll');
$routes->get('materias/excel', 'MateriaPrima::obtenerMateriasExcel');

//lenguas
$routes->get('lenguas', 'Lenguas::getAll');
$routes->get('lenguas/excel', 'Lenguas::obtenerLenguasExcel');

//trimestres
$routes->get('trimestres', 'TrimestresCapacitaciones::getAll');


//programas capacitaciones
$routes->get('programas', 'ProgramasCapacitaciones::getAll');

//acciones

$routes->get('acciones/home', 'AccionesCapacitaciones::getAllHome');
$routes->post('accion/update/(:any)', 'AccionesCapacitaciones::update/$1');
$routes->get('acciones', 'AccionesCapacitaciones::getAll');
$routes->post('acciones/create', 'AccionesCapacitaciones::create');
$routes->get('acciones/search', 'AccionesCapacitaciones::search');
$routes->get('acciones/excel', 'AccionesCapacitaciones::obtenerAccionesExcel');
$routes->get('acciones/offline/artesanos', 'AccionesCapacitaciones::obtenerArtesanosAccionesExcel');

//inscripciones

$routes->get('inscripcion/constancia', 'InscripcionesCapacitaciones::constancia');
$routes->get('inscripciones', 'InscripcionesCapacitaciones::getAll');
$routes->post('inscripcion/create', 'InscripcionesCapacitaciones::create');
$routes->get('inscripciones/search', 'InscripcionesCapacitaciones::search');
$routes->post('inscripcion/update/(:any)', 'InscripcionesCapacitaciones::update/$1');
$routes->put('inscripciones/update/asistencias', 'InscripcionesCapacitaciones::updateAsistencias');
$routes->get('inscripciones/reporte', 'InscripcionesCapacitaciones::reporte');
$routes->get('inscripciones/getInfo', 'InscripcionesCapacitaciones::getInfo');
$routes->get('inscripciones/excel', 'InscripcionesCapacitaciones::obtenerInscripcionesExcel');
$routes->get('inscripciones/offline/agregar', 'InscripcionesCapacitaciones::agregarInscripcionExcel');
$routes->get('inscripciones/constancias/artesanos', 'InscripcionesCapacitaciones::obtenerArtesanosInscripciones');

//comprobaciones
$routes->get('comprobaciones', 'ComprobacionesCapacitaciones::getAll');
$routes->post('comprobaciones/create', 'ComprobacionesCapacitaciones::create');
$routes->get('comprobaciones/search', 'ComprobacionesCapacitaciones::search');
$routes->post('comprobaciones/update/(:any)', 'ComprobacionesCapacitaciones::update/$1');

});





