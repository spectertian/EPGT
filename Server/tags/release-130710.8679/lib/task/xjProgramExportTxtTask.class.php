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
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
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
        echo 'connected!\n';
        
        $dateArr = array(0,1,2,3,4,5,6); //预发送今天起一周内容
        
        $channels = array (
                '重庆卫视' => '5731a167d79c432575056c4963dc8049',
                '甘肃卫视' => '5ace8ddc54a4151bbcf76e56c8aa582a',
                '广东卫视' => 'c8bf387b1824053bdb0423ef806a2227',
                '深圳卫视' => '20831bb807a45638cfaf81df1122024d',
                '广西卫视' => '5cbb108dbf59f2ae1849ec8d1126d1a5',
                '贵州卫视' => '5a7d01661b5d9c64293860531374312b',
                '河北卫视' => 'ef1fce69a9e1b3a587ca734302400107',
                '河南卫视' => '2c854868563485135dd486801057dd6e',
                '黑龙江卫视' => '1ce026a774dba0d13dc0cef453248fb7',
                '湖北卫视' => '55fc65ef82e92d0e1ccb2b3f200a7529',
                '湖南卫视' => 'c39a7a374d888bce3912df71bcb0d580',
                '吉林卫视' => '45392a8be644f5b8903838436870c75d',
                '江苏卫视' => '322fa7b66243b8d0edef9d761a42f263',
                '江西卫视' => '535765a19ab55a12bbf64a1e98ae97dd',
                '辽宁卫视' => '9291c40ec1cec1281638720c74c7245f',
                '宁夏卫视' => 'a09ab19928a6b2bd616f7e2eba1056ee',
                '青海卫视' => '4ec095f1d2564f82341275fff64edb5a',
                '山东卫视' => '28502a1b6bf5fbe7c6da9241db596237',
                '山西卫视' => '2aeb585ccaca9fa893b0bdfdbc098c7f',
                '陕西卫视' => 'eb7330e363ceec8c6895eacc44a1a804',
                '游戏风云' => 'a57bb859618877ab8cf2d2abf30b4f55',
                '动漫秀场' => '5c8dbe3714f3544285a4c4922e2ed01a',
                '卫生健康' => '17f79002aa3904b69fc41b463c04cca3',
                '全纪实' => '2e79089eeb8dfeb99cd21296facd2025',
                '四川卫视' => 'b82fa4086c9a2c9442279efbb80cce31',
                '天津卫视' => '5927c7a6dd31f38686fafa073e2e13bc',
                '云南卫视' => 'c786da29f0f5cc5973444e3ad49413a6',
                '浙江卫视' => '590e187a8799b1890175d25ec85ea352',
                '安徽卫视' => 'antv',
                '东南卫视' => 'fjtv',
                'CCTV-NEWS' => 'CCTV-NEWS',
                'CCTV-1（高清）' => 'cctv1gaoqing',
                '湖南卫视（高清）' => 'hunanweishigaoqing',
                '东方财经' => 'sitvdfcj',
                '生活时尚' => 'sitvshss',
                '天元围棋' => '3d23c7fa7feae2ea2b6e3f7f1359aa7a',
                '早期教育' => 'zaoqijiaoyu',
                '3D频道' => '3dpindao',
                '风云剧场' => 'fengyunjuchang',
                '央视精品' => 'yangshijingpin',
                '风云音乐' => 'fengyunyinyue',
                '风云足球' => 'fengyunzuqiu',
                '高尔夫网球' => 'gaoerfuwangqiu',
                '世界地理' => 'shijiedili',
                '国防军事' => 'guofangjunshi',
                '第一剧场' => 'diyijuchang',
                '孕育指南' => 'yunyuzhinan',
                '留学世界' => 'liuxueshijie',
                '英语辅导' => 'yingyufudao',
                '中视购物' => 'zhongshigouwu',
                '老年福' => 'laonianfu',
                '游戏竞技' => 'youxijingji',
                '先锋记录' => 'xianfengjilu',
                '发现之旅' => 'faxianzhilv',
                '中国气象' => 'zhongguoqixiang',
                '收藏天下' => 'shoucangtianxia',
                '家庭健康' => 'jiatingjiankang',
                '先锋乒羽' => 'xianfengpingyu',
                '时代美食' => 'shidaimeishi',
                '环球旅游' => 'huanqiulvyou',
                '四海钓鱼' => 'sihaidiaoyu',
                'CCTV-1' => 'cctv1',
                'CCTV-2' => 'cctv2',
                'CCTV-3' => 'cctv3',
                'CCTV-4' => 'cctv4_asia',
                'CCTV-5' => 'cctv5',
                'CCTV-6' => 'cctv6',
                'CCTV-7' => 'cctv7',
                'CCTV-8' => 'cctv8',
                'CCTV-10' => 'cctv10',
                'CCTV-11' => 'cctv11',
                'CCTV-12' => 'cctv12',
                'CCTV-新闻' => 'cctv_news',
                'CCTV-少儿' => 'cctv_kids',
                'CCTV-音乐' => 'cctv_music',
                '北京卫视' => '5dfcaefe6e7203df9fbe61ffd64ed1c4',
                '金鹰卡通' => '370e3081d7630e9fe35125bd6dab01da',
                '都市剧场' => '8c2c76bce805d11f5ba0266f8a33c65e',
                '欢笑剧场' => '1800444c032205d1443af46a5111fbf1',
                '法治天地' => '05d6693c933de13842e71023eee86cdd',
                '七彩戏剧' => '8a29f3de1096334d5a784ebadf4895e3',
                '魅力音乐' => '2ac392f31cfbacdee4cb042d6bd4ad75',
                '金色频道' => 'a4d72876a289825786845866024a4765',
                '极速汽车' => '6612405d22d72e43ac5dc9d1762c5109',
                '劲爆体育' => '2ccef4b3a8b8f1686594ab6a8c3ba802',
                '新疆教育' => 'ad77cc612a0dcef9c788efb61384883f',
                '上海卫视' => 'dragontv',
                '上海炫动卡通' => '74ca733eddc5c16163210a031f3295db',
                '内蒙卫视(蒙)' => '1cbdd1e125f9a7778d4716592d9e4088',
                '内蒙卫视(汉)' => '03295de404257fa9653b89bf2d0e47ac',
                '优漫卡通卫视'=>'youmanktws',
                '北京（高清）'=>'bjweishigaoqing',
                '浙江（高清）'=>'jztv_high',
                '江苏（高清）'=>'jiangsuweishigaoqing',
                '黑龙江（高清）'=>'hljweishigaoqing',
                '3D频道'=>'3dpindao',
                'CHC高清电影频道'=>'chcgaoqingdianying',
                '梨园频道'=>'liyuan',
                '电视指南'=>'szdianshizhinan',
                '家庭影院'=>'chcjiatingyingyuan',
                '怀旧剧场'=>'yangshihuaijiujuchang',
                '动作电影'=>'chcdongzuody',
                '新娱乐'=>'b0624dfb3bd6bb4f345387d7092793b7',
                '靓妆'=>'6a12341152e41576d5107eae44a4fef8',
                '摄影频道'=>'7ec3142adb7bde4ae02b11344a4e1ab5',
                '书画频道'=>'shuhua',
                '车迷频道'=>'chemi',
                '武术世界'=>'wushushijie',
                '老故事'=>'laogushi',
                '碟市'=>'dieshi',
                '女性时尚'=>'ladyfashion',
                '现代女性'=>'nowlady',
                '时代家居'=>'shidaijiaju',
                '宝贝家'=>'babyhome',
                '新科动漫'=>'xinkedongman',
                '百姓健康'=>'baixingjiankang',
                '读书频道'=>'dushupindao',
                'DV生活'=>'dvlife',
                '彩民在线'=>'caiminonline',
                '城市建设'=>'chengshijianshe',
                'CCTV-俄语国际频道'=>'cctveyu',
                'CCTV-法语国际频道'=>'wushushijie',
                'CCTV-西班牙语国际频道'=>'cctvxibanya',
                'CCTV-阿拉伯语国际频道'=>'cctvalabo',
                '乌鲁木齐-1'=>'16d3812f7a440cfbe6dbc1d9de3ebb31',
                '乌鲁木齐-3'=>'014005d0ad57eb95cd0667edeb988125',
                '乌鲁木齐-4'=>'ed10480d4e1a230b608a47523ae582a7',
                '乌鲁木齐-5'=>'a4190734ac8aa80ded3253051f234533',
                '乌鲁木齐-6'=>'9d08f36b3dad281377dc599b16180ee8',
                '中教1'=>'fcc4eabadaf03c98f7e61018e97c6d03',
                'XJTV-1'=>'ad291a233f1fd3f24332e41461798a25',
                'XJTV-2'=>'5d5b32f51a544f8800bb17e7e06e0b5e',
                'XJTV-3'=>'27979b0625fb4b04f8e75774b5074889',
                'XJTV-4'=>'992eda0954d1e66edeb0108cec74996f',
                'XJTV-5'=>'6a218dca2c67a8f58bf9ddf6c395c185',
                'XJTV-6'=>'ecab8aa90fab52f758d90f1678ef4ec8',
                'XJTV-7'=>'351c09f7d3856d6278b33d4a83a223a0',
                'XJTV-8'=>'3c5e39fee5c26df7e9b4c39799db731f',
                'XJTV-9'=>'53a300adff5df78cbd2ada2b099be46c',
                'XJTV-10'=>'66170e96c62afe00a4e8acb3028b8d47',
                'XJTV-11'=>'a131b27ae3ffcbe2ff4f80b030ccd38e',
                'XJTV-12'=>'ee5905e0f191954d6c0d6712c71b8501',
                '兵团新闻'=>'bingtuanweishi',
                '西藏汉语'=>'feccf21eb7e50753355efdab2d54d9e8',
                '测试一'=>'bb8f7378ef7eefeea8ec82cf7e34f173',
                '测试三'=>'xgyxgjdl',
                '测试二'=>'b52ed95ecc9995cb7a418061040c740f',
                '测试六'=>'fd5e69184516f4e96a7f4d41e52b3bb0',
        );
        
        foreach ($dateArr as $date) {
        
            $targetDate = ($date == 0)?date('Y-m-d',time()):date('Y-m-d',strtotime("+$date day"));
            echo $targetDate.'\n';
            foreach ($channels as $channel => $channelCode) {
                
                $content = '';
                $fileName = $channel.'.txt';
                $dayPrograms = $programRep->getDayPrograms($channelCode,$targetDate);
                
                if ($dayPrograms){
                    foreach ($dayPrograms as $program) {
                        $content .= $program->getTime()." ".$program->getName()."\n";
                    }
                }else {
                    continue;
                }
                if (! empty($content)){
                    $target_file= '/epg_xj/'.$targetDate.'/'.@iconv("UTF-8","GBK//IGNORE",$channel).'.txt';
                    file_put_contents($fileName, @iconv("UTF-8","GBK//IGNORE",$content));
                    ftp_pasv($conn,true);
                    @ftp_mkdir($conn,'/epg_xj/'.$targetDate);
                    ftp_put($conn,$target_file,$fileName,FTP_ASCII);
                    echo $target_file." upload!\n";
                    @unlink($fileName);
                }
            }
        }
        
        ftp_close($conn);
        echo 'finished! connect closed!';
    }
}
