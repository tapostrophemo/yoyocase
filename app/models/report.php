<?php

class Report extends Model
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
      ORDER BY Year(x1.created_at), Month(x1.created_at)";
    return $this->db->query($sql)->result_array();
  }
}

