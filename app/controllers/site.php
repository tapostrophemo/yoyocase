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
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $email = $this->input->post('email');
      $newUserId = $this->User->register($username, $password, $email);
      if (null == $newUserId) {
        $this->redirect_with_error('Username has already been taken', '/register');
      }

      $this->session->set_userdata('username', $this->input->post('username'));
      $this->session->set_userdata('userid', $newUserId);
      $this->session->set_userdata('logged_in', true);
      log_message('error', "(INFO) new user registration (id=$newUserId, username=$username, email=$email)"); // TODO: submit/fix CI bug re: log levels(?)
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

  function _validate_login($junk) {
    if (!$this->User->is_registered($this->input->post('username'), $this->input->post('password'))) {
      $this->form_validation->set_message('_validate_login', 'Invalid username or password');
      return false;
    }
    return true;
  }

  function logout() {
    $this->session->sess_destroy();
    redirect('/');
  }

  function passwordreset() {
    if (!$this->form_validation->run('site_resetpass')) {
      $this->load->view('pageTemplate', array('content' => $this->load->view('site/password', null, true)));
    }
    else {
      $this->load->library('email');
      if ($this->input->post('username') != 't_mo' && $this->email->isProdTest()) {
        $this->redirect_with_error('We apologize, but password resets by email are under construction.');
      }

      $user = $this->User->findByUsername($this->input->post('username'));
      $this->email->to($user->email);
      $this->email->from('noreply@yoyocase.net');
      $this->email->subject('password reset for yoyocase.net');
      $this->email->message('TODO: create/store/include temporary token, instructions, etc.');

      if ($this->email->send()) {
        $this->load->view('pageTemplate', array('content' => 'An email with instructions on resetting your password has been sent to your registered email address.'));
      }
      else {
        $this->redirect_with_error('Problem sending email to your registered address');
      }
    }
  }

  function _is_registered_user($junk) {
    if ($this->form_validation->can_short_circut('_is_registered_user')) {
      return false;
    }

    if (!$this->User->isRegisteredUsername($this->input->post('username'))) {
      $this->form_validation->set_message('_is_registered_user', 'Username not found');
      return false;
    }

    return true;
  }
}

