<?php

class PhotoHostingService extends CI_Model
{
  function getThumbnails() {
    if (!$this->getServiceUserToken()) {
      return array();
    }

    $this->load->model('Yoyo');
    $usedPhotos = $this->Yoyo->findPhotosForCollector($this->session->userdata('userid'));
    return $this->searchPhotos($usedPhotos);
  }

  function getServiceUserToken() {
    throw new Exception('subclasses must implement');
  }

  function searchPhotos($usedPhotos) {
    throw new Exception('subclasses must implement');
  }
}

