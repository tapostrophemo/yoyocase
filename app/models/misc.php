<?php

class Misc extends Model
{
  function fun_facts() {
    $sql = "
      SELECT 'num_users' AS item, Count(*) AS num_items FROM users
      UNION
      SELECT 'num_yoyos', Count(*) FROM yoyos
      UNION
      SELECT 'num_photos', Count(*) FROM photos";
    $result = $this->db->query($sql)->result_array();
    foreach ($result as $row) {
      $data[$row['item']] = $row['num_items'];
    }

    return $data;
  }

  function randomThumbs() {
    $thumbs = glob($this->input->server('DOCUMENT_ROOT').'/thumbs/*.jpg');
    return array_map('basename', $thumbs);
  }
}

