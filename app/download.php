<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<div class="parse">
<?php


/*
Загрузка 1 способом
filen – ссылка на файл
$file_new – название файла
*/
function upload_to($filen, $file_new){
    $data = implode("", file($filen));
    $fp = fopen("files/".$file_new, "w");
    fputs($fp, $data);
    fclose($fp);
}

// Получение данных файла с moonwalk.center
function get_content($URL){
 	$ch = curl_init(); 
 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
 	curl_setopt($ch, CURLOPT_URL, $URL); 
 	$data = curl_exec($ch); 
 	curl_close($ch); 
 	return $data; 
} 

// $ch = curl_init("http://moonwalk.center/api/movies_foreign.json?api_token=be5e27241cc85a6c14532f4ef6952490");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_HEADER, false);
// $html = curl_exec($ch);
// curl_close($ch);

// file_put_contents(__DIR__.'/files/main.json', $html);

// $str = '{
//   "report": {
//     "report_name": "movies_foreign",
//     "generated_at": 1550232389,
//     "total_count": 22326,
//     "movies": [
//       {
//         "title_ru": "001 Троллинг",
//         "title_en": "Trolling",
//         "year": 2017,
//         "duration": {
//           "seconds": 4865,
//           "human": "01:21"
//         },
//         "kinopoisk_id": 907972,
//         "world_art_id": null,
//         "pornolab_id": null,
//         "token": "199817e0d677715b",
//         "type": "movie",
//         "camrip": false,
//         "source_type": "WEBRip",
//         "instream_ads": true,
//         "directors_version": false,
//         "iframe_url": "http://moonwalk.cc/video/199817e0d677715b/iframe",
//         "trailer_token": null,
//         "trailer_iframe_url": null,
//         "translator": "Многоголосый закадровый",
//         "translator_id": 67,
//         "added_at": "2017-08-07 14:02:26",
//         "category": null,
//         "block": {
//           "blocked_at": null,
//           "block_ru": false,
//           "block_ua": false
//         },
//         "material_data": {
//           "updated_at": "2019-02-15 02:44:55",
//           "poster": null,
//           "year": 2017,
//           "tagline": "-",
//           "description": null,
//           "age": null,
//           "countries": [
//             "США"
//           ],
//           "genres": [
//             "комедия"
//           ],
//           "actors": [
//             "Филлип Кэйрс",
//             "Katherine Galanova",
//             "Gregg Golding",
//             "Лорен Кэйн",
//             "Энн Луна",
//             "Heather Mignon",
//             "Кэрол Мори",
//             "Louis Morton",
//             "Tyler J. Oakley",
//             "Монтгомери Полсен"
//           ],
//           "directors": [
//             "Gregg Golding"
//           ],
//           "studios": [
//             "Collage Fossil"
//           ],
//           "kinopoisk_rating": 0.0,
//           "kinopoisk_votes": null,
//           "imdb_rating": 4.4,
//           "imdb_votes": 267,
//           "mpaa_rating": null,
//           "mpaa_votes": null
//         }
//       },
//       {
//         "title_ru": "007: Координаты «Скайфолл»",
//         "title_en": "Skyfall",
//         "year": 2012,
//         "duration": null,
//         "kinopoisk_id": 408871,
//         "world_art_id": null,
//         "pornolab_id": null,
//         "token": "4313a2221c5d7df95dde1204733cdc92",
//         "type": "movie",
//         "camrip": false,
//         "source_type": "BluRay",
//         "instream_ads": false,
//         "directors_version": false,
//         "iframe_url": "http://moonwalk.cc/video/4313a2221c5d7df95dde1204733cdc92/iframe",
//         "trailer_token": "01c4a8433d39dc55",
//         "trailer_iframe_url": "https://trailerclub.me/video/01c4a8433d39dc55/iframe",
//         "translator": "Дубляж",
//         "translator_id": 21,
//         "added_at": "2013-11-26 16:30:26",
//         "category": null,
//         "block": {
//           "blocked_at": null,
//           "block_ru": false,
//           "block_ua": false
//         },
//         "material_data": {
//           "updated_at": "2019-02-15 02:00:10",
//           "poster": "https://st.kp.yandex.net/images/film_iphone/iphone360_408871.jpg",
//           "year": 2012,
//           "tagline": "-",
//           "description": "Лояльность Бонда своей начальнице М под угрозой со стороны ее прошлого, которое внезапно даст о себе знать. MI6 подвергается нападению, и агент 007 должен ликвидировать угрозу, несмотря на цену, которую придется заплатить.",
//           "age": 16,
//           "countries": [
//             "Великобритания",
//             "США"
//           ],
//           "genres": [
//             "боевик",
//             "триллер",
//             "приключения"
//           ],
//           "actors": [
//             "Дэниэл Крэйг",
//             "Хавьер Бардем",
//             "Джуди Денч",
//             "Рэйф Файнс",
//             "Наоми Харрис",
//             "Бен Уишоу",
//             "Альберт Финни",
//             "Беренис Марло",
//             "Хелен Маккрори",
//             "Ола Рапас"
//           ],
//           "directors": [
//             "Сэм Мендес"
//           ],
//           "studios": [
//             "Danjaq",
//             "Eon Productions Ltd.",
//             "Metro-Goldwyn-Mayer (MGM)"
//           ],
//           "kinopoisk_rating": 7.429,
//           "kinopoisk_votes": 119890,
//           "imdb_rating": 7.8,
//           "imdb_votes": 580308,
//           "mpaa_rating": 0.0,
//           "mpaa_votes": 0
//         }
//       }
//     ]
//   }
// }';

// file_put_contents(__DIR__.'/files/test.json', $str);

$file = file_get_contents('files/main.json');
$json = json_decode($file);
foreach($json->report->movies as $item) {
    if(isset($_GET['title'])) {
      if(is_in_str($item->title_ru, $_GET['title'])) echo $item->iframe_url."<br>";
    }
    // if(isset($_GET['title'])) {
    //   if(is_in_str($item->title_ru, $_GET['title'])) echo "Название: ".$item->title_ru."<br>URL: ".$item->iframe_url."<br>Качество: ".$item->translator."<br>ID Кинопоиска: ".$item->kinopoisk_id."<br>______________________________________________<br>";
    // } 
    // else if($item->kinopoisk_id == $_GET['kp_id']) echo "Название: ".$item->title_ru."<br>URL: ".$item->iframe_url."<br>Качество: ".$item->translator."<br>______________________________________________<br>";

}
function is_in_str($str, $substr){
   $result = strpos ($str, $substr);
   if ($result === FALSE) return false;
   else return true;   
 }
//$data = get_content('http://moonwalk.center/api/movies_foreign.json?api_token=be5e27241cc85a6c14532f4ef6952490'); 

?>
</div>
</body>
</html>