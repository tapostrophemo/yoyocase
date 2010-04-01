<?php

function _urls_only($ary) {
  return $ary['url'];
}

class Yoyo extends Model
{
  function find_all_by_userid($userid) {
    // TODO: EXPLAIN's showing 2 full-table scans on this...but what else to index?
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
      ->select('id, user_id, manufacturer, mod, country, model_year, model_name, created_at, updated_at, condition, serialnum, notes')
      ->where('id', $id)
      ->get('yoyos');
    return $query->row();
  }

  function find_photos_for_collector($userid) {
    $sql = "
      SELECT p.url
      FROM photos p
        JOIN yoyos y ON y.id = p.yoyo_id
        JOIN users u ON u.id = y.user_id
      WHERE u.id = ?";
    $query = $this->db->query($sql, $userid);
    return array_map('_urls_only', $query->result_array());
  }

  function update($yoyoid, $data) {
    $this->load->helper('date');
    $data['updated_at'] = mdate('%Y-%m-%d %H:%i:%s', time());
    return $this->db->where('id', $yoyoid)->update('yoyos', $data);
  }
}

