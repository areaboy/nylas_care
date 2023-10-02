<?php
//error_reporting(0);
session_start();
include ('authenticate.php');
include ('settings.php');

$userid_sess =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname_sess =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$token_sess =   $userid_sess;
$email_sess =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['status']);
$role = strip_tags($_SESSION['status']);



?>
<!DOCTYPE html>
<html lang="en">

<head>
 
<title>Welcome <?php echo htmlentities(htmlentities($fullname, ENT_QUOTES, "UTF-8")); ?> to Medi-Care Appointment Booking System </title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="landing page, website design" />
  <script src="scripts/jquery.min.js"></script>
  <script src="scripts/bootstrap.min.js"></script>
<link type="text/css" rel="stylesheet" href="scripts/bootstrap.min.css">

<link type="text/css" rel="stylesheet" href="scripts/store.css">
<script src="scripts/moment.js"></script>
	<script src="scripts/livestamp.js"></script>

</head>
<body>



<div class="text-center">
<nav class="navbar navbar-fixed-top style='background:purple' ">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navgator">
        <span class="navbar-header-collapse-color icon-bar"></span>
        <span class="navbar-header-collapse-color icon-bar"></span>
        <span class="navbar-header-collapse-color icon-bar"></span> 
        <span class="navbar-header-collapse-color icon-bar"></span>                       
      </button>
     
<li class="navbar-brand home_click imagelogo_li_remove" ><img class="img-rounded imagelogo_data" src="logo.png"></li>
    </div>
    <div class="collapse navbar-collapse" id="navgator">

      <ul class="nav navbar-nav navbar-right">

<li class="navgate">
<button data-toggle="modal" data-target="#myModal_event" class="invite_btnx btn btn-warning" title='Book Medical Appointments'>Book Appointments </button>
</li>

<li class="navgate">
<button data-toggle="modal" data-target="#myModal_cal" class="invite_btnx btn btn-warning" title='Get Calendar List'>Get Calendar List</button>
</li>


<li class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='logout.php' title='Logout'>Logout</a></button>

</li>
</ul>





    </div>
  </div>


</nav>


    </div><br />
<br /><br />



<div style='width:100vw; height: 100vh;  min-height:600px;'>
 

<div class='row'>

<center><h4>Welcome: <?php echo $fullname_sess; ?> </h4></center>

<div class='col-sm-12 well'>
<h3>Users Medical Info</h3>
<b>Name: </b><?php echo htmlentities(htmlentities($fullname_sess, ENT_QUOTES, "UTF-8")); ?> <br>
<b>Status: </b><?php echo htmlentities(htmlentities($role, ENT_QUOTES, "UTF-8")); ?>




</div></div>









<style>
.report_cssx{
background:#ddd;
padding:10px;
height:70px;
border:none;
color:black;
border-radius:20%;
font-size:16px;
text-align:center;


}


.report_cssx:hover{
background:orange;
color:black;

}

</style>



<script>

// clear Modal div content on modal closef closed
$(document).ready(function(){


$("#myModal_medication").on("hidden.bs.modal", function(){
    //$(".modal-body").html("");
 $('.mydata_empty3').empty(); 
//$('#q').val(''); 
});

});



// loads Calendars

$(document).ready(function(){

var nylas='contact';
$('#loader_nylas_calendar_load').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif">&nbsp;Please Wait, Loading Medical Appointments Events Calendar via Nylas API...</div>');
var datasend = {nylas:nylas};	
		$.ajax({
			
			type:'POST',
			url:'nylas_events_calendar_load.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_nylas_calendar_load').hide();

$('#result_nylas_calendar_load').fadeIn('slow').html(msg);

			}
			
		});
		
					
});
</script>













<div class='row'>


<div id="loader_nylas_calendar_load"></div>
<div id="result_nylas_calendar_load"></div>


</div>










<script>


// Books Appointments


