<?php
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
/**
 * wiki actions.
 *
 * @package    epg2.0
 * @subpackage wiki
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wikiActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
      //$this->forward('default', 'module');
    }
    /**
     * 根据 url 请求取得维基
     * @param sfWebRequest $request
     * @return <type>
     * @author luren
     */
    protected function requestWiki(sfWebRequest $request) 
    {
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
    }    /**
    * 单个维基显示页面 和 ajax请求返回信息
    * @param sfWebRequest $request
    * @author luren
    */
    public function executeShow(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $this->wiki = $this->requestWiki($request); 
        if ($request->isXmlHttpRequest()) {
        	$cover = thumb_url($this->wiki->getCover(), 172, 255);
        	$directors = implode(',',$this->wiki->getDirector());
        	$stars = implode(',',$this->wiki->getStarring());
        	
        	$data['title'] = $this->wiki->getTitle();
        	$data['cover'] = $cover;
        	$data['slug'] = $this->wiki->getSlug();
        	$data['directors'] = $directors;
        	$data['stars'] = $stars;
        	$data['htmlcache'] = $this->wiki->getHtmlCache(200, ESC_RAW);
        	return $this->renderText(json_encode($data));
        } else {
          //$this->getResponse()->setTitle($this->wiki->getTitle()." - 智能EPG");
          $this->weibo_qqt = false;
          $this->weibo_sina = false;
          $this->user_id = $this->getUser()->getAttribute('user_id');                     
                
          if($this->user_id!=NULL){
              $userShareRep = $mongo->getRepository("UserShare");
              $this->weibo_sina = $userShareRep->checkShare($this->user_id, 1);
              $this->weibo_qqt = $userShareRep->checkShare($this->user_id, 2);
          }
          
          $this->previous = $request->getReferer();

          switch ($this->wiki->getModel()) {
              case 'teleplay':
                    // 获取电视剧相关节目单
                    $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                    $program_repository = $mongo->getRepository('Program');
                    $this->related_programs = $program_repository->getdayUnPlayedProgramByWikiId($this->wiki->getId());
                    //$this->related_programs = $this->wiki->getWeekRelatedPrograms();
                    $this->dramas_total = $wikiMetaRepos->count(array('wiki_id' => (string) $this->wiki->getId()));
                    $this->dramas = $wikiMetaRepos->getMetasByWikiId((string) $this->wiki->getId(), 0, 10);
                  break;
              case 'film':
                    $program_repository = $mongo->getRepository('Program');
                    $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                    $this->related_programs = $program_repository->getdayUnPlayedProgramByWikiId($this->wiki->getId());
                  break;
              case 'television':
                    $program_repository = $mongo->getRepository('Program');
                    $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                    $this->related_programs = $program_repository->getdayUnPlayedProgramByWikiId($this->wiki->getId());
                    $time = (int) str_replace('-', '', $request->getParameter('time'));
                    if ($time) {
                        $query = array(
                                'query' => array(
                                    'wiki_id' => (string) $this->wiki->getId(),
                                    'mark' => $time
                                )
                              );
                        $this->wikiMeta = $wikiMetaRepos->getMetesByQurey($query);
                     
//                        if (!$this->wikiMeta) {
//                            return $this->setTemplate('television_main') ;
//                        } else {
//                            return $this->setTemplate('television');
//                        }
                     } else {
//                        return $this->setTemplate('television_main') ;
//                        return $this->setTemplate('television') ;
                     }
              case 'actor':
                  $this->film0graphy = $this->wiki->getFilmography($this->wiki->getTitle());
                  break;
          }

          //$this->setTemplate($this->wiki->getModel());
        }
    } 

    /**
    * 获取当前正在播放的节目
    * @param sfWebRequest $request
    * @author lifucang
    */
    public function executeCurrentProgram(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $program_repository = $mongo->getRepository('Program');
        $program = $program_repository->getOneLiveProgramByCode();
        $this->wiki = $program->getWiki(); 
        $this->weibo_qqt = false;
        $this->weibo_sina = false;
        $this->user_id = $this->getUser()->getAttribute('user_id');                     
        if($this->user_id!=NULL){
            $userShareRep = $mongo->getRepository("UserShare");
            $this->weibo_sina = $userShareRep->checkShare($this->user_id, 1);
            $this->weibo_qqt = $userShareRep->checkShare($this->user_id, 2);
        }
        $this->previous = $request->getReferer();
        switch ($this->wiki->getModel()) {
          case 'teleplay':
                // 获取电视剧相关节目单
                $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                $this->related_programs = $program_repository->getdayUnPlayedProgramByWikiId($this->wiki->getId());
                //$this->related_programs = $this->wiki->getWeekRelatedPrograms();
                $this->dramas_total = $wikiMetaRepos->count(array('wiki_id' => (string) $this->wiki->getId()));
                $this->dramas = $wikiMetaRepos->getMetasByWikiId((string) $this->wiki->getId(), 0, 10);
              break;
          case 'film':
                $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                $this->related_programs = $program_repository->getdayUnPlayedProgramByWikiId($this->wiki->getId());
              break;
          case 'television':
                $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                $this->related_programs = $program_repository->getdayUnPlayedProgramByWikiId($this->wiki->getId());
                $time = (int) str_replace('-', '', $request->getParameter('time'));
                if ($time) {
                    $query = array(
                            'query' => array(
                                'wiki_id' => (string) $this->wiki->getId(),
                                'mark' => $time
                            )
                          );
                    $this->wikiMeta = $wikiMetaRepos->getMetesByQurey($query);
                 
        //                        if (!$this->wikiMeta) {
        //                            return $this->setTemplate('television_main') ;
        //                        } else {
        //                            return $this->setTemplate('television');
        //                        }
                 } else {
        //                        return $this->setTemplate('television_main') ;
        //                        return $this->setTemplate('television') ;
                 }
          case 'actor':
              $this->film0graphy = $this->wiki->getFilmography($this->wiki->getTitle());
              break;
        }
        $this->setTemplate('show');

    }     
}
