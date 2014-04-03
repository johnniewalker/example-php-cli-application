<?php

/**
*@desc Represents a CSV file
*
* @todo add the option of overwriting files if required
*/
class Library_CSVFile
	{

	/**
	*@desc the number of columns that were passed to the constructor
	*/
	private $_numColumns;

	/**
	*@desc represent the columns of the csv data
	*/
	private $_columnsArray;


	/**
	*@desc the handle of the csv file
	*/
	private $_fileHandle;

	/**
	*@desc boolean is marked as TRUE when columns names are written to the file
	*/
	private $_hasWrittenColnames;


	/**
	*@desc opens a file for writing, throws exception if file exists already
	* @param string file name to write-to
	* @param columns numerically indexed array of strings to represent each column of the csv file.
	*  The number of elements defines the number of possible columns that can be written-to
	* @throws Exception if file already exists
	*/
       	public function __construct( $file, $columnsArray )
		{

		if ( file_exists( $file ) )
			{
			throw new Exception('Cannot create new csv file. It already exists');
			}


		if (! (is_array( $columnsArray ) ))
			{
			throw new Exception('columnsArray should be an array');
			}

		$this->_numColumns = count($columnsArray);

		if (! ( $this->_numColumns > 0 ))
			{
			throw new Exception('No columns specified. We need at least one column to write csv data to');
			}

		$this->_columnsArray = $columnsArray;

		//else open it up for writing (use t mode (translate) for portability )
		$this->_fileHandle = fopen($file, 'wt');

		if (!( $this->_fileHandle ))
			{
			throw new Exception('Unable to open file to write to' . $file);
			}
		}


	/**
	*@desc writes a record to the csv file
	* @param array numerically indexed array of the data to write - must have the same number of elements as the columns array
	* @return Returns the length of the written string or FALSE on failure.
	* @throws Exception on invlaid arguments
	*/
	public function writeRecord( $cellDataArray )
		{

		if (! (is_array( $cellDataArray ) ))
			{
			throw new Exception('cellDataArray should be an array');
			}

		if ( ! (count( $cellDataArray ) == $this->_numColumns ))
			{
			throw new Exception('cellDataArray should of equal length to the columnsArray array');
			}

		if (!($this->_hasWrittenColnames))
			{
			$this->putCells( $this->_columnsArray );
			$this->_hasWrittenColnames = TRUE;

			}

		return $this->putCells( $cellDataArray );
		}

	/**
	*@desc physically write the cells to the file
	* @param array numerically indexed array of the data to write - must have the same number of elements as the columns array
	* @return Returns the length of the written string or FALSE on failure.
	*/
	private function putCells( $cellDataArray )
		{
		return  fputcsv ( $this->_fileHandle , $cellDataArray, ',', '"');
		}


	/**
	*@desc close the file handle
	*@return void
	*/
	public function closeFile()
		{
		if (!(	fclose( $this->_fileHandle )))
			{
			throw new Exception('failed to close file');
			}

		unset( $this->_fileHandle );
		}

	/**
	*@desc closes file suppressing errors
	*/
	public function __destruct()
		{
		if ( isset($this->_fileHandle) )
			{
			@fclose( $this->_fileHandle );
			}
		}
	}