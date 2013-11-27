<?php

/**
 * Comment document.
 */
class Comment extends \BaseComment
{
    public $user;
    protected $wiki;
    /**
     * 保存一条评论记录
     * @param <type> $wiki_id
     * @param <type> $type
     * @param <type> $parent_id
     * @param <type> $text
     * @author luren
     */
     /*原来程序
    public function saveComent($wiki_id, $type, $parent_id = 0, $text = '',$user_id=null) {
    	if($user_id)
    	{
    		$this->setUserId($user_id);
    	}
    	else
    	{
    		$this->setUserId(sfContext::getInstance()->getUser()->getAttribute('user_id'));
    	}
        $this->setWikiId($wiki_id);
        $this->setParentId($parent_id);
        $this->setType($type);
        $this->setText($text);
        parent::save();
    }
    */
    //修改:lfc
    public function saveComent($wiki_id, $type, $parent_id = 0, $text = '',$user_id=null) {
        
     	if(!$user_id)
    	{
    		$user_id=sfContext::getInstance()->getUser()->getAttribute('user_id');
    	}   
        
        $mongo = $this->getMondongo();
        $commonRepository = $mongo->getRepository('Comment');
        $info = $commonRepository->findOne(array('query'=>array('user_id' => $user_id,'wiki_id' => $wiki_id,'type'=>$type)));
        if($info){
        	$info->setUserId($user_id);
            $info->setWikiId($wiki_id);
            $info->setParentId($parent_id);
            $info->setType($type);
            if($text!='')
                $info->setText($text);
            $info->save(); 
        }else{
        	$this->setUserId($user_id);
            $this->setWikiId($wiki_id);
            $this->setParentId($parent_id);
            $this->setType($type);
            $this->setText($text);
            parent::save(); 
        }

    }
     /**
     * 获取评论用户
     * @author luren
     */
    public function getUser() {
        if ($this->getUserId()) {
            if (!isset($this->user)) {
                $mongo = $this->getMondongo();
                $UserRepository = $mongo->getRepository('User');
                $this->user = $UserRepository->findOneById(new MongoId($this->getUserId()));
            }
        }
        
        return $this->user;
    }

    /**
     * 返回操作类型的中文名称
     * @return <type>
     * @author luren
     */
    public function getZhcnType() {
        $types = array(
                    'comment' => '评论 该片',
                    'like' => '喜欢',
                    'dislike' => '不喜欢',
                    'watched' => '看过',
                    'queue' => '加入片单',
                );
        
        return ($this->getType()) ? $types[$this->getType()] : '评论 该片';
    }

   /**
    * 获取评论的子评论
    * @return <type>
    * @author luren
    */
   public function getSonComments() {
        $mongo = $this->getMondongo();
        $CommentRepository = $mongo->getRepository('Comment');
        return $CommentRepository->getSonComment((string) $this->getId());
   }

   /**
    * 计算子评论个数
    * @return <type>
    */
   public function getSonCommentsCount(){
        $mongo = $this->getMondongo();
        $CommentRepository = $mongo->getRepository('Comment');
        $count = $CommentRepository->count(array(
                                        'is_publish'=> true,
                                        'parent_id'=> (string) $this->getId()
                                        )
                                    );
        return ($count > 0) ? $count : '';
   }
   /**
    * 删除一天评论时 他的子评论也要一起删除
    * @author luren
    */
   public function postDelete() {
        $mongo = $this->getMondongo();
        $CommentRepository = $mongo->getRepository('Comment');
        $SonComments = $CommentRepository->getSonComment((string) $this->getId());
        if ($SonComments) {
            foreach ($SonComments as $comment) {
                $comment->delete();
            }
        }
   }
   
    /**
    * 获取wiki 相应的信息
    * @return obj
    * @autho lizhi
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
}