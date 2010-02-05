<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = 'pages';
$route['scaffolding_trigger'] = "";

$route['privacy'] = 'pages/privacy';
$route['credits'] = 'pages/credits';

$route['register'] = 'site/register';
$route['login'] = 'site/login';
$route['logout'] = 'site/logout';

$route['account'] = 'users';
$route['preferences'] = 'users/preferences';
$route['update_flickr_info_1'] = 'users/update_flickr_info_1';
$route['update_flickr_info_2'] = 'users/update_flickr_info_2';

