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
      $this->load->model('Misc');
      $facts = array('facts' => $this->Misc->fun_facts());
      $this->load->view('pageTemplate', array('title' => 'register and manage your yoyo collection', 'content' => $this->load->view('site/register', $facts, true)));
    }
    else {
      $newUserId = $this->User->register(
        $this->input->post('username'),
        $this->input->post('password'),
        $this->input->post('email'));
      if (null == $newUserId) {
        $this->redirect_with_error('Username has already been taken', '/register');
      }

      $this->session->set_userdata('username', $this->input->post('username'));
      $this->session->set_userdata('userid', $newUserId);
      $this->session->set_userdata('logged_in', true);
      $this->redirect_with_message('Welcome, ' . $this->input->post('username') . '!', '/preferences');
    }
  }

  function login() {
    if (!$this->form_validation->run('site_login')) {
      $this->load->view('pageTemplate', array('title' => 'login and manage your yoyo collection', 'content' => $this->load->view('site/login', null, true)));
    }
    else {
      $user = $this->User->mark_login($this->input->post('username'));
      $this->session->set_userdata('username', $this->input->post('username'));
      $this->session->set_userdata('userid', $user->id);
      $this->session->set_userdata('flickr_userid', $user->flickr_userid);
      $this->session->set_userdata('photobucket_username', $user->photobucket_username);
      $this->session->set_userdata('is_admin', $user->is_admin);
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

