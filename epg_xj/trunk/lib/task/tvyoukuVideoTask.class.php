<?php
/**
 * 优酷爬取视频任务
 * @author luren
 */
class tvyoukuVideoTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'youkuVideo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tvyoukuVideo|INFO] task does things.
Call it with:

  [php symfony tvyoukuVideo|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
       $channels = array(
                    'film',
                    'teleplay',
//                    'dongman',
//                    'sport',
//                    'zongyi',
//                    'yinyue',
//                    'travel',
                );
       foreach ($channels as $channel) {
           $this->crawlerYouku($channel);
       }
    }

    /**
     * 优酷视频采集
     * @param <type> $channel
     * @param <type> $pages
     */
    protected function crawlerYouku($channel) {
        $mongo = $this->getMondongo();
        $wiki_repos = $mongo->getRepository('Wiki');
        for ($page = 1; $page <= 30; $page++) {
            $list = $this->crawlerYoukuListHtml($channel, $page);
            $models = array('film', 'teleplay', 'television');
            $model = in_array($channel, $models) ? $channel : 'television';
            if (!empty($list)) {
                foreach ($list as $item) {
                    $item = preg_replace('/\s+/s', '', $item);
                    $item = substr($item, 0, 220);
                    preg_match('|<liclass="p_link"><ahref="(.*)"title="(.*)"tar.*?></a>|', $item, $matches);

                    if ($matches) {
                        $item_url = isset($matches[1]) ? $matches[1] : '';
                        $title = isset($matches[2]) ? $matches[2] : '';
                        $wiki = $wiki_repos->findOne(array('query' => array('slug' => Wiki::slugify($title), 'model' => $model)));
                        $wiki = ($wiki) ? $wiki : time().rand(10, 100);

                        switch($channel) {
                            case 'film':
                                  $filmpage = file_get_contents($item_url, false, Common::createStreamContext());
                                  $filmpageArray = explode('<div class="common">', $filmpage);
                                  if (isset($filmpageArray[1])) {
                                      $filmdiv = strstr($filmpageArray[1], '</div>', true);
                                      preg_match('|<a.*href="(.+)".*>\t*<span|', $filmdiv, $filmMatches);
                                      $url = isset($filmMatches[1]) ? $filmMatches[1] : '';
                                      if ($url){
                                          $config = $this->youkuAnalysis($filmMatches[1]);
                                          $this->saveVideo($model, $title, $url, $config, $wiki);
                                      }
                                  }
                                  unset($filmpage,$filmpageArray,$filmMatches);
                                break;
                            case 'teleplay':
                                preg_match('/id_(.+)\.html/', $item_url, $tv_url_match);
                                $VideoPlaylist = $this->saveVideoPlayList($title, $wiki, $item_url);        //存储到Teleplay表
                                if (isset($tv_url_match[1])) {                          //循环采集电视分集
                                   for($i = 1; $i < 10; $i++) {
                                       $tv_list_url = sprintf('http://www.youku.com/show_eplist/showid_%s_page_%d.html', $tv_url_match[1], $i);
                                       $list = $this->crawlerTeleplayListHtml($tv_list_url);
                                       if (empty($list)) break;
                                       foreach ($list as $tvitem) {
                                           $tvitem = preg_replace('/\s+/s', '', $tvitem);
                                           preg_match('#<spanclass="num">([\d|:]+)</span>.*href="(.*)"t.*?>(.*)</a>#s', $tvitem, $tvmatches);
                                           if (isset($tvmatches[1])) {
                                                $time = isset($tvmatches[1]) ? $tvmatches[1] : '';
                                                $url = isset($tvmatches[2]) ? $tvmatches[2] : '';
                                                $title = isset($tvmatches[3]) ? $tvmatches[3] : '';
                                                preg_match('/id_(.+)\.html/', $url, $tvmatch);
                                                if (isset($tvmatch[1])) $config['id'] = $tvmatch[1];
                                                preg_match('#^.*[^\d+](\d+)$#i', $title, $tvmark);
                                                if (isset($tvmark[1])) $mark = $tvmark[1];
                                                $this->saveVideo($model,$title, $url, $config, $wiki, (string)$VideoPlaylist->getId(), $time, $mark);
                                           }
                                       }
                                       sleep(mt_rand(10, 100));
                                   }
                                }
                                break;
                            case 'dongman':
                                break;
                            case 'sport':
                                break;
                            case 'zongyi':
                                preg_match('/id_(.+)\.html/', $item_url, $tv_url_match);
                                if (isset($tv_url_match[1])) {                          //循环采集栏目分期
                                   for($i = 1; $i < 20; $i++) {
                                       $tv_list_url = sprintf('http://www.youku.com/show_eplist/showid_%s_page_%d.html', $tv_url_match[1], $i);
                                       $list = $this->crawlerTeleplayListHtml($tv_list_url);
                                       if (empty($list)) break;
                                       foreach ($list as $tvitem) {
                                           $tvitem = preg_replace('/\s+/s', '', $tvitem);
                                           preg_match('#<spanclass="num">([\d|:]+)</span>.*href="(.*)"t.*?>(.*)</a>#s', $tvitem, $tvmatches);
                                           if (isset($tvmatches[1])) {
                                                $time = isset($tvmatches[1]) ? $tvmatches[1] : '';
                                                $url = isset($tvmatches[2]) ? $tvmatches[2] : '';
                                                $title = isset($tvmatches[3]) ? $tvmatches[3] : '';
                                                preg_match('/id_(.+)\.html/', $url, $tvmatch);
                                                if (isset($tvmatch[1])) $config['id'] = $tvmatch[1];
                                                preg_match('#^.*[^\d+](\d+)$#i', $title, $tvmark);
                                                if (isset($tvmark[1])) $mark = '20'. $tvmark[1];
                                                $this->saveVideo($model,$title, $url, $config, $wiki, false , $time, $mark);
                                           }
                                       }
                                       sleep(mt_rand(10, 100));
                                   }
                                }
                                break;
                            case 'travel':
                                break;
                        }
                    }
                }
            }
            sleep(mt_rand(10, 100));
        }
    }

    /**
     * 爬取优酷网视频列表页
     * @param <type> $channel
     * @param <type> $page
     * @return <array> 视频列表 html
     */
    private function crawlerYoukuListHtml($channel, $page) {
        $url = $list = '';
        switch ($channel) {
            case 'film' :  //电影
                $url = 'http://www.youku.com/v_olist/c_96_a__g__r__d_1_fv_0_fl__fc__fe__o_7_p_'.$page.'.html';
                break;
            case 'teleplay' : // 电视剧
                $url = 'http://www.youku.com/v_olist/c_97_a__g__r__d_1_fv_0_fl__fc__fe__o_7_p_'.$page.'.html';
                break;
            case 'dongman' : //动漫
                $url = 'http://www.youku.com/v_olist/c_100_a__g__r__d_1_fv_0_fl__fc__fe__o_7_p_'.$page.'.html';
                break;
            case 'sport' : //体育
               $url = 'http://www.youku.com/v_showlist/t2c98d1p'.$page.'.html';
                break;
            case 'zongyi' : //综艺
                $url = 'http://www.youku.com/v_olist/c_85_a__g__r__d_1_fv_0_fl__fc__fe__o_7_p_'.$page.'.html';
                break;
            case 'yinyue' : //音乐
                $url = 'http://www.youku.com/v_showlist/t2c95d1p3.html';
                break;
            case 'travel' : //旅游
                $url = 'http://www.youku.com/v_showlist/t2c88d1p3.html';
                break;
            default :
                return array();
        }

        printf("1 url: %s \n", $url);
        $html = file_get_contents($url, false, Common::createStreamContext());
        $htmlArray = explode('<div class="items">', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode('<div class="qPager">', $htmlArray[1]);
        $html = array_shift($htmlArray);
        $list = explode('</ul>', $html);
        return $list;
    }

    /**
     * 爬去电视剧分集列表页 HTML
     * @param <type> $url
     * @return <array> 分集列表 html
     */
    protected function crawlerTeleplayListHtml($url) {
        printf("3 url: %s \n", $url);
        $html = file_get_contents($url,false, Common::createStreamContext());
        $htmlArray = explode('<div class="items">', $html);
        if (!isset($htmlArray[1])) return array();
        $htmlArray = explode('<div class="qPager">', $htmlArray[1]);
        $html = array_shift($htmlArray);
        $list = explode('</ul>', $html);
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
    protected function saveVideo($model, $title, $url, $config, $wiki, $videoPlaylistId = false, $time = 0, $mark = 0) {
        $video = new Video();
        $video->setModel($model);
        $video->setTitle($title);
        $video->setUrl($url);
        $video->setConfig($config);
        $video->setReferer('youku');
        $video->setPublish(true);

        if ($videoPlaylistId)  $video->setVideoPlaylistId($videoPlaylistId);
        if ($time) $video->setTime($time);
        if ($mark > 0) $video->setMark($mark);
        if ($wiki instanceof Wiki) {
            $video->setWikiId((string) $wiki->getId());
            if ($mark > 0) {
                $mongo = $this->getMondongo();
                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
                $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' =>(int)$mark)));
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
    protected function saveVideoPlayList($title, $wiki, $url) {
        $VideoPlaylist = new VideoPlaylist();
        $VideoPlaylist->setTitle($title);
        $VideoPlaylist->setUrl($url);
        $VideoPlaylist->setReferer('youku');

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
    protected function youkuAnalysis($url) {
        $result = array();
        printf("2 url: %s \n", $url);
        $html = file_get_contents($url, false, Common::createStreamContext());
        $html = explode('</title>', $html);

        preg_match("|<title>(.+)|",$html[0], $ret);
        if (isset($ret[1])) {
            $title = explode(' - ', $ret[1]);
            $result['title'] = reset($title);
        }

        preg_match("|id_(.+)\.html|",$url, $ret);
        if (isset($ret[1]))  $result['id'] = $ret[1];

        return $result;
    }
}
