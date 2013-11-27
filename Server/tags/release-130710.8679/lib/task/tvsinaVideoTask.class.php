<?php
/**
 * 新浪爬取视频任务
 * @author luren
 */
class tvsinaVideoTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'sinaVideo';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tvsinaVideo|INFO] task does things.
Call it with:

  [php symfony tvsinaVideo|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $models = array(
                    'film',
                    'teleplay',
                    //'television'
        );

        foreach ($models as $m) {
           $this->crawlerSina($m);
        }
    }

    /**
     * 优酷视频采集
     * @param <type> $channel
     * @param <type> $pages
     */
    protected function crawlerSina($model) 
    {
        $mongo = $this->getMondongo();
        $wiki_repos = $mongo->getRepository('Wiki');

        for ($page = 1; $page <= 200; $page++) {
            $list = $this->crawlerSinaListHtml($model, $page);
            if (!empty($list)) {
                foreach ($list as $item) {
                    $title = $item->name;
                    $wiki = $wiki_repos->findOne(array('query' => array('slug' => Wiki::slugify($title), 'model' => $model)));
                    $wiki = ($wiki) ? $wiki : time().rand(10, 100);      
                    switch ($model) {
                        case 'film' :
                            $item_url = 'http://video.sina.com.cn'.$item->url;
                            $config = $this->sinaAnalysis($item_url);
                            $this->saveVideo($model, $title, $item_url, $config, $wiki);
                            break;
                        case 'teleplay' :
                            $item_url = 'http://video.sina.com.cn'.$item->detail;
                            $VideoPlaylist = $this->saveVideoPlayList($title, $wiki, $item_url);
                            $tvList = $this->crawlerTeleplayListHtml($item_url);
                            if ($tvList) {
                                foreach ($tvList as $tvitem) {
                                    $tvitem = preg_replace('/\s+/s', '', $tvitem);
                                    preg_match('|</div><ahref="(.*)"target.*?rel="(\d+)">|', $tvitem, $tvmatches);
                                    if ($tvmatches) {
                                        $url = isset($tvmatches[1]) ? 'http://video.sina.com.cn'.$tvmatches[1] : '';
                                        $tvtitle = isset($tvmatches[2]) ? $title .'第'. $tvmatches[2] .'集' : '';
                                        $mark = isset($tvmatches[2]) ? $tvmatches[2] : false;
                                        $config = $this->sinaAnalysis($url);
                                        $this->saveVideo($model, $tvtitle, $url, $config, $wiki, (string)$VideoPlaylist->getId(), $mark);
                                    }
                                }
                                unset($tvtitle);
                                sleep(mt_rand(10, 100));
                            }
                            break;
                        case 'television' :
                            $item_url = 'http://tv.video.sina.com.cn/play/'.$item->id.'.html';
                            $mark = str_replace('-', '', $item->play_date);
                            $config = $this->sinaAnalysis($item_url);
                            $this->saveVideo($model, $title, $item_url, $config, $wiki, false, $mark);
                            break;
                    }
                }
            }    
            sleep(mt_rand(10, 100));
        }
    }

    /**
     * 爬取新浪网视频列表页
     * @param <type> $channel
     * @param <type> $page
     * @return <array>
     */
    private function crawlerSinaListHtml($model, $page) 
    {
        switch ($model) {
            case 'film' :  //电影
                $url = 'http://video.sina.com.cn/interface/movie/category.php?category=movie&page='.intval($page).'&pagesize=20&liststyle=1&topid=2&leftid=movie-index&rnd=0.2318881792224784';
                break;
            case 'teleplay' : // 电视剧
                $url = 'http://video.sina.com.cn/interface/movie/category.php?category=teleplay&page='.intval($page).'&pagesize=20&liststyle=1&topid=2&leftid=teleplay-index&rnd=0.9818705167548052';
                break;
            case 'television' : // 综艺
                $url = 'http://tv.video.sina.com.cn/interface/getShowByType.php?t=9&p='.intval($page);
                break;
            default :
                return array();
        }
        
        printf("1 url: %s \n", $url);
        $josn_data = file_get_contents($url ,false, Common::createStreamContext());
        $data = json_decode($josn_data);
      
        if ('television' == $model ) {
            if ($data) {
                return $data->result;
            }
        } else {
            if (is_array($data->data)) {
                return $data->data;
            }
        }
        return array();
    }

    /**
     * 爬去电视剧分集列表页 HTML
     * @param <type> $url
     * @return <array> 分集列表 html
     */
    protected function crawlerTeleplayListHtml($url) 
    {
        printf("3 url: %s \n", $url);
        $html = file_get_contents($url, false, Common::createStreamContext());
        $htmlArray = explode('<div class="list_demand" id="T_1">', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode("<!-- 分集点播 end-->", $htmlArray[1]);
        $html = array_shift($htmlArray);
        $list = explode('</li>', $html);
        return $list;
    }

    /**
     * 视频保存
     * @param <type> $title
     * @param <type> $config
     * @param <type> $referer
     * @param Wiki $wiki
     * @param <type> $time
     * @param <type> $mark
     * @return void
     */
    protected function saveVideo($model, $title, $url, $config, $wiki, $videoPlaylistId = false, $mark = 0) 
    {
        $video = new Video();
        $video->setModel($model);
        $video->setTitle($title);
        $video->setUrl($url);
        $video->setConfig($config);
        $video->setReferer('sina');
        $video->setPublish(true);
        
        if ($videoPlaylistId)  $video->setVideoPlaylistId($videoPlaylistId);
        if ($mark > 0) $video->setMark($mark);
        if ($wiki instanceof Wiki) {
            $video->setWikiId((string) $wiki->getId());
            if ($mark > 0) {
                $mongo = $this->getMondongo();
                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
                $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => (int)$mark)));
                if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
            }
            $wiki->setHasVideo(true);
            $wiki->save();
        } else{
            $video->setWikiId($wiki);
        }

        $video->save();
        return;
    }

    /**
     * 存储一份临时电视剧视频列表
     * @param <type> $title
     * @param Wiki $wiki
     * @param <type> $url
     */
    protected function saveVideoPlayList($title, $wiki, $url) 
    {
        $VideoPlaylist = new VideoPlaylist();
        $VideoPlaylist->setTitle($title);
        $VideoPlaylist->setUrl($url);
        $VideoPlaylist->setReferer('sina');

        if ($wiki instanceof Wiki) {
            $VideoPlaylist->setWikiId((string) $wiki->getId());
        } else {
            $VideoPlaylist->setWikiId($wiki);
        }

        $VideoPlaylist->save();
        return $VideoPlaylist;
    }
    
    /**
     * 分析播放数据
     * @param <type> $url
     * @return <type>
     */
    protected function sinaAnalysis($url) 
    {
        $result = array();
        printf("2 url: %s \n", $url);
        $html = file_get_contents($url, false, Common::createStreamContext());
        $html = explode('</head>', $html);
        $html = array_shift($html);
        preg_match("|\Wvid:\'(.*?)\',|",$html, $ret);
        if (isset($ret[1]))  $result['vid'] = $ret[1];
        preg_match("|ipad_vid:\'(.*?)\',|",$html, $ret);
        if (isset($ret[1]))  $result['ipad_vid'] = $ret[1];
        return $result;
    }
}
