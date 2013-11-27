<?php

/**
 * favorites actions.
 *
 * @package    epg
 * @subpackage favorites
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class favoritesActions extends sfActions
{
    public function executeAjax_create(sfWebRequest $request) {

        if (!$request->isXmlHttpRequest()) return $this->renderText (json_encode(array('result' => false)));
        
        $this->getResponse()->setContentType('application/x-json');
        $user_id = $this->getUser()->getAttribute('user_id');
        $content = $request->getPostParameter('content');
        $type = $request->getPostParameter('type');
        if(!$user_id || !$content || !$type) return $this->renderText (json_encode(array('result' => false)));

        $favorite = Doctrine::getTable('Favorites')->createQuery()
                    ->where('user_id = ?', $user_id)
                    ->andWhere('content =?', $content)
                    ->fetchOne();

        if(!$favorite) {
            $favorite = new Favorites();
            $favorite->setUserId($user_id);
            $favorite->setContent($content);
            $favorite->setType($type);
            $favorite->save();
        }

        return $this->renderText (json_encode(array('result' => true)));
    }

    /**
     * 获取我收藏的频道
     * @param sfWebRequest $request
     */
    public function executeAjax_get_channel(sfWebRequest $request) {
        $user_id = $this->getUser()->getAttribute('user_id');
        if($user_id && $request->isXmlHttpRequest()){
            $pids = Doctrine::getTable('Favorites')->getFavoriteContents($user_id, 'channel');

            if(count($pids)) {
            $this->channels = Doctrine::getTable('Channel')->createQuery()
                        ->whereIn('id', $pids)
                        ->execute();
            }
        }
    }

    /**
     * 获取我收藏频道的节目
     * @param sfWebRequest $request
     */
    public function executeAjax_get_channel_program(sfWebRequest $request) {
        $user_id = $this->getUser()->getAttribute('user_id');
        if($user_id && $request->isXmlHttpRequest()){
            $pids = Doctrine::getTable('Favorites')->getFavoriteContents($user_id, 'channel');

            if(count($pids)) {
                $channels = Doctrine::getTable('Channel')->createQuery()
                            ->whereIn('id', $pids)
                            ->execute();
                $this->live_programs = Doctrine::getTable('Program')->getAllLivePrograms($channels);

                $this->other_programs = Doctrine::getTable('Program')->createQuery()
                            ->where('publish = ?', 1)
                            ->andWhereIn('channel_id', $pids)
                            ->andWhere('date = ?', date('Y-m-d'))
                            ->andWhere('time > ?', date('H:i:s'))
                            ->orderBy('time ASC')
                            ->execute();
            }
        }
    }

    public function executeAjax_get_tag_program(sfWebRequest $request) {
        $user_id = $this->getUser()->getAttribute('user_id');
        if($user_id && $request->isXmlHttpRequest()){
            $tags = Doctrine::getTable('Favorites')->getFavoriteContents($user_id, 'tag');

            if(count($tags)) {
                $this->other_programs = Doctrine::getTable('Program')->getTodayProgramByTags($tags);
                $this->live_programs = Doctrine::getTable('Program')->getTodayLiveProgramByTags($tags);
            }
        }
    }

        /**
     * 获取我收藏的栏目
     * @param sfWebRequest $request
     */
    public function executeAjax_get_tag(sfWebRequest $request) {
        $user_id = $this->getUser()->getAttribute('user_id');
        if($user_id && $request->isXmlHttpRequest()){
            $this->tags = Doctrine::getTable('Favorites')->getFavoriteContents($user_id, 'tag');
        }
    }
}
