
<?php


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);

error_reporting(0);

session_start();
include ('authenticate.php');

$userid =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$email =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['status']);
$role = strip_tags($_SESSION['status']);




if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

include('settings.php');



if($nylas_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Nylas API Access Token  at <b>settings.php</b> File</div><br>";
exit();

}


if($chatgpt_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Chatgpt Access Token at <b>settings.php</b> File</div><br>";
exit();

}



if($nylas_calendar_id ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Nylas Google Calendar ID at <b>settings.php</b> File</div><br>";
exit();

}



//$nylas= strip_tags($_POST[nylas']);

// Make API Call to NYLAS AI
//$url ="https://api.nylas.com/calendars?limit=10&metadata_value=$email";


$url ="https://api.nylas.com/events?limit=30&calendar_id=$nylas_calendar_id";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $nylas_accesstoken"));  
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_param);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$output = curl_exec($ch); 

if($output == ''){
echo "<div style='background:red;color:white;padding:10px;border:none;'>API Call to Nylas API Failed. Ensure there is an Internet  Connections...</div><br>";
exit();
}




$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// catch error message before closing
if (curl_errno($ch)) {
   // echo $error_msg = curl_error($ch);
}

curl_close($ch);


$json = json_decode($output, true);
$account_id = $json[0]["account_id"];


$mx_error = $json["message"];
if($mx_error != ''){
echo "<div style='background:red;color:white;padding:10px;border:none;'>&nbsp;&nbsp;&nbsp;&nbsp;Nylas API Error Message: $mx_error</div><br>";
exit();
}



if($account_id != ''){

echo "<div style='color:white;background:green;padding:10px;'><h2>&nbsp;&nbsp;&nbsp;&nbsp;Events Medical Appointments Calendars Successfully Loaded</h2></div>";

}
else {
echo "<div style='color:white;background:red;padding:10px;'><h2>&nbsp;&nbsp;&nbsp;&nbsp;No Medical  Appointments Found in Events Calendars.  Please Book an Appointments</h2></div>";
exit();

}   


echo '<div class="row"><div class="col-sm-1"></div>
<div class="col-sm-10">
<table border="0" cellspacing="2" cellpadding="2" class="table table-striped_no table-bordered table-hover"> 
      <tr> 
<th> <font face="Arial">FullName</font> </th> 
          <th> <font face="Arial">Appointment Title</font> </th> 
          <th> <font face="Arial">Appointment Message</font> </th> 
          <th> <font face="Arial">Appointment Date</font> </th> 
<th> <font face="Arial">Appointment Time</font> </th> 
<th> <font face="Arial">Action</font> </th> 




      </tr>';

foreach($json as $row){


$account_id = $row['account_id'];
$calendar_id = $row['calendar_id'];
$id = $row['id'];
$description = $row['description'];
$title = $row['title'];
foreach($row['participants'] as $row2){
$fullname = $row2['name'];
$email = $row2['email'];
$updated_at = $row['updated_at'];

$date = $row['metadata']['date'];
$time = $row['metadata']['time'];
$timing = $row['metadata']['timing'];

echo "<tr class='rec_$id' > 

<td>
($id)
<b>$fullname</b>
</td>

         
                  
                  <td>$title</td>



                  <td>$description</td> 

                  <td>$date</td> 
 <td>$time</td>  
                 
 <td>
<div>
<button type='button'  class='btn btn-primary btn-sm btn_callx' data-toggle='modal' data-target='#myModal_chatgpt'
data-id='$id'
data-fullname='$fullname'
data-email='$email'
data-title='$title'
data-desc='$description'

>Prescribe Patients Drugs Via ChatGPT & Send Mail Via Nylas</button></div><br>



<div><button type='button'  class='btn btn-warning btn-sm btn_summary' data-toggle='modal' data-target='#myModal_summary'
data-id='$id'
data-fullname='$fullname'
data-email='$email'
data-title='$title'
data-desc='$description'

>Summarize Patients Medical Message Via Expert.AI</button></div>


<div><button type='button'  class='btn btn-danger btn-sm btn_sentiment' data-toggle='modal' data-target='#myModal_sentiment'
data-id='$id'
data-fullname='$fullname'
data-email='$email'
data-title='$title'
data-desc='$description'

>Patients Medical Message Sentimental Analysis Via Expert.AI</button></div>




   <div style='display:none' class='loader-delete_$id'></div>
   <div style='display:none' class='result-delete_$id'></div>
<button style='display:none' class='btn btn-danger delete_btn' data-id='$id'  title='Delete Appointment' disabled>Delete Appointment</button>


</td>

              </tr>";





}
}
echo "</div><div class='col-sm-1'></div></div>";







}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}


?>
