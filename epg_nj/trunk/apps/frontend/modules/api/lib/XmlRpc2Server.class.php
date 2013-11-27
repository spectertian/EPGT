<?php
/**
 * @author ward
 * @version 1.0
 * @since 2010-08-31 14:25
 */
class XmlRpc2Server extends IXR_Server {
    
    public function __construct() {
        $android = new AndroidRpc();
        $androidtv = new AndroidTvRpc2();
        $this->methods = array(
            'sayHello' => 'this:sayHello',
            'client.livetv' =>'this:getLiveTv',
            'client.movies' =>'this:getMovies',
            'client.weekprograms' => 'this:getWeekChannelPrograms',

            'huan.getActor' => 'this:getActor',
            'huan.getFilmTV' => 'this:getFilmTV',

            "android.sayHello" => array($android, "sayHello"),
            "android.getProvinceList" => array($android, "getProvinceList"),
            'android.getHotplay' => array($android, 'getHotplay'),
            'android.getRecommend' => array($android, 'getRecommend'),
           'androidtv.sayHello' => array($androidtv, "sayHello"),
           'androidtv.getChannelList' => array($androidtv, "getChannelList"),
           'androidtv.getWeekByProvinceList' => array($androidtv, "getWeekByProvinceList"),
           'androidtv.getLiveList' => array($androidtv, "getLiveList"),
           'androidtv.getWikiAllInfo' => array($androidtv, "getWikiAllInfo"),
		   'androidtv.search' => array($androidtv, "search"),
           'androidtv.programDetail' => array($androidtv, "programDetail"),
           'androidtv.recommendVideo' => array($androidtv, "recommendVideo"),
           'androidtv.getLiveTags' => array($androidtv, 'getLiveTags'),
           'androidtv.getNowPrograms' => array($androidtv, 'getNowPrograms'),
           'androidtv.getServerTime' => array($androidtv, 'getServerTime'),
           'androidtv.getAllChannel' => array($androidtv, 'getAllChannel'),
           'androidtv.getMetasByWikiId' => array($androidtv, 'getMetasByWikiId'),
           'androidtv.postUserLiving' => array($androidtv, 'postUserLiving'),
           'androidtv.getChannelInfo' => array($androidtv, 'getChannelInfo'),
           'androidtv.getWikiInfoByChannel' => array($androidtv, 'getWikiInfoByChannel'),
        );
        $this->failed   = false;
        $this->IXR_Server($this->methods);
    }

    public function  __destruct() {
    }

    /**
     * xml-rpc 演示、测试， hello
     * @return XML
     */
    public function sayHello() {
        return 'hello';
    }

    /**
     * 获取直播电视频道列表信息
     * @param array $args
     * return array
     * @author zhigang
     */
    public function getLiveTv($args) {
        $live_channels = Doctrine::getTable("Channel")->createQuery()
                ->where("live = 1")
                ->execute();
        $live_tvs = array();
        foreach ($live_channels as $live_channel) {
            $live_tv = array();
            $live_tv['code'] = $live_channel->getCode();
            $live_tv['name'] = $live_channel->getName();
            $live_tv['logo'] = $live_channel->getLogoUrl();
            
            $config = json_decode($live_channel->getLiveConfig());
            $live_tv['config'] = $config;

            /*$programs = $live_channel->getDayPrograms(date("Y-m-d"));
            $today_programs = array();
            foreach ($programs as $program) {
                $today_program = array();
                $today_program["start_time"] = $program->getStartTime()->format("Y-m-d H:i:s");
                if ($program->getEndTime()) {
                    $today_program["end_time"] = $program->getEndTime()->format("Y-m-d H:i:s");
                }
                $today_program["name"] = $program->getName();

                $today_programs[] = $today_program;
            }
            $live_tv["programs"] = json_encode($today_programs);*/

            $live_tvs[] = $live_tv;
        }

        return $live_tvs;
    }

    /**
     * 获取可点播电影列表
     * @param <type> $args
     */
    public function getMovies($args) {
        $mongo = sfContext::getInstance()->get("mondongo");
        $video_repo = $mongo->getRepository("Video");
        $movies = $video_repo->find();
        $ret_movies = array();
        foreach ($movies as $movie) {
            $ret_movie = array();
            $ret_movie["title"] = $movie->getTitle();
            
            $wiki = $movie->getWiki();
            if ($wiki) {
            $ret_movie["cover"] = $wiki->getCoverUrl(false);
            $ret_movie["staring"] = $wiki->getStarring();
            $ret_movie["content"] = $wiki->getContent();
            $ret_movie["director"] = $wiki->getDirector(",");
            }

            $config = $movie->getConfig();
            $default = $movie->getDefault();
            if (strlen($default)) {
                $ret_movie["config"] = $config[$default];
                $ret_movie["playback"] = $default;
            } else {
                $ret_movie["config"] = $config['qiyi'];
                $ret_movie["playback"] = "qiyi";
            }

            $ret_movies[] = $ret_movie;
        }
        return $ret_movies;
    }

