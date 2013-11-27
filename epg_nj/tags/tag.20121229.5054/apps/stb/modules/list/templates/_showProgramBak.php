<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php $class=array('one','two','three');?>
<?php $i=0;?>
<?php if($programList):?>
    <?php foreach ($programList as $program):?>
    <?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
    <?php $plan = time() - strtotime($program->getTime());?>
    <?php $width = round($plan/$all,2) * 100?>  
    <?php if($program->getWikiCover()!='1313030694207.png'&&$program->getWikiCover()!='1352173852807.jpg'):  //有图片才显示?>                
    <li class='program_list_gb'>
    	<a href="javascript:void(0);" onclick="goChannelByName('<?php echo $program->getSpName();?>',2,'visible');currentProgram();" onmouseover="showPlay('<?php echo $program->getSpName();?>','<?php echo $program->getWikiTitle();?>','<?php echo strtotime($program->getStartTime()->format("Y-m-d H:i:s"));?>','<?php echo strtotime($program->getEndTime()->format("Y-m-d H:i:s"));?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($program->getWikiCover(), 114, 152);?>" alt="<?php echo $program->getWikiCover();?>" /><span><em><?php echo $program->getWikiTitle();?></em></span></a>
        <?php if($i==100):?>
        <b class="<?php echo $class[$i];?>"></b>
        <?php endif;?>
    </li>
    <?php endif;?>
    <?php $i++;?>
    <?php endforeach;?>
    <!--不足则用点播填充-->
    <?php if(count($programList)<8):?>
        <?php foreach($wikis as $wiki):?>
        <?php if($wiki->getCover()!=''):  //有图片才显示?>          
        <li  class='program_list_gb'>
        	<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wiki->getCover(), 114, 152);?>" alt="" /><span><em><?php echo $wiki->getTitle();?></em></span></a>
        </li>
        <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
<?php else:?>
    <?php foreach($wikis as $wiki):?> 
    <?php //$wiki=$wikiRecommend ->getWiki();?>   
    <?php if($wiki->getCover()!=''):  //有图片才显示?>      
    <li  class='program_list_gb'>
    	<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wiki->getCover(), 114, 152);?>" alt="" /><span><em><?php echo $wiki->getTitle();?></em></span></a>
        <?php if($i==100):?>
        <b class="<?php echo $class[$i];?>"></b>
        <?php endif;?>
    </li>
    <?php endif;?>
    <?php $i++;?>
    <?php endforeach;?>
<?php endif;?>