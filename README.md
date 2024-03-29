--------------------------------

= Say Dal - The Data Access Layer Generation Utility =

[Based on Say Pay - The Payroll Date Generation Utility]

--------------------------------

== Quick Start ==

Check the configuration:
 1. Open the file, "saydal\saydal\saydal.php" in a text editor
 2. Check the locale and timezone settings are correct for your needs

Usage:

This is a command line utility that outputs a Comma-Separated Values (CSV) file containing the sales department payment dates for the remainder of this year.

	Usage:
	saydal.php <option>

	<option> is the destination path and filename where the CSV data is written to.

	Example:
	php saydal.php myfile.csv

	Where:
		'php' - is the interpreter that runs the script.
 		'saydal.php' - the utility script
		'myfile.csv' - is the name of the file that you wish to write-to

	Note: The filepath that you provide must not exist already. Otherwise an error will be shown.


==  Requirements ==

 * PHP 5

The code has been tested extensively on two platforms throughout development:
 * Windows XP - running PHP 5.2.9 (cli)
 * Ubuntu 10.04 LTS (the Lucid Lynx) - PHP 5.3.2-1ubuntu4.7 with Suhosin-Patch (cli)

The code has recently been run on PHP 7.1.7.

It does not rely on any other libraries or frameworks to function.

==  "Minimal" versus "Standard" Versions ***

If you have got the "Minimal Version" then you will not find any "unit tests" or "extras".
Otherwise, the unit tests are included in the version. They were run using the SimpleTest Testing Framework 1.0.1 which would be included with the test cases.


*** About the project ***

The main parts in the project:

src/
 saydal.php 		- the main bootstrap for the application
 Controller/
  MainController.php 	- the main control code for the application
  Library/
   CLIFeedback.php 	- view handle for writing user feedback and error messages
   CSVFile.php 		- the object that represent the CSV file
  Model/
   PayrollCalendarMonth.php - the object that represents the PayrollMonth
  View/
   Help.php 		- a text file containing user help for the cli output

We also expect the Simpletest (simpletest_1.1.0) library for testing.
This should be installed via specification in the project's `composer.json` file.
Our TestRunner.php expects the Simpletest library to be at: 

vendor/ 		    - 3rd party libraries that are not shipped with the project.
 simpletest/
  simpletest/      - [Simplest testing libary current release: SimpleTest v1.1.0.](http://simpletest.org/en/download.html)


==  Standard Version Only ==

Running Tests from the Command Line

First, create a config file that sets the include path to the main application folder, call it:

```
tests\Config\IncludePathsConfig.ini
```

This info helps the `TestRunner.php` to set the base include path. 


Then, point your command line interface to:

saydal\saydal\Tests\TestRunner.php

Then run:
 # php TestRunner.php

Some tests create files in 'TempData' directories.
The files can be deleted but don't delete those directories.






