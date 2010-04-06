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

  function saveAcquisition($userid, $yoyoid, $date, $type, $party, $price) {
    $this->db->insert('acquisitions', array(
      'user_id' => $userid,
      'yoyo_id' => $yoyoid,
      'date' => $date,
      'type' => $type,
      'party' => $party,
      'price' => $price));
  }

  function updateAcquisition($userid, $yoyoid, $date, $type, $party, $price) {
    if ($this->_hasAcquisition($userid, $yoyoid)) {
      $this->db->where(array('user_id' => $userid, 'yoyo_id' => $yoyoid))->update('acquisitions', array(
        'date' => $date,
        'type' => $type,
        'party' => $party,
        'price' => $price));
    }
    else {
      $this->saveAcquisition($userid, $yoyoid, $date, $type, $party, $price);
    }
  }

  function _hasAcquisition($userid, $yoyoid) {
    $query = $this->db->select('Count(*) AS ct')
      ->where(array('user_id' => $userid, 'yoyo_id' => $yoyoid))
      ->get('acquisitions');
    $row = $query->row();
    return 1 == $row->ct;
  }

  function find_by_id($id) {
    $sql = "
      SELECT y.id, y.user_id, y.manufacturer, y.mod, y.country, y.model_year, y.model_name,
             y.created_at, y.updated_at, y.condition, y.serialnum, y.value, y.notes,
             a.date AS acq_date, a.type AS acq_type, a.party AS acq_party, a.price AS acq_price
      FROM yoyos y LEFT JOIN acquisitions a ON a.yoyo_id = y.id AND a.user_id = y.user_id
      WHERE y.id = ?";
    $query = $this->db->query($sql, $id);
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

