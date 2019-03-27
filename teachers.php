<?

header("Content-Type: text/html; charset=utf-8");
require_once 'phpQuery.php';
require_once "Classes/PHPExcel.php";
require_once "Classes/PHPExcel/IOFactory.php";



/*
   Парсинг ссылок и загрузка Excel файлов на FTP сервер
*/
function Parser(){

 	$url = 'http://students.perm.hse.ru/timetable/';
	$file = file_get_contents($url);// получили HTML версию сайта по указанному URL

  	$doc = phpQuery::newDocument($file);// создаём документ для работы с библиотекой для парсинга

  	$pl = $doc->find('.first_child.text a[class="link fileRef"]');// указываем из какого места брать ссылки

  	//обработка полученного HTML кода 
  	$result = explode("<a class=\"link fileRef\" href=\"", $pl);
  	$awd = implode('', $result);

  	$noun = count($result);

  	for($i = 0; $i < $noun; $i++) $result[$i] = urldecode($result[$i]);// превращение ссылок из ASCII кода
  
  	for($i = 0; $i < $noun; $i++){
  		$result[$i] = strstr($result[$i], '.xls', true);
  	}
  	for($i = 0; $i < $noun; $i++){
  		if($result[$i] == "") unset($result[$i]);
  	}
  	// работа с бакалавриатом
  	for($i = 0; $i < count($result); $i++){
    	if(strpos($result[$i], "Магистратура") === false || strpos($result[$i], "неделя") === false){
    		$result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";// если в строке есть слово "Магистратура"
    	}else{
    		$result[$i] = "http:".trim($result[$i]).".xls";
    	}
    	// if($i!=(count($result)-1) && $i!=(count($result)-2)) $result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";
    	// else $result[$i] = "http:".trim($result[$i]).".xls";
  	}

  	//убираем пустые элементы в массиве
  	if(in_array("http://students.perm.hse.ru.xls", $result)){
  		unset($result[array_search("http://students.perm.hse.ru.xls", $result)]);
  	}
  	$result = array_values($result);
  	//записыли ссылки в файл на сервере
  	file_put_contents('result.txt', implode("\r\n", $result));
  	for($i = 0; $i < count($result); $i++){
   		UploadTo($result[$i],end(explode("/",$result[$i])));
  	}
}

/*
   Обновление ссылок и файлов
*/
function Update(){
	if(file_exists("files/")){
		foreach(glob('files/*') as $file) unlink($file);
    	unlink("result.txt");
  	}
   	Parser();
}


/*
   Проверка "Обновлять / Не обновлять" через GET запрос
*/
if(isset($_GET['update']) or isset($_POST['update'])) Update();

/* Массивы под существующие на сайте недели
   Формат: [0] => "03.12.2018", [1] => "04.12.2018", [2] => "05.12.2018", [3] => "06.12.2018", [4] => "07.12.2018"  */
$week_one = [];
$week_two = [];
$week_three = [];
$week_four = [];
$week_five = [];
$week_six = [];

/* Место под названия недель
   Формат: "1 неделя", "2 неделя", "3 неделя", "4 неделя", "5 неделя" */
$week_names = array();

/* Очистили магистров и пересдачи */
$files_to = scanDirs("files", 1);
for($i = 0; $i < count($files_to);$i++){
	if(!preg_match("/неделя/",$files_to[$i])) unset($files_to[$i]);//оставляем только бакалавриат
}

$files_to = array_reverse(array_values($files_to));//развернули и переиндексовали

/* Загрузка Excel файлов на FTP сервер
   Параметры:
  	 — путь
  	 — название файла
*/
function UploadTo($filen, $file_new){
    $data = implode("", file($filen));
    $fp = fopen("files/".$file_new, "w");
    fputs($fp, $data);
    fclose($fp);
}

for($i = 0; $i < count($files_to);$i++){

 	$objPHPExcel = PHPExcel_IOFactory::load("files/".$files_to[$i]);//обратились к файлу
  	$objPHPExcel->setActiveSheetIndex(0);//поставили фокус на 1 лист
  	$aSheet = $objPHPExcel->getActiveSheet();//перешли на него

  	//проверяем каждый файл, записывая данные про дни недели
  	if($i == 0){//1 файл (первая в списке неделя)
    	for($j = 4; $j < 46; $j++){
    		//добавление в массив недель дней в формате "06.12.2018"
     		array_push($week_one, trim(substr(activeMergedCells(exNumToStr(0,$j), $aSheet),-10)));
    	}
    	$week_names[0] = trim(substr($files_to[0],0,15));//записали название недели в формате "1 неделя"
  	}else if($i == 1){//2 файл
    	for($j = 4; $j < 46; $j++){
    		array_push($week_two, trim(substr(activeMergedCells(exNumToStr(0,$j), $aSheet),-10)));
    	}
    	$week_names[1] = trim(substr($files_to[1],0,15));
  	}else if($i == 2){//3 файл
    	for($j = 4; $j < 46; $j++){
      		array_push($week_three, trim(substr(activeMergedCells(exNumToStr(0,$j), $aSheet),-10)));
    	}
    	$week_names[2] = trim(substr($files_to[2],0,15));
  	}else if($i == 3){//4 файл
    	for($j = 4; $j < 46; $j++){
      		array_push($week_four, trim(substr(activeMergedCells(exNumToStr(0,$j), $aSheet),-10)));
    	}
    	$week_names[3] = trim(substr($files_to[3],0,15));
  	}else if($i == 4){//5 файл
    	for($j = 4; $j < 46; $j++){
      		array_push($week_five, trim(substr(activeMergedCells(exNumToStr(0,$j), $aSheet),-10)));
    	}
    	$week_names[4] = trim(substr($files_to[4],0,15));
  	}else if($i == 5){//6 файл
    	for($j = 4; $j < 46; $j++){
      		array_push($week_six, trim(substr(activeMergedCells(exNumToStr(0,$j), $aSheet),-10)));
    	}
    	$week_names[5] = trim(substr($files_to[5],0,15));
  }
}

