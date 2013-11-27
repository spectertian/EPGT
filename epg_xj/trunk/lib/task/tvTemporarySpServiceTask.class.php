<?php
/**
 * SpService表 数据导入
 * 临时任务
 * @author tianzhongsheng-ex@huan.tv 2013-09-04
 */
class tvTemporarySpServiceTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'TemporarySpService';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:TemporarySpService|INFO] task does things.
Call it with:

  [php tv:TemporarySpService|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
		$databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

		$spService_repository = $this->getMondongo()->getRepository('SpService');
     	$options['query'] = array('tvsou_title'=>'');
     	$spServices = $spService_repository->find($options);
		$date = $this->getDates();
		
		foreach($date as $k => $v)
		{
			$spService = new SpService();
			$spService->setSpCode('bjctv');
			$spService->setName($v);
			$spService->setLogicNumber($k);
			echo $k."\t".$v."\t";
			$channels = Doctrine::getTable('Channel')->createQuery('j')->where('j.name=?',$v)->orderBy('j.editor_update')->fetchArray();
			$channels = $channels[0];
			if(count($channels) > 0 )
			{
				$spService->setChannelCode($channels['code']);
				echo $channels['name']."\t".$channels['code']."\n";
				

			}
			$spService->save();
			echo "\n";

		}

    }
    
    public function getDates()
    {
    	$date = array (
				1 => '中央电视台-1综合',
				2 => '中央电视台-2财经',
				3 => '中央电视台-3综艺',
				4 => '中央电视台-4中文国际',
				5 => '中央电视台-5体育',
				6 => '中央电视台-6电影',
				7 => '中央电视台-7军事农业',
				8 => '中央电视台-8电视剧',
				9 => '中央电视台-9纪录',
				10 => '中央电视台-10科教',
				11 => '中央电视台-11戏曲',
				12 => '中央电视台-12社会与法',
				13 => '中央电视台-新闻',
				14 => '中央电视台-少儿',
				15 => '中央电视台-音乐',
				16 => 'CCTV-NEWS',
				21 => 'BTV北京',
				22 => 'BTV文艺',
				23 => 'BTV科教',
				24 => 'BTV影视',
				25 => 'BTV财经',
				26 => 'BTV体育',
				27 => 'BTV生活',
				28 => 'BTV青年',
				29 => 'BTV新闻',
				30 => 'BTV卡酷',
				31 => '湖南卫视',
				32 => '江苏卫视',
				33 => '浙江卫视',
				34 => '东方卫视',
				35 => '青海卫视',
				36 => '云南卫视',
				37 => '福建东南卫视',
				38 => '辽宁卫视',
				39 => '中国教育电视台-1',
				40 => '中国教育电视台-3',
				41 => '山东教育电视台',
				42 => '重庆卫视',
				43 => '黑龙江卫视',
				44 => '旅游卫视',
				45 => '贵州卫视',
				46 => '宁夏卫视',
				47 => '江西卫视',
				48 => '安徽卫视',
				49 => '广西卫视',
				50 => '河南卫视',
				51 => '广东卫视',
				52 => '吉林卫视',
				53 => '山东卫视',
				54 => '湖北卫视',
				55 => '陕西卫视',
				56 => '四川卫视',
				57 => '天津卫视',
				58 => '甘肃卫视',
				59 => '西藏卫视',
				60 => '河北卫视',
				61 => '山西卫视',
				62 => '内蒙古卫视',
				63 => '新疆卫视',
				64 => '深圳卫视',
				65 => '金鹰卡通',
				75 => '置业',
				76 => '东方财经',
				77 => '全纪实',
				78 => '魅力音乐',
				79 => '生活时尚',
				80 => '兵团卫视',
				81 => '七彩剧场',
				82 => '炫动卡通',
				83 => '优漫卡通',
				84 => '空中课堂',
				85 => '嘉佳卡通',
				101 => '四海钓鱼',
				102 => '动感音乐',
				103 => '车迷',
				104 => '新娱乐',
				105 => '环球旅游',
				106 => '时代家居',
				107 => '时代出行',
				108 => '时代美食',
				109 => '时代风尚',
				110 => '央广健康',
				111 => '收藏天下',
				112 => '快乐宠物',
				113 => '家庭理财',
				114 => '职业指南',
				115 => '新科动漫',
				121 => '读书',
				122 => '法律服务',
				125 => '百姓健康',
				141 => '劲爆体育',
				142 => '游戏风云',
				143 => '动漫秀场',
				144 => '极速汽车',
				145 => '法治天地',
				146 => '欢笑剧场',
				147 => '金色频道',
				151 => 'CHC动作电影',
				152 => '新动漫',
				153 => '中华美食',
				161 => 'CHC家庭影院',
				162 => '都市剧场',
				163 => '优优宝贝',
				164 => '考试在线',
				165 => '弈坛春秋',
				166 => '证券资讯',
				167 => '电子体育',
				168 => '欧洲足球',
				169 => '高尔夫',
				170 => '高尔夫·网球',
				171 => '老故事',
				172 => '中国气象',
				173 => '书画频道',
				201 => '第一剧场',
				203 => '风云剧场',
				204 => '风云音乐',
				205 => '风云足球',
				206 => '怀旧剧场',
				207 => '央视文化精品',
				208 => '世界地理',
				209 => '国防军事',
				214 => '环球奇观',
				218 => '早期教育',
				219 => '靓妆',
				220 => '留学世界',
				222 => '天元围棋',
				223 => '游戏竞技',
				224 => '孕育指南',
				227 => '先锋纪录',
				228 => 'DV指南',
				601 => 'CCTV-1高清',
				603 => 'CCTV-3综艺高清',
				605 => 'CCTV-5体育高清',
				606 => 'CCTV-6电影高清',
				608 => 'CCTV-8电视剧高清',
				617 => 'cctv-5+高清',
				618 => 'CHC高清电影',
				621 => 'BTV北京卫视高清',
				622 => 'BTV文艺高清',
				626 => 'BTV体育高清',
				631 => '湖南卫视高清',
				632 => '江苏卫视高清',
				633 => '浙江卫视高清',
				634 => '东方卫视高清',
				643 => '黑龙江卫视高清',
				651 => '广东卫视高清',
				653 => '山东卫视高清',
				654 => '湖北卫视高清',
				657 => '天津卫视高清',
				665 => '深圳卫视高清',
				701 => '3D试验频道',
				20 => 'BTV纪实高清',
				801 => '朝阳自办',
				802 => '海淀自办',
				803 => '丰台自办',
				804 => '石景山自办',
			);
		return $date;
    }
}
