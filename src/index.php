<?php
//Idea and proof of concept by Aero.

//IMPORTANT: LOGGER DOESN'T ACTUALLY LOG USERS HERE ON REPL
//AS REPL(s) IS/ARE VIRTUALIZED.
//Truly to use on your own environment :) 

//Set Max Script Life
ini_set('max_execution_time', 30);


//---------------CONFIG BELOW----------------
//Where to redirect stats after logging.
$redirect = "https://discord.gg";

//For real-world situations,
//place key file somewhere safe. #Defult: 123456
$adminKey = file_get_contents("key.dat");
//-------------------------------------------

//Basic Admin View: 
// https://example.com/this_file.php?admin_key=123456
//-------------------------------------------


// Get Info From Our Visitor
$my_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
$referer = $_SERVER['HTTP_REFERER'];
$method = $_SERVER['REQUEST_METHOD'];
if (!$referrer){$referer = "Direct";}
$UA = $_SERVER['HTTP_USER_AGENT'];
$timeNow_calendar = date('Y-m-d');
$timeNow_exp = date('H:m:s');
$timeNow = "Time: $timeNow_calendar $timeNow_exp";
$thisHit = "[Logger Hit -> $timeNow] - IP: $my_addr | Referrer: $referer | User-Agent: $UA | Request Method: $method\r\n";




//Check if viewing as admin.
if (isset($_GET['admin_key'])) {
  //as admin then check key // Admin Stat-Viewer
  if ($_GET['admin_key'] == $adminKey){
    //Append The successfull Admin Auth To Our Log File.
    $myfile = file_put_contents('logs.txt', "\r\n [ADMIN]".$thisHit , FILE_APPEND | LOCK_EX);
    //authed then get logs - // Admin Stat-Viewer
    $logs = nl2br(file_get_contents("logs.txt"));
    
    //print logs // Admin Stat-Viewer
    echo "Welcome To Your Logger :)<br><br>Basic Info:<br><br>
    --Hits with '[ADMIN]' indicate a successfull admin auth for that IP.<br>
    --Hits with '[ADMIN-FAILED]' indicate a FAILED attempt to view the logger stats for that IP.<br>---------------------------------------------------------------------------------------------------------------------------------------------------------<br><br>.".PHP_EOL.$logs;

  //auth failed
  }else{
    //Append The Failed Admin Auth To Our Log File.
    $myfile = file_put_contents('logs.txt', "\r\n [ADMIN-FAILED]".$thisHit , FILE_APPEND | LOCK_EX);
    die('Incorrect Admin Key!');
  }
//Viewing as stat - // A Stat-Viewer
} else {
  //Append The Usual Hit To Our Log File
  $myfile = file_put_contents('logs.txt', "\r\n".$thisHit , FILE_APPEND | LOCK_EX);
  //Gracefully Redirect Our Stats to $redirect
  header('Location: '.$redirect);
}

?>