/* Делаем значения уникальными */
if(count($week_one)){
  	$week_one = array_values(array_unique($week_one));
  	unset($week_one[array_search(end($week_one), $week_one)]);
}
if(count($week_two)){
  	$week_two = array_values(array_unique($week_two));
  	unset($week_two[array_search(end($week_two), $week_two)]);
}
if(count($week_three)){
  	$week_three = array_values(array_unique($week_three));
  	unset($week_three[array_search(end($week_three), $week_three)]);
}
if(count($week_four)){
  	$week_four = array_values(array_unique($week_four));
  	unset($week_four[array_search(end($week_four), $week_four)]);
}
if(count($week_five)){
  	$week_five = array_values(array_unique($week_five));
  	unset($week_five[array_search(end($week_five), $week_five)]);
}
if(count($week_six)){
  	$week_six = array_values(array_unique($week_six));
  	unset($week_six[array_search(end($week_six), $week_six)]);
}

/* Удаление из массива информации не про даты
   Параметры:
	 — массив дат
   Возвращает обработанный массив (массив с информацией только про даты)
*/
function DelNotDate($arr){
	$pre = array_values($arr);//записали значения(количества пар)
  	$new = array_keys($arr);//массив из ключей исходного массива(даты)
  	$j = 0;
  	for($i = 0; $i < count($new); $i++){
    	$temp = explode(".",$new[$i]);
    	if(strlen($temp[0]) == 2) $j++;
  	}
  	$var = array();
  	for($i = 0; $i < $j; $i++){
    	array_push($var, $pre[$i]);
  	}
  	return $var;
}

/* Определение нужного для проверки количества строк на конкретном листе
   Параметры:
	 — день
	 — массивы с датами на недели
	 — названия недель
   Возвращает массив с количеством строк в листах выбранной недели по заданному дню
*/   
function GetCountAllPairs($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){
	$temp = array();
  	/* Выбрали неделю */
  	$objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names));

  	$count_list = $objPHPExcel->getSheetCount();//количество листов

  	$week = array();
  	for($i = 0; $i < $count_list; $i++){
    	$objPHPExcel->setActiveSheetIndex($i);//выбрали нужный лист
    	$aSheet = $objPHPExcel->getActiveSheet();//перешли на него
    	for($j = 4; $j < 46; $j++){
			//добавили в массив недель дни в формате "06.12.2018"
			array_push($week, trim(substr(activeMergedCells(exNumToStr(0, $j), $aSheet), -10)));
    	}
    	$values = array_count_values($week);//записали значения
    	$values = DelNotDate($values);//удалили всё, что не связаано с датами
    	array_push($temp,array_sum($t));//залил в массив
    	$week = array();//очистили массив
  	}
  return $temp;
}

/* Удаление лишнего из недельных дат
   Параметры:
	 — массив дат
   Возвращает обработанный массив
*/   
function DelGab($arr){
	$week_new = array();
  	for($i = 0; $i < count($arr);$i++){
    	$temp = explode(".",$arr[$i]);
    	if(strlen($temp[0]) == 2) array_push($week_new, $arr[$i]);
  	}
  	return $week_new;
}

/* Удаление всего лишнего из недельных массивов */
$week_one = DelGab($week_one);
$week_two = DelGab($week_two);
$week_three = DelGab($week_three);
$week_four = DelGab($week_four);
$week_five = DelGab($week_five);
$week_six = DelGab($week_six);

