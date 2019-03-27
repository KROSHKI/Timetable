<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta charset="utf-8">
</head>
<body>


<?

header("Content-Type: text/html; charset=utf-8");

require_once 'phpQuery.php';
require_once "Classes/PHPExcel.php";
require_once "Classes/PHPExcel/IOFactory.php";

function parser(){

  $new_files = array();
  $last_files = array();
  $files_need = array();


  $url = 'http://students.perm.hse.ru/timetable/';
  $file = file_get_contents($url);

  $doc = phpQuery::newDocument($file);

  $pl = $doc->find('.first_child.text a[class="link fileRef"]');
  $result = explode("<a class=\"link fileRef\" href=\"", $pl);
  $awd = implode('', $result);
  
  $noun = count($result);
  //for($i = 0; $i < $noun; $i=$i+2) unset($result[$i]);
  for($i = 0; $i < $noun; $i++) $result[$i] = urldecode($result[$i]);
  $res_new = array();
  for($i = 0; $i < count($result); $i++){
  	$temp = explode(">", $result[$i]); 
  	if(strpos($temp[1], 'неделя') == false) {
      continue;
    } else {
      //echo strstr($temp[1], '.xls', true).'<br>';
      array_push($res_new, strstr($temp[1], '.xls', true));
    }
  }
  sort($res_new);
  $new_files = $res_new;//массив с новыми названиями файлов на сайте
  $last_files = getLastNameFiles();//массив с старыми названиями файлов на сайте

  for($i = 0; $i < count($new_files);$i++){
    if(trim($new_files[$i]) == trim($last_files[$i])) {
      continue;
    } else {
      $temp = explode(" ",explode("(",$new_files[$i])[0]);
      array_push($files_need,$temp[0].' '.$temp[1]);
    }
  }

  for($i = 0; $i < $noun; $i++){
   $result[$i] = strstr($result[$i], '.xls', true);
  }
  $new_links = array();//нужные ссылки
  for($i = 0; $i < count($result); $i++){
    if(strpos($result[$i], $files_need[0]) === false && strpos($result[$i], $files_need[1]) === false){
      continue;
    } else {
      array_push($new_links, "http://students.perm.hse.ru".trim($result[$i]).".xls");
    }
  }
  file_put_contents('copy_files/result_new.txt', implode("\r\n", $new_links));
  //return $result;

  if(isset($_GET['update']) or isset($_POST['update'])) {

    for($i = 0; $i < count($result); $i++){
      $result[$i] = "http://students.perm.hse.ru".trim($result[$i]).".xls";
    }

    //очистили папку
    if(file_exists("copy_files/")){
      foreach(glob('copy_files/*') as $file) unlink($file);
    }
    for($i = 0; $i < count($result); $i++){
      if(strpos($result[$i], 'неделя') === false){
        continue;
      } else {
        upload_to($result[$i],end(explode("/",$result[$i])));
      }
    }
  }

  for($i = 0; $i < count($files_need);$i++) echo 'Изменения призошли в '.explode(" ",$files_need[$i])[0].' неделе.<br>';
  if(count($files_need) == 0) echo 'Все файлы актуальны!';
  //return $files_need;
}

/*Получение всех названий файлов из какой-либо папки*/
function scanDirs($dir, $sort){
  $list = scandir($dir, $sort);
  unset($list[count($list)-1], $list[count($list)-1]);
  return $list;
}

/*Получение старых названий файлов на сайте */
function getLastNameFiles(){
  /* Очистили магистров и пересдач */
  $files_to = scanDirs("copy_files", 1);
  for($i = 0; $i < count($files_to);$i++){
    if(!preg_match("/неделя/",$files_to[$i])) unset($files_to[$i]);
  }
  for($i = 0; $i < count($files_to);$i++){
    $files_to[$i] = strstr($files_to[$i], ".xls", true);
  }

  for($i = 0; $i < count($files_to); $i++){
    if($files_to[$i] == "") unset($files_to[$i]);
  }

  //развернули и переиндексовали
  $files_to = array_reverse(array_values($files_to));

  return $files_to;
}

/* Загрузка на сервер excel файлов */
function upload_to($filen, $file_new){
    $data = implode("", file($filen));
    $fp = fopen("copy_files/".$file_new, "w");
    fputs($fp, $data);
    fclose($fp);
}

parser();

?>

</body>
</html>