<?php

class tvGetNJBCProgramsTask extends sfMondongoTask
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
      new sfCommandOption('filename', null, sfCommandOption::PARAMETER_OPTIONAL, 'The filename name')      
    ));

    $this->namespace        = 'tv';
    $this->name             = 'getNJBCPrograms';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:getNJBCPrograms|INFO] task does things.
Call it with:

  [php symfony tv:getNJBCPrograms|INFO]
EOF;

    $this->chanellist = array();
  }

    protected function execute($arguments = array(), $options = array())
    {
        global $ftp_conn,$ftp_path,$argv;	
        $chanellist = array("chcdongzuody" =>"CHC动作电影",
                            "chcgaoqingdianying" => "CHC高清电影",
                            "chcjiatingyingyuan" => "CHC家庭影院",
                            "dazhongyingyuan" => "大众影院",
                            "8997a5a46f2b2f73af589e7075fde1ff" => "江苏城市",
                            "35482ad8ed0e51daaed52b3307282520" => "江苏公共",
                            //"江苏国际",
                            "e1aa06acd0fec68c4574d9b6d4129d15" => "江苏教育",
                            "6a12341152e41576d5107eae44a4fef8" => "江苏靓妆",
                            "600bd02be669f8c255d12704fa37fa30" => "江苏体育",
                            "875a32b06f39cec9178403c104a29418" => "江苏影视",
                            "70d3931ad7b8a08380027e10b9f6a8db" => "江苏综艺",
                            "7f0bc7666fadfdbab99f00e79e9d6eed" => "南京教科",
                            "c817d4455a6958f4c978bf805a6befe3" => "南京少儿",
                            "c7b4f13f56db73dd19b00f59cac59e6f" => "南京生活",
                            "c2bb69888fed8681a09c6a084cac5ba7" => "南京十八",
                            "8486f91868f3e6d4f4d6517ca2c2c017" => "南京新闻",
                            //"南京信息",
                            "f702e2980d1d5d07ec19ab7dd8a1d4df" => "南京影视",
                            "780e26a32bcc0a63a9fbcda28729084b" => "南京娱乐");
        $this->chanellist = array_flip($chanellist); 
        if (isset($options['filename'])) {
            $mongo = $this->getMondongo();
            $program_repository = $mongo->getRepository('program');
            $wiki_repository = $mongo->getRepository('wiki');
            $chanelname = $options['filename'];
            
            //if($options['filename']==1) $chanelname='南京新闻';  //临时测试
            echo $chanelname."\n";
            if(!$this->chanellist[$chanelname]) {
                return false;
            }
            $channelcode = $this->chanellist[$chanelname];
            $programdays = $this->getProgramList($chanelname);            
            foreach($programdays as $day => $programs) {
                echo $day."\n";
                if($programs) {                    
                    $program_repository->removeDayPrograms($channelcode, $day);
                    foreach($programs as $prog) {
                        $program = new Program();
                        $program->setChannelCode($channelcode);
                        $program->setName($prog['title']);
                        $program->setStartTime(new DateTime($prog['starttime']));
                        $program->setEndTime(new DateTime($prog['endtime']));
                        $program->setDate($day);
                        $program->setTime($prog['time']);
                        $program->setPublish(1);
                        
                        $title = $this->getSubTitle($prog['title']);
                        $wiki = $wiki_repository->getWikiByTitle($title);
                        if($wiki){
                            $program->setWikiId((string)$wiki->getId());
                            $program->setTags($wiki->getTags());
                        }
                        $program->save();
                    }
                }                
            }
        }else {    
            $ftp_conn = ftp_connect("172.31.143.67") or die("FTP服务器连接失败"); 
            ftp_login($ftp_conn ,"epg","epg") or die("FTP服务器登陆失败");
            $ftp_dir = $this->getNewDir($ftp_conn);
            $this->getDirList($ftp_dir);
            echo "finished!\n";
        }
    }
    
    /**
     * 找到最新的一个目录
     * @param void $ftp_conn
     */    
    private function getNewDir() {
        global $ftp_conn,$ftp_path,$argv;
        $today = date('Y-m-d');
        for($i = 0; $i < 10; $i ++) {
            $date_from = date('Ymd', strtotime($today.'-'.$i.' day'));
            $path = iconv("utf-8","gbk","/".$date_from."/上午更新");
            $ftp_files = ftp_nlist($ftp_conn,$path);
            if(count($ftp_files) > 1) {
                $ftp_path = $path;
                return $ftp_files;
            }
        }  
    }
    
    /**
     * 遍历目录的中的xml文件，符合条件的解析
     * @param void $ftp_conn
     */ 
    private function getDirList($ftp_files) {
        global $ftp_conn,$ftp_path,$argv;
        $cmd = implode(" ", $argv);
        foreach($ftp_files as $file) { 
            $key = iconv("gbk","utf-8",str_replace(".xml",'',$file));
            if(array_key_exists($key,$this->chanellist)) { 
                if (ftp_get($ftp_conn, "./tmp/epg/".iconv("gbk","utf-8",$file), $ftp_path."/".$file, FTP_BINARY)) {
                    echo "Successfully written to $file\n";
                    exec("php ".$cmd." --filename=".$key);
                } else {
                    echo "There was a problem\n";
                }
            }else{
            }
        } 
    }
    
    /**
     * 遍历一个xml，获取节目信息
     * @param void $ftp_conn
     */ 
    private function getProgramList($channelname) {
        global $ftp_conn,$ftp_path,$argv;
        $programs = array();
        if(file_exists("./tmp/epg/".$channelname.".xml")) {
            $xml = simplexml_load_file("./tmp/epg/".$channelname.".xml");
            if($xml) {
                $events = $xml->SchedulerData->Channel->Event;
                foreach($events as $event) {
                    $day = date("Y-m-d",strtotime($event['begintime']));      
                    $starttime = date("Y-m-d H:i:s",strtotime($event['begintime']));
                    $endtime = $this->getEndTimeByDuration($starttime,$event['duration']);
                    $title = (string)$event->EventText->Name;
                    $time = date("H:i",strtotime($event['begintime']));
                    $programs[$day][] = array("title" => $title,
                                        "starttime" => $starttime,
                                        "time" => $time,
                                        "endtime" => $endtime);
                }
            }
            return $programs;
        }
    }
    
    
    /**
     * 根据开始时间和延续时间算出结束时间
     * @param void $ftp_conn
     */ 
    private function getEndTimeByDuration($begintime,$duration) {
        $durationH = intval(substr($duration,0,2));
        $durationM = intval(substr($duration,2,2));
        $durationS = intval(substr($duration,4,2));
        return date("Y-m-d H:i:s",strtotime($begintime." + " .$durationH. " hours ".$durationM. " minutes"));
    }
    
    /**
     * 对节目名称进行过滤
     * @param void $ftp_conn
     */ 
    private function getSubTitle($str){
        //忽略
        $passs = array("蜂乃宝天天30分");    
        if(in_array($str,$passs)) {
            return $str;
        }
        
        //替换
        $patterns = array('/ /','/\(/','/\)/','/\（/','/\）/','/\d+/','/:/','/：/','/-/','/、/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/复/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/.*剧场/');
        $str = preg_replace($patterns, "", $str);
        
        //替换
        $patterns = array('/法治中国/','/视野/','/爱探险的朵拉/',
                          '/欧美流行.*/');
        $repatt = array('法治中国（江苏）','视野（辽宁）','爱探险的Dora',
                        '欧美流行');
        $str = preg_replace($patterns, $repatt, $str);
        $str=str_replace("\r","",$str);
        $str=str_replace("\n","",$str);
        $str=str_replace("\n\r","",$str);
        return $str;
    }
    
    
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }    
}
