<style>


.cssx{
background:fuchsia;color:white;padding:6px;border:none;border-radius:25%;
}


.cssx:hover{
background: black;
color:white;

}

</style>
<?php


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);

error_reporting(0);



if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

include('settings.php');



if($nylas_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Nylas API Access Token  at <b>settings.php</b> File</div><br>";
exit();

}


if($expertai_username ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Expert.AI Username at <b>settings.php</b> File</div><br>";
exit();

}

if($expertai_password ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Expert.AI Password  at <b>settings.php</b> File</div><br>";
exit();

}




$message= strip_tags($_POST['msg_text']);

// Make API Call to Expert.AI to Generate Access Token

$url = 'https://developer.expert.ai/oauth2/token';
$ch = curl_init($url);

$uname =$expertai_username;
$upass  =$expertai_password;
$data = array(
    'username' => $uname,
    'password' => $upass
);
$payload = json_encode(array($data));
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$access_token = $result;



// Make API Call to ExpertAI Text Summary and KeyPhrases Analysis

$text_post= $message;


$url1 = 'https://nlapi.expert.ai/v2/analyze/standard/en/relevants';

$ch1 = curl_init($url1);
$data1 = array(
    'text' => $text_post
);
$payload1 = json_encode(array("document" => $data1));
curl_setopt($ch1, CURLOPT_POSTFIELDS, $payload1);
curl_setopt($ch1, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token", 'Content-Type:application/json'));
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch1);




$http_status = curl_getinfo($ch1, CURLINFO_HTTP_CODE);

// catch error message before closing
if (curl_errno($ch1)) {
   // echo $error_msg = curl_error($ch1);
}

curl_close($ch1);

if($http_status==200){

echo "<div style='color:white;background:green;padding:10px;'>Email Summary and Keyphrases Successfully Analyzed. See Below</div>";

}
else {
echo "<div style='color:white;background:red;padding:10px;'>There is an Issue Making Email Summary and Keyphrases API Call to ExpertAI. Please Check Internet Connections</div>";
exit();

}   


$json = json_decode($output, true);


echo "<div class='row'>
<div class='col-sm-1'></div> 
<div class='col-sm-10 well'>
<h4> Email Text Main Sentences/Summary</h4>";

$countx= 1;
foreach($json['data']['mainSentences'] as $row){

$countxx = $countx++;
$value = $row["value"];
echo "<span><b>$countxx.)</b> $value</span><br>";

}




echo "<div class='row'>
<div class='col-sm-1'></div> 
<div class='col-sm-10 well'>
<h4> Email Text Key Phrases</h4>";


foreach($json['data']['mainPhrases'] as $row2){

$value = $row2["value"];
echo "<span class='cssx'>$value</span><br><br>";

}
echo "</div>
<div class='col-sm-1'></div> 
</div>";



echo "<div class='row'>
<div class='col-sm-1'></div> 
<div class='col-sm-10 well'>
<h4> Email Text Key Words</h4>";

foreach($json['data']['mainLemmas'] as $row3){

$value = $row3["value"];
echo "<span  class='cssx'>$value</span><br><br>";

}
echo "</div>
<div class='col-sm-1'></div> 
</div>";




}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}


?>
