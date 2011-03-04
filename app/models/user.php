<?php

class User extends MY_Model
{
  function register($username, $password, $email) {
    $this->db->select('id')->where('username', $username);
    $query = $this->db->get('users');
    if ($query->num_rows > 0) {
      return null;
    }

    $this->load->plugin('salt');
    $this->load->helper('date');

    $salt = salt();
    $now = mdate('%Y-%m-%d %H:%i:%s', time());
    $data = array(
      'username' => $username,
      'email' => $email,
      'crypted_password' => sha1("$password$salt"),
      'password_salt' => $salt,
      'persistence_token' => 'TODO:',
      'perishable_token' => '',
      'current_login_at' => $now,
      'current_login_ip' => $this->input->ip_address(),
      'created_at' => $now
    );
    $this->db->insert('users', $data);
    return $this->db->insert_id();
  }

  function is_registered($username, $password) {
    $this->db->select('password_salt')->where('username', $username);
    $query = $this->db->get('users');
    if ($query->num_rows != 1) {
      return false;
    }

    $salt = $query->row()->password_salt;
    $this->db->select('id')->where('username', $username)->where('crypted_password', sha1("$password$salt"));
    $query = $this->db->get('users');
    return $query->num_rows == 1;
  }

  function isRegisteredUsername($username) {
    $this->db->select('id')->where('username', $username);
    $query = $this->db->get('users');
    return $query->num_rows == 1;
  }

  function isNew($username) {
    $query = $this->db->select('last_login_at')->where('username', $username)->get('users');
    if ($query->num_rows == 0) {
      return false;
    }
    return !isset($query->row()->last_login_at);
  }

  function markLogin($username) {
    $this->load->helper('date');

    // TODO: collapse the following to 1 SQL statement (is it possible?)
    $this->db->where('username', $username)
      ->set('last_login_at', 'current_login_at', false)
      ->set('last_login_ip', 'current_login_ip', false)
      ->update('users');

    $now = mdate('%Y-%m-%d %H:%i:%s', time());
    $this->db->where('username', $username)->update('users', array(
      'current_login_at' => $now,
      'current_login_ip' => $this->input->ip_address()));

    return $this->findByUsername($username);
  }

  function createPerishableToken($userid) {
    $this->load->plugin('salt');
    $token = salt();
    $this->db->where('id', $userid)->set('perishable_token', $token)->update('users');
    return $token;
  }

  function resetPerishableToken($token) {
    $query = $this->db->select('id')->where('perishable_token', $token)->get('users');
    if (!$query->num_rows == 1) {
      return null;
    }

    $user = $this->findById($query->row()->id);
    if ($user) {
      $this->db->where('id', $user->id)->set('perishable_token', '')->update('users');
    }
    return $user;
  }

  function expirePerishableTokens() {
    $flag = $this->db->simple_query("
      UPDATE users
      SET perishable_token = ''
      WHERE id IN (SELECT user_id FROM user_pw_reset)");

    $flag = $flag && $this->db->simple_query('TRUNCATE TABLE user_pw_reset');

    $flag = $flag && $this->db->simple_query("
      INSERT INTO user_pw_reset
        SELECT id
        FROM users
        WHERE Trim(perishable_token) <> ''");

    return $flag;
  }

  function findByUsername($username) {
    return $this->find_by_username($username);
  }

  function find_by_username($username) {
    $query = $this->db
      ->select('id, username, email, flickr_userid, photobucket_username, is_admin')
      ->where('username', $username)->get('users');
    if ($query->num_rows == 1) {
      $result = $query->result();
      return $result[0];
    }
    else {
      return null;
    }
  }

  function update($username, $email = null, $password = null, $flickr_userid = null, $photobucket_username = null) {
    $this->load->helper('date');
    $now = mdate('%Y-%m-%d %H:%i:%s', time());

    $attrs = array('updated_at' => $now);

    if ($email != null) {
      $attrs['email'] = $email;
    }

    if ($flickr_userid != null) {
      $attrs['flickr_userid'] = $flickr_userid;
    }

    if ($photobucket_username != null) {
      $attrs['photobucket_username'] = $photobucket_username;
    }

    if ($password != null) {
      $this->load->plugin('salt');
      $salt = salt();
      $attrs['password_salt'] = $salt;
      $attrs['crypted_password'] = sha1("$password$salt");
    }

    $this->db->where('username', $username)->set($attrs)->update('users');
  }

  function archive($userid) {
    $user = $this->findById($userid);
    $this->db->insert('archives', array(
      'user_id' => $userid,
      'yoyo_id' => 0,
      'data' => serialize($user),
      'date' => $this->_now()));
    $this->db->where('id', $userid)->delete('users');
  }

  function findById($userid) {
    $query = $this->db
      ->select('id, username, created_at, last_login_at, email, flickr_userid, photobucket_username, is_admin')
      ->where('id', $userid)
      ->get('users');

    if ($query->num_rows == 1) {
      $result = $query->result();
      return $result[0];
    }
    else {
      return null;
    }
  }

  function findAll($sort = 'u.id') {
    $sql = "
      SELECT u.id, u.username, u.created_at, u.last_login_at, u.email,
        Count(DISTINCT y.id) AS num_yoyos, Count(DISTINCT p.id) AS num_photos
      FROM users u
        LEFT JOIN yoyos y ON y.user_id = u.id
        LEFT JOIN photos p ON p.yoyo_id = y.id
      GROUP BY u.username, u.created_at, u.last_login_at, u.email
      ORDER BY $sort";
    $query = $this->db->query($sql);
    return $query->result();
  }

  function findAllSorted($by) {
    switch ($by) {
      case 'username': return $this->findAll('u.username');
      case 'reg':      return $this->findAll('u.created_at DESC');
      case 'login':    return $this->findAll('u.last_login_at DESC');
      default: return array();
    }
  }

  static function isValidSortKey($name) {
    return in_array($name, array('username', 'reg', 'login'));
  }
}

