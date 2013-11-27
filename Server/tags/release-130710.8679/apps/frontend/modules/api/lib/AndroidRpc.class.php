<?php
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'GetFileUrl'));

class AndroidRpc {
    
    /**
     * xml-rpc 演示、测试， hello
     * @return XML
     */
    public function sayHello() {
        return 'hello';
    }

    /**
     * 获取省份列表
     * @return array
     * @author pjl
     */
    public function getProvinceList() {
        $all_province = array(
            "北京", "上海", "黑龙江", "吉林", "辽宁",
            "天津", "安徽", "江苏", "浙江", "陕西",
            "湖北", "湖南", "甘肃", "四川", "山东",
            "福建", "河南", "重庆", "云南", "河北",
            "江西", "山西", "贵州", "广西", "内蒙古",
            "宁夏", "青海", "新疆", "海南", "西藏",
            "香港", "澳门", "台湾"
        );

        $results = array();
        foreach($all_province as $name) {
            $result = array(
                'name' => $name,
                'url' => 'http://android.5i.tv' . url_for('channel/other?province=' . urlencode($name))
            );
            $results[] = $result;
            unset($result);
        }

        return $results;
    }

    /**
     * AndroidRPC 推荐接口读取后台维基推荐数据
     * @return <array>
     * @author luren
     */
    public function getRecommend() {
        $mongo = sfContext::getInstance()->get("mondongo");
        $wikiRecommedRepes = $mongo->getRepository('WikiRecommend');
        $teleplayWikis = $wikiRecommedRepes->getWikiByModel('teleplay', 4);
        $filmWikis = $wikiRecommedRepes->getWikiByModel('film', 4);
        $results = array();

        if (!is_null($teleplayWikis)) {
            foreach ($teleplayWikis as $wiki) {
                $item = array(
                       'title' => $wiki->getWiki()->getTitle(),
                       'cover' => file_url($wiki->getWiki()->getCover()),
                       'url'   => 'http://android.5i.tv' .url_for('wiki/show?id='.$wiki->getWikiId())
                    );
                $results[] = $item;
                unset($item);
            }
        }

        if (!is_null($filmWikis)) {
            foreach ($filmWikis as $wiki) {
                $item = array(
                       'title' => $wiki->getWiki()->getTitle(),
                       'cover' => file_url($wiki->getWiki()->getCover()),
                       'url'   => 'http://android.5i.tv' .url_for('wiki/show?id='.$wiki->getWikiId())
                    );
                $results[] = $item;
                unset($item);
            }
        }
        
	shuffle($results);
        return $results;
    }

    /**
     * AndroidRPC 热播接口 读取电视剧、电影、栏目的维基推荐数据
     * @return <array>
     * @author luren
     */
    public function getHotplay() {
        $mongo = sfContext::getInstance()->get("mondongo");
        $wikiRecommedRepes = $mongo->getRepository('WikiRecommend');
        $tags = array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
        $results = array();

        foreach($tags as $tag) {
            $wikis = $wikiRecommedRepes->getWikiByTag($tag, 5);
            $results[] = array(
                        'title' => $tag,
                        'data'  =>  $this->paserWikiToArray($wikis)
                    );
        }

        return $results;
    }

    /**
     * 分析维基数据转换成RPC返回数据数组
     * @param <type> $wiks
     * @return <type>
     */
    private function paserWikiToArray($wikis) {
        $result = array();

        if (!is_null($wikis)) {
            foreach ($wikis as $wiki) {
                $item = array(
                       'title' => $wiki->getWiki()->getTitle(),
                       'cover' => file_url($wiki->getWiki()->getCover()),
                       'url'   => 'http://android.5i.tv' .url_for('wiki/show?id='.$wiki->getWikiId()),
                       'description' => $wiki->getWiki()->getHtmlCache(80)
                    );
                $result[] = $item;
                unset($item);
            }
        }

        return $result;
    }   
}
