<?php

/**
 * Repository of ProgramArchiver document.
 */
class ProgramArchiverRepository extends \BaseProgramArchiverRepository
{
    /**
     * 根据date,time,channel_code查找ProframArchiver
     * @param <type> $date
     * @param <type> $time
     * @param <type> $channel_code
     * @return <type>
     * @author ly
     */
    public  function getProframArchiver($date,$time,$channel_code){
        return $ret = $this->find(
            array(
                'query' => array(
                    "channel_code" => $channel_code,
                    'date' => $date,
                    'time' => $time,
                )
            )
        );
    }
}