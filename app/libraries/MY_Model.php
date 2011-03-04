<?php

class MY_Model extends Model
{
  function __construct() {
    parent::__construct();
    $this->load->helper('date');
  }

  function _now() {
    return mdate('%Y-%m-%d %H:%i:%s', time());
  }
}

