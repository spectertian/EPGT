<?php
/**
 * 导入新疆广电 北京歌华txt文件数据至spservice
 * 
 * @author majun
 * @date   2013-10-16
 */
class spImportXJBJchannelTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name',"frontend"),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'sp';
    $this->name             = 'importXJBJchannel';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [sp:importXJBJchannel|INFO] task does things.
Call it with:

  [php symfony sp:importXJBJchannel|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    //$databaseManager = new sfDatabaseManager($this->configuration);
    //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $this->connectMaster($options);
  	$fileNameArr = array("xjctv","bjctv");//读取2个文件
  	$dir = "./web_admin/uploads/";
  	$mongo = $this->getMondongo();
  	foreach ($fileNameArr as $fileName) {
  		$file = $dir.$fileName.".txt";
  		//获取频道号  电视台名称并组合数据
  		$handle = @fopen($file, "r");
		if ($handle) {
			while (!feof($handle)) {
				$item = fgets($handle, 4096);
				preg_match("/(.*?)\s+(.*)/", $item,$arr);
				if($arr[1] && $arr[2]){
					$channelArr[$arr[1]] = trim($arr[2]);
				}
			}
			fclose($handle);
		}
		
		
		
		if($channelArr){
			foreach ($channelArr as $logicNumber => $channelName) {
				$channelCode = Doctrine_Query::create()
                        ->select('code,type')
                        ->from('channel')
                        ->where('publish = 1')
                        ->addWhere('name=?', $channelName)
                        ->orWhere('memo=?',$channelName)
                        ->fetchArray();
               
                $spService = new SpService();
                $spService -> setSpCode($fileName);
                $spService -> setLogicNumber($logicNumber);
                $spService -> setName($channelName);
                
                if ($channelCode[0]["code"]) $spService -> setChannelCode($channelCode[0]["code"]);
                if ($channelCode[0]["type"]) $spService -> setTags(array($channelCode[0]["type"]));
                
                $spService -> save();
                echo $channelName."已导入\n";
                
			}
		}
		echo $fileName."已导入\n";
		//break;//现只导入新疆文件
  	}
  	echo "finished!";
  }
  
	/**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }
}
