<?php

/**
 * tcl actions.
 *
 * @package    epg
 * @subpackage tcl
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tclActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        //$this->forward('default', 'module');
    }

    /**
     * 主导航菜单，仅AJAX使用
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeMenu(sfWebRequest $request) {
        
    }

    /**
     * 搜索表单，公 AJAX 使用
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeSearch(sfWebRequest $request) {
        $this->sizer = $request->getParameter('sizer', 'main-nav-sizer');
    }

    /**
     * 首页， 仅 AJAX 使用
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeHomepage(sfWebRequest $request) {

    }

    /**
     * tcl 电视剧
     * @author fangdun.cai
     * @param sfWebRequest $request
     */
    public function executeTvplays(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');
        
        $this->programs = $program_respository->getLiveProgramByTag('电视剧', $channels);
    }

    /**
     * tcl 电影
     * @author fangdun.cai
     * @param sfWebRequest $request
     */
    public function executeMovie(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTag('电影', $channels);

    }

    /**
     * tcl 体育
     * @author fangdun.cai
     * @param sfWebRequest $request
     */
    public function executeSports(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTag('体育', $channels);

    }

    /**
     * 娱乐
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeEnt(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTag('娱乐', $channels);

    }

    /**
     * 少儿
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeChildren(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTag('少儿', $channels);

    }

    /**
     * 科教
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeEdu(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTag('科教', $channels);

    }

    /**
     * 财经
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeFinance(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTag('财经', $channels);

    }

    /**
     * 综合
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeGeneral(sfWebRequest $request) {
        $city = $this->getUser()->getAttribute('user_city');
        $provice = $this->getUser()->getAttribute('province');
        $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);

        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTag('综合', $channels);

    }

    public function executeTest(sfWebRequest $request) {
        $mondongo = $this->getMondongo();
        $program_respository = $mondongo->getRepository('program');

        $wiki_ids = array(
            '4cf604f985bf037005020000',
            '4cf707c1d2824fd901000000',
            '4cf708e8d2824ff400000000',
            '4cf70aaad2824fed01000000',
            '4cf70beed2824fed01010000'
        );
        $programs = $program_respository->find(
                    array(
                        'query' => array(
                            'date' => array('$gte' => date('Y-m-d')),
                            'tags'=> '电影'
                        )
                    )
                );
        foreach($programs as $program) {
            $program->setWikiId($wiki_ids[rand(0, 4)]);
            $program->save();
        }

    }
}
