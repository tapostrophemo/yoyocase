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

  function accounts($sort = null) {
    $this->load->model('User');
    if (!$sort) {
      $data = array('accounts' => $this->User->findAll());
    }
    else if (User::isValidSortKey($sort)) {
      $data = array('accounts' => $this->User->findAllSorted($sort));
    }
    else {
      $data = array('accounts' => array());
    }
    $this->load->view('pageTemplate', array('content' => $this->load->view('admin/accounts', $data, true)));
  }

  function userDetail($id) {
    $this->load->model('User');
    $user = $this->User->findById($id);
    $this->load->view('admin/userDetail', array('user' => $user));
  }

  function deleteUser($id) {
    $this->load->model('User');
    $this->load->model('Yoyo');
    $yoyos = $this->Yoyo->findAllByUserid($id);
    foreach ($yoyos as $yoyo) {
      $this->Yoyo->archive($yoyo->id);
    }
    $this->User->archive($id);
    $this->redirectWithMessage('user deleted', 'admin/accounts');
  }

  function registrationActivationReport() {
    $this->load->model('Report');

    $content = $this->load->view('admin/genericReport', array(
      'title' => 'Registration, activation and retention',
      'note' => 'counts as "activation" in curent month, "retention" in past months',
      'data' => $this->Report->registrationAndActivation(),
      'fields' => array(
        'date' => 'Month',
        'num_registrations' => 'Registrations',
        'num_activations' => 'Activations/Retentions')), true);

    $this->load->view('pageTemplate', array('content' => $content));
  }
  function checkThumbnails() {
    $this->load->model('Photo');
    $data['max'] = $this->Photo->getMaxThumbnail();
    $data['photos'] = $this->Photo->getUnThumbed($data['max']);
    $this->load->view('pageTemplate', array('content' => $this->load->view('admin/pendingThumbs', $data, true)));
  }

  function generateThumbnails($max) {
    $docroot = $this->input->server('DOCUMENT_ROOT');
    $path = $docroot . '/thumbs/';
    $overlay = $docroot . '/res/cornerOverlay.gif';
    $s = '';

    $this->load->model('Photo');
    $data = $this->Photo->getUnThumbed($max);

    $status = array();
    foreach ($data as $photo) {
      $ext = pathinfo($photo->url, PATHINFO_EXTENSION);
      $thumb = $path . $photo->id . '.' . $ext;
      $s .= '<img src="/thumbs/' . $photo->id . '.' . $ext . '"/>';

      $wget = sprintf('wget %s -O %s 2>&1', $photo->url, $thumb);
      $img1 = sprintf('composite -gravity Center %s %s %s 2>&1', $overlay, $thumb, $thumb);
      $img2 = sprintf('mogrify -gravity Center -crop 111x111+0+0 %s 2>&1', $thumb);

      $this->_execCmd($wget, $status);
      $this->_execCmd($img1, $status);
      $this->_execCmd($img2, $status);

      $max = $photo->id;
    }

    $this->Photo->setMaxThumbnail($max);
    $content = "<h2>New Thumbnails</h2>New max: $max<br/>$s";
    if (!empty($status)) {
      $content .= '<br/>errors:<br/><pre>' . print_r($status, true) . '</pre>';
    }
    $this->load->view('pageTemplate', array('content' => $content));
  }

  function _execCmd($cmd, &$status) {
    $retval = -1;
    $lines = array();
    exec($cmd, $lines, $retval);
    if ($retval != 0) {
      $status[$photo->id][] = "command [$wget] returned $retval";
      $status[$photo->id][] = $lines;
    }
  }
}

