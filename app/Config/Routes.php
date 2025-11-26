<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API Routes - Parking de Coches
$routes->get('vehicles/search', 'Vehicles::search');
$routes->get('vehicles/estacionados', 'Vehicles::estacionados');
$routes->get('vehicles/estado', 'Vehicles::estado');
$routes->get('vehicles/matricula/(:segment)', 'Vehicles::porMatricula/$1');
$routes->resource('vehicles');

// Mantener rutas de posts para compatibilidad (opcional)
// $routes->get('posts/search', 'Posts::search');
// $routes->resource('posts');
