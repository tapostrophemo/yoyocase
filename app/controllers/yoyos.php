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
        $this->_savePhotos($yoyoid);
        $this->redirect_with_message('Yoyo added to collection successfully', 'yoyos');
      }
      else {
        echo 'TODO: handle problem where yoyo not saved on create (go back and try again?)';
      }
    }
  }

  function view($yoyoid) {
    $yoyo = $this->_findYoyo($yoyoid);
    $data = array(
      'yoyo' => $yoyo,
      'photos' => $this->Photo->find_all_by_yoyoid($yoyoid),
      'yoyos' => $this->Yoyo->find_all_by_userid($this->session->userdata('userid')),
      'cancel_url' => '/yoyos');
    $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/view', $data, true)));
  }

  // TODO: disallow editing yoyos that don't belong to current user

  function edit($yoyoid) {
    if (!$this->form_validation->run('yoyo')) {
      $yoyo = $this->_findYoyo($yoyoid);
      $data = array(
        'yoyo' => $yoyo,
        'photos' => $this->Photo->find_all_by_yoyoid($yoyoid),
        'yoyos' => $this->Yoyo->find_all_by_userid($this->session->userdata('userid')),
        'thumbnails' => $this->_getFlickrThumbnails($this->session->userdata('userid'), $this->session->userdata('flickr_userid')),
        'cancel_url' => '/yoyos');
      $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/edit', $data, true)));
    }
    else {
      $data = array(
        'manufacturer' => $this->input->post('manufacturer'),
        'country' => $this->input->post('country'),
        'model_year' => $this->input->post('model_year'),
        'model_name' => $this->input->post('model_name'));
      $rval = $this->Yoyo->update($yoyoid, $data);
      if ($rval) {
        $this->_savePhotos($yoyoid);
        $this->redirect_with_message('Yoyo saved successfully', "yoyo/$yoyoid");
      }
      else {
        echo 'TODO: handle problem where yoyo not saved on update (go back and try again?)';
      }
    }
  }

  function removePhoto($photoid) {
    $photo = $this->Photo->find_by_id($photoid);
    $yoyo = $this->Yoyo->find_by_id($photo->yoyo_id);
    if ($photo && $yoyo && $yoyo->user_id == $this->session->userdata('userid')) {
      $this->Photo->delete($photoid);
      redirect("yoyo/{$photo->yoyo_id}/edit");
    }
    else {
      $this->redirect_with_error("You cannot remove photos from another user's yoyos");
    }
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

  function _findYoyo($yoyoid) {
    $yoyo = $this->Yoyo->find_by_id($yoyoid);
    if (!$yoyo) {
      $this->redirect_with_error('Yoyo not found', 'yoyos');
    }
    return $yoyo;
  }

  function _savePhotos($yoyoid) {
    if ($this->input->post('photos')) { // TODO: how to validate array of inputs? can CI_Form_Validation do it?
      foreach ($this->input->post('photos') as $url) {
        $this->Photo->add_for_yoyo($yoyoid, array('url' => $url));
      }
    }
  }
}

