<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>HSE TIMETABLE</title>
  </head>
  <body>
<div class="parse">
<?

// $screenWidth='<script type="text/javascript">document.write(screen.width);</script>';
// $screenHeight='<script type="text/javascript">document.write(screen.height);</script>';
// echo "Ширина: ".$screenWidth." px;<br>";
// echo "Высота: ".$screenHeight." px;<br>";

header("Content-Type: text/html; charset=utf-8");
require_once 'phpQuery.php';
require_once "Classes/PHPExcel.php";
require_once "Classes/PHPExcel/IOFactory.php";



function parser(){
  $url = 'http://students.perm.hse.ru/timetable/';
  $file = file_get_contents($url);

  $doc = phpQuery::newDocument($file);

  $pl = $doc->find('.first_child.text a[class="link fileRef"]');
  $result = explode("<a class=\"link fileRef\" href=\"", $pl);
  $awd = implode('', $result);
  $result = explode(".xls",$awd);

  for($i = 1; $i < (count($result)+5); $i=$i+2) unset($result[$i]);
  for($i = 1; $i < (count($result)+5); $i++) if(strlen($result[$i]) < 10) unset($result[$i]);
  for($i = 0; $i < count($result); $i++) $result[$i] = urldecode($result[$i]);

  $qwer = implode('', $result);
  $result = explode("</a>", $qwer);
  array_pop($result);
  for($i = 0; $i < count($result); $i++){
    //$result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";
    if(strpos($result[$i], "Магистратура") === false){
      $result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";//если в строке есть слово "Магистратура"
    }else{
      $result[$i] = "http:".trim($result[$i]).".xls";
    }
    // if($i!=(count($result)-1) && $i!=(count($result)-2)) $result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";
    // else $result[$i] = "http:".trim($result[$i]).".xls";
  }
  file_put_contents('result.txt', implode("\r\n", $result));
  for($i = 0; $i < count($result); $i++){
    upload_to($result[$i],end(explode("/",$result[$i])));
  }
}


//очистка старых excel файлов
function update(){
  if(file_exists("files/")){
    foreach(glob('files/*') as $file) unlink($file);
    unlink("result.txt");
  }
   parser();
}

//update();

// $objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek("21.09.2018"));
// $objPHPExcel->setActiveSheetIndex(0);//выбрали первый лист
// $aSheet = $objPHPExcel->getActiveSheet();//перешли на него

//очистили магистров и пересдачи
// $files_to = scanDirs("files", 1);
// for($i = 0; $i < count($files_to);$i++){
//   if(!preg_match("/неделя/",$text)) unset($files_to[$i]);//оставляем только бакалавриат
// }
// $files_to = array_reverse(array_values($files_to));
// $role = [];
// for($i = 4; $i < 37; $i++){
//   array_push($role, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
// }

//масссивы под существующие на сайте массивы
$week_one = [];
$week_two = [];
$week_three = [];
$week_four = [];
$week_five = [];
$week_six = [];

//место под названия недель
$wp1 = "";
$wp2 = "";
$wp3 = "";
$wp4 = "";
$wp5 = "";
$wp6 = "";

//количество пар в какой-либо день
$we_pa_num1 = [];
$we_pa_num2 = [];
$we_pa_num3 = [];
$we_pa_num4 = [];
$we_pa_num5 = [];
$we_pa_num6 = [];

//очистили магистров и пересдачи
$files_to = scanDirs("files", 1);
for($i = 0; $i < count($files_to);$i++){
  if(!preg_match("/неделя/",$files_to[$i])) unset($files_to[$i]);//оставляем только бакалавриат
}

$files_to = array_reverse(array_values($files_to));//развернули и переиндексовали


//генерация массивов недель
function GenAr(){}

//загрузка на сервер excel файлов
function upload_to($filen, $file_new){
    $data = implode("", file($filen));
    $fp = fopen("files/".$file_new, "w");
    fputs($fp, $data);
    fclose($fp);
}



