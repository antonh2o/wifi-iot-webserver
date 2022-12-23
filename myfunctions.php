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
 $a_target = '<a href="'.$url.'?select='.$target.'" >'.ucfirst($target).'</a>';
 return($a_target);
}


// Парсинг урла
function url_parsing($url) {
 $str='';
 try{
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
             $str .= "<b>". $k."</b>:".a_href($v, $url)." ";
          }
    }
 }
    return $str;
 }catch(Exception $ex){
   return "Error 404";
 }
} // end parsing url func

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

# для преобразования секунд в строку: дней чаcов минут секунд
function secToStr($secs)
{
 $res = '';
 $days = floor($secs / 86400);
 $secs = $secs % 86400;
 if ( num_word($days,'') > 0 ) {
    $res .= num_word($days, array('день', 'дня', 'дней')) . ', ';
 }

 $hours = floor($secs / 3600);
 $secs = $secs % 3600;
 if ( num_word($hours,'') > 0 ) {
     $res .= num_word($hours, array('час', 'часа', 'часов')) . ', ';
    }

 $minutes = floor($secs / 60);
 $secs = $secs % 60;
 if ( num_word($minutes,'') > 0 ) {
     $res .= num_word($minutes, array('минута', 'минуты', 'минут')) . ', ';
    }

 $res .= num_word($secs, array('секунда', 'секунды', 'секунд'));

 return $res;
}

// Байты -Кб -Мб -Гб
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'Kb', 'Mb', 'Gb', 'Tb');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}


// read json
function pars_sensors($read) {
  $json = curl($read);
  $arrayiter = new RecursiveArrayIterator(json_decode($json, TRUE));
  $iteriter = new RecursiveIteratorIterator($arrayiter);

  foreach ($arrayiter as $key => $value) {
    // Проверка на массив
    if (is_array($value) == False) {
        echo "value: ".$value ;
    }
    else {
     foreach ($value as $k => $v) {
       switch ($k) {
         case 'hostname':
           $hostname = $v;
           break;
         case 'freemem':
           $hardparm .= "FreeMemory: ".formatBytes($v).". <br>";
           break;
         case 'uptime':
           $hardparm .= "Uptime: ".secToStr($v).".<br>";
           break;
         case 'rssi':
           $hardparm .= "WIFI: ".$v." dBm.<br>";
           break;
         case 'btval0101':
           $sens .= "<b>BTHUB 1 LYWSD03:</b></br>Температура: ".$v." °C<br>";
           break;
         case 'btval0102':
           $sens .= "Влажность: ".$v." %<br>";
           break;
         case 'btval0103':
           $sens .= "Battery: ".$v." %<br>";
           break;
         case 'btrssi1':
           $sens .= "Btrssi: ".$v." Dbm.<br>";
           break;
         case 'mb0101':
           $sens .= "<b>Напряжение:</b> ".$v." В. </b><br>";
           break;
         case 'mb0102':
           $sens .= "<b>Ток:</b> ".$v." А. </b><br>";
           break;
         case 'mb0103':
           $sens .= "<b>Мощность:</b> ".$v." Вт. </b><br>";
           break;
         case 'mb0104':
           $sens .= "<b>Счетчик:</b> ".$v." Втч. </b><br>";
           break;
         case 'mb0105':
           $sens .= "<b>Частота сети:</b> ".$v." Гц. </b><br>";
           break;
         case 'mb0106':
           $sens .= "<b>CosF:</b> ".$v."°. </b><br>";
           break;

         default:
           $r .= " <b>". $k."</b>:" . $v. " <br>";
    }
   }
  }
 }
 return array($hostname = $hostname, $hardparm = $hardparm, $sens = $sens, $r = $r);
}


?>