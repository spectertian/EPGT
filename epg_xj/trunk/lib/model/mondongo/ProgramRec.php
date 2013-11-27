<?php

/**
 * ProgramRec document.
 */
class ProgramRec extends \BaseProgramRec
{
    protected $wiki = null;
    protected $channel = null;
    /**
     * 获取关联的频道
     * @return <obj>
     * @author pjl
     */
    public function getChannel() {
        if (! $this->channel) {
            $channel_code = $this->getChannelCode();
            $this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
        }
        return $this->channel;
    }
}