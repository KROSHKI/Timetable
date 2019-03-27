<?php 
	if (isset($_POST["Token"]) && isset($_POST["Id"])) {
		$token = $_POST["Token"];
		$id = $_POST["Id"];
		$conn = mysqli_connect("localhost","q922371f_dbase","supsup123123!","q922371f_dbase") or die("Error connecting");
		//$q = "INSERT INTO users (Token) VALUES ( '$token') "." ON DUPLICATE KEY UPDATE Token = '$token'";      
		$q = "UPDATE teachers SET token='$token' WHERE id=$id";
      	mysqli_query($conn,$q) or die(mysqli_error($conn));
      	mysqli_close($conn);
	}
 ?>