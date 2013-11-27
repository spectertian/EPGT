<?php
/**
 * program actions.
 * @package    epg2.0
 * @subpackage program
 * @author     lifucang gaobo
 */
class vodActions extends sfActions
{
    public static $types = array("电影","电视剧","栏目");
    public static $areas = array(
                                array("大陆","香港","台湾","日本","韩国","美国","其它"),
                                array("大陆","香港","台湾","日本","韩国","美国","其它"),
                                array("体育","娱乐","少儿","科教","综合"),
                           );
    public static $tags  = array(
                                array("喜剧","爱情","古装","家庭","剧情","悬疑","谍战","动作","战争","恐怖","动画"),
                                array("喜剧","爱情","古装","家庭","剧情","悬疑","谍战","动作","战争","恐怖","动画"),
                                array("竞技","访谈","综艺","益智","亲子","生活","新闻","人文","法制","纪录","旅游")
                           );
    
    /*
     * Executes index action
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
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
        $this->sort = $request->getParameter("sort",0);  //排序字段   默认按照上映时间
        if($tags){
            $this->wikiTagsRepons = array("全部") + $tags;
        }else{
            $this->wikiTagsRepons = array("全部");
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
