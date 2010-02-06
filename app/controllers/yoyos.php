<?php

class Yoyos extends MY_Controller
{
  function __construct() {
    parent::__construct();
    $this->load->model('Yoyo');
    log_message('debug', 'Yoyos class initialized');
  }

  function index() {
    $data = array('yoyos' => $this->Yoyo->find_by_userid($this->session->userdata('userid')));
    $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/collection', $data, true)));
  }

  function add() {
    if (!$this->form_validation->run('yoyo')) {
      $data = array(
        'yoyos' => $this->Yoyo->find_by_userid($this->session->userdata('userid')),
        'cancel_url' => '/yoyos');
      $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/new', $data, true)));
    }
    else {
      $data = array(
        'manufacturer' => $this->input->post('manufacturer'),
        'country' => $this->input->post('country'),
        'model_year' => $this->input->post('model_year'),
        'model_name' => $this->input->post('model_name'));
      $rval = $this->Yoyo->add_for_user($this->session->userdata('userid'), $data);
      if ($rval) {
        // TODO: save photos
        $this->redirect_with_message('Yoyo added to collection successfully', 'yoyos');
      }
      else {
        echo 'TODO: handle problem where yoyo not saved (go back and try again?)';
      }
    }
  }

  function view($yoyoid) {
    $yoyo = $this->Yoyo->find_by_id($yoyoid);
    if (!$yoyo) {
      $this->redirect_with_error('Yoyo not found', 'yoyos');
    }

    $data = array(
      'yoyo' => $yoyo,
      'yoyos' => $this->Yoyo->find_by_userid($this->session->userdata('userid')),
      'cancel_url' => '/yoyos');
    $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/view', $data, true)));
  }

  function edit($yoyoid) {
    $this->load->view('pageTemplate', array('content' => 'TODO: edit form'));
  }
}

