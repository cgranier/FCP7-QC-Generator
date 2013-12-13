#!/usr/bin/env php
<?php

// readxml.php
// Version 1.0 - 20131018160932
// 
// A program to read Final Cut Pro 7 XML files
// and create relevant csv files to be imported into
// the appropriate Excel timecode spreadsheets.
// Author: Carlos Granier @cgranier
// http://cgranier.com
//

// Read the command line arguments

if ($argc < 2) {
    echo "\n";
    echo "+------------------------------------------------------------------+\n";
    echo "+ ERROR: Insufficient arguments                                    +\n";
    echo "+ USAGE:                                                           +\n";
    echo "+ ./readxml.php xml-input-filename                                 +\n";
    echo "+------------------------------------------------------------------+\n\n";
}
else {
	// Load a FCP XML file with all the edited episode sequences
	$filename = $argv[1];
	// $outfile = $argv[2];
	$outfile = pathinfo($filename)['filename'] . '.csv';
	$xml=simplexml_load_file($filename);

	// Figure out the timebase. Should be 25 or 30
	$timebase = $xml->bin->children->bin->children->sequence->rate->timebase;

	// For each episode, figure out the timecode where every ad break falls
	// and check if they are correct.
	$fo = fopen($outfile, 'w');
	foreach($xml->bin->children->bin as $bin) {
		foreach($bin->children->sequence as $sequence) {
			unset($dataRows);
			$dataRows = array();
			array_push($dataRows,trim($bin->name),trim($sequence[id]));
			foreach($sequence->media->video->track->clipitem as $clipItem)
			  {
			  	if( (intval($clipItem->start) !== 0) && ( ( strpos($clipItem[id],"egro") OR (strpos($clipItem[id],"lug") ) ) ) ) {
			  		array_push($dataRows,$clipItem->start);
				}
			  }
	  		fputcsv($fo, $dataRows);
		}
	}
	fclose($fo);
}
?>
