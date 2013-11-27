<?php if($refer=='center'||$refer=='tongzhou'):?>
    <?php $i = 0 ;?>
    <?php foreach($wikis as $wiki) :?>
    <?php if($i < 4) :?>
    <li><a href="<?php echo $wiki['url']; ?>"><img src="<?php echo $wiki['poster'];?>" alt="<?php echo $wiki['Title'];?>"/><span><i><big><?php echo $wiki['Title'];?></big></i></span></a></li>
    <?php endif;?>
    <?php $i++;?>
    <?php endforeach;?>
<?php else:?>
    <?php foreach($wikis as $wiki):?>
    <?php if($k>=4) break;?>   
    <?php if(!$wiki) continue;?> 
    <?php if($wiki->getCover()=='') continue; else $k++;  //ÓÐÍ¼Æ¬²ÅÏÔÊ¾?>         
    <li>
       <a href="<?php echo url_for('wiki/show?id='.$wiki->getId().'&refer=list') ?>">
            <img src="<?php echo thumb_url($wiki->getCover(), 112, 152)?>" alt=""/>
            <span><i><big><?php echo $wiki->getTitle();?></big></i></span>
       </a> 
    </li>
    <?php endforeach;?>
<?php endif;?>