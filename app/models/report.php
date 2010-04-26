<?php

class Report extends Model
{
  function registration() {
    $sql = "
      SELECT Date_Format(created_at, '%b. %Y') AS date, Count(*) AS num_registrations
      FROM users
      GROUP BY Date_Format(created_at, '%b %Y')
      ORDER BY Year(created_at), Month(created_at)";
    return $this->db->query($sql)->result_array();
  }

  function activation() {
    $sql = "
      SELECT Date_Format(created_at, '%b. %Y') AS month, Sum(flag) AS num_activations
      FROM (
        SELECT created_at, CASE WHEN last_login_at IS NULL THEN 0 ELSE 1 END AS flag
        FROM users
      ) x
      GROUP BY Date_Format(created_at, '%b. %Y')
      ORDER BY Year(created_at), Month(created_at)";
    return $this->db->query($sql)->result_array();
  }
}

