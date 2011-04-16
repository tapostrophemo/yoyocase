<?php

class Photo extends CI_Model
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

  function findById($id) {
    return $this->find_by_id($id);
  }

  function find_by_id($id) {
    $query = $this->db->select('yoyo_id, url')->where('id', $id)->get('photos');
    return $query->row();
  }

  function delete($id) {
    $this->db->where('id', $id)->delete('photos');
  }

  function getMaxThumbnail() {
    return $this->db->where('name', 'max_thumbnail_id')->get('system')->row()->value;
  }

  function setMaxThumbnail($id) {
    $this->db->where('name', 'max_thumbnail_id')->update('system', array('value' => $id));
  }

  function getUnThumbed($max) {
    $sql = "
      SELECT p.id, u.username, y.model_name, p.url
      FROM photos p
        JOIN yoyos y ON y.id = p.yoyo_id
        JOIN users u ON u.id = y.user_id
      WHERE p.id > ?
      ORDER BY p.id";
    return $this->db->query($sql, array($max))->result();
  }
}

