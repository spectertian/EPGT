<?php
/**
 * 豆瓣页面抓取任务
 * 
 */
class tvDoubanMovieUpdateTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')
        ));

        $this->namespace        = 'tv';
        $this->name             = 'DoubanMovieUpdate';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {    
        require "lib/vendor/simple_html_dom.php";
        $year = date("Y");
        $logtxt = "===".date("Y-m-d H:i:s")."===\n";
        $ids = array();
        $urls = array(
            "http://movie.douban.com/nowplaying/beijing/",
            "http://movie.douban.com/later/beijing/",
            "http://movie.douban.com/tv/?type=1",
            "http://movie.douban.com/tv/?type=2",
            "http://movie.douban.com/tv/?type=3",
            "http://movie.douban.com/tv/?type=4",
            "http://movie.douban.com/tv/?type=5",
            "http://movie.douban.com/tv/?type=6",
            "http://movie.douban.com/tv/?type=7",
            "http://movie.douban.com/tag/".$year."?start=0&type=R",
            "http://movie.douban.com/tag/".$year."?start=20&type=R",
            "http://movie.douban.com/tag/".$year."?start=40&type=R",
            "http://movie.douban.com/tag/".$year."?start=60&type=R",
            "http://movie.douban.com/tag/".($year+1)."?start=0&type=R",
            "http://movie.douban.com/tag/".($year+1)."?start=20&type=R",
            "http://movie.douban.com/tag/".($year+1)."?start=40&type=R",
            "http://movie.douban.com/review/latest/",
            "http://movie.douban.com/review/best/",
            "http://movie.douban.com/review/best/?start=10",
            "http://movie.douban.com/review/best/?start=20"
        );
        foreach($urls as $url) {
            $html = file_get_html($url);
            $as = $html->find("a");
            foreach($as as $a) {
                $href = $a->href;
                if(strpos($href,"douban.com/subject")) {
                    echo $href."\n";
                    $id = intval(str_replace("http://movie.douban.com/subject/","",$href));
                    if($id > 0) {
                        $ids[$id] = $this->ignore_non_utf8($a->plaintext);
                    }
                }
            }
            usleep(500);
        }
        if(count($ids) <= 0) {
            $logtxt .= "nothing add!\n\n";
            file_put_contents("./log/task_douban_moiveupdate.log", $logtxt, FILE_APPEND);
            exit;
        }
        
        $mongo = $this->getMondongo();
        $dmRep = $mongo->getRepository("DoubanMovie");            
        $wkRep = $mongo->getRepository("Wiki");
        $addnum = $findnum = 0;
        foreach($ids as $id => $title) {
            $dbmovie = $dmRep->findOne(array('query'=>array("douban_id" => $id)));
            if($dbmovie && $dbmovie->getDoubanId()) {                   
                continue;
            }
            $movie = $this->getMoiveBySubject($id);
            if(!$movie) {
                $logtxt .= $id."获取失败\n";
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
            $addnum++;
            
            echo $movie['title']."($id) 添加成功\n";
            $logtxt .= $movie['title']."($id) 添加成功\n";
            
            $wkDoc = $wkRep->getWikiByDoubanMovie($dmDoc);
            if($wkDoc) {
                $wkDoc->setDoubanId($id);
                $wkDoc->save();
                $dmDoc->setWikiId((string)$wkDoc->getId());
                $dmDoc->setSynStatus(1);
                $dmDoc->Save();
                echo $wkDoc->getTitle()." 匹配成功\n";
                $logtxt .= $wkDoc->getTitle()." 匹配成功\n";
                $findnum ++;
            }            
            sleep(2);            
        }
        if($addnum == 0) {
            echo "nothing add!\n\n"; 
            $logtxt .= "nothing add!\n";            
        }else {
            echo "总共有 $addnum 个豆瓣影视新加。其中有 $findnum 个匹配上wiki。\n";
            $logtxt .= "总共有 $addnum 个豆瓣影视新加。其中有 $findnum 个匹配上wiki。\n";
        }
        file_put_contents("./log/task_douban_moiveupdate.log", $logtxt, FILE_APPEND);
        
        $from = 'epg@huan.tv';
        $to = array(
    		'chenmiao@huan.tv' => 'chenmiao',
    		'yaomanman@huan.tv' => 'yaomanman',
    		'chenshengwen@huan.tv' => 'chenshengwen',
    	);
        $title = '豆瓣抓取任务更新记录提醒';
    	$this->getMailer()->composeAndSend($from, $to , $title, $logtxt);
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