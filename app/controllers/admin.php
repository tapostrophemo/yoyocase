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

    $content = $this->load->view('admin/genericReport', array(
      'title' => 'Registration, Activation and Retention',
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

    foreach ($data as $photo) {
      $ext = pathinfo($photo->url, PATHINFO_EXTENSION);
      $thumb = $path . $photo->id . '.' . $ext;
      $s .= '<img src="/thumbs/' . $photo->id . '.' . $ext . '"/>';

      $wget = sprintf('wget %s -O %s', $photo->url, $thumb);
      $img1 = sprintf('composite -gravity Center %s %s %s', $overlay, $thumb, $thumb);
      $img2 = sprintf('mogrify -gravity Center -crop 111x111+0+0 +repage %s', $thumb);
      `$wget; $img1; $img2`;

      $max = $photo->id;
    }

    $this->Photo->setMaxThumbnail($max);
    $this->load->view('pageTemplate', array('content' => "<h2>New Thumbnails</h2>New max: $max<br/>$s"));
  }
}

