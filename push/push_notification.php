<?php

define("API_ACCESS_KEY", "AAAACjzjhVw:APA91bHVrW1J9Nwcy2L0zLBzw_IXkoHE89rIi3mAqvaGFMcEJbuE-oQzTo-QPoYxr0qPS_f2S8Jm-n_aJaPujPSHtNwfibUeAlVav-Ez735A-MKjFt-LMtH28gbPhiXmYgSaBPJ6gwRl");

function send_notification($tokens = array()) {
	
	$registrationIds = $tokens;
	#prep the bundle
    $notification = array(
		"body" 	=> "Описание того, что обновилось",
		"title"	=> "Обновление расписания!",
		"sound" => "default",
		"priority" => "high"
    );
    $android = array("priority" => "high");
    $headers = array("Urgency" => "high");
    $web_push = array($headers);
	$fields = array(
		"registration_ids"	=> $registrationIds,
		"notification"	=> $notification,
		"android" => $android,
		"webpush" => $web_push
	);
	
	$headers = array(
		"Authorization: key=" . API_ACCESS_KEY,
		"Content-Type: application/json"
	);
	#Send Reponse To FireBase Server	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	echo $result;
	curl_close($ch);
}
	
	$connect = mysqli_connect("localhost","q922371f_dbase","supsup123123!","q922371f_dbase");
	//$query = "SELECT id FROM users ORDER BY id DESC LIMIT 1";//"SELECT MAX(ID) FROM users";
	//$max_id = mysqli_fetch_assoc(mysqli_query($connect, $query));
	//$max_id["id"] - максимальный ID

	$sql = "SELECT token FROM teachers";
	$result = mysqli_query($connect, $sql);
	$tokens = array();

	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			if($row["token"] != "") $tokens[] = $row["token"];
		}
	}
	mysqli_close();

	if(isset($_GET['push'])) send_notification($tokens);


?>