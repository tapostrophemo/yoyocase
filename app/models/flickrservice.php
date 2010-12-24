<?php

require_once(APPPATH . 'libraries/photohostingservice.php');

class FlickrService extends PhotoHostingService
{
  function getServiceUserToken() {
    return $this->session->userdata('flickr_userid');
  }

  function searchPhotos($usedPhotos) {
    $this->load->library('Flickr_API');
    $thumbnails = array();
    $response = $this->flickr_api->photos_search(array('user_id' => $this->getServiceUserToken()));
    foreach ($response['photos']['photo'] as $p) {
      $url = $this->flickr_api->get_photo_url($p['id'], $p['farm'], $p['server'], $p['secret']);
      if (!in_array($url, $usedPhotos)) {
        $thumbnails[] = array(
          'thumbnail' => $this->flickr_api->get_photo_url($p['id'], $p['farm'], $p['server'], $p['secret'], 'thumbnail'),
          'url' => $url);
      }
    }
    return $thumbnails;
  }
}

