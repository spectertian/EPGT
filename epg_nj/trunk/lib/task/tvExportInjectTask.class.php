<?php
/**
 *  @todo  : 导出content_inject数据给欢网
 *  @author: lifucang 2013-06-04
 */
class tvExportInjectTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('startTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'startTime'),
      new sfCommandOption('endTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'endTime'),
      new sfCommandOption('id', null, sfCommandOption::PARAMETER_OPTIONAL, 'id'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'ExportInject';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ExportInject|INFO] task does things.
Call it with:

  [php symfony tv:ExportInject|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('ExportInject');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $url=sfConfig::get('app_epghuan_posturl').'/inject';
        //$url='http://172.31.200.121:8082/test/inject';
		$nodeArrays = array();
        $id = $options['id'];
        $starttime = $options['startTime']?new MongoDate(strtotime($options['startTime'])):new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime=$options['endTime']?new MongoDate(strtotime($options['endTime'])):new MongoDate(mktime(23, 59, 59, date('m'), date('d'), date('Y')));
		$Repository = $mongo->getRepository('ContentInject');
        if($id){
            $query_arr = array('_id'=>new MongoId($id));
        }else{
            $query_arr = array('created_at'=>array('$gte' => $starttime,'$lte' => $endtime));
        }
        $injects = $Repository->find(array('query'=>$query_arr));
		//$nodeArray = $this->getErrArray(0,'',count($injects));
        foreach($injects as $inject) 
		{
			$nodeArrays[] =  array(
                            'id'    => (string)$inject->getId(),
                            'content'   => $inject->getContent(),
                            'from'  => $inject->getFrom(),
                            'state'  =>$inject->getState(),
            );
		}
        //分割数组
        $arr=array_chunk($nodeArrays, 5);
        foreach($arr as $nodeArraySmall){
            $nodeArray['injects']=$nodeArraySmall;
    		$postData=json_encode($nodeArray);
            $return=Common::post_json($url,$postData);
            echo date('Y-m-d H:i:s'),$return,"\n";
            sleep(1);
        }
        
        $content="num:".count($injects).' '.$return;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
    }
    /**
     * 返回错误代码
     * @author lifucang
     * @final 2012-07-19
     */
    private function getErrArray($errorStatus=0,$message='',$total=null,$num=null){
        $nodeArray = array();
        $nodeArray['error'] = array(
                'code' => $errorStatus,
                'info' => $message,
        );
		if(!is_null($total))
		{
			$nodeArray['total'] = $total;
		}
		if(!is_null($num))
		{
			$nodeArray['num'] = $num;
		}
		return $nodeArray;
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
