<?php

/**
 * default actions.
 *
 * @package    epg
 * @subpackage default
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vodActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
        $this->types = array("电视剧", "电影", "体育", "娱乐", "少儿", "科教", "财经", "综合");
        $this->searchCondition = array(
                "type" => '',
                'tag'  => '',
                'area' => '',
                'time' => ''
        );
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
        //呈现样式
        $this->style = $request->getParameter("style","list");
        //排序字段
        $this->sort = $request->getParameter("sort",0);
        
        $mogo = $this->getMondongo();
        $wiki = $mogo->getRepository("wiki");
        $this->queryStr = $this->getSearchText(array_filter($this->searchCondition));
        $tags = $wiki->getTagsByType($this->type);
        if($tags){
            $this->wikiTagsRepons = array("全部") + $tags;
        }else{
            $this->wikiTagsRepons = "";
        }   
        $this->wiki_pager = new XapianPager("Wiki", 9);
        $this->wiki_pager->setSearchText($this->queryStr);
        $this->wiki_pager->setSearchRange($this->searchCondition['time']=="全部"?null :$this->searchCondition['time']);
        $this->wiki_pager->setSort($this->sort);
        $this->wiki_pager->setPage($this->page);
        $this->wiki_pager->init();
        $this->getResponse()->setTitle($this->type.' - 搜索');	
        /*
        //在indexSuccess.php里判断
        if($this->style=='list')
            $this->setTemplate('list');
        else
            $this->setTemplate('index');
        */    
                	
	}
    /*
     * 获取搜索文本
     * @param <array> $array
     * @return <string> $queryStr
     * @author:guoqiang.zhang
     */
    function getSearchText($array){
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
    public function getIndexTag($str){
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
