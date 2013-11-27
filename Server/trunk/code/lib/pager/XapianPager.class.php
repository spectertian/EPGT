<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class XapianPager extends sfPager {

    protected $search_text;
    protected $weight_key;
    protected $range;
    protected $sort;
    protected $results;

    /**
     * @see sfPager
     */
    public function init() {
        $this->resetIterator();

        $resposityry = $this->getRepository();
        $total = null;
        $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
        $limit = $this->getMaxPerPage();
        
        $this->results = $resposityry->xun_search($this->search_text, $total, $offset, $limit,$this->range,$this->sort,$this->weight_key);
        $this->setNbResults($total);
        
        if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults()) {
            $this->setLastPage(0);
        } else {
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
            $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
        }
    }

    public function  getResults() {
        return $this->results;
    }

    /**
     * 设置搜索文字
     * @param string $text
     */
    public function setSearchText($text) {
        $this->search_text = $text;
    }
    /**
     * 关键字加权
     * @param string $text
     */
    public function setWeightKey($wk) {
        $this->weight_key = $wk;
    }
    /*
     * 设置搜索区间
     * @param 区间表达式
     */
    public function setSearchRange($text){
        $this->range = $text;
    }

    /*
     * 设置排序
     * @param int XapianDocument add_value时value_no值
     */
    public function setSort($value_no){
        $this->sort = $value_no;
    }
    
    public function  retrieveObject($offset) {
        if (array_key_exists($offset, $this->results)) {
            return $this->results[$offset];
        } else {
            return false;
        }
    }

    /**
     * Returns the repository of the pager class.
     *
     * @return Mondongo\Repository The repository of the pager class.
     */
    protected function getRepository() {
        return sfContext::getInstance()->get('mondongo')->getRepository($this->getClass());
    }

}
