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
        $this->page = $request->getParameter('page', 1);

        if ($request->isMethod('post')) {
            $city = $this->getUser()->getAttribute('user_city');
            $channels = Doctrine::getTable('Channel')->getReceiveChannels($city);
            $channel_ids = array();
            foreach ($channels as $k => $channel) {
                $channel_ids[] = $channel->getId();
            }

            $sphinx = new SphinxClient();
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
                return $this->renderText(0);
            }

            $this->programs = Doctrine::getTable('Program')
                            ->createQuery()
                            ->where('publish = ?', 1)
//                        ->where('p.date >= ?', date('Y-m-d'))
//                        ->andWhere('p.date <= ?', date('Y-m-d', time() + (7 - $week) * 3600 * 24))
                            ->andWhereIn('id', $program_ids)
                            ->orderBy('date, time')
                            ->execute();
            
            if (!$this->programs->count()) {
                return $this->renderText(0);
            }
        }
    }

}
