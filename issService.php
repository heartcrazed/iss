<?php
session_start();

//### [check GET Data for validity]

if(isset($_GET['lat']) && isset($_GET['lng']) && isset($_GET['spacestationpasses']) && !isset($_GET['weather']))
{
	$json_timesSpaceStation = file_get_contents("http://api.uhaapi.com/passes?satid=25544&lat=".$_GET['lat']."&lng=".$_GET['lng']); //read JSON uhaapi for ISS (id 25544)
	//### [return error message if service unavailable]
	echo $json_timesSpaceStation;
}
else if(isset($_GET['lat']) && isset($_GET['lng']) && isset($_GET['weather']) && !isset($_GET['spacestationpasses'])) //return weather
{
	$lat = str_replace('.',',',$_GET['lat']); //change float format '.' to ','
	$lng = str_replace('.',',',$_GET['lng']);
	$lat*=1000000; //multiply by 1000000 as google weather api expects
	$lng*=1000000;
	$weatherApiUrl="http://www.google.com/ig/api?weather=,,,".$lat.",".$lng; //client has to multiply by 1000000 because of google weather API
	$weatherApiResponse = file_get_contents($weatherApiUrl); //read google xml weather webservice
    $weatherXml = new SimpleXMLElement($weatherApiResponse); //create SimpleXMLElement instance with received weatherdata
	$condition = $weatherXml->weather->current_conditions->condition[data];
	if(strcmp((string) $condition, "") == 0)
	{
		$condition = " not available.";
	}
	$weatherArray = array ('weather' => (string)$condition);	//read weatherconditions
	echo json_encode($weatherArray);
}
else
{
	echo "{}"; //an error occured - send empty JSON
}
?>