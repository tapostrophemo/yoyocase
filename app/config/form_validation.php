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
    array('field' => 'account', 'label' => 'account', 'rules' => 'callback__validate_login')
  ),

  'site_resetpass' => array(
    array('field' => 'username', 'label' => 'username', 'rules' => 'trim|required|max_length[255]|xss_clean'),
    array('field' => 'account', 'label' => 'account', 'rules' => 'callback__is_registered_user')
  ),

  'site_passwordresetform' => array(
    array('field' => 'password', 'label' => 'password', 'rules' => 'trim|required'),
    array('field' => 'passconf', 'label' => 'confirm password', 'rules' => 'trim|required|matches[password]')
  ),

  'user_preferences' => array(
    array('field' => 'email', 'label' => 'email', 'rules' => 'trim|required|max_length[255]|valid_email|xss_clean'),
    array('field' => 'password', 'label' => 'password', 'rules' => 'trim'),
    array('field' => 'passconf', 'label' => 'confirm password', 'rules' => 'trim|matches[password]')
  ),

  'yoyo' => array(
    array('field' => 'model_name', 'label' => 'model name', 'rules' => 'trim|required|max_length[255]|xss_clean'),
    array('field' => 'manufacturer', 'label' => 'manufacturer', 'rules' => 'trim|max_length[255]|xss_clean'),
    array('field' => 'mod', 'label' => 'modder', 'rules' => 'trim|max_length[255]|xss_clean'),
    array('field' => 'country', 'label' => 'country', 'rules' => 'trim|max_length[255]|xss_clean'),
    array('field' => 'model_year', 'label' => 'model year', 'rules' => 'trim|integer|max_length[4]'),
    array('field' => 'serialnum', 'label' => 'serial number', 'rules' => 'trim|max_length[64]|xss_clean'),
    array('field' => 'condition', 'label' => 'condition', 'rules' => 'trim|callback__is_valid_condition'),
    array('field' => 'value', 'label' => 'value', 'rules' => 'trim|numeric|max_length[15]'),
    array('field' => 'acq_date', 'label' => 'acquisition date', 'rules' => 'trim|callback__yyyy_mm_dd_format'),
    array('field' => 'acq_type', 'label' => 'acquisition method', 'rules' => 'trim|callback__is_valid_acquisition_type'),
    array('field' => 'acq_party', 'label' => 'acquired from', 'rules' => 'trim|max_length[255]|xss_clean'),
    array('field' => 'acq_price', 'label' => 'acquisition price', 'rules' => 'trim|numeric|max_length[15]'),
    array('field' => 'notes', 'label' => 'notes', 'rules' => 'trim|xss_clean')
  )
);

