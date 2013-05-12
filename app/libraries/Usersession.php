<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserSession
{
  var $CI;

  function __construct() {
    $this->CI =& get_instance();
  }

  public function setup($user) {
    $this->setupBasic($user->username, $user->id);
    $this->CI->session->set_userdata('is_admin', $user->is_admin);
    $this->CI->session->set_userdata('flickr_userid', $user->flickr_userid);
    $this->CI->session->set_userdata('photobucket_username', $user->photobucket_username);
  }

  public function setupBasic($username, $userId) {
    $this->CI->session->set_userdata('username', $username);
    $this->CI->session->set_userdata('userid', $userId);
    $this->CI->session->set_userdata('logged_in', true);
    $this->CI->session->set_userdata('is_admin', false);
  }

  public function impersonate($user) {
    $this->setup($user);
    $this->CI->session->set_userdata('impersonating', true);
  }
}

