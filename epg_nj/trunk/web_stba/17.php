<?php
function get_url_content($url, $timeout = 1) {   
    $ch = curl_init();   
    $timeout = 5;   
    curl_setopt ($ch, CURLOPT_URL, $url);   
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);   
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);   
    $file_contents = curl_exec($ch);   
    curl_close($ch);   
    return $file_contents;   
} 
$q=$_GET['q'];
if($q){
    $module=$q;
}else{
    $module='vod';
}
$content=get_url_content("http://172.31.200.11/$module");
echo $content;
?>