/* Определение начального индекса на каждом листе
   Параметры:
  — день
  — массивы с датами на недели
  — названия недель
   Возвращает массив из начальных индексов
*/    
function GetStartIndex($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){
  	$temp = array();
  	$objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names));
  	$count_list = $objPHPExcel->getSheetCount();//количество листов
  	for($i = 0; $i < $count_list; $i++){
    	$sum = 3;//начальный индекс, т.к. нужно выбрать
    	$week = array();//временный массив для количества пар
    	$objPHPExcel->setActiveSheetIndex($i);//выбрали нужный лист
    	$aSheet = $objPHPExcel->getActiveSheet();//перешли на него
    	/* Записали неделю на каонкретном листе */
    	for($j = 4; $j < 46; $j++){
    		//добавили в массив недель дни в формате "06.12.2018"
      		array_push($week, trim(substr(activeMergedCells(exNumToStr(0,$j), $aSheet),-10)));
    	}
    	/* Выделили сколько пар на этой неделе в каждом листе */
    	$count_pair = array_count_values($week);// массив пар в дни на конкретном листе
    	$count_pair = DelNotDate($count_pair);// удалили всё, что не связаано с датами
    	$day_id = 0;// индекс искомого дня
    	//ищём в какой неделе находится запрашиваемый день и записываем идекс искомого дня
    	if(in_array($dayR, $week_one)) $day_id = array_search($dayR, $week_one);
    	else if(in_array($dayR, $week_two)) $day_id = array_search($dayR, $week_two);
    	else if(in_array($dayR, $week_three)) $day_id = array_search($dayR, $week_three);
    	else if(in_array($dayR, $week_four)) $day_id = array_search($dayR, $week_four);
    	else if(in_array($dayR, $week_five)) $day_id = array_search($dayR, $week_five);
    	else if(in_array($dayR, $week_six)) $day_id = array_search($dayR, $week_six);

    	for($j = 0; $j < $day_id; $j++) $sum += $count_pair[$j];//получаем искомое количество
    	array_push($temp, $sum + 1);
  	}
  	return $temp;
}

/* Получение количества пар в конкретный день в каждом листе
   Параметры:
  	 — день
  	 — массивы с датами на недели
  	 — названия недель
   Возвращает массив с количеством пар в конкретный день 
*/   
function GetCountPairs($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){
  	$temp = array();
  	$objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names));//обратились к файлу
  	$count_list = $objPHPExcel->getSheetCount();//количество листов
  	for($i = 0; $i < $count_list; $i++){
    	$res = 0;
    	$week = array();
    	$objPHPExcel->setActiveSheetIndex($i);//выбрали нужный лист
    	$aSheet = $objPHPExcel->getActiveSheet();//перешли на него
    	/* Записали неделю на каонкретном листе */
    	for($j = 4; $j < 46; $j++){
    		//добавили в массив недель дни в формате "06.12.2018"
    	  	array_push($week, trim(substr(activeMergedCells(exNumToStr(0, $j), $aSheet), -10)));
    	}
    	/* Выделили сколько пар на этой неделе в каждом листе */
    	$value = array_count_values($week);// массив пар в дни на конкретном листе
    	$value = DelNotDate($value);//удалили всё, что не связаано с датами
    	$day_id = 0;// индекс искомого дня
    	if(in_array($dayR, $week_one)) $day_id = array_search($dayR, $week_one);
    	else if(in_array($dayR, $week_two)) $day_id = array_search($dayR, $week_two);
    	else if(in_array($dayR, $week_three)) $day_id = array_search($dayR, $week_three);
    	else if(in_array($dayR, $week_four)) $day_id = array_search($dayR, $week_four);
    	else if(in_array($dayR, $week_five)) $day_id = array_search($dayR, $week_five);
    	else if(in_array($dayR, $week_six)) $day_id = array_search($dayR, $week_six);
    	$res = $value[$day_id];
    	array_push($temp,$res);
  	}
  	return $temp;
}

/* Получение недели строкой по запрашиваемому дню
   Параметры:
  — день
  — массивы с датами на недели
  — названия недель
   Возвращает название недели по запрашиваему дню
*/
function GetNowWeek($date_now, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){
	//проверяем в какой неделе запрошеный день
  	if(in_array($date_now, $week_one)) $week_res = $week_names[0];
  	else if(in_array($date_now, $week_two)) $week_res = $week_names[1];
  	else if(in_array($date_now, $week_three)) $week_res = $week_names[2];
  	else if(in_array($date_now, $week_four)) $week_res = $week_names[3];
  	else if(in_array($date_now, $week_five)) $week_res = $week_names[4];
  	else if(in_array($date_now, $week_six)) $week_res = $week_names[5];

  	return $week_res;
}

/* Получение названий excel файлов с расписанием
   Параметры:
  	 — директория
  	 — тип сортировки
   Возвращает название недели по запрашиваему дню
*/
function scanDirs($dir, $sort){
	$list = scandir($dir, $sort);
  	unset($list[count($list)-1], $list[count($list)-1]);
	return $list;
}

/* Поиск недели по дню
   Параметры:
  	 — день
  	 — массивы с датами на недели
  	 — названия недель
   Возвращает название файла с недели, в которой находится запрашиваемый день
*/
function searchWeek($day, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){
  	$week = trim(GetNowWeek($day, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names));//определили неделю
  	$files_to = scanDirs("files", 1);//массив из названий Excel файлов
  	for($i = 0; $i < count($files_to);$i++){
    	if(!preg_match("/неделя/", $files_to[$i])) unset($files_to[$i]);//оставляем только бакалавриат
  	}
  	$files_to = array_reverse(array_values($files_to));//развернули и переиндексовали

  	for($i = 0; $i < count($files_to); $i++){
    	if(substr($files_to[$i], 0, strlen($week)) == $week) $res = $files_to[$i];//проверили и сохранили нужный
    	else continue;
  	}
  	return $res;//вывели нужный
}

