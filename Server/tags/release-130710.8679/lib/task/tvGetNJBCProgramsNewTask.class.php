<?php
/**
 *  从ftp里获取南京本地节目
 *  @author: lifucang
 */
class tvGetNJBCProgramsNewTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update')       
        ));

        $this->namespace        = 'tv';
        $this->name             = 'getNJBCProgramsNew';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:getNJBCProgramsNew|INFO] task does things.
Call it with:

  [php symfony tv:getNJBCProgramsNew|INFO]
EOF;

        $this->chanellist = array();
    }

    protected function execute($arguments = array(), $options = array())
    {
        global $ftp_conn,$ftp_path,$argv;	
        $this->chanellist = array(
            "南京少儿" => "c817d4455a6958f4c978bf805a6befe3",
            "南京新闻" => "8486f91868f3e6d4f4d6517ca2c2c017",
            "南京影视" => "f702e2980d1d5d07ec19ab7dd8a1d4df",
            "南京生活" => "c7b4f13f56db73dd19b00f59cac59e6f",
            "南京娱乐" => "780e26a32bcc0a63a9fbcda28729084b",
            "南京教科" => "7f0bc7666fadfdbab99f00e79e9d6eed",
            "南京十八" => "c2bb69888fed8681a09c6a084cac5ba7",
            "南京信息" => "nanjingxinxi",
            "江苏城市" => "8997a5a46f2b2f73af589e7075fde1ff",
            "江苏综艺" => "jiangsuzongyi",
            "江苏影视" => "875a32b06f39cec9178403c104a29418",
            "江苏教育" => "e1aa06acd0fec68c4574d9b6d4129d15",
            "江苏公共" => "35482ad8ed0e51daaed52b3307282520",
            "江苏体育休闲" => "600bd02be669f8c255d12704fa37fa30",
            "优漫卡通" => "youmanktws",
            "动漫秀场" => "5c8dbe3714f3544285a4c4922e2ed01a",
            "全纪实" => "2e79089eeb8dfeb99cd21296facd2025",
            "东方财经" => "sitvdfcj",
            "游戏风云" => "a57bb859618877ab8cf2d2abf30b4f55",
            "劲爆体育" => "2ccef4b3a8b8f1686594ab6a8c3ba802",
            "极速汽车" => "6612405d22d72e43ac5dc9d1762c5109",
            "魅力音乐" => "2ac392f31cfbacdee4cb042d6bd4ad75",
            "卫生健康" => "17f79002aa3904b69fc41b463c04cca3",
            "生活时尚" => "sitvshss",
            "欢笑剧场" => "1800444c032205d1443af46a5111fbf1",
            "都市剧场" => "8c2c76bce805d11f5ba0266f8a33c65e",
            "金色频道" => "a4d72876a289825786845866024a4765",
            "七彩戏剧" => "8a29f3de1096334d5a784ebadf4895e3",
            "时代美食" => "shidaimeishi",
            "老年福" => "laonianfu",
            "梨园" => "liyuan",
            "第一剧场" => "diyijuchang",
            "环球奇观" => "huanqiuqiguan",
            "游戏竞技" => "youxijingji",
            "先锋纪录" => "xianfengjilu",
            "发现之旅" => "faxianzhilv",
            "风云剧场" => "fengyunjuchang",
            "央视文化精品" => "yangshijingpin",
            "风云音乐" => "fengyunyinyue",
            "风云足球" => "fengyunzuqiu",
            "高尔夫网球" => "gaoerfuwangqiu",
            "央视怀旧剧场" => "yangshihuaijiujuchang",
            "世界地理" => "shijiedili",
            "孕育指南" => "yunyuzhinan",
            "留学世界" => "liuxueshijie",
            "早期教育" => "zaoqijiaoyu",
            "欧洲足球" => "ouzhouzuqiu",
            "DOXTV音像世界" => "doxtvyinxiangsj",
            "江苏靓妆" => "6a12341152e41576d5107eae44a4fef8",
            "天元围棋" => "3d23c7fa7feae2ea2b6e3f7f1359aa7a",
            "书画" => "shuhua",
            "中国气象" => "zhongguoqixiang",
            "四海钓鱼" => "sihaidiaoyu",
            "快乐宠物" => "kuailechongwu",
            "车迷" => "chemi",
            "环球旅游" => "huanqiulvyou",
            "摄影" => "7ec3142adb7bde4ae02b11344a4e1ab5",
            "幸福彩" => "0c387b6ead6bca8f1c6536c044d57a3c",
            "收藏天下" => "shoucangtianxia",
            "央广健康" => "jiatingjiankang",
            "优优宝贝" => "youyoubaobei",
            "国防军事" => "guofangjunshi",
            "英语辅导" => "yingyufudao",
            "法治天地" => "05d6693c933de13842e71023eee86cdd",
            "幼儿教育" => "sitvyejy",
            "CHC高清电影" => "chcgaoqingdianying",
            "SITV新视觉高清" => "sitvxsjgaoqing",
            "DOX映画高清" => "doxyinghua",
            "DOX剧场高清" => "doxjuchang",
            "DOX新知高清" => "doxxinzhi",
            "DOX新艺高清" => "doxxinyi",
            "江苏国际" => "jsintertv_asia",
            "CHC家庭影院" => "chcjiatingyingyuan",
            "CHC动作电影" => "chcdongzuody",
            "中华美食" => "zhonghuameishi",
            "先锋乒羽" => "xianfengpingyu",
            "新动漫" => "liaoningxdm",
            "网络棋牌" => "sitvwlqp",
            "学习频道" => "jiangsuzhaokao",
            "大众影院" => "dazhongyingyuan",
            "第一导视" => "diyidaoshi",
            "SITV新视觉" => "SITVxinshijue",
            "股市" => "7aae5790363837c8391effbd38a901ae",
            "时尚购物" => "shishanggouwu"
        );
        
        //连接ftp服务器
        $ftp_conn = ftp_connect("110.173.3.73",1001) or die("FTP服务器连接失败"); 
        ftp_login($ftp_conn ,"njepg","njepg025") or die("FTP服务器登陆失败");
        ftp_pasv($ftp_conn,TRUE);  //被动模式，否则会很慢

        //遍历ftp目录,将文件放入epg目录
        $ftp_path = iconv("utf-8","gbk","/");
        $ftp_files = ftp_nlist($ftp_conn,$ftp_path);
        if(count($ftp_files) > 0) {
            echo date("Y-m-d H:i:s"),'------',"Start get files from FTP!\r\n";
            $this->getDirList($ftp_files);
            echo date("Y-m-d H:i:s"),'------',"End get files from FTP!\r\n";
        }else{
            echo date("Y-m-d H:i:s"),'------',"No files In FTP!\r\n";
        }
        
        //遍历本地tmp/epg目录
        $files=$this->getLocalDir('./tmp/epg/');
        if(count($files)==0){
            echo date("Y-m-d H:i:s"),'------',"No files!\r\n";
            exit;
        }
        //开始循环本地tmp/epg目录
        echo date("Y-m-d H:i:s"),'##############',"start!\r\n";
        foreach($files as $file) {
            echo "------$file\r\n";
            $chanelnameGbk=str_replace(".xml",'',$file);
            $chanelname = iconv("gbk","utf-8",str_replace(".xml",'',$file));
            $mongo = $this->getMondongo();
            $wiki_repository = $mongo->getRepository('wiki');
            $editormemory = $mongo->getRepository('EditorMemory');
            if(!$this->chanellist[$chanelname]) {
                unlink('./tmp/epg/'.$file);
                continue;
            }
            $channelcode = $this->chanellist[$chanelname];
            //获取该频道省，市及类型开始
            $channel_type=array();
            $channela = Doctrine::getTable('Channel')->findOneByCode($channelcode);  
            if($channela){
                if($channela->getType())
                    $channel_type[] = $channela->getType();
                if($channela->getProvince())    
                    $channel_type[] = $channela->getProvince();
                if($channela->getCity())
                    $channel_type[] = $channela->getCity();
            }
            //获取该频道省，市及类型结束
            echo date("Y-m-d H:i:s"),"To parse the XML !\r\n";
            $programdays = $this->getProgramList($chanelnameGbk);
            if(count($programdays)>0){
                echo date("Y-m-d H:i:s"),"Begin write the Mongo !\r\n";    
            }else{
                echo date("Y-m-d H:i:s"),"Fail parse the XML !\r\n";    
            }   
            $isdate = false; //记录是否有当天数据   
            $isdateall = false; //记录是否有当天数据,用于频道更新 
            $date = date("Y-m-d");  
            foreach($programdays as $day => $programs) {
                if(strtotime($day)<strtotime(date("Y-m-d"))) continue;  //小于当天的节目信息不添加
                if($programs) {
                    //判断是否有当天数据开始
                    if($day == $date){
                        $isdate = true;
                        $isdateall = true;
                        $program_repository = $mongo->getRepository('programTemp');
                    }else{
                        $isdate = false;
                        $program_repository = $mongo->getRepository('program');
                    }
                    //判断是否有当天数据结束
                    if($channelcode!='dazhongyingyuan'){ //大众影院的不删除
                        $program_repository->removeDayPrograms($channelcode, $day);
                    }
                    foreach($programs as $prog) {
                        //大众影院的  "以播出为准" 不入库
                        if($channelcode=='dazhongyingyuan'&&$prog['title']=='以播出为准'){
                            break;
                        }
                        if($isdate){
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
                        $program->setChannelType($channel_type);
                        $title = $this->getSubTitle($prog['title']);
                        if($title){
                            //$wiki = $wiki_repository->getWikiByTitle($title);
                            try{
                                $wiki = $wiki_repository->getOneWikiByTitle($title);
                            }catch(Exception $e){
                                echo $e->getMessage(),"\r\n"; 
                            }
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
            if($isdateall){
                $channel = Doctrine::getTable('Channel')->findOneByCode($channelcode);  
                $channel->setEpgUpdate(date('Y-m-d H:i:s'));
                $channel->save();
            }
            //删除该文件
            unlink('./tmp/epg/'.$file);
            sleep(2);
        }
        echo date("Y-m-d H:i:s"),'##############',"finished!\r\n";
    }  
    /**
     * 遍历本地的目录
     * @param void $ftp_conn
     */    
    private function getLocalDir($path) {
        $files = scandir($path);
        $fileArr=array();
        if($files) {
            foreach($files as $file) { 
                if(!($file=='.'||$file=='..')){
                    $fileArr[]=$file;
                }
            }
            return $fileArr;
        }else{
            return null;
        }
        
    }
    /**
     * 遍历ftp目录的中的xml文件，符合条件的解析
     * @param void $ftp_conn
     */ 
    private function getDirList($ftp_files) {
        global $ftp_conn,$ftp_path,$argv;
        $date = date("Ymd");
        $fsc = new FSC(); //文件操作对象file.class.php
        $fsc->notfate_mkdir("./tmp/epgbak/$date/");
        //如果查找到over.txt，则把所有ftp文件放到epg文件夹里
        if(in_array("/over.txt",$ftp_files)){ 
            foreach($ftp_files as $file) { 
                $file = str_replace('/','',$file);  //返回的文件名数组里有 /，例如/CCTV-6.xml
                $key = iconv("gbk","utf-8",str_replace(".xml",'',$file));
                //$fileutf = iconv("gbk","utf-8",$file);
                if(array_key_exists($key,$this->chanellist)) { 
                    ftp_get($ftp_conn, "./tmp/epg/".$file, $ftp_path."/".$file, FTP_BINARY);
                }
                //备份到epgbak
                ftp_get($ftp_conn, "./tmp/epgbak/$date/".$file, $ftp_path."/".$file, FTP_BINARY);
                if($file!='over.txt'){
                    ftp_delete($ftp_conn,$ftp_path."/".$file);  //删除ftp服务器上的相关文件
                }
            }
            ftp_delete($ftp_conn,$ftp_path."/over.txt");  //删除ftp服务器上的over.txt
        }else{
            echo date("Y-m-d H:i:s"),'------',"No over.txt!\r\n";
        }
    }
    
    /**
     * 遍历一个xml，获取节目信息
     * @param void $ftp_conn
     */ 
    private function getProgramList($channelname) 
    {
        global $ftp_conn,$ftp_path,$argv;
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
                    //持续时间大于1分钟的才写入到数据库
                    $duration=intval($event['duration']);
                    if($duration>60){
                        $programs[$day][] = array("title" => $title,
                                            "starttime" => $starttime,
                                            "time" => $time,
                                            "endtime" => $endtime);
                    }

                }
            }
            return $programs;
        }
    }
    
    
    /**
     * 根据开始时间和延续时间算出结束时间
     * @param void $ftp_conn
     */ 
    private function getEndTimeByDuration($begintime,$duration) 
    {
        $durationH = intval(substr($duration,0,2));
        $durationM = intval(substr($duration,2,2));
        $durationS = intval(substr($duration,4,2));
        return date("Y-m-d H:i:s",strtotime($begintime." + " .$durationH. " hours ".$durationM. " minutes"));
    }
    
    /**
     * 对节目名称进行过滤
     * @param void $ftp_conn
     */ 
    private function getSubTitle($str)
    {
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
