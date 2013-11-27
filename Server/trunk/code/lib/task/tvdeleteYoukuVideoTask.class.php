<?php
/**
 * 删除优酷视频脚本
 * 一次性执行
 * @author luren
 */
class tvdeleteYoukuVideoTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'deleteYoukuVideo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tvdeleteYoukuVideo|INFO] task does things.
Call it with:

  [php symfony tvdeleteYoukuVideo|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
 // initialize the database connection
    $mongo = $this->getMondongo();
    $videoRepos = $mongo->getRepository('Video');
    $videoPlayListRepos = $mongo->getRepository('VideoPlayList');
    
    $v = true;
    $num = 0;
    // delele youku video
    while($v) {
        $videos = $videoRepos->find(array(
                                   'query' => array(
                                       'referer' => 'youku'
                                   ),
                                   'limit' => 100
                                )
                            );
        if ($videos) {
            foreach ($videos as $video) {
                $video->delete();
                $num++;
            }

            printf("delele youku video %d rows \n", $num);
        } else {
            $v = false;
            printf("delele youku video successfully ..\n");
        }
    }
    
    $v2 = true;
    $num = 0;
    // delele youku video_playlist
    while($v2) {
        $PlayList = $videoPlayListRepos->find(array(
                                            'query' => array(
                                               'referer' => 'youku'
                                            ),
                                            'limit' => 100
                                        )
                                    );
        if ($PlayList) {
            foreach($PlayList as $playlist) {
                $playlist->delete();
                $num++;
            }
           
            printf("delele youku video_playlist %d rows \n", $num);
        } else {
            $v2 = false;
            printf("delele youku video_playlist successfully .. \n");
        }
    }
  }
}
