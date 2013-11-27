<?php
/**
 * 测试任务
 * 
 */
class tvtestTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('code', null, sfCommandOption::PARAMETER_REQUIRED, 'what date?', ''),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'test';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {      
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('wiki');
        
        $titles[] = "海豚万家剧场：甄嬛传8";
        $titles[] = "电视剧:利箭行动 35";
        $titles[] = "精品剧场：反击(3)";
        $titles[] = "午夜剧场：誓言今生（22）";
        $titles[] = "经典影院：佛跳墙";
        $titles[] = "情感剧场：爱情公寓Ⅲ精装版";
        $titles[] = "夜间剧场：裸婚时代 30";
        $titles[] = "转播中央台新闻联播";
        $titles[] = "城市经典剧场：聊斋之狐仙（11）";
        $titles[] = "城市第一剧场：铁血尖刀(29)";
        $titles[] = "午夜剧场：洪湖赤卫队(4)";
        $titles[] = "好梦剧场：洪湖赤卫队(5-6)";
        $titles[] = "活力剧场：爱可以重来(9-11)";
        $titles[] = "天天剧场:郎本无情13-15";
        $titles[] = "午夜狂放:绝杀28―30";
        $titles[] = "白领剧场:面包大王 39―40";
        $titles[] = "好剧看不停:我的灿烂人生 23";
        $titles[] = "刁蛮公主(24-25)";
        $titles[] = "靓妆直播间(复播)";
        $titles[] = "魅惑摄影(首播)";
        $titles[] = "直播南京（直播）";
        $titles[] = "直播南京（复）";
        $titles[] = "离婚前规则27";
        $titles[] = "天涯赤子心（34）";
        $titles[] = "影视新剧场::香水佳人 2";
        
        foreach($titles as $title) {
            $wiki = $wiki_repository->getWikiByProgramTitle($title, $channelcode);
            if($wiki) {
                echo $title ."\t\t".$wiki->getTitle()."\n"; 
            } else {
                echo $title ."\t\t------------\n"; 
            }
        }
    }
    
    protected function sendMail()
    {
        // 邮件发送
    	$to = array(
    			'578709642@qq.com' => '强海明',
    			'chenshengwen@huan.tv' => 'chenshengwen',
    	);
    	$message = $this->getMailer()->compose('jianghaiming0426@126.com',$to , '测试题目', '<a id="cb_post_title_url" class="postTitle2" href="http://www.baidu.com">测试</a>');
    	$message->setBody(
    			'<html>' .
    			' <head></head>' .
    			' <body>' .
    			'<p>aaaaaaaaa</p>'.
    			'<h1>任务计划里面测试 </h1>'.
    			'测试文件       html   <a id="cb_post_title_url" class="postTitle2" href="http://www.baidu.com">测试</a>'.
    			' </body>' .
    			'</html>',
    			'text/html'
    	);
     	$this->getMailer()->send($message);     
    }
    
    protected function getRepeatWiki() 
    {
        // mapreduce 统计显示重复项
        $m = new Mondongo\Connection('mongodb://epg:epgpass@118.194.161.68:27017','epg');
        $db = $m->getMongoDB();
        $map = '
        function() {
        var key = {title: this.model+this.title};
        var value = {count:1};
        emit(key,value);
        }';
        $reduce = '
        function(key, values) {
        var ret = {count:0};
        for(var i in values) {
          ret.count += 1;
        }
        return ret;
        }';
        $query = null;
        $cmd = $db->command(array(
        'mapreduce' => 'wiki',
        'map'       => $map,
        'reduce'    => $reduce,
        'query' => $query,
        'out' => 'wiki_temp_res'
        ));

        $cursor = $db->selectCollection('wiki_temp_res')->find();
        try {
        $i=0;
        while ($cursor->hasNext())
        {
            $result = $cursor->getNext();
            if($result['value']['count']>1){
                echo $result['_id']['title'];
                echo "\n";
                echo $i;
                echo "\n";
               $i++;
            }

        }
        }
        catch (MongoConnectionException $e)
        {
        echo $e->getMessage();
        }
        catch (MongoCursorTimeoutException $e)
        {
        echo $e->getMessage();
        }
        catch(Exception $e){
        echo $e->getMessage();
        }
        exit;
    }
}
?>