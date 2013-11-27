<?php
/**
 *  @todo  : 从本地tmp/epg文件夹里获取节目数据，使用该计划任务前先将文件传至tmp/epg目录下；暂时为了让测试服务器获取节目数据，不过最终的节目数据要从北京正式服务器tmp/epgbak里获取
 *  @author: lifucang
 */
class tvGetNJBCProgramsFromLocalTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('filename', null, sfCommandOption::PARAMETER_OPTIONAL, 'The filename name'),
      new sfCommandOption('editor', null, sfCommandOption::PARAMETER_OPTIONAL, 'editor'),
      new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update')     
    ));

    $this->namespace        = 'tv';
    $this->name             = 'getNJBCProgramsFromLocal';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:getNJBCProgramsFromLocal|INFO] task does things.
Call it with:

  [php symfony tv:getNJBCProgramsFromLocal|INFO]
EOF;
    $this->chanellist = array();
    //symfony tv:getNJBCProgramsFromLocal
  }

    protected function execute($arguments = array(), $options = array())
    {
        global $ftp_conn,$ftp_path,$argv;	
        $chanellist = array('c817d4455a6958f4c978bf805a6befe3' => '南京少儿',
                            '8486f91868f3e6d4f4d6517ca2c2c017' => '南京新闻',
                            'f702e2980d1d5d07ec19ab7dd8a1d4df' => '南京影视',
                            'c7b4f13f56db73dd19b00f59cac59e6f' => '南京生活',
                            '780e26a32bcc0a63a9fbcda28729084b' => '南京娱乐',
                            '7f0bc7666fadfdbab99f00e79e9d6eed' => '南京教科',
                            'c2bb69888fed8681a09c6a084cac5ba7' => '南京十八',
                            'nanjingxinxi' => '南京信息',
                           // '322fa7b66243b8d0edef9d761a42f263' => '江苏卫视',
                            '8997a5a46f2b2f73af589e7075fde1ff' => '江苏城市',
                            'jiangsuzongyi' => '江苏综艺',
                            '875a32b06f39cec9178403c104a29418' => '江苏影视',
                            'e1aa06acd0fec68c4574d9b6d4129d15' => '江苏教育',
                            '35482ad8ed0e51daaed52b3307282520' => '江苏公共',
                            '600bd02be669f8c255d12704fa37fa30' => '江苏体育',
                            'youmanktws' => '优漫卡通卫视',
                            '5c8dbe3714f3544285a4c4922e2ed01a' => '动漫秀场',
                            '2e79089eeb8dfeb99cd21296facd2025' => '全纪实',
                            'sitvdfcj' => '东方财经',
                            'a57bb859618877ab8cf2d2abf30b4f55' => '游戏风云',
                            '2ccef4b3a8b8f1686594ab6a8c3ba802' => '劲爆体育',
                            '6612405d22d72e43ac5dc9d1762c5109' => '极速汽车',
                            '2ac392f31cfbacdee4cb042d6bd4ad75' => '魅力音乐',
                            '17f79002aa3904b69fc41b463c04cca3' => '卫生健康',
                            'sitvshss' => '生活时尚',
                            '1800444c032205d1443af46a5111fbf1' => '欢笑剧场',
                            '8c2c76bce805d11f5ba0266f8a33c65e' => '都市剧场',
                            'a4d72876a289825786845866024a4765' => '金色频道',
                            '8a29f3de1096334d5a784ebadf4895e3' => '七彩戏剧',
                            'shidaimeishi' => '时代美食',
                            'laonianfu' => '老年福',
                            'liyuan' => '梨园',
                            'diyijuchang' => '第一剧场',
                            'huanqiuqiguan' => '环球奇观',
                            'youxijingji' => '游戏竞技',
                            'xianfengjilu' => '先锋纪录',
                            'faxianzhilv' => '发现之旅',
                            'fengyunjuchang' => '风云剧场',
                            'yangshijingpin' => '央视文化精品', //原央视精品
                            'fengyunyinyue' => '风云音乐',
                            'fengyunzuqiu' => '风云足球',
                            'gaoerfuwangqiu' => '高尔夫网球',
                            'yangshihuaijiujuchang' => '央视怀旧剧场',
                            'shijiedili' => '世界地理',
                            'yunyuzhinan' => '孕育指南',
                            'liuxueshijie' => '留学世界',
                            'zaoqijiaoyu' => '早期教育',
                            'ouzhouzuqiu' => '欧洲足球',
                            'doxtvyinxiangsj' => 'DOXTV音像世界',
                            '6a12341152e41576d5107eae44a4fef8' => '江苏靓妆',
                            '3d23c7fa7feae2ea2b6e3f7f1359aa7a' => '天元围棋',
                            'shuhua' => '书画',
                            'zhongguoqixiang' => '中国气象',
                            'sihaidiaoyu' => '四海钓鱼',
                            'kuailechongwu' => '快乐宠物',
                            'chemi' => '车迷',
                            'huanqiulvyou' => '环球旅游',
                            '7ec3142adb7bde4ae02b11344a4e1ab5' => '摄影',
                            '0c387b6ead6bca8f1c6536c044d57a3c' => '幸福彩',
                            'shoucangtianxia' => '收藏天下',
                            'jiatingjiankang' => '央广健康', //原家庭健康
                            'youyoubaobei' => '优优宝贝',
                            'guofangjunshi' => '国防军事',
                            'yingyufudao' => '英语辅导',
                            '05d6693c933de13842e71023eee86cdd' => '法治天地',
                            'sitvyejy' => '幼儿教育',
                            //'jiangsuweishigaoqing' => '江苏卫视高清',
                            'chcgaoqingdianying' => 'CHC高清电影',
                            'sitvxsjgaoqing' => 'SITV新视觉高清',
                            'doxyinghua' => 'DOX映画高清',   //原DOX映画
                            'doxjuchang' => 'DOX剧场高清',   //原DOX剧场
                            'doxxinzhi' => 'DOX新知高清',    //原DOX新知
                            'doxxinyi' => 'DOX新艺高清',     //原DOX新艺
                            'jsintertv_asia' => '江苏国际',
                            'chcjiatingyingyuan' => 'CHC家庭影院',
                            'chcdongzuody' => 'CHC动作电影',
                            'zhonghuameishi' => '中华美食',
                            'xianfengpingyu' => '先锋乒羽',
                            'liaoningxdm' => '新动漫',
                            'sitvwlqp' => '网络棋牌',
                            'jiangsuzhaokao' => '学习频道',  //原江苏招考
                            'dazhongyingyuan' => '大众影院',
                            'diyidaoshi' => '第一导视',
                            'SITVxinshijue' => 'SITV新视觉',
                            '7aae5790363837c8391effbd38a901ae' => '股市',
                            'shishanggouwu' => '时尚购物',
                            );
        $this->chanellist = array_flip($chanellist); 
        if (isset($options['filename'])) {
            $update=isset($options['update'])?true:false;//判断是放到program_temp表还是program表
            
            $mongo = $this->getMondongo();
            if($update){
                $program_repository = $mongo->getRepository('programTemp');
            }else{
                $program_repository = $mongo->getRepository('program');
            }
            $wiki_repository = $mongo->getRepository('wiki');
            $chanelname = $options['filename'];
            
            //if($options['filename']==1) $chanelname='南京新闻';  //临时测试
            //echo $chanelname."\n";
            if(!$this->chanellist[$chanelname]) {
                return false;
            }
            $channelcode = $this->chanellist[$chanelname];
			echo date("Y-m-d H:i:s"),"To parse the XML !\r\n";
            $programdays = $this->getProgramList(iconv("utf-8","gbk",$chanelname));    
            echo date("Y-m-d H:i:s"),"Begin write the Mongo !\r\n";         			
            foreach($programdays as $day => $programs) {
                //echo $day."\n";
                if(strtotime($day)<strtotime(date("Y-m-d"))) continue;  //小于当天的节目信息不添加
                //if($day!='2013-09-16') continue;  //不是9月16号的不更新
                if($programs) {                    
                    if($channelcode!='dazhongyingyuan'){ //大众影院的不删除
                        $program_repository->removeDayPrograms($channelcode, $day);
                    }
                    foreach($programs as $prog) {
                        if($update){
                            $program = new ProgramTemp();
                        }else{
                            $program = new Program();
                        }
                        $program->setChannelCode($channelcode);
                        $program->setName($prog['title']);
                        $program->setStartTime(new DateTime($prog['starttime']));
                        $program->setEndTime(new DateTime($prog['endtime']));
                        $program->setDate($day);
                        $program->setTime($prog['time']);
                        $program->setPublish(1);
                        
                        $title = $this->getSubTitle($prog['title']);
                        if($title){
                            $wiki = $wiki_repository->getWikiByTitle($title);
                            if($wiki){
                                $program->setWikiId((string)$wiki->getId());
                                $program->setTags($wiki->getTags());
                            }
                        }
                        $program->save();
                    }
                }                
            }
			echo date("Y-m-d H:i:s"),"End write the Mongo !\r\n"; 
            //记录该频道的更新时间
            if($update){
                $channel = Doctrine::getTable('Channel')->findOneByCode($channelcode);  
                $channel->setEpgUpdate(date('Y-m-d H:i:s'));
                $channel->save();
            }
        }else {    
            $ftp_dir = $this->getNewDir('./tmp/epg/');
            if($ftp_dir){
                $this->getDirList($ftp_dir);
            }else{
                echo date("Y-m-d H:i:s"),'------',"No files!\r\n";
            }
        }
    }  
    /**
     * 找到最新的一个目录
     * @param void $ftp_conn
     */    
    private function getNewDir($path) {
        $ftp_files = scandir($path);
        if(count($ftp_files) > 0) {
            return $ftp_files;
        }else{
            return false;
        }
    }
    
    /**
     * 遍历目录的中的xml文件，符合条件的解析
     */ 
    private function getDirList($ftp_files) {
        global $argv;
        $cmd = implode(" ", $argv);
        /*
        foreach($ftp_files as $file) {
            echo $file,"\n";
        }
        exit;
        */
        if(in_array("over.txt",$ftp_files)){ //如果查找到over.txt才进行操作
            foreach($ftp_files as $file) { 
                if($file=='.'||$file=='..') continue;
                $key = iconv("gbk","utf-8",str_replace(".xml",'',$file));
                if(array_key_exists($key,$this->chanellist)) { 
                        echo $file,"\n";
                        exec("php ".$cmd." --filename=".$key);
                }
            }
            echo date("Y-m-d H:i:s"),'------',"finished!\r\n";
        }else{
            echo date("Y-m-d H:i:s"),'------',"No over.txt!\r\n";
        }
    }
    
    /**
     * 遍历一个xml，获取节目信息
     * @param void $ftp_conn
     */ 
    private function getProgramList($channelname) {
        $programs = array();
        if(file_exists("./tmp/epg/".$channelname.".xml")) {
            //$xml = simplexml_load_file("./tmp/epg/".$channelname.".xml");
            $xmlcontent=file_get_contents("./tmp/epg/".$channelname.".xml");
            $xmlcontent=str_replace(iconv('utf-8','gbk','囧'),'',$xmlcontent);
            $xml=simplexml_load_string($xmlcontent);
            if($xml) {
                $events = $xml->SchedulerData->Channel->Event;
                foreach($events as $event) {
                    $day = date("Y-m-d",strtotime($event['begintime']));      
                    $starttime = date("Y-m-d H:i:s",strtotime($event['begintime']));
                    $endtime = $this->getEndTimeByDuration($starttime,$event['duration']);
                    $title = (string)$event->EventText->Name;
                    $title = str_replace("\n",'',$title);
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
        $patterns = array('/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/','/《.*》/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/复/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/.*剧场/','/;提示/',
                          '/第.*集/','/\d+集/','/\d/','/―/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/-.*/');
                          
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
