<?php
/**
 *  导出每天JSON文本节目数据至FTP服务器epg目录下
 *  @author: gaobo
 */
class programToFtpForNASTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'programToFtpForNAS';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [programToFtpForNAS|INFO] task does things.
Call it with:

  [php symfony tv:programToFtpForNAS|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $channel = Doctrine::getTable('Channel')->createQuery()
                    ->orderBy('id ASC')
                    ->execute();

        $mongo = $this->getMondongo();

        $conna = ftp_connect("ch.f.cedock.com","1001") or die("FTP服务器连接失败");
        ftp_login($conna,"usanas","2wsx@usanas") or die("FTP服务器登陆失败");


        $dirdatename = date('Ymd',time());
        $nodeArray = array();
        
        foreach($channel as $v){
            //$start_time = date('Y-m-d ',time()).'00:00:00';
            //$end_time   = date('Y-m-d ',time()).'23:59:59';
            $start_time = new MongoDate(mktime(0, 0, 0, date("m"),date("d"),date("Y")));
            $end_time   = new MongoDate(mktime(23, 59, 0, date("m"),date("d"),date("Y")));
            $ProgramRepository = $mongo->getRepository('Program');
            $channelcode = $v->getCode();
            $programs = $ProgramRepository->find(
                    array('query'=>array(
                            'channel_code'=>$channelcode,
                            'start_time'=>array('$gte'=>$start_time),
                            'end_time'=>array('$lte'=>$end_time)
                    )));
            
            $logo = $v->getLogo();
            if (strlen($logo) > 1) {
                $logo = Common::file_url($logo);
            } else {
                $logo = "";
            }
            $nodeArray['channel'] = array(
                    'name'=>$v->getName(),
                    'code'=>$channelcode,
            		'channel_memo'=>$v->getMemo(),
                    'logourl'=>$logo,
                    'hot'=>$v->getHot(),
            );
            
            if($programs){
                $filename = $channelcode.'.txt';
                foreach($programs as $key =>$program){
                    $wiki_info = $program->getWiki();
                    $hasVideo = ($wiki_info['has_video']>0)?'yes':'no';
                    $source = implode(',',$wiki_info['source']);
					
                    //Modify by tianzhongsheng-ex@huan.tv 2013-10-21 16:45:00 增加获取频道别名channel_memo
					$channel = $program->getChannel();
                    $nodeArray['program'][$key] = array(
                    		'program_id'=> (string)$program->getId(),
                            'name' => $program['name'],
                            'date' => $program['date'],
                            'start_time' => date("H:i",$program['start_time']->getTimestamp()),
                            'end_time' => date("H:i",$program['end_time']->getTimestamp()),
                            'wiki_id' => $program['wiki_id'],
                            'wiki_cover' => Common::file_url($wiki_info['cover']),
                            'tags' => $wiki_info['tags'],
//                            'hasvideo'=>$hasVideo,
//                            'source'=>$source,
                    );
                }
                if($nodeArray){
                    $temp = json_encode($nodeArray,true);
                    if($temp){
                        file_put_contents($filename, @iconv("UTF-8","GBK//IGNORE",$temp));
                        ftp_pasv($conna,true);
                        @ftp_mkdir($conna,'/epg/'.$dirdatename);
                        $target_file = '/epg/'.$dirdatename.'/'.iconv("UTF-8","GBK//IGNORE",$channelcode).'.txt';
                        ftp_put($conna,$target_file,$filename,FTP_ASCII);
                        @unlink($filename);
                    } 
                }
            }
        }

        //删除FTP中两周前的program
        //FIXME
        $startDate = date('Ymd',strtotime("-14 day"));
        $dirList = ftp_nlist($conna,'/epg');//获取epg下所有文件夹
        //$fileFunc = new FSC();
        foreach($dirList as $dirName){
            $rdirName = substr($dirName,5);
            //echo $dirName."-".$startDate;
            if(intval($rdirName) < intval($startDate)){
                $fileList = ftp_nlist($conna,$dirName);//获取文件夹下所有文件
                foreach($fileList as $filePath){
                    ftp_delete($conna,$filePath);
                }
                //$fileFunc->delfolder($dirName.'/');
                ftp_rmdir($conna,$dirName);
                echo $dirName." deleted\n";
            }
        }

        //合并WIKI_DELETE
        //FIXME
        $WikiRepository = $mongo->getRepository('WikiDelete');
        $starttime = strtotime(date('Y-m-d',strtotime("-1 day"))." 00:00:00");
        $endtime   = strtotime(date('Y-m-d',strtotime("-1 day"))." 23:59:59");

        $starttime = new MongoDate($starttime);
        $endtime   = new MongoDate($endtime);
        $wikis = $WikiRepository->find(array("query" => array("created_at" => array('$gte'=>$starttime,'$lte'=>$endtime))));
        $date = date('Ymd',strtotime("-1 day"));
        $str  = '';
        $filename = $date.'.txt';
        foreach ($wikis as $wiki) {
            if($wiki){
                $str .= $wiki->getWikiId()."\n";
            }
            unset($wiki);
        }
        echo $str;
        if($str){
            file_put_contents($filename, $str);
            ftp_pasv($conna,true);
            //@ftp_mkdir($conna,'/wiki_delete/');
            $target_file = '/wiki_delete/'.$filename;
            ftp_put($conna,$target_file,$filename,FTP_ASCII);
            @unlink($filename);
        }



    }

}
