<?php
/**
 * 豆瓣页面抓取任务
 * 一次性执行
 */
class tvDoubanMovieListTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')
        ));

        $this->namespace        = 'tv';
        $this->name             = 'DoubanMovieList';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {    
        require "lib/vendor/simple_html_dom.php";
        //$tags = array("2013","2014");
        //foreach($tags as $tag) {
        for($tag = 2013; $tag >= 1905; $tag--) {
            $start = 0;
            while(true) {
                
                $mongo = $this->getMondongo();
                $dmRep    = $mongo->getRepository("DoubanMovie");           
                $wkRep = $mongo->getRepository("Wiki");
                
                $url = "http://movie.douban.com/tag/$tag?start=$start&type=R";
                $html = file_get_html($url);
                $trs = $html->find("table tr.item");
                
                if(!$trs) break;
                
                echo "============ $tag $start =========\n";
                foreach($trs as $tr) {
                
                    $a = $tr->find("td",1)->children(0)->children(0);
                    $id = str_replace("http://movie.douban.com/subject/","",$a->href);
                    $id = intval($id);
                    
                    $dbmovie = $dmRep->findOne(array('query'=>array("douban_id" => $id)));
                    if($dbmovie && $dbmovie->getDoubanId()) {                   
                        continue;
                    }
                    $movie = $this->getMoiveBySubject($id);
                    if(!$movie) {
                        echo $id."获取失败\n";
                        continue;
                    }
                    
                    $dmDoc = new DoubanMovie();
                    $dmDoc->setDoubanId($id);
                    $dmDoc->setTitle($movie['title']);
                    $dmDoc->setOriginalTitle($movie['original_title']);
                    $dmDoc->setAka($movie['aka']);
                    $dmDoc->setImages($movie['images']);
                    $dmDoc->setRating($movie['rating']);
                    $dmDoc->setRatingsCount($movie['ratings_count']);
                    $dmDoc->setWishCount($movie['wish_count']);
                    $dmDoc->setCollectCount($movie['collect_count']);
                    $dmDoc->setSubtype($movie['subtype']);
                    $dmDoc->setDirectors($movie['directors']);
                    $dmDoc->setCasts($movie['casts']);
                    $dmDoc->setWriters($movie['writers']);
                    $dmDoc->setMainlandPubdate($movie['mainland_pubdate']);
                    $dmDoc->setYear($movie['year']);
                    $dmDoc->setLanguages($movie['languages']);
                    $dmDoc->setDurations($movie['durations']);
                    $dmDoc->setGenres($movie['genres']);
                    $dmDoc->setCountries($movie['countries']);
                    $dmDoc->setSummary($movie['summary']);
                    $dmDoc->setCommentsCount($movie['comments_count']);
                    $dmDoc->setReviewsCount($movie['reviews_count']);
                    $dmDoc->setSeasonsCount($movie['seasons_count']);
                    $dmDoc->setCurrentSeason($movie['current_season']);
                    $dmDoc->setEpisodesCount($movie['episodes_count']);
                    $dmDoc->setPhotos($movie['photos']);
                    $dmDoc->setPopularReviews($movie['popular_reviews']);
                    $dmDoc->setSynStatus(0);
                    $dmDoc->Save();
                    echo $movie['title']."($id) 添加成功\n";
                    
                    $wkDoc = $wkRep->getWikiByDoubanMovie($dmDoc);
                    if($wkDoc) {
                        $wkDoc->setDoubanId($id);
                        $wkDoc->save();
                        $dmDoc->setWikiId((string)$wkDoc->getId());
                        $dmDoc->setSynStatus(1);
                        $dmDoc->Save();
                        echo $wkDoc->getTitle()." 匹配成功\n";
                    }
                    sleep(2);  
                }  
                echo "\n";
                file_put_contents("douban_moive.log","$tag\t$start\n",FILE_APPEND);
                $start = $start + 20;
            }
        }
        echo "finished!\n\n";
        exit;
    }
    
    protected function getMoiveBySubject($id)
    {
        require_once("lib/vendor/simple_html_dom.php");
        
        $id = intval($id);
        $url = "https://api.douban.com/v2/movie/subject/".$id;
        $html = Common::get_url_content($url);
        $movie = @json_decode($html,true);
        if($movie && isset($movie['id'])) {
            $movie['title'] = $this->ignore_non_utf8($movie['title']);
            $movie['original_title'] = $this->ignore_non_utf8($movie['original_title']);
            return $movie;    
        }else{
            return null;
        }
    }
    
    protected function ignore_non_utf8($text)
    {
        $text = htmlspecialchars_decode(htmlspecialchars($text, ENT_IGNORE, 'UTF-8'));
        $text = preg_replace('~\s+~u', ' ', $text);
        $text = preg_replace('~\p{C}+~u', '?', $text);
        return $text;
    }
}
?>