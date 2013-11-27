<?php

/**
 * Page document.
 */
class Page extends \BasePage
{
    /**
     * 保存模板页面 如果已经存在相同模板 则把当前保存的模板版本设置为最新
     * @author luren
     */
    public function save() {
        $mongo = $this->getMondongo();
        $page_repository = $mongo->getRepository('Page');
        $page = $page_repository->getNewestPageByName($this->getPagename());

        if (!is_null($page)) {
            $this->setVersion($page->getVersion() + 1);
        } else {
            $this->setVersion(0);
        }
        
        parent::save();
    }
}