<?php

//print __FILE__." -- ".dirname(__FILE__) ;
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));

require_once('simpletest/autorun.php');
require_once('krumo/class.krumo.php');
require('tests/CEERNResourceUtil.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile('tests/test_users.php');
        $this->addFile('tests/test_resources.php');
		$this->addFile('tests/test_sources.php');
		$this->addFile('tests/test_education.php');
    }
}







?>