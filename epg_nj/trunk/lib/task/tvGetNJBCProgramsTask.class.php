<?php
/**
 *  从ftp里获取南广传的所有频道数据到program_week表
 *  @author: lifucang 2013-09-06
 */
class tvGetNJBCProgramsTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')     
        ));

        $this->namespace        = 'tv';
        $this->name             = 'GetNJBCPrograms';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:GetNJBCPrograms|INFO] task does things.
Call it with:

  [php symfony tv:GetNJBCPrograms|INFO]
EOF;

        $this->chanellist = array();
    }

    protected function execute($arguments = array(), $options = array())
    {
        global $ftp_conn,$ftp_path,$argv;	
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('GetNJBCPrograms');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $sp_res = $mongo->getRepository("SpService");
        $sqServices = $sp_res->getServicesByTag();
        foreach($sqServices as $sqService){
            $this->chanellist[$sqService->getName()]=$sqService->getChannelCode();
        }
        $ftpIp = sfConfig::get('app_commonFtp_host');
        $ftpPort = sfConfig::get('app_commonFtp_port');
        $ftpUser = sfConfig::get('app_commonFtp_username');
        $ftpPass = sfConfig::get('app_commonFtp_password');
        //连接ftp服务器
        $ftp_conn = ftp_connect($ftpIp,$ftpPort) or die("FTP服务器连接失败"); 
        ftp_login($ftp_conn ,$ftpUser,$ftpPass) or die("FTP服务器登陆失败");
        ftp_pasv($ftp_conn,TRUE);  //被动模式，否则会很慢

        //遍历ftp目录,将文件放入epg目录
        $ftp_path = iconv("utf-8","gbk","/epg");  //注意：不要后边的/
        $ftp_files = ftp_nlist($ftp_conn,$ftp_path);
        if(count($ftp_files) > 0) {
            echo date("Y-m-d H:i:s"),'------',"Start get files from FTP!\r\n";
            $this->getDirList($ftp_files);
            echo date("Y-m-d H:i:s"),'------',"End get files from FTP!\r\n";
        }else{
            echo date("Y-m-d H:i:s"),'------',"No files In FTP!\r\n";
        }
        $program_num=0; //记录保存program数
        //遍历本地tmp/epg目录
        $files=$this->getLocalDir('./tmp/epg/');
        if(count($files)==0){
            echo date("Y-m-d H:i:s"),'------',"No files In Local!\r\n";
        }else{
            //开始循环本地tmp/epg目录
            echo date("Y-m-d H:i:s"),'##############',"start!\r\n";
            foreach($files as $file) {
                echo "------$file\r\n";
                $chanelnameGbk=str_replace(".xml",'',$file);
                $chanelname = iconv("gbk","utf-8",str_replace(".xml",'',$file));
                
                $wiki_repository = $mongo->getRepository('wiki');
                if(!$this->chanellist[$chanelname]) {
                    unlink('./tmp/epg/'.$file);
                    continue;
                }
                $channelcode = $this->chanellist[$chanelname];
                //获取该频道省，市及类型开始
                $channel_type=array();
                //获取该频道省，市及类型结束
                echo date("Y-m-d H:i:s"),"To parse the XML !\r\n";
                $programdays = $this->getProgramList($chanelnameGbk);
                if(count($programdays)>0){
                    echo date("Y-m-d H:i:s"),"Begin write the Mongo !\r\n";    
                }else{
                    echo date("Y-m-d H:i:s"),"Fail parse the XML !\r\n";    
                }   
                foreach($programdays as $day => $programs) {
                    if(strtotime($day)<strtotime(date("Y-m-d"))) continue;  //小于当天的节目信息不添加
                    //if($day!='2013-09-16') continue;  //不是9月16号的不更新
                    if($programs) {
                        //小于4条节目信息的不添加
                        if(count($programs)<4) continue;
                        $program_repository = $mongo->getRepository('programWeek');
                        //判断是否有当天数据结束
                        if($channelcode!='dazhongyingyuan'){ //大众影院的不删除
                            $program_repository->removeDayPrograms($channelcode, $day);
                        }
                        foreach($programs as $prog) {
                            //大众影院的  "以播出为准" 不入库
                            if($channelcode=='dazhongyingyuan'&&$prog['title']=='以播出为准'){
                                break;
                            }
                            $program = new ProgramWeek();
                            $program->setChannelCode($channelcode);
                            $program->setName($prog['title']);
                            $program->setStartTime(new DateTime($prog['starttime']));
                            $program->setEndTime(new DateTime($prog['endtime']));
                            $program->setDate($day);
                            $program->setTime($prog['time']);
                            $program->setPublish(1);
                            //$program->setChannelType($channel_type);
                            $program->save();
                            $program_num++;
                        }
                    }                
                }
                echo date("Y-m-d H:i:s"),"End write the Mongo !\r\n"; 
                //删除该文件
                unlink('./tmp/epg/'.$file);
                sleep(2);
            }
        }
        $content="Program:".$program_num;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
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
        if(in_array("$ftp_path/over.txt",$ftp_files)){ 
            foreach($ftp_files as $file) { 
                $file = str_replace("$ftp_path/",'',$file);  //返回的文件名数组里有 /，例如/CCTV-6.xml
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
            $xmlcontent=str_replace(iconv('utf-8','gbk','郞'),iconv('utf-8','gbk','郎'),$xmlcontent);
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
