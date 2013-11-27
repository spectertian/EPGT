<?php
/**
 * 导出EPG为txt格式给新疆用
 * 放到计划任务中，每1小时执行
 * @author superwen
 */
class xjProgramExportTxtTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'xj';
        $this->name             = 'ProgramExportTxt';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [xj:ProgramExportTxt|INFO] task does things.
Call it with:

  [php symfony xj:ProgramExportTxt|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $programRep = $this->getMondongo()->getRepository('Program');
        
        $conn = ftp_connect("110.173.3.73","1001") or die("FTP服务器连接失败");
        ftp_login($conn,"shanghai-epg","shanghai-epg021") or die("FTP服务器登陆失败");
        echo "FTP connected!\n";
        
        $dateArr = array(0,1,2,3,4,5,6);
        
        $channels = array (
            'CCTV-1' => 'cctv1',
            'CCTV-2' => 'cctv2',
            'CCTV-3' => 'cctv3',
            'CCTV-4' => 'cctv4_asia',
            'CCTV-5' => 'cctv5',
            'CCTV-6' => 'cctv6',
            'CCTV-7' => 'cctv7',
            'CCTV-8' => 'cctv8',
            'CCTV-9' => 'cctv9',
            'CCTV-10' => 'cctv10',
            'CCTV-11' => 'cctv11',
            'CCTV-12' => 'cctv12',
            'CCTV-新闻' => 'cctv_news',
            'CCTV-少儿' => 'cctv_kids',
            'CCTV-音乐' => 'cctv_music',
            '中教1' => 'fcc4eabadaf03c98f7e61018e97c6d03',
            '电视指南' => 'szdianshizhinan',
            '湖南快乐购物' => 'hunanklg',
            '中视购物' => 'zhongshigouwu',
            '辽宁卫视' => '9291c40ec1cec1281638720c74c7245f',
            '浙江卫视' => '590e187a8799b1890175d25ec85ea352',
            '江苏卫视' => '322fa7b66243b8d0edef9d761a42f263',
            '深圳卫视' => '20831bb807a45638cfaf81df1122024d',
            '北京卫视' => '5dfcaefe6e7203df9fbe61ffd64ed1c4',
            '天津卫视' => '5927c7a6dd31f38686fafa073e2e13bc',
            '河北卫视' => 'ef1fce69a9e1b3a587ca734302400107',
            '重庆卫视' => '5731a167d79c432575056c4963dc8049',
            '广东卫视' => 'c8bf387b1824053bdb0423ef806a2227',
            '旅游卫视' => '0d7b5dfe999fc5fd0140863f6e8910a5',
            '贵州卫视' => '5a7d01661b5d9c64293860531374312b',
            '四川卫视' => 'b82fa4086c9a2c9442279efbb80cce31',
            '安徽卫视' => 'antv',
            '湖北卫视' => '55fc65ef82e92d0e1ccb2b3f200a7529',
            '黑龙江卫视' => '1ce026a774dba0d13dc0cef453248fb7',
            '河南卫视' => '2c854868563485135dd486801057dd6e',
            '湖南卫视' => 'c39a7a374d888bce3912df71bcb0d580',
            '东南卫视' => 'fjtv',
            '江西卫视' => '535765a19ab55a12bbf64a1e98ae97dd',
            '宁夏卫视' => 'a09ab19928a6b2bd616f7e2eba1056ee',
            '陕西卫视' => 'eb7330e363ceec8c6895eacc44a1a804',
            '云南卫视' => 'c786da29f0f5cc5973444e3ad49413a6',
            '山东卫视' => '28502a1b6bf5fbe7c6da9241db596237',
            '山西卫视' => '2aeb585ccaca9fa893b0bdfdbc098c7f',
            '上海卫视' => 'dragontv',
            '上海炫动卡通' => '74ca733eddc5c16163210a031f3295db',
            '内蒙卫视(蒙)' => '1cbdd1e125f9a7778d4716592d9e4088',
            '内蒙卫视(汉)' => '03295de404257fa9653b89bf2d0e47ac',
            '金鹰卡通' => '370e3081d7630e9fe35125bd6dab01da',
            '广西卫视' => '5cbb108dbf59f2ae1849ec8d1126d1a5',
            '青海卫视' => '4ec095f1d2564f82341275fff64edb5a',
            '吉林卫视' => '45392a8be644f5b8903838436870c75d',
            '甘肃卫视' => '5ace8ddc54a4151bbcf76e56c8aa582a',
            '江苏优漫卡通' => 'youmanktws',
            '靓妆' => '6a12341152e41576d5107eae44a4fef8',
            '空中课堂' => 'ccde5c5d525542675a7a50c02b4c9778',
            '西藏汉语' => 'feccf21eb7e50753355efdab2d54d9e8',
            'cctv-1(高清)' => 'cctv1gaoqing',
            'cctv-综合（高清）' => 'cctv1gaoqing',
            '北京（高清）' => 'bjweishigaoqing',
            '浙江（高清）' => 'jztv_high',
            '深圳(高清）' => 'shenzhenweishigaoqing',
            '江苏（高清）' => 'jiangsuweishigaoqing',
            '黑龙江（高清）' => 'hljweishigaoqing',
            '天津（高清）' => '5927c7a6dd31f38686fafa073e2e13bc',
            '湖北（高清）' => '55fc65ef82e92d0e1ccb2b3f200a7529',
            '湖南（高清）' => 'c39a7a374d888bce3912df71bcb0d580',
            '广东（高清）' => 'c8bf387b1824053bdb0423ef806a2227',
            '山东（高清）' => '28502a1b6bf5fbe7c6da9241db596237',
            '上海（高清）' => 'dragontv',
            '3D频道' => '3dpindao',
            'CHC高清电影频道' => 'chcgaoqingdianying',
            '新视觉' => 'SITVxinshijue',
            '新视觉-纪实' => 'sitv-jishi',
            '新视觉-剧场' => 'doxjuchang',
            '新视觉-体育' => 'sitv-sports',
            '新视觉-娱乐' => 'sitv-yule',
            '新视觉-电影' => 'sitv-movie',
            '新视觉-旅游' => 'sitv-travel',
            '新视觉-时尚' => 'sitv-fashion',
            '新视觉-贝贝' => 'sitv-baby',
            '新视觉-游戏' => 'sitv-game',
            '测试一' => 'bb8f7378ef7eefeea8ec82cf7e34f173',
            '测试二' => 'b52ed95ecc9995cb7a418061040c740f',
            '测试三' => 'xgyxgjdl',
            '测试四' => 'now-espn',
            '测试五' => 'c73f35112e74ce77a8ecc4ddb5628bf2',
            '测试六' => 'fd5e69184516f4e96a7f4d41e52b3bb0',
            '家庭影院' => 'chcjiatingyingyuan',
            '动作电影' => 'chcdongzuody',
            '第一剧场' => 'diyijuchang',
            '都市剧场' => '8c2c76bce805d11f5ba0266f8a33c65e',
            '风云剧场' => 'fengyunjuchang',
            '怀旧剧场' => 'yangshihuaijiujuchang',
            '欢笑剧场' => '1800444c032205d1443af46a5111fbf1',
            '国防军事' => 'guofangjunshi',
            '劲爆体育' => '2ccef4b3a8b8f1686594ab6a8c3ba802',
            '高尔夫网球' => 'gaoerfuwangqiu',
            '风云足球' => 'fengyunzuqiu',
            '先锋乒羽' => 'xianfengpingyu',
            '四海钓鱼' => 'sihaidiaoyu',
            '天元围棋' => '3d23c7fa7feae2ea2b6e3f7f1359aa7a',
            '武术世界' => 'sitv-wushushijie',
            '世界地理' => 'shijiedili',
            '全纪实' => '2e79089eeb8dfeb99cd21296facd2025',
            '发现之旅' => 'faxianzhilv',
            '环球旅游' => 'huanqiulvyou',
            '先锋记录' => 'xianfengjilu',
            '老故事' => 'cctv-laogushi',
            '东方财经' => 'sitvdfcj',
            '法治天地' => '05d6693c933de13842e71023eee86cdd',
            '风云音乐' => 'fengyunyinyue',
            '魅力音乐' => '2ac392f31cfbacdee4cb042d6bd4ad75',
            '央视精品' => 'yangshijingpin',
            '新娱乐' => 'b0624dfb3bd6bb4f345387d7092793b7',
            '碟市' => 'szpd-dieshi',
            '生活时尚' => 'sitvshss',
            '女性时尚' => 'cctv-nvxingshishang',
            '现代女性' => 'xiandainvxing',
            '时代美食' => 'shidaimeishi',
            '时代家居' => 'szpd-shidaijiaju',
            '英语辅导' => 'yingyufudao',
            '留学世界' => 'liuxueshijie',
            '宝贝家' => 'zscm-baobeijia',
            '孕育指南' => 'yunyuzhinan',
            '早期教育' => 'zaoqijiaoyu',
            '动漫秀场' => '5c8dbe3714f3544285a4c4922e2ed01a',
            '新科动漫' => 'cctv-xinkedongman',
            '游戏风云' => 'a57bb859618877ab8cf2d2abf30b4f55',
            '游戏竞技' => 'youxijingji',
            '央广健康' => '',
            '卫生健康' => '17f79002aa3904b69fc41b463c04cca3',
            '百姓健康' => 'zscm-beixingjiankang',
            '书画' => 'shuhua',
            '收藏天下' => 'shoucangtianxia',
            '读书频道' => 'szpd-dushu',
            '金色频道' => 'a4d72876a289825786845866024a4765',
            '老年福' => 'laonianfu',
            '七彩戏剧' => '8a29f3de1096334d5a784ebadf4895e3',
            '梨园频道' => 'liyuan',
            '汽摩频道' => 'zscmqimo',
            '车迷频道' => 'chemi',
            '极速汽车' => '6612405d22d72e43ac5dc9d1762c5109',
            '摄影频道' => '7ec3142adb7bde4ae02b11344a4e1ab5',
            'DV生活' => 'zscm-dvsh',
            '彩民在线' => 'zscm-caiminzaixian',
            '城市建设' => 'zscm-chegnshijianshe',
            'CCTV-NEWS' => 'CCTV-NEWS',
            'CCTV-俄语国际频道' => 'cctv-russian',
            'CCTV-法语国际频道' => 'cctv-french',
            'CCTV-西班牙语国际频道' => 'cctv-xibanya',
            'CCTV-阿拉伯语国际频道' => 'cctv-alabo',
            'CCTV-中学生' => 'cctv-zhongxuesheng',
            '中国气象' => 'zhongguoqixiang',
            'CCTV5+' => 'cctv5+',
            '欧洲足球' => 'ouzhouzuqiu',
            '中华美食' => 'zhonghuameishi',
            '高清剧场栏目' => 'thgqyingshi',
            '高清探索栏目' => 'thgqtansuo',
            '高清体育栏目' => 'thgqtiyu',
            '高清综艺栏目' => 'thgqzongyi'            
        );
        
		foreach ($channels as $channel => $channelCode) {
			echo $channel.":  ";
			$content = "\n";
			$fileName = $channel.'.txt';
            if($channelCode == '') {
                continue;
            }
			foreach ($dateArr as $date) {
				$targetDate = ($date == 0)?date('Y-m-d',time()):date('Y-m-d',strtotime("+$date day"));
				$targetDate_format = ($date == 0)?date('y/m/d',time()):date('y/m/d',strtotime("+$date day"));
				echo $targetDate."  ";
				$content .= "\n".$targetDate_format."\n\n";
                $dayPrograms = $programRep->getDayPrograms($channelCode,$targetDate);
                if ($dayPrograms) {
					foreach ($dayPrograms as $program) {
						$content .= $program->getTime()." ".$program->getName()."\n";
                    }
                }else {
                    $content .= $this->getNullProgramList();
                }
			}
			echo "\n";
			if (! empty($content)) {
				$file_date = date('Y-m-d',time());
				$target_file= '/epg_xj/'.$file_date.'/'.@iconv("UTF-8","GBK//IGNORE",$channel).'.txt';
				file_put_contents($fileName, @iconv("UTF-8","GBK//IGNORE",$content));
				ftp_pasv($conn,true);
				@ftp_mkdir($conn,'/epg_xj/'.$file_date);
				ftp_put($conn,$target_file,$fileName,FTP_ASCII);
				echo $target_file." upload!\n";
				@unlink($fileName);
			}
		}
        ftp_close($conn);
        echo "finished! connect closed!\n";
    }
    
    function getNullProgramList()
    {   
        return "00:00  以播出为准\n02:00  以播出为准\n04:00  以播出为准\n06:00  以播出为准\n08:00  以播出为准\n10:00  以播出为准\n12:00  以播出为准\n14:00  以播出为准\n16:00  以播出为准\n18:00  以播出为准\n20:00  以播出为准\n22:00  以播出为准\n";
    }
}
