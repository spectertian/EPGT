<?php

/**
 * ProgramLive document.
 */
class ProgramLive extends \BaseProgramLive
{
	protected $channel = null;
	/**
	 * 获取关联的频道
	 * @return <obj>
	 * @author jhm
	 */
   public function getChannel() {
		if (! $this->channel) {
			$channel_code = $this->getChannelCode();
			$this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
		}
		return $this->channel;
	}
	/**
	 * 获取频道名称
	 * @return <string>
	 * @author jhm
	 */
	public function getChannelName() {
		$channel = $this->getChannel();
	
		$channel_name = '';
		if($channel) {
			$channel_name = $channel->getName();
		}
		return $channel_name;
	}
}