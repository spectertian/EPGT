<?php
/**
 * 搜狐爬取视频任务
 * @author luren
 */
class tvsohuVideoTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'sohuVideo';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tvsohuVideo|INFO] task does things.
Call it with:

  [php symfony tvsohuVideo|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $channels = array(
                    'film',
                    'teleplay'
                );
        foreach ($channels as $channel) {
           $this->crawlerSohu($channel);
        }
    }

    /**
     * 搜狐视频采集
     * @param <type> $channel
     * @param <type> $pages
     */
    protected function crawlerSohu($channel) 
    {
        $mongo = $this->getMondongo();
        $wiki_repos = $mongo->getRepository('Wiki');
        for ($page = 1; $page <= 200; $page++) {
            $list = $this->crawlerQiyiListHtml($channel, $page);
            $models = array('film', 'teleplay', 'television');
            $model = in_array($channel, $models) ? $channel : 'television';
            if (!empty($list)) {
                foreach ($list as $item) {
                    preg_match('|<divclass="vTxt"><h4><ahref="(.*)"t.*?>(.*)</a></h4>|', $item, $matches);
                    if ($matches) {
                        $item_url = isset($matches[1]) ? $matches[1] : '';
                        $title = isset($matches[2]) ? trim($matches[2]) : '';
                        $wiki = $wiki_repos->findOne(array('query' => array('slug' => Wiki::slugify($title), 'model' => $model)));
                        $wiki = ($wiki) ? $wiki : time().rand(10, 100);
                        
                        switch($channel) {
                            case 'film' :
                                $config = $this->sohuAnalysis($item_url);
                                $this->saveVideo($model, $title, $item_url, $config, $wiki);
                                break;
                            case 'teleplay' :
                                $VideoPlaylist = $this->saveVideoPlayList($title, $wiki, $item_url);
                                $tvList = $this->crawlerTeleplayListHtml($item_url);
                                $mark = 1;
                                foreach ($tvList as $tvitem) {
                                    preg_match('|<span><a target=_blank href= \'(.*)\' >(.*)</a>|', $tvitem, $tvmatches);
                                    if ($tvmatches) {
                                        $url = isset($tvmatches[1]) ? $tvmatches[1] : '';
                                        $title = isset($tvmatches[2]) ? trim($tvmatches[2]) : '';
                                        $config =  $this->sohuAnalysis($url);
                                        $this->saveVideo($model, $title, $url, $config, $wiki, (string)$VideoPlaylist->getId(), $mark);
                                    }
                                    $mark++;
                                }
                                sleep(mt_rand(10, 100));
                                break;
                            case 'zongyi' :
                                preg_match('#<em>([\d|\-]+).*</em>#i', $item, $matches);
                                $mark = isset($matches[1]) ? str_replace('-', '', $matches[1]) : date('Ymd', time());
                                $config = $this->sohuAnalysis($item_url);
                                $this->saveVideo($model, $title, $item_url, $config, $wiki, false, $mark);
                                break;
                        }
                    }
                }
            }
            sleep(mt_rand(10, 100));
        }
    }

    /**
     * 爬取搜狐网视频列表页
     * @param <type> $channel
     * @param <type> $page
     * @return <array> 视频列表 html
     */
    private function crawlerQiyiListHtml($channel, $page) 
    {
        $url = $list = '';
        switch ($channel) {
            case 'film' :  //电影
                $url = 'http://so.tv.sohu.com/list_p11_p2_p3_p4-1_p5_p6_p70_p80_p9-1_p10'.$page.'_p11.html';
                break;
            case 'teleplay' : // 电视剧
                $url = 'http://so.tv.sohu.com/list_p12_p2_p3_p4-1_p5_p6_p70_p80_p9-1_p10'.$page.'_p11.html';
                break;
            case 'dongman' : //动漫
                $url = 'http://so.tv.sohu.com/list_p116_p2_p3_p4-1_p5_p6_p70_p80_p9-2_p102_p1'.$page.'.html';
                break;
            case 'jilu' : //纪录片
                $url = 'http://so.tv.sohu.com/list_p18_p2_p3_p4-1_p5_p6_p70_p80_p9-2_p102_p1'.$page.'.html';
                break;
            case 'zongyi' : //综艺
                $url = 'http://so.tv.sohu.com/list_p17_p2_p3_p4-1_p5_p6_p70_p80_p9-2_p10'.$page.'_p11.html';
                break;
            case 'yinyue' : //音乐
                $url = 'http://so.tv.sohu.com/list_p124_p2_p3_p4-1_p5_p6_p70_p80_p9-2_p102_p1'.$page.'.html';
                break;
            case 'jiaoyu' : //教育
                $url = 'http://so.tv.sohu.com/list_p121_p2_p3_p4-1_p5_p6_p70_p80_p9-2_p102_p1'.$page.'.html';
                break;
            default :
                return array();
        }

        printf("1 url: %s \n", $url);
        $html = file_get_contents($url, false, Common::createStreamContext());
        $htmlArray = explode('<div class="jsonPP clear" id="videoData">', $html);
        if (!isset($htmlArray[1])) return array();
        $htmlArray = explode(' <div class="jumpB clear">', $htmlArray[1]);
        $html = array_shift($htmlArray);
        $html = iconv('GB18030', 'UTF-8//IGNORE', $html);
        $html = preg_replace('/\s+/s', '', $html);
        $divDataArray = explode('<divclass="vDataclear">', $html);
        $divArray = array();
        foreach ($divDataArray as $divData) {
            $divArray = explode('<divclass="vInfo">', $divData);
            foreach ($divArray as $div) {
                if (!empty($div)) {
                    $list[] = $div;
                }
            }
            $divArray = array();
        }
        array_pop($list);
        return $list;
    }

    /**
     * 爬去电视剧分集列表页 HTML
     * @param <type> $url
     * @return <array> 分集列表 html
     */
    protected function crawlerTeleplayListHtml($url) 
    {
        printf("3 url: %s \n", $url);
        $html = file_get_contents($url,false, Common::createStreamContext());
        $htmlArray = explode('<div id="similarLists"', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode("<!--for(;nowpage<=count;nowpage++)", $htmlArray[1]);
        $html = array_shift($htmlArray);
        $html = iconv('GB18030', 'UTF-8//IGNORE', $html);
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
        $video->setReferer('sohu');
        $video->setPublish(true);
        
        if ($videoPlaylistId)  $video->setVideoPlaylistId($videoPlaylistId);
        if ($mark > 0) $video->setMark($mark);
        if ($wiki instanceof Wiki) {
            $video->setWikiId((string) $wiki->getId());
            if ($mark > 0) {
                $mongo = $this->getMondongo();
                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
                $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' =>(int) $mark)));
                if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
            }
            $wiki->setHasVideo(true);
            $wiki->save();
        } else{
            $video->setWikiId($wiki);
        }
        $video->save();
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
        $VideoPlaylist->setReferer('sohu');

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
    protected function sohuAnalysis($url) 
    {
        $result = array();
        printf("2 url: %s \n", $url);
        $html = file_get_contents($url, false, Common::createStreamContext());
        $html = explode('<body>', $html);
        $html = iconv('GB18030', 'UTF-8//IGNORE', $html[0]);
        preg_match("|\Wvid=\"(.*?)\";|",$html, $ret);
        if (isset($ret[1]))  $result['vid'] = $ret[1];
        return $result;
    }
}
