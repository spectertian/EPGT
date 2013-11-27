<?php
header("Content-Type:text/html;charset=utf-8");  
include('IXR.class.php');  

$client  = new IXR_Client('http://www.5itest.tv/RPC');  //服务端
//$client->debug = true;  
function show($client)  
{  
    if($client) {$response = $client->getResponse();}   
    else{echo "<h2>ihefe::Error! ".$client->getErrorCode().":".$client->getErrorMessage().'</h2>';}  
    print_r($response);  
    echo "<br/><hr/><br/>";

}  
// Run a query for PHP  
echo "<pre>";

echo "<font color='red'>某频道按天的节目列表androidtv.getWeekByProvinceList</font><br/>";
if($client->query('androidtv.getWeekByProvinceList',array('channel_code'=>'CCTV-6'))) 
show($client);
echo "<br/><br/>";
?>