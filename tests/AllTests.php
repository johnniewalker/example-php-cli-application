<?php

/**
* This file is bootstrapped by TestRunner.php
*/

class AllTests extends TestSuite
	{
	function __construct()
		{
		parent::__construct('All Tests');

		$this->addFile(dirname(__FILE__) . '/UnitTests/AllTests.php');

		//integration tests
		$this->addFile(dirname(__FILE__) . '/IntTests/AllTests.php');


		}
	}
