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
    	$mondongo = $this->getMondongo();
    	/**
    	 * 原使用的wikiRecommend 
    	$wikiRecommendRep = $mondongo -> getRepository("wikiRecommend");
    	$wikiRep = $mondongo -> getRepository("wiki");
		$rwikis = $wikiRecommendRep -> find(array(
                 "sort"=>array("created_at"=>-1),
                 "limit"=>6));
		
		if ($rwikis){
			foreach ($rwikis as $rwiki) {
				$wiki = $wikiRep->findOneById(new MongoId($rwiki->getWikiId()));
				if($wiki){
					$recommendWikis[] = $wiki;
				}
			}
			$this->recWikis = $recommendWikis;
		}*/
		
		//现改为使用recoomed表
		$recRep = $mondongo -> getRepository('recommend');
		$memcache = tvCache::getInstance();
		
		$bigKey = "HomePageBigPicRecKey";
		$bigPicWiki = $memcach->get($bigKey);
		if(!$bigPicWiki){
    		$bigPicWiki = $recRep -> findOne(array(
    		         'query' => array(
                        'pic'=>array('$ne'=>'','$ne'=>null,'$exists'=>true)),
                     'sort'=>array('created_at'=>-1)
            ));
            $memcach->set($bigKey,$bigPicWiki);
        }
        if($bigPicWiki) {
            $existWikiTitle = $bigPicWiki->getTitle();
            $this->bigPicWiki=$bigPicWiki;
		}
		
		$smallKey = "HomePageBigSmallRecKey";
		$recWikis = $memcach->get($smallKey);
		if(!$recWikis){
    		$recWikis = $recRep -> find(array(
    		         'query' => array(
                        'smallpic'=>array('$ne'=>'','$ne'=>null,'$exists'=>true),
                        'title'=>array('$ne'=>"$existWikiTitle")),
                     'sort'=>array('created_at'=>-1),
                     'limit'=>3
            ));
            $memcach->set($smallKey,$recWikis);
        }
        $this->recWikis = $recWikis;
		//print_r($this->wikis);exit();
    }

    /**
     * tcl 电视剧
     * @author fangdun.cai
     * @param sfWebRequest $request
     */
    public function executeTvplays(sfWebRequest $request) {
    	
        $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        
        $program_respository = $mondongo->getRepository('program');
        $this->programs = $program_respository->getLiveProgramByTagHn('电视剧', $channels);
    }

    /**
     * tcl 电影
     * @author fangdun.cai
     * @param sfWebRequest $request
     */
    public function executeMovie(sfWebRequest $request) {
        $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTagHn('电影', $channels);

    }

    /**
     * tcl 体育
     * @author fangdun.cai
     * @param sfWebRequest $request
     */
    public function executeSports(sfWebRequest $request) {
        $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTagHn('体育', $channels);

    }

    /**
     * 娱乐
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeEnt(sfWebRequest $request) {
        $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTagHn('娱乐', $channels);

    }

    /**
     * 少儿
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeChildren(sfWebRequest $request) {
       $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTagHn('少儿', $channels);

    }

    /**
     * 科教
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeEdu(sfWebRequest $request) {
        $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTagHn('科教', $channels);

    }

    /**
     * 财经
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeFinance(sfWebRequest $request) {
        $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTagHn('财经', $channels);

    }

    /**
     * 综合
     * @param sfWebRequest $request
     * @author pjl
     */
    public function executeGeneral(sfWebRequest $request) {
        $mondongo = $this->getMondongo();
    	$netWorkId = $this->getUser()->getAttribute('netWorkId');
    	
    	$spServiceRes = $mondongo -> getRepository('spService');
    	$channels = $spServiceRes -> getChannelsByNetWorkId($netWorkId);
        $program_respository = $mondongo->getRepository('program');

        $this->programs = $program_respository->getLiveProgramByTagHn('综合', $channels);

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
