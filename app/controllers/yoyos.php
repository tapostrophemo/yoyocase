<?php

class Yoyos extends MY_Controller
{
  function __construct() {
    parent::__construct();
    $this->load->model('Yoyo');
    $this->load->model('Photo');
    log_message('debug', 'Yoyos class initialized');
  }

  function index() {
    $data = array('yoyos' => $this->Yoyo->find_all_by_userid($this->session->userdata('userid')));
    $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/collection', $data, true)));
  }

  function add() {
    if (!$this->form_validation->run('yoyo')) {
      $data = array(
        'yoyos' => $this->Yoyo->find_all_by_userid($this->session->userdata('userid')),
        'thumbnails' => $this->_getFlickrThumbnails($this->session->userdata('userid'), $this->session->userdata('flickr_userid')),
        'cancel_url' => '/yoyos');
      $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/new', $data, true)));
    }
    else {
      $data = array(
        'manufacturer' => $this->input->post('manufacturer'),
        'country' => $this->input->post('country'),
        'model_year' => $this->input->post('model_year'),
        'model_name' => $this->input->post('model_name'));
      $yoyoid = $this->Yoyo->add_for_user($this->session->userdata('userid'), $data);
      if ($yoyoid) {
        if ($this->input->post('photos')) { // TODO: how to validate array of inputs? can CI_Form_Validation do it?
          foreach ($this->input->post('photos') as $url) {
            $this->Photo->add_for_yoyo($yoyoid, array('url' => $url));
          }
        }
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
      'photos' => $this->Photo->find_all_by_yoyoid($yoyo->id),
      'yoyos' => $this->Yoyo->find_all_by_userid($this->session->userdata('userid')),
      'cancel_url' => '/yoyos');
    $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/view', $data, true)));
  }

  function edit($yoyoid) {
    $this->load->view('pageTemplate', array('content' => 'TODO: edit form'));
  }

  function _getFlickrThumbnails($userid, $flickr_userid) { // TODO: make this a helper function(?)
    $thumbnails = array();
    if ($flickr_userid) {
      $this->load->library('Flickr_API');
      $response = $this->flickr_api->photos_search(array('user_id' => $flickr_userid));
      foreach ($response['photos']['photo'] as $p) {
        // TODO: filter out images we've already attached
        $thumbnails[] = array(
          'thumbnail' => $this->flickr_api->get_photo_url($p['id'], $p['farm'], $p['server'], $p['secret'], 'thumbnail'),
          'url' => $this->flickr_api->get_photo_url($p['id'], $p['farm'], $p['server'], $p['secret']));
      }
    }
    return $thumbnails;
  }
}