$(document).ready(function(){
$('#p_btn').click(function(){
//$(document).on( 'click', '.p_btn', function(){ 
		
var desc  =         $('#desc').val();
var title =  $(".title:checked").val();
var p_date  =  $('#p_date').val();
var p_time = $(".p_time:checked").val();
var calendar =  '<?php echo $nylas_calendar_id; ?>';

if(calendar ==''){
alert('Please Ask Admin or Doctors to Set Google Calendar ID at settings.php file');
//return false;
}

else if(title==undefined){
alert('Please Select Medical Appointment Services');
//return false;
}


else if(desc==''){
alert('Please Enter Appointment Details');
//return false;
}




 else if(p_date==''){
alert('please Select Appointment date.');
}



 else if(p_time==undefined){
alert('please Select Appointment Time.');
}


else{
$('#loader_v').fadeIn(400).html('<br><br><br><br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif"> Please Wait! .Checking Patients Medical Message for Sensitive Data Redaction....</div>')



var datasend = {p_date:p_date, p_time:p_time, title:title, desc:desc, calendar:calendar};	
		$.ajax({
			
			type:'POST',
			url:'pangea_redact.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
 		
$('#loader_v').hide();
$('#result_v').html(msg);
//setTimeout(function(){ $('#result_v').html(''); }, 9000);


//strip all html elemnts using jquery
var html_stripped = jQuery(msg).text();
//check occurrence of word (Successfully) from backend output already html stripped.
var Frombackend = html_stripped;
var bcount = (Frombackend.match(/Successfully/g) || []).length;
if(bcount > 0){
$('#desc').val('');
$(".title:checked").val('');
$('#p_date').val('');
$(".p_time:checked").val();
//location.reload();

}




		

	}
			
		});
		
		}
	
	})
					
});



</script>


<!--Event Calendar  Modal start -->



<div id="myModal_event" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header"  style='background: #008080;color:white;padding:10px;'>
        <h4 class="modal-title">Medical Appointment Booking System Powered by Nylas Email API, Calendaring API, Events API, and Pangea Redact AI</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">


Easily Book Medical Appointments and have Your Data Secures and Protected in <b>Nylas</b> highly secured Calendaring Events System.<br><br>


        <div class="well">
    		<label>Medical Services</label><br>

<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Dental Care" class="title"/>Dental Care <br>
</div>


<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Eye Care" class="title"/>Eye Care <br>
</div>




<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Gynaecological Care" class="title"/>Gynaecological Care <br>
</div>


<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Paediatric Care" class="title"/>Paediatric Care <br>
</div>



<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Occulist Care" class="title"/>Occulist Care <br>
</div>


<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="General Doctor" class="title"/>General Doctor<br>
</div>


</div>

<br>



 <div class="form-group">
              <label>Medical Details(Message). </label>
              <textarea class="col-sm-12 form-control" cols="3" rows="3" id="desc" name="desc" placeholder="Medical Details(Message).">I am Fred Cool. Am a Diabetic Patients. Am currently Suffering from Malaria. I am from Texas, United State. Has been suffering this Sickness for Years. I am 25 Years Old. My Social Security Number is 555-50-1234. Drivers Licence Number is 0187689235. Banking Info is Bank of America. Phone number is (555) 555-1212</textarea>
            </div>
<br>




 <div class="form-group">
              <label>Appointment Date</label>
              <input type="date" class="col-sm-12 form-control" id="p_date" name="p_date" placeholder="Select Date">
            </div>
<br>





<style>
.time_css{
background:#ccc;padding:6px;border-radius:20%;
}

.time_css:hover{
background:orange;color:black;
}



</style>



    	


        <div role="" class="well">
    		<p><label>Appointment Time</label><br>


<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="10:00:00_10:00 AM" class="p_time"/>10:00 AM <br>
</div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="10:30:00_10:30 AM" class="p_time"/>10:30 AM <br>
</div>
<div class='col-sm-3 time_css'>
 <input type="radio" id="p_time" name="p_time" value="11:00:00_11:00 AM" class="p_time"/>11:00 AM <br>   
</div>

<div class='col-sm-3 time_css'>
 <input type="radio" id="p_time" name="p_time" value="11:30:00_11:30 AM" class="p_time"/>11:30 AM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="12:00:00_12:00 PM" class="p_time"/>12:00 PM <br> </div>
<div class='col-sm-3 time_css'> 
<input type="radio" id="p_time" name="p_time" value="12:30:00_12:30 PM" class="p_time"/>12:30 PM <br></div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="13:00:00_1:00 PM" class="p_time"/>1:00 PM <br>  </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="13:30:00_1:30 PM" class="p_time"/>1:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="14:00:00_2:00 PM" class="p_time"/>2:00 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="14:30:00_2:30 PM" class="p_time"/>2:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="15:00:00_3:00 PM" class="p_time"/>3:00 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="15:30:00_3:30 PM" class="p_time"/>3:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="16:00:00_4:00 PM" class="p_time"/>4:00 PM <br></div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="16:30:00_4:30 PM" class="p_time"/>4:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="17:00:00_5:00 PM" class="p_time"/>5:00 PM <br>
</div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="17:30:00_5:30 PM" class="p_time"/>5:30 PM <br>
</div>



</p>



 <div class="form-group">
						<div id="loader_v"></div>
                        <div id="result_v" class="myform_clean_v"></div>
                    </div>

                    <input type="button" id="p_btn" class="pull-right btn btn-primary p_btn" value="Book Appointments" />

