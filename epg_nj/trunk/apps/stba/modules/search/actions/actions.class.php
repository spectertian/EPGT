<?php
/**
 * search actions.
 * @package    epg2.0
 * @subpackage list
 * @author     Huan lifucang
 * @version    1.0
 */
class searchActions extends sfActions
{
    /**
    * Executes index action
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $this->wikis=array();
        
	    $cardId = $request->getParameter("cardId","8250102372401749");  //智能卡号
        $stbId  = $request->getParameter("stbId","99766609340071223");   //机顶盒号
        $backurl = 'http://'.$request->getHost().'/search';
        $interface=sfConfig::get('app_recommend_vodWho');
        $this->search_url=sfConfig::get('app_search_url');
        //运营中心
        if(($cardId == '8250102372401749') || ($cardId == '8250102886999246')){
            $stbId = "99766609340071223";
        }
        $memcache = tvCache::getInstance(); 
        $this->wikis=$memcache->get("search_wikis_$interface");
        $this->refer=$interface; 
        if(!$this->wikis){
            switch($interface){
                case 'tcl':     
                    $this->wikis=$this->getTclVodPrograms($cardId, 6, '', $backurl);
                    break;
                case 'center':
                    $this->wikis = $this->getCenterVodPrograms($stbId, 6, '', $backurl);
                    break;
                case 'tongzhou':
                    $this->wikis = $this->getTongzhouVodPrograms($cardId, 6, '', $backurl);
                    break;
            }
            $memcache->set("search_wikis_$interface",$this->wikis,3600);  //1小时
            //获取不到从本地获取
            if(count($this->wikis)==0||!$this->wikis){
                $this->wikis=$this->getLocationVodPrograms($cardId,6,'',$backurl);     
                $this->refer='local';  
            }  
        }
    }
    /**
    * 搜索列表页
    * @param sfRequest $request A request object
    */
    public function executeList(sfWebRequest $request)
    {
        $this->page = $request->getParameter("page", 1);
        $this->q = $request->getParameter("q");
        if($this->q==''||!$this->q){
            $this->redirect("search/index");
        }
        $this->sort = $request->getParameter("sort",1);
        $this->gcurrent = $request->getParameter("gcurrent","0");
        $this->getResponse()->setTitle($this->q.' - 搜索');
        $mogo = $this->getMondongo();
        $wiki = $mogo->getRepository("wiki");
        $this->type = array('0'=>'','1'=>"type:video",'2'=>"type:television",'3'=>"type:actor");
        /*
        $this->wikimodel = array('actor'=>"艺人","television"=>"栏目","film"=>"电影","teleplay"=>"电视剧");
        $this->count=array();
        $this->searchGroup = array();
        foreach($this->type as $key =>$value){
            $this->count[$key] = $wiki->getXunSearchCount($this->q." ".$this->type[$key]);
        }
        */
        $this->wiki_pager = new XapianPager("Wiki", 14);
        $this->wiki_pager->setSearchText($this->q." ".$this->type[$this->gcurrent]);
        $this->wiki_pager->setSort((int)$this->sort);
        $this->wiki_pager->setPage($this->page);
        $this->wiki_pager->init();
    }    
    /**
     * 节目相关tag
     */
    public function executeTags(sfWebRequest $request) 
    {
        
        $keyWord = $request->getParameter("keyword");
        if($keyWord!=''&&$keyWord!=null){
            $mongo = sfContext::getInstance()->getMondongo();
            $arr = array();
            $wikiRep = $mongo->getRepository('wiki');
            $wiki=$wikiRep->getWikiBySlug($keyWord);
            
            $tags=$wiki->getTags() ? implode(',', $wiki->getTags()):'';
            $directors=$wiki->getDirector()?implode(',', $wiki->getDirector()):'';
            $actors=$wiki->getStarring()?implode(',', $wiki->getStarring()):'';
            
            $mytags=$tags.','.$directors.','.$actors;
            $arr_tag=array();
            $arr_tag=explode(',',$mytags);
            $arr_tag=array_filter($arr_tag);  //去除空元素
            if (count($arr_tag) > 1) {
                shuffle($arr_tag);                     //打乱标签
                $arr_tag = array_slice($arr_tag, 0, 6);  //取两个相关的标签
            }  
            //print_r($arr_tag);
            return $this->renderPartial('tags', array('tags'=>$arr_tag)); 
        }else{
            return 0;
        }
        
    }  
    /**
     * 热门搜索
     * 根据当前节目获取热门搜索
     */
    public function executeSearchSuggest(sfWebRequest $request) 
    {
        //$keyWord = $request->getParameter("keyword");   不进行keyword判断了
        $keyWord = NULL;
        $mongo = sfContext::getInstance()->getMondongo();
        $arr = array();
        if(empty($keyWord)){
            $setting = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => 'hotsearchkey' ));
            $rs = $setting->findOne($query);
            if($rs){
                $arr_value=explode(',',$rs->getValue());  //数组
                $total = count($arr_value);
                $i = 0;
                if($total<9){
                    foreach($arr_value as $value) 
                    {
                        $arr[] = $value;
                    }   
                }else{
                    $arr_use=array_rand($arr_value,9);
                    foreach($arr_use as $value) 
                    {
                        $arr[] = $arr_value[$value];
                    }    
                }
            }
        }else{
            $wikiRep = $mongo->getRepository('wiki');
            $tags=$wikiRep->getTagBySlug($keyWord);
            $tags= array_splice($tags, 1);  //删除第一个元素，也就是把“电视剧”，“电影”等删除
            shuffle($tags);  //按随机顺序重新排列
            $result = $wikiRep->xun_search("tag:".$tags[0], $total, 0, 9,null,1);
            $total = count($result);
            if($result){
                foreach($result as $res) 
                {
                    $arr[] = $res->getTitle();
                }
            } 
        }
        return $this->renderPartial('searchSuggest', array('searchHot'=>$arr)); 
    } 
    /**
     * 获取Tcl的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTclVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository("Wiki");
        $user_id = substr($user_id,0,strlen($user_id)-1);
        $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=".$count."&uid=".$user_id."&backurl=".$backurl;
        $contents = Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            foreach($arr_contents->recommend as $value){
                $wiki_id = $value->contid_id;  
                $wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
            }
        }
        return $wikis;
    }
    /**
     * 获取运营中心的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getCenterVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $user_id = $user_id ? $user_id."_0" : "99586611250057372_0";
        $filter  = $type ? urlencode("genre='".$type."'") : "";
        $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&lang=zh&urltype=1&alg=CF&uid='.$user_id.'&filter='.$filter.'&backurl='.$backurl;
        $recomTxt = Common::get_url_content($recomUrl, 2);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson)
                $wikis = $recomJson['recommend'];
        }
        return $wikis;
    }
    
    /**
     * 获取技术部同洲厂家的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTongzhouVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        if($type!=''){
            //按标签推荐
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&genre='.$type.'&backurl='.$backurl;
        }else{
            //个性化推荐
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&backurl='.$backurl;
        }
        $recomTxt = Common::get_url_content($recomUrl);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson){
                $wikis = $recomJson['recommend'][0]['recommand'];    
            }
        }
        return $wikis;
    }
    /**
     * 获取本地点播节目
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */ 
    protected function getLocationVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $memcache = tvCache::getInstance(); 
        $wikis=$memcache->get("search_wikis_local");
        if(!$wikis){
            $mongo = $this->getMondongo();
            $wrRepo = $mongo->getRepository("WikiRecommend");
            $wikiRecommends = $wrRepo->getWikiByPageAndSize(1,$count,$type); 
            foreach($wikiRecommends as $recommend){
                $wikis[]=$recommend->getWiki();
            }
            $memcache->set("search_wikis_local",$wikis,3600*2);  //2小时
        }
        return $wikis;
    }     
}
