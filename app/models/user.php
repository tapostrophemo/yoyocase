<?php

class User extends Model
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

    return $this->find_by_username($username);
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

  function findAll() {
    $sql = "
      SELECT u.id, u.username, u.created_at, u.last_login_at, u.email,
        Count(DISTINCT y.id) AS num_yoyos, Count(DISTINCT p.id) AS num_photos
      FROM users u
        LEFT JOIN yoyos y ON y.user_id = u.id
        LEFT JOIN photos p ON p.yoyo_id = y.id
      GROUP BY u.username, u.created_at, u.last_login_at, u.email
      ORDER BY u.id";
    $query = $this->db->query($sql);
    return $query->result();
  }
}

