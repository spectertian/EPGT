<?php
/**
 * 临时测试豆瓣和维基的匹配结果
 * @author lifucang
 * @date 2013-11-18
 */
class tempDoubanMovieToWikiTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')
        ));

        $this->namespace        = 'temp';
        $this->name             = 'DoubanMovieToWiki';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {    
        $mongo = $this->getMondongo();
        $dmRep = $mongo->getRepository("DoubanMovie");
        $wkRep = $mongo->getRepository("Wiki");
        $douban_id = 0;
        $wikiNum = 0;
        while(1) {
            $dmCol = $dmRep->find(array(
                "query" => array("douban_id" => array('$gt' => $douban_id)),
                "sort" => array("douban_id" => 1),
                "limit" => 100)
            );
            $dmNum = count($dmCol);
            if($dmNum == 0) {
                break;
            }
            foreach($dmCol as $dmDoc) {
                echo iconv('utf-8','gbk',$dmDoc->getTitle())." ($douban_id) \n";
                $wkDoc = $wkRep->getWikiByDoubanMovie($dmDoc);
                if($wkDoc){
                    $wikiNum++;
                }
                $douban_id = $dmDoc->getDoubanId();
            }
            echo 'wiki is match num:',$wikiNum,"\n";
        }
        
        echo "finished.\n";
    }
}
?>