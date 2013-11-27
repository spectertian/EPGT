<?php
/**
 *  @todo  :  导出json节目数据
 *  @author:  lifucang 2013-09-18
 *  @example: symfony export:EpgJson --days=9 --channel=GuiZhouTV --nosend=true //nosend=true时不发送
 *  @example: symfony export:EpgJson --date=2013-07-31
 *  @example: symfony export:EpgJson --tomorrow=1
 */
class exportEpgJsonTask extends sfMondongoTask
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
      new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update',0),
      new sfCommandOption('nosend', null, sfCommandOption::PARAMETER_OPTIONAL, 'nosend'),
    ));

    $this->namespace        = 'export';
    $this->name             = 'EpgJson';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [export:EpgJson|INFO] task does things.
Call it with:

  [php symfony export:EpgJson|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('exportEpgJson');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        
        //获取更新的频道
        $update = isset($options['update'])?$options['update']:0;
        $channel_codes=array();
        if($update){
            $hour = date('H');
            if($hour<=12){
                $start_time = date('Y-m-d 00:00:00');
                $end_time = date('Y-m-d 12:00:00');
            }elseif($hour>12 && $hour<=16){
                $start_time = date('Y-m-d 12:00:00');
                $end_time = date('Y-m-d 16:00:00');
            }else{
                $start_time = date('Y-m-d 16:00:00');
                $end_time = date('Y-m-d 23:59:59');
            }
            $url = sfConfig::get('app_epghuan_url');
            $apikey = sfConfig::get('app_epghuan_apikey');
            $secretkey = sfConfig::get('app_epghuan_secretkey');
            $json_post='{"action": "GetChannelsUpdate","device": {"dnum": "123"},"user": {"userid": "123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param": {"start_time": "'.$start_time.'","end_time":"'.$end_time.'"}}';
            $getinfo = Common::post_json($url,$json_post);
            if($getinfo){
                $result = json_decode($getinfo,true);
                if($result){
                    $channel_codes=$result['channel_code'];
                }
            }
        }
        $ftpIp = sfConfig::get('app_commonFtp_host');
        $ftpPort = sfConfig::get('app_commonFtp_port');
        $ftpUser = sfConfig::get('app_commonFtp_username');
        $ftpPass = sfConfig::get('app_commonFtp_password');
        
        $program_repo = $mongo->getRepository("Program");                  
        //$channels=$mongo->getRepository('SpService')->getServicesByEpg('check_epgbak');   //只发送回看监测中的频道      
        $channels=$mongo->getRepository('SpService')->getServicesByTag();
        if(!isset($options['nosend'])){
            $config = array(
            			'hostname' => $ftpIp,
            			'username' => $ftpUser,
            			'password' => $ftpPass,
            			'port' => $ftpPort
            				);
            $ftp = new Ftp();
            $ftp->connect($config);
        }           

        $date = date("Y-m-d");	   
        $channelNum=0;      //记录发送的频道数
        $daysNum=0;         //记录发送的天数或者具体日期
        $path = 'json/'.date("Ymd").'/'.date('H');
        if(!isset($options['nosend'])){
            $ftp ->mkdirs($path,775);
        }
        foreach($channels as $channel){
            $channelCode=$channel->getChannelCode();
            $channelName=$channel->getName();
            $channelLogo=$channel->getChannelLogo();
            if(!$channelCode) continue; //没有code，继续下一轮循环
            if(isset($options['channel'])){
                if($channel->getChannelId()!=$options['channel']) continue; //如果设置的channelId，继续，只传递一个频道数据过去
            }
            //只发送$channel_codes里更新的频道
            if($update&&!in_array($channelCode,$channel_codes)) {
                continue;
            }
            if(isset($options['days'])){
                $days=$options['days'];
                $daysNum=$days;
                for($i = 0; $i < $days ; $i ++) {
                    $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));
                    $dateName = date("Ymd",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));
                    $channelXML=$this->getPrograms($date,$channelCode,$channelName,$channelLogo,$program_repo);
                    if(!$channelXML) continue; //没有节目数据，继续下一轮循环
                    
                    $file='tmp/json/JSON_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.json';
                    $target_file='JSON_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.json';        
                    file_put_contents($file,$channelXML);
                    if(!isset($options['nosend'])){
                        $ftp->chgdir($path);
                        $ftp->upload($file,$target_file,'ascii');
                        @unlink($file);
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
                $dateName = date("Ymd",strtotime($date));
                $daysNum=$date;
                $channelXML=$this->getPrograms($date,$channelCode,$channelName,$channelLogo,$program_repo);
                if(!$channelXML) continue; //没有节目数据，继续下一轮循环
                
                $file='tmp/json/JSON_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.json';
                $target_file='JSON_'.iconv("UTF-8","GBK",$channelName).'_'.$dateName.'.json';        
                file_put_contents($file,$channelXML);
                if(!isset($options['nosend'])){
                    $ftp->chgdir($path);
                    $ftp->upload($file,$target_file,'ascii'); 
                    @unlink($file);
                }else{
                    echo "no send\n";
                }
            }
            $channelNum++; 
            //echo iconv('UTF-8','GBK',$channelName),"\n";
        }           
        if(!isset($options['nosend'])){
            $ftp->close();
        }
	    echo date("Y-m-d H:i:s"),"---finished!";
        
        $content="date:$daysNum---channel:".$channelNum;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
    }
    
    private function getPrograms($date,$channelCode,$channelName,$channelLogo,&$program_repo){
        $programs = $program_repo->getDayPrograms($channelCode, $date);
        if(!$programs) return null; 
        $nodeArray=array(); 
        $nodeArray['channel']=array(
            'name'=>$channelName,
            'code'=>$channelCode,
            'logourl'=>$this->file_url($channelLogo)
        );
        $nodeArray['total']=count($programs);
        $k=0;
        foreach($programs as $program){
           $wiki = $program->getWiki();
           if($wiki){
               $nodeArray['programs'][$k]= array(
                					'name' => $program['name'],
                					'date' => $program['date'],
                					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
                					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
                					'wiki_id' => $program['wiki_id'],
                                    'wiki_cover' => $this->file_url($wiki->getCover()),
                                    'tags' => !$wiki->getTags() ? '' : implode(',', $wiki->getTags()),
                                ); 
               $nodeArray = $this->getWikiInfo($wiki, $k, $nodeArray);      
           }else{
               $nodeArray['programs'][$k]= array(
                					'name' => $program['name'],
                					'date' => $program['date'],
                					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
                					'end_time' => date("H:i",$program['end_time']->getTimestamp())
                                ); 
           }       
           $k++;            
        }
        $program_json=json_encode($nodeArray);
        return $program_json;
    }  
    /*
     * 返回wiki信息
     * @editor lifucang
     */
     private function getWikiInfo(&$wiki,$i,$nodeArray){
        $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
        $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
        $tags = !$wiki->getTags() ? '' : implode(',', $wiki->getTags());
        $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
        $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
        $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
        $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
        $nodeArray['programs'][$i]['info'] = array(
            "director" => $director,
            "actors" => $actors,
            "type" => $tags,
            "area" => $area,
            "language" => $language,
            "score" => $score,
            "playdate" => $playdate
        );
        $nodeArray['programs'][$i]['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if ($cover) {
            $nodeArray['programs'][$i]['posters'][0] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => $this->thumb_url($cover, 120, 160),
            );
            $nodeArray['programs'][$i]['posters'][1] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => $this->thumb_url($cover, 240, 320),
            );
            $nodeArray['programs'][$i]['posters'][2] = array(
                "type" => "max",
                "size" => "1240*460",
                "url" => $this->thumb_url($cover, 1240, 460),
            );
        }
        return $nodeArray;
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
    private function file_url($key = null)
    {
        if(is_null($key)){
            return false;
        }else{
            //$url =  sfConfig::get('app_static_url');
            $url =  sfConfig::get('app_img_url');
            $url.='%s/%s/%s/%s';
            $key_prefix = explode('.', $key);
            $key_prefix_year = substr($key_prefix[0],-2);
            $key_prefix_month = substr($key_prefix[0],-5,3);
            $key_prefix_day = substr($key_prefix[0],-9,4);
            return sprintf($url,$key_prefix_year,$key_prefix_month,$key_prefix_day,$key);
        }
    } 
}
