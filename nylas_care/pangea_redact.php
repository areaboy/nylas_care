
<style>
.xcss{
display:inline-block;background:fuchsia;color:white;padding:8px;border:none;border-radius:15%;

}
.xcss:hover {
background: black;
color:white;
}
</style>

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
$calendar = strip_tags($_POST['calendar']);


$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);
$tm = time();
$titlex ="Medical Appointments on $title -- $tm";
$descx ="Medical Appointments on $title.  Details: $desc";



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


if($text_check ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>URL Link to Check Cannot be Empty</div><br>";
exit();
}



if($redact_access_token ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Redact Access Token is Empty</div><br>";
exit();
}

 $data_param= '{"return_result":true,"debug":true,
"rules":["DATE_TIME","IP_ADDRESS","URL","EMAIL_ADDRESS","NRP","LOCATION","PERSON","PHONE_NUMBER","US_DRIVER_LICENSE","US_ITIN","US_PASSPORT","US_SSN","CREDIT_CARD","CRYPTO","IBAN_CODE","US_BANK_NUMBER"],
"text": "'.$text_check.'"}';



$url ="https://redact.aws.us.pangea.cloud/v1/redact";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $redact_access_token"));  
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





if($output ==''){

echo "<div style='background:red;color:white;padding:10px;border:none;'>
 Please Ensure there is Internet Connections and Try Again</div><br>";
exit();
}


$json = json_decode($output, true);

$redacted_text = $json['result']['redacted_text'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];
$count = $json['result']['count'];



if($request_id !='' && $status != 'Success'){
echo "<div style='background:red;color:white;padding:10px;border:none;'>
 Sensitive Data Redaction Failed. Try Again.  Error: $summary  </div><br>";
exit();

}


if($request_id !='' && $status == 'Success' ){
//echo "<div style='background:green;color:white;padding:10px;border:none;'>Patients Sensitive Data Redaction Successful</div><br>";
}

if($count >0){



echo "


<script>
$(document).ready(function(){
$('#redact_btn').click(function(){
//$(document).on( 'click', '.redact_btn', function(){ 
var p_date= '$p_date';
var p_time= '$p_time';
var title= '$title';
var desc=  $('.message_new').val();
var calendar= '$calendar';

if(desc==''){
alert('Medical Message Cannot be Empty');


}

else{

$('#loader_redact').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Please Wait!.  Calendar Appointment Booking  & Email Sending in Progress..</div>');
var datasend = {p_date:p_date, p_time:p_time, title:title, desc:desc, calendar:calendar};


$.ajax({
			
			type:'POST',
			url:'nylas_book_appointments.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_redact').hide();
$('#result_redact').html(msg);


			
			}
			
		});
		
		}
		
	});
	});				





</script>


";



echo "


<script>
$(document).ready(function(){
$('#redacto_btn').click(function(){
//$(document).on( 'click', '.redact_btn', function(){ 
var p_date= '$p_date';
var p_time= '$p_time';
var title= '$title';
var desc=  '$desc';
var calendar= '$calendar';
if(desc==''){
alert('Medical Message Cannot be Empty');


}

else{

$('#loader_redacto').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Please Wait!.  Calendar Appointment Booking  & Email Sending in Progress..</div>');
var datasend = {p_date:p_date, p_time:p_time, title:title, desc:desc,calendar:calendar};


$.ajax({
			
			type:'POST',
			url:'nylas_book_appointments.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_redacto').hide();
$('#result_redacto').html(msg);


			
			}
			
		});
		
		}
		
	});
	});				





</script>


";









echo "<div class='well'>
<div style='background:green;color:white;padding:10px;border:none;'>Patients Sensitive Data Redaction Successful</div><br>

<b>Redacted Patients Identifiable Informations(PII):</b><br>                             
";



                    
foreach($json['result']['report']['recognizer_results'] as $row){

$field_type = $row['field_type'];
$score = $row['score'];
$percent =  $score * 100;
$percent2 ="$percent %";
$text = $row['text'];
$redacted = $row['redacted'];

echo "<span class='xcss'><b>$field_type: </b><br>$text</span>";

}
echo "
</br><br>

<b>Redacted Text Message:</b><br> $redacted_text
<textarea cols='10' rows='5' class='form-control message_new' >$redacted_text</textarea>
<br>
<div class='form-group'>
<div id='loader_redact'></div>
<div id='result_redact' class='myform_clean_redact'></div>
                    </div>
<input type='button' id='redact_btn' class='pull-right btn btn-primary redact_btn' value='Edit Redacted Message & Continue with Appointments Booking' />

<br><br>
<center><h2>OR</h2></center>


<br> 
<b>Original Text Message Content Before Redaction: </b>$text_check</br><br>
<div class='form-group'>
<div id='loader_redacto'></div>
<div id='result_redacto' class='myform_clean_redacto'></div>
                    </div>
<input type='button' id='redacto_btn' class='pull-right btn btn-primary redacto_btn' value='Continue with Appointments Booking' />


</div>
<script>

$(document).ready(function(){
$('#p_btn').hide();
});
</script>
";

exit();

}else{





echo "


<script>
$(document).ready(function(){
$('#rx_btn').click(function(){
//$(document).on( 'click', '.redact_btn', function(){ 
var p_date= '$p_date';
var p_time= '$p_time';
var title= '$title';
var desc=  '$desc';
var calendar= '$calendar';
if(desc==''){
alert('Medical Message Cannot be Empty');


}

else{

$('#loader_rx').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Please Wait!. Calendar Appointment Booking  & Email Sending in Progress..</div>');
var datasend = {p_date:p_date, p_time:p_time, title:title, desc:desc, calendar:calendar};


$.ajax({
			
			type:'POST',
			url:'nylas_book_appointments.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_rx').hide();
$('#result_rx').html(msg);


			
			}
			
		});
		
		}
		
	});
	});				





</script>


";




echo "<br><div style='background:green;color:white;padding:10px;border:none;'>No Patients Sensitive Data Found</div><br>";


echo "<div class='well'>
<br> 
<b>Original Text Message Content: </b>$text_check</br><br>
<div class='form-group'>
<div id='loader_rx'></div>
<div id='result_rx' class='myform_clean_rx'></div>
                    </div>
<input type='button' id='rx_btn' class='pull-right btn btn-primary rx_btn' value='Continue with Appointments Booking' />
<script>
$(document).ready(function(){
$('#p_btn').hide();
});
</script>

</div>";



exit();
}






}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}





?>



