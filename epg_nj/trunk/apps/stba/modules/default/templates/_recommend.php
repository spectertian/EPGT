<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php $i=1;?>
<?php if($refer=='local'||$refer=='tcl'):?>
    <?php foreach($wikis as $wiki):?>  
    <?php if($i>6) break;?>   
    <?php if(!$wiki) continue;?> 
    <?php if($wiki->getCover()!=''):?>             
	<li>
		<a href="<?php echo url_for('wiki/show?id='.$wiki->getId()) ?>" title="" class="there"  onmouseover="showNum('num2',<?php echo $i?>)">
			<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" alt=""/>
			<span>
				<i><big><?php echo $wiki->getTitle();?></big></i>
			</span>
		</a>
	</li>
    <?php $i++;?>
    <?php endif;?>
	<?php endforeach;?>
<?php elseif($refer=='center'): //运营中心?>
    <?php 
          foreach($wikis as $wiki):
          if($i>6) break;  
          if($wiki['poster']):
              $urls=explode('&amp;backurl=',$wiki['url']);
              $url = $urls[0]."&param2=bokong&param3=CF&backurl=".$urls[1];
              //$url = $wiki['url']."&param2=bokong&param3=CF";
    ?>  
	<li>
		<a href="<?php echo $url; ?>" title="" class="there" onmouseover="showNum('num2',<?php echo $i?>)">
			<img src="<?php echo $wiki['poster'];?>" alt=""/>
			<span>
				<i><big><?php echo $wiki['Title'] ;?></big></i>
			</span>
		</a>
	</li>        
	<?php
              $i++;
          endif;
          endforeach;
    ?>
<?php else: //同洲的?>
    <?php 
          foreach($wikis as $wiki):
          if($i>6) break;  
          if($wiki['poster']):
    ?>  
	<li>
		<a href="<?php echo $wiki['url']; ?>" title="" class="there" onmouseover="showNum('num2',<?php echo $i?>)">
			<img src="<?php echo $wiki['poster'];?>" alt=""/>
			<span>
				<i><big><?php echo $wiki['Title'] ;?></big></i>
			</span>
		</a>
	</li>        
	<?php
              $i++;
          endif;
          endforeach;
    ?>
<?php endif;?>