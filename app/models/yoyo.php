<?php

class Yoyo extends Model
{
  function find_by_userid($userid) {
    $query = $this->db
      ->select('id, manufacturer, country, model_year, model_name, created_at, updated_at')
      ->where('user_id', $userid)
      ->get('yoyos');
    return $query->result();
  }

  function add_for_user($userid, $data) {
    $this->load->helper('date');
    $data['user_id'] = $userid;
    $data['created_at'] = mdate('%Y-%m-%d %H:%i:%s', time());
    $this->db->insert('yoyos', $data);
    return $this->db->insert_id();
  }

  function find_by_id($id) {
    $query = $this->db
      ->select('id, manufacturer, country, model_year, model_name, created_at, updated_at')
      ->where('id', $id)
      ->get('yoyos');
    return $query->row();
  }
}

