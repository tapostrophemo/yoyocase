<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = 'pages';

$route['404_override'] = 'pages/fourYoFour';

$route['privacy'] = 'pages/privacy';
$route['tos'] = 'pages/tos';
$route['credits'] = 'pages/credits';
$route['tour'] = 'pages/tour';
$route['help'] = 'pages/help';

$route['register'] = 'site/register';
$route['login'] = 'site/login';
$route['logout'] = 'site/logout';
$route['passwordreset'] = 'site/passwordreset';
$route['passreset/(:any)'] = 'site/passreset/$1';
$route['newpass'] = 'site/newpass';

$route['account'] = 'users';
$route['preferences'] = 'users/preferences';
$route['update_flickr_info_1'] = 'users/update_flickr_info_1';
$route['update_flickr_info_2'] = 'users/update_flickr_info_2';
$route['update_photobucket_info_1'] = 'users/update_photobucket_info_1';
$route['update_photobucket_info_2'] = 'users/update_photobucket_info_2';

$route['yoyo'] = 'yoyos/add';
$route['yoyo/(:num)'] = 'yoyos/view/$1';
$route['yoyo/(:num)/edit'] = 'yoyos/edit/$1';
$route['yoyo/(:num)/delete'] = 'yoyos/delete/$1';
$route['photo/(:num)/delete'] = 'yoyos/removePhoto/$1';

$route['galleries'] = 'users/listAll';
$route['yoyoreport'] = 'users/collectionReport';
$route['yoyos/(:any)'] = 'users/gallery/$1';

