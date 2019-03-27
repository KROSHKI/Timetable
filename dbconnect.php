<?

$servername = "localhost";
$username = "q922371f_dbase";
$password = "supsup123123!";
$db_name="q922371f_dbase";
try {
    $conn = new mysqli($servername,$username, $password,$db_name);    
    if($conn->connect_error){
    	echo 'Ошибка!';
    }  
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}

?>