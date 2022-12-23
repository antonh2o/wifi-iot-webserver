<?php
$r=''; $sens=''; $updated =''; $str ='';

require 'myfunctions.php';

# ip
$ip = "10.10.99.75";

# url api
$url="http://".$ip."/jsonoptions";
$str = url_parsing($url);

# url api all
$url_all="http://".$ip."/jsonoptions?select=all";

# url read json
$read="http://".$ip."/readjson";

$localtime = date('d.m.Y H:i:s');
$hostname = pars_sensors($read)[0];
$hardparm = pars_sensors($read)[1];
$sensors = pars_sensors($read)[2];
$read = pars_sensors($read)[3];
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
<div class="sys fll">'.$hardparm.'Local Time: '.$localtime.'<br>
  </div>
 </div>
</div>
<div class="c2" >
<div class="h" style="background: #7D8EE2">Sensors:</div>
<div class="c">'.$sensors.'<br>
<b>Не распарсено:</b><br>'.$read.'
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
<script>
$(function() {
 var tab = $('#tabs .tabs-items > div');
 tab.hide().filter(':first').show();

 // Клики по вкладкам.
 $('#tabs .tabs-nav a').click(function(){
  tab.hide();
  tab.filter(this.hash).show();
  $('#tabs .tabs-nav a').removeClass('active');
  $(this).addClass('active');
  return false;
 }).filter(':first').click();

 // Клики по якорным ссылкам.
 $('.tabs-target').click(function(){
  $('#tabs .tabs-nav a[href=' + $(this).attr('href')+ ']').click();
 });

 // Отрытие вкладки из хеша URL
 if(window.location.hash){
  $('#tabs-nav a[href=' + window.location.hash + ']').click();
  window.scrollTo(0, $("#" . window.location.hash).offset().top);
 }
});
</script>
