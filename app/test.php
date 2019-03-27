<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script async="" src="http://visearch.info/v2/find-player.min.js"></script>
</head>
<body>
<!-- api_token=be5e27241cc85a6c14532f4ef6952490 -->	
<!-- Плеер -->
<div id="visearch">
<?php

include 'phpQuery.php';

function get_content($URL){
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_URL, $URL); 
  $data = curl_exec($ch); 
  curl_close($ch); 
  return $data; 
} 

$document = phpQuery::newDocument(get_content("http://devredowl.ru/tparser/app/download.php?title=".urlencode($_GET['title']))); //Загружаем полученную страницу в phpQuery
$hentry = $document->find('.parse'); //Находим все элементы с классом "item" (селектор .item)
if(substr_count($hentry, 'iframe') > 1){
  $v = array("<br>", '<div class="parse">', "</div>");
  $hentry = str_replace($v, "", $hentry);
  $res = explode("iframe", $hentry);
  for($i = 0; $i < count($res)-1; $i++){
    $res[$i] = $res[$i]."iframe";
    $query = "<iframe src='".$res[$i]."' width='600' height='400' frameborder='0' allowfullscreen></iframe><br>";
    echo $query;
  }

}else{
  $v = array("<br>", '<div class="parse">', "</div>");
  $hentry = str_replace($v, "", $hentry);
  $query = "<iframe src='".$hentry."' style='position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;' frameborder='0' allowfullscreen></iframe>";
  echo $query;
}

?>
<!-- <iframe src="http://serpens.nl/serial/97070a95bd8daba704ed3e0e9bd54935/iframe" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;" frameborder="0" allowfullscreen></iframe> -->
</div>


<div id="visearch-trailer"><!-- сюда будет вставлен трейлер --></div>

<script type="text/javascript">
  // var moon_params = {
  //   width: "600",
  //   height: "400"
  // };

  !function(e,n,t,r,a){r=e.createElement(n),a=e.getElementsByTagName(n)
  [0],r.async=!0,r.src=t,a.parentNode.insertBefore(r,a)}
  (document,"script","//visearch.info/v2/find-player.min.js");
</script>

</body>
</html>