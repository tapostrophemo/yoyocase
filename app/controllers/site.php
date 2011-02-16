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
      $email = $this->input->post('email');
      $newUserId = $this->User->register($username, $this->input->post('password'), $email);
      if (null == $newUserId) {
        $this->redirect_with_error('Username has already been taken', '/register');
      }

      $this->session->set_userdata('username', $username);
      $this->session->set_userdata('userid', $newUserId);
      $this->session->set_userdata('logged_in', true);
      $this->session->set_flashdata('new_user', true);
      log_message('error', "(INFO) new user registration (id=$newUserId, username=$username, email=$email)"); // TODO: submit/fix CI bug re: log levels(?)
      $this->redirectWithMessage("Welcome, $username!", '/account');
    }
  }

  function login() {
    if (!$this->form_validation->run('site_login')) {
      $this->load->view('pageTemplate', array('title' => 'login and manage your yoyo collection', 'content' => $this->load->view('site/login', null, true)));
    }
    else {
      if ($this->User->isNew($this->input->post('username'))) {
        $this->session->set_flashdata('new_user', true);
      }
      $user = $this->User->markLogin($this->input->post('username'));
      $this->_setupSession($user);
      $this->redirect_with_message('Welcome back!', '/account');
    }
  }

  function _setupSession($user) {
    $this->session->set_userdata('username', $user->username);
    $this->session->set_userdata('userid', $user->id);
    $this->session->set_userdata('flickr_userid', $user->flickr_userid);
    $this->session->set_userdata('photobucket_username', $user->photobucket_username);
    $this->session->set_userdata('is_admin', $user->is_admin);
    $this->session->set_userdata('logged_in', true);
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
      if ($token = $this->User->createPerishableToken($user->id)) {
        $message = $this->load->view('site/passwordEmail', array('user' => $user, 'token' => $token), true);

        $this->email->to($user->email);
        $this->email->from('noreply@yoyocase.net');
        $this->email->subject('password reset for yoyocase.net');
        $this->email->message($message);

        if ($this->email->send()) {
          $content = 'An email with instructions on resetting your password has been sent to your registered email address.';
          if ($this->email->inTestMode()) {
            $content .= "<pre>$message</pre>";
          }
          $this->load->view('pageTemplate', array('content' => $content));
        }
        else {
          log_message('error', 'unable to send password reset email' . $this->email->print_debugger());
          $this->redirect_with_error('Problem sending email to your registered address');
        }
      }
      else {
        log_message('error', 'problem giving temporary login token to user');
        $this->redirect_with_error("Sorry, the site's unable to reset your password right now. Please try again later.");
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

  function passreset($token) {
    if ($user = $this->User->resetPerishableToken($token)) {
      $this->User->markLogin($user->username);
      $this->_setupSession($user);
      $this->redirect_with_message('Please enter a new password', '/newpass');
    }
    else {
      $this->redirect_with_error('That password reset request is invalid or has expired');
    }
  }

  function newpass() {
    if (!$this->form_validation->run('site_passwordresetform')) {
      $this->load->view('pageTemplate', array('content' => $this->load->view('site/passwordResetForm', null, true)));
    }
    else {
      log_message('error', '(INFO) password reset (username=' . $this->session->userdata('username') . ')');
      $this->User->update($this->session->userdata('username'), null, $this->input->post('password'));
      $this->redirect_with_message('Your password has been updated.', '/account');
    }
  }
}

