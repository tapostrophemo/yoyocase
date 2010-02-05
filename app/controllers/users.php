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
      $this->redirectWithError('You must be signed in to set your preferences.', '/login');
    }

    $data = array('user' => $user);
    if (!$this->form_validation->run('user_preferences')) {
      $this->load->view('pageTemplate', array('content' => $this->load->view('users/preferences', $data, true)));
    }
    else {
      $this->User->update($this->session->userdata('username'), array('email' => $this->input->post('email')));
      $this->redirect_with_message('Preferences updated.', '/account');
    }
  }

  function update_flickr_info_1() {
    $this->load->library('Flickr_API');
    $this->flickr_api->authenticate('read');
  }

  function update_flickr_info_2() {
    $this->load->library('Flickr_API');
    $auth = $this->flickr_api->auth_getToken($_REQUEST['frob']);
    $nsid = $auth['auth']['user']['nsid'];
    $this->User->update($this->session->userdata('username'), array('flickr_userid' => $nsid));
    $this->redirect_with_message('Flicker user id updated.', '/account');
  }
}

