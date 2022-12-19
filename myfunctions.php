<?php

# curl url
function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

# генерация ссылок
function a_href($target, $url)
{
 $a_target = '<a href="'.$url.'?select='.$target.'" >'.$target.'</a>';
 return($a_target);
}


// Парсинг урла
function url_parsing($url) {
 $json_opt = curl($url);
 $arrayiter = new RecursiveArrayIterator(json_decode($json_opt, TRUE));
 $iteriter = new RecursiveIteratorIterator($arrayiter);

 foreach ($arrayiter as $key => $value) {
    // Проверка на массив
    if (is_array($value) == False) {
        $hostname = $value ;
    }
    else {
       foreach ($value as $k => $v) {
             $str .= "<b>". $k."</b>:".a_href(ucfirst($v), $url)." ";
          }
    }
 }
    return $str;
} // end parsing func

# для конвертирования секунд в дни, часы, минуты
function num_word($value, $words, $show = true)
{
 $num = $value % 100;
 if ($num > 19) {
  $num = $num % 10;
 }

 $out = ($show) ?  $value . ' ' : '';
 switch ($num) {
  case 1:  $out .= $words[0]; break;
  case 2:
  case 3:
  case 4:  $out .= $words[1]; break;
  default: $out .= $words[2]; break;
 }

 return $out;
}

function secToStr($secs)
{
 $res = '';

 $days = floor($secs / 86400);
 $secs = $secs % 86400;
 $res .= num_word($days, array('день', 'дня', 'дней')) . ', ';

 $hours = floor($secs / 3600);
 $secs = $secs % 3600;
 $res .= num_word($hours, array('час', 'часа', 'часов')) . ', ';

 $minutes = floor($secs / 60);
 $secs = $secs % 60;
 $res .= num_word($minutes, array('минута', 'минуты', 'минут')) . ', ';

 $res .= num_word($secs, array('секунда', 'секунды', 'секунд'));

 return $res;
}

?>