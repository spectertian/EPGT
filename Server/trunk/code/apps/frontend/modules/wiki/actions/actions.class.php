<?php
/**
 * wiki actions.
 *
 * @package    epg
 * @subpackage wiki
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
 sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
class wikiActions extends sfActions
{
    /**
     * 根据 url 请求取得维基
     * @param sfWebRequest $request
     * @return <type>
     * @author luren
     */
    protected function requestWiki(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $this->slug  = trim($request->getParameter('slug'));

        if (preg_match("|[0-9a-f]{24}|", $this->slug) || $request->hasParameter("id")) {
            $id = $request->hasParameter("id") ? $request->getParameter('id') : $this->slug;
            $this->slug = $wiki_repository->getSlugById($id);

            if ($this->slug) {
                $this->redirect("wiki/show?slug=".$this->slug, 301);
            } else {
                $this->forward404('该条维基不存在，你懂得！');
            }
        } else {
            $wiki = $wiki_repository->getWikiBySlug($this->slug);
            $this->forward404Unless($wiki, '该条维基不存在，你懂得！');
            return $wiki;
        }
    }
    
    /**
    * 单个维基显示页面 和 ajax请求返回信息
    * @param sfWebRequest $request
    * @author luren
    */
    public function executeShow(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $this->wiki = $this->requestWiki($request); 
        if ($request->isXmlHttpRequest()) {
            $time = $request->getParameter('time');
            $time = empty($time) ? date('Y-m-d') : $time;
            $programs = $this->wiki->getUserRelateProgramByDate($this->getUser()->getUserProvince(), $time, $time);
            return $this->renderPartial($this->wiki->getModel(), array(
                                                                'programs' => $programs,
                                                                'wiki'=> $this->wiki
                                                            )
                                                        );
        } else {
          $this->getResponse()->setTitle($this->wiki->getTitle()." - 我爱电视");
          $this->weibo_qqt = false;
          $this->weibo_sina = false;
          $this->user_id = $this->getUser()->getAttribute('user_id');
          
          /*
          //########得到评论:lfc
          $wiki_id = $this->wiki->getId();           
          $CommentRepository = $mongo->getRepository('Comment');
          $this->pinglun_watch = $CommentRepository->getCommentByUWikiId($this->user_id, (string)$wiki_id);
          //$this->pinglun_watch = $CommentRepository->getOneComment($this->user_id, $wiki_id, 'watched');
          //$this->pinglun = $CommentRepository->findOne();
          print_r($this->pinglun_watch);
          exit;
          //########得到评论:lfc  
          */
           
                
          if($this->user_id!=NULL){
              $userShareRep = $mongo->getRepository("UserShare");
              $this->weibo_sina = $userShareRep->checkShare($this->user_id, 1);
              $this->weibo_qqt = $userShareRep->checkShare($this->user_id, 2);
          }
          //var_dump($this->weibo_sina);
          //var_dump($this->weibo_qqt);
          //var_dump($this->user_id);
          switch ($this->wiki->getModel()) {
              case 'teleplay':
                    // 获取本周的电视剧相关节目单
                    $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                    $this->related_programs = $this->wiki->getWeekRelatedPrograms();
                    $this->dramas_total = $wikiMetaRepos->count(array('wiki_id' => (string) $this->wiki->getId()));
                    $this->dramas = $wikiMetaRepos->getMetasByWikiId((string) $this->wiki->getId(), 0, 10);
                  break;
              case 'film':
                    $program_repository = $mongo->getRepository('Program');
                    $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                    $this->related_programs = $program_repository->getUnPlayedProgramByWikiId($this->wiki->getId());
                  break;
              case 'television':
                    $program_repository = $mongo->getRepository('Program');
                    $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                    $this->related_programs = $program_repository->getUnPlayedProgramByWikiId($this->wiki->getId());
                    $time = (int) str_replace('-', '', $request->getParameter('time'));
                    if ($time) {
                        $query = array(
                                'query' => array(
                                    'wiki_id' => (string) $this->wiki->getId(),
                                    'mark' => $time
                                )
                              );
                        $this->wikiMeta = $wikiMetaRepos->getMetesByQurey($query);

                        if (!$this->wikiMeta) {
                            return $this->setTemplate('television_main') ;
                        } else {
                            return $this->setTemplate('television');
                        }
                     } else {
                        return $this->setTemplate('television_main') ;
                     }
              case 'actor':
                  $this->film0graphy = $this->wiki->getFilmography($this->wiki->getTitle());
                  break;
          }

          $this->setTemplate($this->wiki->getModel());
        }
    }
    
    /**
    * 电视剧分集剧情读取调用
    * @param sfWebRequest $request
    * @author luren
    */
    public function executeDrama(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $wiki_id = $request->getParameter('id');
            $offset = $request->getParameter('offset',10);
            $mongo = $this->getMondongo();
            $wikiMetaRepos = $mongo->getRepository('WikiMeta');
            $dramas_total = $wikiMetaRepos->count(array('wiki_id' => $wiki_id));
            $dramas = $wikiMetaRepos->getMetasByWikiId($request->getParameter('id'), ((1 == $offset) ? 0 : $offset) , 10);

            if ($dramas) {
                $loop = $dramas_total - 10;
                $page_bar = "<div class=\"episode-navi\">\n";
                for($i = 1; $i <= $loop; $i+=10) {
                    if ($offset == $i) {
                        $page_bar .= '<span class="active">'. sprintf("%d-%d", $i, $i+9). "</span>\n";
                    } else {
                        $page_bar .= '<a href="javascript:loadDrama('.$i.')">'.sprintf("%d-%d", $i, $i+9). "</a>\n";
                    }
                    $page_bar .= ' | ';
                }
                if ($i <= $dramas_total) {
                    if ($offset == $i) {
                        $page_bar .= '<span class="active">'. sprintf("%d-%d", $i, $dramas_total). "</span>\n";
                    } else {
                        $page_bar .= '<a href="javascript:loadDrama('.$i.')">'.sprintf("%d-%d", $i, $dramas_total). "</a>\n";
                    }
                }

                $page_bar .= "</div>\n";
                $dramashtml = "<div class=\"episodes\"><dl>\n";
                foreach($dramas as $drama) {
                    $dramashtml .= '<dt>'.$drama->getTitle().'</dt>';
                    $dramashtml .= '<dd><p>'.$drama->getContent().'</p></dd>';
                }
                $dramashtml.= "<dl></div>\n";
                return $this->renderText($page_bar.$dramashtml.$page_bar);
            } else {
                return $this->renderText(0);
            }
        } else {
            $this->forward404();
        }
    }

    /**
     * 栏目归档页面
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeArchive(sfWebRequest $request) {
        $this->wiki = $this->requestWiki($request);
        $mongo = $this->getMondongo();
        $wikiMetaRepository = $mongo->getRepository('wikiMeta');
        $this->getResponse()->setTitle($this->wiki->getTitle()." - 我爱电视");
        $styles = array('list', 'tile');
        $this->style = $request->getParameter('style', 'tile');
        $this->style = in_array($this->style, $styles) ? $this->style : 'tile';
        $this->page = $request->getParameter('page' , 1);
        
        $this->year =  $request->getParameter('year', date('Y', time()));
        $this->month = $request->getParameter('month', 'all');
        $this->archiveDate = $this->wiki->getArchiveDate($this->year);        
        $this->year = in_array($this->year, $this->archiveDate['years']) ? $this->year : current($this->archiveDate['years']);
        $this->month = in_array($this->month, $this->archiveDate['months']) ?  $this->month : 'all';
        
        $this->archivePager = new sfMondongoPager('wikiMeta', 20);
        $query['wiki_id'] = (string) $this->wiki->getId();
        $query['year'] = $this->year;
        ($this->month != 'all') && $query['month'] = $this->month;
        $this->archivePager->setFindOptions(array('query' => $query, 'sort' => array('mark' => -1)));
        $this->archivePager->setPage($this->page);
        $this->archivePager->init();
    }
    
    /**
     * 用户提交维基评论
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeComment(sfWebRequest $request) {
        $user_id = $this->getUser()->getAttribute('user_id');
        $wiki_id = $request->getParameter('id');
        $parent_id = $request->getParameter('pid', 0);
        $type = $request->getParameter('type');
        $tags = $request->getParameter('tags');
        $text = $request->getParameter('comment');
        $weibo = $request->getParameter('weibo');
        $title = $request->getParameter('title');
        $mongo = $this->getMondongo();
        //var_dump($weibo);exit;
        //如果只是普通的评论就直接插入一条评论
        $generalTypes = array('comment', 'reply');
        if (in_array($type, $generalTypes)) {
            $comment = new Comment();
            $comment->saveComent($wiki_id, $type, $parent_id, $text);
        } else {
            $CommentRepository = $mongo->getRepository('Comment');
            $comment = $CommentRepository->getOneComment($user_id, $wiki_id, $type);
            $comment->setText($text);
            $comment->save();
        }
        if($weibo!=NULL || $type=='like' || $type=='watched') {
          $userShareRep = $mongo->getRepository("UserShare");
          $wikiRep = $mongo->getRepository("Wiki");
          $wiki = $wikiRep->findOneById(new MongoId($wiki_id));
          $this->weibo_sina = $userShareRep->checkShare($user_id, 1);
          $this->weibo_qqt = $userShareRep->checkShare($user_id, 2);
          if($type=='like' || $type=='watched'){
              if($this->weibo_sina) $weibo['Sina'] = 'Sina';
              if($this->weibo_qqt) $weibo['Qqt'] = 'Qqt';
          }
          foreach($weibo as $key => $val) {
              if($val=='Sina' && $this->weibo_sina != false){
                 $sina_api_config = sfConfig::get("app_weibo_Sina");
                $oauth_token = $this->weibo_sina->getAccecssToken();
                $oauth_token_secret = $this->weibo_sina->getAccecssTokenSecret();
                $SinaWeiboClient = new SinaWeiboClient($sina_api_config['akey'], $sina_api_config['skey'], $oauth_token, $oauth_token_secret);
                //var_dump($SinaWeiboClient);
                $send_text = '#'.$wiki->getTitle().'#'.$text.' http://www.5i.tv/wiki/'.$wiki->getId();
                $weiboData = $SinaWeiboClient->update($send_text);
              }
              if($val=='Qqt' && $this->weibo_qqt != false){
                $qqt_api_config = sfConfig::get("app_weibo_Qqt");
                $oauth_token = $this->weibo_qqt->getAccecssToken();
                $oauth_token_secret = $this->weibo_qqt->getAccecssTokenSecret();
                $QqtWeiboClient = new QqtWeiboClient($qqt_api_config['akey'], $qqt_api_config['skey'], $oauth_token, $oauth_token_secret);
                $send_text = '#'.$wiki->getTitle().'#'.$text.' http://www.5i.tv/wiki/'.$wiki->getId();;
                $weiboData = $QqtWeiboClient->update($send_text);
              }
          }
        }
        //var_dump($weibo);exit;
        if (!$request->isXmlHttpRequest()) {
            // 分割标签并保持到用户的 tags
            if ($tags) {
                $tags = preg_split ("/[\s,]+/", $tags, -1, PREG_SPLIT_NO_EMPTY);
                // 添加用户常用标签
                $UserRepository = $mongo->getRepository('User');
                $User = $UserRepository->findOneById(new MongoId($user_id));
                
                if ($UserTags = $User->getTags()){
                    $User->setTags($this->tagsKeyPlus($UserTags, $tags));
                } else {
                    foreach ($tags as $tag) {
                        $tagArray[$tag]  = 1;
                    }         
                    $User->setTags($tagArray);
                }
                $User->save();

                //添加维基评论标签
                $WikiRepository = $mongo->getRepository('Wiki');
                $Wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
                
                if ($WikiCommentTags = $Wiki->getCommentTags()) {
                    $Wiki->setCommentTags($this->tagsKeyPlus($WikiCommentTags, $tags));
                } else {
                    foreach ($tags as $tag) {
                        $tagArray[$tag]  = 1;
                    }
                    $Wiki->setCommentTags($tagArray);
                }
                $Wiki->save();
            }

            $this->redirect($request->getReferer());
        } else {
            $thumb_url = thumb_url($comment->getUser()->getAvatar(), 32, 32);
            $uid = 'user/user_feed?uid='.$comment->getUser()->getId();
            $html = <<<EOF
<li>
  <div class="avatar">
      <a href="{$uid}"><img width="32" height="32" alt="" src="{$thumb_url}" class="popup-tip" title="{$comment->getUser()->getUsername()}"></a>
  </div>
  <div class="reply-bd">
    <div class="reply-quote">
        <span class="username yourself">
            <a href="#">{$comment->getUser()->getUsername()}</a>
        </span>
        {$comment->getText()}
    </div>
    <div class="reply-time">{$comment->getCreatedAt()->format("Y-m-d H:i:s")}</div>
  </div>
</li>
EOF;
            return $this->renderText($html);
        }
    }

    /**
     * ajax 评论加载
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeLoad_comment(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $page = $request->getParameter('page', 0);
            $comments = $this->processComments($request, 'load');
            if ($comments) {
                return $this->renderPartial('comments_list', array('comments' => $comments, 'page' => $page));
            } else {
                return $this->renderText('<li>暂时没有评论...</li>');
            }
        } else {
            $this->forward404();
        }
    }
    
    /**
     * ajax 检查是否还有更多的评论
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeMore(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $comments = $this->processComments($request, 'more');
            if ($comments) {
                return $this->renderText(1);
            } else {
                return $this->renderText(0);
            }
        } else {
            $this->forward404();
        }
    }

    /**
     * 处理评论请求参数 并返回评论数据
     * @param sfWebRequest $request
     * @author luren
     */
    protected function processComments(sfWebRequest $request, $action = 'load') {
        $wiki_id = $request->getParameter('id');
        $type = $request->getParameter('type', null);
        $type == 'all' && $type = null;
        $page = $request->getParameter('page', 0);
        $skip = ('load' == $action) ? ($page - 1) * 10 : $page * 10;
        $mongo = $this->getMondongo();
        $comment_repository = $mongo->getRepository('Comment');
        return $comment_repository->getParentCommentByWikiId($wiki_id, $skip, 10, $type);
    }
    
    /**
     * 用户对维基 喜欢/不喜欢/看过/加入片单操作
     * @author luren
     */
    public function executeDo(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $user_id = $this->getUser()->getAttribute('user_id');
            $wiki_id = $request->getParameter('id');
            $action = $request->getParameter('act');
            $mongo = $this->getMondongo();
            $wiki_repository = $mongo->getRepository('Wiki');
            $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));

            if ($wiki) {
                switch($action) {
                    case 'like':
                        $wiki->setActionValue('like', true);
                        $wiki->setDoDate(new \DateTime());
                        // 如果对该条维基已经存在不喜欢行为记录 则取消该条记录
                        $mongo = $this->getMondongo();
                        $CommentRepository = $mongo->getRepository('Comment');
                        $comment = $CommentRepository->getOneComment($user_id, $wiki_id, 'dislike');
                        //添加lfc
                        $this->pinglun = $CommentRepository->getOneComment($user_id, $wiki_id, 'like');
                        if ($comment) {
                            $comment->delete();
                            $wiki->setActionValue('dislike', false,true,null,true);
                        }
                        //return $this->renderPartial('nav_tool', array('wiki' => $this->wiki, 'wikiMeta' => $this->wikiMeta, 'related_programs' => $this->related_programs,'pinglun'=>$this->pinglun));
                    break;
                    case 'like_cancel':
                        $wiki->setActionValue('like', false);
                    break;
                    case 'dislike':
                        $wiki->setActionValue('dislike', true,true,null,true);
                        // 如果对该条维基已经存在喜欢行为记录 则取消该条记录
                        $mongo = $this->getMondongo();
                        $CommentRepository = $mongo->getRepository('Comment');
                        $comment = $CommentRepository->getOneComment($user_id, $wiki_id, 'like');
                        //添加lfc
                        $this->pinglun = $CommentRepository->getOneComment($user_id, $wiki_id, 'dislike');
                        if ($comment){
                            $comment->delete();
                            $wiki->setActionValue('like', false);
                        }
                    break;
                    case 'dislike_cancel':
                        $wiki->setActionValue('dislike', false);
                    break;
                    case 'watche':
                        $wiki->setActionValue('watched', true,true,null,true);
                        //添加lfc
                        $mongo = $this->getMondongo();
                        $CommentRepository = $mongo->getRepository('Comment');
                        $this->pinglun = $CommentRepository->getOneComment($user_id, $wiki_id, 'watched');  
                        //echo $this->pinglun->getText(); 
                        //return $this->renderPartial('nav_tool', array('wiki' => $this->wiki, 'wikiMeta' => $this->wikiMeta, 'related_programs' => $this->related_programs,'pinglun'=>$this->pinglun));                    
                    break;
                    case 'watche_cancel':
                        $wiki->setActionValue('watched', false);
                    break;
                    case 'queue':
                        $chip = new SingleChip();
                        $chip->setUserId($user_id);
                        $chip->setWikiId($wiki_id);
                        $chip->setIsPublic(true);
                        $chip->save();
                        $comment = new Comment();
                        $comment->saveComent($wiki_id, 'queue', 0);
                        return $this->renderText(1);
                    case 'queue_cancel':
                        $commentRepository = $mongo->getRepository('Comment');
                        $comment = $commentRepository->getOneComment($user_id, $wiki_id, 'queue');
                        if ($comment) $comment->delete();
                        $chipRepository = $mongo->getRepository('SingleChip');
                        $chip = $chipRepository->getOneChip($user_id, $wiki_id);
                        if ($chip) $chip->delete();
                        return $this->renderText(2);
                }
                
                $wiki->save();
                $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));
                $result = array(
                    'like_num' => $wiki->getLikeNum(),
                    'dislike_num' => $wiki->getDislikeNum(),
                    'watched_num' => $wiki->getWatchedNum(),
                    'rating' => $wiki->getRating(),
                    'rating_total' => $wiki->getRatingTotal(),
                    'rating_int' => $wiki->getRatingInt(),
                    'rating_float' => $wiki->getRatingFloat(),
                    'rating_color' => $wiki->getRatingColor(),
                	'neirong' => $this->pinglun->getText(),
                );
                
                return $this->renderText(json_encode($result));
            } else {
                return $this->renderText(0);
            }
        } else {
            $this->forward404();
        }
    }

    /**
     * 微薄数据加载
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeWeibo(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $title = $request->getParameter('title');
            $sina_api_config = sfConfig::get("app_weibo_Sina");
            $oauth_token = "0fca4a4acafdb1b006380bf9f92ed2e0";
            $oauth_token_secret = "324fff766991715bebc86c505b055ff0";
            $SinaWeiboClient = new SinaWeiboClient($sina_api_config['akey'], $sina_api_config['skey'], $oauth_token, $oauth_token_secret);
            $weiboData = $SinaWeiboClient->trends_statuses(sprintf('#%s#', $title));
            return $this->renderPartial('weibo', array('weiboData' => $weiboData));
        } else {
            $this->forward404();
        }
    }
    /**
     * 给输入标签键值加一 
     * @param <arrry> $tags1 已有标签
     * @param <array> $tags2 新输入标签
     * @return <array>
     * @author luren
     */
    private function tagsKeyPlus($tags1, $tags2) {;
	$result = array();
	foreach ($tags1 as $tag1 => $count) {
            if (in_array($tag1, $tags2)) {
                $result[$tag1] = $count + 1;
            } else {
                $result[$tag1] = $count;
            }

            $index = array_search($tag1, $tags2);
            if ($index !== false) {
                unset($tags2[$index]);
            }
	}

	if (!empty($tags2)) {
            foreach ($tags2 as $tag2) {
                $result[$tag2] = 1;
            }
	}
        
        uasort($result, array($this, 'cmp'));
        return $result;
    }
    
    /**
     * 传递 $a, $b 对比两个数的大小 用于 uasort callback
     * @author luren
     */
    private function cmp($a, $b) {
        return ($a < $b);
    }    
}
