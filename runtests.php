<?php

require_once('lib/simpletest_1.0.1/unit_tester.php');
require_once('lib/simpletest_1.0.1/reporter.php');
require_once('lib/simpletest_1.0.1/web_tester.php');

define('BASE_URL', 'http://dev.yoyocase.net');

class MY_WebTestCase extends WebTestCase
{
  function deleteRecord($tablename, $criteria = array()) {
    $sql = "DELETE FROM $tablename\n";
    if (count($criteria) > 0) {
      $sql .= ' WHERE';
      foreach ($criteria as $column => $value) {
        $clauses[] = " $column = $value\n";
      }
      $sql .= join($clauses, ' AND');
    }
    `mysql -uyoyocase_user -pbob yoyocase -e "$sql"`; // TODO: make this "safer"? at least make username/pw configurable
  }
}

$test = &new TestSuite('yoyocase.net tests');
foreach (glob('tests/*.php') as $file) {
  $test->addTestFile($file);
}
exit($test->run(new TextReporter()) ? 0 : 1);

