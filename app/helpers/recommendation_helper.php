<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function similarCollections($userId = null) {
  $ci =& get_instance();
  $ci->load->model('Yoyo');

  if (null == $userId) {
    $userId = $ci->session->userdata('userid');
  }
  return (null == $userId) ? array() : $ci->Yoyo->getCollectionsSimilarTo($userId);
}
