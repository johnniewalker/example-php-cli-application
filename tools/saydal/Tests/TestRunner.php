<?php


//set the timezone - See List of Supported Timezones at: http://www.php.net/manual/en/timezones.php
date_default_timezone_set( "Europe/London" );

//set the erro reporting
error_reporting(E_ALL ^E_DEPRECATED);

//sets locale of the system
//or hardcode the locale e.g. to Fench: setlocale(LC_TIME, 'fr');
setlocale(LC_TIME, null);

$pathsConfigArray = @parse_ini_file( dirname(__FILE__) . '/Config/IncludePathsConfig.ini');

if (!( $pathsConfigArray ))
	{
	echo 'Please check to ensure the application has been configured correctly. See the installation instructions in the README.txt file';
	exit(1);
	}

if (!( isset($pathsConfigArray['applicationpath']) ))
	{
	echo 'Please set the "applicationpath" setting in the "/Config/IncludePathsConfig.ini" file';
	exit(1);
	}

// Define path to application directory
define('APPLICATION_PATH', $pathsConfigArray['applicationpath'] );

//set the include paths
set_include_path (
	APPLICATION_PATH
	. PATH_SEPARATOR
	. APPLICATION_PATH . '/Vendor/simpletest_1-0-1/'
	. PATH_SEPARATOR
	. get_include_path()
	);


require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

if ( isset($argv[1]) )
	{
	$test_path = $argv[1];
	}
else	{
	$test_path = 'AllTests.php';
	}

//RUN THE TESTS
$testSuite = new TestSuite('Specified Test');
$testSuite->addFile(dirname(__FILE__) .'/'.$test_path);

exit ( $testSuite->run(new TextReporter() ) ? 0 : 1);

