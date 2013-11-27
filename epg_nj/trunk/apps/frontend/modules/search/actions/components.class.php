<?php

class searchComponents extends sfComponents {

    /**
     * 页面侧边栏热播
     * @param sfWebRequest $request
     */
    public function executeHot_boardvideo(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
	$modelArray = array("actor","teleplay","film","television");
        $this->hot_boardvideo = $wikiRecommendRepo->getWikiByModel("film", 10);

    }
}
?>
