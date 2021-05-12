<?php 
	function decodeUnicode($str){
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', function($matches){return iconv("UCS-2BE","UTF-8",pack("H*", $matches[1]));}, $str);
	}

function checkavailable($videourl){
    
    $ch = curl_init($videourl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    if($code == 200){
    return $videourl;
    //}else{
    //  echo $code;
    }
}

$handle = fopen("https://www.youtube.com/get_video_info?video_id=" . $_REQUEST['v'] . "&el=detailpage", "rb"); 
$contents = stream_get_contents($handle); 
fclose($handle); 
$unicode_str = urldecode($contents);
//echo urldecode($contents);
//echo json_decode($unicode_str);
preg_match_all ("/https:\/\/(.*)(videoplayback)(?<!ytimg)+(.*)\"/U", $unicode_str, $pat_array);

/*
foreach ($pat_array[0] as &$value) {
    $value = substr(decodeUnicode($value), 0, -1);
    echo checkavailable($value);
}*/

$videourl = $pat_array[0][1];
$value = substr(decodeUnicode($videourl), 0, -1);
$reurl=checkavailable($value);

if (isset($reurl))
{
Header("HTTP/1.1 303 See Other");
Header("Location: $reurl");
exit;
}
?> 
