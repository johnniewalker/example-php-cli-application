<?php

//include the subject to test
require_once('Model/PayrollCalendarMonth.php');


class UnitTestCase_testOf_Model_PayrollCalendarMonth extends UnitTestCase
	{

	function testInstantation()
		{
		$obj = new Model_PayrollCalendarMonth(2004, 2);
		$this->assertIsA( $obj , 'Model_PayrollCalendarMonth');

		try 	{
			$obj = new Model_PayrollCalendarMonth(2004, 31);
			$this->fail('Expected exception on bad date');
			}
		catch(Exception $e)
			{
			$this->pass('Expected exception on bad date');
			}
		}

	function PrivateTestGetMonthNumeric()
		{
		$payrollMonth = new Model_PayrollCalendarMonth(2004, 2);

		$this->assertEqual($payrollMonth->getMonthNumeric(), 2);

		}


	function testGetMonthName()
		{

		$payrollMonth = new Model_PayrollCalendarMonth(2004, 2);
		$this->assertEqual($payrollMonth->getMonthName('%b'), 'Feb', "%s ");

		}


	function testGetSalaryPaymentDate()
		{
		//leap year
		$payrollMonth = new Model_PayrollCalendarMonth(2004, 2);
		$this->assertEqual($payrollMonth->getSalaryPaymentDate('%Y-%m-%d'), '2004-02-27');

		$payrollMonth = new Model_PayrollCalendarMonth(2004, 1);
		$this->assertEqual($payrollMonth->getSalaryPaymentDate('%Y-%m-%d'), '2004-01-30');

		//way into future
		$payrollMonth = new Model_PayrollCalendarMonth(2037, 1);
		//$this->dump($payrollMonth->getSalaryPaymentDate('%Y-%m-%d'));
		$this->assertEqual($payrollMonth->getSalaryPaymentDate('%Y-%m-%d'), '2037-01-30');




		}


	function testGetBonusPaymentDate()
		{
		//leap year + weekend
		$payrollMonth = new Model_PayrollCalendarMonth(2004, 2);
		$this->assertEqual($payrollMonth->getBonusPaymentDate('%Y-%m-%d'), '2004-02-18');

		$payrollMonth = new Model_PayrollCalendarMonth(2004, 5);
		$this->assertEqual($payrollMonth->getBonusPaymentDate('%Y-%m-%d'), '2004-05-19');

		//oct 2005
		$payrollMonth = new Model_PayrollCalendarMonth(2005, 10);
		$this->assertEqual($payrollMonth->getBonusPaymentDate('%Y-%m-%d'), '2005-10-19');

		}


	function testHavePaymentDatesPassedDate()
		{

		$preDate = Model_PayrollCalendarMonth::getDateSimple( 2005, 02, 27);
		$payrollMonth = new Model_PayrollCalendarMonth(2005, 02); //28th
		$this->assertFalse( $payrollMonth->havePaymentDatesPassed( $preDate ) );

		$onDate = Model_PayrollCalendarMonth::getDateSimple( 2005, 02, 28 );
		$payrollMonth = new Model_PayrollCalendarMonth(2005, 02); //28th
		$this->assertFalse( $payrollMonth->havePaymentDatesPassed( $onDate ) );

		$postDate = Model_PayrollCalendarMonth::getDateSimple( 2005, 03, 01 );
		$payrollMonth = new Model_PayrollCalendarMonth(2005, 02); //28th
		$this->assertTrue( $payrollMonth->havePaymentDatesPassed( $postDate ) );

		$payrollMonth = new Model_PayrollCalendarMonth(2005, 02);
		$this->assertTrue( $payrollMonth->havePaymentDatesPassed( ) ); //today is well passed 2005


		}


	function PrivateTestGetNumDaysInMonth()
		{
		//leap year
		$payrollMonth = new Model_PayrollCalendarMonth(2004, 2);
		$this->assertEqual($payrollMonth->getNumDaysInMonth(), 29);
		}


	function PrivateTestGetNumericDayOfWeekOfLastDayOfMonth()
		{
		$payrollMonth = new Model_PayrollCalendarMonth(2004, 2);

		$this->assertEqual($payrollMonth->getNumericDayOfWeekOfLastDayOfMonth(),0);
		}



	function testPayrollDatesFor2Years()
		{

		/*
		//STRUCTURED LIKE SO:

		$fixturePayrollMonths = array(
			['YYYY_MM'] => array(
				'MonthName' => 'STRING',
				'SalaryPaymentDate' => 'YYYY-MM-DD',
				'BonusPaymentDate' => 'YYYY-MM-DD',
				)
			);
		*/
		$fixturePayrollMonths = $this->getFixturePayrollMonths();


		foreach($fixturePayrollMonths as $payrollMonth => $expectedValuesArray  )
			{
			$yearMonthArray = explode('_',$payrollMonth);

			$payrollMonth = new Model_PayrollCalendarMonth($yearMonthArray[0], $yearMonthArray[1]);

			$this->assertEqual($payrollMonth->getMonthName('%b'), $expectedValuesArray['MonthName'] );
			$this->assertEqual($payrollMonth->getBonusPaymentDate('%Y-%m-%d'), $expectedValuesArray['BonusPaymentDate'] );
			$this->assertEqual($payrollMonth->getSalaryPaymentDate('%Y-%m-%d'), $expectedValuesArray['SalaryPaymentDate'] );
			}
		}

	/**
	* this test leaves an empty file - so if it is run last, do not expect to find any contents in it
	*/
	function testExceptionThrownUponBadYears()
		{


		//1969
		try 	{
			$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths(1969);
			$this->fail('Expected Exception');
			}
		catch (Exception $e )
			{
			$this->pass('Expected Exception', $e);
			}

		//2038
		try 	{
			$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths(2038);
			$this->fail('Expected Exception');
			}
		catch (Exception $e )
			{
			$this->pass('Expected Exception', $e);
			}
		}



	function getFixturePayrollMonths()
		{
		return array(

			'2004_1' => array(
				'MonthName' => 'Jan',
				'BonusPaymentDate' => '2004-01-15',
				'SalaryPaymentDate' => '2004-01-30',
				),

			'2004_2' => array(
				'MonthName' => 'Feb',
				'BonusPaymentDate' => '2004-02-18',
				'SalaryPaymentDate' => '2004-02-27',
				),

			'2004_3' => array(
				'MonthName' => 'Mar',
				'BonusPaymentDate' => '2004-03-15',
				'SalaryPaymentDate' => '2004-03-31',
				),

			'2004_4' => array(
				'MonthName' => 'Apr',
				'BonusPaymentDate' => '2004-04-15',
				'SalaryPaymentDate' => '2004-04-30',
				),

			'2004_5' => array(
				'MonthName' => 'May',
				'BonusPaymentDate' => '2004-05-19',
				'SalaryPaymentDate' => '2004-05-31',
				),

			'2004_6' => array(
				'MonthName' => 'Jun',
				'BonusPaymentDate' => '2004-06-15',
				'SalaryPaymentDate' => '2004-06-30',
				),

			'2004_7' => array(
				'MonthName' => 'Jul',
				'BonusPaymentDate' => '2004-07-15',
				'SalaryPaymentDate' => '2004-07-30',
				),

			'2004_8' => array(
				'MonthName' => 'Aug',
				'BonusPaymentDate' => '2004-08-18',
				'SalaryPaymentDate' => '2004-08-31',
				),

			'2004_9' => array(
				'MonthName' => 'Sep',
				'BonusPaymentDate' => '2004-09-15',
				'SalaryPaymentDate' => '2004-09-30',
				),

			'2004_10' => array(
				'MonthName' => 'Oct',
				'BonusPaymentDate' => '2004-10-15',
				'SalaryPaymentDate' => '2004-10-29',
				),

			'2004_11' => array(
				'MonthName' => 'Nov',
				'BonusPaymentDate' => '2004-11-15',
				'SalaryPaymentDate' => '2004-11-30',
				),

			'2004_12' => array(
				'MonthName' => 'Dec',
				'BonusPaymentDate' => '2004-12-15',
				'SalaryPaymentDate' => '2004-12-31',
				),

			'2005_1' => array(
				'MonthName' => 'Jan',
				'BonusPaymentDate' => '2005-01-19',
				'SalaryPaymentDate' => '2005-01-31',
				),

			'2005_2' => array(
				'MonthName' => 'Feb',
				'BonusPaymentDate' => '2005-02-15',
				'SalaryPaymentDate' => '2005-02-28',
				),

			'2005_3' => array(
				'MonthName' => 'Mar',
				'BonusPaymentDate' => '2005-03-15',
				'SalaryPaymentDate' => '2005-03-31',
				),

			'2005_4' => array(
				'MonthName' => 'Apr',
				'BonusPaymentDate' => '2005-04-15',
				'SalaryPaymentDate' => '2005-04-29',
				),

			'2005_5' => array(
				'MonthName' => 'May',
				'BonusPaymentDate' => '2005-05-18',
				'SalaryPaymentDate' => '2005-05-31',
				),

			'2005_6' => array(
				'MonthName' => 'Jun',
				'BonusPaymentDate' => '2005-06-15',
				'SalaryPaymentDate' => '2005-06-30',
				),

			'2005_7' => array(
				'MonthName' => 'Jul',
				'BonusPaymentDate' => '2005-07-15',
				'SalaryPaymentDate' => '2005-07-29',
				),

			'2005_8' => array(
				'MonthName' => 'Aug',
				'BonusPaymentDate' => '2005-08-15',
				'SalaryPaymentDate' => '2005-08-31',
				),

			'2005_9' => array(
				'MonthName' => 'Sep',
				'BonusPaymentDate' => '2005-09-15',
				'SalaryPaymentDate' => '2005-09-30',
				),

			'2005_10' => array(
				'MonthName' => 'Oct',
				'BonusPaymentDate' => '2005-10-19',
				'SalaryPaymentDate' => '2005-10-31',
				),

			'2005_11' => array(
				'MonthName' => 'Nov',
				'BonusPaymentDate' => '2005-11-15',
				'SalaryPaymentDate' => '2005-11-30',
				),

			'2005_12' => array(
				'MonthName' => 'Dec',
				'BonusPaymentDate' => '2005-12-15',
				'SalaryPaymentDate' => '2005-12-30',
				),

			);

		}

	/**
	*@desc 	temporary method to generate some test data to check manually then feed back into the test
	* To make it run you need to prefix it with 'test', as per SimpleTest convention
	*/
	function TEMPMETHODtestGenerateTestData()
		{
		$years[] = 2004;
		$years[] = 2005;

		$arrayDeclaration ='';

		foreach($years as $year)
			{
			$arrayDeclaration .= $this->generateTestFixtureDataForYear( $year );
			}

		$PHPstr = '
		$fixturePayrollMonths = array(' . PHP_EOL;

		$PHPstr .= $arrayDeclaration;

		$PHPstr .= '
			); ' . PHP_EOL;

		fwrite(STDOUT, $PHPstr . PHP_EOL);


		}

	function generateTestFixtureDataForYear( $year )
		{

		$arrayDeclaration ='';

		for ($idx = 1; $idx < 13; $idx++ )
			{
			$payrollMonth = new Model_PayrollCalendarMonth($year, $idx);


			$arrayDeclaration .= '
			\''. $year .'_'. $idx  .'\' => array(
				\'MonthName\' => \''. $payrollMonth->getMonthName('%b') .'\',
				\'BonusPaymentDate\' => \''. $payrollMonth->getBonusPaymentDate('%Y-%m-%d') .'\',
				\'SalaryPaymentDate\' => \''. $payrollMonth->getSalaryPaymentDate('%Y-%m-%d') .'\',
				),'. PHP_EOL;

			}

		return $arrayDeclaration;
		}


	}