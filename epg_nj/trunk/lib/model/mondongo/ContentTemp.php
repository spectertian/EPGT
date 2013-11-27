<?php

/**
 * ContentTemp document.
 */
class ContentTemp extends \BaseContentTemp
{
  protected $wiki = null;
  /**
   * 获取关联的wiki对象
   * @return <obj>
   * @author pjl
   */
  public function getWiki() {
    if (!isset($this->wiki)) {
      $wiki_id = $this->getWikiId();
      if($wiki_id) {
        $mondongo = $this->getMondongo();
        $wiki_repository = $mondongo->getRepository('Wiki');
        $this->wiki = $wiki_repository->getWikiById($wiki_id);
      }
    }
    return $this->wiki;
  }
  
  /**
   * 获取wiki标题
   * @return <string>
   * @author pjl
   */
  public function getWikiTitle() {
    $wiki_title = '';
    $this->wiki = $this->getWiki();
    if($this->wiki) {
      $wiki_title = $this->wiki->getTitle();
    }
    return $wiki_title;
  }
}