/* Перебор(проверка) объединённых ячеек — activeMergedCells('H5',$aSheet)
   Параметры:
     — адрес ячейки
     — ссылка на используемый лист в excel
   Возвращает ту же ячейку, только проверенную на соединение, в которую вставили значение
 */
function activeMergedCells($cellC, $aSheet){
  	$cell = $aSheet->getCell($cellC);
  	$mergedCellsRange = $aSheet->getMergeCells();
  	foreach($mergedCellsRange as $currMergedRange) {
    	if($cell->isInRange($currMergedRange)) {
    		$currMergedCellsArray = PHPExcel_Cell::splitRange($currMergedRange);
  			$cell = $aSheet->getCell($currMergedCellsArray[0][0]);
  			break;
  		}
  	}
  	return $cell;
}

/* Преобразование нумерации в excel формат: exNumToStr(0, 5) => A5
   Параметры:
     — буква столбеца
     — номер строки
   Возвращает строку в формате "A5"
 */
function exNumToStr($col, $row){ return(PHPExcel_Cell::stringFromColumnIndex($col).$row); }

/* Проверка и сборка файлов на выходе в зависимости от количества пар на неделе
   Параметры:
  	 — значение ячейки
  	 — ссылка на рабочий лист
  	 — номер столбца
  	 — номер строки
   Возвращает обработанную ячейку строкой
*/
function cellCheck($poi, $aSheet, $group_id, $p){
  	/*Пример использования:
		cellCheck(activeMergedCells(exNumToStr($group_id,$i),$aSheet),$aSheet,$group_id, $i)
  	*/

  	$results = array();

  	$vowels = array(" (лек.)", " (лекция)", " (семинар)", " (сем.)");
  	$role = str_replace($vowels, "", $poi);

  	// Обработка ячейки
  	$rest = explode(")",$role);

  	// Если в ячейке 1 предмет :
  	$group = ""; // Подгруппа
  	$couple_name = ""; // Название пары
  	$lector_room = ""; // Аудитория
  	$corps_num = ""; // Корпус

  	$type = ""; // Вид пары

  	// Если в ячейке 2 предмета :
  	$group_one = ""; // 1-ая подгруппа
  	$group_two = ""; // 2-ая подгруппа
  	$lector_room_one = ""; // 1-ая аудитория
  	$lector_room_two = ""; // 2-ая аудитория
  	$corps_num_one = ""; // 1-ый корпус
  	$corps_num_two = ""; // 2-ый корпус
  
  	$cell = $aSheet->getCell(exNumToStr($group_id, 3)); // Параметр "У кого"
  	$time = substr(trim($aSheet->getCell(exNumToStr(1, $p))), 2); // Время

  	if(count($rest)>2){ // Если в ячейке 2 предмета
      	$group_one = substr($rest[0],-1);
      	$lector_room_one = substr(substr($rest[0], -9), 0, 3);
      	$corps_num_one = substr(substr($rest[0], -9), 4, -4);

      	$group_two = substr($rest[1],-1);
      	$lector_room_two = substr(substr($rest[1], -9), 0, 3);
      	$corps_num_two = substr(substr($rest[1], -9), 4, -4);

      	// Первый предмет
      	$rol1 = substr($rest[0], 0, -11);
      	$r1 = preg_split("/[\s,]+/", $rol1);
      	if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
      	$first1 = array_pop($r1); //Имя + Отчество
      	$second2 = array_pop($r1); // Фамилия
      	$teacher1 = $second2.' '.$first1; // Преподаватель
      	$name_pair1 = ""; // Название пары
      	for($i = 0; $i < count($r1); $i++) $name_pair1 = $name_pair1.$r1[$i].' ';

      	// Второй предмет
      	$rol2 = substr($rest[1], 0, -11);
      	$r2 = preg_split("/[\s,]+/", $rol2);
      	if(strlen($r2[count($r2)-1]) == 2) unset($r2[array_search(end($r2), $r2)]);
      	$first2 = array_pop($r2); // Имя + Отчество
      	$second2 = array_pop($r2); // Фамилия
      	$teacher2 = $second2.' '.$first2;// Преподаватель
      	if(substr_count(substr($role,-55), ')') > 1){
        	$name_pair2 = $name_pair1;
      	}else{
        	$name_pair2 = "";// Название пары
        	for($i = 0; $i < count($r2); $i++) $name_pair2 = $name_pair2.$r2[$i].' ';
      	}
  	  	$type = "Семинар";
      	// $vol1 - название пары
      	// $qw1 - имя преподаватель
      	// $lector_room_one - номер аудитории
      	// $group_one - номер подгруппы
      	// $corps_num_one - номер корпуса
  		return $teacher1.'•'.$cell.'('.$group_one.')•'.$name_pair1.'•'.$corps_num_one.'•'.$lector_room_one.'•'.$time.'•'.$type.'|'.$teacher2.'•'.$cell.'('.$group_two.')•'.$name_pair2.'•'.$corps_num_two.'•'.$lector_room_two.'•'.$time.'•'.$type;
	}else{ // Если в ячейке 1 предмет
    	$r1 = preg_split("/[\s,]+/", $role);
    	if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
    	$b1 = array_pop($r1);
    	$first = array_pop($r1);// Имя + Отчество
    	$second = array_pop($r1);// Фамилия
    	$teacher = $second.' '.$first;// Преподаватель (ФИО)
    	$name_pair = ""; // Название пары
    	for($i = 0; $i < count($r1); $i++) $name_pair = $name_pair.$r1[$i].' ';

    	$rest_new = substr(substr($poi, -2),-2,-1);
    	if(is_numeric($rest_new)){ // Если есть подгруппа
     		$group = $rest_new; // Номер группы
      		$lector_room = substr(substr($rest[0], -9), 0, 3); // Аудитория
      		$corps_num = substr(substr($rest[0], -9), 4, -4); // Корпус
      		if(substr($cell,-1) == '1'){
          		$g1 = activeMergedCells(exNumToStr($group_id, $p), $aSheet); // Середина
          		$g2 = activeMergedCells(exNumToStr($group_id+1, $p), $aSheet); // Справа
          		if($g1 == $g2) $type = 'Лекция'; // Если объединена
          		else $type = 'Семинар'; // Если НЕ объединена
        	}else if(substr($cell,-1) == '2'){
          		$g1 = activeMergedCells(exNumToStr($group_id, $p), $aSheet); // Середина
          		$g2 = activeMergedCells(exNumToStr($group_id-1, $p), $aSheet); // Справа
          		if($g1 == $g2) $type = 'Лекция'; // Если объединена
          		else $type = 'Семинар'; // Если НЕ объединена
        	}else if(substr($cell,-1) == '3'){
          		$g1 = activeMergedCells(exNumToStr($group_id, $p), $aSheet); // Середина
          		$g2 = activeMergedCells(exNumToStr($group_id-2, $p), $aSheet); // Справа
          		if($g1 == $g2) $type = 'Лекция'; // Если объединена
          		else $type = 'Семинар'; // Если НЕ объединена
        	}
      		return $teacher.'•'.$cell.'('.$group.')•'.$name_pair.'•'.$corps_num.'•'.$lector_room.'•'.$time.'•'.$type;
    	}else{ // Если нет подгруппы
      		$r1 = preg_split("/[\s,]+/", $role);
      		if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
      		$b1 = array_pop($r1);
      		$first = array_pop($r1);
      		$second = array_pop($r1);
      		$teacher = $second.' '.$first; // Преподаватель
      		$name_pair = ""; // Название пары
      		for($i = 0; $i < count($r1); $i++) $name_pair = $name_pair.$r1[$i].' ';

      		if($poi != ''){
        		if(substr($cell,-1) == '1'){
          			$g1 = activeMergedCells(exNumToStr($group_id, $p), $aSheet); // Середина
          			$g2 = activeMergedCells(exNumToStr($group_id+1, $p), $aSheet); // Справа
          			if($g1 == $g2) $type = 'Лекция'; // Если объединена
          			else $type = 'Семинар'; // Если НЕ объединена
        		}else if(substr($cell,-1) == '2'){
          			$g1 = activeMergedCells(exNumToStr($group_id, $p), $aSheet); // Середина
          			$g2 = activeMergedCells(exNumToStr($group_id-1, $p), $aSheet); // Справа
          			if($g1 == $g2) $type = 'Лекция'; // Если объединена
          			else $type = 'Семинар'; // Если НЕ объединена
        		}else if(substr($cell,-1) == '3'){
          			$g1 = activeMergedCells(exNumToStr($group_id, $p), $aSheet); // Середина
          			$g2 = activeMergedCells(exNumToStr($group_id-2, $p), $aSheet); // Справа
          			if($g1 == $g2) $type = 'Лекция'; // Если объединена
          			else $type = 'Семинар'; // Если НЕ объединена
        		}

        		$lector_room = substr($rest[0], -6, -3);// Номер аудитории
        		$corps_num = substr($rest[0], -2, -1);// Номер корпуса

        		return $teacher.'•'.$cell.'•'.$name_pair.'•'.$corps_num.'•'.$lector_room.'•'.$time.'•'.$type;//substr($cell, 0, -2)
      		}else{
      			return "";
      		}
    	}
  	}
}

