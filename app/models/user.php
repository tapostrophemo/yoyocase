<?php

class User extends Model
{
  function num_accounts() {
    return $this->db->count_all('users');
  }

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
      'perishable_token' => 'TODO:',
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

  function mark_login($username) {
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

  function find_by_username($username) {
    $query = $this->db->select('id, username, email, flickr_userid, is_admin')->where('username', $username)->get('users');
    if ($query->num_rows == 1) {
      $result = $query->result();
      return $result[0];
    }
    else {
      return null;
    }
  }

  function update($username, $email, $password = null) {
    $this->load->helper('date');
    $now = mdate('%Y-%m-%d %H:%i:%s', time());

    $attrs = array('email' => $email, 'updated_at' => $now);

    if ($password != null) {
      $this->load->plugin('salt');
      $salt = salt();
      $attrs['password_salt'] = $salt;
      $attrs['crypted_password'] = sha1("$password$salt");
    }
    $this->db->where('username', $username)->set($attrs)->update('users');
  }

  function find_all() {
    $query = $this->db->select('username, created_at, last_login_at')->get('users');
    return $query->result();
  }
}

