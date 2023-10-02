

<?php
//error_reporting(0);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

session_start();
include ('authenticate.php');

$userid =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$email =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['status']);
$role = strip_tags($_SESSION['status']);







include('settings.php');
include('data6rst.php');



$p_date = strip_tags($_POST['p_date']);
$p_time = strip_tags($_POST['p_time']);

$title = strip_tags($_POST['title']);
$desc = strip_tags($_POST['desc']);
$calendar_id = strip_tags($_POST['calendar']);



$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);
$tm = time();
$titlex ="Medical Appointments on $title by -- $fullname";
$descx ="Medical Appointments on $title.  Details: $desc";

$subject ="$fullname Just Booked a Medical Appointments. -- $tm";
$message =$descx;

if ($email == ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Email Address is empty</font></div>";
exit();
}

$em= filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$em){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Email Address is Invalid</font></div>";
exit();
}

$ip= filter_var($ipaddress, FILTER_VALIDATE_IP);
if (!$ip){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>IP Address is Invalid</font></div>";
exit();
}

		 

$timer= time();
$text_check = $desc;

$fullname =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));

$data_param ='
{
"name": "Medical Appointment",
  "title": "'.$titlex.'",
  "calendar_id": "'.$calendar_id.'",
  "visibility": "private",
  "busy": true,
  "participants": [
    {
      "name": "'.$fullname.'",
      "email": "'.$email.'",
      "status": "yes"
    }
  ],
  "description": "'.$desc.'",
  "when": {
    "time": "'.$timer.'"
  },
  "location": "",
  "recurrence": {
    "rrule": [
      "RRULE:FREQ=WEEKLY;BYDAY=MO"
    ],
    "timezone": "America/New_York"
  },
"metadata": {
"date": "'.$p_date.'",
"time": "'.$p_time.'",
"timing": "'.$timer.'"
}
}
';




// Make API Call to Nylas Events API

//$url ="https://api.nylas.com/events?notify_participants=true";

$url ="https://api.nylas.com/events";



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $nylas_accesstoken"));  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_param);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$output = curl_exec($ch); 

if($output == ''){
echo "<div style='background:red;color:white;padding:10px;border:none;'>API Call to Nylas Events API Failed. Ensure there is an Internet  Connections...</div><br>";
exit();
}



$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// catch error message before closing
if (curl_errno($ch)) {
   // echo $error_msg = curl_error($ch);
}

curl_close($ch);

$json = json_decode($output, true);
$account_id = $json["account_id"];
$id = $json["id"];

$mx_error = $json["message"];
if($mx_error != ''){
echo "<div style='background:red;color:white;padding:10px;border:none;'>API Error Message: $mx_error</div><br>";
exit();
}



if($account_id != ''){


echo "<div style='color:white;background:green;padding:10px;'>Appointments Successfully Created via Nylas Events API</div>";

echo "<script>alert('Appointments Successfully Created via Nylas Events API'); location.reload();</script>";



/*
// Send Eamil to Notify Doctors Via Nylas



$data_param= '{
  "subject": "'.$subject.'",
  "to": [
    {
      "email": "'.$admin_email.'",
      "name": "'.$admin_name.'"
    }
  ],
  "from": [
    {
      "name": "'.$fullname.'",
      "email": "'.$email.'"
    }
  ],
  "reply_to": [
    {
      "name": "'.$admin_name.'",
      "email": "'.$admin_email.'"
    }
  ],
  "body": "'.$message.'"
}';


$url ="https://api.nylas.com/send";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $nylas_accesstoken"));  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_param);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$output = curl_exec($ch); 


$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// catch error message before closing
if (curl_errno($ch)) {
    //echo $error_msg = curl_error($ch);
}

curl_close($ch); 


$json = json_decode($output, true);
$account_id = $json["account_id"];
$id = $json["id"];

$mx_error = $json["message"];
if($mx_error != ''){
echo "<div style='background:red;color:white;padding:10px;border:none;'>API Error Message: $mx_error</div><br>";
exit();
}



if($account_id != ''){

echo "<div style='background:green;color:white;padding:10px;border:none;'> Email successful Sent to Doctors/Medical Teams Via Nylas Rest API</div><br>";

echo "<script> alert('Email successfully Sent Via Nylas Rest API');
 location.reload();</script>";
}else{


echo "<div style='background:red;color:white;padding:10px;border:none;'> Email sending Via Nylas Rest API Failed. Ensure that everything is set </div><br>";

}

*/










}
else {
echo "<div style='color:white;background:red;padding:10px;'>There is an Issue Creating Appointments  via Nylas API. Please Check Internet Connections</div>";
exit();

}   



}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}





?>



