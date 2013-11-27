<?php

/**
 * SingleChip document.
 */
class SingleChip extends \BaseSingleChip
{
    protected $wiki = null;
    protected $comment = null;
    /**
    * wiki
    * @return obj
    * @autho lizhi
    */
    public function getWiki() {
        if (!isset($this->wiki)) {
            $wiki_id = $this->getWikiId();
            if($wiki_id) {
                $mondongo = $this->getMondongo();
                $wiki_repository = $mondongo->getRepository('Wiki');
               // $this->wiki = $wiki_repository->getWikiById($wiki_id);
                $this->wiki = $wiki_repository->findOneById(new MongoId($wiki_id));
            }
        }
        return $this->wiki;
    }
    
    /**
    * wiki_id
    * @return obj
    * @author lizhi
    */
    public function getComment() {
        if(!isset($this->comment)) {
            $wiki_id = $this->getWikiId();
            $user_id = $this->getUserId();
            if($wiki_id) {
                $mondongo = $this->getMondongo();
                $comment_repository = $mondongo->getRepository('Comment');
                $this->comment = $comment_repository->getCommentByUWikiId($user_id,$wiki_id);
            }
        }
        
        return $this->comment;
    }
    
    /**
    * comment by type
    * @return obj
    * @param string type
    * @author lizhi
    */
    public function getCommentByType($type) {
      if(!isset($this->comment)) {
        $wiki_id = $this->getWikiId();
        $user_id = $this->getUserId();
        if($wiki_id) {
          $mondongo = $this->getMondongo();
          $comment_repository = $mondongo->getRepository('Comment');
          $this->comment = $comment_repository->getOneComment($user_id,$wiki_id, $type);
        }
      }
      return $this->comment;
    }
}