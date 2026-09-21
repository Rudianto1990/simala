<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::index');

$routes->group('auth', static function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::index');
    $routes->post('ceklogin', 'Auth::ceklogin');
    $routes->get('register', 'Auth::register');
    $routes->post('prosesregis', 'Auth::prosesregis');
    $routes->get('logout', 'Auth::logout');
});

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'ceklogin']);
$routes->get('dashboard/kalijapat', 'Dashboard::kalijapat', ['filter' => 'ceklogin']);
$routes->get('dashboard/dermaga_a', 'Dashboard::dermaga_a', ['filter' => 'ceklogin']);
$routes->get('dashboard/dermaga_b', 'Dashboard::dermaga_b', ['filter' => 'ceklogin']);
$routes->get('dashboard/dermaga_c', 'Dashboard::dermaga_c', ['filter' => 'ceklogin']);

$routes->group('cctv', ['filter' => 'ceklogin'], static function ($routes) {
    $routes->get('/', 'Cctv::index');
    $routes->get('create', 'Cctv::create');
    $routes->post('store', 'Cctv::store');
    $routes->get('show/(:num)', 'Cctv::show/$1');
    $routes->get('snapshot/(:num)', 'Cctv::snapshot/$1');
    $routes->get('edit/(:num)', 'Cctv::edit/$1');
    $routes->post('update/(:num)', 'Cctv::update/$1');
    $routes->get('delete/(:num)', 'Cctv::delete/$1');
});

$routes->group('form', ['filter' => 'ceklogin'], static function ($routes) {
    $routes->get('/', 'Form::index');
    $routes->get('createalat', 'Form::createalat');
    $routes->post('simpan', 'Form::simpan');
    $routes->get('dataalat', 'Form::dataalat');
    $routes->get('dataalat/export/excel', 'Form::exportExcel');
    $routes->get('dataalat/export/pdf', 'Form::exportPdf');
    $routes->get('update/(:segment)', 'Form::update/$1');
    $routes->post('prosesupdate/(:segment)', 'Form::prosesupdate/$1');
    $routes->get('hapus/(:num)', 'Form::hapus/$1');
    $routes->get('detail/(:segment)', 'Form::detail/$1');
});

$routes->get('alat/(:segment)', 'Form::detail/$1');
