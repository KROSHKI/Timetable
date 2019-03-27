<?
header('Content-Type: text/html; charset=utf-8');
/*Подключаемся к базе данных*/
$servername = "localhost";
$username = "g982257e_tt";
$password = "Pablo228";
$db_name="g982257e_tt";
require_once('dbconnect.php');
/*Запрашиваем все данные из таблицы teachers*/
$sql = "SELECT * FROM teachers";
$result = $conn->query($sql);
$arr_teachers = array();
if ($result->num_rows > 0) {
 	// output data of each row
 	while($row[] = $result->fetch_assoc()) {
 		$tem = $row;
 		$arr_teachers = $tem;
 	}
} else {
	echo "0 results";
}
$temp = [ 'teachers'=> $arr_teachers ];//массив с расписанием
echo json_encode($temp, JSON_UNESCAPED_UNICODE);
$conn->close();

?>