<?php
include("IXR_Library.php");

$urls['prod'] = "http://www.epg.huan.tv/RPC2";
$urls['test'] = "http://www.5i.test.cedock.net/RPC2";
$urls['dev'] = "http://www.5itest.tv/RPC2";

if($_GET['method']) {
    $env = isset($_GET['env']) ? $_GET['env'] : "prod";
    $url = $urls[$env]; 
    $method = $_GET['method'];
    $client = new IXR_Client($url);   
    //$client->debug = true;
    
    switch($method) {
        case "getChannelList":
            //$result = $client->query("androidtv.getChannelList",array("province" => "北京", "city" =>""));
            $result = $client->query("androidtv.getChannelList","jiangsu", "");
            break;
        case "getWeekByProvinceList":
            $channel_code = $_GET['channel_code'] ? $_GET['channel_code'] : "cctv2";
            $result = $client->query("androidtv.getWeekByProvinceList", $channel_code, date("Y-m-d"));
            break;
        case "getNowPrograms":
            $channel_code = $_GET['channel_code'] ? $_GET['channel_code'] : "cctv2";
            $result = $client->query("androidtv.getNowPrograms", $channel_code);
            break;
        case "getLiveTags":
            $result = $client->query("androidtv.getLiveTags");
            break;
        case "getLiveList":
            $province = $_GET['province'] ? $_GET['province'] : "";
            $tag = $_GET['tag'] ? $_GET['tag']: "";
            $result = $client->query("androidtv.getLiveList", $province, $tag);
            break;
        case "recommendVideo":
            $result = $client->query("androidtv.recommendVideo");
            break;
        case "search":
            $keyword = $_GET['keyword'] ? $_GET['keyword'] : "电视剧";
            $result = $client->query("androidtv.search", $keyword, 1);
            break;
        case "getWikiAllInfo":
            $result = $client->query("androidtv.getWikiAllInfo",$_GET['wiki_id']);
            break;
        case "postUserLiving":
            $result = $client->query("androidtv.postUserLiving","123456","cctv-6");
            break;
        case "getThemeList":
            $result = $client->query("androidtv.getThemeList");
            break;
        case "getAllChannel":
            $result = $client->query("androidtv.getAllChannel");
            break;            
    }
    $response = $client->getResponse();
    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
    echo "<h2>$env 环境</h2>";
    echo "<h2>============ method ===================</h2>";    
    echo "<h3>$method</h3>";
    echo "<h2>============ response ===================</h2>";
    echo "<pre>";
    //echo "<h2>============ result ===================</h2>";
    //print_r($result);
    print_r($response);
    
} else {

  
}