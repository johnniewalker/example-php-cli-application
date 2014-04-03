<?php

class AllIntTests extends TestSuite
	{
	function __construct()
		{
		parent::__construct('App Unit Tests');

		$this->addFile(dirname(__FILE__) . '/WritePayrollToCSVFile_test.php');
		}
	}
