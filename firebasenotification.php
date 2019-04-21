<?php
function send_notification ($tokens, $message)
{
$url = 'https://fcm.googleapis.com/fcm/send';
$payload = array(
    'registration_ids' => $tokens,
	'data' => $message,
	'notification' => $message //Remove this 'notification'	line if not working. Keep only 'data'
);

$headers = array();
$headers[] = 'Content-type: application/json';
$headers[] = 'Authorization: key=AIzaSyxxxxxxxxxxxxxxxxxxxxxxxxxxxtng'; //Your firebase server key

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$result = curl_exec($ch);
if ($result === FALSE) {
    die('Curl failed: '.curl_error($ch));
}
curl_close($ch);
return $result;
}

//Connect to database to fetch users registrationToken
$conn = mysqli_connect("localhost","my_user","my_password","my_db");
$sql = "SELECT fcmtoken FROM users"; //fcmtoken is database column holding users registrationToken
$result = mysqli_query($conn,$sql);
$tokens = array();

if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)) {
	$tokens[] = $row["fcmtoken"];
	}
}
mysqli_close($conn);
//Connection to database closed. End fetching users registrationToken

//Message to send
$title = "Title of notification";
$msgbody = "Body of notification";
$msg = array
          (
		'body' 	=> $msgbody,
		'title'	=> $title,
             	'icon'	=> 'myicon',/*Default Icon*/
              'sound' => 'mySound'/*Default sound*/
 );
//End message to send

$message_status = send_notification($tokens, $msg);
echo $message_status; 
?>
