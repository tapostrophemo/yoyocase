<?php

class Cron extends Controller
{
  function __construct() {
    parent::__construct();

    if ($this->input->ip_address() != '127.0.0.1') {
      header($this->input->server('SERVER_PROTOCOL') . ' 403 Forbidden');
      echo '<html><head><title>403 Forbidden</title></head><body><h1>403 Forbidden</h1></body></html>';
      echo $this->input->ip_address() . '<br>';
      exit();
    }
  }

  function expirePasswordResetTokens() {
    $this->load->model('User');
    if ($this->User->expirePerishableTokens()) {
      echo '<html><body>' . __FUNCTION__ . ' completed</body></html>';
    }
    else {
      echo '<html><body>' . __FUNCTION__ . ' failed</body></html>';
    }
  }
}

