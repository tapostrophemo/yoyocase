<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('flickr_thumbnails')) {
  function _urls_only($ary) {
    return $ary['url'];
  }

  function flickr_thumbnails() {
    $CI =& get_instance();

    $userid = $CI->session->userdata('userid');
    $flickr_userid = $CI->session->userdata('flickr_userid');
    $thumbnails = array();

    if (!$flickr_userid) {
      return $thumbnails;
    }

    $sql = "
      SELECT p.url
      FROM photos p
        JOIN yoyos y ON y.id = p.yoyo_id
        JOIN users u ON u.id = y.user_id
      WHERE u.id = ?";
    $query = $CI->db->query($sql, $userid);
    $used = array_map('_urls_only', $query->result_array());

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
    $CI =& get_instance();
    $username = $CI->session->userdata('photobucket_username');
    $CI->load->library('Photobucket_API');
    $response = $CI->photobucket_api->photos_search(array('username' => $username));

    // TODO: check response code, check for already-used photos, etc.
    $thumbnails = array();
    foreach ($response['content']['media'] as $media) {
      $thumbnails[] = array('thumbnail' => $media['thumb'], 'url' => $media['url']);
    }
    return $thumbnails;
  }
}

