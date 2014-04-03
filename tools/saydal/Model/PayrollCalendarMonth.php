<?php


/**
*@desc Represents a month of the payroll calendar
*/
class Model_PayrollCalendarMonth
	{
	/**
	*@desc just to use in non-day specific date functions
	*/
	const FIRST_DAY_OF_MONTH_NUMERIC = 1;

	/**
	* @desc make this magic number explicit
	*/
	const FIFTEENTH_DAY_OF_MONTH_NUMERIC = 15;

	/**
	*@desc default format of month name
	*/
	private $_monthNameFormat = '%b';

	/**
	*@desc default format of date
	*/
	private $_dateFormat = '%Y-%m-%d';

	/**
	*@desc
	* @param int year in YYYY format.
	* @return array of PayrollMonths objects for the given year
	* @throws Exception if year provided is out-of-bounds
	*/
	static public function createPayrollMonths( $year)
		{
		if ( ( 1970 > $year) || ( 2037 < $year) )
			{
			throw new Exception('Unable to handle year ' . $year);
			}

		for ($idx = 1; $idx < 13; $idx++ )
			{
			$payrollMonths[$idx] = new Model_PayrollCalendarMonth($year, $idx);
			}

		return $payrollMonths;
		}


	/**
	* @desc  simple facade to getdate()
	* @param int year in YYYY format
	* @param month numeric 1= jan
	* @param day of month 1 = 1st day
	*/
	static public function getDateSimple($year = null, $monthNumeric = null, $dayOfMonth = null)
		{
		return getdate( mktime(0,0,0,$monthNumeric, $dayOfMonth, $year) );
		}

	/**
	*@desc
	* @param int year in 4 digit format YYYY
	* @param int month 1 = Jan, 12 = Dec
	* @throws Exception if date provided is not valid
	*/
	public function __construct($year , $monthNumeric)
		{
		$this->_year = (int) $year;
		$this->_monthNumeric = (int) $monthNumeric;

		//check that 1st of this month and year is a valid date
		if (!(checkdate ( $this->_monthNumeric , self::FIRST_DAY_OF_MONTH_NUMERIC , $this->_year )))
			{
			throw new Exception('Invalid date provided: year given was '. $this->_year .' , monthNumeric given was '. $this->_monthNumeric);
			}

		}



	/**
	* @desc returns the name of the month
	* @param string format used by strftime() to generate the month name - optional mainly used for testing
	* @return string
	*/
	public function getMonthName( $monthNameFormat = null )
		{
		$format = ($monthNameFormat) ? $monthNameFormat : $this->_monthNameFormat;

		return strftime( $format , $this->getTimeStamp() );
		}

	/**
	*@desc determines SalaryPaymentDate of this object's month - according to rules:
	* if last day of month is weekday - returns date of last day of month
	* else - returns date of last week day before last day of month
	* @param string format used by strftime() to generate the dates - optional mainly used for testing
	* @return date string in the form of 'YYYY-MM-DD' or according to the dateFormat provided
	*/
	public function getSalaryPaymentDate($dateFormat = null)
		{
		$format = ($dateFormat) ? $dateFormat : $this->_dateFormat;

		$dayOfWeekNumeric = $this->getNumericDayOfWeekOfLastDayOfMonth();

		if ( $this->isWeekday( $dayOfWeekNumeric ) )
			{
			return strftime( $format , $this->getTimeStamp( $this->getNumDaysInMonth() ) );
			}
		if ($this->isSaturday( $dayOfWeekNumeric ))
			{
			return strftime( $format , $this->getTimeStamp( ($this->getNumDaysInMonth() - 1)  ) );
			}

		//else it must be sunday
		return strftime( $format , $this->getTimeStamp( ($this->getNumDaysInMonth() - 2)  ) );
		}

	/**
	*@desc determines the Bonus Payment Date of this object's month - according to rules:
	* if the 15th of the month is a weekday - Bonus Payment Date is the 15th
	* else the Bonus Payment Date is the Wednesday following the 15th of the month
	* @param string format used by strftime() to generate the dates - optional mainly used for testing
 	* @return date string in the form of 'YYYY-MM-DD' or according to the dateFormat provided
	*
 	* @todo if these rules need to change, convert the '15th' day of the month into a more abstract value, perhaps, NormalBonusDayOfMonth
	* in that case we would have to perform a different operation to determine the following Wednesday
	*/
	public function getBonusPaymentDate($dateFormat = null)
		{
		$format = ($dateFormat) ? $dateFormat : $this->_dateFormat;

		$dayOfWeekNumeric = $this->getNumericDayOfWeekByTimestamp( $this->getTimeStamp( self::FIFTEENTH_DAY_OF_MONTH_NUMERIC ) );

		if ( $this->isWeekday( $dayOfWeekNumeric ) )
			{
			return strftime( $format , $this->getTimeStamp( self::FIFTEENTH_DAY_OF_MONTH_NUMERIC ) );
			}
		if ($this->isSaturday( $dayOfWeekNumeric ))
			{
			return strftime( $format , $this->getTimeStamp( ( self::FIFTEENTH_DAY_OF_MONTH_NUMERIC + 4 ) ) );
			}

		//else it must be sunday
		return strftime( $format , $this->getTimeStamp( ( self::FIFTEENTH_DAY_OF_MONTH_NUMERIC + 3 ) ) );
		}

	/**
	*@desc determine if any of this month's payments occur after the current date (deadline date)
	* @param null | array in the form of the return value from getdate()
	* @return False if payments occur on or after deadline date
	*/
	public function havePaymentDatesPassed($deadlineDate = null)
		{

		if ($deadlineDate === null)
			{
			$deadlineTime = mktime(0,0,0);
			}
		else	{
			$deadlineTime = mktime(0,0,0,$deadlineDate['mon'], $deadlineDate['mday'], $deadlineDate['year']);
			}

		$salaryTime = mktime(0,0,0,$this->getSalaryPaymentDate('%m'), $this->getSalaryPaymentDate('%d'), $this->getSalaryPaymentDate('%Y'));

		if ($salaryTime < $deadlineTime )
			{
			return TRUE;
			}
		}


	private function getNumericDayOfWeekByTimestamp( $timestamp )
		{
		return date( 'w', $timestamp );
		}


	private function getNumericDayOfWeekOfLastDayOfMonth()
		{
		$numDaysInMonth = date( 't', $this->getTimeStamp() );

		return $this->getNumericDayOfWeekByTimestamp( $this->getTimeStamp( $numDaysInMonth ) );
		}

	private function getNumDaysInMonth()
		{
		return date( 't', $this->getTimeStamp() );
		}

	/**
	*@desc
	* @param int NumericDayOfWeek 0 = Sunday
	* @return TRUE if NumericDayOfWeek is a Saturday
	*/
	private function isSaturday( $dayOfWeekNumeric )
		{

		if ( $dayOfWeekNumeric == 6 )
			{
			return TRUE;
			}
		}

	/**
	*@desc
	* @param int NumericDayOfWeek 0 = Sunday 6= saturday
	* @return TRUE if NumericDayOfWeek falls between 0 - 6
	*/
	private function isWeekday( $dayOfWeekNumeric )
		{

		if ( ( 0 < $dayOfWeekNumeric ) && ( $dayOfWeekNumeric < 6 ) )
			{
			//its greater than sunday AND less than saturday
			return TRUE;
			}
		}

	/**
	* @return the month in mumeric format for this object's month - 1 = jan
	*/
	private function getMonthNumeric()
		{
		return $this->_monthNumeric;
		}


	/**
	*@desc
	* @param integer - the day of the month 1st of the month = 1
	* @return Unix timestamp for either :
	*  the first day of this object's month
	*  the optional numeric day provided
	*/
	private function getTimeStamp( $forDay = null )
		{
		if (!( $forDay ))
			{
			//just set it to first day
			$forDay = self::FIRST_DAY_OF_MONTH_NUMERIC;
			}
		else	{

			//check date for sanity
			if (!(checkdate ( $this->_monthNumeric , $forDay , $this->_year )))
				{
				throw new Exception('Invalid day of month provided to '. __FUNCTION__ .' - '. (int) $forDay);
				}
			}

		return mktime(
			0, //hour
			0, //minute
			0, //second
			$this->getMonthNumeric() , //month
			$forDay,
			$this->_year
			//,-1 //daylight saving time state is unknown deprecated
			);
		}


	}