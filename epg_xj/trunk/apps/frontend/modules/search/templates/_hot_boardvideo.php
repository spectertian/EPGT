<div class="mod" id="top">
  <div class="hd">
    <h3>影视热播榜</h3>
    <div class="more"><a href="#">更多&gt;&gt;</a></div>
  </div>
  <div class="bd">
    <?php if(!empty($hot_boardvideo)):?>
    <ol>
      <?php
            foreach ($hot_boardvideo as $o_hotBroadcast):
            $wiki = $o_hotBroadcast->getWiki();
      ?>    
      <li><a href="<?php echo url_for("wiki/show?slug=".$wiki->getTitle()); ?>"><?php echo $wiki->getTitle(); ?></a><span style="display:none;">30集全</span></li>
     <?php endforeach;?> 
    </ol>
    <?php endif;?>
  </div>
</div>