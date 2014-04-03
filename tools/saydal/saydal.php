<?php

/**
*@desc The main say pay utility CLI bootstrap file
*
* User Settings - modify if necessary:
*/

//set the timezone - See List of Supported Timezones at: http://www.php.net/manual/en/timezones.php
date_default_timezone_set( "Europe/London" );

//set the erro reporting
//error_reporting(E_ALL ^E_DEPRECATED);
error_reporting(E_ALL ^E_DEPRECATED ^E_NOTICE);

// Define path to application directory
define('APPLICATION_PATH', dirname(__FILE__) );

//sets locale of the system
//or hardcode the locale e.g. to Fench: setlocale(LC_TIME, 'fr');
setlocale(LC_TIME, null);

/**
* Do not edit below this line
*/

//set the include paths
set_include_path (
	APPLICATION_PATH
	. PATH_SEPARATOR
	. get_include_path()
	);


require_once('Controller/MainController.php');
$appResult = Controller_MainController::executeCLI( $argc,  $argv );

if ( $appResult )
	{
	//success
	exit(0);
	}
else	{
	//failed
	exit(1);
	}