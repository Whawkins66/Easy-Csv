<?php

require realpath('./Easy_csv.php');

$easy_csv = new Easy_csv();

// Set optional column headings.
$easy_csv->headings(array(
	'User ID',
	'User Name',
	'Date Created'
));

// This initiates output to the browser as a forced file download.
$easy_csv->download('New Users Report.csv');

$sample_data = array();

$sample_data[] = array(1, 'Test Testerson', '20-08-2013T01:22:03');
$sample_data[] = array(2, 'Some User', '20-08-2013T02:15:12');
$sample_data[] = array(3, 'Test Testerson II', '22-08-2013T15:03:23');

// Add data in one big batch.
// Since we already started outputting data, this will be immediately outputted.
$easy_csv->data($sample_data);

// You could also add data one row at a time.
foreach ($sample_data as $row)
	$easy_csv->data($row);

exit;