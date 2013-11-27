<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php $i=1;?>
<?php if($refer=='local'||$refer=='tcl'):?>
    <?php foreach($wikis as $wiki):?>  
    <?php if($i>8) break;?>   
    <?php if(!$wiki) continue;?> 
    <?php if($wiki->getCover()!=''):?>             
	<li>
		<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>">
			<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" />
			<span><b><?php echo $wiki->getTitle();?></b></span>
		</a>
	</li>
    <?php $i++;?>
    <?php endif;?>
	<?php endforeach;?>
<?php else: //运营中心和同洲的?>
    <?php 
          foreach($wikis as $wiki):
          if($i>8) break;  
          if($wiki['poster']):
    ?>          
	<li>
		<a href="<?php echo $wiki['url']; ?>">
			<img src="<?php echo $wiki['poster'];?>" />
			<span><b><?php echo $wiki['Title'] ;?></b></span>
		</a>
	</li>
	<?php
              $i++;
          endif;
          endforeach;
    ?>
<?php endif;?>