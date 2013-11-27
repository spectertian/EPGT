<?php

class tvEpgHdTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
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
        //$code=$options['code'];  
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
        /*
		$channels = Doctrine::getTable('Channel')->createQuery("c")
                        ->where('c.publish = 1')
                        ->andWhere('c.type = "cctv"')
                        ->orWhere('c.type = "tv"')
                        ->execute();
        */                
        $channels=$mongo->getRepository('SpService')->getServicesByTag();                
                   
        $conn = @ftp_connect("10.20.88.211") or die("FTP服务器连接失败"); 
        @ftp_login($conn,"huanwang","huanwang") or die("FTP服务器登陆失败");   
        //$conna = @ftp_connect("10.20.20.132") or die("FTP服务器连接失败"); 
        //@ftp_login($conna,"wangyong","wangyong") or die("FTP服务器登陆失败");            
        foreach($channels as $channel){
               if(!$channel->getChannelCode()) continue; //没有code，继续下一轮循环
               $nodeArray=array(); 
               $file='tmp/hd/HD'.iconv("UTF-8","GBK",$channel->getName()).'.xml';
               $target_file='HD'.iconv("UTF-8","GBK",$channel->getName()).'.xml';
               @unlink($file);

               $date = date("Y-m-d");	
               $programs = $program_repo->getDayProgramsWiki($channel->getChannelCode(), $date);
               if(!$programs) continue; //没有program，继续下一轮循环
               $nodeArray=array();
               //channel 信息
               $nodeArray['ProviderInfo'][0][DOM::ATTRIBUTES]=array(
                   'id'=>'huanwang',
                   'name'=>'huanwang'
               );
               $nodeArray['SchedulerData'][0][DOM::ATTRIBUTES]=array(
                   'type'=>'PROGRAM',
               );
               $nodeArray['Channel'][0]['ChannelText'][0][DOM::ATTRIBUTES]=array(
                   'language'=>'chi',
               );
               $nodeArray['Channel'][0]['ChannelText'][0]['ChannelName']=$channel->getName();
               $k=0;
               //节目单
               
               foreach($programs as $program){

                   $wiki = $program->getWiki();
                   //节目信息
                   $nodeArray['Channel'][0]['Event'][$k][DOM::ATTRIBUTES]=array(
                       'begintime'=>date("YmsHis",$program['start_time']->getTimestamp()),
                       'endtime'=>date("YmsHis",$program['end_time']->getTimestamp()),
                       'duration'=>$program['end_time']->getTimestamp()-$program['start_time']->getTimestamp(),
                       'eventid'=>0,
                       'eventtype'=>0,
                   );
                   $nodeArray['Channel'][0]['Event'][$k]['EventText'][0][DOM::ATTRIBUTES]=array(
                       'language'=>'chi',
                   );
                   $nodeArray['Channel'][0]['Event'][$k]['EventText'][0]['Name']=$program['name'];
                   if($wiki){
                       //wiki基本信息
                       $nodeArray['Channel'][0]['Event'][$k]['EventText'][0]['Info'][0][DOM::ATTRIBUTES]=array(
                           'director'=>!$wiki->getDirector() ? '' : implode(',', $wiki->getDirector()),
                           'actors'=>!$wiki->getStarring() ? '' : implode(',', $wiki->getStarring()),
                           'type'=>!$wiki->getTags() ? '' : implode(',', $wiki->getTags()),
                           'area'=>!$wiki->getCountry() ? "" : $wiki->getCountry(),
                           'language'=>!$wiki->getLanguage() ? "" : $wiki->getLanguage(),
                           'score'=>$wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt(),
                           'playdate'=> !$wiki->getReleased() ? '' : $wiki->getReleased(),
                       );
                       $nodeArray['Channel'][0]['Event'][$k]['EventText'][0]['Description']=$wiki->getContent();
                       //海报信息
                       $cover = $wiki->getCover();
                       if ($cover) {
                            $nodeArray['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0][DOM::ATTRIBUTES]=array(
                               'num'=>3,
                            );
                            $nodeArray['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0]['poster'][0][DOM::ATTRIBUTES] = array(
                                "type" => "small",
                                "size" => "120*160",
                                "url" => $this->thumb_url($cover, 120, 160),
                            );
                            $nodeArray['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0]['poster'][1][DOM::ATTRIBUTES] = array(
                                "type" => "big",
                                "size" => "240*320",
                                "url" => $this->thumb_url($cover, 240, 320),
                            );
                            $nodeArray['Channel'][0]['Event'][$k]['EventText'][0]['Posters'][0]['poster'][2][DOM::ATTRIBUTES] = array(
                                "type" => "max",
                                "size" => "1240*460",
                                "url" => $this->thumb_url($cover, 1240, 460),
                            );
                       }
                   }
                   $k++;            
                   
               }
              //$jiemu_json=json_encode($nodeArray);
              $channelXML = DOM::arrayToXMLString($nodeArray,'BroadcastData',array('code'=>$channel->getChannelCode(),'creationtime'=>date("YmsHis"),'version'=>'2.0'));
              
              /*
              $f = fopen($file, 'w');
              fwrite($f, $jiemu);
              fclose($f);
              */
              file_put_contents($file,$channelXML);
              @ftp_put($conn,$target_file,$file,FTP_ASCII);
              //@ftp_put($conna,$target_file,$file,FTP_ASCII);
        }           
	    @ftp_close($conn);
        //@ftp_close($conna);
	    echo "finished!";
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
        
        return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }   
}
