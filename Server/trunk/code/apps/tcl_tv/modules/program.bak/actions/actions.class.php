<?php

/**
 * program actions.
 *
 * @package    epg
 * @subpackage program
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class programActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    /**
     * ajax 取得当周，周一到周日其中一天的节目列表
     * @param sfWebRequest $request
     */
    public function executeAjax_weekday(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $channel_id = $request->getParameter('channel_id', 1);
            $date = $request->getParameter('date', date('Y-m-d'));

            $this->programs = Doctrine::getTable('Program')->getPrograms($channel_id, $date);

            if (!$this->programs->count()) {
                return $this->renderText(0);
            }

            $this->current_program = Doctrine::getTable('Program')->getTodayProgram($channel_id, $date);
            $this->tags = Doctrine::getTable('ProgramTag')->getTagsWithPrograms($this->programs);
        }
    }

    /**
     * 取得当前分类的直播列表
     * @param sfWebRequest $request
     */
    public function executeAjax_live_tag(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $tag = $request->getParameter('tag', 'all');

            $city = $this->getUser()->getAttribute('user_city');

            $channels = Doctrine::getTable('Channel')->getReceiveChannels($city);

            $this->programs = Doctrine::getTable('Program')->getLiveProgramsWithTag($tag, $channels);

            if (!count($this->programs)) {
                return $this->renderText(0);
            }
        }
    }

    public function executeAjax_all_live(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $city = $this->getUser()->getAttribute('user_city');
            $provice    = $this->getUser()->getAttribute('province');
            $channels = Doctrine::getTable('Channel')->getReceiveChannels($city, $provice);
            
            $this->programs = Doctrine::getTable('Program')->getAllLivePrograms($channels);

            if (!count($this->programs)) {
                return $this->renderText(0);
            }
        }
    }

}