$indexSheet = 0;// у 2 курса
if($_GET['way'] == 'mi'){
  if($_GET['course'] == '2') $indexSheet = 2;// у 2 курса
  else if($_GET['course'] == '3') $indexSheet = 4;// у 3 курса
  else if($_GET['course'] == '1') $indexSheet = 10;
  else if($_GET['course'] == '4') $indexSheet = 10;
}else if($_GET['way'] == 'ba'){
  if($_GET['course'] == '1') $indexSheet = 0;
  else if($_GET['course'] == '2') $indexSheet = 1;
  else if($_GET['course'] == '3') $indexSheet = 3;
  else if($_GET['course'] == '4') $indexSheet = 5;
}


if($indexSheet != 10){
  //место под сеществующие недели(как минимум 6)
  for($j = 0; $j < count($files_to);$j++){
    //array_push($roal, $files_to[$j]);
    $objPHPExcel = PHPExcel_IOFactory::load("files/".$files_to[$j]);

    //echo "В файле: [".$files_to[$j]."] доступно ".$objPHPExcel->getSheetCount()." листов!<br>";
    $objPHPExcel->setActiveSheetIndex($indexSheet);//выбрали первый лист
    $aSheet = $objPHPExcel->getActiveSheet();//перешли на него
    print_r($objPHPExcel->getSheetNames());
    //array_unique – уникальный массив
    if($j == 0){
      for($i = 4; $i < 46; $i++){
        array_push($week_one, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
      }
      $wp1 = trim(substr($files_to[0],0,15));
    }else if($j == 1){
      for($i = 4; $i < 46; $i++){
        array_push($week_two, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
      }
      $wp2 = trim(substr($files_to[1],0,15));
    }else if($j == 2){
      for($i = 4; $i < 46; $i++){
        array_push($week_three, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
      }
      $wp3 = trim(substr($files_to[2],0,15));
    }else if($j == 3){
      for($i = 4; $i < 46; $i++){
        array_push($week_four, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
      }
      $wp4 = trim(substr($files_to[3],0,15));
    }else if($j == 4){
      for($i = 4; $i < 46; $i++){
        array_push($week_five, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
      }
      $wp5 = trim(substr($files_to[4],0,15));
    }else if($j == 5){
      for($i = 4; $i < 46; $i++){
        array_push($week_six, trim(substr(activeMergedCells(exNumToStr(0,$i), $aSheet),-10)));
      }
      $wp6 = trim(substr($files_to[5],0,15));
    }
  }
  //делаем значения уникальными
  if(count($week_one)){
    $we_pa_num1 = array_count_values($week_one);
    $week_one = array_values(array_unique($week_one));
    //if(!stripos(end($week_one), '0')) unset($week_one[array_search(end($week_one), $week_one)]);
    unset($week_one[array_search(end($week_one), $week_one)]);
  }
  if(count($week_two)){
    $we_pa_num2 = array_count_values($week_two);
    $week_two = array_values(array_unique($week_two));
    //if(!stripos(end($week_two), '0')) unset($week_two[array_search(end($week_two), $week_two)]);
    unset($week_two[array_search(end($week_two), $week_two)]);
  }
  if(count($week_three)){
    $we_pa_num3 = array_count_values($week_three);
    $week_three = array_values(array_unique($week_three));
    //if(!stripos(end($week_three), '0')) unset($week_three[array_search(end($week_three), $week_three)]);
    unset($week_three[array_search(end($week_three), $week_three)]);
  }
  if(count($week_four)){
    $we_pa_num4 = array_count_values($week_four);
    $week_four = array_values(array_unique($week_four));
    //if(!stripos(end($week_four), '0')) unset($week_four[array_search(end($week_four), $week_four)]);
    unset($week_four[array_search(end($week_four), $week_four)]);
  }
  if(count($week_five)){
    $we_pa_num5 = array_count_values($week_five);
    $week_five = array_values(array_unique($week_five));
    //if(!stripos(end($week_five), '0')) unset($week_five[array_search(end($week_five), $week_five)]);
    unset($week_five[array_search(end($week_five), $week_five)]);
  }
  if(count($week_six)){
    $we_pa_num6 = array_count_values($week_six);
    $week_six = array_values(array_unique($week_six));
    //if(!stripos(end($week_six), '0')) unset($week_six[array_search(end($week_six), $week_six)]);
    unset($week_six[array_search(end($week_six), $week_six)]);
  }

  // if(isset($_GET['update']) or isset($_POST['update'])){ update(); }
  // else {
  //   $nul = revArr($indexSheet, $week_one[0], $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6);
  //   for($i = 0; $i < count($nul); $i++) {
  //     if($i != count($nul)-1) echo $nul[$i].'•';
  //     else echo $nul[$i];
  //   }
  // }
  echo get_now_week($_GET['day'], $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6).'<br>';
  echo $objPHPExcel->getSheetCount().'<br>';
  print_r($objPHPExcel->getSheetNames()).'<br>';
  echo $files_to[0];
}else{
  echo 'none';
}

function get_count_pairs($day, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6){
  if(array_key_exists($day, $we_pa_num1)) $count_pairs = $we_pa_num1[$day];
  else if(array_key_exists($day, $we_pa_num2)) $count_pairs = $we_pa_num2[$day];
  else if(array_key_exists($day, $we_pa_num3)) $count_pairs = $we_pa_num3[$day];
  else if(array_key_exists($day, $we_pa_num4)) $count_pairs = $we_pa_num4[$day];
  else if(array_key_exists($day, $we_pa_num5)) $count_pairs = $we_pa_num5[$day];
  else if(array_key_exists($day, $we_pa_num6)) $count_pairs = $we_pa_num6[$day];
  return $count_pairs;
}

// нужно посчитать существующую сумму дней на конкретной неделе ДО
//Получить неделю. Получить день

function get_prev_day($rty, $day, $we_pa_num1, $we_pa_num2, $we_pa_num3, $we_pa_num4, $we_pa_num5, $we_pa_num6){

  $sum = 3;
  if($rty == 0){
    $sum = 3;
  }else if($rty == 1){
    if(array_key_exists($day, $we_pa_num1)){
      $n_we_pa_num1 = array_keys($we_pa_num1);
      for($i = 0; $i < 1; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
    }else if(array_key_exists($day, $we_pa_num2)){
      $n_we_pa_num2 = array_keys($we_pa_num2);
      for($i = 0; $i < 1; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
    }else if(array_key_exists($day, $we_pa_num3)){
      $n_we_pa_num3 = array_keys($we_pa_num3);
      for($i = 0; $i < 1; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
    }else if(array_key_exists($day, $we_pa_num4)){
      $n_we_pa_num4 = array_keys($we_pa_num4);
      for($i = 0; $i < 1; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
    }else if(array_key_exists($day, $we_pa_num5)){
      $n_we_pa_num5 = array_keys($we_pa_num5);
      for($i = 0; $i < 1; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
    }else if(array_key_exists($day, $we_pa_num6)){
      $n_we_pa_num6 = array_keys($we_pa_num6);
      for($i = 0; $i < 1; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
    }
  }else if($rty == 2){
    if(array_key_exists($day, $we_pa_num1)){
      $n_we_pa_num1 = array_keys($we_pa_num1);
      for($i = 0; $i < 2; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
    }else if(array_key_exists($day, $we_pa_num2)){
      $n_we_pa_num2 = array_keys($we_pa_num2);
      for($i = 0; $i < 2; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
    }else if(array_key_exists($day, $we_pa_num3)){
      $n_we_pa_num3 = array_keys($we_pa_num3);
      for($i = 0; $i < 2; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
    }else if(array_key_exists($day, $we_pa_num4)){
      $n_we_pa_num4 = array_keys($we_pa_num4);
      for($i = 0; $i < 2; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
    }else if(array_key_exists($day, $we_pa_num5)){
      $n_we_pa_num5 = array_keys($we_pa_num5);
      for($i = 0; $i < 2; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
    }else if(array_key_exists($day, $we_pa_num6)){
      $n_we_pa_num6 = array_keys($we_pa_num6);
      for($i = 0; $i < 2; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
    }
  }else if($rty == 3){
    if(array_key_exists($day, $we_pa_num1)){
      $n_we_pa_num1 = array_keys($we_pa_num1);
      for($i = 0; $i < 3; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
    }else if(array_key_exists($day, $we_pa_num2)){
      $n_we_pa_num2 = array_keys($we_pa_num2);
      for($i = 0; $i < 3; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
    }else if(array_key_exists($day, $we_pa_num3)){
      $n_we_pa_num3 = array_keys($we_pa_num3);
      for($i = 0; $i < 3; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
    }else if(array_key_exists($day, $we_pa_num4)){
      $n_we_pa_num4 = array_keys($we_pa_num4);
      for($i = 0; $i < 3; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
    }else if(array_key_exists($day, $we_pa_num5)){
      $n_we_pa_num5 = array_keys($we_pa_num5);
      for($i = 0; $i < 3; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
    }else if(array_key_exists($day, $we_pa_num6)){
      $n_we_pa_num6 = array_keys($we_pa_num6);
      for($i = 0; $i < 3; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
    }
  }else if($rty == 4){
    if(array_key_exists($day, $we_pa_num1)){
      $n_we_pa_num1 = array_keys($we_pa_num1);
      for($i = 0; $i < 4; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
    }else if(array_key_exists($day, $we_pa_num2)){
      $n_we_pa_num2 = array_keys($we_pa_num2);
      for($i = 0; $i < 4; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
    }else if(array_key_exists($day, $we_pa_num3)){
      $n_we_pa_num3 = array_keys($we_pa_num3);
      for($i = 0; $i < 4; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
    }else if(array_key_exists($day, $we_pa_num4)){
      $n_we_pa_num4 = array_keys($we_pa_num4);
      for($i = 0; $i < 4; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
    }else if(array_key_exists($day, $we_pa_num5)){
      $n_we_pa_num5 = array_keys($we_pa_num5);
      for($i = 0; $i < 4; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
    }else if(array_key_exists($day, $we_pa_num6)){
      $n_we_pa_num6 = array_keys($we_pa_num6);
      for($i = 0; $i < 4; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
    }
  }else if($rty == 5){
    if(array_key_exists($day, $we_pa_num1)){
      $n_we_pa_num1 = array_keys($we_pa_num1);
      for($i = 0; $i < 5; $i++) $sum += $we_pa_num1[$n_we_pa_num1[$i]];
    }else if(array_key_exists($day, $we_pa_num2)){
      $n_we_pa_num2 = array_keys($we_pa_num2);
      for($i = 0; $i < 5; $i++) $sum += $we_pa_num2[$n_we_pa_num2[$i]];
    }else if(array_key_exists($day, $we_pa_num3)){
      $n_we_pa_num3 = array_keys($we_pa_num3);
      for($i = 0; $i < 5; $i++) $sum += $we_pa_num3[$n_we_pa_num3[$i]];
    }else if(array_key_exists($day, $we_pa_num4)){
      $n_we_pa_num4 = array_keys($we_pa_num4);
      for($i = 0; $i < 5; $i++) $sum += $we_pa_num4[$n_we_pa_num4[$i]];
    }else if(array_key_exists($day, $we_pa_num5)){
      $n_we_pa_num5 = array_keys($we_pa_num5);
      for($i = 0; $i < 5; $i++) $sum += $we_pa_num5[$n_we_pa_num5[$i]];
    }else if(array_key_exists($day, $we_pa_num6)){
      $n_we_pa_num6 = array_keys($we_pa_num6);
      for($i = 0; $i < 5; $i++) $sum += $we_pa_num6[$n_we_pa_num6[$i]];
    }
  }
  return $sum;
}

//получаем неделю(в текстовом формате - "2 неделя")   --------принимаем день и возвращаем строкой "1 неделя"
function get_now_week($date_now, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){
  //проверяем в какой неделе запрошеный день
  if(in_array($date_now, $week_one)) $week_res = $wp1;
  else if(in_array($date_now, $week_two)) $week_res = $wp2;
  else if(in_array($date_now, $week_three)) $week_res = $wp3;
  else if(in_array($date_now, $week_four)) $week_res = $wp4;
  else if(in_array($date_now, $week_five)) $week_res = $wp5;
  else if(in_array($date_now, $week_six)) $week_res = $wp6;

  return $week_res;
}

//получаем массив с неделей(для GET)   ----------возвращает массив с датами пн, вт, ср, чт, пт и сб
function get_now_day($date_now, $week_one, $week_two, $week_three, $week_five, $week_six){
  if(in_array($date_now, $week_one)) return $week_one;
  else if(in_array($date_now, $week_two)) return $week_two;
  else if(in_array($date_now, $week_three)) return $week_three;
  else if(in_array($date_now, $week_four)) return $week_four;
  else if(in_array($date_now, $week_five)) return $week_five;
  else if(in_array($date_now, $week_six)) return $week_six;
}


function search_day($day, $ar){ return array_search($day, $ar); }

//получаем названия файлов с расписанием
function scanDirs($dir, $sort){
	$list = scandir($dir, $sort);
  unset($list[count($list)-1], $list[count($list)-1]);
	return $list;
}

//поиск недели среди файлов
function searchWeek($day, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){//  ----------- получили день, пределили его неделю и вывели его неделю
  $week = trim(get_now_week($day, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));//определили неделю
  $files_to = scanDirs("files", 1);//названия excel файлов
  for($i = 0; $i < count($files_to);$i++){
    if(!preg_match("/неделя/",$files_to[$i])) unset($files_to[$i]);//оставляем только бакалавриат
  }
  $files_to = array_reverse(array_values($files_to));//развернули и переиндексовали

  for($i = 0; $i < count($files_to); $i++){
    if(preg_match('/'.$week.'/', $files_to[$i])) $res = $files_to[$i];//проверили и сохранили нужный
    else continue;
  }
  return $res;//вывели нужный
}

//перебор объединённых ячеек ******* activeMergedCells('H5',$aSheet) ********
function activeMergedCells($cellC, $aSheet){//принимает значение и ссылку на файл
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

// Преобразование нумерации в excel формат ******* exNumToStr(0, 5) => A5 ********
function exNumToStr($col, $row){ return(PHPExcel_Cell::stringFromColumnIndex($col).$row); }


function change_key( $array, $old_key, $new_key ) {

    if( ! array_key_exists( $old_key, $array ) )
        return $array;

    $keys = array_keys( $array );
    $keys[ array_search( $old_key, $keys ) ] = $new_key;

    return array_combine( $keys, $array );
}

function getIdWayLearn($indexSheet = 0, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){

  $objPHPExcel = PHPExcel_IOFactory::load("files/".searchWeek($dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));//выбираем нужный excel файл и дату(на завтра) date("d.m.Y", $date_tom))
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

//получаем массив с значениями строки направлений
function revArr($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6){
  $zx = [];
  $awd2 = getIdWayLearn($indexSheet, $dayR, $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6);
  for($i = 0; $i < count($awd2);$i++){
    if($awd2[$i] != '') array_push($zx,trim($awd2[$i]));
    else unset($awd2[$i]);
  }
  return $zx;
}

//создаём массив с ID направлений
function get_id_way($zx){
  $id_arr_Way = [];
  for($i = 2; $i < count($zx)+2; $i++){
    array_push($id_arr_Way, $i);
  }
  return $id_arr_Way;
}
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
//print_r(revArr($indexSheet, $_GET['day'], $week_one, $week_two, $week_three, $week_five, $week_six, $wp1, $wp2, $wp3, $wp4, $wp5, $wp6));


//echo substr($_GET['way'],-2);
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
