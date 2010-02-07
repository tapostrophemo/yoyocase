<?php

class Photo extends Model
{
  function add_for_yoyo($yoyoid, $data) {
    $this->load->helper('date');
    $data['yoyo_id'] = $yoyoid;
    $data['created_at'] = mdate('%Y-%m-%d %H:%i:%s', time()); // TODO: factor this into MY_Model (other classes too)
    $this->db->insert('photos', $data);
    return $this->db->insert_id();
  }

  function find_all_by_yoyoid($yoyoid) {
    $query = $this->db->select('id, url')->where('yoyo_id', $yoyoid)->get('photos');
    return $query->result();
  }
}

