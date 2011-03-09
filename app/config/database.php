<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = "default";
$active_record = TRUE;

// settings for normal, run-time operation of the site
$db['default']['hostname'] = "localhost"; // TODO: configure for PROD deploy
$db['default']['username'] = "yoyocase_user"; // TODO: configure for PROD deploy
$db['default']['password'] = "bob"; // TODO: configure for PROD deploy
$db['default']['database'] = "yoyocase"; // TODO: configure for PROD deploy
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

