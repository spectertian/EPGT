<?php

/**
 * Repository of WikiPlay document.
 */
class WikiPlayRepository extends \BaseWikiPlayRepository
{
    /**
     * 根据 wiki_id, date, province 判断是否存在该记录
     * @param <int> $wiki_id
     * @param <date> $date
     * @param <string> $province
     * @author luren
     */
    public function checkWikiPlayIsExist($wiki_id, $date, $province) {
        $result = $this->findOne(
                array(
                    'query' => array(
                            'wiki_id' => $wiki_id,
                            'date' => $date,
                            'province' => $province
                        )
                    )
                );

        return (!is_null($result)) ? true : false;
    }

     /**
     * 根据 tag 和 date 获取 wikiPlay
     * @param <type> tag
     * @param <type> $date
     * @author luren
     */
    public function getWikiPlayByTagAndDate($tag, $date,$limit = 0) {
        return $this->find(
                array(
                    'query' => array(
                            'tags' => $tag,
                            'date' => $date
                        ),
                    'limit' => $limit
                    )
                );
    }

    /**
     * 获取自定义日期区间wikiPlay数据
     * @param <type> $date
     * @param <integer> $limit
     * @return luren
     */
    public function getWikiPlayByDate($date, $limit = 0, $skip = 0) {
        return $this->find(
                    array(
                        'query' => array(
                            'date' => $date
                        ),
                        'skip' => $skip,
                        'limit' => $limit
                    )
                );
    }

    /**
     * 根据 tag, date, province 获取相关的 wikiPlay
     * @param <string> $tag
     * @param <date> $date
     * @param <string> $province
     * @param <int> $skip
     * @param <int> $limit
     * @return <type>
     * @author luren
     */
    public function getWikiPlays($tag, $date, $province, $skip = 0, $limit = 0) {
        if($tag=='all'){
            return $this->find(
                    array(
                            'query' => array(
                                    'date' => $date,
                                    'province' => $province
                                ),
                            'skip'  => $skip,
                            'limit' => $limit
                        )
                    );             
        }else{
            return $this->find(
                    array(
                            'query' => array(
                                    'tags' => $tag,
                                    'date' => $date,
                                    'province' => $province
                                ),
                            'skip'  => $skip,
                            'limit' => $limit
                        )
                    );         
        }

    }

    /**
     * 获取随机预告节目单 获取的必须大于当期日期
     * @param <date> $date Y-m-d
     * @param <int> $limit
     * @return <type>
     * @author luren
     */
    public function getAdvanceWikiPlay($date, $limit = 6) {
        $max = $this->count(array('date' => array('$gt' => $date))) - $limit;

        if ($max > 0) {
            return $this->find(
                        array(
                            'query' => array(
                                'date' => array('$gt' => $date),
                            ),
                            'skip' => rand(0, $max),
                            'limit' => $limit
                        )
                    );
        }

        return array();
    }
}