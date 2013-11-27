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

echo "<font color='red'>某地区频道列表androidtv.getChannelList</font><br/>";
if($client->query('androidtv.getChannelList',array())) 
show($client);
echo "<br/><br/>";

echo "<font color='red'>某频道按天的节目列表androidtv.getWeekByProvinceList</font><br/>";
if($client->query('androidtv.getWeekByProvinceList',array('cctv6','2012-02-17')) 
show($client);
echo "<br/><br/>";

echo "<font color='red'>按分类正在直播的节目列表androidtv.getLiveList</font><br/>";
if($client->query('androidtv.getLiveList',array('北京','电视剧')))
show($client);
echo "<br/><br/>";


echo "<font color='red'>通过wiki_id来获得详细的信息androidtv.getWekiAllInfo</font><br/>";
if($client->query('androidtv.getWekiAllInfo',array('Wiki_id'=>'4e91767fedcd88544400000b')))
show($client);
echo "<br/><br/>";



echo "<font color='red'>获得推荐的信息androidtv.recommendVideo</font><br/>";
if($client->query('androidtv.recommendVideo'))
show($client);
echo "<br/><br/>";


echo "<font color='red'>根据节目名称显示相应的播放时间androidtv.programDetail</font><br/>";
if($client->query('androidtv.programDetail',array('Wiki_id'=>'4f4a608303af7f4b0a001b32')))
show($client);
echo "<br/><br/>";

echo "<font color='red'>获得直播中所常用的几个tags标签androidtv.getLiveTags</font><br/>";
if($client->query('androidtv.getLiveTags'))
show($client);
echo "<br/><br/>";



echo "<font color='red'>获取今天正在或将要播出的节目androidtv.getNowPrograms</font><br/>";
if($client->query('androidtv.getNowPrograms',array(	"channel_code" => '0c387b6ead6bca8f1c6536c044d57a3c')))
show($client);
echo "<br/><br/>";


echo "<font color='red'>获得服务器时间的时间戳androidtv.getServerTime() </font><br/>";  
if($client->query('androidtv.getServerTime'))
show($client);
echo "<br/><br/>";

echo "<font color='red'>获得所有电视台列表androidtv.getAllChannel</font><br/>";  
if($client->query('androidtv.getAllChannel'))
show($client);
echo "<br/><br/>";

echo "<font color='red'>根据wiki_id获得分集剧情androidtv.getMetasByWikiId</font><br/>";  
if($client->query('androidtv.getMetasByWikiId',array('Wiki_id'=>'4f4a608303af7f4b0a001b32')))
show($client);
echo "<br/><br/>";

/*echo "<font color='red'>获取直播电视频道列表信息client.livetv</font><br/>";
if($client->query('client.livetv'))
show($client);
echo "<br/><br/>";

echo "<font color='red'>获取可点播电影列表client.movies</font><br/>";
if($client->query('client.movies'))  //不加if判断，上面的结果会显示出来
show($client);
echo "<br/><br/>";

echo "<font color='red'>获取艺人wikihuan.getActor</font><br/>";
if($client->query('huan.getActor',array(1,5)))  //第1页，每页5条
show($client);
echo "<br/><br/>";

echo "<font color='red'>获取影视剧wiki huan.getFilmTV</font><br/>";
if($client->query('huan.getFilmTV',array(1,5)))  //第1页，每页5条
show($client);
echo "<br/><br/>";

echo "<font color='red'>根据频道 channel_code 获取一星期的电视节目client.weekprograms</font><br/>";  //这个好像没有必要重复7天的，只取最后一天就行
if($client->query('client.weekprograms',array('channel_code'=>'cctv6')))  
show($client);
echo "<br/><br/>";

echo "<font color='red'>获取省份列表android.getProvinceList</font><br/>";  
if($client->query('android.getProvinceList'))
show($client);
echo "<br/><br/>";

echo "<font color='red'>推荐接口读取后台维基推荐数据android.getRecommend</font><br/>";        //$wikiRecommedRepes->getWikiByModel这个看不懂
if($client->query('android.getRecommend'))
show($client);
echo "<br/><br/>";

echo "<font color='red'>热播接口 读取电视剧、电影、栏目的维基推荐数据android.getHotplay</font><br/>";  
if($client->query('android.getHotplay'))
show($client);
echo "<br/><br/>";*/

?>