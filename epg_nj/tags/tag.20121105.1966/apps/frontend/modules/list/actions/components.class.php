<?php

class listComponents extends sfComponents {

    /**
     * 页面侧边栏热播
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeHot_boardvideo(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
        $this->tag = $request->getParameter('type');
        $this->hot_boardvideo = $wikiRecommendRepo->getWikiByTag($this->tag, 10);
    }
}
?>
