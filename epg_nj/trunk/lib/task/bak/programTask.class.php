<?php

class programTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('table', sfCommandArgument::OPTIONAL, 'Please input the syn table', 'program'),
            new sfCommandArgument('date', sfCommandArgument::OPTIONAL, 'Please input the from date', date('Y-m-d'))
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
                // add your own options here
        ));

        $this->namespace = 'tv';
        $this->name = 'program';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [task|INFO] task does things.
Call it with:

  [php symfony task|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
//        ini_set('memory_limit', '128M');

        // initialize the database connection
//        $databaseManager = new sfDatabaseManager($this->configuration);
//        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        if (method_exists($this, $arguments['table'])) {
            $this->$arguments['table']($arguments, $options);
        } else {
            $this->log('nothing...');
        }
    }

    /**
     * 同步 tvStation
     * @param array $arguments
     * @param array $options
     */
    public function tvStation($arguments, $options) {
        $tvprogrambot = $this->connectTvProgram();

        $tv_stations = $tvprogrambot->fetchAll('SELECT * FROM tv_station');

        $this->connectMaster($options);

        $parent_key = array();
        foreach ($tv_stations as $i => $tv) {
            $this->log($tv['name']);
            $tv_station = new TvStation();
//
            $tv_station->setName($tv['name']);
            $tv_station->setSort(0);
            $tv_station->save();
            if (!$tv['parent_id']) {
                $parent_key['key' . $tv['id']] = $tv_station->getId();
                $parent_id = 0;
            } else {
                $parent_id = $parent_key['key' . $tv['parent_id']];
            }
//
            $tv_station->setParentId($parent_id);
            $tv_station->save();
        }
    }

    /**
     * 同步频道
     * @param array $arguments
     * @param array $options
     */
    public function channel($arguments, $options) {
        $tvprogrambot = $this->connectTvProgram();
        $tv_stations = $tvprogrambot->fetchAll('SELECT * FROM tv_station');
        $channels = $tvprogrambot->fetchAll('SELECT * FROM channel');

        $this->connectMaster($options);

        $tv = array();
        foreach ($tv_stations as $i => $tv_station) {
            $tv['key' . $tv_station['id']] = Doctrine::getTable('TvStation')->findOneByName($tv_station['name'])->getId();
        }

        foreach ($channels as $key => $c) {
            $this->log($c['name']);
            $_channel = Doctrine::getTable('Channel')->findOneByCode($c['code']);
            if ($_channel) {
                $channel = $_channel;
            } else {
                $channel = new Channel();
            }
            $channel->setTvStationId($tv['key' . $c['tv_station_id']]);
            $channel->setName($c['name']);
            $channel->setCode($c['code']);
            $channel->setMemo($c['memo']);
            $channel->setPublish(1);
            $channel->save();
        }
    }

    /**
     * 同步节目单 默认同步当天 from_date = to_date = today
     * @param array $arguments
     * @param array $options
     */
    public function program($arguments, $options) {
        $this->log('The date is ' . $arguments['date']);
        $this->connectMaster($options);
        $channels = Doctrine::getTable('Channel')->findAll();
//        $channel_ids = array();
        $programAutosyns = array();
        foreach ($channels as $j => $channel) {
//            $channel_ids[] = $channel->getId();
            $programAutosyn = Doctrine::getTable('ProgramAutosyn')->createQuery()
                            ->where('date = ?', $arguments['date'])
                            ->andWhere('channel_id = ?', $channel->getId())
                            ->fetchOne();
            
            if (!$programAutosyn) {
                $programAutosyn = new ProgramAutosyn();
                $programAutosyn->setDate($arguments['date']);
                $programAutosyn->setProgramId(0);
                $programAutosyn->setChannelId($channel->getId());
                $programAutosyn->save();
            }

            $program_id = $programAutosyn->getProgramId();

            $tvprogrambot = $this->connectTvProgram();
            $programs = $tvprogrambot->fetchAll("SELECT * FROM program WHERE date = ? AND channel_code = ? AND id > ?", array($arguments['date'], $channel->getCode(), $program_id));
            $this->connectMaster($options);

            $i = 0;
            foreach ($programs as $i => $p) {
                $program = new Program();
                $program->setDate($p['date']);
                $program->setTime($p['time']);
                $program->setName($p['name']);
                $program->setChannelId($channel->getId());
                $program->setPublish(1);
                $program->save();
                $this->log($p['name'] . '---' . $p['id']);
                $programAutosyn->setProgramId($p['id']);
                $programAutosyn->save();
            }
            $this->log('last ' . $i);
            unset($programs);
            unset($tvprogrambot);
            unset($programAutosyn);
        }
    }

    /**
     * 连接 TvProgram 数据库
     * @return
     */
    private function connectTvProgram() {
        $tvprogrambot = Doctrine_Manager::getInstance()->connection('mysql://tvprogram:8eTFwdTE28xrNhMH@192.168.1.51/tvprogram', 'tvprogrambot');
//        $tvprogrambot = Doctrine_Manager::getInstance()->connection('mysql://tvprogram:8eTFwdTE28xrNhMH@p.mozitek.com/tvprogram', 'tvprogrambot');
        Doctrine_Manager::connection()->setCharset('utf8');
        Doctrine_Manager::connection()->setCollate('utf8_general_ci');
        return $tvprogrambot;
    }

    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }

}

