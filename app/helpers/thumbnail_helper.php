<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// TODO: check response codes for each interface

if (!function_exists('flickr_thumbnails')) {
  function flickr_thumbnails() {
    $CI =& get_instance();

    $flickr_userid = $CI->session->userdata('flickr_userid');
    $thumbnails = array();

    if (!$flickr_userid) {
      return $thumbnails;
    }

    $CI->load->model('Yoyo');
    $used = $CI->Yoyo->find_photos_for_collector($CI->session->userdata('userid'));

    $CI->load->library('Flickr_API');
    $response = $CI->flickr_api->photos_search(array('user_id' => $flickr_userid));
    foreach ($response['photos']['photo'] as $p) {
      $url = $CI->flickr_api->get_photo_url($p['id'], $p['farm'], $p['server'], $p['secret']);
      if (!in_array($url, $used)) {
        $thumbnails[] = array(
          'thumbnail' => $CI->flickr_api->get_photo_url($p['id'], $p['farm'], $p['server'], $p['secret'], 'thumbnail'),
          'url' => $url);
      }
    }

    return $thumbnails;
  }
}

if (!function_exists('photobucket_thumbnails')) {
  function photobucket_thumbnails() {
    $thumbnails = array();
    $CI =& get_instance();
    $username = $CI->session->userdata('photobucket_username');
    if (!$username) {
      return $thumbnails;
    }

    $CI->load->library('Photobucket_API');
    $response = $CI->photobucket_api->photos_search(array('username' => $username));

    $CI->load->model('Yoyo');
    $used = $CI->Yoyo->find_photos_for_collector($CI->session->userdata('userid'));

    foreach ($response['content']['media'] as $media) {
      if (!in_array($media['url'], $used)) {
        $thumbnails[] = array('thumbnail' => $media['thumb'], 'url' => $media['url']);
      }
    }

    return $thumbnails;
  }
}

