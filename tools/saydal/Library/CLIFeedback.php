<?php

/**
*@desc Handle used by the controller to write feedback output
*/
class Library_CLIFeedback
	{

	/**
	*@desc writes a message to STDOUT
	*@param string the message to write
	*@return void
	*/
	public function addMessage( $message )
		{
		fwrite(STDOUT, $message . PHP_EOL);
		}

	/**
	*@desc writes a message to STDERR
	*@param string the message to write
	*@return void
	*/
	public function addErrorMessage( $message )
		{
		fwrite(STDERR, $message . PHP_EOL);
		}

	}