/* Формирование массива из индексов столбцов с названиями направлений
   Параметры:
   	 — номер листа в Excel файле
  	 — день
  	 — массивы с датами на недели
  	 — названия недель
   Возвращает массив с индексами (номерами) столбцов с названиеми направлений
*/
function getIdWayLearn($indexSheet = 0, $dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){

  	$objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names));//выбираем нужный excel файл и дату(на завтра) date("d.m.Y", $date_tom))
  	$objPHPExcel->setActiveSheetIndex($indexSheet);//выбрали первый лист
  	$aSheet = $objPHPExcel->getActiveSheet();//перешли на него

  	$group_id = 0;
  	$pre_arr_way = array();
  	$id_arr_Way = array();
  	for($i = 2; $i < 20; $i++){
    	$cell = $aSheet->getCell(exNumToStr($i,3));
    	array_push($arr_way, $cell);
    	array_push($id_arr_Way, $i);
    	array_push($pre_arr_way, $cell);
  	}
  	$arr_way = array_combine(array_values($id_arr_Way), array_values($pre_arr_way));

  	return $arr_way;
}

/* Создание массива со значениями строки направлений
   Параметры:
   	 — номер листа в Excel файле
  	 — день
  	 — массивы с датами на недели
  	 — названия недель
   Возвращает массив из названий направлений
*/
function revArr($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){
  	$zx = [];
  	$awd2 = getIdWayLearn($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
  	for($i = 0; $i < count($awd2);$i++){
    	if($awd2[$i] != '') array_push($zx,trim($awd2[$i]));
    	else unset($awd2[$i]);
  	}
  	return $zx;
}

/*Создание массива из дат на 14 дней вперёд после текущего дня*/
$new_days = array();
for($i = 0; $i < 16; $i++){
  if(date('d.m.Y', strtotime("+$i day")) != date('d.m.Y', strtotime('Sunday this week')) &&
     date('d.m.Y', strtotime("+$i day")) != date('d.m.Y', strtotime('Sunday next week'))) array_push($new_days, date('d.m.Y', strtotime("+$i day")));
}


/* Сборка массива из предметов, которые ведёт конкретный преподаватель
   Параметры:
   	 — ФИО Преподавателя (для сравнения)
  	 — номер строки в Excel файле, с которой проверять преподавателя
  	 — номер строки в Excel файле, вплоть до которой нужно проверять преподавателя
  	 — массивы с датами на недели
  	 — названия недель
  	 — название дня, на который подготавливается расписание
   Возвращает массив расписания для его дальнейшего преобразования в JSON формат
*/
function getTeacher($teacher, $ot, $cp, $dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names, $num_day = ""){

  	$schedule = array(); // Массив с расписанием

  	$objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names)); // Выбираем нужный excel файл и дату(на завтра) date("d.m.Y", $date_tom))
  	$count_list = $objPHPExcel->getSheetCount();
  	$out = array(); // Номера пар какого-то преподавателя
  	$res = array(); // Все пары какого-то преподавателя
  	for($i = 0; $i < $count_list; $i++){
    	$objPHPExcel->setActiveSheetIndex($i); // Выбрали первый лист
    	$aSheet = $objPHPExcel->getActiveSheet(); // Перешли на него
    	//$row = GetCountAllPairs($dayR, $week_one,$week_two,$week_three, $week_four,$week_five,$week_six,$week_names)[$i];//сколько проверять строк
    	$col = count(revArr($i, $dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names));//сколько проверять столбцов

    	$time_ch = array();//для сортировки по времени
    	//проходимся по всем строкам
    	for($j = 2; $j < $col+2; $j++){	
      		for($k = $ot[$i]; $k < $ot[$i]+$cp[$i]; $k++){
      			$time = trim($aSheet->getCell(exNumToStr(1,$k)));
        		$role_new = activeMergedCells(exNumToStr($j,$k),$aSheet);//записали текущую ячейку

        		// Удаление ненужной информации для аналога функции Split
      			$vowels = array(" (лек.)", " (лекция)", " (семинар)", " (сем.)");
    			$role = str_replace($vowels, "", $role_new);

        		$rest = explode(")",$role);
        		if(count($rest)>2){ // Если в ячейке 2 предмета

          			// Первый предмет
          			$rol1 = substr($rest[0], 0, -11);
          			$r1 = preg_split("/[\s,]+/", $rol1);
          			if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
          			$first1 = array_pop($r1);
          			$second2 = array_pop($r1);
          			$qw1 = $second2.' '.$first1;// 1-ый преподаватель
          			if($qw1 == $teacher) {
          				if(!in_array($time, $time_ch)){
      						array_push($out,substr($time, 0, 1));
      						array_push($time_ch,$time);
      						$get_info = cellCheck(activeMergedCells(exNumToStr($j,$k),$aSheet),$aSheet,$j,$k);
      						$new_get = explode("•",explode("|",$get_info)[0]);
      						$temp = [
                				'day'=>$num_day,
                				'name_day'=>getNameDay($num_day),
      	 						'name_teachers'=>$new_get[0],
      	 						'name_users'=>$new_get[1],
      	 						'name_lesson'=>$new_get[2],
      	 						'lecture'=>$new_get[4],
      	 						'campus'=>$new_get[3],
      	 						'time'=>trim($new_get[5]),
      	 						'type'=>$new_get[6]	
      	 					];
      	 					array_push($schedule, $temp);
      					}
          			}
          			// Второй предмет
          			$rol2 = substr($rest[1], 0, -11);
          			$r2 = preg_split("/[\s,]+/", $rol2);
          			if(strlen($r2[count($r2)-1]) == 2) unset($r2[array_search(end($r2), $r2)]);
          			$first2 = array_pop($r2);
          			$second2 = array_pop($r2);
          			$qw2 = $second2.' '.$first2;// 2-ой преподаватель
          			if($qw2 == $teacher) {
          				if(!in_array($time, $time_ch)){
          					array_push($out,substr($time, 0, 1));
      						array_push($time_ch,$time);
      						$get_info = cellCheck(activeMergedCells(exNumToStr($j,$k),$aSheet),$aSheet,$j,$k);
      						$new_get = explode("•",explode("|",$get_info)[1]);
      						$temp = [
                				'day'=>$num_day,
                				'name_day'=>getNameDay($num_day),
      	 						'name_teachers'=>$new_get[0],
      	 						'name_users'=>$new_get[1],
      	 						'name_lesson'=>$new_get[2],
      	 						'lecture'=>$new_get[4],
      	 						'campus'=>$new_get[3],
      	 						'time'=>trim($new_get[5]),
      	 						'type'=>$new_get[6]	
      	 					];
      	 					array_push($schedule, $temp);
      					}
          			}

        		}else{ // Если в ячейке один предмет
          			$r1 = preg_split("/[\s,]+/", $role);
          			if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
          			$b1 = array_pop($r1);
          			$first = array_pop($r1);
          			$second = array_pop($r1);
          			$qw1 = $second.' '.$first; // Преподаватель
          			if($qw1 == $teacher) {
          				if(!in_array($time, $time_ch)){
          					array_push($out,substr($time, 0, 1));
      						array_push($time_ch, $time);

      						$get_info = cellCheck(activeMergedCells(exNumToStr($j,$k),$aSheet),$aSheet,$j,$k);
      						$new_get = explode("•",$get_info);
      						$temp = [
                				'day'=>$num_day,
                				'name_day'=>getNameDay($num_day),
      	 						'name_teachers'=>$new_get[0],
      	 						'name_users'=>$new_get[1],
      	 						'name_lesson'=>$new_get[2],
      	 						'lecture'=>$new_get[4],
      	 						'campus'=>$new_get[3],
      	 						'time'=>trim($new_get[5]),
      	 						'type'=>$new_get[6]
      	 					];
      	 					array_push($schedule, $temp);
      				  	}
          		    } 
        	    }
      	    }
        }
    }


    // Сортировка по времени (сначала самые ранние пары)
  	$new_json = array();
  	$j = min($out);
  	for($i = 0; $i < count($schedule); $i++){
    	$new_json[$i] =  $schedule[array_search($j,$out)];
    	$j++;
  	}

  	return $new_json;
}


