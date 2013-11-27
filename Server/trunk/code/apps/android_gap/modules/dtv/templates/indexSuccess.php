<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
		<h2 class="tit"><a href="/dtv/channel" class="plist">频道</a>分类查看</h2>
		<?php foreach($live_tags as $key => $tag):?>
			<?php include_component('default','liveList',array('key'=>$key,'tag'=>$tag,'module'=>'dtv'));?>
		<?php endforeach;?>