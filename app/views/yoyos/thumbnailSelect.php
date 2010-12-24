<?php

$CI =& get_instance();
$CI->load->model('FlickrService');
$CI->load->model('PhotobucketService');
$services = array($CI->FlickrService, $CI->PhotobucketService);

foreach ($services as $service) {
  echo '<hr/>';
  if ($service->getServiceUserToken()) {
    $thumbnails = $service->getThumbnails();
    if (count($thumbnails) > 0) {
      echo "<p><label>Link photos from your {$service->name} {$service->description}?</label></p>\n";
      echo '<table class="thumbnailSelect">';
      echo "\n<tr>";
      $i = 0;
      foreach ($thumbnails as $tn) {
        echo '<td>';
        echo '<img src="'.$tn['thumbnail'].'"/><br/>';
        echo '<input id="photo_'.$i.'" type="checkbox" name="photos[]" value="'.$tn['url'].'"/>';
        echo '</td>';
        $i++; if (($i % 3) == 0) echo "</tr>\n<tr>";
      }
      echo "</tr>\n";
     echo "</table>\n";
    }
    else {
      echo '<p>Your flickr account is authorized, but you need to add some photos to your photostream.</p>';
    }
  }
  else {
    echo "<p>Go to the <a href=\"/preferences\">preferences</a> screen to verify your {$service->name} {$service->description}.</p>";
  }
}

