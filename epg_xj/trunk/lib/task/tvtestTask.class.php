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


          // mapreduce 统计显示重复项
     $m = new Mondongo\Connection('mongodb://epg:epgpass@118.194.161.68:27017','epg');
     $db = $m->getMongoDB();
     $map = '
     function() {
      var key = {title: this.model+this.title};
      var value = {count:1};
      emit(key,value);
     } ';
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



     
     // 邮件发送
     /**
     
    	$to = array(
    			//'578709642@qq.com' => '强海明',
    			'chenshengwen@huan.tv' => 'chenshengwen',
    	);
    	$message = $this->getMailer()
    	->compose('jianghaiming0426@126.com',$to , '测试题目', '<a id="cb_post_title_url" class="postTitle2" href="http://www.baidu.com">测试</a>')
    	;
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
     	//$message->send();
      */


  /**
    	$to = array(
    			'578709642@qq.com' => '强海明',
    	    	//'chenshengwen@huan.tv' => 'chenshengwen',
    	);
        $message = Swift_Message::newInstance()
    	->setFrom('jianghaiming0426@126.com')
    	->setTo($to)
    	->setSubject('Subject')
    	;
    	$message->setBody(
    			'<html>' .
    			' <head></head>' .
    			' <body>' .
    			'<p>aaaaaaaaa</p>'.
    			'<h1>测试 test </h1>'.
    			'测试文件       html   <a id="cb_post_title_url" class="postTitle2" href="http://www.baidu.com">测试</a>'.
    			' </body>' .
    			'</html>',
    			'text/html'
    	);
    	
    	$this->getMailer()->send($message);
*/


        /**
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $channels = Doctrine::getTable('Channel')->getYangShiAndWeiShiChannels();
        $programRep = $this->getMondongo()->getRepository('Program');
        $i = 0;
        foreach($channels as $channel) {
            //if($i >=2) break;
            $i++;
            $filename = "./tmp/epg/txt/".iconv("utf-8","gbk",$channel->getName()).".txt";
            $filecont = "";
            echo $channel->getCode()."\n";
            for($date = 0; $date < 5; $date ++) {
                $targetDate = date('Y-m-d',strtotime("+$date day"));
                echo "    ".$targetDate."\n";
                $filecont .= $targetDate."\n";
                $dayPrograms = $programRep->getDayProgramsByChannelCode($channel->getCode(), $targetDate, false);
                foreach($dayPrograms as $program) {
                    $filecont .= $program->getTime()."  ".$program->getName();
                    if($program->getWikiId()) {
                        $filecont .= "  http://www.epg.huan.tv/wiki/show/id/".$program->getWikiId();
                    }
                    $filecont .= "\n";
                }
                $filecont .= "\n";
            }
            file_put_contents($filename,iconv("utf-8","gbk",$filecont));
        }
        */
    }
}
?>