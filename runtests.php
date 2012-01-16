<?php

require_once('lib/simpletest_1.0.1/unit_tester.php');
require_once('lib/simpletest_1.0.1/reporter.php');
require_once('lib/simpletest_1.0.1/web_tester.php');

define('BASE_URL', 'http://dev.yoyocase.net');

class MY_WebTestCase extends WebTestCase
{
  function deleteRecord($tablename, $criteria = array()) {
    $sql = "SET NAMES 'utf8'; DELETE FROM $tablename\n";
    if (count($criteria) > 0) {
      $sql .= ' WHERE';
      foreach ($criteria as $column => $value) {
        $clauses[] = " $column = $value\n";
      }
      $sql .= join($clauses, ' AND');
    }
    `mysql -uyoyocase_user -pbob yoyocase -e "$sql"`; // TODO: make this "safer"? at least make username/pw configurable
  }

  function insertRecord($tablename, $colsAndVals = array()) {
    if (count($colsAndVals) > 0) {
      $sql = "INSERT INTO $tablename(";
      foreach ($colsAndVals as $column => $value) { $columns[] = $column; }
      $sql .= join($columns, ', ') . ")\n VALUES(";
      foreach ($colsAndVals as $column => $value) { $values[] = $value; }
      $sql .= join($values, ', ') . ")\n";
      `mysql -uyoyocase_user -pbob yoyocase -e "$sql"`;

      $sql = "SELECT Max(id) FROM $tablename";
      $id = `mysql -uyoyocase_user -pbob yoyocase -Be "$sql" | tail -1`;
      return (int) $id;
    }
    return -1;
  }

  function updateRecord($tablename, $field, $value, $criteriaColumn, $criteriaValue) {
    $sql = "UPDATE $tablename SET $field = $value WHERE $criteriaColumn = $criteriaValue";
    `mysql -uyoyocase_user -pbob yoyocase -e "$sql"`;
  }

  function updateRecordValues($tablename, $criteriaColumn, $criteriaValue, $fieldsAndValues) {
    foreach ($fieldsAndValues as $field => $value) {
      $this->updateRecord($tablename, $field, $value, $criteriaColumn, $criteriaValue);
    }
  }

  function assertRecords($count, $tablename, $criteriaColumn, $criteriaValue) {
    $sql = "SELECT Count(*) FROM $tablename WHERE $criteriaColumn = $criteriaValue";
    $this->assertEqual((int) `mysql -uyoyocase_user -pbob yoyocase -e "$sql" | tail -1`, $count);
  }

  function assertRecord($tablename, $criteriaColumn, $criteriaValue) {
    $this->assertRecords(1, $tablename, $criteriaColumn, $criteriaValue);
  }

  function assertNoRecord($tablename, $criteriaColumn, $criteriaValue) {
    $sql = "SELECT Count(*) FROM $tablename WHERE $criteriaColumn = $criteriaValue";
    $this->assertEqual((int) `mysql -uyoyocase_user -pbob yoyocase -e "$sql" | tail -1`, 0);
  }

  function countRecords($tablename) {
    return (int) `mysql -uyoyocase_user -pbob yoyocase -Be "SELECT Count(*) FROM $tablename" | tail -1`;
  }

  function createUser($username, $email, $password) {
    $this->deleteRecord('users', array('username' => "'$username'"));
    $pass = sha1($password);
    return $this->insertRecord('users', array(
      'username' => "'$username'",
      'email' => "'$email'",
      'crypted_password' => "'$pass'",
      'created_at' => 'NOW()'));
  }

  function createAdminUser($username, $email, $password) {
    $userId = $this->createUser($username, $email, $password);
    $this->updateRecord('users', 'is_admin', 1, 'id', $userId);
  }

  function createYoyo($userid, $name, $notes = null) {
    $data = array('user_id' => $userid, 'model_name' => "'$name'");
    if (isset($notes)) {
      $data['notes'] = "'$notes'";
    }
    return $this->insertRecord('yoyos', $data);
  }

  function createPhoto($yoyoid, $url) {
    return $this->insertRecord('photos', array('yoyo_id' => $yoyoid, 'url' => "'$url'"));
  }

  function logInAs($username , $password) {
    $this->get(BASE_URL.'/login');
    $this->setField('username', $username);
    $this->setField('password', $password);
    $this->clickSubmit('Login');
  }

  function parseFromPageText($pattern) {
    $match = array();
    $text = $this->getBrowser()->getContentAsText();
    preg_match($pattern, $text, $match);
    return $match[0];
  }

  function getResetTokenPath() {
    return $this->parseFromPageText('/\/passreset\/[^ ]+/');
  }

  function setMaxThumbnailId($num) {
    `mysql -uyoyocase_user -pbob yoyocase -Be "UPDATE system SET value = '$num' WHERE name = 'max_thumbnail_id'"`;
  }
}

$test = &new TestSuite('yoyocase.net tests');
if (isset($_SERVER['argv'][1])) {
  $files = array($_SERVER['argv'][1]);
}
else {
  $files = glob('tests/*.php');
}
foreach ($files as $file) {
  $test->addTestFile($file);
}
exit($test->run(new TextReporter()) ? 0 : 1);

