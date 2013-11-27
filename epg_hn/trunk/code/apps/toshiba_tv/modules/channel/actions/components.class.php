<?php

class channelComponents extends sfComponents {

    public function executeShow_tags_list(sfWebRequest $request) {
        $this->tags = Doctrine::getTable('Tags')->findAll();
    }

    public function executeShow_weekdays_list(sfWebRequest $request) {
        
    }

    /**
     *
     * @param sfWebRequest $request
     * @author dehua.chen
     * @date
     * @update author fangdun.cai
     */
    public function executeShow(sfWebRequest $request) {
        $channel_id = $this->channel_id;
        $date = $this->show_date;
        $page = $this->show_page;
        $today = date('Y-m-d');
        $this->current_program  = null;

        $datetime_start = date('Y-m-d H:i:s', ( $today == $date ) ? time() : strtotime( $date ) );
        $datetime_end = date('Y-m-d H:i:s', strtotime($today) + 3600 * 7 * 24);
        
        if ($page == 1 && $today == $date) {
            $this->current_program = Doctrine::getTable('Program')->getTodayProgram($channel_id, $date);
            if ($this->current_program) {
                $datetime_start = $this->current_program->getFulltime();
            }
        }

        $this->pager = new sfDoctrinePager('Program', 10);
        $this->pager->setPage($page);
        $this->pager->getQuery()
                ->from('Program a')
                ->where('channel_id = ?', $channel_id)
                ->andWhere('fulltime >= ?', $datetime_start)
                ->andWhere('fulltime < ?', $datetime_end)
                ->andWhere('publish = ?', 1)
                ->orderBy('fulltime asc')
                ->execute();
        $this->pager->init();

        $this->onePrePage = 1 + ( $this->pager->getPage() - 1 ) * 10;
    }

}
?>
