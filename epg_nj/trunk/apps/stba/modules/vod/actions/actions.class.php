<?php
/**
 * program actions.
 * @package    epg2.0
 * @subpackage program
 * @author     lifucang gaobo
 */
class vodActions extends sfActions
{
    public static $types = array("电影","电视剧","其他");
    public static $areas = array(
                                "全部"=>array("大陆","香港","台湾","日本","韩国","美国","其他"),
                                "电影"=>array("大陆","香港","台湾","日本","韩国","美国","其他"),
                                "电视剧"=>array("大陆","香港","台湾","日本","韩国","美国","其他"),
                                "其他"=>array("体育","娱乐","少儿","科教","综合"),
                           );
    public static $tags  = array(
                                "全部"=>array("喜剧","爱情","古装","家庭","剧情","悬疑","谍战","动作","战争","恐怖","动画","其他"),
                                "电影"=>array("喜剧","爱情","古装","家庭","剧情","悬疑","谍战","动作","战争","恐怖","动画","其他"),
                                "电视剧"=>array("喜剧","爱情","古装","家庭","剧情","悬疑","谍战","动作","战争","恐怖","动画","其他"),
                                "其他"=>array("竞技","访谈","综艺","益智","亲子","生活","新闻","人文","法制","纪录","旅游","其他")
                           );
    
