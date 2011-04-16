<?php

class MY_Controller extends CI_Controller
{
  function __construct() {
    parent::__construct();
    $this->form_validation->set_error_delimiters('<div class="err miniRound">', '</div>');
    log_message('debug', 'MY_Controller class initialized');
  }

  function redirectWithError($errmsg, $path = '') {
    $this->redirect_with_error($errmsg, $path);
  }

  function redirect_with_error($errmsg, $path = '') {
    $this->session->set_flashdata('err', $errmsg);
    redirect($path);
  }

  function redirectWithMessage($msg, $path = '') {
    $this->redirect_with_message($msg, $path);
  }

  function redirect_with_message($msg, $path = '') {
    $this->session->set_flashdata('msg', $msg);
    redirect($path);
  }
}

