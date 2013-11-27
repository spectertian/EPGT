<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php if($refer=='local'||$refer=='tcl'):?>
    <?php foreach($wikis as $wiki):?>     
    <?php if(!$wiki) continue;?> 
    <?php if($wiki->getCover()=='1313030694207.png') continue;?>             
	<li>
		<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>">
			<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" />
			<span><b><?php echo $wiki->getTitle();?></b></span>
		</a>
	</li>
	<?php endforeach;?>
<?php else: //运营中心的?>
    <?php 
          foreach($wikis as $wiki):
          if($wiki['poster']):
    ?>          
	<li>
		<a href="<?php echo $wiki['url']; ?>">
			<img src="<?php echo $wiki['poster'];?>" />
			<span><b><?php echo $wiki['Title'] ;?></b></span>
		</a>
	</li>
	<?php
          endif;
          endforeach;
    ?>
<?php endif;?>