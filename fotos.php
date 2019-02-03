<!-- <!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>HSE TIMETABLE</title>
  </head>
  <body>
<div class="parse"> -->
<?

// $screenWidth='<script type="text/javascript">document.write(screen.width);</script>';
// $screenHeight='<script type="text/javascript">document.write(screen.height);</script>';
// echo "Ширина: ".$screenWidth." px;<br>";
// echo "Высота: ".$screenHeight." px;<br>";

//header("Content-Type: text/html; charset=utf-8");
// require_once 'phpQuery.php';
// require_once "Classes/PHPExcel.php";
// require_once "Classes/PHPExcel/IOFactory.php";
//
//
//
// function parser(){
//   $url = 'http://students.perm.hse.ru/timetable/';
//   $file = file_get_contents($url);
//
//   $doc = phpQuery::newDocument($file);
//
//   $pl = $doc->find('.first_child.text a[class="link fileRef"]');
//   $result = explode("<a class=\"link fileRef\" href=\"", $pl);
//   $awd = implode('', $result);
//
//   $result = explode(".xls",$awd);
//
//
//   for($i = 1; $i < (count($result)+5); $i=$i+2) unset($result[$i]);
//   for($i = 1; $i < (count($result)+5); $i++) if(strlen($result[$i]) < 10) unset($result[$i]);
//   for($i = 0; $i < count($result); $i++) $result[$i] = urldecode($result[$i]);
//
//   $qwer = implode('', $result);
//   $result = explode("</a>", $qwer);
//   array_pop($result);
//
//   for($i = 0; $i < count($result); $i++){
//     if(strpos($result[$i], "Магистратура") === false){
//       $result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";//если в строке есть слово "Магистратура"
//     }else{
//       $result[$i] = "http:".trim($result[$i]).".xls";
//     }
//     // if($i!=(count($result)-1) && $i!=(count($result)-2)) $result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";
//     // else $result[$i] = "http:".trim($result[$i]).".xls";
//   }
//   file_put_contents('result.txt', implode("\r\n", $result));
//   for($i = 0; $i < count($result); $i++){
//     upload_to($result[$i],end(explode("/",$result[$i])));
//   }
//   //print_r($result);
// }
//
//
// //очистка старых excel файлов
// function update(){
//   if(file_exists("files/")){
//     foreach(glob('files/*') as $file) unlink($file);
//     unlink("result.txt");
//   }
//    parser();
// }
//
// //update();
//
// // $objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek("21.09.2018"));
// // $objPHPExcel->setActiveSheetIndex(0);//выбрали первый лист
// // $aSheet = $objPHPExcel->getActiveSheet();//перешли на него
//
// //очистили магистров и пересдачи
// // $files_to = scanDirs("files", 1);
// // for($i = 0; $i < count($files_to);$i++){
// //   if(!preg_match("/неделя/",$text)) unset($files_to[$i]);//оставляем только бакалавриат
// // }
// // $files_to = array_reverse(array_values($files_to));
// // $role = [];
// // for($i = 4; $i < 37; $i++){
// //   array_push($role, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
// // }
//
// //масссивы под существующие на сайте массивы
// $week_one = [];
// $week_two = [];
// $week_three = [];
// $week_four = [];
// $week_five = [];
// $week_six = [];
//
// //место под названия недель
// $wp1 = "";
// $wp2 = "";
// $wp3 = "";
// $wp4 = "";
// $wp5 = "";
// $wp6 = "";
//
// //количество пар в какой-либо день
// $we_pa_num1 = [];
// $we_pa_num2 = [];
// $we_pa_num3 = [];
// $we_pa_num4 = [];
// $we_pa_num5 = [];
// $we_pa_num6 = [];
//
// //очистили магистров и пересдачи
// $files_to = scanDirs("files", 1);
// for($i = 0; $i < count($files_to);$i++){
//   if(!preg_match("/неделя/",$files_to[$i])) unset($files_to[$i]);//оставляем только бакалавриат
// }
//
// $files_to = array_reverse(array_values($files_to));//развернули и переиндексовали
//
//
// //генерация массивов недель
// function GenAr(){}
//
// //загрузка на сервер excel файлов
// function upload_to($filen, $file_new){
//     $data = implode("", file($filen));
//     $fp = fopen("files/".$file_new, "w");
//     fputs($fp, $data);
//     fclose($fp);
// }
//
// $indexSheet = 0;
// //место под сеществующие недели(как минимум 6)
// for($j = 0; $j < count($files_to);$j++){
//   //array_push($roal, $files_to[$j]);
//   $objPHPExcel = PHPExcel_IOFactory::load("files/".$files_to[$j]);
//   if(substr($_GET['way'],-2) == '18') $indexSheet = 0;
//   else if(substr($_GET['way'],-2) == '17') $indexSheet = 1;
//   else if(substr($_GET['way'],-2) == '16') $indexSheet = 3;
//   else if(substr($_GET['way'],-2) == '15') $indexSheet = 5;
//   //echo "В файле: [".$files_to[$j]."] доступно ".$objPHPExcel->getSheetCount()." листов!<br>";
//   $objPHPExcel->setActiveSheetIndex($indexSheet);//выбрали первый лист
//   $aSheet = $objPHPExcel->getActiveSheet();//перешли на него
//
//   if($j == 0){
//     for($i = 4; $i < 46; $i++){
//       array_push($week_one, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
//     }
//     $wp1 = trim(substr($files_to[0],0,15));
//   }else if($j == 1){
//     for($i = 4; $i < 46; $i++){
//       array_push($week_two, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
//     }
//     $wp2 = trim(substr($files_to[1],0,15));
//   }else if($j == 2){
//     for($i = 4; $i < 46; $i++){
//       array_push($week_three, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
//     }
//     $wp3 = trim(substr($files_to[2],0,15));
//   }else if($j == 3){
//     for($i = 4; $i < 46; $i++){
//       array_push($week_four, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
//     }
//     $wp4 = trim(substr($files_to[3],0,15));
//   }else if($j == 4){
//     for($i = 4; $i < 46; $i++){
//       array_push($week_five, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
//     }
//     $wp5 = trim(substr($files_to[4],0,15));
//   }else if($j == 5){
//     for($i = 4; $i < 46; $i++){
//       array_push($week_six, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
//     }
//     $wp6 = trim(substr($files_to[5],0,15));
//   }
// }
// //делаем значения уникальными
// if(count($week_one)){
//   $we_pa_num1 = array_count_values($week_one);
//   $week_one = array_values(array_unique($week_one));
//   //if(!stripos(end($week_one), '0')) unset($week_one[array_search(end($week_one), $week_one)]);
//   unset($week_one[array_search(end($week_one), $week_one)]);
// }
// if(count($week_two)){
//   $we_pa_num2 = array_count_values($week_two);
//   $week_two = array_values(array_unique($week_two));
//   //if(!stripos(end($week_two), '0')) unset($week_two[array_search(end($week_two), $week_two)]);
//   unset($week_two[array_search(end($week_two), $week_two)]);
// }
// if(count($week_three)){
//   $we_pa_num3 = array_count_values($week_three);
//   $week_three = array_values(array_unique($week_three));
//   //if(!stripos(end($week_three), '0')) unset($week_three[array_search(end($week_three), $week_three)]);
//   unset($week_three[array_search(end($week_three), $week_three)]);
// }
// if(count($week_four)){
//   $we_pa_num4 = array_count_values($week_four);
//   $week_four = array_values(array_unique($week_four));
//   //if(!stripos(end($week_four), '0')) unset($week_four[array_search(end($week_four), $week_four)]);
//   unset($week_four[array_search(end($week_four), $week_four)]);
// }
// if(count($week_five)){
//   $we_pa_num5 = array_count_values($week_five);
//   $week_five = array_values(array_unique($week_five));
//   //if(!stripos(end($week_five), '0')) unset($week_five[array_search(end($week_five), $week_five)]);
//   unset($week_five[array_search(end($week_five), $week_five)]);
// }
// if(count($week_six)){
//   $we_pa_num6 = array_count_values($week_six);
//   $week_six = array_values(array_unique($week_six));
//   //if(!stripos(end($week_six), '0')) unset($week_six[array_search(end($week_six), $week_six)]);
//   unset($week_six[array_search(end($week_six), $week_six)]);
// }
//
//
// function get_count_pairs($day, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6){
//   if(array_key_exists($day, $we_pa_num1)) $count_pairs = $we_pa_num1[$day];
//   else if(array_key_exists($day, $we_pa_num2)) $count_pairs = $we_pa_num2[$day];
//   else if(array_key_exists($day, $we_pa_num3)) $count_pairs = $we_pa_num3[$day];
//   else if(array_key_exists($day, $we_pa_num4)) $count_pairs = $we_pa_num4[$day];
//   else if(array_key_exists($day, $we_pa_num5)) $count_pairs = $we_pa_num5[$day];
//   else if(array_key_exists($day, $we_pa_num6)) $count_pairs = $we_pa_num6[$day];
//   return $count_pairs;
// }
//
// // нужно посчитать существующую сумму дней на конкретной неделе ДО
// //Получить неделю. Получить день
//
// function get_prev_day($rty, $day, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6){
//
//   $sum = 3;
//   if($rty == 0){
//     $sum = 3;
//   }else if($rty == 1){
//     if(array_key_exists($day, $we_pa_num1)){
//       $n_we_pa_num1 = array_keys($we_pa_num1);
//       for($i = 0; $i < 1; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
//     }else if(array_key_exists($day, $we_pa_num2)){
//       $n_we_pa_num2 = array_keys($we_pa_num2);
//       for($i = 0; $i < 1; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
//     }else if(array_key_exists($day, $we_pa_num3)){
//       $n_we_pa_num3 = array_keys($we_pa_num3);
//       for($i = 0; $i < 1; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
//     }else if(array_key_exists($day, $we_pa_num4)){
//       $n_we_pa_num4 = array_keys($we_pa_num4);
//       for($i = 0; $i < 1; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
//     }else if(array_key_exists($day, $we_pa_num5)){
//       $n_we_pa_num5 = array_keys($we_pa_num5);
//       for($i = 0; $i < 1; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
//     }else if(array_key_exists($day, $we_pa_num6)){
//       $n_we_pa_num6 = array_keys($we_pa_num6);
//       for($i = 0; $i < 1; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
//     }
//   }else if($rty == 2){
//     if(array_key_exists($day, $we_pa_num1)){
//       $n_we_pa_num1 = array_keys($we_pa_num1);
//       for($i = 0; $i < 2; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
//     }else if(array_key_exists($day, $we_pa_num2)){
//       $n_we_pa_num2 = array_keys($we_pa_num2);
//       for($i = 0; $i < 2; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
//     }else if(array_key_exists($day, $we_pa_num3)){
//       $n_we_pa_num3 = array_keys($we_pa_num3);
//       for($i = 0; $i < 2; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
//     }else if(array_key_exists($day, $we_pa_num4)){
//       $n_we_pa_num4 = array_keys($we_pa_num4);
//       for($i = 0; $i < 2; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
//     }else if(array_key_exists($day, $we_pa_num5)){
//       $n_we_pa_num5 = array_keys($we_pa_num5);
//       for($i = 0; $i < 2; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
//     }else if(array_key_exists($day, $we_pa_num6)){
//       $n_we_pa_num6 = array_keys($we_pa_num6);
//       for($i = 0; $i < 2; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
//     }
//   }else if($rty == 3){
//     if(array_key_exists($day, $we_pa_num1)){
//       $n_we_pa_num1 = array_keys($we_pa_num1);
//       for($i = 0; $i < 3; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
//     }else if(array_key_exists($day, $we_pa_num2)){
//       $n_we_pa_num2 = array_keys($we_pa_num2);
//       for($i = 0; $i < 3; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
//     }else if(array_key_exists($day, $we_pa_num3)){
//       $n_we_pa_num3 = array_keys($we_pa_num3);
//       for($i = 0; $i < 3; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
//     }else if(array_key_exists($day, $we_pa_num4)){
//       $n_we_pa_num4 = array_keys($we_pa_num4);
//       for($i = 0; $i < 3; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
//     }else if(array_key_exists($day, $we_pa_num5)){
//       $n_we_pa_num5 = array_keys($we_pa_num5);
//       for($i = 0; $i < 3; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
//     }else if(array_key_exists($day, $we_pa_num6)){
//       $n_we_pa_num6 = array_keys($we_pa_num6);
//       for($i = 0; $i < 3; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
//     }
//   }else if($rty == 4){
//     if(array_key_exists($day, $we_pa_num1)){
//       $n_we_pa_num1 = array_keys($we_pa_num1);
//       for($i = 0; $i < 4; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
//     }else if(array_key_exists($day, $we_pa_num2)){
//       $n_we_pa_num2 = array_keys($we_pa_num2);
//       for($i = 0; $i < 4; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
//     }else if(array_key_exists($day, $we_pa_num3)){
//       $n_we_pa_num3 = array_keys($we_pa_num3);
//       for($i = 0; $i < 4; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
//     }else if(array_key_exists($day, $we_pa_num4)){
//       $n_we_pa_num4 = array_keys($we_pa_num4);
//       for($i = 0; $i < 4; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
//     }else if(array_key_exists($day, $we_pa_num5)){
//       $n_we_pa_num5 = array_keys($we_pa_num5);
//       for($i = 0; $i < 4; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
//     }else if(array_key_exists($day, $we_pa_num6)){
//       $n_we_pa_num6 = array_keys($we_pa_num6);
//       for($i = 0; $i < 4; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
//     }
//   }else if($rty == 5){
//     if(array_key_exists($day, $we_pa_num1)){
//       $n_we_pa_num1 = array_keys($we_pa_num1);
//       for($i = 0; $i < 5; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
//     }else if(array_key_exists($day, $we_pa_num2)){
//       $n_we_pa_num2 = array_keys($we_pa_num2);
//       for($i = 0; $i < 5; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
//     }else if(array_key_exists($day, $we_pa_num3)){
//       $n_we_pa_num3 = array_keys($we_pa_num3);
//       for($i = 0; $i < 5; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
//     }else if(array_key_exists($day, $we_pa_num4)){
//       $n_we_pa_num4 = array_keys($we_pa_num4);
//       for($i = 0; $i < 5; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
//     }else if(array_key_exists($day, $we_pa_num5)){
//       $n_we_pa_num5 = array_keys($we_pa_num5);
//       for($i = 0; $i < 5; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
//     }else if(array_key_exists($day, $we_pa_num6)){
//       $n_we_pa_num6 = array_keys($we_pa_num6);
//       for($i = 0; $i < 5; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
//     }
//   }
//   return $sum;
// }
//
// //получаем неделю(в текстовом формате - "2 неделя")   --------принимаем день и возвращаем строкой "1 неделя"
// function get_now_week($date_now, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){
//   //проверяем в какой неделе запрошеный день
//   if(in_array($date_now, $week_one)) $week_res = $wp1;
//   else if(in_array($date_now, $week_two)) $week_res = $wp2;
//   else if(in_array($date_now, $week_three)) $week_res = $wp3;
//   else if(in_array($date_now, $week_four)) $week_res = $wp4;
//   else if(in_array($date_now, $week_five)) $week_res = $wp5;
//   else if(in_array($date_now, $week_six)) $week_res = $wp6;
//
//   return $week_res;
// }
//
// //получаем массив с неделей(для GET)   ----------возвращает массив с датами пн, вт, ср, чт, пт и сб
// function get_now_day($date_now, $week_one, $week_two, $week_three, $week_five, $week_six){
//   if(in_array($date_now, $week_one)) return $week_one;
//   else if(in_array($date_now, $week_two)) return $week_two;
//   else if(in_array($date_now, $week_three)) return $week_three;
//   else if(in_array($date_now, $week_four)) return $week_four;
//   else if(in_array($date_now, $week_five)) return $week_five;
//   else if(in_array($date_now, $week_six)) return $week_six;
// }
//
//
// function search_day($day, $ar){ return array_search($day, $ar); }
//
// //получаем названия файлов с расписанием
// function scanDirs($dir, $sort){
// 	$list = scandir($dir, $sort);
//   unset($list[count($list)-1], $list[count($list)-1]);
// 	return $list;
// }
//
// //поиск недели среди файлов
// function searchWeek($day, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){//  ----------- получили день, пределили его неделю и вывели его неделю
//   $week = trim(get_now_week($day, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));//определили неделю
//   $files_to = scanDirs("files", 1);//названия excel файлов
//   for($i = 0; $i < count($files_to);$i++){
//     if(!preg_match("/неделя/",$files_to[$i])) unset($files_to[$i]);//оставляем только бакалавриат
//   }
//   $files_to = array_reverse(array_values($files_to));//развернули и переиндексовали
//
//   for($i = 0; $i < count($files_to); $i++){
//     if(preg_match('/'.$week.'/', $files_to[$i])) $res = $files_to[$i];//проверили и сохранили нужный
//     else continue;
//   }
//   return $res;//вывели нужный
// }
//
// //перебор объединённых ячеек ******* activeMergedCells('H5',$aSheet) ********
// function activeMergedCells($cellC, $aSheet){//принимает значение и ссылку на файл
//   $cell = $aSheet->getCell($cellC);
//   $mergedCellsRange = $aSheet->getMergeCells();
//   foreach($mergedCellsRange as $currMergedRange) {
//     if($cell->isInRange($currMergedRange)) {
//     	$currMergedCellsArray = PHPExcel_Cell::splitRange($currMergedRange);
//   		$cell = $aSheet->getCell($currMergedCellsArray[0][0]);
//   		break;
//   	}
//   }
//   return $cell;
// }
//
// // Преобразование нумерации в excel формат ******* exNumToStr(0, 5) => A5 ********
// function exNumToStr($col, $row){ return(PHPExcel_Cell::stringFromColumnIndex($col).$row); }
//
// //проверяет и собирает должным образом выходные данные
// function cellCheck($poi, $aSheet, $group_id, $p){
//
//   $results = array();
//   $role = $poi;
//   $rest = explode(")",$poi);
//
//   //если в 1 ячейке 1 предмет :
//   $group = "";//группа
//   $couple_name = "";//название пары
//   $lector_room = "";//аудитория
//   $corps_num = "";//корпус
//
//   //если в 1 ячейке 2 предмета :
//   $group_one = "";//группа
//   $group_two = "";
//   $lector_room_one = "";//аудитория
//   $lector_room_two = "";
//   $corps_num_one = "";//корпус
//   $corps_num_two = "";
//
//   if(count($rest)>2){ // если в ячейке 2 предмета
//     //var_dump($rest);
//       $group_one = substr($rest[0],-1);
//       $lector_room_one = substr(substr($rest[0], -9), 0, 3);
//       $corps_num_one = substr(substr($rest[0], -9), 4, -4);
//
//       $group_two = substr($rest[1],-1);
//       $lector_room_two = substr(substr($rest[1], -9), 0, 3);
//       $corps_num_two = substr(substr($rest[1], -9), 4, -4);
//
//       $rol1 = substr($rest[0], 0, -11);//первая пара
//       $rol2 = substr($rest[1], 0, -11);//вторая пара
//
//       $r1 = preg_split("/[\s,]+/", $rol1);
//       if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
//       $first1 = array_pop($r1);
//       $second2 = array_pop($r1);
//       $qw1 = $second2.' '.$first1;//преподаватель
//       $vol1 = "";//пара
//       for($i = 0; $i < count($r1); $i++) $vol1 = $vol1.$r1[$i].' ';
//
//
//       $r2 = preg_split("/[\s,]+/", $rol2);
//       if(strlen($r2[count($r2)-1]) == 2) unset($r2[array_search(end($r2), $r2)]);
//       $first2 = array_pop($r2);
//       $second2 = array_pop($r2);
//       $qw2 = $second2.' '.$first2;//преподаватель
//       // $vol2 = "";
//       // for($i = 0; $i < count($r2); $i++) $vol2 = $vol2.$r2[$i].' ';
//       if(substr_count(substr($role,-55), ')') > 1){
//         $vol2 = $vol1;
//       }else{
//         $vol2 = "";//пара
//         for($i = 0; $i < count($r2); $i++) $vol2 = $vol2.$r2[$i].' ';
//       }
//
//       // $r2 = preg_split("/[\s,]+/", $rol2);
//       // if(iconv_strlen(substr($role,-35)) > 2){
//       //   if(strlen($r2[count($r2)-1]) == 2) unset($r2[array_search(end($r2), $r2)]);
//       //   $first2 = array_pop($r2);
//       //   $second2 = array_pop($r2);
//       //   $qw2 = $second2.' '.$first2;//преподаватель
//       //   $vol2 = $vol1;//пара
//       // }else{
//       //   if(strlen($r2[count($r2)-1]) == 2) unset($r2[array_search(end($r2), $r2)]);
//       //   $first2 = array_pop($r2);
//       //   $second2 = array_pop($r2);
//       //   $qw2 = $second2.' '.$first2;//преподаватель
//       //   $vol2 = "";//пара
//       //   for($i = 0; $i < count($r2); $i++) $vol2 = $vol2.$r2[$i].' ';
//       // }
//
//     // array_push($results, $var);
//     // $vol1 - название пары
//     // $qw1 - имя преподаватель
//     // $lector_room_one - номер аудитории
//     // $group_one - номер подгруппы
//     // $corps_num_one - номер корпуса
//     return 'Подгруппа '.$group_one.'•<br>'.$vol1.'•<br>Корпус '.$corps_num_one.' | Аудитория '.$lector_room_one.' | '.$qw1.'•><br>Подгруппа '.$group_two.'•<br>'.$vol2.'•<br>Корпус '.$corps_num_two.' | Аудитория '.$lector_room_two.' | '.$qw2.'•<br>~';
//
//   }else{ // если в ячейке 1 предмет
//     //var_dump($rest);
//
//     $r1 = preg_split("/[\s,]+/", $role);
//     if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
//     $b1 = array_pop($r1);
//     $first = array_pop($r1);
//     $second = array_pop($r1);
//     $qw1 = $second.' '.$first;//преподаватель
//     $vol = "";//пара
//     for($i = 0; $i < count($r1); $i++) $vol = $vol.$r1[$i].' ';
//
//     $rest_new = substr(substr($poi, -2),-2,-1);
//     if(is_numeric($rest_new)){
//       $group = 'Подгруппа '.$rest_new;//группа английского
//       $lector_room = substr(substr($rest[0], -9), 0, 3);
//       $corps_num = substr(substr($rest[0], -9), 4, -4);
//       $couple_name = substr($rest[0], 0, -10);
//
//
//
//       return $group.'•<br>'.$vol.'•<br>Корпус '.$corps_num.' | Аудитория '.$lector_room.' | '.$qw1.'•<br>~';
//     }else{
//
//       $r1 = preg_split("/[\s,]+/", $role);
//       if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
//       $b1 = array_pop($r1);
//       $first = array_pop($r1);
//       $second = array_pop($r1);
//       $qw1 = $second.' '.$first;//преподаватель
//       $vol = "";//пара
//       for($i = 0; $i < count($r1); $i++) $vol = $vol.$r1[$i].' ';
//
//       if($poi != ''){
//         // $mergedCellsRange = $aSheet->getMergeCells();
//         // $aSheet->mergeCells('A'.$id.':R'.$id);
//         // foreach ($mergedCellsRange as $cells) {
//         //   if ($cell->isInRange($cells)) {
//         //     $group = 'Лекция';//если объединена
//         //     break;
//         //   }else{
//         //     $group = 'Семинар';//если НЕ объединена
//         //     break;
//         //   }
//         // }
//
//         //(P.S. можно поспать)
//         if($_GET['group_id'] == '1'){
//           $g1 = activeMergedCells(exNumToStr($group_id,$p),$aSheet);//середина
//           $g2 = activeMergedCells(exNumToStr($group_id+1,$p),$aSheet);//справа
//           if($g1 == $g2) $group = 'Лекция';//если объединена
//           else $group = 'Семинар';//если НЕ объединена
//         }else if($_GET['group_id'] == '2'){
//           $g1 = activeMergedCells(exNumToStr($group_id,$p),$aSheet);//середина
//           $g2 = activeMergedCells(exNumToStr($group_id-1,$p),$aSheet);//справа
//           if($g1 == $g2) $group = 'Лекция';//если объединена
//           else $group = 'Семинар';//если НЕ объединена
//         }else if($_GET['group_id'] == '3'){
//           $g1 = activeMergedCells(exNumToStr($group_id,$p),$aSheet);//середина
//           $g2 = activeMergedCells(exNumToStr($group_id-2,$p),$aSheet);//справа
//           if($g1 == $g2) $group = 'Лекция';//если объединена
//           else $group = 'Семинар';//если НЕ объединена
//         }
//         //$cell = $aSheet->getCell(exNumToStr($group_id,3));
//         //activeMergedCells(exNumToStr($group_id,$i),$aSheet)
//
//         //$group = 'Все';//если это не английский
//         $lector_room = substr($rest[0], -6, -3);
//         $corps_num = substr($rest[0], -2, -1);
//         //$couple_name = substr($rest[0], 0, -8);
//
//         return $group.'•<br>'.$vol.'•<br>Корпус '.$corps_num.' | Аудитория '.$lector_room.' | '.$qw1.'•<br>~';
//       }else{return "";}
//     }
//   }
// }
//
//
// function change_key( $array, $old_key, $new_key ) {
//
//     if( ! array_key_exists( $old_key, $array ) )
//         return $array;
//
//     $keys = array_keys( $array );
//     $keys[ array_search( $old_key, $keys ) ] = $new_key;
//
//     return array_combine( $keys, $array );
// }
//
// function getIdWayLearn($indexSheet = 0, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){
//
//   $objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));//выбираем нужный excel файл и дату(на завтра) date("d.m.Y", $date_tom))
//   $objPHPExcel->setActiveSheetIndex($indexSheet);//выбрали первый лист
//   $aSheet = $objPHPExcel->getActiveSheet();//перешли на него
//
//   $group_id = 0;
//   $pre_arr_way = array();
//   $id_arr_Way = array();
//   for($i = 2; $i < 20; $i++){
//     $cell = $aSheet->getCell(exNumToStr($i,3));
//     array_push($arr_way, $cell);
//     array_push($id_arr_Way, $i);
//     array_push($pre_arr_way, $cell);
//   }
//   $arr_way = array_combine(array_values($id_arr_Way), array_values($pre_arr_way));
//
//   return $arr_way;
// }
//
// //получаем массив с значениями строки направлений
// function revArr($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){
//   $zx = [];
//   $awd2 = getIdWayLearn($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6);
//   for($i = 0; $i < count($awd2);$i++){
//     if($awd2[$i] != '') array_push($zx,trim($awd2[$i]));
//     else unset($awd2[$i]);
//   }
//   return $zx;
// }
//
// //создаём массив с ID направлений
// function get_id_way($zx){
//   $id_arr_Way = [];
//   for($i = 2; $i < count($zx)+2; $i++){
//     array_push($id_arr_Way, $i);
//   }
//   return $id_arr_Way;
// }
//
//
// //Функция парсинга, принимающаяя значения ден, направление, группа и подгруппа
// function parse_to($dayR,$way_learn,$group_id, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6){
//
//   $results = array();//запись вообще всех пар С ПИ
//   $day = array("Пн","Вт","Ср","Чт","Пт","Сб");//день недели
//   $day_res = "";//какой выбран день(for)
//   $day_1 = array();//пн
//   $day_2 = array();//вт
//   $day_3 = array();//ср
//   $day_4 = array();//чт
//   $day_5 = array();//пт
//   $day_6 = array();//сб
//   $time_couple = "";//время пары
//
//   $indexSheet = 0;
//
//   $date_tom = date('d.m.y', time() + 86400);//посчитали завтрашний день
//   //strtotime("+1 day");
//   $objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));//выбираем нужный excel файл и дату(на завтра) date("d.m.Y", $date_tom))
//   if(substr($way_learn,-2) == '18') $indexSheet = 0;
//   else if(substr($way_learn,-2) == '17') $indexSheet = 1;
//   else if(substr($way_learn,-2) == '16') $indexSheet = 3;
//   else if(substr($way_learn,-2) == '15') $indexSheet = 5;
//   $objPHPExcel->setActiveSheetIndex($indexSheet);//выбрали первый лист
//   $aSheet = $objPHPExcel->getActiveSheet();//перешли на него
//
//
//   //ID
//   $idf = get_id_way(revArr($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));
//
//   //названия
//   $arrf = revArr($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6);
//
//   $temp1 = $way_learn.'-'.$group_id;
//   $temp2 = array_search($temp1, $arrf);
//   $group_id = $idf[$temp2];
//
//   $olop = "";
//   $olop2 = "";
//
//
//   //распределяет день, время и пары
//   for($i = 4; $i <= 46; $i++){
//     //записали всё, что есть в масссив
//     $pr = "";
//     //вычислили индекс дня в неделе: 0, 1, 2, 3, 4, 5
//     $rty = search_day($dayR, get_now_day($dayR, $week_one, $week_two, $week_three, $week_five, $week_six));
//
//     if($rty == 0){
//       $day_res = $day[0];
//       $pairs = get_count_pairs($dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);// 5 или 6
//       $ot = get_prev_day($rty, $dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);//3
//       $pol = $aSheet->getCell("B".($ot+1));
//       if(substr($pol,0,1) == 2) $olop = 'Нет пары•<br>~<br>';
//       if(substr($pol,0,1) == 3) $olop2 = 'Нет пары•<br>~<br>';
//       $do = $pairs + $ot + 1;
//
//     }else if($rty == 1){
//       $day_res = $day[1];
//       $pairs = get_count_pairs($dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
//       $ot = get_prev_day($rty, $dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);//9
//       $pol = $aSheet->getCell("B".($ot+1));
//       if(substr($pol,0,1) == 2) $olop = 'Нет пары•<br>~<br>';
//       if(substr($pol,0,1) == 3) $olop2 = 'Нет пары•<br>~<br>';
//       $do = $pairs + $ot + 1;
//
//     }else if($rty == 2){
//       $day_res = $day[2];
//       $pairs = get_count_pairs($dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
//       $ot = get_prev_day($rty, $dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);//14
//       $pol = $aSheet->getCell("B".($ot+1));
//       if(substr($pol,0,1) == 2) $olop = 'Нет пары•<br>~<br>';
//       if(substr($pol,0,1) == 3) $olop2 = 'Нет пары•<br>~<br>';
//       $do = $pairs + $ot + 1;
//
//     }else if($rty == 3){
//       $day_res = $day[3];
//       $pairs = get_count_pairs($dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
//       $ot = get_prev_day($rty, $dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);//19
//       $pol = $aSheet->getCell("B".($ot+1));
//       if(substr($pol,0,1) == 2) $olop = 'Нет пары•<br>~<br>';
//       if(substr($pol,0,1) == 3) $olop2 = 'Нет пары•<br>~<br>';
//       $do = $pairs + $ot + 1;
//
//     }else if($rty == 4){
//       $day_res = $day[4];
//       $pairs = get_count_pairs($dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
//       $ot = get_prev_day($rty, $dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);//24
//       $pol = $aSheet->getCell("B".($ot+1));
//       if(substr($pol,0,1) == 2) $olop = 'Нет пары•<br>~<br>';
//       if(substr($pol,0,1) == 3) $olop2 = 'Нет пары•<br>~<br>';
//       $do = $pairs + $ot + 1;
//
//     }else if($rty == 5){
//       $day_res = $day[5];
//       $pairs = get_count_pairs($dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
//       $ot = get_prev_day($rty, $dayR, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);//31
//       $pol = $aSheet->getCell("B".($ot+1));
//       if(substr($pol,0,1) == 2) $olop = 'Нет пары•<br>~<br>';
//       if(substr($pol,0,1) == 3) $olop2 = 'Нет пары•<br>~<br>';
//       $do = $pairs + $ot + 1;
//
//     }
//
//
//
//     if($i > $ot && $i < $do){
//       //$alo = activeMergedCells(exNumToStr($group_id,$i),$aSheet);//записали ячейку
//       if($i == $ot+1) $alo = cellCheck(activeMergedCells(exNumToStr($group_id,$i),$aSheet),$aSheet,$group_id, $i); // возвращает разобранную строку
//       else $alo = cellCheck(activeMergedCells(exNumToStr($group_id,$i),$aSheet),$aSheet,$group_id, $i);
//     }else{ continue; }
//     $pp = "";
//     $pp2 = "";
//     if($i == $ot+1) {
//       if(substr($pol,0,1) == 2){
//         $pp = $day_res.$olop;
//         array_push($results, $pp);
//       }
//       if(substr($pol,0,1) == 3){
//         $pp = $day_res.$olop2;
//         array_push($results, $pp);
//         $pp2 = $day_res.$olop2;
//         array_push($results, $pp2);
//       }
//
//     }
//
//
//     if ($alo == '') $pr = $day_res.'Нет пары•<br>~<br>';
//     else $pr = $day_res.$alo.'<br>';
//     //else $pr = $day_res.'['.$time_couple.']'.$alo.'•<br>';
//     array_push($results, $pr);
//   }
//   //распределяем данные по дням
//   for($i = 0; $i < count($results); $i++){
//     if(substr($results[$i], 0, 4) == "Пн")  array_push($day_1, substr($results[$i], 4));//пн
//     else if(substr($results[$i], 0, 4) == "Вт") array_push($day_2, substr($results[$i], 4));//вт
//     else if(substr($results[$i], 0, 4) == "Ср") array_push($day_3, substr($results[$i], 4));//ср
//     else if(substr($results[$i], 0, 4) == "Чт") array_push($day_4, substr($results[$i], 4));//чт
//     else if(substr($results[$i], 0, 4) == "Пт") array_push($day_5, substr($results[$i], 4));//пт
//     else if(substr($results[$i], 0, 4) == "Сб") array_push($day_6, substr($results[$i], 4));//сб
//   }
//   if(count($day_1)){
//     for($i = 0; $i < count($day_1); $i++){
//       if($i != (count($day_1)-1)) echo $day_1[$i];
//       else echo substr($day_1[$i], 0, -2);
//     }
//   }
//   if(count($day_2)){
//     for($i = 0; $i < count($day_2); $i++){
//       if($i != (count($day_2)-1)) echo $day_2[$i];
//       else echo substr($day_2[$i], 0, -2);
//     }
//   }
//   if(count($day_3)){
//     for($i = 0; $i < count($day_3); $i++){
//       if($i != (count($day_3)-1)) echo $day_3[$i];
//       else echo substr($day_3[$i], 0, -2);
//     }
//   }
//   if(count($day_4)){
//     for($i = 0; $i < count($day_4); $i++){
//       if($i != (count($day_4)-1)) echo $day_4[$i];
//       else echo substr($day_4[$i], 0, -2);
//     }
//   }
//   if(count($day_5)){
//     for($i = 0; $i < count($day_5); $i++){
//       if($i != (count($day_5)-1)) echo $day_5[$i];
//       else echo substr($day_5[$i], 0, -2);
//     }
//   }
//   if(count($day_6)){
//     for($i = 0; $i < count($day_6); $i++){
//       if($i != (count($day_6)-1)) echo $day_6[$i];
//       else echo substr($day_6[$i], 0, -2);
//     }
//   }
// }
// echo 'Выбранный файл: '.searchWeek($_GET['day'], $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6).'<br><br><br>';
// $pairs = get_count_pairs('26.09.2018', $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
// $ot = get_prev_day(2, '26.09.2018', $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
// $do = $ot + $pairs + 1;
// echo 'От '.$ot.' и до '.$do.'<br>';
//
//
// echo "files/".searchWeek('24.09.2018', $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6).'<br>';
// print_r($files_to);
// echo '<br>';
// print_r(get_now_day('24.09.2018', $week_one, $week_two, $week_three, $week_five, $week_six));
//обновление по пост или гет запросу



