<?php
/**
 * 删除epg临时数据文件
 * 每天23:50定时删除tmp/epg中的本地节目数据文件
 *  @author: lifucang
 */
class tvDelNJBCFolderTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'DelNJBCFolder';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:DelNJBCFolder|INFO] task does things.
Call it with:

  [php symfony tv:DelNJBCFolder|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $fsc=new FSC();
        $fsc->delfolderOne('./tmp/epg/');
        echo "finished\n";
    }
}