<br>
      </div>
<br><br>
</div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- Event Calendar Modal ends -->










<script>

// loads Calendars

$(document).ready(function(){

var nylasx='contact';
$('#loader_cal').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif">&nbsp;Please Wait, Loading Medical Appointments Events Calendar via Nylas API...</div>');
var datasend = {nylasx:nylasx};	
		$.ajax({
			
			type:'POST',
			url:'nylas_calendar_loadx.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_cal').hide();

$('#result_cal').fadeIn('slow').html(msg);

			}
			
		
});					
});
</script>



<!--Calendar list  Modal start -->

<div id="myModal_cal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"   style='background: #008080;color:white;padding:10px;'>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nylas Calendar list..</h4>
      </div>
      <div class="modal-body">
        <p>  <div class="form-group">
			<div id="loader_cal"></div>
                        <div id="result_cal"></div>
                       </div>
</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Calendar list Modal ends -->






<script>
$(document).ready(function(){
//$('.btn_callx').click(function(){
$(document).on( 'click', '.btn_callx', function(){ 


var id = $(this).data('id');
var email = $(this).data('email');
var fullname = $(this).data('fullname');
var title = $(this).data('title');
var desc = $(this).data('desc');


$('.p_id').html(id);
$('.p_email').html(email);
$('.p_fullname').html(fullname);
$('.p_title').html(title);
$('.p_desc').html(desc);



$('.p_id_value').val(id).value;
$('.p_email_value').val(email).value;
$('.p_fullname_value').val(fullname).value;
$('.p_title_value').val(title).value;
$('.p_desc_value').val(desc).value;

});

});




$(document).ready(function(){
$('#c_btn').click(function(){
		
var emailx = $('.p_email_value').val();
var fullnamex = $('.p_fullname_value').val();
var em_title = $('.p_title_value').val();
var email_message = $('.p_desc_value').val();
var chatgpt_message = $('.chatgpt_message').val();

if(chatgpt_message==""){
alert('Ask ChatGPT to Prescribe Drug');
}


else{

$('#loader_c').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif">&nbsp;Please Wait, Generating Drug Prescription via ChatGPT API...</div>');
var datasend = {emailx:emailx,fullnamex:fullnamex, em_title:em_title, email_message:email_message, chatgpt_message:chatgpt_message};	
		$.ajax({
			
			type:'POST',
			url:'chatgpt_prescribe_drug.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
                        $('#loader_c').hide();
                        $('#result_c').fadeIn('slow').html(msg);
//setTimeout(function(){ $('#result_c').html(''); }, 9000);


//strip all html elemnts using jquery
var html_stripped = jQuery(msg).text();
//check occurrence of word (successful) from backend output already html stripped.
var Frombackend = html_stripped;
var bcount = (Frombackend.match(/successful/g) || []).length;
if(bcount > 0){
//$('.email_message').val('');
//$('.em_title').val('');
}


			}
			
		});
		
		}
		
	})
					
});


</script>




<input type="hidden" class="p_id_value" value="">

<input type="hidden" class="p_email_value" value="">
<input type="hidden" class="p_fullname_value" value="">
<input type="hidden" class="p_title_value" value="">
<input type="hidden" class="p_desc_value" value="">




<!--Chatgpt start -->

<div id="myModal_chatgpt" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"   style='background: #008080;color:white;padding:10px;'>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Prescribe Patients Drugs Via ChatGPT & Send Mail Via Nylas</h4>
      </div>

      <div class="modal-body">

        <p>  

<div class="form-group well">
<b>Patients Fullname:</b> <span class='p_fullname'></span><br>
<b>Patients Email:</b> <span class='p_email'></span><br>
<b>Appointment Title:</b> <span class='p_title'></span><br>
<b>Medical Details:</b> <span class='p_desc'></span><br>
<br>
</div>



<div class="col-sm-12 form-group" style='background:#f1f1f1; padding:16px;color:black'>
<label>Ask ChatGPT to Prescribe Drug.</label><span style='color:red'> Eg. Ask CHATGPT to Prescribe Malaria Drugs</span><br>
<textarea name="" id="" rows="5" cols="5" class="form-control chatgpt_message" placeholder="Eg. Write List of Malaria Drugs"> Prescribe Drug for Malaria Patients</textarea>
</div><br>


<div class="form-group">
			<div id="loader_c"></div>
                       </div>


<div class="form-group">
<button type="button" id="c_btn" class="btn btn-primary cat_cssa"  >Ask ChatGPT</button><br><br>
</div>


<div class="form-group">
                        <div id="result_c"></div>
                       </div>


</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Chatgpt ends -->
















