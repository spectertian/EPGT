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
        $mongo = $this->getMondongo();
        $this->wikis=array();
        //先从tcl接口获取

        $wiki_repository = $mongo->getRepository('Wiki');
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=6&uid=123";
        //$contents=file_get_contents($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            foreach($arr_contents[3]->recommend as $value){
                $wiki_id = $value->contid_id;  
                $this->wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
            }
        }

        //获取不到从本地获取
        if(count($this->wikis)==0){
            $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
            $recommends = $wiki_recommend_repository->getRandWiki(6);  
            foreach($recommends as $recommend){
                $this->wikis[]=$recommend->getWiki();
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
                $arr_value=json_decode($rs->getValue());  //数组
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
}
