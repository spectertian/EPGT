<?php
/**
 *  @todo  : 从ftp里获取南京本地节目
 *  @author: lifucang
 */
class tvGetNJBCProgramsTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('filename', null, sfCommandOption::PARAMETER_OPTIONAL, 'The filename name'),
      new sfCommandOption('editor', null, sfCommandOption::PARAMETER_OPTIONAL, 'editor'),
      new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update')       
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
    //symfony tv:getNJBCPrograms --editor=1  编辑介入进来后执行这个
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
                            '322fa7b66243b8d0edef9d761a42f263' => '江苏卫视',
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
                            'yangshijingpin' => '央视精品',
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
                            'jiatingjiankang' => '家庭健康',
                            'youyoubaobei' => '优优宝贝',
                            'guofangjunshi' => '国防军事',
                            'yingyufudao' => '英语辅导',
                            '05d6693c933de13842e71023eee86cdd' => '法治天地',
                            'sitvyejy' => '幼儿教育',
                            'jiangsuweishigaoqing' => '江苏卫视高清',
                            'chcgaoqingdianying' => 'CHC高清电影',
                            'sitvxsjgaoqing' => 'SITV新视觉高清',
                            'doxyinghua' => 'DOX映画',
                            'doxjuchang' => 'DOX剧场',
                            'doxxinzhi' => 'DOX新知',
                            'doxxinyi' => 'DOX新艺',
                            'jsintertv_asia' => '江苏国际',
                            'chcjiatingyingyuan' => 'CHC家庭影院',
                            'chcdongzuody' => 'CHC动作电影',
                            'zhonghuameishi' => '中华美食',
                            'xianfengpingyu' => '先锋乒羽',
                            'liaoningxdm' => '新动漫',
                            'sitvwlqp' => '网络棋牌',
                            'jiangsuzhaokao' => '江苏招考',
                            'dazhongyingyuan' => '大众影院',
                            'diyidaoshi' => '第一导视',
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
            $programdays = $this->getProgramList($chanelname);            
            foreach($programdays as $day => $programs) {
                //echo $day."\n";
                if(strtotime($day)<strtotime(date("Y-m-d"))) continue;  //小于当天的节目信息不添加
                if($programs) {                    
                    $program_repository->removeDayPrograms($channelcode, $day);
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
                        $wiki = $wiki_repository->getWikiByTitle($title);
                        if($wiki){
                            $program->setWikiId((string)$wiki->getId());
                            $program->setTags($wiki->getTags());
                        }
                        $program->save();
                    }
                }                
            }
            //记录该频道的更新时间
            if($update){
                $channel = Doctrine::getTable('Channel')->findOneByCode($channelcode);  
                $channel->setEpgUpdate(date('Y-m-d H:i:s'));
                $channel->save();
            }
        }else {    
            $ftp_conn = ftp_connect("110.173.3.73") or die("FTP服务器连接失败"); 
            ftp_login($ftp_conn ,"njepg","njepg025") or die("FTP服务器登陆失败");
            ftp_pasv($ftp_conn,TRUE);  //被动模式，否则会很慢
            $ftp_dir = $this->getNewDir($ftp_conn);
            //判断编辑是否介入进来
            if (isset($options['editor'])) {
                $editor=true;
            }else{
                $editor=false;
            }
            if($ftp_dir){
                $this->getDirList($ftp_dir,$editor);
            }else{
                echo date("Y-m-d H:i:s"),'------',"No files!\r\n";
            }
        }
    }  
    /**
     * 找到最新的一个目录
     * @param void $ftp_conn
     */    
    private function getNewDir() {
        global $ftp_conn,$ftp_path,$argv;
        /*之前的判断目录，无用
        $today = date('Y-m-d');
        for($i = 0; $i < 10; $i ++) {
            $date_from = date('Ymd', strtotime($today.'-'.$i.' day'));
            $path = iconv("utf-8","gbk","/");
            $ftp_files = ftp_nlist($ftp_conn,$path);
            if(count($ftp_files) > 0) {
                echo '有文件',"\n";
                $ftp_path = $path;
                return $ftp_files;
            }else{
                return false;
            }
        }  
        */
        $path = iconv("utf-8","gbk","/");
        $ftp_files = ftp_nlist($ftp_conn,$path);
        if(count($ftp_files) > 0) {
            $ftp_path = $path;
            return $ftp_files;
        }else{
            return false;
        }
    }
    
    /**
     * 遍历目录的中的xml文件，符合条件的解析
     * @param void $ftp_conn
     * @param update 是否只做更新记录保存，不执行具体解析
     */ 
    private function getDirList($ftp_files,$editor) {
        global $ftp_conn,$ftp_path,$argv;
        $cmd = implode(" ", $argv);
/*
        foreach($ftp_files as $file) {
            echo $file,"\n";
        }
*/      
        $date=date("Ymd");
        $fsc=new FSC(); //文件操作对象file.class.php
        $fsc->notfate_mkdir("./tmp/epgupdate/$date/");
        $fsc->notfate_mkdir("./tmp/epgbak/$date/");
        if(in_array("/over.txt",$ftp_files)){ //如果查找到over.txt才进行操作
            foreach($ftp_files as $file) { 
                $file=str_replace('/','',$file);  //返回的文件名数组里有 /，例如/CCTV-6.xml
                $key = iconv("gbk","utf-8",str_replace(".xml",'',$file));
                $fileutf=iconv("gbk","utf-8",$file);
                if(array_key_exists($key,$this->chanellist)) { 
                    //首先判断epg文件夹里是否有该文件
                    if($editor){ //如果编辑介入进来每天只导入一次，否则编辑记录会被置空
                        $exist=$fsc->isFile('./tmp/epg/',$fileutf);
                        if($exist){
                            ftp_get($ftp_conn, "./tmp/epgupdate/$date/".$file, $ftp_path."/".$file, FTP_BINARY); //放到更新文件夹里
                            //echo $file." is exist\n";
                            //放到临时节目数据库里program_temp里
                            if (ftp_get($ftp_conn, "./tmp/epg/".$fileutf, $ftp_path."/".$file, FTP_BINARY)) {
                                echo "Again written to $file\r\n";
                                exec("php ".$cmd." --update=true --filename=".$key);
                            }    
                        }else{
                            if (ftp_get($ftp_conn, "./tmp/epg/".$fileutf, $ftp_path."/".$file, FTP_BINARY)) {
                                echo "Successfully written to $file\r\n";
                                exec("php ".$cmd." --filename=".$key);
                            }    
                        }
                    }else{
                        if (ftp_get($ftp_conn, "./tmp/epg/".$fileutf, $ftp_path."/".$file, FTP_BINARY)) {
                            echo "Successfully written to $file\r\n";
                            exec("php ".$cmd." --filename=".$key);
                        }   
                    }

                }
                ftp_get($ftp_conn, "./tmp/epgbak/$date/".$file, $ftp_path."/".$file, FTP_BINARY); //先备份一次
                ftp_delete($ftp_conn,$ftp_path."/".$file);  //删除ftp服务器上的相关文件
                //echo $ftp_path."/".$file," is delete\n";
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
