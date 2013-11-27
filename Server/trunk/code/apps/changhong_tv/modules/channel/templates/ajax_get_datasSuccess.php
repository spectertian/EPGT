<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$root_dir = $sf_request->getRelativeUrlRoot().'/public/';

$ranking        = array(
                        //数组开始 -- 科教
                        'kejiao'=>array(
                            'ranking-title' => '本周节目热播榜',
                            'drama-tips' => '今日全部科教节目',
                            'cover'=>array(
                                        'src'=>$root_dir.'kejiao_01.jpg',
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'走近科学（精编版）',
                                        'channel'=>'CCTV1',
                                    ),
                                    1 => array(
                                        'title'=>'百科探秘',
                                        'channel'=>'CCTV10',
                                    ),
                                    2 => array(
                                        'title'=>'人物聚焦',
                                        'channel'=>'CCTV9',
                                    ),
                                    3 => array(
                                        'title'=>'大家看法',
                                        'channel'=>'CCTV12',
                                    ),
                                    4 => array(
                                        'title'=>'科技之光',
                                        'channel'=>'CCTV11',
                                    ),
                                    5 => array(
                                        'title'=>'科学世界',
                                        'channel'=>'探索发现',
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'kejiao_02.jpg',
                                        'title'=>'探索夏威夷',
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'kejiao_03.jpg',
                                        'title'=>'雌雄争霸战',
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'kejiao_04.jpg',
                                        'title'=>'BBC 日月星宿',
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'kejiao_05.jpg',
                                        'title'=>'逐鹿非洲',
                                        'cat'=>'科教/自然',
                                        'producer'=>'绿色空间',
                                        'count'=>'9.1',
                                    ),
                                   1 => array(
                                        'title'=>'科学世界',
                                    ),
                                   2 => array(
                                        'title'=>'子午书简',
                                    ),
                                   3 => array(
                                        'title'=>'道德观察',
                                    ),
                                   4 => array(
                                        'title'=>'百家讲坛',
                                    ),
                                   5 => array(
                                        'title'=>'人与自然',
                                    ),
                                   6 => array(
                                        'title'=>'科技博览',
                                    ),
                                   7 => array(
                                        'title'=>'探索·发现',
                                    ),
                                   8 => array(
                                        'title'=>'科技之光',
                                    ),
                            ),
                        ),//数组结尾
                        
                        //数组开始 --- 动漫
                        'anime'=>array(
                            'ranking-title' => '本周动画热播榜',
                            'drama-tips' => '今日全部动画片',
                            'cover'=>array(
                                        'src'=>$root_dir.'donghua_01.jpg',
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'美猴王',
                                        'channel'=>'CCTV1',
                                    ),
                                    1 => array(
                                        'title'=>'猫和老鼠',
                                        'channel'=>'CCTV2',
                                    ),
                                    2 => array(
                                        'title'=>'开心果',
                                        'channel'=>'CCTV1',
                                    ),
                                    3 => array(
                                        'title'=>'大菲鹏',
                                        'channel'=>'CCTV2',
                                    ),
                                    4 => array(
                                        'title'=>'唐老鸭从军记',
                                        'channel'=>'CCTV1',
                                    ),
                                    5 => array(
                                        'title'=>'变形金刚',
                                        'channel'=>'上海综合',
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'donghua_02.jpg',
                                        'title'=>'WALL•E',
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'donghua_03.jpg',
                                        'title'=>'飞屋环游记',
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'donghua_04.jpg',
                                        'title'=>'圣诞营救计划',
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'donghua_05.jpg',
                                        'title'=>'功夫熊猫',
                                        'cat'=>'卡通/动画',
                                        'producer'=>'约翰史蒂',
                                        'count'=>'8.3',
                                    ),
                                   1 => array(
                                        'title'=>'铠甲勇士',
                                    ),
                                   2 => array(
                                        'title'=>'果宝特攻',
                                    ),
                                   3 => array(
                                        'title'=>'喜羊羊与灰太狼',
                                    ),
                                   4 => array(
                                        'title'=>'电击小子',
                                    ),
                                   5 => array(
                                        'title'=>'秦时明月',
                                    ),
                                   6 => array(
                                        'title'=>'我叫MT',
                                    ),
                                   7 => array(
                                        'title'=>'小破孩',
                                    ),
                                   8 => array(
                                        'title'=>'海宝来了',
                                    ),
                            ),
                        ),//数组结尾
                        
                        //数组开始 --- 电影
                        'film'=>array(
                            'ranking-title' => '本周电影热播榜',
                            'drama-tips' => '今日全部电影',
                            'cover'=>array(
                                        'src'=>$root_dir.'film_01.jpg',
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'嘻游记',
                                        'channel'=>'CCTV1',
                                    ),
                                    1 => array(
                                        'title'=>'我的雷人男友',
                                        'channel'=>'CCTV2',
                                    ),
                                    2 => array(
                                        'title'=>'杜拉拉升职记',
                                        'channel'=>'CCTV8',
                                    ),
                                    3 => array(
                                        'title'=>'唐伯虎点秋香',
                                        'channel'=>'BTV',
                                    ),
                                    4 => array(
                                        'title'=>'全城戒备',
                                        'channel'=>'湖南卫视',
                                    ),
                                    5 => array(
                                        'title'=>'人在囧途',
                                        'channel'=>'上海综合',
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'film_02.jpg',
                                        'title'=>'无人驾驶',
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'film_03.jpg',
                                        'title'=>'画皮',
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'film_04.jpg',
                                        'title'=>'色情男女',
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'film_05.jpg',
                                        'title'=>'全城戒备',
                                        'cat'=>'爱情/动作',
                                        'producer'=>'陈木胜',
                                        'count'=>'8.6',
                                    ),
                                   1 => array(
                                        'title'=>'大灌篮',
                                    ),
                                   2 => array(
                                        'title'=>'爱的练习发声',
                                    ),
                                   3 => array(
                                        'title'=>'唐山大地震',
                                    ),
                                   4 => array(
                                        'title'=>'无人驾驶',
                                    ),
                                   5 => array(
                                        'title'=>'赤裸特工',
                                    ),
                                   6 => array(
                                        'title'=>'决战杀马镇',
                                    ),
                                   7 => array(
                                        'title'=>'人在囧途',
                                    ),
                                   8 => array(
                                        'title'=>'功夫梦',
                                    ),
                            ),
                        ),//数组结尾

                        //数组开始 --- 娱乐
                        'ent'=>array(
                            'ranking-title' => '本周节目热播榜',
                            'drama-tips' => '今日全部综艺节目',
                            'cover'=>array(
                                        'src'=>$root_dir.'zongyi_01.jpg',
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'天天向上',
                                        'channel'=>'湖南卫视',
                                    ),
                                    1 => array(
                                        'title'=>'中国达人秀',
                                        'channel'=>'东方卫视',
                                    ),
                                    2 => array(
                                        'title'=>'幸福魔方',
                                        'channel'=>'CCTV1',
                                    ),
                                    3 => array(
                                        'title'=>'娱乐百分百',
                                        'channel'=>'CCTV3',
                                    ),
                                    4 => array(
                                        'title'=>'越策越开心',
                                        'channel'=>'湖南卫视',
                                    ),
                                    5 => array(
                                        'title'=>'幸福晚点名',
                                        'channel'=>'江苏卫视',
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'zongyi_02.jpg',
                                        'title'=>'我们约会吧',
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'zongyi_03.jpg',
                                        'title'=>'开心100',
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'zongyi_04.jpg',
                                        'title'=>'非诚勿扰',
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'zongyi_05.jpg',
                                        'title'=>'爱唱才会赢',
                                        'cat'=>'娱乐/综艺',
                                        'producer'=>'汪涵',
                                        'count'=>'8.7',
                                    ),
                                   1 => array(
                                        'title'=>'我爱记歌词',
                                    ),
                                   2 => array(
                                        'title'=>'相亲才会赢',
                                    ),
                                   3 => array(
                                        'title'=>'周末星派对',
                                    ),
                                   4 => array(
                                        'title'=>'时刻准备着',
                                    ),
                                   5 => array(
                                        'title'=>'龙门阵',
                                    ),
                                   6 => array(
                                        'title'=>'天天向上',
                                    ),
                                   7 => array(
                                        'title'=>'快乐大本营',
                                    ),
                                   8 => array(
                                        'title'=>'中国达人秀',
                                    ),
                            ),
                        ),//数组结尾

                        //  周2周3 10：00-11：00
                        //数组开始 -- 热播剧集
                        'vod2'=>array(
                            'ranking-title' => '本周电视剧热播榜',
                            'drama-tips' => '今日全部电视剧',
                            'cover'=>array(
                                        'src'=>$root_dir.'高粱红了COVER.gif',
                                        'wiki_id'=>52086,
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'传说',
                                        'channel'=>'CCTV-1',
                                        'wiki_id'=>52699,
                                    ),
                                    1 => array(
                                        'title'=>'暖秋',
                                        'channel'=>'山东卫视',
                                        'wiki_id'=>51294,
                                    ),
                                    2 => array(
                                        'title'=>'黎明之前',
                                        'channel'=>'北京卫视',
                                        'wiki_id'=>52788,
                                    ),
                                    3 => array(
                                        'title'=>'内线',
                                        'channel'=>'云南卫视',
                                        'wiki_id'=>51062,
                                    ),
                                    4 => array(
                                        'title'=>'愤怒的蝴蝶',
                                        'channel'=>'西藏卫视',
                                        'wiki_id'=>857,
                                    ),
                                    5 => array(
                                        'title'=>'最后的99天',
                                        'channel'=>'深圳电视剧频道',
                                        'wiki_id'=>50577,
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'笑笑茶楼封面.gif',
                                        'title'=>'笑笑茶楼',
                                        'wiki_id'=>48524,
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'我们的队伍向太阳.gif',
                                        'title'=>'我们的队伍向太阳',
                                        'wiki_id'=>52833,
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'雪域天路.gif',
                                        'title'=>'雪域天路',
                                        'wiki_id'=>52050,
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'雪豹COVER5.gif',
                                        'title'=>'雪豹',
                                        'cat'=>'战争、历史',
                                        'producer'=>'陈皓威',
                                        'count'=>'',
                                        'wiki_id'=>52688,
                                    ),
                                   1 => array(
                                        'title'=>'高粱红了',
                                        'wiki_id'=>52086,
                                    ),
                                   2 => array(
                                        'title'=>'夫妻一场',
                                        'wiki_id'=>51836,
                                    ),
                                   3 => array(
                                        'title'=>'内线',
                                        'wiki_id'=>51062,
                                    ),
                                   4 => array(
                                        'title'=>'来不及说爱你',
                                        'wiki_id'=>52517,
                                    ),
                                   5 => array(
                                        'title'=>'牵挂',
                                        'wiki_id'=>52805,
                                    ),
                                   6 => array(
                                        'title'=>'黎明之前',
                                        'wiki_id'=>52788,
                                    ),
                                   7 => array(
                                        'title'=>'闪婚',
                                        'wiki_id'=>52583,
                                    ),
                                   8 => array(
                                        'title'=>'暖秋',
                                        'wiki_id'=>51294,
                                    ),
                            ),
                        ),//数组结尾

                        //  周2周3 14：00-15：00
                        //数组开始 -- 热播剧集
                        'vod3'=>array(
                            'ranking-title' => '本周电视剧热播榜',
                            'drama-tips' => '今日全部电视剧',
                            'cover'=>array(
                                        'src'=>$root_dir.'_GetLockPic.php.gif',
                                        'wiki_id'=>53,
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'青蛇外传',
                                        'channel'=>'CCTV-1',
                                        'wiki_id'=>84,
                                    ),
                                    1 => array(
                                        'title'=>'红楼梦',
                                        'channel'=>'北京卫视',
                                        'wiki_id'=>4,
                                    ),
                                    2 => array(
                                        'title'=>'天使之翼',
                                        'channel'=>'辽宁卫视',
                                        'wiki_id'=>50,
                                    ),
                                    3 => array(
                                        'title'=>'阳关光灿烂周三强',
                                        'channel'=>'河南卫视',
                                        'wiki_id'=>9,
                                    ),
                                    4 => array(
                                        'title'=>'深宅',
                                        'channel'=>'深圳都市频道',
                                        'wiki_id'=>88,
                                    ),
                                    5 => array(
                                        'title'=>'内线',
                                        'channel'=>'贵州卫视',
                                        'wiki_id'=>35,
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'下一站幸福.gif',
                                        'title'=>'下一站幸福',
                                        'wiki_id'=>26,
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'老大的幸福生活封面.gif',
                                        'title'=>'老大的幸福',
                                        'wiki_id'=>83,
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'爱拼才会赢.gif',
                                        'title'=>'爱拼才会赢',
                                        'wiki_id'=>55,
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'雪豹COVER5.gif',
                                        'title'=>'雪豹',
                                        'cat'=>'战争、历史',
                                        'producer'=>'陈皓威',
                                        'count'=>'',
                                        'wiki_id'=>90,
                                    ),
                                   1 => array(
                                        'title'=>'高粱红了',
                                        'wiki_id'=>74,
                                    ),
                                   2 => array(
                                        'title'=>'夫妻一场',
                                        'wiki_id'=>75,
                                    ),
                                   3 => array(
                                        'title'=>'内线',
                                        'wiki_id'=>35,
                                    ),
                                   4 => array(
                                        'title'=>'来不及说爱你',
                                        'wiki_id'=>78,
                                    ),
                                   5 => array(
                                        'title'=>'牵挂',
                                        'wiki_id'=>79,
                                    ),
                                   6 => array(
                                        'title'=>'黎明之前',
                                        'wiki_id'=>80,
                                    ),
                                   7 => array(
                                        'title'=>'闪婚',
                                        'wiki_id'=>81,
                                    ),
                                   8 => array(
                                        'title'=>'暖秋',
                                        'wiki_id'=>82,
                                    ),
                            ),
                        ),//数组结尾

                        //数组开始 --- 财经理财
                        'finance'=>array(
                            'ranking-title' => '本周节目热播榜',
                            'drama-tips' => '今日全部节目',
                            'cover'=>array(
                                        'src'=>$root_dir.'finance_01.jpg',
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'环球财经连线',
                                        'channel'=>'CCTV2',
                                    ),
                                    1 => array(
                                        'title'=>'第一房产',
                                        'channel'=>'CCTV1',
                                    ),
                                    2 => array(
                                        'title'=>'天下汽车',
                                        'channel'=>'上海卫视',
                                    ),
                                    3 => array(
                                        'title'=>'财经点对点',
                                        'channel'=>'CCTV9',
                                    ),
                                    4 => array(
                                        'title'=>'石评大财经',
                                        'channel'=>'东方财经',
                                    ),
                                    5 => array(
                                        'title'=>'证券情报站',
                                        'channel'=>'凤凰卫视',
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'finance_02.jpg',
                                        'title'=>'金融超越战',
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'finance_03.jpg',
                                        'title'=>'华尔街完美案例',
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'finance_04.jpg',
                                        'title'=>'港股直通车',
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'finance_05.jpg',
                                        'title'=>'中国股市报道',
                                        'cat'=>'财经/股市',
                                        'producer'=>'张根锡',
                                        'count'=>'8.0',
                                    ),
                                   1 => array(
                                        'title'=>'财经小词典 ',
                                    ),
                                   2 => array(
                                        'title'=>'港股直通车',
                                    ),
                                   3 => array(
                                        'title'=>'经济半小时',
                                    ),
                                   4 => array(
                                        'title'=>'中国财经报道',
                                    ),
                                   5 => array(
                                        'title'=>'证券时间',
                                    ),
                                   6 => array(
                                        'title'=>'经济信息联播',
                                    ),
                                   7 => array(
                                        'title'=>'证券时间',
                                    ),
                                   8 => array(
                                        'title'=>'你好股民',
                                    ),
                            ),
                        ),//数组结尾

                        //数组开始 --- 社会新闻
                        'news'=>array(
                            'ranking-title' => '本周新闻节目热播榜',
                            'drama-tips' => '今日全部新闻节目',
                            'cover'=>array(
                                        'src'=>$root_dir.'news_01.jpg',
                            ),
                            'onair'=>array(
                                    0 => array(
                                        'title'=>'新闻30分',
                                        'channel'=>'CCTV1',
                                    ),
                                    1 => array(
                                        'title'=>'东方时空',
                                        'channel'=>'CCTV3',
                                    ),
                                    2 => array(
                                        'title'=>'朝闻天下',
                                        'channel'=>'CCTV10',
                                    ),
                                    3 => array(
                                        'title'=>'新闻社区',
                                        'channel'=>'CCTV4',
                                    ),
                                    4 => array(
                                        'title'=>'新闻会客厅',
                                        'channel'=>'CCTV2',
                                    ),
                                    5 => array(
                                        'title'=>'法制在线',
                                        'channel'=>'CCTV8',
                                    ),
                            ),
                            'items'=> array(
                                    0 => array(
                                        'src'=>$root_dir.'news_02.jpg',
                                        'title'=>'中央新闻联播',
                                    ),
                                    1 => array(
                                        'src'=>$root_dir.'news_03.jpg',
                                        'title'=>'第一时间',
                                    ),
                                    2 => array(
                                        'src'=>$root_dir.'news_04.jpg',
                                        'title'=>'中国新闻',
                                    ),
                            ),
                            'ranking'=>array(
                                   0 => array(
                                        'src'=>$root_dir.'news_05.jpg',
                                        'title'=>'夕阳红',
                                        'cat'=>'社会/新闻',
                                        'producer'=>'张悦/黄薇',
                                        'count'=>'7.9',
                                    ),
                                   1 => array(
                                        'title'=>'艺术人生 ',
                                    ),
                                   2 => array(
                                        'title'=>'实话实说',
                                    ),
                                   3 => array(
                                        'title'=>'海峡两岸',
                                    ),
                                   4 => array(
                                        'title'=>'中国新闻',
                                    ),
                                   5 => array(
                                        'title'=>'中国文艺',
                                    ),
                                   6 => array(
                                        'title'=>'中国法制报道',
                                    ),
                                   7 => array(
                                        'title'=>'晚间新闻',
                                    ),
                                   8 => array(
                                        'title'=>'第一时间',
                                    ),
                            ),
                        ),//数组结尾
                    );

if(is_null($pager))
{
    echo '';
}else{
    echo json_encode($ranking[$pager]);
}
?>