/* Сборка ФИО преподавателей
   Параметры:
   	 — ФИО Преподавателя (для сравнения)
  	 — номер строки в Excel файле, с которой проверять преподавателя
  	 — номер строки в Excel файле, вплоть до которой нужно проверять преподавателя
  	 — массивы с датами на недели
  	 — названия недель
  	 — название дня, на который подготавливается расписание
   Возвращает массив расписания для его дальнейшего преобразования в JSON формат
*/
function getFIO($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names){
  	$objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names)); // Выбираем нужный excel файл и дату(на завтра) date("d.m.Y", $date_tom))
  	$count_list = $objPHPExcel->getSheetCount();
  	$res = array();
  	for($i = 0; $i < $count_list; $i++){
  		$objPHPExcel->setActiveSheetIndex($i); // Выбрали первый лист
    	$aSheet = $objPHPExcel->getActiveSheet(); // Перешли на него
    	//$row = GetCountAllPairs($dayR, $week_one,$week_two,$week_three, $week_four,$week_five,$week_six, $week_names)[$i]; // Сколько проверять строк
    	$col = count(revArr($i, $dayR, $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names)); // Сколько проверять столбцов
    	// Проходимся по всем строкам
    	for($j = 2; $j < $col+2; $j++){
      		for($k = 3; $k < $row+3; $k++){
        		$role_new = activeMergedCells(exNumToStr($j,$k),$aSheet);//записали текущую ячейку

        		$vowels = array(" (лек.)", " (лекция)", " (семинар)", " (сем.)");
				$role = str_replace($vowels, "", $role_new);

        		$rest = explode(")",$role);
        		if(count($rest)>2){ // если в ячейке 2 предмета
          			// Первый предмет
          			$rol1 = substr($rest[0], 0, -11);
          			$r1 = preg_split("/[\s,]+/", $rol1);
          			if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
          			$first1 = array_pop($r1);
          			$second2 = array_pop($r1);
          			$qw1 = $second2.' '.$first1;// 1 преподаватель
          			array_push($res,$qw1);
          			// Второй предмет
          			$rol2 = substr($rest[1], 0, -11);
          			$r2 = preg_split("/[\s,]+/", $rol2);
          			if(strlen($r2[count($r2)-1]) == 2) unset($r2[array_search(end($r2), $r2)]);
          			$first2 = array_pop($r2);
          			$second2 = array_pop($r2);
          			$qw2 = $second2.' '.$first2;// 2 преподаватель
          			array_push($res,$qw2);
        		}else{
          			$r1 = preg_split("/[\s,]+/", $role);
          			if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
          			$b1 = array_pop($r1);
          			$first = array_pop($r1);
          			$second = array_pop($r1);
          			$qw1 = $second.' '.$first;//преподаватель
          			array_push($res,$qw1);
        		}
      		}
    	}
  }
  return $res;
}

