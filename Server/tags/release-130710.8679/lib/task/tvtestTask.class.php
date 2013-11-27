<?php
/**
 * 测试任务
 * 
 */
class tvtestTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('code', null, sfCommandOption::PARAMETER_REQUIRED, 'what date?', ''),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'test';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $channels = Doctrine::getTable('Channel')->getYangShiAndWeiShiChannels();
        $programRep = $this->getMondongo()->getRepository('Program');
        $i = 0;
        foreach($channels as $channel) {
            //if($i >=2) break;
            $i++;
            $filename = "./tmp/epg/txt/".iconv("utf-8","gbk",$channel->getName()).".txt";
            $filecont = "";
            echo $channel->getCode()."\n";
            for($date = 0; $date < 5; $date ++) {
                $targetDate = date('Y-m-d',strtotime("+$date day"));
                echo "    ".$targetDate."\n";
                $filecont .= $targetDate."\n";
                $dayPrograms = $programRep->getDayProgramsByChannelCode($channel->getCode(), $targetDate, false);
                foreach($dayPrograms as $program) {
                    $filecont .= $program->getTime()."  ".$program->getName();
                    if($program->getWikiId()) {
                        $filecont .= "  http://www.epg.huan.tv/wiki/show/id/".$program->getWikiId();
                    }
                    $filecont .= "\n";
                }
                $filecont .= "\n";
            }
            file_put_contents($filename,iconv("utf-8","gbk",$filecont));
        }
    }
}
?>