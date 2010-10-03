<?php

class MY_Controller extends Controller
{
  function __construct() {
    parent::Controller();
    $this->form_validation->set_error_delimiters('<div class="err miniRound">', '</div>');
    log_message('debug', 'MY_Controller class initialized');
  }

  function redirect_with_error($errmsg, $path = '') {
    $this->session->set_flashdata('err', $errmsg);
    redirect($path);
  }

  function redirect_with_message($msg, $path = '') {
    $this->session->set_flashdata('msg', $msg);
    redirect($path);
  }
}

