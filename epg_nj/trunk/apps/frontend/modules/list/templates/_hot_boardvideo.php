<?php if($hot_boardvideo):?>
<div class="mod" id="top">
  <div class="hd">
    <h3><?php echo $tag?>热播榜</h3>
    <!--<div class="more"><a href="#">更多&gt;&gt;</a></div>-->
  </div>
  <div class="bd">
    <ol>
      <?php
            foreach ($hot_boardvideo as $boardvideo) :
            $wiki = $boardvideo->getWiki();
                if($wiki):
      ?>    
      <li>
          <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()); ?>"><?php echo $wiki->getTitle(); ?></a>
          <?php if ($wiki->getEpisodes()) :?>
          <span style="display:none;"><?php echo $wiki->getEpisodes()?> 集全</span>
          <?php endif;?>
      </li>
     <?php 
                endif;
            endforeach;
     ?> 
    </ol>
  </div>
</div>
<?php endif;?>