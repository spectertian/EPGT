<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php $class=array('one','two','three');?>
<?php $i=0;?>
<?php if($programList):?>
    <?php $n=0;?>
    <?php foreach ($programList as $program):?>
    <?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
    <?php $plan = time() - strtotime($program->getTime());?>
    <?php $width = round($plan/$all,2) * 100?>  
    <?php if($program->getWikiCover()!='1313030694207.png'&&$program->getWikiCover()!='1352173852807.jpg'&&$program->getWikiCover()!='1354241942343.jpg'):  //有图片才显示?>                
    <?php if($program->getWikiTitle()=='新闻联播'&&$n>0){continue;}?>
    <li class='program_list_gb'>
    	<a href="javascript:void(0);" onclick="goChannelByName('<?php echo $program->getSpName();?>',2,'visible');currentProgram();" onmouseover="showPlay('<?php echo $program->getSpName();?>','<?php echo $program->getWikiTitle();?>','<?php echo strtotime($program->getStartTime()->format("Y-m-d H:i:s"));?>','<?php echo strtotime($program->getEndTime()->format("Y-m-d H:i:s"));?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($program->getWikiCover(), 114, 152);?>" alt="<?php echo $program->getWikiCover();?>" /><span><em><?php echo $program->getWikiTitle();?></em></span></a>
        <?php if($i<3):?>
        <b class="<?php echo $class[$i];?>"></b>
        <?php endif;?>
    </li>
    <?php $i++;?>
    <?php endif;?>
    <?php if($program->getWikiTitle()=='新闻联播'){$n++;}?>
    <?php endforeach;?>
    <!--不足则用点播填充-->
    <?php //if(count($programList)<8):
         if($i<8):
    ?>
        <?php if($refer=='center'):?>
            <?php foreach($wikis as $wiki):?>
            <?php if($wiki['poster']=='') continue;  //有图片才显示?>          
            <li class='program_list_gb'>
            	<a href="<?php echo $wiki['url'];?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo $wiki['poster'];?>" alt="" /><span><em><?php echo $wiki['Title'];?></em></span></a>
            </li>
            <?php endforeach;?>
        <?php else:?>
            <?php foreach($wikis as $wiki):?>
            <?php if(!$wiki) continue;?> 
            <?php if($wiki->getCover()==''||$wiki->getCover()=='1313030694207.png'||$wiki->getCover()=='1352173852807.jpg') continue;  //有图片才显示?>         
            <li  class='program_list_gb'>
            	<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wiki->getCover(), 114, 152);?>" alt="" /><span><em><?php echo $wiki->getTitle();?></em></span></a>
            </li>
            <?php endforeach;?>
        <?php endif;?>
    <?php endif;?>
<?php else:?>
    <?php if($refer=='center'||$refer=='tongzhou'):?>
        <?php foreach($wikis as $wiki):?>    
        <?php if($wiki['poster']!=''):  //有图片才显示?>      
        <li  class='program_list_gb'>
        	<a href="<?php echo $wiki['url'];?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo $wiki['poster'];?>" alt="" /><span><em><?php echo $wiki['Title'];?></em></span></a>
            <?php if($i<3):?>
            <b class="<?php echo $class[$i];?>"></b>
            <?php endif;?>
        </li>
        <?php $i++;?>
        <?php endif;?>
        <?php endforeach;?>
    <?php else:?>
        <?php foreach($wikis as $wiki):?>    
        <?php if(!$wiki) continue;?> 
        <?php if($wiki->getCover()!=''&&$wiki->getCover()!='1313030694207.png'&&$wiki->getCover()!='1352173852807.jpg'):  //有图片才显示?>      
        <li  class='program_list_gb'>
        	<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wiki->getCover(), 114, 152);?>" alt="" /><span><em><?php echo $wiki->getTitle();?></em></span></a>
            <?php if($i<3):?>
            <b class="<?php echo $class[$i];?>"></b>
            <?php endif;?>
        </li>
        <?php $i++;?>
        <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
<?php endif;?>