/*ПРОВЕРКА И ПОЛУЧЕНИЕ СПИСКА ПРЕПОДАВАТЕЛЕЙ*/

// $tf = getFIO($_GET['day'], $week_one,$week_two,$week_three,$week_four,$week_five,$week_six, $week_names);
// $fio = array();
// for($i = 0; $i < count($tf);$i++){
//   if($tf[$i] != "") array_push($fio,$tf[$i]);
// }
//удаление пустых элементов
// $fio = array();
// for($i = 0; $i < count($tf);$i++){
//   if(substr_count($tf[$i], ".") == 2 && strlen($tf[$i]) > 7) array_push($fio,$tf[$i]);
// }
//удаление повторяющихся
// $tmp = array();
// foreach ($fio as $row) if (!in_array($row,$tmp)) array_push($tmp,$row);

// $ot = GetStartIndex($_GET['day'], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
// $cp = GetCountPairs($_GET['day'], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
//$ot — с какой строки читать [массив]
//$cp — сколько строк читать [массив]

/* Обращение к базе данных MySQL*/
$servername = "localhost";
$username = "q922371f_dbase";
$password = "supsup123123!";
$db_name="q922371f_dbase";
$conn = mysqli_connect($servername, $username, $password, $db_name);
// Запрос выделить име конкреного преподавателя по GET парметру, в который вводится индекс из базы данных MySQL
$name_teach = mysqli_fetch_row(mysqli_query($conn, "SELECT name FROM teachers WHERE id = $_GET[teach]"));
mysqli_close($conn);

