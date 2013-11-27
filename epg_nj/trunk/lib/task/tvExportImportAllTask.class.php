<?php
/**
 *  @todo  : 导出content_import的所有数据给欢网
 *  @author: lifucang 2013-08-26
 */
class tvExportImportAllTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'ExportImportAll';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ExportImportAll|INFO] task does things.
Call it with:

  [php symfony tv:ExportImportAll|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //开始
        $url=sfConfig::get('app_epghuan_posturl').'/import';
		$Repository = $mongo->getRepository('ContentImport');
        
        $count = $Repository->count();
        echo "count:",$count,"\n";
        sleep(1);
        $i = 0;
        //$importId='519af4417b5fbd1255000000';
        $importId='519af4417b5fbd1250000000';
        while ($i < $count) {
            $query=array('_id'=>array('$gt'=>new MongoId($importId)));
            $imports = $Repository->find(array("query"=>$query,"sort" => array("_id" => 1), "limit" => 50));
            $nodeArray = array();
            foreach($imports as $import) 
    		{
    			$nodeArray['imports'][] =  array(
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
                $importId = (string)$import->getId();
    		}
    		$postData=json_encode($nodeArray);
            $return=Common::post_json($url,$postData);
            $i = $i + 50;
            echo $importId,"\n";
            echo $i,'*************************************',$return,"\n";
            sleep(1);
        }
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
