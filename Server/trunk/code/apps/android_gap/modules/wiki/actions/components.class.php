<?php

class wikiComponents extends sfComponents {
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
            
            $this->movies = $wiki_repository->getWikiByTags($wiki, $tags, 3);
        } else {
            $this->movies = null;
        }
    }
}
?>
