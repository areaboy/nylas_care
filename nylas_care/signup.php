<?php


ini_set('max_execution_time', 300); 
// temporarly extend time limit
set_time_limit(300);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

//error_reporting(0);
$email = strip_tags($_POST['email']);
$password = strip_tags($_POST['password']);
$fullname = strip_tags($_POST['fullname']);
$statusx = strip_tags($_POST['status']);
$timer = time();

if($email ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Email to Check Cannot be Empty</div><br>";
exit();
}


$em= filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$em){
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>Email Address is Invalid</div>";
exit();
}


include('settings.php');
include('data6rst.php');

//create table users(id int primary key auto_increment, password varchar(200),fullname varchar(200),email varchar(200),status varchar(200),timing varchar(200));

// check if user has verified his email
$result_verified = $db->prepare('select * from users where email=:email');
$result_verified->execute(array(':email' =>$email));
$rowv = $result_verified->fetch();
$count = $result_verified->rowCount();


if($count == 1){
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>Email Address Already Exist</div>";
exit();
}





$statement = $db->prepare('INSERT INTO users
(password,fullname,email,status,timing)
 
                          values
(:password,:fullname,:email,:status,:timing)');

$statement->execute(array( 

':password' => $password,
':fullname' => $fullname,
':email' => $email,		
':status' =>$statusx,
':timing' =>$timer

));


if($statement){
echo "<div id='' style='background:green;color:white;padding:10px;border:none;'>User Created Successfully</div>";


echo "<script>alert('User Created Successfully');</script><br><br>";


}








}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}


?>