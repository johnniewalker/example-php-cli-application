<?php

return 'This is a command line utility that outputs a Comma-Separated Values (CSV) file containing the sales department payment dates for the remainder of this year.

	Usage:
	'. $applicationScript .' <option>

	<option> is the destination path and filename where the CSV data is written to.

	Example:
	php '. $applicationScript .' myfile.csv

	Note: The filepath that you provide must not exist already. Otherwise an error will be shown.';