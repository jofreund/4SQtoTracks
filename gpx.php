<?php

if (file_exists('4sq.gpx')) {
    $xml = simplexml_load_file('4sq.gpx');
 
    //print_r($xml);
	$waypoints = $xml->wpt;
	
	$finalXML = generateXMLHeader();
	
	foreach ($waypoints as $waypoint) {
		$square = generateSquare($waypoint['lat'],$waypoint['lon']);
		
		$finalXML .= generateTrackFromSquare($square);
	}
	
	$finalXML .= generateXMLFooter();
	
	echo($finalXML);
	
} else {
    exit('Failed to open 4sq.gpx.');
}

function generateSquare($lat,$lon) {
	$horizontal = floatval(0.000002);
	$vertical = floatval(0.000000030);
	
	$square = array();
	
	$lat = floatval($lat);
	$lon = floatval($lon);
	
	$square[0]['lat'] = $lat-$horizontal;
	$square[0]['lon'] = $lon;
	
	$square[1]['lat'] = $lat;
	$square[1]['lon'] = $lon-$vertical;
	
	$square[2]['lat'] = $lat+$horizontal;
	$square[2]['lon'] = $lon;
	
	$square[3]['lat'] = $lat;
	$square[3]['lon'] = $lon+$vertical;
	
	return $square;
}

function generateTrackFromSquare($square) {
	$xml = "<trk>
		<name>4sq Track</name>
		<trkseg>";
	foreach ($square as $point) {
		$xml .= '
			<trkpt lat="'.$point['lat'].'" lon="'.$point['lon'].'">
				<ele>0</ele>
			</trkpt>';
	}
	$xml .= "
		</trkseg>
	</trk>
	";
	
	return $xml;
	
}

function generateXMLHeader() {
	$xml = '<?xml version="1.0" encoding="UTF-8"?>
	<gpx xmlns="http://www.topografix.com/GPX/1/1" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:mytracks="http://mytracks.stichling.info/myTracksGPX/1/0" creator="myTracks" version="1.1" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd">
	';
	
	return $xml;
}

function generateXMLFooter() {
	$xml = '</gpx>';
	
	return $xml;
}


?>