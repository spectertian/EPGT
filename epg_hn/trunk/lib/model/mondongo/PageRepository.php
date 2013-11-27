<?php

/**
 * Repository of Page document.
 */
class PageRepository extends \BasePageRepository
{
    /**
     * 根据 pagename 获取一条版本最新的 HTML 模板
     * @param <type> $name
     * @return <type>
     * @author luren
     */
    public function getNewestPageByName($name) {
        return $this->findOne(
                    array(
                        'query' => array('pagename' => $name),
                        'sort' => array('version' => -1)
                    )
                );
    }
}