//if(isset($_GET['update']) or isset($_POST['update'])) update();
//else parse_to($_GET['day'], $_GET['way'], $_GET['group_id'], $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);

$image = imagecreate(200,20);
$background = imagecolorallocate($image,0,0,0);
$foreground = imagecolorallocate($image,255,255,255);
imagestring($image,5,5,1,"Test",$foreground);
header("Content-type: image/jpeg");

//imagejpeg($image);


//$indexSheet = 0;

// if(substr($_GET['way'],-2) == '18') $indexSheet = 0;
// else if(substr($_GET['way'],-2) == '17') $indexSheet = 1;
// else if(substr($_GET['way'],-2) == '16') $indexSheet = 3;
// else if(substr($_GET['way'],-2) == '15') $indexSheet = 5;

//$objPHPExcel->setActiveSheetIndex($indexSheet);
//выбрали первый лист


// $objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($_GET['day'], $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));//выбираем нужный excel файл и дату(на завтра) date("d.m.Y", $date_tom))
// $objPHPExcel->setActiveSheetIndex(0);//выбрали первый лист
// $aSheet = $objPHPExcel->getActiveSheet();//перешли на него
// $ot = get_prev_day(0, '15.10.2018', $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6);
// //if(exNumToStr(1,$ot+1),$aSheet))
// $pool = $aSheet->getCell("F6");
// echo $pool;
// if(substr($pool,0,1) == 2)
// echo '['.substr($pool,0,1).']';

// //if(iconv_strlen(substr($role,-50)) > 2)
// $r1 = preg_split("/[\s,]+/", $role);
// echo substr($role,-55);
// echo iconv_strlen(substr($role,-55), ')');

//print_r($r1);

// if(strlen($r1[count($r1)-1]) == 2) unset($r1[array_search(end($r1), $r1)]);
// $b1 = array_pop($r1);
// $first = array_pop($r1);
// $second = array_pop($r1);
//
// $qw1 = $second.' '.$first;//преподаватель
// $vol = "";//пара
// for($i = 0; $i < count($r1); $i++) $vol = $vol.$r1[$i].' ';
//
// echo $qw1.'<br>';
// echo $vol;
?>

</div>
</body>
</html>
