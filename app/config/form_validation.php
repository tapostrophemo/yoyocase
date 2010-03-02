<?php

$config = array(
  'site_register' => array(
    array('field' => 'username', 'label' => 'username', 'rules' => 'trim|required|max_length[255]|xss_clean'),
    array('field' => 'password', 'label' => 'password', 'rules' => 'trim|required'),
    array('field' => 'email', 'label' => 'email', 'rules' => 'trim|required|max_length[255]|valid_email|xss_clean')
  ),

  'site_login' => array(
    array('field' => 'username', 'label' => 'username', 'rules' => 'trim|required|max_length[255]|xss_clean'),
    array('field' => 'password', 'label' => 'password', 'rules' => 'trim|required'),
    array('field' => 'account', 'label' => 'account', 'rules' => 'callback__is_registered_user')
  ),

  'user_preferences' => array(
    array('field' => 'email', 'label' => 'email', 'rules' => 'trim|required|max_length[255]|valid_email|xss_clean'),
    array('field' => 'password', 'label' => 'password', 'rules' => 'trim'),
    array('field' => 'passconf', 'label' => 'confirm password', 'rules' => 'trim|matches[password]')
  ),

  'yoyo' => array(
    array('field' => 'manufacturer', 'label' => 'manufacturer', 'rules' => 'trim|max_length[255]|xss_clean'),
    array('field' => 'country', 'label' => 'country', 'rules' => 'trim|max_length[255]|xss_clean'),
    array('field' => 'model_year', 'label' => 'model year', 'rules' => 'trim|integer|max_length[4]'),
    array('field' => 'model_name', 'label' => 'model name', 'rules' => 'trim|required|max_length[255]|xss_clean')
  )
);

