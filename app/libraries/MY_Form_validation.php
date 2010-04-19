<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
  function can_short_circut($key, $message = '') {
    if (count($this->_error_array) > 0) {
       $this->set_message($key, $message);
       return true;
    }
    return false;
  }
}

