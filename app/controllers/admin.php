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
    $data = array('accounts' => $this->User->findAll());
    $this->load->view('pageTemplate', array('content' => $this->load->view('admin/accounts', $data, true)));
  }

  function userDetail($id) {
    $this->load->model('User');
    $user = $this->User->findById($id);
    $this->load->view('admin/userDetail', array('user' => $user));
  }

  function registrationActivationReport() {
    $this->load->model('Report');
    $content = '<h2>Registration and activation</h2>';

    $data = $this->Report->registration();
    $content .= $this->load->view('admin/genericReport', array(
      'title' => 'Registrations',
      'data' => $data,
      'fields' => array('date' => 'Date', 'num_registrations' => 'Registrations'),
    ), true);

    $data = $this->Report->activation();
    $content .= $this->load->view('admin/genericReport', array(
      'title' => 'Activations / Retentions',
      'note' => 'counts as "activation" in current month and "retention" in past months',
      'data' => $data,
      'fields' => array('month' => 'Month', 'num_activations' => 'Activations'),
    ), true);

    $this->load->view('pageTemplate', array('content' => $content));
  }
}

