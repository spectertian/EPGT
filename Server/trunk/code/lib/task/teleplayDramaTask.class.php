<?php
/**
 * 处理电视剧分集剧情
 * @author luren
 */
class teleplayDramaTask extends sfMondongoTask
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
    $this->name             = 'teleplayDrama';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [wikMetaCreate|INFO] task does things.
Call it with:

  [php symfony wikMetaCreate|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $mongo = $this->getMondongo();
    $wikiRepos = $mongo->getRepository('Wiki');
    $wikiMetaRepos = $mongo->getRepository('WikiMeta');
    
    $v = true;
    $i = 0;
    $num = 0;
    while($v) {
        $teleplays = $wikiRepos->find(
                            array(
                                  'query' => array('model' => 'teleplay'),
                                  'skip' => $i,
                                  'limit' => 100
                          )
                    );
       
        if (!is_null($teleplays)) {
            foreach ($teleplays as $wiki) {
                $dramas = $wiki->getDrama();
                if ($dramas) {
                    foreach ($dramas as $key => $data) {
                        $meta = $wikiMetaRepos->getMetaByTitle($data['title']);
                        if (is_null($meta)) {
                            $wikiMeta = new WikiMeta();
                            $wikiMeta->setWikiId((string) $wiki->getId());
                            $wikiMeta->setTitle($data['title']);
                            $wikiMeta->setContent($data['content']);
                            $wikiMeta->setMark($key);
                            $wikiMeta->save();
                            $num++;
                        }
                    }
                }

                $wiki->setDrama(null);
                $wiki->save();
            }
        } else {
            $v = false;
        }

        $i += 100;
        printf("insert %d rows \n", $num);
    }
    // add your code here
  }

}
