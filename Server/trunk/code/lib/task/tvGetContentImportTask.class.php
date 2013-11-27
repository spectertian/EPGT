<?php
/**
 *  从南京获取Import
 *  @author: lifucang 2013-05-27
 */
class tvGetContentImportTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('startTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'startTime'),
            new sfCommandOption('endTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'endTime'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'GetContentImport';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:GetContentImport|INFO] task does things.
Call it with:

  [php symfony tv:GetContentImport|INFO]
EOF;
   //php symfony tv:GetContentImport --startTime=2013-05-21 --endTime=2013-05-22
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $url = "http://122.193.13.36:8082/json";
        
        $startTime=$options['startTime']?$options['startTime']:date("Y-m-d 00:00:00",strtotime("-1 days"));
        $endTime=$options['endTime']?$options['endTime']:date("Y-m-d 23:59:59",strtotime("-1 days"));
        echo $startTime,'---',$endTime,"\n";
        
        $json_post='{"action":"GetContentImport","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'"}}';        
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $imports=$result['imports']?$result['imports']:array(); 
        echo "count:",count($imports),"\n";
        sleep(1);
        foreach($imports as $import){
               $importExists = $mongo->getRepository("ContentImport")->findOneById(new MongoId($import['id']));
               if(!$importExists){
                    $importLocal = new ContentImportNew();
                    $importLocal -> setId(new MongoId($import['id']));
                    $importLocal -> setInjectId($import['inject_id']);
                    $importLocal -> setFrom($import['from']);
                    $importLocal -> setFromId($import['from_id']);
                    $importLocal -> setFromTitle($import['from_title']);
                    $importLocal -> setProviderId($import['provider_id']);
                    $importLocal -> setFromType($import['from_type']);
                    $importLocal -> setState($import['state']);
                    $importLocal -> setChildrenId($import['children_id']);
                    if($import['wiki_id'])
                        $importLocal -> setWikiId($import['wiki_id']);
                    $importLocal -> setStateEdit($import['state_edit']);
                    $importLocal -> save();
               }
        }    
        echo date("Y-m-d H:i:s"),"------finished!\r\n";  
    }
}
