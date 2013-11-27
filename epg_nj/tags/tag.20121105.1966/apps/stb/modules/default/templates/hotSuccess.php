<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
	<div class="smartnav">
        <?php if($programTop):?>
        <?php $all = strtotime($programTop->getEndTime()->format("Y-m-d H:i:s")) - strtotime($programTop->getTime());?>
    	<?php $plan = time() - strtotime($programTop->getTime());?>
    	<?php $width = round($plan/$all,2) * 100?> 
		<h2 id="tvplay"><i><?php echo $programTop->getChannelName();?>：<?php echo $programTop->getWikiTitle()?></i> <span class="time"><span class="timebg"><b class="timenow" style="width:<?php echo $width?>%"></b></span><?php echo $programTop->getStartTime()->format("H:i");?></span></h2>
        <?php else:?>
        <h2 id="tvplay"></h2>
        <?php endif;?>
        <div class="snav clr">
			<h3><a href="#">节目详情</a><a href="#">节目表</a></h3>
			<ul>
                <?php foreach ($types as $type):?>
				<li><a href="<?php echo url_for('list/index?type='.$type) ?>" <?php echo ($tag==$type)?' class="there"':'';?>><?php echo $type;?></a></li>
                <?php endforeach;?>
			</ul>
		</div>
		
		<div class="snavlisth">
			<ul class="snavlist">
                <?php $i=0;?>
                <?php $class=array('one','two','three');?>
				<?php foreach ($programList as $program):?>
				<?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
				<?php $plan = time() - strtotime($program->getTime());?>
				<?php $width = round($plan/$all,2) * 100?>                  
				<li>
					<a href="#" onclick="hidPlay();return goChannelByName('<?php echo $program->getChannelName();?>');" onmouseover="showPlay('<?php echo $program->getChannelName();?>','<?php echo $program->getWikiTitle();?>','<?php echo $program->getStartTime()->format("H:i");?>','<?php echo $program->getEndTime()->format("H:i");?>','<?php echo $width;?>');" onmouseout="hidPlay()"><img src="<?php echo thumb_url($program->getWikiCover(), 114, 152);?>" alt="" /><span><?php echo mb_strcut($program->getWikiTitle(), 0, 12, 'utf-8');?></span></a>
				    <?php if($i<3):?>
                    <b class="<?php echo $class[$i];?>"></b>
                    <?php endif;?>
                </li>
                <?php $i++;?>
                <?php endforeach;?>
			</ul>
		</div>
	</div>