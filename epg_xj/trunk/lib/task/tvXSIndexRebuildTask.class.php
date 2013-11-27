<?php
/**
 * 重建Xunsearch wiki索引
 * @author mj
 */
class tvXSIndexRebuildTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'XSIndexRebuild';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:XSIndexRebuild|INFO] task does things.
Call it with:

  [php symfony tv:XSIndexRebuild|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $wikiRepo = $mongo->getRepository("Wiki");
        $startdate = new MongoDate(1287676800);
        $starttime = time();
        
        while (true) {
            $wikis = $wikiRepo->find(array(
                "query" => array("created_at" => array('$gt' => $startdate)),
                "sort" => array("created_at" => 1),
                "limit" => 100)
            );
            $counts = count($wikis);
            if($counts > 0){
                $i = 1;
                foreach($wikis as $wiki){                    
                    printf("%s\n", $wiki->getTitle());
                    $wiki->updateXunSearchDocument();
                    if($i == $counts) {
                        $startdate = new MongoDate($wiki->getCreatedAt()->getTimestamp());
                    }
                    $i++;
                }
            } else {
                break;
            }            
        }
        
        $usetime = time() - $starttime;
        echo "重建完成，本次重建共用时 $usetime 秒！";
    }
}

