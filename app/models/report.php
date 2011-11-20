<?php

class Report extends CI_Model
{
  function registrationAndActivation() {
    $sql = "
      SELECT x1.mdate AS date, x1.num_registrations, x2.num_activations
      FROM (
        SELECT created_at, Date_Format(created_at, '%b. %Y') AS mdate, Count(*) AS num_registrations
        FROM users
        GROUP BY Date_Format(created_at, '%b %Y')
      ) x1 JOIN (
        SELECT created_at, Date_Format(created_at, '%b. %Y') AS mdate, Sum(flag) AS num_activations
        FROM (
          SELECT created_at, CASE WHEN last_login_at IS NULL THEN 0 ELSE 1 END AS flag
          FROM users
        ) x
        GROUP BY Date_Format(created_at, '%b. %Y')
      ) x2 ON x1.mdate = x2.mdate
      ORDER BY Year(x1.created_at) DESC, Month(x1.created_at) DESC";
    return $this->db->query($sql)->result_array();
  }

  function getCollectionDetail($userid) {
    $query = $this->db->query("
      SELECT y.manufacturer, y.country, y.model_year, y.model_name, y.notes, y.condition, y.mod,
             y.serialnum, y.value, a.date AS acq_date, a.type AS acq_type, a.party, a.price
      FROM yoyos y
        LEFT JOIN acquisitions a ON a.yoyo_id = y.id AND a.user_id = y.user_id
      WHERE y.user_id = ?
    ", $userid);
    return $query->result();
  }

  function getCollectionSummary($userid) {
    $query = $this->db->query("
      SELECT Count(y.id) AS num, Sum(a.price) AS price, Sum(y.value) AS value
      FROM yoyos y
        LEFT JOIN acquisitions a ON a.yoyo_id = y.id AND a.user_id = y.user_id
      WHERE y.user_id = ?", $userid);
    return $query->row();
  }

  function getArchiveList() {
    $query = $this->db->query("
      SELECT u.username, a.user_id, Count(*) AS num_deletes, Min(a.date) AS start_date, Max(a.date) AS end_date
      FROM archives a LEFT JOIN users u ON u.id = a.user_id
      GROUP BY u.username, a.user_id
      ORDER BY u.username");
    return $query->result_array();
  }

  function getArchivesForUser($userid) {
    $query = $this->db->query("
      SELECT a.id, u.username, a.user_id, a.yoyo_id, a.date, a.data
      FROM archives a LEFT JOIN users u ON u.id = a.user_id
      WHERE a.user_id = ?
      ORDER BY a.id DESC", $userid);
    return $query->result_array();
  }
}

