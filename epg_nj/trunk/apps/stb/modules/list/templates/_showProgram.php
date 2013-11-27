<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php $class=array('one','two','three');?>
<?php $i=0;?>
<?php if($programList):?>
    <?php $n=0;?>
    <?php foreach ($programList as $program):?>
    <?php $wikia=$program->getWiki();?>
    <?php if(!$wikia||!$wikia->getCover()) continue;?>
    <?php if($i<5):?>
    <?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
    <?php $plan = time() - strtotime($program->getTime());?>
    <?php $width = round($plan/$all,2) * 100?>                 
    <?php if($wikia->getTitle()=='新闻联播'&&$n>0):
              continue;
          else:
    ?>
    <li class='program_list_gb'> 
    	<a href="javascript:void(0);" onclick="goChannelByName('<?php echo $program->getSpName();?>',2,'visible');currentProgram();" onmouseover="showPlay('<?php echo $program->getSpName();?>','<?php echo $wikia->getTitle();?>','<?php echo strtotime($program->getStartTime()->format("Y-m-d H:i:s"));?>','<?php echo strtotime($program->getEndTime()->format("Y-m-d H:i:s"));?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wikia->getCover(), 114, 152);?>" alt="<?php echo $wikia->getCover();?>" /><span><em><?php echo $wikia->getTitle();?></em></span></a>       
        <b></b>
    </li>
    <?php     $i++;?>
    <?php endif;?>
    <?php if($wikia->getTitle()=='新闻联播'){$n++;}?>
    <?php endif;?>
    <?php endforeach;?>
    <!--用点播填充-->
    <?php if($refer=='center'||$refer=='tongzhou'):?>
        <?php foreach($wikis as $wiki):?>
        <?php if($i>=10) break;?>
        <?php if($wiki['poster']=='') continue; else $i++;  //有图片才显示?>          
        <li class='program_list_gb'>
        	<a href="<?php echo $wiki['url'];?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo $wiki['poster'];?>" alt="" /><span><em><?php echo $wiki['Title'];?></em></span></a>
        </li>
        <?php endforeach;?>
    <?php else:?>
        <?php foreach($wikis as $wiki):?>
        <?php if($i>=10) break;?>   
        <?php if(!$wiki) continue;?> 
        <?php if($wiki->getCover()=='') continue; else $i++;  //有图片才显示?>         
        <li  class='program_list_gb'>
        	<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug().'&refer=list') ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wiki->getCover(), 114, 152);?>" alt="" /><span><em><?php echo $wiki->getTitle();?></em></span></a>
        </li>
        <?php endforeach;?>
    <?php endif;?>

<?php else:?>
    <?php if($refer=='center'||$refer=='tongzhou'):?>
        <?php foreach($wikis as $wiki):?> 
        <?php if($wiki['poster']!=''):  //有图片才显示?>      
        <li class='program_list_gb'>
        	<a href="<?php echo $wiki['url'];?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo $wiki['poster'];?>" alt="" /><span><em><?php echo $wiki['Title'];?></em></span></a>
        </li>
        <?php $i++;?>
        <?php endif;?>
        <?php endforeach;?>
    <?php else:?>
        <?php foreach($wikis as $wiki):?>    
        <?php if(!$wiki) continue;?> 
        <?php if($wiki->getCover()!=''):  //有图片才显示?>      
        <li class='program_list_gb'>
        	<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug().'&refer=list') ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');" id="<?php echo "a".$i;?>" <?php if($i % 8 == 7) echo "class='last'" ?> <?php if($i % 8 == 0) echo "class='first'"?>><img src="<?php echo thumb_url($wiki->getCover(), 114, 152);?>" alt="" /><span><em><?php echo $wiki->getTitle();?></em></span></a>
        </li>
        <?php $i++;?>
        <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
<?php endif;?>