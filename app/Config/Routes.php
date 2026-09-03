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

$routes->group('form', ['filter' => 'ceklogin'], static function ($routes) {
    $routes->get('/', 'Form::index');
    $routes->get('createalat', 'Form::createalat');
    $routes->post('simpan', 'Form::simpan');
    $routes->get('dataalat', 'Form::dataalat');
    $routes->get('update/(:segment)', 'Form::update/$1');
    $routes->post('prosesupdate/(:segment)', 'Form::prosesupdate/$1');
    $routes->get('hapus/(:num)', 'Form::hapus/$1');
    $routes->get('detail/(:segment)', 'Form::detail/$1');
});

$routes->get('alat/(:segment)', 'Form::detail/$1');
