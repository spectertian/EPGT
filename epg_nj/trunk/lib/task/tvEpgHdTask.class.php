<?php
/**
 *  @todo  :  导出xml节目数据给运营中心
 *  @author:  lifucang 2013-07-31
 *  @example: symfony tv:EpgHd --days=9 --channel=GuiZhouTV --nosend=true //nosend=true时不发送
 *  @example: symfony tv:EpgHd --date=2013-07-31
 *  @example: symfony tv:EpgHd --tomorrow=1
 */
class tvEpgHdTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('days', null, sfCommandOption::PARAMETER_OPTIONAL, 'days'),
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_OPTIONAL, 'date'),
      new sfCommandOption('channel', null, sfCommandOption::PARAMETER_OPTIONAL, 'channel'),
      new sfCommandOption('tomorrow', null, sfCommandOption::PARAMETER_OPTIONAL, 'tomorrow'),  
      new sfCommandOption('nosend', null, sfCommandOption::PARAMETER_OPTIONAL, 'nosend'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'EpgHd';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:EpgHd|INFO] task does things.
Call it with:

  [php symfony tv:EpgHd|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $chanellist = array("LvYouTV","HeiLongJiangTV","ZheJiangTV","HeNanTV","HeBeiTV");
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('EpgHd');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $ftpIp = sfConfig::get('app_centerFtp_host');
        $ftpUser = sfConfig::get('app_centerFtp_username');
        $ftpPass = sfConfig::get('app_centerFtp_password');
        
        $program_repo = $mongo->getRepository("Program");                  
        $channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epgbak');   //只发送回看监测中的频道      
        //$channels=$mongo->getRepository('SpService')->getServicesByTag();
        if(!isset($options['nosend'])){
            $conn = @ftp_connect($ftpIp) or die("FTP服务器连接失败"); 
            @ftp_login($conn,$ftpUser,$ftpPass) or die("FTP服务器登陆失败");   
            ftp_pasv($conn,TRUE);  //被动模式，否则会很慢
        }           

        $date = date("Y-m-d");	   
        $channelNum=0;      //记录发送的频道数
        $daysNum=0;         //记录发送的天数或者具体日期
        foreach($channels as $channel){
            $channelCode=$channel->getChannelCode();
            $channelName=$channel->getName();
            if(!$channelCode) continue; //没有code，继续下一轮循环
            if(isset($options['channel'])){
                if($channel->getChannelId()!=$options['channel']) continue; //如果设置的channelId，继续，只传递一个频道数据过去
            }
            //暂时只发送$chanellist里定义的频道
            if(!in_array($channel->getChannelId(),$chanellist)) {
                continue;
            }
            if(isset($options['days'])){
                $days=$options['days'];
                $daysNum=$days;
                for($i = 0; $i < $days ; $i ++) {
                    $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));
                    $channelXML=$this->getPrograms($date,$channelCode,$channelName);
                    if(!$channelXML) continue; //没有节目数据，继续下一轮循环
                    
                    $file='tmp/hd/HD'.iconv("UTF-8","GBK",$channelName).'_'.$date.'.xml';
                    $target_file='HD'.iconv("UTF-8","GBK",$channelName).'_'.$date.'.xml';        
                    file_put_contents($file,$channelXML);
                    if(!isset($options['nosend'])){
                        @ftp_put($conn,$target_file,$file,FTP_ASCII);  
                    }else{
                        echo "no send\n";
                    }
                }
            }else{
                if($options['tomorrow']==1){
                    $date = date("Y-m-d",strtotime("+1 day"));
                }elseif(isset($options['date'])){
                    $date = $options['date'];
                }
                $daysNum=$date;
                $channelXML=$this->getPrograms($date,$channelCode,$channelName);
                if(!$channelXML) continue; //没有节目数据，继续下一轮循环
                
                $file='tmp/hd/HD'.iconv("UTF-8","GBK",$channelName).'_'.$date.'.xml';
                $target_file='HD'.iconv("UTF-8","GBK",$channelName).'_'.$date.'.xml';        
                file_put_contents($file,$channelXML);
                if(!isset($options['nosend'])){
                    @ftp_put($conn,$target_file,$file,FTP_ASCII);  
                }else{
                    echo "no send\n";
                }
            }
            $channelNum++; 
            echo iconv('utf-8','gbk',$channelName),"\n";
        }           
        if(!isset($options['nosend'])){
            @ftp_close($conn);
        }
	    echo "finished!";
        
        $content="date:$daysNum---channel:".$channelNum;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
    }
    
    private function getPrograms($date,$channelCode,$channelName){
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");       
        $programs = $program_repo->getDayPrograms($channelCode, $date);
        if(!$programs) return null; 
        
        
        $nodeArray=array();
        //channel 信息
        $nodeArray['ProviderInfo'][0][DOM::ATTRIBUTES]=array(
           'id'=>'huanwang',
           'name'=>'huanwang'
        );
        $nodeArray['SchedulerData'][0][DOM::ATTRIBUTES]=array(
           'type'=>'PROGRAM',
        );
        $nodeArray['SchedulerData'][0]['Channel'][0]['ChannelText'][0][DOM::ATTRIBUTES]=array(
           'language'=>'chi',
        );
        $nodeArray['SchedulerData'][0]['Channel'][0]['ChannelText'][0]['ChannelName']=$channelName;
        $k=0;
        
        //节目单
        foreach($programs as $program){
        
           $wiki = $program->getWiki();
           //节目信息
           $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k][DOM::ATTRIBUTES]=array(
               'begintime'=>date("YmdHis",$program['start_time']->getTimestamp()),
               'endtime'=>date("YmdHis",$program['end_time']->getTimestamp()),
               'duration'=>$program['end_time']->getTimestamp()-$program['start_time']->getTimestamp(),
               'eventid'=>0,
               'eventtype'=>0,
           );
           $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0][DOM::ATTRIBUTES]=array(
               'language'=>'chi',
           );
           $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0]['Name']=$program['name'];
           if($wiki){
               //wiki基本信息
               $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0]['Info'][0][DOM::ATTRIBUTES]=array(
                   'director'=>!$wiki->getDirector() ? '' : implode(',', $wiki->getDirector()),
                   'actors'=>!$wiki->getStarring() ? '' : implode(',', $wiki->getStarring()),
                   'type'=>!$wiki->getTags() ? '' : implode(',', $wiki->getTags()),
                   'area'=>!$wiki->getCountry() ? "" : $wiki->getCountry(),
                   'language'=>!$wiki->getLanguage() ? "" : $wiki->getLanguage(),
                   'score'=>$wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt(),
                   'playdate'=> !$wiki->getReleased() ? '' : $wiki->getReleased(),
               );
               $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0]['Description']=$wiki->getContent();
               //海报信息
               $cover = $wiki->getCover();
               if ($cover) {
                    $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0][DOM::ATTRIBUTES]=array(
                       'num'=>3,
                    );
                    $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0]['poster'][0][DOM::ATTRIBUTES] = array(
                        "type" => "small",
                        "size" => "120*160",
                        "url" => $this->thumb_url($cover, 120, 160),
                    );
                    $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0]['poster'][1][DOM::ATTRIBUTES] = array(
                        "type" => "big",
                        "size" => "240*320",
                        "url" => $this->thumb_url($cover, 240, 320),
                    );
                    $nodeArray['SchedulerData'][0]['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0]['poster'][2][DOM::ATTRIBUTES] = array(
                        "type" => "max",
                        "size" => "1240*460",
                        "url" => $this->thumb_url($cover, 1240, 460),
                    );
               }
           }
           $k++;            
           
        }
        $channelXML = DOM::arrayToXMLString($nodeArray,'BroadcastData',array('code'=>$channelCode,'creationtime'=>date("YmsHis"),'version'=>'2.0'));
        return $channelXML;
    }    
    /*
     * 返回wiki的类型
     * author lifucang
     */
    public function getTag($tags,$arr){
        $tmpstr = array();
        foreach($tags as $tag){
            if(!array_search($tag, $arr)){
                $tmpstr[] = $tag;
            }
        }
       return implode(",",$tmpstr);
    }    
    /*
     * 获取动态缩略图
     */
    public function thumb_url($key=null, $width=75, $height=110) {
        if (empty($key)) return '';
        
        return sprintf(sfConfig::get('app_img_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }   
}
