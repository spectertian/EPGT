<?php

class wikiComponents extends sfComponents {
    public function executeAdd_news( sfWebRequest $request ) 
    {
        $this->model = array(
          'teleplay'=>'电视剧',
          'film'=>'电影',
          'television'=>'栏目',
          'actor'=>'艺人',
          'footerball_player'=>'足球队员',
          'basketball_player'=>'篮球队员',
          'footerball_team'=>'足球队',
          'basketball_team'=>'篮球队',
          'nba_team'=>'NBA'
        );
        $mongo = $this->getMondongo();
        $wiki_mongo = $mongo->getRepository('Wiki');
        $this->wikis = $wiki_mongo->getNewsWiki();
    }

    /**
     * 维基页面侧边栏热播
     * @param sfWebRequest $request
     */
    public function executeHot_broadcast(sfWebRequest $request) 
    {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
        $this->wiki_slug = $request->getParameter('slug');
        $wiki = $wiki_repository->getWikiBySlug($this->wiki_slug);
        switch ($wiki->getModel()) {
            case 'teleplay':
              $this->model = "电视剧热播榜";
              break;
            case 'film':
              $this->model = "电影热播榜";
              break;
            case 'television':
              $this->model = "综艺热播榜";
              break;
        }

        $this->hotBroadcast = $wikiRecommendRepo->getWikiByModel($wiki->getModel(), 10);
    }

    /**
     * 维基页面侧边栏 相关影片数据
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeRelated_movies(sfWebRequest $request) 
    {
        $this->movies = array();
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $slug = $request->getParameter('slug');
        $wiki = $wiki_repository->getWikiBySlug($slug);
        $interface='tcl';  //默认接口是tcl
        //从tcl接口获取
        if($interface=='tcl'){
            $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.corelation.v1&ctype=vod&count=4&uid=123&cid=".(string)$wiki->getId();
            $contents=Common::get_url_content($url);
            if($contents){
                $arr_contents=json_decode($contents);
                foreach($arr_contents->recommend as $value){
                    $wiki_id = $value->contid_id;  
                    $this->movies[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
                }
            }    
            $this->refer='tcl';  
        }    
        //从运营中心获取
        if($interface=='center'){
            $memcache = tvCache::getInstance();
            $this->movies = $memcache->get("index_recomwikis");
            if(!$this->movies) {
                $recomUrl = 'http://172.20.224.146:9090/ie/interface?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&ctype=vod&count=10&uid=99766609340071223&lang=zh&rtype=recommend.keyword.v1&cid=10812415&urltype=1&backurl=http://'.$request->getHost().'/';
                $recomTxt = Common::get_url_content($recomUrl);
                if($recomTxt){
                    $recomJson = json_decode($recomTxt,true);
                    if($recomJson)
                        $this->movies = $recomJson['recommend'][0]['recommend'];
                        $memcache->set("index_recomwikis",$this->movies,3600);
                }
            }
            $this->refer='center';  
        }
        //获取不到从本地获取
        if(count($this->movies)==0||!$this->movies){
            $tags = $wiki->getTags();
            if (!empty($tags)) {
                if (count($tags) > 1) {
                    array_shift($tags);
                    shuffle($tags);                     //打乱标签
                    $tags = array_slice($tags, 0, 2);  //取三个相关的标签
                }
                $this->movies = $wiki_repository->getWikiByTagsGd($wiki, $tags, 4);
            } 
            $this->refer='local';  
        }
    }
}
?>
