<?php
/**
 *  @todo  : 将content_import1中的有wiki的信息导入到新的content_import表
 *  @author: lifucang
 */
class tvImport1ToImportTask extends sfMondongoTask
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
    $this->name             = 'Import1ToImport';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:Import1ToImport|INFO] task does things.
Call it with:

  [php symfony tv:Import1ToImport|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
	    set_time_limit(0); 
        $mongo = $this->getMondongo();
        $import_repo = $mongo->getRepository("ContentImport");
        $import_repo1 = $mongo->getRepository("ContentImport1");
        
        $query=array('wiki_id'=>array('$exists'=>false));
        $import_count = $import_repo->count($query);
        $i = 0;
        $wikinum=0;
        echo "count:",$import_count,"\n";
        sleep(1);
        while ($i < $import_count) 
        {
            $imports = $import_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach ($imports as $import) 
            {
                $from_id=$import->getFromId();
                $query1=array('from_id'=>$from_id,'wiki_id'=>array('$exists'=>true));
                $import1=$import_repo1->findOne(array("query"=>$query1));
                if($import1){
                    $import->setWikiId($import1->getWikiId());
                    $import->save();
                    $wikinum++;
                }
            }
            $i = $i + 200;
            echo $i,"\n";
            sleep(1);
        }
        echo "finished; ------wikiNum:",$wikinum,"\n";
    }
}
