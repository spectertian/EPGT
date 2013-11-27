<?php

/**
 * queue actions.
 *
 * @package    epg2.0
 * @subpackage queue
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class queueActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    *
    */
    public $putQueues = array("epg_queue","epg_queue_irs","epg_queue_nj");

    public function executeIndex(sfWebRequest $request)
    {
        $this->pageTitle = '消息队列管理';
        $httpsqs = HttpsqsService::get();

        foreach($this->putQueues as $queue) {
            $queueStatus[$queue] = json_decode($httpsqs->status_json($queue),true);
        }
        $this->queueStatus = $queueStatus;
        //$this->irs = json_decode($httpsqs->status_json("epg_queue_irs"));
        //$this->nj = json_decode($httpsqs->status_json("epg_queue_nj"));

    }
}

