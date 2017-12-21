<?php

require_once('Library/CSVFile.php');

class UnitTestCase_testOf_Library_CSVFile extends UnitTestCase
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

	function testCSVWritesFiles()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$cSVFile->writeRecord( array('February','2004-02-27','2004-02-27') );

		$cSVFile->closeFile();

		$this->assertTrue( file_exists( $this->_testFileName  ));
		}

	/**
	* this test leaves an empty file - so if it is run last, do not expect to find any contents in it
	*/
	function testExceptionThrownUponIncongruentColumnCounts()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		try 	{
			$cSVFile->writeRecord( array('Line3','Line') );
			$this->fail('Expected Exception');
			}
		catch (Exception $e )
			{
			$this->pass('Expected Exception', $e);
			}
		}

	function testCSVWrittenData()
		{

		$this->assertFalse( file_exists( $this->_testFileName  ));

		$cSVFile = new Library_CSVFile(
				$this->_testFileName  ,
				array(	'Month Name',
					'Salary Payment Date',
					'Bonus Payment Date') );

		$cSVFile->writeRecord( array('Line1','Line','Line') );
		$cSVFile->writeRecord( array('Line2','Line','Line') );
		$cSVFile->writeRecord( array('Line3','Line','Line') );

		$cSVFile->closeFile();

		$lines = file ( $this->_testFileName, FILE_IGNORE_NEW_LINES );

		$this->assertEqual( trim($lines[0]), '"Month Name","Salary Payment Date","Bonus Payment Date"' );
		$this->assertEqual( trim($lines[1]), 'Line1,Line,Line' );
		$this->assertEqual( trim($lines[2]), 'Line2,Line,Line' );
		$this->assertEqual( trim($lines[3]), 'Line3,Line,Line' );
		}






	}
