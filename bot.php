<?php

$tgPayload = json_decode(file_get_contents('php://input'), true);
$chatId = $tgPayload["message"]["chat"]["id"];
$text = $tgPayload["message"]["text"];

$weatherJSON=file_get_contents('http://api.openweathermap.org/data/2.5/weather?id=1512086&appid=999999999999999999999999999999999999&units=metric');
$weatherData=json_decode($weatherJSON, true);
$weatherTemp=$weatherData["main"]["temp"];
$weatherTempMin=$weatherData["main"]["temp_min"];
$weatherTempMax=$weatherData["main"]["temp_max"];

function send_message($chatId, $text)
{
    $apiToken = '358664460:AAGEOD5Kq8Dd0RSZMPBlhbsAJ_TvRoEbhD8';
    return file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chatId&text="
        . urlencode($text));
}


function dc($date)
{
$check_time = strtotime($date) - time();
if($check_time <= 0)
{
return false;
}

$days = floor($check_time/86400);
$hours = floor(($check_time%86400)/3600);
$minutes = floor(($check_time%3600)/60);
$seconds = $check_time%60;

$str = '';
if($days >= 0) $str .= declension($days,array('день','дня','дней')).' ';
if($hours >= 0) $str .= declension($hours,array('час','часа','часов')).' ';
if($minutes >= 0) $str .= declension($minutes,array('минута','минуты','минут')).' ';
if($seconds >= 0) $str .= declension($seconds,array('секунда','секунды','секунд'));

return $str;
}


function declension($digit,$expr,$onlyword=false){
if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
if(empty($expr[2])) $expr[2]=$expr[1];
$i=preg_replace('/[^0-9]+/s','',$digit)%100;
if($onlyword) $digit='';
if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
else
{
$i%=10;
if($i==1) $res=$digit.' '.$expr[0];
elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
else $res=$digit.' '.$expr[2];
}
return trim($res);
}

function botay($time, $subj)
{
    $result='До ЕГЭ по '.$subj.' осталось '.$time.'. Надо ботать.';
    return $result;
}

$egeRus='03-06-2019'.' 10:00:00';
$egeMath='29-05-2019'.' 10:00:00';
$egePhys='31-05-2019'.' 10:00:00';
$egeInf='13-06-2019'.' 10:00:00';
$egeChem='05-06-2019'.' 10:00:00';
$egeObsh='10-06-2019'.' 10:00:00';
$armyStart='01-04-2019'.' 00:00:00';
$armyStop='15-07-2019'.' 00:00:00';
$PhysTech='23-02-2019'.'10:00:00';



switch (strtolower($text)) {
    case '/coin@botayhardbot':
    case '/coin':
    $rnd=random_int (0, 99);
        if (($rnd % 2) == 0)
        { send_message($chatId, 'орел') ;}
        else
        { send_message($chatId, 'решка');}
        break;
    case '/rus':
    case '/rus@botayhardbot':
	send_message($chatId, botay(dc($egeRus), 'русскому'));
        break;
    case '/math':
    case '/math@botayhardbot':
        send_message($chatId, botay(dc($egeMath), 'математике'));
        break;
    case '/phys':
    case '/phys@botayhardbot':
        send_message($chatId, botay(dc($egePhys), 'физике'));
        break;
    case '/inf':
    case '/inf@botayhardbot':
        send_message($chatId, botay(dc($egeInf), 'информатике'));
        break;
    case '/chem':
    case '/chem@botayhardbot':
        send_message($chatId, botay(dc($egeChem), 'химии'));
        break;
    case '/obsh':
    case '/obsh@botayhardbot':
        send_message($chatId, botay(dc($egeObsh), 'обществознанию'));
        break;
    case '/pht':
    case '/pht@botayhardbot':
        send_message($chatId, dc($PhysTech));
        break;
    case '/army':
    case '/army@botayhardbot':
        send_message($chatId, 'До начала призыва '.dc($armyStart).'. '."\n".'До конца призыва '.dc($armyStop).'.');
        break;
    case '/help':
    case '/help@botayhardbot':
        send_message($chatId, '/rus /math /phys /inf /chem /obsh /army');
	break;
    case '/unix':
	send_message($chatId, time());
	break;
    case '/ping':
    case '/ping@botayhardbot':
        send_message($chatId, 'да жив да');
        break;
    case '/temp':
    case '/temp@botayhardbot':
        send_message($chatId, 'В академическом колхозе температура составляет '.$weatherTemp.'°C'."\n".'Ну или не меньше '.$weatherTempMin.'°C'.' и не больше '.$weatherTempMax.'°C');
        break;
}

