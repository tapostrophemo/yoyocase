<?php

class Yoyos extends MY_Controller
{
  function __construct() {
    parent::__construct();

    if (!$this->session->userdata('logged_in')) {
      $this->redirect_with_error('You must be logged in to add/edit yoyos', 'login');
    }

    $this->load->helper('thumbnail');

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
        'cancel_url' => '/yoyos');
      $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/new', $data, true)));
    }
    else {
      $data = $this->_get_form_data();
      $yoyoid = $this->Yoyo->add_for_user($this->session->userdata('userid'), $data);
      if ($yoyoid) {
        $this->_savePhotos($yoyoid);
        $this->_saveAcquisition($yoyoid);
        $this->redirect_with_message('Yoyo added to collection successfully', 'yoyos');
      }
      else {
        echo 'TODO: handle problem where yoyo not saved on create (go back and try again?)';
      }
    }
  }

  function _get_form_data() {
    return array(
      'manufacturer' => $this->input->post('manufacturer'),
      'mod' => $this->input->post('mod'),
      'country' => $this->input->post('country'),
      'model_year' => $this->input->post('model_year') == "" ? null : $this->input->post('model_year'),
      'model_name' => $this->input->post('model_name'),
      'serialnum' => $this->input->post('serialnum'),
      'condition' => $this->input->post('condition'),
      'value' => $this->input->post('value') == "" ? null : $this->input->post('value'),
      'notes' => $this->input->post('notes'));
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

  function edit($yoyoid) {
    if (!$this->form_validation->run('yoyo')) {
      $yoyo = $this->_findYoyo($yoyoid);
      if ($yoyo->user_id != $this->session->userdata('userid')) {
        $this->redirect_with_error("You cannot update another user's yoyos", 'account');
      }

      $data = array(
        'yoyo' => $yoyo,
        'photos' => $this->Photo->find_all_by_yoyoid($yoyoid),
        'yoyos' => $this->Yoyo->find_all_by_userid($this->session->userdata('userid')),
        'cancel_url' => '/yoyos');
      $this->load->view('pageTemplate', array('content' => $this->load->view('yoyos/edit', $data, true)));
    }
    else {
      $data = $this->_get_form_data();
      $rval = $this->Yoyo->update($yoyoid, $data);
      if ($rval) {
        $this->_savePhotos($yoyoid);
        $this->_updateAcquisition($yoyoid);
        $this->redirect_with_message('Yoyo saved successfully', "yoyo/$yoyoid");
      }
      else {
        echo 'TODO: handle problem where yoyo not saved on update (go back and try again?)';
      }
    }
  }

  function delete($yoyoid) {
    $yoyo = $this->Yoyo->findById($yoyoid);
    if ($yoyo && $yoyo->user_id == $this->session->userdata('userid')) {
      if ($this->input->post('archive')) {
        $this->Yoyo->archive($yoyoid);
        $this->redirectWithMessage('Yoyo archived and deleted', 'yoyos');
      }
      else {
        $this->Yoyo->delete($yoyoid);
        $this->redirectWithMessage('Yoyo deleted', 'yoyos');
      }
    }
    else {
      $this->redirectWithError('Only users are allowed to delete their own yoyos', 'account');
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

  function _saveAcquisition($yoyoid) {
    if ($this->_hasAcquisitionInfo()) {
      $this->Yoyo->saveAcquisition($this->session->userdata('userid'), $yoyoid,
        $this->input->post('acq_date'),
        $this->input->post('acq_type'),
        $this->input->post('acq_party'),
        $this->input->post('acq_price') == "" ? null : $this->input->post('acq_price'));
    }
  }

  function _updateAcquisition($yoyoid) {
    if ($this->_hasAcquisitionInfo()) {
      $this->Yoyo->updateAcquisition($this->session->userdata('userid'), $yoyoid,
        $this->input->post('acq_date'),
        $this->input->post('acq_type'),
        $this->input->post('acq_party'),
        $this->input->post('acq_price') == "" ? null : $this->input->post('acq_price'));
    }
  }

  function _hasAcquisitionInfo() {
    return
      $this->input->post('acq_date') ||
      $this->input->post('acq_type') ||
      $this->input->post('acq_party') ||
      $this->input->post('acq_price');
  }

  function _is_valid_condition($str) {
    if (empty($str) || in_array($str, array('mint', 'excellent', 'good', 'fair', 'poor'))) {
      return true;
    }

    $this->form_validation->set_message('_is_valid_condition', 'Please choose from available conditions.');
    return false;
  }

  function _yyyy_mm_dd_format($str) {
    if (empty($str) || preg_match('/^\d\d\d\d-\d\d?-\d\d?$/', $str)) {
      return true;
    }

    $this->form_validation->set_message('_yyyy_mm_dd_format', 'The %s field must be in YYYY-MM-DD format.');
    return false;
  }

  function _is_valid_acquisition_type($str) {
    if (empty($str) || in_array($str, array('purchase', 'trade', 'gift'))) {
      return true;
    }

    $this->form_validation->set_message('_is_valid_acquisition_type', 'Please choose from available acquisition methods.');
    return false;
  }
}

