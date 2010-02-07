<?php

class Site extends MY_Controller
{
  function __construct() {
    parent::__construct();
    $this->load->model('User');
    log_message('debug', 'Site class initialized');
  }

  function register() {
    if (!$this->form_validation->run('site_register')) {
      $this->load->view('pageTemplate', array('content' => $this->load->view('site/register', null, true)));
    }
    else {
      $newUserId = $this->User->register(
        $this->input->post('username'),
        $this->input->post('password'),
        $this->input->post('email'));
      if (null == $newUserId) {
        $this->redirect_with_error('That username is already taken.', '/register');
      }

      $this->session->set_userdata('username', $this->input->post('username'));
      $this->session->set_userdata('userid', $newUserId);
      $this->session->set_userdata('logged_in', true);
      $this->redirect_with_message('Welcome to <tt>yoyocase.net</tt>!', '/preferences');
    }
  }

  function login() {
    if (!$this->form_validation->run('site_login')) {
      $this->load->view('pageTemplate', array('content' => $this->load->view('site/login', null, true)));
    }
    else {
      $user = $this->User->mark_login($this->input->post('username'));
      $this->session->set_userdata('username', $this->input->post('username'));
      $this->session->set_userdata('userid', $user->id);
      $this->session->set_userdata('flickr_userid', $user->flickr_userid);
      $this->session->set_userdata('logged_in', true);
      $this->redirect_with_message('Welcome back!', '/account');
    }
  }

  function _is_registered_user($junk) {
    if (!$this->User->is_registered($this->input->post('username'), $this->input->post('password'))) {
      $this->form_validation->set_message('_is_registered_user', 'Invalid username or password');
      return false;
    }
    return true;
  }

  function logout() {
    $this->session->sess_destroy();
    redirect('/');
  }
}
