<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// you can customize this view
// $routes->set404Override(function() {
//     echo view('app/public/error_404.html'); 
// });
$routes->set404Override(function() {
    echo file_get_contents(FCPATH . 'public/error_404.html');
});

// Default route
$routes->get('/', 'Home::index');
$routes->get('/Home', function() {
    return redirect()->to('/');
});



// Custom routes
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// Auth & Dashboard
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attempt');
$routes->get('/logout', 'Auth::logout');
//$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('dashboard', 'Auth::dashboard');

// Registration
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::store');
//$routes->post('/auth/addRole', 'Auth::addRole');
$routes->post('auth/addUserByAdmin', 'Auth::addUserByAdmin');

$routes->post('auth/updateUserRole', 'Auth::updateUserRole');
$routes->post('auth/deleteUser', 'Auth::deleteUser');
//$routes->post('auth/updateUserRole', 'Auth::updateUserRole');

$routes->get('manage-users', 'Auth::manageUsers');
$routes->post('restrictUser', 'Auth::restrictUser');



$routes->get('restricted-users', 'Auth::restrictedUsers');
$routes->post('auth/unrestrictUser', 'Auth::unrestrictUser');
$routes->post('auth/deleteUserPermanent', 'Auth::deleteUserPermanent');



$routes->get('settings', 'Auth::settings');
$routes->post('auth/updateSettings', 'Auth::updateSettings');


$routes->get('auth/generateDefaultPassword/(:num)', 'Auth::generateDefaultPassword/$1');

