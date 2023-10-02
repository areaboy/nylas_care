<?php
error_reporting(0);
$positivity = $_GET['positivity'];
$negativity = $_GET['negativity'];
$overall = $_GET['overall'];
$neutra = 0; 

$totalsentiments = $positivity + $negativity + $neutra;

header('Content-Type: application/json');

$data ='[{"id":"1","sentiments":"Positive","scores":"'.$positivity.'"},{"id":"2","sentiments":"Negative","scores":"'.$negativity.'"},{"id":"3","sentiments":"Neutral","scores":"'.$neutra.'"},{"id":"4","sentiments":"Total_Sentiments","scores":"'.$totalsentiments.'"}]
';

echo $data;
?>