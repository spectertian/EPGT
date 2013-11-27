<?php
class Recommand
{
    /**
     * 获取运营中心的点播推荐
     * @author lifucang
     */
    static function getCenterVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $user_id = $user_id ? $user_id."_0" : "99666611230068607_0";
        $url=sfConfig::get('app_recommend_centerUrl');
        $arr=array(
            'Series' => 'Category6%3D%27%E7%94%B5%E8%A7%86%E5%89%A7%27',
            'Movie' => 'Category6%3D%27%E7%94%B5%E5%BD%B1%27',
            'Sports' => 'Category6%3D%27%E4%BD%93%E8%82%B2%27',
            'Entertainment' => 'Category6%3D%27%E7%BB%BC%E8%89%BA%27',
            'Cartoon' => 'Category6%3D%27%E5%8A%A8%E6%BC%AB%27',
            'Culture' => 'Category6%3D%27%E6%96%87%E5%8C%96%27',
            'News' => 'Category6%3D%27%E6%96%B0%E9%97%BB%E6%97%B6%E7%A7%BB%27',
            'vod' => '',   //评分最高，智能导航新上线用这个
            'like' => '',  //猜你喜欢，智能门户用这个
            'hot' => ''    //最热，暂时没用
        );
        $filter = $arr[$type];
        if($type=='vod'){
            $recomUrl = $url.'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.toprating.v1&ctype=vod&postertype=1&count='.$count.'&uid='.$user_id.'&lang=zh&urltype=1&user_weight=0.4&optr_weight=0.6&backurl='.$backurl;
        }elseif($type=='like'){
            $recomUrl = $url.'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count='.$count.'&uid='.$user_id.'&lang=zh&urltype=1&alg=CF&backurl='.$backurl;
        }elseif($type=='hot'){
            $recomUrl = $url.'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.hotitem.v1&ctype=vod&postertype=1&count='.$count.'&uid='.$user_id.'&lang=zh&urltype=1&period=monthly&filter=m_index%3D1&backurl='.$backurl;
        }else{
            $recomUrl = $url.'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count='.$count.'&uid='.$user_id.'&filter='.$filter.'&lang=zh&urltype=1&alg=RK&backurl='.$backurl;
        }
        $recomTxt = Common::get_url_content($recomUrl);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson)
                $wikis = $recomJson['recommend'];
        }
        return $wikis;
    }
    /**
     * 获取技术部的点播推荐。
     * @author lifucang
     * @date 2013-08-21
     */
    static function getTongzhouVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        if($type=='vod'){
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.hotitem.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&backurl='.$backurl;
        }elseif($type=='like'){
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&backurl='.$backurl;
        }else{
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&genre='.$type.'&backurl='.$backurl;
        }    
        $recomTxt = Common::get_url_content($recomUrl);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson){
                $wikis = $recomJson['recommend'][0]['recommand'];    
            }
        }
        return $wikis;
    }  
}