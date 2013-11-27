<?php
/**
 * @tobo   获取频道推荐
 * @author majun
 * @editor lifucang 2013-05-17
 * @time   2013-03-11
 */
class tvGetChannelRecommendTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'GetChannelRecommend';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:GetChannelRecommend|INFO] task does things.
Call it with:

  [php symfony tv:GetChannelRecommend|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
      $mongo = $this->getMondongo();
      //先记录日志
      $crontabStartTime=date("Y-m-d H:i:s");
      $crontabLog=new CrontabLog();
      $crontabLog->setTitle('GetChannelRecommend');
      $crontabLog->setContent('');
      $crontabLog->setState(0);
      $crontabLog->setStartTime($crontabStartTime);
      $crontabLog->save();
      //开始
      $channels=$mongo->getRepository('SpService')->getServicesByTag();
      $url = sfConfig::get('app_epghuan_url');
      $apikey = sfConfig::get('app_epghuan_apikey');
      $secretkey = sfConfig::get('app_epghuan_secretkey');
      $nums=0;
  	  foreach ($channels as $channel) {
		  if(!$channel->getChannelCode()) continue;
		  $channelCode = $channel->getChannelCode();
		  $result = $this->getRecommend($url,$channelCode,$apikey,$secretkey);  //根据channelcode 获取推荐wiki
          if($result){
              echo iconv('utf-8','gbk',$channel->getName()),"\r\n";
              $nums++;
          }else{
              echo iconv('utf-8','gbk',$channel->getName().'暂未获取到频道推荐'),"\r\n";
          }
          
	  }
      echo "------finished!\r\n";

      $content="num:".$nums;
      //更新计划任务日志
      $crontabLog_repo = $mongo->getRepository("CrontabLog");  
      $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
      $crontabLoga->setContent($content);
      $crontabLoga->setState(1);
      $crontabLoga->save();
  }
  
  /*
   * 获取推荐wiki  并存入数据库  
   * 以json数据为准  
   * 增加 更新 删除
   */
  private function getRecommend($url,$channelCode,$apikey,$secretkey)
  {
  	  //从接口中获取数据
      $json_post='{"action":"GetRecommendByChannel","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"channel_code": "'.$channelCode.'"}}';
      $getinfo = Common::post_json($url,$json_post);
      
      $result = json_decode($getinfo,true);
      if ($result['media']){
	      $recommendWikiInfo=$result['media'];
      	  //先将原有的推荐删除
          $q = Doctrine_Query::create() ->delete('ChannelRecommend') ->where('channel_code = ?',$channelCode); 
          $numrows = $q->execute();
          
  	      foreach ($recommendWikiInfo as $recommendWiki) {
  	      	  //截取图片文件名
  	      	  $pic = substr($recommendWiki['img'], strrpos($recommendWiki['img'], '/')+1);
  	      	  $channelrecommend = new ChannelRecommend();
  	      	  $channelrecommend->setChannelCode($channelCode);
  	      	  $channelrecommend->setWikiId($recommendWiki['id']);
  	      	  $channelrecommend->setTitle($recommendWiki['title']);
  	      	  $channelrecommend->setPic($pic);
  	      	  $channelrecommend->setPlaytime($recommendWiki['playtime']);
  	      	  $channelrecommend->setRemark($recommendWiki['remark']);
  	      	  $channelrecommend->save();
  	      }
          return true;
      }else{
          return false;
      }
  }
}
