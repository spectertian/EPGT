<?php

class tvCheckWikiCoverTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        sfConfig::set('app_photo1_config', array('hosts' => '172.31.201.101:6001', 'domain' => 'epg', 'class' => 'image'));
        sfConfig::set('app_photo1_type', 'MogilefsStorage');
        sfConfig::set('app_static1_url','http://image.epg.huan.tv/');
        
        $this->namespace        = 'tv';
        $this->name             = 'checkWikiCover';
        $this->briefDescription = '';
        $this->detailedDescription = 'tv:checkWikiCover|INFO';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");        
        $i = 0;
        $wiki_count = $wiki_repo->count(array("cover"=>array('$exists' => true)));
        while ($i < $wiki_count) 
        {
            $wikis = $wiki_repo->find(array("query"=>array("first_letter"=>null), "limit" => 50));
            foreach ($wikis as $wiki) 
            {
                $pinyin = Common::Pinyin($wiki->getTitle(),true);
                $wiki->setFirstLetter($pinyin);
                echo $wiki->getTitle() . "\t" . $pinyin . "\n";
                $wiki->save();
            }
            $i = $i + 50;    
        }	
        echo "finished! \n";
    }
}