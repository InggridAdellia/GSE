<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['dashboard'] = 'dashboard';
$route['ids']              = 'ids/index';
$route['ids/unblock/(:num)']      = 'ids/unblock/$1';
$route['ids/block_manual/(:any)'] = 'ids/block_manual/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['scan/get_gse_data/(.+)'] = 'scan/get_gse_data/$1';