<?php

class Admin extends MY_Controller
{
  function __construct() {
    parent::__construct();

    if (!$this->session->userdata('logged_in')) {
      $this->redirect_with_error('Administrators must be logged in', 'login');
    }

    if (!$this->session->userdata('is_admin')) {
      $this->redirect_with_error('Authorized administrators only', 'account');
    }
  }

  function index() {
    $this->load->view('pageTemplate', array('content' => $this->load->view('admin/menu', null, true)));
  }

  function accounts() {
    $this->load->model('User');
    $data = array('accounts' => $this->User->find_all());
    $this->load->view('pageTemplate', array('content' => $this->load->view('admin/accounts', $data, true)));
  }
}

