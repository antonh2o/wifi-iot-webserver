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

# ip
$ip = "10.10.99.77";

# url api
$url="http://".$ip."/jsonoptions";
$str = url_parsing($url);

# url api all
$url_all="http://".$ip."/jsonoptions?select=all";


# url read json
$read="http://".$ip."/readjson";

// read json
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
           $freemem = "FreeMemory: ".$v." B.";
           break;
         case 'uptime':
           $uptime = $v." sec.";
           break;
         case 'rssi':
           $rssi = $v." dBm.";
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
         default:
           $r .= " ". $k.":" . $v. " <br>";
    }
  }
 }
}


$localtime = date('d.m.Y H:i:s');
echo '
<!doctype html>
<html lang="en">
<head>
  <title>Wifi-IoT: '.$hostname.'</title>
  <meta charset="utf-8">
  <meta http-equiv="REFRESH" content="60">
  <meta name="viewport" content="width=480" />
  <meta name="mobile-web-app-capable" content="yes" />
  <link rel="stylesheet" href="jquery-ui.css">
  <link rel="stylesheet" href="main.css">
  <script src="jquery-3.6.0.min.js"></script>
  <script src="jquery-ui.min.js"></script>
</head>
<body>

<br>
<div style="text-align: center">
<div style="display: inline-block">
<div class="name fll">'.$hostname.'
 <div class="www">MaksMS <a href="http://wifi-iot.com" target="_blank">wifi-iot.com</a>
<br> Pro mode</div>
</div>
<div class="spV2 fll"></div>
<div class="spV fll"></div>
<div class="spV2 fll"></div>
<div class="sys fll">'.$freemem.'
<br>Uptime: '.$uptime.'
  <br> WIFI: '.$rssi.'
  <br>Updated: '.$updated.'
  <br>Local Time: '.$localtime.'
  <br>
  </div>
 </div>
</div>
<div class="c2" >
<div class="h" style="background: #7D8EE2">Sensors:</div>
<div class="c">'.$sens.'<br>
<br><b>Не распарсено:</b><br>'.$r.'
<div class="dummy fll"> </div>
<script type="text/javascript" src="js.js"></script>
</div>
<br><div class="h" style="background:#808080">Config:</div>
<div class="c"> '.$str.'
  </div>
 </div>
</div>
  </body>
</html>
';

?>