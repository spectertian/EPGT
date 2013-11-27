<?php

/**
 * Repository of Television document.
 */
class TelevisionRepository extends \BaseTelevisionRepository
{
    public function getChannelTelevisions($channel_code) {
        return $this->find(array(
                            'query'=>array(
                                "channel_code"=>$channel_code,
                            )
        ));
    }

}