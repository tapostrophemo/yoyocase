<?php

class Users extends MY_Controller
{
  function index() {
    $this->load->view('pageTemplate', array('content' => $this->load->view('users/account', null, true)));
  }

  function preferences() {
    $this->load->view('pageTemplate', array('content' => 'TODO: user preferences/settings page'));
  }
}

