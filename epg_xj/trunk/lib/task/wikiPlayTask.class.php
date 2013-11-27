<?php
/**
 * 指定日期获取节目单并插入到 wikiPlay
 * @param <date>    定义日期
 * @param <int> 循环天数
 * @author luren
 */
class wikiPlayTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('date', null, sfCommandOption::PARAMETER_REQUIRED, 'the date ??', ''),
            new sfCommandOption('numdays', null, sfCommandOption::PARAMETER_REQUIRED, 'the number days ??' , 3)
            // add your own options here
        ));

        $this->namespace    = 'tv';
        $this->name         = 'wikiPlay';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [wikiPlay|INFO] task does things.
Call it with:
    [php symfony wikiPlay|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $program_repository    = $mongo->getRepository('Program');
        $wikiplay_repository = $mongo->getRepository('wikiPlay');

        $date = ($options['date']) ? date('Y-m-d', strtotime('-1 day', strtotime($options['date']))) : date('Y-m-d', time());
        $numdays = ($options['numdays']) ? ($options['numdays']) : 3;
        
        //循环删除已过已过期数据
        for($i = 1; $i < 30; $i++) {
                $expried = date('Y-m-d', strtotime('-'.$i.' day', strtotime($date)));
                $expriedCount = $wikiplay_repository->count(array('date' => $expried));
                
                if ($expriedCount > 0) {
                        for($j = 0; $j < $expriedCount; $j+=1000) {
                                $expriedPlays = $wikiplay_repository->getWikiPlayByDate($expried, 1000, $j);
                                if(!empty($expriedPlays)) {
                                        foreach ($expriedPlays as $play) {
                                                if ($play) $play->delete();
                                        }
                                }
                        }
                }
                printf("%s deleted %d rows\n", $expried, $expriedCount);
        }

        //循环获取指定日期数据
        for ($d = 1; $d <= $numdays; $d++) {
                $day = date('Y-m-d', strtotime('+'.$d.' day', strtotime($date)));
    
                //数据插入之前 先把原来的数据删除
                $wikiPlayCount = $wikiplay_repository->count(array('date' => $day));
                if ($wikiPlayCount > 0) {
                        for($i = 0; $i < $wikiPlayCount; $i+=1000) {
                                $wikiPlays = $wikiplay_repository->getWikiPlayByDate($day, 1000, $i);
                                if (!empty($wikiPlays)) {
                                        foreach($wikiPlays as $play) {
                                                if ($play) $play->delete();
                                        }
                                }
                        }
                }
                
                $provinces = Province::getProvince();
                foreach ($provinces as $name => $province) {
                        $channels = Doctrine::getTable('Channel')->getUserChannels('', $name);
                        $num = 0;
                        foreach ($channels as $channel) {
                                $programs = $program_repository->getProgramByDateAndChannelCode($day, $channel->getCode());
                                if (!empty($programs)) {
                                        $i = 0;
                                        foreach($programs as $program) {
                                                //判断 wikiId 是否为空
                                                $wiki_id = $program->getWikiId();
                                                if (empty($wiki_id)) continue;
                                                //根据省份、维基、日期判断是否存在该条维基记录 如果存在则跳过
                                                if ($wikiplay_repository->checkWikiPlayIsExist($wiki_id, $day, $province)) continue;

                                                $wikiPlay = new WikiPlay();
                                                $wikiPlay->setWikiId($wiki_id);
                                                $wikiPlay->setTags($program->getTags());
                                                $wikiPlay->setDate($program->getDate());
                                                $wikiPlay->setProvince($province);
                                                $wikiPlay->save();
                                                $i++;
                                        }
                                }
                                
                                $num += $i;
                        }
                        
                        printf("%s | %s : added %d rows \n", $day, $province, $num);
                }
        }
    }
}
