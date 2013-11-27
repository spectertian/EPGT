<?php
/**
 * 创建词典
 * 一次性执行
 * @author superwen
 */
class makeDictTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace    = 'tv';
        $this->name         = 'makeDict';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [makeDict|INFO] task does things.
Call it with:
    [php symfony makeDict|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        //set_time_limit('1200000');
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('wiki');
        $options['query'] = array('title'=>array('$ne'=>NULL), 'model'=>'film');
        $options['fields'] = array('title','alias','model');
        $options['limit'] = 50;
        $options['sort'] = array("created_at" => 1);
        $wikiCount = $wiki_repository->count($options['query']);
        print_r($wikiCount);
        for($i=0; $i<=$wikiCount; $i=$i+50){
                $options['skip'] = $i;
                $wikiRes = $wiki_repository->find($options);

                foreach($wikiRes as $obj){
                    $title = $obj->getTitle();
                    $alias = $obj->getAlias();
                    $id = $obj->getId();
                    if(!empty($alias)){
                        foreach($alias as $val){
                            if(trim($val)==trim($title)){
                                continue;
                            }
                            $aliasDict = new Dict();
                            $aliasDict->setKeyword(trim($val));
                            $aliasDict->setWikiId($id);
                            $aliasDict->save();
                        }
                    }
                    $titleDict = new Dict();
                    $titleDict->setKeyword(trim($title));
                    $titleDict->setWikiId($id);
                    $titleDict->save();
                }
        }
    }
}
