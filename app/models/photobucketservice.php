<?php

require_once(APPPATH . 'libraries/photohostingservice.php');

class PhotobucketService extends PhotoHostingService
{
  function getServiceUserToken() {
    return $this->session->userdata('photobucket_username');
  }

  function searchPhotos($usedPhotos) {
    $this->load->library('Photobucket_API');
    $thumbnails = array();
    $response = $this->photobucket_api->photos_search(array('username' => $this->getServiceUserToken()));
    foreach ($response['content']['media'] as $media) {
      if (!in_array($media['url'], $usedPhotos)) {
        $thumbnails[] = array('thumbnail' => $media['thumb'], 'url' => $media['url']);
      }
    }
    return $thumbnails;
  }
}

