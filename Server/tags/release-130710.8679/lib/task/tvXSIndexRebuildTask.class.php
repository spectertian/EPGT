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
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
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

        $this->wikiToXS($wikiRepo,$startdate);
        //$wikiRepo->rebuildXunSearchDocument();
    }

    /**
     * 递归查找wiki 并建立xunsearch索引
     * @param $rep
     * @param $startdate
     */
    private function wikiToXS($rep,$startdate)
    {
        //global $argv;
        //$cmd = implode(" ", $argv);
        $wikis = $rep->find(array("query" => array(
            "created_at" => array('$gt' => $startdate)),
            "sort" => array("created_at" => 1),
            "limit" => 100));

        if(count($wikis) > 0){
            $i = 1;
            foreach($wikis as $wiki){
                if($i%100 == 0) $startdate = new MongoDate($wiki->getCreatedAt()->getTimestamp());
                printf("%s\n", $wiki->getTitle());
                $wiki->updateXunSearchDocument();
                $i++;
            }
            $this->wikiToXS($rep,$startdate);
        }else{
            echo "index rebuild finished!";
        }
    }

}

