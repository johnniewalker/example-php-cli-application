<?php

require_once('Library/CLIFeedback.php');
require_once('Library/CSVFile.php');
require_once('Model/PayrollCalendarMonth.php');

/**
*@desc the main SayDal application
*/
class Controller_MainController
	{
	/**
	*@desc the request params available to the application
	*/
	private $_request = array();

	/**
	*@desc checks the cli arguments, sets up the controller and view components and routes
	*  to the appropriate action.
	*
	* @param int the number of arguments passed to the CLI utility
	* @param array the arguments passed to the CLI utility
	* @return boolean TRUE on success | FALSE on error
	* @throws Exception on error
	*/
	static public function executeCLI( $argc,  $argv )
		{

		//route according to args
		if ($argc !=2 || in_array($argv[1], array('--help', '-help', '-h', '-?') ))
			{
			$request['action'] = 'showCLIHelp';
			$request['applicationScript'] = $argv[0];

			}
		else	{
			$request['action'] = 'writeCSV';
			$request['destinationFile'] = $argv[1];
			}

		//instantiate the controller
		$response = new Library_CLIFeedback();
		$controller = new Controller_MainController($request, $response );

		try 	{
			return self::dispatch( $controller, $request['action'] );
			}
		catch (Exception $e)
			{
			$response->addErrorMessage($e->getMessage());
			return FALSE;
			}


		}

	static private function dispatch( $controller, $action )
		{

		//dispatch the action
		switch( $action )
			{
			case 'showCLIHelp':
				return $controller->showCLIHelpAction( );
				break;
			case 'writeCSV':
				return $controller->writeCSVAction( );
				break;
			default:
				throw new Exception('Switch default error. Unrecognised Controller Action');
			}
		}

	/**
	*@desc constructor
	*@param array controller's request parameters
	* @param object the feedback writer object
	*/
	public function __construct( $request, $response )
		{
		$this->_request = $request;
		$this->_response = $response;
		}

	/**
	*@desc Action invoked by default to display help
	*/
	public function showCLIHelpAction()
		{
		$applicationScript = $this->_request['applicationScript'];
		$message = @include "View/Help.php";
		$this->_response->addMessage( $message );
		return TRUE;
		}


	/**
	*@desc determinines the payroll dates and writes a CSV file
	* @return boolean TRUE on Success
	* @throws Exception implicitly on error or if destination file exists
	*/
	public function writeCSVAction()
		{

		//implicitly throws exceptions on file existing or errors
		$cSVFile = new Library_CSVFile(
				$this->_request['destinationFile'],
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$dateToday = getdate();

		//get the payroll months
		$payrollMonths = Model_PayrollCalendarMonth::createPayrollMonths( $dateToday['year'] );

		foreach($payrollMonths as $payrollMonth)
			{
			//we only want to write payment dates for the remainder of this year
			if ( $payrollMonth->havePaymentDatesPassed( $dateToday ) )
				{
				//skip
				continue;
				}

			$cSVFile->writeRecord( array(
					$payrollMonth->getMonthName('%b'),
					$payrollMonth->getSalaryPaymentDate('%Y-%m-%d'),
					$payrollMonth->getBonusPaymentDate('%Y-%m-%d'))
					);
			}

		$cSVFile->closeFile();

		$this->_response->addMessage( 'The payroll dates have been written to ' . $this->_request['destinationFile'] );

		return TRUE;
		}


	}
