<?php
/**
 *  导出每天JSON文本节目数据至FTP服务器epg目录下
 *  @author: gaobo
 */
class wikiDeleteToFtpForNASTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'wikiDeleteToFtpForNAS';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [wikiDeleteToFtpForNAS|INFO] task does things.
Call it with:

  [php symfony tv:wikiDeleteToFtpForNAS|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $conna = ftp_connect("ch.f.cedock.com","1001") or die("FTP服务器连接失败");
        ftp_login($conna,"usanas","2wsx@usanas") or die("FTP服务器登陆失败");
        $mongo = $this->getMondongo();
        $WikiRepository = $mongo->getRepository('WikiDelete');
        
        //FIXME
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
