<?php

require_once('lib/simpletest_1.0.1/unit_tester.php');
require_once('lib/simpletest_1.0.1/reporter.php');
require_once('lib/simpletest_1.0.1/web_tester.php');

define('BASE_URL', 'http://dev.yoyocase.net');

$test = &new TestSuite('yoyocase.net tests');
foreach (glob('tests/*.php') as $file) {
  $test->addTestFile($file);
}
exit($test->run(new TextReporter()) ? 0 : 1);

