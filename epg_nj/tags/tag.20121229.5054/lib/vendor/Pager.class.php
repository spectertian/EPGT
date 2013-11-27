<?php
/**
 * 静/动态通用分页类
 * @author 雪峰(林强)<linqiang.gx@gmail.com>
 * @since 2004/7/10
 * @final 2006/12/7
 */
class Pager {
    var $mTotalPages;
    var $mPageSize = 1; //每页显示记录数
    var $mTotalRows = -1; //最大记录数
    var $mCurPage = 1; //当前页ID
    var $mUrlStr = ''; //查询页面
    var $mPerPageLinks = 10; //数字分页符显示个数
    var $mUrlEndStr = ''; //其他查询字符串,这些字符串附加在"$mUrlStr"后面
    var $mFirstTag = '第一页';//第一页
    var $mLastTag = '最后一页';//最后一页
    var $mPrevTag = '上一页';//上一页
    var $mNextTag = '下一页';//下一页
    var $mPrevNTag = '上10页';//上N页
    var $mNextNTag = '下10页';//下N页

    /**
     * 构造函数
     * @access public
     * @param URL $urlStr
     * @param 总记录数 $totalRows
     * @param 每页显示记录数 $pageSize
     * @param URL后部字符串 $urlEndStr
     * @return void
     */
    function __construct($urlStr, $totalRows, $pageSize, $urlEndStr='')
    {
        $this->mUrlStr = $urlStr;
        $this->mTotalRows = $totalRows;
        $this->mPageSize = $pageSize;
        $this->mUrlEndStr = $urlEndStr;
        $this->mTotalPages = ceil($totalRows / $pageSize);
    }

    /**
     * 设置当前请求的页码
     * @access public
     * @param int $curPageId
     */
    function SetCurPage($curPageId = 0)
    {
        if ($curPageId > 0) {
            $this->mCurPage = $curPageId;
        }
        if ($curPageId > $this->mTotalPages) {
            $this->mCurPage = $this->mTotalPages;
        }
    }

    /**
     * 记录的开始
     * @access public
     * @return int
     */
    function GetBegin()
    {
        $b = ($this->mCurPage - 1) * $this->mPageSize;
        if ($b < 0) {
            $b = 0;
        }
        return $b;
    }

    /**
     * 记录的尺寸
     * @access public
     * @return int
     */
    function GetSize()
    {
        return $this->mPageSize;
    }

    /**
     * 总页数
     * @access public
     * @return int
     */
    function GetTotalPages()
    {
        return $this->mTotalPages;
    }

    /**
     * 第一页
     * @access public
     * @return string
     */
    function First()
    {
        if ($this->mCurPage > 1) {
            return $this->GetLink('1',$this->mFirstTag,'第一页');
        }
    }

    /**
     * 最后一页
     * @access public
     * @return string
     */
    function Last()
    {
        if ($this->mCurPage < $this->mTotalPages) {
            return $this->GetLink($this->mTotalPages, $this->mLastTag, '最后一页');
        }
    }

    /**
     * 上一页
     * @access public
     * @return string
     */
    function Prev()
    {
        $curPageid = $this->mCurPage - 1;
        if ($curPageid > 0) {
            return $this->GetLink($curPageid,$this->mPrevTag, '上一页');
        }
    }

    /**
     * 下一页
     * @access public
     * @return string
     */
    function Next()
    {
        $curPageid = $this->mCurPage + 1;
        if ($curPageid <= $this->mTotalPages) {
            return $this->GetLink($curPageid, $this->mNextTag, '下一页');
        }
    }

    /**
     * 上N页
     * @access public
     * @return string
     */
    function PrevN()
    {
        $curPageid = $this->mCurPage - $this->mPerPageLinks;
        if ($curPageid > 0) {
            return $this->GetLink($curPageid, $this->mPrevNTag, '上'.$this->mPerPageLinks.'页');
        }
    }

    /**
     * 下N页
     * @access public
     * @return string
     */
    function NextN()
    {
        $curPageid = $this->mCurPage + $this->mPerPageLinks;
        if ($curPageid <= $this->totalPages())
        {
            return $this->GetLink($curPageid, $this->mNextNTag, '下'.$this->mPerPageLinks.'页');
        }
    }

    /**
     * 取得分页数字页码
     * @access public
     * @return string
     */
    function PageN()
    {
        $center = ceil($this->mPerPageLinks / 2) - 1;
        $begin = $this->mCurPage - $center;
        $end = $this->mCurPage + $this->mPerPageLinks - $center - 1;
        if ($begin < 1)
        {
            $begin = 1;
            $end = $this->mPerPageLinks;
        }
        if ($end > $this->mTotalPages)
        {
            $end = $this->mTotalPages;
            $begin = (($this->mTotalPages - $this->mPerPageLinks) > 0)
                ? ($this->mTotalPages - $this->mPerPageLinks + 1)
                : 1;
        }
        $numlink = '';
        for ($begin; $begin <= $end; $begin++)
        {
            if ($this->mCurPage != $begin)
            {
                $numlink .= $this->GetLink($begin, $begin).' ';
            }
            else
            {
                $numlink .= '<span>'.$begin.'</span>'.' ';
            }
        }
        return $numlink;
    }

    /**
     * 取得默认分页导航字符串
     * @access public
     * @return string
     */
    function GetNavigation()
    {
        //return "<span>{$this->mCurPage}</span>/<span>{$this->mTotalPages}</span> ".$this->first().' '.$this->prev().' '.$this->pageN().$this->next().' '.$this->last();
        return "共{$this->mTotalPages}页 ".$this->first().' '.$this->prev().' '.$this->pageN().$this->next().' '.$this->last();
    }

    /**
     * 取得链接字符串
     * @access private
     * @param 页码 $pageId
     * @param 链接显示的字符串 $tag
     * @param 说明 $titleStr
     * @return string
     */
    function GetLink($pageId, $tag, $titleStr=null)
    {
        $titleStr = empty($titleStr) ? '' : ' title="'.$titleStr.'"';
        return '<a href="'.$this->mUrlStr.$pageId.$this->mUrlEndStr.'"'."$titleStr>$tag</a>";
    }
}
