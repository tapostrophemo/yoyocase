<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

ini_set('include_path',
  BASEPATH.'../PBAPI-0.2.3' . PATH_SEPARATOR .
  BASEPATH.'../OAuth-0.1.1' . PATH_SEPARATOR .
  ini_get('include_path'));

require_once('PBAPI.php');

class MY_PBAPI extends PBAPI
{
  // you know, I really don't want to mess with that constructor just to pass in the format I want
  function setDefaultFormat($type) {
    $this->request->setDefaultFormat($type);
  }
}

class Photobucket_API
{
  protected $CI;
  protected $api_key;
  protected $api_private_key;

  public function __construct($params = array()) {
    $this->CI =& get_instance();

    $this->api_key = $params['api_key'];
    $this->api_private_key = $params['api_private_key'];

    log_message('debug', 'Photobucket_API class initialized');
  }

  protected function _initialized() {
    return !empty($this->api_key) && !empty($this->api_private_key);
  }

  function authenticate() {
    if (!$this->_initialized()) {
      log_message('error', 'Photobucket_API not properly initialized in "authenticate"');
      return false;
    }

    $api = new PBAPI($this->api_key, $this->api_private_key);

    $api->login('request')->post();
    $api->loadTokenFromResponse();

    $token = $api->getOAuthToken();
    $this->CI->session->set_userdata(array(
      'oauth_token' => $token->getKey(),
      'oauth_token_secret' => $token->getSecret()));

    $api->goRedirect('login');
  }

  function verify_login() {
    if (!$this->_initialized()) {
      log_message('error', 'Photobucket_API not properly initialized in "verify_login"');
      return false;
    }

    $api = new PBAPI($this->api_key, $this->api_private_key);

    try {
      $api->setOAuthToken($this->CI->session->userdata('oauth_token'), $this->CI->session->userdata('oauth_token_secret'));
      $this->CI->session->unset_userdata('oauth_token');
      $this->CI->session->unset_userdata('oauth_token_secret');

      $api->login('access')->post();
      $api->loadTokenFromResponse();
    }
    catch (PBAPI_Exception $ex) {
      return false;
    }

    return $api->getUsername();
  }

  function photos_search() {
    $api = new MY_PBAPI($this->api_key, $this->api_private_key);
    $api->setDefaultFormat('phpserialize');

    // may need calls to Media/GetMediaLinks, Search/SearchImages, or
    // Users/GetRecentUserMedia; may need to loop through results and get details,
    // such as Media/GetMediaLinks; however, for now...
    return unserialize($api->user($this->CI->session->userdata('photobucket_username'))->search(array('perpage' => 100))->get()->getResponseString());
  }
}

