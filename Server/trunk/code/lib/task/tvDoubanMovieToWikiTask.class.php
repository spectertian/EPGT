<?php
/**
 * 从 豆瓣影视 创建 wiki
 * 
 */
class tvDoubanMovieToWikiTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')
        ));

        $this->namespace        = 'tv';
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
        
        while(1) {
            $dmCol = $dmRep->find(array(
                "query" => array("douban_id" => array('$gt' => $douban_id),"wiki_id" => array('$exists' => false)),
                "sort" => array("douban_id" => 1),
                "limit" => 100)
            );
            $dmNum = count($dmCol);
            if($dmNum == 0) {
                break;
            }
            foreach($dmCol as $dmDoc) {
                $douban_id = $dmDoc->getDoubanId();
                echo $dmDoc->getTitle()." ($douban_id) \n";
                $douban_id = $dmDoc->getDoubanId();
                $model = ($dmDoc->getSubtype() == "movie") ? "film" : "teleplay";
                    
                $wiki = $wkRep->factory($model);                
                $wiki->setModel($model);
                $wiki->setTitle($dmDoc->getTitle());
                $wiki->setContent($dmDoc->getSummary());
                //$wiki->setTags('');
                //$wiki->setEname();
                $wiki->setAlias(implode(",",$dmDoc->getAka()));
                $wiki->setDirector($this->coverString($dmDoc->getDirectors()));
                $wiki->setWriter($this->coverString($dmDoc->getWriters()));
                $wiki->setStarring($this->coverString($dmDoc->getCasts()));
                $wiki->setProduced($dmDoc->getMainlandPubdate());
                $wiki->setReleased($dmDoc->getYear());
                $wiki->setLanguage(implode(",",$dmDoc->getLanguages()));
                $wiki->setCountry(implode(",",$dmDoc->getCountries()));
                $wiki->setDoubanId($douban_id);  

                $images = $dmDoc->getImages();
                if($images && isset($images['large'])) {
                    $content = Common::get_url_content($images['large']);
                    if(strlen($content) > 1024) {
                        $filename = Common::get_filename_rand($images['large']);
                        file_put_contents("./tmp/".$filename, $content);
                        usleep(500);
                        //$storage = StorageService::get('photo');
                        //$storage->save($filename,"./tmp/".$filename); 
                        $wiki->setCover($filename);
                    }
                }                
                $wiki->save();
                
                $wiki_id = (string)$wiki->getId(); 
                $dmDoc->setWikiId($wiki_id);
                $dmDoc->save();
                exit;                
            }
        }
        echo "finished.\n";
    }
    
    protected function coverString($array) {
        $result = array();
        if(is_array($array)) {
            foreach($array as $a) {
                if(isset($a['name'])) {
                   $result[] =  $a['name'];
                }
            }
        }
        return $result;
    }
}
?>