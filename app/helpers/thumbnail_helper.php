<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// TODO: check response codes for each interface

if (!function_exists('flickr_thumbnails')) {
  function flickr_thumbnails() {
    $CI =& get_instance();
    $CI->load->model('FlickrService');
    return $CI->FlickrService->getThumbnails();
  }
}

if (!function_exists('photobucket_thumbnails')) {
  function photobucket_thumbnails() {
    $CI =& get_instance();
    $CI->load->model('PhotobucketService');
    return $CI->PhotobucketService->getThumbnails();
  }
}

