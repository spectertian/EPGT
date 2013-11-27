<?php
/**
 * 测试根据传入的信息选择wiki插入
 * @author lizhi
 * @version $Id programWikiTask.php 2011-05-25 11:15 $
 */
class programWikiTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stb'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'programWiki';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [programWiki|INFO] task does things.
Call it with:

  [php symfony programWiki|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
     //var_dump($testData);
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $mongo = $this->getMondongo();
    $wiki_repository = $mongo->getRepository('wiki');
    $options['fields'] = array('title','tags');
    $testData = $this->getContent("D:/project/tvprogrambot/testForenotice.CSV");
    foreach($testData as $val)
    {
        $top = $this->scwsKeyword($val[1]);
        $options['query']=array('title'=>$top);
        $wikiRes = $wiki_repository->findOne($options);
        if(!empty($wikiRes)){
            print_r($wikiRes);// print the array and update wiki_id
        }else{
            //log the keyword message
        }
        sleep(2);
    }
    // add your code here
  }
/**
 * 获得相应的信息
 * @param string $filename
 * @return void
 */
  private function getContent($filename = 'Forenotice.CSV')
  {
      if(!is_file($filename)){
          return false;
      }
      $handle = fopen($filename,"r");
      $res = array();
      $i = 0;
      while ($data = fgetcsv($handle, 1000, ",")) {
          $res[$i] = $data;
          $i++;
      }
      return $res;
  }
  /**
   * 根据相应传入的信息进行分词的权重处理
   * @param string $keywork
   * @return void
   */
  private function scwsKeyword($keyword)
  {
      $sh = scws_open();
      scws_set_charset($sh, 'utf-8');
      scws_set_dict($sh, 'D:/project/Test/dict.utf8.xdb');
      scws_set_dict($sh, 'D:/project/Test/newdict.utf8.xdb');
      scws_set_rule($sh, 'D:/project/Test/rules.ini');
      scws_send_text($sh, $keyword);
      $top = scws_get_tops($sh, 5);
      $top = !empty($top) ? $top[0]['word'] : $keyword;
      return $top;
  }
}