    /**
     * 不用迅搜，就是底部从$query的写法变了一些，其它和使用迅搜的基本一样，indexSuccess也不用变
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $searchType = $request->getParameter("type", '全部');
        $searchArea = $request->getParameter("area", '全部');
        $searchTag = $request->getParameter("tag", '全部');
        $this->types = self::$types;
        $this->areas = self::$areas[$searchType];
        $this->wikiTagsRepons  = self::$tags[$searchType];
        
        $this->searchCondition = array("type" => '','area' => '','tag'  => '','tag1'=>'');
        foreach($this->searchCondition as $key => $condition){
            if($request->getParameter($key)){
                $this->searchCondition[$key] = $request->getParameter($key);
            }else{
                $this->searchCondition[$key] = "全部";
            }
        }
        
        $this->pagechange = $request->getParameter("pagechange",0);  //为了控制初始化时的焦点
        $this->name = $request->getParameter("name");                //为了控制初始化时的焦点
        
        if($this->name=='type'){
            $this->searchCondition['area'] = '全部';
            $this->searchCondition['tag']  = '全部';
        }        

        $mogo = $this->getMondongo();
        //为了控制初始化时的焦点
        $this->page = $request->getParameter("page", 1); //分页
        $this->sort = $request->getParameter("sort",4);  //排序字段   默认按照更新时间
        //从这开始和使用迅搜的程序有了变化
        $query = array();
        $query['has_video'] = array('$gt'=>0);
        $model=array('电影'=>'film','电视剧'=>'teleplay','其他'=>'television');
        $areas=array('大陆'=>'中国大陆','香港'=>'香港','台湾'=>'台湾','日本'=>'日本','韩国'=>'韩国','美国'=>'美国','其他'=>'其他');

        //构建搜索数组
        if($searchType!='全部'){
            $query['model'] = $model[$searchType];
        }
        if($searchArea!='全部'){
            if($searchType == '其他'){
                $query['tags'] = $searchArea;
            }else{
                if($searchArea=='其他'){
                    $query['country'] = array('$nin'=>array("大陆","中国大陆","香港","台湾","日本","韩国","美国"));
                }else{
                    $query['country'] = $areas[$searchArea];
                }
            }  
        }
        if($searchTag!='全部'){
            if($searchTag=='其他'){
                if($searchType == '其他'){
                    $query['tags'] = array('$nin'=>array("竞技","访谈","综艺","益智","亲子","生活","新闻","人文","法制","纪录","旅游"));
                }else{
                    $query['tags'] = array('$nin'=>array("喜剧","爱情","古装","家庭","剧情","悬疑","谍战","动作","战争","恐怖","动画"));
                }
            }else{
                $query['tags'] = $searchTag;
            }
        }
        if($searchType=='全部'&&$searchArea=='全部'&&$searchTag=='全部'){
            $memcache = tvCache::getInstance(); 
            //$mem_key="vod_".$model[$searchType].'_'.$this->page;
            $mem_key="vod_".$this->page;
            $this->wiki_pager=$memcache->get($mem_key);
        }
        if(!$this->wiki_pager){
            $this->wiki_pager = new sfMondongoPager('Wiki', 14);
            $this->wiki_pager->setFindOptions(array('query'=>$query,'sort' => array('video_update' => -1)));
            $this->wiki_pager->setPage($request->getParameter('page', $this->page));
            $this->wiki_pager->init();
        }   
        if($searchType=='全部'&&$searchArea=='全部'&&$searchTag=='全部'){
            $memcache->set($mem_key,$this->wiki_pager,120);  //2分钟
        }
    }
    /**
     * 使用迅搜
     * @param sfRequest $request A request object
     */
    public function executeIndexBak(sfWebRequest $request)
    {
      
        $this->types = self::$types;
        //$this->areas = self::$areas;
        $this->tags  = self::$tags;
        
        $this->searchCondition = array("type" => '','area' => '','tag'  => '','tag1'=>'');
        foreach($this->searchCondition as $key => $condition){
            if($request->getParameter($key)){
                $this->searchCondition[$key] = $request->getParameter($key);
            }else{
                $this->searchCondition[$key] = "全部";
            }
        }
        
        if($this->searchCondition['type'] == "全部"){
            $this->type = $this->types[0];
            $this->searchCondition["type"] = $this->types[0];
        }else{
            $this->type = $this->searchCondition['type'];
        }
        
        $this->areas = self::getValues('areas',$this->type);
        $tags = self::getValues('tags',$this->type);
        
        $this->pagechange = $request->getParameter("pagechange",0);  //为了控制初始化时的焦点
        $this->name = $request->getParameter("name");                //为了控制初始化时的焦点

        //$lasttype = $request->getParameter('lasttype');//词参数为上一次type值，为了刷新areas和tag
        //if($lasttype!=$this->searchCondition['type']){
        if($this->name=='type'){
            $this->searchCondition['area'] = '全部';
            $this->searchCondition['tag']  = '全部';
            $this->searchCondition['tag1'] = '全部';
        }        

        $mogo = $this->getMondongo();
        $wiki = $mogo->getRepository("wiki");
        if($this->searchCondition['type'] == "栏目"){
            $this->searchCondition['tag1'] = $this->searchCondition['area'];
            $areatemp = $this->searchCondition['area']; //栏目不为TAG标签，将第二层TAG寄存
            unset($this->searchCondition['area']);      //释放第二层无用TAG，拼接搜索条件
            $this->queryStr = $this->getSearchText(array_filter($this->searchCondition));
            $this->searchCondition['area'] = $areatemp; 
        }else{
            $this->queryStr = $this->getSearchText(array_filter($this->searchCondition));
        }
        //为了控制初始化时的焦点
        
        $this->page = $request->getParameter("page", 1); //分页
        $this->sort = $request->getParameter("sort",4);  //排序字段   默认按照更新时间
        /*
        if($tags){
            $this->wikiTagsRepons = array("全部") + $tags;
        }else{
            $this->wikiTagsRepons = array("全部");
        } 
        */ 
        if($tags){
            $this->wikiTagsRepons = $tags;
        }else{
            $this->wikiTagsRepons = array();
        }  
        $this->queryStr.=" (hasvideo:1)";
        $this->wiki_pager = new XapianPager("Wiki", 14);
        $this->wiki_pager->setSearchText($this->queryStr);
        $this->wiki_pager->setSort($this->sort);
        $this->wiki_pager->setPage($this->page);
        $this->wiki_pager->init();
    }    
    /*
     * 根据类型和值获取相应标签组
     * @param string $type 'areas'|'tags'
     * @param string $name
     */
    private function getValues($type='',$name)
    {
        if($type == 'areas'){
            $arrtemp = array_keys(self::$types,$name);
            return self::$areas[$arrtemp[0]];
        }elseif ($type == 'tags'){
            $arrtemp = array_keys(self::$types,$name);
            return self::$tags[$arrtemp[0]];
        }
    }

    /*
     * 获取搜索文本
     * @param <array> $array
     * @return <string> $queryStr
     * @author:guoqiang.zhang
     */
    function getSearchText($array)
    {
        $tempstr = " ";
        $model=array('电影'=>'film','电视剧'=>'teleplay','栏目'=>'television');
        foreach($array as $key =>$value){
            if($value !== "全部" && $key !== 'time'){
                $tag = $this->getIndexTag($key);
                if($key=='type'){
                    $value=$model[$value];
                }
                $tempstr .= " ".$tag.":".$value;
            }
        }
        return $tempstr;
    }
    
    /*
     * 返回搜索索引tag 与xapian add_prefix中的index对应
     * @param <string> $str
     * @return <string> $tag
     * @author guoqiang.zhang
     */
    public function getIndexTag($str)
    {
        switch ($str){
            case "type":
                    $indexTag = "model";
                    break;
            case "tag":
            case "tag1":
                    $indexTag = "tag";
                    break;
            case "time":
                    $indexTag = "time";
                    break;
            case "area":
                    $indexTag = "area";
                    break;
            default:
                    $indexTag = "";
                    break;
        }
        return $indexTag;
    }
}