    /**
     * 获取艺人wiki
     * @param <array> $args page, limit
     * @return <array>
     * @author pjl
     */
    public function getActor($args) {
        $page = intval($args[0]) ? intval($args[0]) : 1;
        $limit = intval($args[1]) ? intval($args[1]) : 20;
        
        $mongo = new sfMondongoPager('Wiki', $limit);

        $query = array();
        $query['query'] = array('model' => 'actor');
        $query['sort'] = array('created_at' => -1);

        $mongo->setFindOptions($query);
        $mongo->setPage($page);
        $mongo->init();

        $actors = $mongo->getResults();

        $rets = array();
        $rets['resultcount'] = count($actors);
        $rets['recordcount'] = $mongo->count();
        $rets['pagecount'] = intval($mongo->getLastPage());
        $rets['stars'] = array();
        
        foreach($actors as $actor) {
            $ret['id'] = (string)$actor->getId();
            $ret['name'] = $actor->getTitle();
            $ret['sex'] = $actor->getSex();
            $ret['birthday'] = $actor->getBirthday();
            $ret['desc'] = $actor->getHtmlCache();
            $ret['photos'] = array($actor->getCoverUrl(false));
            
            $rets['stars'][] = $ret;
            
            unset($ret);
        }

        return $rets;
    }

    /**
     * 获取影视剧wiki
     * @param <array> $args page, limit
     * @return <array>
     * @author pjl
     */
    public function getFilmTV($args) {
        $page = intval($args[0]) ? intval($args[0]) : 1;
        $limit = intval($args[1]) ? intval($args[1]) : 20;

        $mongo = new sfMondongoPager('Wiki', $limit);

        $query = array();
        $query['query'] = array(
                '$or' => array(array('model' => 'film'), array('model' => 'teleplay')),
                'qiyi' => new MongoRegex('/.+/')
            );
        $query['sort'] = array('created_at' => -1);

        $mongo->setFindOptions($query);
        $mongo->setPage($page);
        $mongo->init();

        $results = $mongo->getResults();

        $rets = array();
        $rets['resultcount'] = count($results);
        $rets['recordcount'] = $mongo->count();
        $rets['pagecount'] = intval($mongo->getLastPage());
        $rets['programs'] = array();

        foreach($results as $result) {
            $ret['id'] = (string)$result->getId();
            $ret['title'] = $result->getTitle();
            $ret['director'] = $result->getDirector(',');
            $ret['actors'] = $result->getStarring(',');
            $ret['area'] = $result->getCountry();
            $ret['language'] = $result->getLanguage();
            $ret['performtime'] = $result->getReleased();
            $ret['company'] = $result->getDistributor(',');
            $ret['entitle'] = $result->getEname();
            $ret['alias'] = $result->getAlias(',');
            $ret['tag'] = $result->getTags(',');
            $ret['desc'] = $result->getHtmlCache();

            $ret['posters'] = array($result->getCoverUrl(false));
            $ret['photos'] = $result->getScreenshotUrls();

            //电视剧
            if($result->getModel() == 'teleplay') {
                $ret['episodecount'] = $result->getEpisodes();

                //分集
                if($result->getDrama()) {
                    $ret['episodes'] = array();
                    foreach($result->getDrama() as $key => $drama) {
                        $r['title'] = $drama['title'];
                        $r['order'] = $key;
                        $r['desc'] = $drama['content'];

                        $ret['episodes'][] = $r;
                        unset($r);
                    }
                }
            }

            $rets['programs'][] = $ret;

            unset($ret);
        }

        return $rets;
    }

    /**
     * 根据频道 channel_code 获取一星期的电视节目
     * @param <type> $args
     */
    public function getWeekChannelPrograms($args) {

        $channel_code = isset ($args['channel_code']) ? $args['channel_code'] : '';
        if (! $channel_code) {
            return "arguments error";
        }
        
        $channel = Doctrine::getTable('Channel')->createQuery()
                    ->where('code = ?', $channel_code)
                    ->fetchOne();
        
        $weekday = (date('w') == 0) ? 7 : date('w');
        $today = time();
        $result = array();
        $results = array();

        // 循环获取每天的节目单
        for ($i = 1; $i < 8; $i++) {
            $n = $i - $weekday;
            $date = date('Y-m-d', $today + $n * 86400);
            $programs = $channel->getDayPrograms($date);

            if (! empty($programs) ) {
                $item = array();
                foreach($programs as $program) {
                    $item = array(
                        'name'    => $program->getName(),
                        'date'    => $program->getDate(),
                        'channel_code' => $program->getChannelCode()
                    );
                    
                    if ($program->getWikiId()) {
                        $item['wiki_id'] = $program->getWikiId();
                    }
                }
                
                $result[] = $item;
                $results[$i] = $result;
                unset($item);
            }
        }

        return $results;
    }
}
