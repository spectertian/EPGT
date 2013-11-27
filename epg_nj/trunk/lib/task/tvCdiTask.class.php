<?php
/**
 *  @todo  : 将cdi中的上下线信息分析后写入command等字段
 *  @author: lifucang 
 *  @update: 2013-9-20
 *  @example: php symfony tv:Cdi --idstart=513d48857f8b9ae70900001d --command=no  //不存在command字段的更新
 *  @example: php symfony tv:Cdi --idstart=513d48857f8b9ae70900001d               //id大于513d48857f8b9ae70900001d的更新
 */
class tvCdiTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('id', null, sfCommandOption::PARAMETER_REQUIRED, 'id'),
      new sfCommandOption('idstart', null, sfCommandOption::PARAMETER_REQUIRED, 'idstart'),
      new sfCommandOption('idend', null, sfCommandOption::PARAMETER_REQUIRED, 'idend'),
      new sfCommandOption('command', null, sfCommandOption::PARAMETER_REQUIRED, 'command'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'Cdi';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:Cdi|INFO] task does things.
Call it with:

  [php symfony tv:Cdi|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $id = $options['id'];
        $idstart = $options['idstart'];
        $idend = $options['idend'];
        $command = $options['command'];
        $flag = 0;
        if($idstart&&$idend){
            $query=array("_id"=>array('$gte'=>new MongoId($idstart),'$lte'=>new MongoId($idend)));
        }elseif($idstart&&$command){
            $flag = 1;
            $query=array("_id"=>array('$gte'=>new MongoId($idstart)),'command'=>array('$exists'=>0));
        }elseif($idstart){
            $flag = 2;
            $query=array("_id"=>array('$gte'=>new MongoId($idstart)));
        }elseif($id){
            $query=array("_id"=>new MongoId($id));
        }else{
            $query=array("state"=>0);
        }
        $mongo = $this->getMondongo();
        $cdi_repo = $mongo->getRepository("ContentCdi"); 
        $count = $cdi_repo->count($query);
        echo $count,"\n";
        $limit = 200; 
        $i = 0;
        while ($i < $count) 
        {
            if($flag==1){
                $query=array("_id"=>array('$gte'=>new MongoId($idstart)),'command'=>array('$exists'=>0));
            }elseif($flag==2){
                $query=array("_id"=>array('$gte'=>new MongoId($idstart)));
            }
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
                                $page_id=(string)$attr['page-id'];
                                break;
                            }
                            $cdi -> setCommand('ONLINE_TASK_DONE');
                            $cdi -> setSubcontentId($children_id);
                            $cdi -> setPageId($page_id);
                        }elseif($header['command']=='CONTENT_OFFLINE'){
                            foreach($content->body->contents->content as $val){
                                $attr=$val->attributes();
                                $children_id=(string)$attr['subcontent-id'];
                                break;
                            }
                            $cdi -> setCommand('CONTENT_OFFLINE');
                            $cdi -> setSubcontentId($children_id);
                        }elseif($header['command']=='DELIVERY_TASK_DONE'){
                            foreach($content->body->tasks->task as $val){
                                $attr=$val->attributes();
                                $children_id=(string)$attr['subcontent-id'];
                                $page_id=(string)$val->play-url; //得不到内容
                                break;
                            }
                            //$page_id=$content->body->tasks->task->play-url; //得不到内容
                            $cdi -> setCommand('DELIVERY_TASK_DONE');
                            $cdi -> setSubcontentId($children_id);
                            $cdi -> setPageId($page_id);
                        }else{
                            $cdi -> setCommand($header['command']);
                        }
                    }    
                    $cdi ->setState(1);
                    $cdi ->save();
                    $idstart = (string)$cdi->getId();
                }
            }
            $i = $i + $limit;
            echo $i,'*************************************',"\n"; 
            sleep(1);  
        } 
        echo "finished!\n";      
    }
}
