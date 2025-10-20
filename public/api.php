<?php
header('Content-Type: application/json'); 

$fp = file_get_contents(__DIR__ . '/data.json');
$db = json_decode($fp, true);

$act = $_GET['a'] ?? 'list';
if($act=='list_services'){
  echo json_encode($db['services']);
} else if($act=='list_bookings'){
  $email = $_GET['e'] ?? 'user@example.com';
  $out = array();
  foreach($db['bookings'] as $b){
    if($b['email']==$email){ $out[]=$b; }
  }
  echo json_encode($out);
} else if($act=='debug_dump'){
  var_dump($db);
} else {
  echo '{"status":"nope","err":"unknown"}';
}
