<?php
/**
 *  @todo  : 导出content_import数据给欢网
 *  @author: lifucang 2013-06-04
 */
class tvExportImportTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('startTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'startTime'),
      new sfCommandOption('endTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'endTime'),
      new sfCommandOption('fromId', null, sfCommandOption::PARAMETER_OPTIONAL, 'fromId'),
      new sfCommandOption('stateMatch', null, sfCommandOption::PARAMETER_OPTIONAL, 'stateMatch'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'ExportImport';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ExportImport|INFO] task does things.
Call it with:

  [php symfony tv:ExportImport|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('ExportImport');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $url=sfConfig::get('app_epghuan_posturl').'/import';
        //$url='http://www.5i.test.cedock.net/inject/import'; //测试服务器地址
        
		$nodeArrays = array();
        $fromId = $options['fromId'];
        $stateMatch = $options['stateMatch'];
        $starttime = $options['startTime']?new MongoDate(strtotime($options['startTime'])):new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime=$options['endTime']?new MongoDate(strtotime($options['endTime'])):new MongoDate(mktime(23, 59, 59, date('m'), date('d'), date('Y')));
		$Repository = $mongo->getRepository('ContentImport');
        if($stateMatch){
            $query_arr = array('state_match'=>1);
        }elseif($fromId){
            $query_arr = array('from_id'=>$fromId);
        }else{
            $query_arr['$or']=array(array('created_at'=>array('$gte' => $starttime,'$lte' => $endtime)),array('updated_at'=>array('$gte' => $starttime,'$lte' => $endtime)));
        }
        $imports = $Repository->find(array('query'=>$query_arr));
		//$nodeArray = $this->getErrArray(0,'',count($imports));
        foreach($imports as $import) 
		{
			$nodeArrays[] =  array(
                            'id' => (string)$import->getId(),
                            'inject_id' => $import->getInjectId(),
                            'from'  => $import->getFrom(),
                            'from_id'  =>$import->getFromId(),  
                            'from_title'    => $import->getFromTitle(),
                            'provider_id'   => $import->getProviderId(),
                            'from_type'  => $import->getFromType(),
                            'state'  => $import->getState(),   
                            'children_id'    => $import->getChildrenId(),
                            'wiki_id'   => $import->getWikiId(),
                            'state_edit'  => $import->getStateEdit(),
                            'state_check'  => $import->getStateCheck(),
                            'state_error'  => $import->getStateError(),
            );
		}
        //分割数组
        $return='';
        $arr=array_chunk($nodeArrays, 50);
        foreach($arr as $nodeArraySmall){
            $nodeArray['imports']=$nodeArraySmall;
    		$postData=json_encode($nodeArray);
            $return=Common::post_json($url,$postData);
            sleep(1);
        }
        echo date('Y-m-d H:i:s'),$return,"\n";
        
        $content="num:".count($imports).' '.$return;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
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
