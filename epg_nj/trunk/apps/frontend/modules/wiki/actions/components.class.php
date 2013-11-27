<?php

class wikiComponents extends sfComponents {
    public function executeAdd_news( sfWebRequest $request ) {
        $this->model = array(
          'teleplay'=>'电视剧',
          'film'=>'电影',
          'television'=>'栏目',
          'actor'=>'艺人',
          'footerball_player'=>'足球队员',
          'basketball_player'=>'篮球队员',
          'footerball_team'=>'足球队',
          'basketball_team'=>'篮球队',
          'nba_team'=>'NBA'
        );
        $mongo = $this->getMondongo();
        $wiki_mongo = $mongo->getRepository('Wiki');
        $this->wikis = $wiki_mongo->getNewsWiki();
    }

    /**
     * 维基页面侧边栏热播
     * @param sfWebRequest $request
     */
    public function executeHot_broadcast(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
        $this->wiki_slug = $request->getParameter('slug');
        $wiki = $wiki_repository->getWikiBySlug($this->wiki_slug);
        switch ($wiki->getModel()) {
            case 'teleplay':
              $this->model = "电视剧热播榜";
              break;
            case 'film':
              $this->model = "电影热播榜";
              break;
            case 'television':
              $this->model = "综艺热播榜";
              break;
        }

        $this->hotBroadcast = $wikiRecommendRepo->getWikiByModel($wiki->getModel(), 10);
    }

    /**
     * 维基页面侧边栏 相关影片数据
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeRelated_movies(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $slug = $request->getParameter('slug');
        $wiki = $wiki_repository->getWikiBySlug($slug);
        $tags = $wiki->getTags();
        
        switch ($wiki->getModel()) {
            case 'teleplay':
              $this->modeltext = "相关电视剧";
              break;
            case 'film':
              $this->modeltext = "相关影片";
              break;
            case 'television':
              $this->modeltext = "相关综艺栏目";
              break;
        }
        
        if (!empty($tags)) {
            if (count($tags) > 1) {
                array_shift($tags);
                shuffle($tags);                     //打乱标签
                $tags = array_slice($tags, 0, 3);  //取三个相关的标签
            }
            
            $this->movies = $wiki_repository->getWikiByTags($wiki, $tags, 6);
        } else {
            $this->movies = null;
        }
    }
}
?>
