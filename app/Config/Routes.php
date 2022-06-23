<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Admin');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


// home url
$routes->get("/", "Admin::index");

// menu penyakit
$routes->get("/data-penyakit", "Admin::penyakit");
$routes->get("/data-penyakit/form_penyakit", "Admin::form_tambah_data_penyakit");
$routes->post("/data-penyakit/tambah", "Admin::tambah_data_penyakit");
$routes->get("/data-penyakit/hapus/(:any)", "Admin::hapus_data_penyakit/$1");
$routes->post("/data-penyakit/penanganan/(:any)", "Admin::get_data_penanganan/$1");

// menu gejala
$routes->get("/data-gejala", "Admin::gejala");
$routes->post("/data-gejala/tambah-gejala", "Admin::tambah_gejala");
$routes->get("/data-gejala/hapus/(:any)", "Admin::hapus_gejala/$1");
$routes->post("/data-gejala/semua-gejala", "Admin::semua_gejala");
$routes->post("/data-gejala/diagnosis", "Admin::semua_gejala");
// menu pakar
$routes->get("/pakar", "Admin::pakar");
$routes->post("/pakar/set-pengetahuan", "Admin::set_pakar");
$routes->get("/pakar/lupakan/(:any)", "Admin::hapus_pengetahuan/$1");
$routes->post("/pakar/hasil-diagnosa", "Admin::Diagnosis_forward_chaining");
// middleware login
$routes->get("/login-to-sistem-pakar", "Admin::Login");
$routes->post("/tambah-admin-baru", "Admin::tambah_admin");
$routes->post("/login", "Admin::auth_login");
$routes->get("/logout-from-administrator", "Admin::logout");

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
