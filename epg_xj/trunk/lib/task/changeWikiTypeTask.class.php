<?php
/**
 * 重建wiki的模型字段
 * 一次性执行
 * @author superwen
 */
class changeWikiTypeTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'changeWikiType';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [changeWikiType|INFO] task does things.
Call it with:

[php symfony changeWikiType|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('wiki');
        $options['query'] = array('tags'=>array('$ne'=>NULL));
        $options['fields'] = array('tags');
        $options['limit'] = 50;
        $options['sort'] = array("created_at" => 1);
        $wikiCount = $wiki_repository->count($options['query']);
            for($i=0; $i<=$wikiCount; $i=$i+50){
            $options['skip'] = $i;
            $wikiRes = $wiki_repository->find($options);
            foreach($wikiRes as $obj){
                $tags = $obj->getTags();
                $id = $obj->getId();
                $wiki = $wiki_repository->findOneById($id);
                $tag = $this->tag($tags);
                $wiki->setType($tag);
                $wiki->save();
            }
        }
    }
  
    private function tag(array $tags)
    {
        $tag = array('电影','电视剧','娱乐','少儿','科教', '财经','体育','综合');
        foreach($tags as $k=>$t){
            if(in_array($t,$tag)){
                return $t;
            }
        }
        return "综合";
    }
}
