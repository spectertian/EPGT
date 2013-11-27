<?php
/**
 *  @todo  : 根据content_cdi中的上下线信息更新content_import中的state状态（即上下线状态）
 *  @author: lifucang 2013-5-20
 */
class tvCdiToImportTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('state', null, sfCommandOption::PARAMETER_OPTIONAL, 'state',0),
      new sfCommandOption('id', null, sfCommandOption::PARAMETER_OPTIONAL, 'id'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'CdiToImport';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:CdiToImport|INFO] task does things.
Call it with:

  [php symfony tv:CdiToImport|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mystate = $options['state'] ? intval($options['state']) : 0;
        if(isset($options['id'])){
            $query=array("_id"=>new MongoId($options['id']));
        }else{
            $query=array("state"=>$mystate);
        }

        $mongo = $this->getMondongo();
        $cdi_repo = $mongo->getRepository("ContentCdi"); 
        $importImport_repo = $mongo->getRepository("ContentImport");      
        $wiki_repository = $mongo->getRepository('wiki');
        
        $count = $cdi_repo->count($query);
        echo $count,"\n";
        $limit = 200; 
        $i = 0;
        while ($i < $count) 
        {
            $cdis = $cdi_repo->find(array("query"=>$query,"sort" => array("_id"=>1),"limit" => $limit));
            if($cdis){ 
                foreach($cdis as $cdi) {
                    $jsonstr=$cdi->getContent();
                    $content = @simplexml_load_string(trim($jsonstr)); 
                    if($content){
                        $header=$content->header->attributes();
                        if($header['command']=='ONLINE_TASK_DONE'){
                            foreach($content->body->tasks->task as $val){
                                $attr=$val->attributes();
                                $children_id=(string)$attr['subcontent-id'];
                                break;
                            }
                            //echo 'shangxian---',$children_id,"\n";
                            $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id)));
                            if($importImport){
                                $importImport->setState(1);
                                $importImport->save();
                                echo iconv("utf-8","gbk",$importImport->getFromTitle()."上线"),"\n";       
                            }
                        }elseif($header['command']=='CONTENT_OFFLINE'){
                            foreach($content->body->contents->content as $val){
                                $attr=$val->attributes();
                                $children_id=(string)$attr['subcontent-id'];
                                break;
                            }
                            //echo 'xiaxian---',$children_id,"\n";
                            $importImport = $importImport_repo->findOne(array('query'=>array('children_id.ID'=>$children_id)));
                            if($importImport){
                                $importImport->setState(0);
                                $importImport->save();
                                echo iconv("utf-8","gbk",$importImport->getFromTitle()."下线"),"\n"; 
                            } 
                        }elseif($header['command']=='DELIVERY_TASK_DONE'){
                            
                        }
                    }    
                    $cdi ->setState(1);
                    $cdi ->save();
                }
            }
            $i = $i + $limit;
            echo $i,'*************************************',"\n"; 
            sleep(1);  
        } 
        echo "finished!\n";      
    }
}
