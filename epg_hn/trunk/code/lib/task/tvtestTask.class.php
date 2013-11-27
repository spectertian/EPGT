<?php
use Mondongo\Container;
use Mondongo\Mondongo;

class tvtestTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('code', null, sfCommandOption::PARAMETER_REQUIRED, 'what date?', ''),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'test';
    $this->briefDescription = '';
    $this->detailedDescription = '';
  }

  protected function execute($arguments = array(), $options = array())
  {
	$channels = array('CCTV-1综合频道' => 1,
					'CCTV-2财经频道' => 3,
					'CCTV-3综艺频道' => 4,
					'CCTV-4中文国际频道亚洲版' => 5,
					'CCTV-5体育频道' => 6,
					'CCTV-6电影频道' => 7,
					'CCTV-7军事农业频道' => 8,
					'CCTV-8电视剧频道' => 9,
					'CCTV-9记录频道' => 10,
					'CCTV-10科教频道' => 11,
					'CCTV-11戏曲频道' => 12,
					'CCTV-12社会与法频道' => 13,
					'CCTV-13新闻频道' => 14,
					'CCTV-14少儿频道' => 15,
					'CCTV-15音乐频道' => 16,
					'北京电视台-1' => 60,
					'重庆卫视' => 59,
					'甘肃卫视' => 52,
					'广东卫视' => 70,
					'广西卫视' => 50,
					'贵州卫视' => 48,
					'旅游卫视' => 31,
					'河北卫视' => 37,
					'河南卫视' => 38,
					'黑龙江卫视' => 34,
					'湖北卫视' => 47,
					'湖南卫视' => 46,
					'吉林卫视' => 35,
					'江苏卫视' => 44,
					'江西卫视' => 45,
					'辽宁卫视' => 36,
					'内蒙古卫视' => 56,
					'宁夏卫视' => 53,
					'青海卫视' => 592,
					'山东卫视' => 41,
					'山西卫视' => 39,
					'陕西卫视' => 40,
					'四川卫视' => 58,
					'天津卫视' => 57,
					'西藏卫视' => 54,
					'新疆卫视' => 55,
					'云南卫视' => 49,
					'浙江卫视' => 43,
					'安徽卫视' => 42,
					'东南卫视' => 51,
					'东方卫视' => 81);
    $code=$options['code'];                
    if($code){
        $arr=array_flip($channels);
        $name=$arr[$code];
		@unlink('log/tmp_'.iconv("UTF-8","GBK",$name).".txt");
		$string = "";
		for($i = 0; $i < 3 ; $i ++) {
			$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));			
			$string .= $date."\r\n";
			unset($programs);
			$content = @file_get_contents("http://hz.tvsou.com/jm/hw/hw8901.asp?id=".$code."&Date=".$date);
            //$content = iconv('GBK', 'GBK//IGNORE', $content);
            $content = str_replace('gb2312', 'gb18030', $content);
			if($content) {
				$xml = simplexml_load_string($content);
				if($xml) {
					foreach($xml->C as $c) {
						$string .= date("H:i:s",strtotime($c->pt)) ." ==>  ". $c->pn."\r\n";
					}
				}
			}
			$string .= "\r\n";
		}
		file_put_contents('log/tmp_'.iconv("UTF-8","GBK",$name).".txt",$string);
    }else{
    	foreach($channels as $name => $code) {
    		@unlink('log/tmp_'.iconv("UTF-8","GBK",$name).".txt");
    		$string = "";
    		for($i = 0; $i < 3 ; $i ++) {
    			$date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));			
    			$string .= $date."\r\n";
    			unset($programs);
    			$content = @file_get_contents("http://hz.tvsou.com/jm/hw/hw8901.asp?id=".$code."&Date=".$date);
                //$content = iconv('GBK', 'GBK//IGNORE', $content);
                $content = str_replace('gb2312', 'gb18030', $content);
    			if($content) {
    				$xml = simplexml_load_string($content);
    				if($xml) {
    					foreach($xml->C as $c) {
    						$string .= date("H:i:s",strtotime($c->pt)) ." ==>  ". $c->pn."\r\n";
    					}
    				}
    			}
    			$string .= "\r\n";
    		}
    		file_put_contents('log/tmp_'.iconv("UTF-8","GBK",$name).".txt",$string);
    	}
    }         
	
	echo "finished!";	
  }  
  
  public function getProgramRepository() {
    $mondongo = new Mondongo();
    $databaseManager = new sfDatabaseManager($this->configuration);
    foreach ($databaseManager->getNames() as $name)
    {
        $database = $databaseManager->getDatabase($name);
        if ($database instanceof sfMondongoDatabase)
        {
          $mondongo->setConnection($name, $database->getMondongoConnection());
        }
    }
    Container::setDefault($mondongo);
    return $mondongo->getRepository('Program');
  }
}
?>