//$name_teach[0] — ФИО выбранного по ID преподавателя
//$tmp[$_GET['teach']]
// $output = getTeacher($name_teach[0], $ot, $cp, $_GET['day'], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
// for($i = 0; $i <count($output);$i++){
//   echo $output[$i].'<br>';
// }


//дни на текущую и следующую недели

// Запрос на один день
if($_GET['type'] == 1) {
  $ot = GetStartIndex($_GET['day'], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
  $cp = GetCountPairs($_GET['day'], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
  $output = getTeacher($name_teach[0], $ot, $cp, $_GET['day'], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names, $_GET['day']);
  echo "{\"days\":".json_encode($output, JSON_UNESCAPED_UNICODE)."}";
} else {// Запрос на 2 недели вперёд
  echo "{\"days\":[";
  for($i = 0; $i < 14; $i++){
    $ot = GetStartIndex($new_days[$i], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
    $cp = GetCountPairs($new_days[$i], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names);
    $output = getTeacher($name_teach[0], $ot, $cp, $new_days[$i], $week_one, $week_two, $week_three, $week_four, $week_five, $week_six, $week_names, $new_days[$i]);

    $timetable = array();
    if(count($output)) {
      array_push($timetable, $output);
      echo substr(substr(json_encode($output, JSON_UNESCAPED_UNICODE),1),0,-1);
    }else{
      echo '{"day":"empty","name_day":"empty","name_teachers":"empty","name_users":"empty","name_lesson":"empty","lecture":"empty","campus":"empty","time":"empty","type":"empty"}';
    }
    
    if($i != 13) echo ",";
  }
  echo "]}";
}


/* Определение дня недели
   Параметры:
   	 — дата в формате "дд.мм.гггг"
   Возвращает день недели
*/
function getNameDay($date){
  $num_day = date("w",strtotime($date));
  if($num_day == 1) return "Понедельник";
  else if($num_day == 2) return "Вторник";
  else if($num_day == 3) return "Среда";
  else if($num_day == 4) return "Четверг";
  else if($num_day == 5) return "Пятница";
  else if($num_day == 6) return "Суббота";
  else if($num_day == 0) return "Воскресенье";
}


/* АЛГОРИТМ ДОБАВЛЕНИЯ НОВЫХ ПРЕПОДАВАТЕЛЕЙ В БАЗУ ДАННЫХ */

// $servername = "localhost";
// $username = "q922371f_dbase";
// $password = "supsup123123!";
// $db_name="q922371f_dbase";
// $conn = mysqli_connect($servername, $username, $password, $db_name);
// $sql_temp = mysqli_query($conn, "SELECT name FROM teachers");
// $name_teach = array();
// while ($result = mysqli_fetch_array($sql_temp)) {
// 	array_push($name_teach, $result['name']);
// }

// for($i = 0; $i < count($tmp);$i++){
// 	if(in_array($tmp[$i], $name_teach)){
// 		continue;
// 	} else {
// 		$sql = "INSERT INTO teachers (name) VALUES ('$tmp[$i]')";
// 		if (mysqli_query($conn, $sql)) {
//     		echo "Успешно!<br>";
// 		} else {
//     		echo "Ошибка: ".$sql."<br>".mysqli_error($conn);
// 		}
// 	}
// }
// mysqli_close($conn);


//вывод массива с ФИО преподавателей
// for($i = 0; $i < count($tmp);$i++){
//   echo $i.'. '.$tmp[$i].'<br>';
// }
?>