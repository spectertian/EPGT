<?php

class tvContentImportTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'ContentImport';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:ContentImport|INFO] task does things.
Call it with:

[php symfony tv:ContentImport|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $mongo = $this->getMondongo();
        $inject_repo = $mongo->getRepository("ContentInject"); 
        $import_repo = $mongo->getRepository("ContentImport");      
		
        $injects = $inject_repo->find(array("query"=>array("state"=>0),"sort" => array("created_at" => 1),"limit" => 100));
        
        if(count($injects) == 0){            
            echo "finished!";                       
        }else{
            foreach($injects as $inject) {
                if($content = simplexml_load_string(trim($inject->getContent()))) {
                    $asset_name = $content->Metadata->AMS['Asset_Name'];
                    $asset_id = $content->Metadata->AMS['Asset_ID'];
                    //$wiki_id = getWikiIdByAssetId($asset_id);                
                    $ContentImport = new ContentImport();
                    $ContentImport -> setInjectId($inject->getId());
                    $ContentImport -> setFrom($inject->getFrom());
                    $ContentImport -> setFromId($asset_id);
                    $ContentImport -> setFromTitle($asset_name);
                    $ContentImport -> save(); 
                    
                    //更新inject状态
                    $inject->setState(1);
                    $inject->save();                    
                }else{
                    $inject->setState(-1);
                    $inject->save();
                }
            }
        }        
    }
  
    /*
     * wiki对象获取海报
     * @editor lifucang
     */
    private function getWikiIdByAssetId($asset_id)
    {
       $mongo = $this->getMondongo();
       $wiki_repo = $mongo->getRepository("Wiki");
       $wiki = $wiki_repo->findOne(array("query"=>array("asset_id"=>$asset_id)));
       if($wiki) return $wiki->getId();
       else return null;
    }  
}
