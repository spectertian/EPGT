<?php
/**
 *  导出每天csv节目数据至FTP服务器epg_csv目录下(停用)
 *  @author: gaobo
 */
class programExportToCsvTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'programExportToCsv';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [programExportToCsv|INFO] task does things.
Call it with:

  [php symfony programExportToCsv|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $channel = Doctrine::getTable('Channel')->createQuery()
                    ->orderBy('id ASC')
                    ->execute();
        $mongo_program = $this->getMondongo()->getRepository('Program');
        $mongo_wiki    = $this->getMondongo()->getRepository('Wiki');
        $start_time = new MongoDate(mktime(0, 0, 0, date("m"),date("d"),date("Y")));
        $end_time   = new MongoDate(mktime(23, 59, 0, date("m"),date("d"),date("Y")));
        //$start_time = new MongoDate(mktime(0, 0, 0, 8,21,2012));
        //$end_time   = new MongoDate(mktime(23, 59, 0, 8,21,2012));
        $tempcsvTitle = '开始时间,结束时间,program名称,tag标签,wikiID,wiki名称';

        $conna = ftp_connect("110.173.3.73","1001") or die("FTP服务器连接失败");
        ftp_login($conna,"shanghai-epg","shanghai-epg021") or die("FTP服务器登陆失败");

        $dirdatename = date('Ymd',time());
        foreach($channel as $v){
            $programs = $mongo_program->find(array('query'=>array('start_time'=>array('$gte'=>$start_time),'end_time'=>array('$lte'=>$end_time),'channel_code'=>$v->getCode())));
            if($programs){
                //$filename = '/www/newepg/temp/'.$v->getCode().'.csv';
                //$fp = fopen($filename,'w');
                $filename = $v->getCode().'.csv';
                echo $filename;
                $temp = $tempcsvTitle."\n";
                foreach($programs as $val){
                    $getStartTime = date('Y-m-d H:i:s',$val->getStartTime()->getTimestamp());
                    $getEndTime = date('Y-m-d H:i:s',$val->getEndTime()->getTimestamp());
                    $getName = $val->getName();
                    
                    $temp .= $getStartTime.',';
                    $temp .= $getEndTime.',';
                    $temp .= $getName.',';
                    $temp .= implode(' ',$val->getTags());
                    $wiki = $mongo_wiki->findOne(array('query'=>array('title'=>$val->getName())));
                    if($wiki){
                        $getId = $wiki->getId();
                        $getTitle = $wiki->getTitle();
                        
                        $temp .= ','.$getId.',';
                        $temp .= $getTitle;
                    }
                    $temp .= "\n";
                }
                if($temp){
                    $temp = "\xEF\xBB\xBF".$temp; 
                    //fwrite($fp, iconv("UTF-8","GB2312//IGNORE",$temp));
                    file_put_contents($filename, @iconv("UTF-8","GBK//IGNORE",$temp));
                    ftp_pasv($conna,true);
                    @ftp_mkdir($conna,'/epg_csv/'.$dirdatename);
                    $target_file = '/epg_csv/'.$dirdatename.'/'.iconv("UTF-8","GBK//IGNORE",str_replace('-','',$v->getName())).'.csv';
                    //$target_file = '/epg_csv/'.$dirdatename.'/'.iconv("CP936","GB2312//IGNORE",'他们').'.csv';
                    echo $target_file,"\n";
                    ftp_put($conna,$target_file,$filename,FTP_ASCII);
                    @unlink($filename);
                    echo $v->getCode().'is ok!',"\n";
                }
            }
        }
    }
}
