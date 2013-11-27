<?php

/**
 * Repository of Comment document.
 */
class CommentRepository extends \BaseCommentRepository
{
    
    /**
     * 通过wiki_id 获得所通过的评论
     * @param string wiki_id
     * @author lizhi
     * @return array | void
     */
    public function getAllCommentsByWikiId($wiki_id, $top=0, $limit=20){
        $parentWiki = $this->getParentCommentByWikiId($wiki_id, $limit);
        if(!is_array($parentWiki)) return false;
        //$item = array();
        foreach($parentWiki as $key => $sonWiki){
            $item[$key]['text'] = $sonWiki;
            $item[$key]['son'] = $this->getSonComment($wiki_id, (string)$sonWiki->getId(), $limit);
        }
        //var_dump($item);
        return $item;
    }
    
    /**
     * 通过wiki_id 获得parent 所有的评论
     * @param string wiki_id
     * @param <string> $type  like/dislike/watched/queue/replay
     * @param int limit 20
     * @author lizhi
     * @return array| object | void
     */
    public function getParentCommentByWikiId($wiki_id, $skip = 0, $limit = 10, $type = null){
        $query = array(
                    'wiki_id'=> $wiki_id,
                    'is_publish'=> true,
                    'parent_id'=> "0",
                );
        
        if ($type) $query['type'] = $type;
        return $this->find(
                    array(
                        'query'=> $query,
                        'limit'=> $limit,
                        'skip' => $skip,
                        'sort' => array('created_at' => -1)
                    )
                );
    }
    
    /**
     * 通过wiki_id,parent_id 获得子评论
     * @param string parent_id
     * @param int limit
     * @author lizhi
     * @return void| object| array
     */
    public function getSonComment($parent_id, $limit=20){
        return $this->find(
                    array(
                        'query'=>array(
                            'is_publish'=> true,
                            'parent_id'=>$parent_id
                        ),
                        'limit' => $limit,
                        'sort' => array('created_at' => -1)
                    )
                );        
    }
    
    /**
     * 采用递归的方式来获取到相应的留言
     * @param string wiki_id
     * @param string parent_id
     * @param int limit
     * @author lizhi
     * @access public
     * @return void|object|array
     */
    public function getAllComment($wiki_id, $parent_id=0, $limit = 20){
        
    }
    
    /**
     * 发送留言
     * @param string wiki_id
     * @param string user_id
     * @param int type 留言类型 默认为1
     * @param string parent_id 留言的回复，默认为 0
     * @param string text 留言的信息
     * @return void|object|array
     * @author lizhi
     */
    public function subComment($wiki_id, $user_id, $text, $parent_id=0, $type=1){
            $this->setType($type);
            $this->setParentId($parent_id);
            $this->setText($text);
            $this->setUserId($user_id);
            $this->setWikiId($wiki_id);
            $this->save();
    }

    /**
     * 根据 $user_id, $wiki_id, $type 获取一条记录
     * @param <string> $user_id
     * @param <string> $wiki_id
     * @return <type>
     * @author luren
     */
    public function getOneComment($user_id, $wiki_id, $type) {
        return $this->findOne(array(
                        'query' => array(
                            'user_id' => $user_id,
                            'wiki_id' => $wiki_id,
                            'type'  => $type
                        )
                    )
                );
    }
    /**
     * xml ReportUserMediaAction接口 评分相关操作（喜欢OR不喜欢）
     * @param <object> $wiki  wiki对象
     * @param <string> $userId 用户ID
     * @param <string> $action  动作（like/dislike）
     * @param <string> $comment 短评
     * @author wangnan
     */
	public function scoreOperation($wiki,$userId,$action,$comment)
	{
		$disAction = ($action == 'like')?'dislike':'like';
        $commentObject = $this->findOne(array(
                        'query' => array(
                            'user_id' => $userId,
                            'wiki_id' => (string)$wiki->getId(),
                            'type'  => $action
                        )
                    )
                );		
		if(!$commentObject)
		{
			$wiki->setActionValue($action,true,true,$userId,false,$comment);
			$wiki->save();
		}
		else
		{
			$commentObject->setText($comment);
			$commentObject->save();
		}		            	
        $disCommentObject = $this->findOne(array(
                        'query' => array(
                            'user_id' => $userId,
                            'wiki_id' => (string)$wiki->getId(),
                            'type'  => $disAction
                        )
                    )
                );			
		if ($disCommentObject) 
		{
			$disCommentObject->delete();
			$wiki->setActionValue($disAction, false);
			$wiki->save();
		}		
	}    
    /**
    * 根据wiki_id user_id 获得comment
    */
    public function getCommentByUWikiId($user_id, $wiki_id) {
        return $this->findOne(array(
                        'query' => array(
                            'user_id' => $user_id,
                            'wiki_id' => $wiki_id
                        )
                    )
                );        
    }
    
    /**
    * 根据user_id 获得所有的操作
    * @param string user_id
    * @author lizhi
    * @return void
    */
    public function getCommentsByUserId($user_id, $skip, $limit) {
        $options['query'] = array(
              'user_id'=> $user_id,
              'is_publish'=> true,
              'parent_id'=>'0'
        );
        //$options['fields'] = array('wiki_id','user_id','created_at','is_public');
        if(!empty($skip)) {
            $options['skip'] = $skip;
        }
        if(!empty($limit)) {
            $options['limit'] = $limit;
        }
        $options['sort']= array('created_at' => -1);
        return $this->find($options);
    }

    /**
     * 传递一个维基ID计算该条维基的评论数量
     * @param <string> $wiki_id
     * @return <integer>
     * @author luren
     */
    public function countCommentByWikiId($wiki_id) {
        return $this->count(array(
                        'wiki_id' => $wiki_id
                    )
                );
    }
    
    /**
     * 通过user_id and id 来获得comment
     * @param string id
     * @param string user_id
     * @author lizhi
     * @return void
     */
    public function getCommentByUserId($user_id, $id) {
        return $this->findOne(array(
                        'query' => array(
                            'is_publish'=> true,
                            'user_id' => $user_id,
                            '_id' => new MongoId($id)
                        )
                    )
        );
    }
}