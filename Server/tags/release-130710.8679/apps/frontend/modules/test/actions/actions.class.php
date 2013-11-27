<?php

/**
 * test actions.
 *
 * @package    epg
 * @subpackage test
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testActions extends sfActions 
{

    /*
     * test index
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeIndex(sfWebRequest $request) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'test_test';
        $channels = $memcache->get($memcache_key);
        if (!$channels) {
            echo "++++";
            $channels = "sssssssssssssss";
            $memcache->set($memcache_key, $channels);
        }
        echo $channels;
        exit;       
    }
    
    /*
     * test mongo
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeMongo()
    {
        $mongo = $this->getMondongo();
        $sp_repos = $mongo->getRepository('wiki');
        $sps = $sp_repos->find(array("sort" => array("update_at" => 1), "limit" => 20));
        foreach ($sps as $sp) {
            echo $sp->getId() . "\t" . $sp->getModel() . "\t" . $sp->getTitle() . "<br>\r\n";
        }
        echo "Slave is " . $mongo->getConnection("mondongo")->getMongo()->getSlave() . "<br>";
        exit;
    }

    /*
     * test cache
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeCache() 
    {
        $size = 4;
        $url = sfConfig::get("app_huan_irs_url");
        $userid = $userid ? $userid : "CH_78cfb72afedf324d9e83d1a76cef55b5746415dd";
        $key = "irs_$userid_$size";
        // $memcache = tvCache::getInstance();
        //   $wikis = $memcache->get($key);

        $content = Common::get_url_content($url . "/recommender/ItemCFRecommenderAction?userID=" . $userid . "&howMany=" . $size);
        $jsoncontent = json_decode($content, true);
        $wikis = $jsoncontent['itemIDs'];
        print_r($wikis);
        echo "aaaaaaaaaaaaaaaaaa";

        foreach ($wikis as $key => $wikiRec) {

            $wikiR[$key]['wiki_id'] = $wikiRec;
        }
        echo "bbbbbbbbbbbbbb";
        print_r($wikiR);
        $wikis = $wikiR;
        // $memcache->set($key,$wikis);

        print_r($wikis);

        die();
        $parameter = $arrs['parameter'][0]['data'][0]['__attributes__'];
        $page = $parameter['page'] ? $parameter['page'] : 1;
        $size = $parameter['size'] ? $parameter['size'] : 8;
        $tag = $parameter['tag'];
        $type = $parameter['type'];
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki');
        $wrRepo = $mongo->getRepository("WikiRecommend");
        //$parameter_user = $arrs['parameter'][0]['user'][0]['__attributes__'];
        //$userId = $parameter_user['huanid'] ? $parameter_user['huanid'] : '1234';
        $userId = '';
        $type = 2;
        switch ($type) {
            case 4:
                $wikiRecs = $wrRepo->getRandWikiBySize($size, $tag);
                $totalWikiRecs = $size;
                break;
            case 2:
                $wikiRecs = $wrRepo->getWikiByHuanIrs($userId, $size);
                $wikiRecs = $wikis;
                $totalWikiRecs = $size;
                break;
            default:
                $wikiRecs = $wrRepo->getWikiByPageAndSize($page, $size, $tag);
                $totalWikiRecs = $wrRepo->getWikiByTagNoLimit($tag);
        }

        if ($wikiRecs) {

            foreach ($wikiRecs as $key => $wikiRec) {
                print_r($wikiRec);

                $wiki = $wikiRepository->getWikiById($wikiRec['wiki_id']);
            }
        } else {
            $arr = $this->getErrArray("false", null, null, '');
        }

        die();
    }

    /*
     * test xunsearch
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeXunsearch(sfWebRequest $request) 
    {
        if ($request->isMethod(sfRequest::POST)) {
            require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
            $xs = new XS('epg_wiki');
            $limit = 200;
            $offset = 0;
            $search = $xs->search;
            $objs = $search->setQuery($request->getParameter('keyword'))
                    ->setLimit($limit, $offset)
                    ->search();

            $search->addWeight('title', $request->getParameter('keyword'));

            //$xsindex = new XSIndex();
            //print_r($xsindex->getCustomDict());exit;
            //print_r($search);exit;
            $total = $search->getLastCount();
            foreach ($objs as $obj) {
                echo "<pre>";
                //print_r($obj);
                echo $obj['id'] . "\t===\t" . $obj['title'] . "===" . $obj['source'] . "<br>";
            }
            exit;
        }
    }

    /*
     * test sqsput
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeSqsput(sfWebRequest $request) 
    {
        exit;
        $httpsqs = HttpsqsService::get();
        for ($i = 0; $i < 6; $i++) {
            $array = array("title" => "video_add" . $i,
                "action" => "video_add",
                "created_at" => time(),
                "parms" => array("type" => "film",
                    "url" => "http://www.baidu.com",
                    "wiki_id" => "12345"));
            $result = $httpsqs->put("epg_queue", json_encode($array));
        }
        return sfView::NONE;
    }

    /*
     * test sqsget
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeSqsgets(sfWebRequest $request) 
    {
        exit;
        $httpsqs = HttpsqsService::get();
        $result = $httpsqs->gets("epg_queue");
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        return sfView::NONE;
    }

    /*
     * 设置tvsou_id
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeSetWikiTvsouid(sfWebRequest $request) 
    {
        $tvsou_id = trim($request->getGetParameter('tvsouid', ''));
        $id = $request->getGetParameter('id', '');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Wiki');
        $wiki = $repository->findOneById(new MongoId($id));
        $wiki->setTvsouId($tvsou_id);
        $wiki->save();
        echo '已成功设置tvsou_id';
        return sfView::NONE;
    }

    /*
     * 得到wiki详细信息
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeGetWikiinfo(sfWebRequest $request) 
    {
        if ($request->isMethod("POST")) {
            $tvsou_id = trim($request->getParameter('tvsouid', ''));
            $title = trim($request->getParameter('title', ''));
            $slug = trim($request->getParameter('slug', ''));
            $id = $request->getParameter('id', '');

            $query_arr = array();
            if ($tvsou_id != '') {
                $query_arr['tvsou_id'] = $tvsou_id;
            }
            if ($title != '') {
                $query_arr['title'] = new MongoRegex("/.*$title.*/i");
            }
            if ($slug != '') {
                $query_arr['slug'] = new MongoRegex("/.*$slug.*/i");
            }
            if ($id != '') {
                $query_arr['_id'] = new MongoId($id);
            }
            $query = array('query' => $query_arr);
            print_r($query);
            $mongo = $this->getMondongo();
            $wikiRes = $mongo->getRepository('wiki');
            $wikis = $wikiRes->find($query);
            echo "<pre>";
            foreach ($wikis as $wiki) {
                print_r((array) $wiki);
            }
            return sfView::NONE;
        }
    }

    /*
     * 得到channel详细信息
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeGetChannel(sfWebRequest $request) 
    {
        if ($request->isMethod("POST")) {
            $code = trim($request->getParameter('code', ''));
            $name = trim($request->getParameter('name', ''));
            if ($code != '') {
                $channels = Doctrine_Query::create()
                        ->from('channel')
                        ->where('code=?', $code)
                        ->fetchArray();
            } elseif ($name != '') {
                $channels = Doctrine_Query::create()
                        ->from('channel')
                        ->where('name=?', $name)
                        ->fetchArray();
            } else {
                $channels = Doctrine_Query::create()
                        ->from('channel')
                        ->fetchArray();
            }
            echo "<pre>";
            foreach ($channels as $channel) {
                echo 'code:', $channel['code'], '<br/>';
                echo 'name:', $channel['name'], '<br/>';
                echo 'tvsou_update:', $channel['tvsou_update'], '<br/>';
                echo 'editor_update:', $channel['editor_update'], '<br/>';
                echo '###############################################', '<br/>';
            }
            return sfView::NONE;
        }
    }

    /*
     * 得到编辑记录详细信息
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeGetEditorMemory(sfWebRequest $request) 
    {
        if ($request->isMethod("POST")) {
            $program_name = trim($request->getParameter('program_name', ''));
            $channel_code = trim($request->getParameter('channel_code', ''));
            $wiki_id = trim($request->getParameter('wiki_id', ''));

            $query_arr = array();
            if ($program_name != '') {
                $query_arr['program_name'] = new MongoRegex("/.*$program_name.*/i");
                //$query_arr['$or']=array(array('program_name'=>new MongoRegex("/.*$program_name.*/i")),array('channel_code'=>$channel_code));
            }
            if ($channel_code != '') {
                $query_arr['channel_code'] = $channel_code;
            }
            if ($wiki_id != '') {
                $query_arr['wiki_id'] = $wiki_id;
            }
            $query = array('query' => $query_arr);
            $mongo = $this->getMondongo();
            $Res = $mongo->getRepository('EditorMemory');
            $lists = $Res->find($query);
            echo "<pre>";
            foreach ($lists as $list) {
                print_r((array) $list);
            }
            return sfView::NONE;
        }
    }

    /*
     * 得到tagparentid
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeGetTagParentid(sfWebRequest $request) 
    {
        $cid = trim($request->getGetParameter('cid', 1));
        echo "cid=", $cid;
        $mongo = $this->getMondongo();
        $wikiRepository = $mongo->getRepository("wiki");
        $category = $wikiRepository->getCategory();
        foreach ($category as $key => $value) {
            echo "key=", $key;
            if ($key == $cid) {
                echo $key;
            } else {
                if (array_key_exists($cid, $value['child'])) {
                    echo $key;
                } else {
                    echo "不存在";
                }
            }
        }
        return sfView::NONE;
    }

    /*
     * SetWikitemp
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeSetWikitemp(sfWebRequest $request) 
    {
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Wiki');
        //先去掉其他的tvsouid
        $wikis = $repository->find(array('query' => array('tvsou_id' => '84401')));
        foreach ($wikis as $wiki) {
            $wiki->setTvsouId(null);
            $wiki->save();
        }
        echo "完成";
        return sfView::NONE;
    }

    /*
     * 得到所有频道的logo
     *
     * @param  sfWebRequest $request
     * @author wangnan
     */
    public function executeGetChannelLogo(sfWebRequest $request) 
    {
        sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
        $channels = Doctrine::getTable("Channel")->findAll();
        echo "<table bgcolor='#999999' border=1>";
        foreach ($channels as $channel) {
            $channelLogo = $channel->getLogo();
            if ($channelLogo) {
                $channel_logoa = thumb_url($channelLogo, 75, 110);
                $channel_logo = "http://image.epg.huan.tv/2012/12/12/" . $channelLogo;
            } else {
                $channel_logo = '';
                $channel_logoa = '';
            }
            //echo "<tr><td>",$channel->getName(),"</td><td><img src='",$channel_logo,"'></img></td><td><img src='",$channel_logoa,"'></img></td></tr>";
            echo "<tr><td>", $channel->getName(), "</td><td><img src='", $channel_logo, "'></img></td></tr>";
        }
        echo "</table>";
        return sfView::NONE;
    }

}