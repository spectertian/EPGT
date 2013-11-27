<?php
/**
 * 豆瓣影视信息抓取任务
 * 一次性执行
 */
class tvDoubanMovieMapTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')
        ));

        $this->namespace        = 'tv';
        $this->name             = 'DoubanMovieMap';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {    
        $mongo = $this->getMondongo();
        $dmRep = $mongo->getRepository("DoubanMovie");
        $wkRep = $mongo->getRepository("Wiki");
                
        echo "正在去除wiki的trim....\n";
        $ii = 0;
        $startdate = new MongoDate(1287676800);
        while (true) {
            $wikis = $wkRep->find(array(
                "query" => array("created_at" => array('$gt' => $startdate)),
                "sort" => array("created_at" => 1),
                "limit" => 1000)
            );
            $counts = count($wikis);
            if($counts > 0){
                $i = 1;
                foreach($wikis as $wiki){   
                    $issave = 0;
                    $title = trim($wiki->getTitle());                    
                    if($title != $wiki->getTitle()) {
                        $wiki->setTitle($title);
                        $issave++; 
                    }
                    $Alias = $wiki->getAlias();
                    foreach($Alias as $key => $alia) {
                        if(trim($alia) != $alia){
                            $issave++; 
                            $Alias[$key] = trim($alia);                           
                        }
                    }
                    if($issave) {
                        echo $wiki->getTitle()."\n";
                        $wiki->setAlias($Alias);
                        $wiki->save();
                        $ii++;
                    }
                    if($i == $counts) {
                        $startdate = new MongoDate($wiki->getCreatedAt()->getTimestamp());
                    }
                    $i++;
                }
            } else {
                break;
            }            
        }
        echo "共去除$ii个。\n\n";        
        
        echo "正在编辑wiki的别名....\n";
        $query = array("title" => new MongoRegex("/（/"),
                       "alias" => array('$exists' => false));        
        $wkCol = $wkRep->find(array("query" => $query));
        $ii = 0;
        foreach($wkCol as $wkDoc) {
            $stitle = preg_replace("/（.+）/","",$wkDoc->getTitle());
            $stitle = trim($stitle);
            if(!preg_match("/([二|三|四|五|六|七])+/",$stitle)){
                echo $wkDoc->getTitle()."\t".$stitle."\n";
                $wkDoc->setAlias(array($stitle));
                $wkDoc->save();
                $ii ++;
            }
        }
        echo "共编辑了$ii个。\n\n";        
        
        echo "开始匹配豆瓣影视....\n";
        $douban_id = $ii = 0;
        while(true) {
            $dmCol = $dmRep->find(array(
                "query" => array("douban_id" => array('$gt' => $douban_id),"syn_status" => array('$exists' => true)),
                "sort" => array("douban_id" => 1),
                "limit" => 100)
            );
            $dmNum = count($dmCol);
            if($dmNum == 0) {
                break;
            }
            foreach($dmCol as $dmDoc) {
                echo $dmDoc->getTitle()."(".$dmDoc->getDoubanId().")\n";
                $douban_id = $dmDoc->getDoubanId();
                $wiki_id = $dmDoc->getWikiId();
                if($wiki_id) {
                    $wkDoc = $wkRep->getWikiById($wiki_id);
                    if($wkDoc && ($wkDoc->getDoubanId() != $douban_id)) {
                        $wkDoc->setDoubanId($douban_id);
                        $wkDoc->save();
                    }
                } else {
                    $wkDoc = $wkRep->getWikiByDoubanMovie($dmDoc);                  
                    if($wkDoc) {
                        $wkDoc->setDoubanId($douban_id);
                        $wkDoc->save();
                        $dmDoc->setWikiId((string)$wkDoc->getId()); 
                        $dmDoc->setSynStatus(1);
                        $dmDoc->Save();
                        echo $wkDoc->getTitle()."\n";
                        $ii ++;
                    }
                }
                echo "\n";
            }            
        }
        echo "共匹配了$ii个。\n";
        echo "计划任务完成。\n\n";
    }
}
?>