<script>
$(document).ready(function(){
//$('.btn_summary').click(function(){
$(document).on( 'click', '.btn_summary', function(){ 


var id = $(this).data('id');
var email = $(this).data('email');
var fullname = $(this).data('fullname');
var title = $(this).data('title');
var desc = $(this).data('desc');
var msg_text = desc;

$('.p_id').html(id);
$('.p_email').html(email);
$('.p_fullname').html(fullname);
$('.p_title').html(title);
$('.p_desc').html(desc);



$('.p_id_value').val(id).value;
$('.p_email_value').val(email).value;
$('.p_fullname_value').val(fullname).value;
$('.p_title_value').val(title).value;
$('.p_desc_value').val(desc).value;



//start
	
if(desc==""){
alert('There is an Issue Selecting Patients Medical Data');
}


else{

$('#loader_sum').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif">&nbsp;Please Wait, Summarizing Patients Medical Data via  Expert.AI...</div>');
var datasend = {msg_text:msg_text};	
		$.ajax({
			
			type:'POST',
			url:'expert_ai_keyphrases_summary.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
                        $('#loader_sum').hide();
                        $('#result_sum').fadeIn('slow').html(msg);
//setTimeout(function(){ $('#result_sum').html(''); }, 9000);


			}
			
		});
		
		}

//end





});

});





</script>



<!--Summary AI start -->

<div id="myModal_summary" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"   style='background: #008080;color:white;padding:10px;'>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Summarize Patients Medical Data Via Expert.AI</h4>
      </div>

      <div class="modal-body">

        <p>  

<div class="form-group well">
<b>Patients Fullname:</b> <span class='p_fullname'></span><br>
<b>Patients Email:</b> <span class='p_email'></span><br>
<b>Appointment Title:</b> <span class='p_title'></span><br>
<b>Medical Details:</b> <span class='p_desc'></span><br>
<br>
</div>




<div class="form-group">
			<div id="loader_sum"></div>
                       </div>



<div class="form-group">
                        <div id="result_sum"  class='mydata_summary'></div>
                       </div>


</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Summary AI ends -->













<script>
$(document).ready(function(){
//$('.btn_sentiment').click(function(){
$(document).on( 'click', '.btn_sentiment', function(){ 


var id = $(this).data('id');
var email = $(this).data('email');
var fullname = $(this).data('fullname');
var title = $(this).data('title');
var desc = $(this).data('desc');
var msg_text = desc;

$('.p_id').html(id);
$('.p_email').html(email);
$('.p_fullname').html(fullname);
$('.p_title').html(title);
$('.p_desc').html(desc);



$('.p_id_value').val(id).value;
$('.p_email_value').val(email).value;
$('.p_fullname_value').val(fullname).value;
$('.p_title_value').val(title).value;
$('.p_desc_value').val(desc).value;



//start
	
if(desc==""){
alert('There is an Issue Selecting Patients Medical Data');
}


else{

$('#loader_sentiment').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif">&nbsp;Please Wait, Patients Medical Data Sentimental Analysis via  Expert.AI...</div>');
var datasend = {msg_text:msg_text};	
		$.ajax({
			
			type:'POST',
			url:'expert_ai_sentiments.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
                        $('#loader_sentiment').hide();
                        $('#result_sentiment').fadeIn('slow').html(msg);
//setTimeout(function(){ $('#result_sentiment').html(''); }, 9000);


			}
			
		});
		
		}

//end





});

});




// clear Modal div content on modal closef closed
$(document).ready(function(){


$("#myModal_sentiment").on("hidden.bs.modal", function(){
    //$(".modal-body").html("");
 $('.mydata_sentiment').empty(); 
});

});



// clear Modal div content on modal closef closed
$(document).ready(function(){
$("#myModal_summary").on("hidden.bs.modal", function(){
    //$(".modal-body").html("");
 $('.mydata_summary').empty(); 
//$('#q').val(''); 
});

});



</script>



<!--Summary AI start -->

<div id="myModal_sentiment" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"   style='background: #008080;color:white;padding:10px;'>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Patients Medical Data Sentimental Analysis Via Expert.AI</h4>
      </div>

      <div class="modal-body">

        <p>  

<div class="form-group well">
<b>Patients Fullname:</b> <span class='p_fullname'></span><br>
<b>Patients Email:</b> <span class='p_email'></span><br>
<b>Appointment Title:</b> <span class='p_title'></span><br>
<b>Medical Details:</b> <span class='p_desc'></span><br>
<br>
</div>




<div class="form-group">
			<div id="loader_sentiment"></div>
                       </div>



<div class="form-group">
                        <div id="result_sentiment" class='mydata_sentiment'></div>
                       </div>


</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Summary AI ends -->













</div>

</body>
</html>
