<?php

class tvWikiToDoubanTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'wikiToDouban';
        $this->briefDescription = '';
        $this->detailedDescription = 'tv:wikiToDouban|INFO';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");        
        $i = 0;
        $wiki_count = $wiki_repo->count(array("first_letter"=>null));
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
    
    protected function getDoubanIdByName($wiki)
    {
        $douban = "http://api.douban.com/movie/subjects?max-results=10&q=".$wiki->getTitle();
        $content = file_get_contents($douban);
        $content = str_replace("db:attribute","db_attribute",$content);
        $xmldocs = @simplexml_load_string($trim);
        foreach($xmldocs->entry as $entry) {
            $id = $this->getDoubanIdByUrl($entry->id);
            $infos = $this->getInfos($entry);
            if($wiki->getTitle() == $entry->title){
                return $id;
            }            
        }
        return null;
    }
    
    protected function getInfos($entry)
    {
        foreach($entry->db_attribute as $dba){
            $key = $this->getNameAttrs($dba);
            $attr[$key][] = (string)$dba;  
        }
        return $attr;
    }
    protected function getNameAttrs($s) 
    {
        if(isset($s)){
            foreach($s->attributes() as $key => $val) {
               if($key == "name") {
                  return (string)$val;
               }
            }  
        }
        return null;
    } 
    
    protected function getDoubanIdByUrl($url) {
        return str_replace("http://api.douban.com/movie/subject/","",$url);
    }
}