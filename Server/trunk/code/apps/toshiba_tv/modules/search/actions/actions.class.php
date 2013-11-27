<?php

/**
 * search actions.
 *
 * @package    epg
 * @subpackage search
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->query = $request->getParameter('q', '');
        //$this->query = mb_convert_encoding($this->query, "UTF-8", "HTML-ENTITIES");
        $this->page = $request->getParameter('page', 1);
        $limit = 10;
        
        if ($this->page > 1) {
            $this->query = mb_convert_encoding($this->query, "UTF-8", "HTML-ENTITIES");
        }

        //if ($request->isMethod('post')) {
            $city = $this->getUser()->getAttribute('user_city');
            $provice = $this->getUser()->getAttribute('province');
            $channels = Doctrine::getTable('Channel')->getUserChannels($city, $provice);
            
            $channel_codes = array();
            foreach ($channels as $k => $channel) {
                $channel_codes[] = $channel->getCode();
            }
            
            // 数据库由 Mysql 迁移至 MongoDB， Sphinx 全文搜索无效，暂时使用正则表达式搜索代理
            $mondongo = $this->getMondongo();
            $program_respository = $mondongo->getRepository('program');

            $query = array(
                "channel_code" => array('$in' => $channel_codes),
                'start_time' => array('$gte' => new MongoDate(time() - 3600)),
                'name' => new MongoRegex("/".$this->query."/im"),
                );
            
            $this->programs = $program_respository->find(array(
                "query" => $query,
                "limit" => $limit,
                "skip" => ($this->page-1) * $limit,
                "sort" => array("start_time" => 1),
                ));
            
            $this->total = $program_respository->count($query);
            $this->total_page = ceil($this->total / $limit);
            /*$sphinx = new SphinxClient();
            $sphinx->SetServer(sfConfig::get('app_sphinxsearch_host'), sfConfig::get('app_sphinxsearch_port'));
//            $sphinx->SetMatchMode(SPH_MATCH_EXTENDED);
//            $sphinx->SetSortMode(SPH_SORT_RELEVANCE);
            $limits = sfConfig::get('app_sphinxsearch_limits');
            $sphinx->SetLimits(($this->page - 1) * $limits, $limits);
            $sphinx->SetFilter('publish', array(1));
            $sphinx->SetFilter('channel_id', $channel_ids);

            $results = $sphinx->Query($this->query);

            $this->total = 0;
            $program_ids = array();
            if ($results['total']) {
                $this->total = $results['total'];
                $program_ids = array_keys($results['matches']);
                asort($program_ids);
            }

//            $week = date('w');
//            if ($week == 0) $week = 7;
            if (!count($program_ids)) {
                $this->total_page = 1;
            } else {
                $this->total_page = ceil($this->total / $limits);
                $this->programs = Doctrine::getTable('Program')
                                ->createQuery()
                                ->where('publish = ?', 1)
    //                        ->where('p.date >= ?', date('Y-m-d'))
    //                        ->andWhere('p.date <= ?', date('Y-m-d', time() + (7 - $week) * 3600 * 24))
                                ->andWhereIn('id', $program_ids)
                                ->orderBy('date, time')
                                ->execute();
            }*/
        //}
    }

}
