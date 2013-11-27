<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php $class=array('one','two','three');?>
<?php $i=0;?>
<?php if($programList):?>
    <?php foreach ($programList as $program):?>
    <?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
    <?php $plan = time() - strtotime($program->getTime());?>
    <?php $width = round($plan/$all,2) * 100?>                  
    <li class='program_list_gb'>
    	<a href="javascript:void(0);" onBlur="javascript:getmouseout(this);" onclick="goChannelByNameThis('<?php echo $program->getChannelName();?>');currentProgram();autohiddenPage();" onmouseover="showPlay('<?php echo $program->getChannelName();?>','<?php echo $program->getWikiTitle();?>','<?php echo $program->getStartTime()->format("H:i");?>','<?php echo $program->getEndTime()->format("H:i");?>','<?php echo $width;?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($program->getWikiCover(), 114, 152);?>" alt="" /><span><em><?php echo $program->getWikiTitle();?></em></span></a>
        <?php if($i==100):?>
        <b class="<?php echo $class[$i];?>"></b>
        <?php endif;?>
    </li>
    <?php $i++;?>
    <?php endforeach;?>
<?php else:?>
    <?php foreach($wikis as $wiki):?>      
    <?php //$wiki=$wikiRecommend ->getWiki();?>   
    <li  class='program_list_gb'>
    	<a onBlur="getmouseout(this);" href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wiki->getCover(), 114, 152);?>" alt="" /><span><em><?php echo $wiki->getTitle();?></em></span></a>
        <?php if($i==100):?>
        <b class="<?php echo $class[$i];?>"></b>
        <?php endif;?>
    </li>
    <?php $i++;?>
    <?php endforeach;?>
<?php endif;?>