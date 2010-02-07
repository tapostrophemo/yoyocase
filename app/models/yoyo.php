<?php

class Yoyo extends Model
{
  function find_all_by_userid($userid) {
    $sql = "
      SELECT y.id, y.manufacturer, y.country, y.model_year, y.model_name, p.url AS first_photo
      FROM yoyos y LEFT JOIN (
        SELECT yoyo_id, url
        FROM photos
        WHERE id IN (SELECT Min(id) FROM photos GROUP BY yoyo_id)
      ) p ON p.yoyo_id = y.id
      WHERE user_id = ?";
    $query = $this->db->query($sql, $userid);
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

