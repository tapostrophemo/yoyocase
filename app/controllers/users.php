<?php

class Users extends MY_Controller
{
  function __construct() {
    parent::__construct();
    $this->load->model('User');
    log_message('debug', 'Users class initialized');
  }

  function index() {
    $this->load->view('pageTemplate', array('content' => $this->load->view('users/account', null, true)));
  }

  function preferences() {
    $user = $this->User->find_by_username($this->session->userdata('username'));
    if (!$user) {
      $this->redirect_with_error('You must be signed in to set your preferences.', '/login');
    }

    $data = array('user' => $user);
    if (!$this->form_validation->run('user_preferences')) {
      $this->load->view('pageTemplate', array('content' => $this->load->view('users/preferences', $data, true)));
    }
    else {
      $this->User->update($this->session->userdata('username'),
        $this->input->post('email'),
        $this->input->post('password'));
      $this->redirect_with_message('Preferences updated.', '/account');
    }
  }

  function update_flickr_info_1() {
    $this->load->library('Flickr_API');
    $this->flickr_api->authenticate('read');
  }

  function update_flickr_info_2() {
    // TODO: detect errors/access denials
    $this->load->library('Flickr_API');
    $auth = $this->flickr_api->auth_getToken($_REQUEST['frob']); // TODO: fake this out so it can be tested
    $nsid = $auth['auth']['user']['nsid'];
    $this->User->update($this->session->userdata('username'), null, null, $nsid);
    $this->session->set_userdata('flickr_userid', $nsid);
    $this->redirect_with_message('Flicker user id updated.', '/account');
  }

  // TODO: photobucket API interaction needs tested too...

  function update_photobucket_info_1() {
    $this->load->library('Photobucket_API');
    $this->photobucket_api->authenticate();
  }

  function update_photobucket_info_2() {
    $this->load->library('Photobucket_API');
    if (($pbUsername = $this->photobucket_api->verify_login())) {
      $this->User->update($this->session->userdata('username'), null, null, null, $pbUsername);
      $this->session->set_userdata('photobucket_username', $pbUsername);
      $this->redirect_with_message('Photobucket username updated.', '/account');
    }
    else {
      $this->redirect_with_error('Problem authenticating to Photobucket', '/account');
    }
  }

  function listAll() {
    $this->load->model('Misc');
    $data = array(
      'users' => $this->User->find_all(),
      'facts' => $this->Misc->fun_facts());
    $this->load->view('pageTemplate', array('title' => 'view yoyo collections', 'content' => $this->load->view('users/list', $data, true)));
  }

  function gallery($username) {
    $username = urldecode($username);
    $yoyos = array();
    $user = $this->User->find_by_username($username);
    $title = 'view yoyo collections';
    $username = htmlspecialchars($username);
    if ($user) {
      $title = "$username's yoyo collection";
      $this->load->model('Yoyo');
      $yoyos = $this->Yoyo->find_all_by_userid($user->id);
      $content = $this->load->view('users/gallery', array('username' => $username, 'yoyos' => $yoyos), true);
    }
    else {
      $content = $this->load->view('users/notFound', array('username' => $username), true);
    }
    $this->load->view('pageTemplate', array('title' => $title, 'content' => $content));
  }
}

