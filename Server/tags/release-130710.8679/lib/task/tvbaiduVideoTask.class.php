<?php
/**
 * 导入百度视频
 * @author wn
 */
class baiduVideoTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
	  new sfCommandOption('model', null, sfCommandOption::PARAMETER_OPTIONAL, 'what model???????', ''),      
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'baiduVideo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [baiduVideo|INFO] task does things.
Call it with:

  [php symfony baiduVideo|INFO]
EOF;
  }

	protected function execute($arguments = array(), $options = array())
	{
		$model = isset($options['model']) ?$options['model']:'film';
		$this->crawlerBaidu($model);
	}	
	protected function crawlerBaidu($model) 
	{
    // initialize the database connection
		$mongo = $this->getMondongo();
		$vc_repository  = $mongo->getRepository('videoCrawler');//待抓取条目表

		$vcs = $vc_repository->getObjectsByModel($model);//待抓取条目
		$baiduTM = BaiduvideoToMongo::getInstance();
		$baiduTM ->setMongo($mongo) ;
		foreach($vcs as $vc)
		{
			$baiduTM->setObject($vc);
		}
	}

}
