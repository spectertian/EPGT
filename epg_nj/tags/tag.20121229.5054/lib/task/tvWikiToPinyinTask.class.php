<?php

class tvWikiToPinyinTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'wikiToPinyin';
        $this->briefDescription = '';
        $this->detailedDescription = 'tv:wikiToPinyin|INFO';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");        
        $i = 0;
        $wiki_count = $wiki_repo->count(array("first_letter"=>null));
        while ($i < $wiki_count) 
        {
            //$wikis = $wiki_repo->find(array("query"=>array("first_letter"=>null), "skip" => $i, "limit" => 50));
            $wikis = $wiki_repo->find(array("query"=>array("first_letter"=>null), "limit" => 50));
            foreach ($wikis as $wiki) 
            {
                $pinyin = Common::Pinyin($wiki->getTitle(),true);
                $wiki->setFirstLetter($pinyin);
                echo $wiki->getTitle() . "\t" . $pinyin . "\n";
                $wiki->save();
            }
            $i = $i + 50;
            echo $i,'*************************************',"\n";     
        }	
        echo "finished! \n";
    }
}