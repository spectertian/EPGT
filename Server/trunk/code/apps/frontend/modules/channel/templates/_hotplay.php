<?php if($Hotplay):?>
<div class="mod" id="top">
  <div class="hd">
    <h3>影视热播榜</h3>
    <!--<div class="more"><a href="#">更多&gt;&gt;</a></div>-->
  </div>
  <div class="bd">
    <ol>
      <?php
            foreach ($Hotplay as $hotplay) :
            $wiki = $hotplay->getWiki();
            if (!$wiki)  continue;
      ?>
      <li>
          <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()); ?>"><?php echo $wiki->getTitle(); ?></a>
          <?php if ($wiki->getEpisodes()) :?>
          <span style="display:none;"><?php echo $wiki->getEpisodes()?> 集全</span>
          <?php endif;?>
      </li>
     <?php endforeach;?>
    </ol>
  </div>
</div>
<?php endif;?>