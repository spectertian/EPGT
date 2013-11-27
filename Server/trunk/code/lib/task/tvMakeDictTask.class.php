<?php
/**
 * @author qhm
 */
class tvMakeDictTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace    = 'tv';
        $this->name         = 'MakeDict';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [makeDict|INFO] task does things.
Call it with:
    [php symfony makeDict|INFO]
EOF;
    }
    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('wiki'); 
        $i = 0;
        while (true) {
		        $options['query'] = array('title'=>array('$ne'=>NULL), 'model'=>'film');
		        $options['fields'] = array('title','alias','model');
		        $options['limit'] = 100;
		        $options['skip'] = $i;
		        $wikiRes = $wiki_repository->find($options);
		        $wikiNum = count($wikiRes);
		        if($wikiNum > 0) {
	                foreach($wikiRes as $obj){
	                    $title = $obj->getTitle();
	                    echo $title;
	                    echo "\n";
	                    $alias = $obj->getAlias();
	                    $id = $obj->getId();
	                    if(!empty($alias)){
	                        foreach($alias as $val){
	                            if(trim($val)==trim($title)){
	                                continue;
	                            }
	                            $Dict = $mongo->getRepository("Dict");
	                            $m= $Dict->getDictByName(trim($val));
	                            if(empty($m)){
	                            	echo "\n";
	                            	echo "bbbbbbbbbbbbbbbbbbb";
	                            $aliasDict = new Dict();
	                            $aliasDict->setName(trim($val));
	                            $aliasDict->save();
	                            }
	                        }
	                    }
	                    $Dict_s = $mongo->getRepository("Dict");
	                    $mm= $Dict_s->getDictByName(trim($title));
	                    if(empty($mm)){
	                  	echo "\n";
	                    echo "aaaaaaaaaaaaaaaa";
	                    $titleDict = new Dict();
	                    $titleDict->setName(trim($title));
	                    $titleDict->save();
	                    }
	                }
			   }else{
			    	break;
			   }
			  $i += 100;
       }
       
    }
}
