<?php

class programComponents extends sfComponents {
    public function executeRecommend_program( sfWebRequest $request ) {
        $channels = Doctrine::getTable('Channel')->createQuery()->whereIn('type',array('cctv','tv'))->execute();
        $mongo = $this->getMondongo();
        $program_mongo = $mongo->getRepository('program');
        $this->tvplays = $program_mongo->getLiveProgramByTag('电视剧', $channels,12);
        $this->movies = $program_mongo->getLiveProgramByTag('电影', $channels,12);
        $this->sports = $program_mongo->getLiveProgramByTag('体育', $channels,12);
        $this->ents = $program_mongo->getLiveProgramByTag('娱乐', $channels,12);
        $this->childrens = $program_mongo->getLiveProgramByTag('少儿', $channels,12);
        $this->edus = $program_mongo->getLiveProgramByTag('科教', $channels,12);
        $this->finances = $program_mongo->getLiveProgramByTag('财经', $channels,12);
        $this->generals = $program_mongo->getLiveProgramByTag('综合', $channels,12);
    }
}
?>
