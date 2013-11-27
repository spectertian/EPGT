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
                    'logourl'=>$logo,
                    'hot'=>$v->getHot(),
            );
            
            if($programs){
                $filename = $channelcode.'.txt';
                foreach($programs as $key =>$program){
                    $wiki_info = $program->getWiki();
                    $hasVideo = ($wiki_info['has_video']>0)?'yes':'no';
                    $source = implode(',',$wiki_info['source']);
                    $nodeArray['program'][$key] = array(
                            'name' => $program['name'],
                            'date' => $program['date'],
                            'start_time' => date("H:i",$program['start_time']->getTimestamp()),
                            'end_time' => date("H:i",$program['end_time']->getTimestamp()),
                            'wiki_id' => $program['wiki_id'],
                            'wiki_cover' => Common::file_url($wiki_info['cover']),
                            'tags' => $wiki_info['tags'],
                            'hasvideo'=>$hasVideo,
                            'source'=>$source,
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
    }
}
