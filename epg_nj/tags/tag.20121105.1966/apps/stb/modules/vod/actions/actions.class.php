<?php
/**
 * program actions.
 *
 * @package    epg2.0
 * @subpackage program
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vodActions extends sfActions
{
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->types = array("电视剧", "电影", "体育", "娱乐", "少儿", "科教", "财经", "综合");
        $this->areas = array("华语","美国","欧洲","日本","韩国","其它");
        $this->searchCondition = array("type" => '',
                'tag'  => '',
                'area' => '');
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
        
        //分页
        $this->page = $request->getParameter("page", 1);
        
        //排序字段   默认按照更新时间
        $this->sort = 0;        
        $mogo = $this->getMondongo();
        $wiki = $mogo->getRepository("wiki");
        $this->queryStr = $this->getSearchText(array_filter($this->searchCondition));
        $tags = $wiki->getTagsByType($this->type);
        if($tags){
            $this->wikiTagsRepons = array("全部") + $tags;
        }else{
            $this->wikiTagsRepons = "";
        }
        $this->wiki_pager = new XapianPager("Wiki", 14);
        $this->wiki_pager->setSearchText($this->queryStr);
        $this->wiki_pager->setSort($this->sort);
        $this->wiki_pager->setPage($this->page);
        $this->wiki_pager->init();
        
        //print_r($this->wiki_pager);
        //$this->getResponse()->setTitle($this->type.' - 搜索');
    }
    
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeAjax(sfWebRequest $request)
    {
        $this->types = array("电视剧", "电影", "体育", "娱乐", "少儿", "科教", "财经", "综合");
        $this->areas = array("华语","美国","欧洲","日本","韩国","其它");
        $this->searchCondition = array("type" => '电视剧',
                'tag'  => '全部',
                'area' => '全部');
        foreach($this->searchCondition as $key => $condition){
            if($request->getParameter($key)){
                $this->searchCondition[$key] = $request->getParameter($key);
            }else{
                $this->searchCondition[$key] = $condition;
            }
        } 
        if($this->searchCondition['type'] == "全部"){
            $this->type = $this->types[0];
            $this->searchCondition["type"] = $this->types[0];
        }else{
            $this->type = $this->searchCondition['type'];
        }       
        //分页
        $this->page = $request->getParameter("page", 1);
        
        //排序字段   默认按照更新时间
        $this->sort = 0;        
        $mogo = $this->getMondongo();
        $wiki = $mogo->getRepository("wiki");
        $this->queryStr = $this->getSearchText(array_filter($this->searchCondition));
        $tags = $wiki->getTagsByType($this->type);
        if($tags){
            $this->wikiTagsRepons = array("全部") + $tags;
        }else{
            $this->wikiTagsRepons = "";
        }
        $this->wiki_pager = new XapianPager("Wiki", 14);
        $this->wiki_pager->setSearchText($this->queryStr);
        $this->wiki_pager->setSort($this->sort);
        $this->wiki_pager->setPage($this->page);
        $this->wiki_pager->init();
        
        //print_r($this->wiki_pager);
        //$this->getResponse()->setTitle($this->type.' - 搜索');
    }
    /**
    * ajax调用
    * @author lifucang
    */
    public function executeShowTags(sfWebRequest $request)
    {
        
        $type = $request->getParameter('type','电视剧');
        $mytag = $request->getParameter('mytag','全部');
        $mogo = $this->getMondongo();
        $wiki = $mogo->getRepository("wiki");        
        $tags = $wiki->getTagsByType($type);
        
        if($tags){
            $wikiTagsRepons = array("全部") + $tags;
        }else{
            $wikiTagsRepons = array("全部");
        }
        
        if($type == '电视剧' || $type == '电影'){
        	$tagsResonsArr = array_slice($wikiTagsRepons, 0, 10, true);
        }
        
        return $this->renderPartial('showTags', array('wikiTagsRepons'=>$wikiTagsRepons,'mytag'=>$mytag,'tagsArr'=>$tagsResonsArr)); 
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
        foreach($array as $key =>$value){
            if($value !== "全部" && $key !== 'time'){
                $tag = $this->getIndexTag($key);
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
                    $indexTag = "tag";
                    break;
            case "tag":
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
