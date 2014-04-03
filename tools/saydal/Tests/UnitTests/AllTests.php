<?php

class AllUnitTests extends TestSuite
	{
	function __construct()
		{
		parent::__construct('App Unit Tests');

		$this->addFile(dirname(__FILE__) . '/Library/CSVFile_test.php');
		$this->addFile(dirname(__FILE__) . '/Model/PayrollCalendarMonth_test.php');
		//$this->addFile(dirname(__FILE__) . '/Model/PayrollCalendar/PayRollDate_test.php');
		}
	}
