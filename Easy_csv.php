<?php

class Easy_csv
{
	/*
		Simple class for outputting data in CSV format; optionally print to the browser, or force download as a file.
	*/

	/*
		Character(s) that are used to separate values in a row.
	*/
	protected $_delimiter = ',';

	/*
		Column headings to be printed as the first row; labels essentially.
	*/
	protected $_headings = array();

	protected $_data = array();

	/*
		End of line character; client-dependent.
	*/
	protected $_eol;

	protected $_output_started = false;

	function __construct()
	{
		// Windows users should get Windows-style line-endings.
		// All others get Unix-style.
		$this->_eol = isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') !== false ? "\r\n" : "\n";
	}

	/*
		Set the character(s) to be used as the delimiter.
	*/
	public function delimiter($characters)
	{
		$this->_delimiter = (string) $characters;
	}

	/*
		Define the column headings.
	*/
	public function headings($headings)
	{
		$this->_headings = $headings;
	}

	/*
		Set the data to be outputted.
	*/
	public function data($data)
	{
		if (!is_array($data))
			// Must be an array.
			die('Invalid data given; must be an array of values.');

		// If it isn't a multi-dimensional array, make it so.
		if (!is_array($data[0]))
			$data = array($data);

		for ($i = 0; $i < count($data); $i++)
			$this->_data[] = $data[$i];

		/*
			If output has started, we just output data as soon as we get it.
		*/
		if ($this->_output_started)
			$this->output_data();
	}

	/*
		Output the data to the client as a CSV file download.
	*/
	public function download($filename = 'Unnamed.csv')
	{
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Type: application/octet-stream');
		header('Connection: close');

		$this->output();
	}

	/*
		Print the data to the client; does not force download.
	*/
	public function output()
	{
		$this->output_headings();
		$this->output_data();
	}

	protected function output_data()
	{
		if (count($this->_data) > 0)
			foreach ($this->_data as $data)
			{
				for ($i = 0; $i < count($data); $i++)
					// Remove instances of the delimiter from the data.
					$data[$i] = str_replace($this->_delimiter, '', $data[$i]);

				echo implode($this->_delimiter, $data) . $this->_eol;
			}

		// Once the data is outputted, we don't need it anymore here.
		$this->_data = array();

		$this->_output_started = true;
	}

	protected function output_headings()
	{
		if (count($this->_headings) > 0)
		{
			for ($i = 0; $i < count($this->_headings); $i++)
				// Remove instances of the delimiter from the headings.
				$this->_headings[$i] = str_replace($this->_delimiter, '', $this->_headings[$i]);

			echo implode($this->_delimiter, $this->_headings) . $this->_eol;
		}
	}

}