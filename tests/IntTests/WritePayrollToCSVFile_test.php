<?php

//include the subject to test
require_once('Library/CSVFile.php');
require_once('Model/PayrollCalendarMonth.php');

class IntTestCase_testOf_WritePayrollToCSVFile extends UnitTestCase
	{

	/**
	*@desc switch this to TRUE formanual investigation of file contents after a test
	*/
	private $_retainTempFilesAfterTest = TRUE;



	function setUp()
		{
		$this->_testFileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TempData' . DIRECTORY_SEPARATOR  . 'test.txt';

		@unlink( $this->_testFileName );
		}

	function tearDown()
		{

		if (!( $this->_retainTempFilesAfterTest ))
			{
			@unlink( $this->_testFileName );
			}
		}
	function testWritePartialPayrollCalendarToCSVFile_AtBeforeDeadlineDate()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths( 2004 );
		$countWatcher =0;

		$deadlineDate = Model_PayrollCalendarMonth::getDateSimple( 2004,02,26);

		foreach($payrollMonths as $payrollMonth)
			{
			$this->assertIsA($payrollMonth, 'Model_PayrollCalendarMonth' );

			if (!($payrollMonth->havePaymentDatesPassed( $deadlineDate )) )
				{
				$countWatcher++;
				$cSVFile->writeRecord( array(
							$payrollMonth->getMonthName('%b'),
							$payrollMonth->getSalaryPaymentDate('%Y-%m-%d'),
							$payrollMonth->getBonusPaymentDate('%Y-%m-%d'))
							);
				}
			}

		$this->assertEqual($countWatcher, 11);

		$cSVFile->closeFile();

		$this->assertTrue( file_exists( $this->_testFileName  ));
		}


	function testWritePartialPayrollCalendarToCSVFile_AtOnDeadlineDate()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths( 2004 );
		$countWatcher =0;

		$deadlineDate = Model_PayrollCalendarMonth::getDateSimple( 2004,02,27);

		foreach($payrollMonths as $payrollMonth)
			{
			$this->assertIsA($payrollMonth, 'Model_PayrollCalendarMonth' );

			if (!($payrollMonth->havePaymentDatesPassed( $deadlineDate )) )
				{
				$countWatcher++;
				$cSVFile->writeRecord( array(
							$payrollMonth->getMonthName('%b'),
							$payrollMonth->getSalaryPaymentDate('%Y-%m-%d'),
							$payrollMonth->getBonusPaymentDate('%Y-%m-%d'))
							);
				}
			}

		$this->assertEqual($countWatcher, 11);

		$cSVFile->closeFile();

		$this->assertTrue( file_exists( $this->_testFileName  ));
		}



	function testWritePartialPayrollCalendarToCSVFile_AtAfterDeadlineDate()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths( 2004 );
		$countWatcher =0;

		$deadlineDate = Model_PayrollCalendarMonth::getDateSimple( 2004,02,28);

		foreach($payrollMonths as $payrollMonth)
			{
			$this->assertIsA($payrollMonth, 'Model_PayrollCalendarMonth' );

			if (!($payrollMonth->havePaymentDatesPassed( $deadlineDate )) )
				{
				$countWatcher++;
				$cSVFile->writeRecord( array(
							$payrollMonth->getMonthName('%b'),
							$payrollMonth->getSalaryPaymentDate('%Y-%m-%d'),
							$payrollMonth->getBonusPaymentDate('%Y-%m-%d'))
							);
				}
			}

		$this->assertEqual($countWatcher, 10);

		$cSVFile->closeFile();

		$this->assertTrue( file_exists( $this->_testFileName  ));
		}




	function testWritePartialPayrollCalendarToCSVFile_AtTodaysDate()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$dateToday = getdate();

		$deadlineDate = Model_PayrollCalendarMonth::getDateSimple($dateToday['year'],$dateToday['mon'], $dateToday['mday']  );

		$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths( $deadlineDate['year'] );
		$countWatcher =0;

		foreach($payrollMonths as $payrollMonth)
			{
			$this->assertIsA($payrollMonth, 'Model_PayrollCalendarMonth' );

			if (!($payrollMonth->havePaymentDatesPassed( $deadlineDate )) )
				{

				$cSVFile->writeRecord( array(
							$payrollMonth->getMonthName('%b'),
							$payrollMonth->getSalaryPaymentDate('%Y-%m-%d'),
							$payrollMonth->getBonusPaymentDate('%Y-%m-%d'))
							);
				}
			}


		$cSVFile->closeFile();

		$this->assertTrue( file_exists( $this->_testFileName  ));
		}


	function testWriteWholePayrollCalendarToCSVFile()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths(2004);

		$countWatcher =0;

		foreach($payrollMonths as $payrollMonth)
			{
			$countWatcher++;
			$this->assertIsA($payrollMonth, 'Model_PayrollCalendarMonth' );
			$cSVFile->writeRecord( array(
						$payrollMonth->getMonthName('%b'),
						$payrollMonth->getSalaryPaymentDate('%Y-%m-%d'),
						$payrollMonth->getBonusPaymentDate('%Y-%m-%d'))
						);
			}

		$this->assertEqual($countWatcher, 12);

		$cSVFile->closeFile();

		$this->assertTrue( file_exists( $this->_testFileName  ));
		}



	}
