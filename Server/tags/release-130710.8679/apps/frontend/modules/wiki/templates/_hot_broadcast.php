    <?php if ($hotBroadcast) :?>
    <div class="mod" id="top">
      <div class="hd">
        <h3><?php echo $model?></h3>
        <!--
        <div class="more"><a href="#">更多&gt;&gt;</a></div>
        -->
      </div>
      <div class="bd">
        <ol>
          <?php foreach ($hotBroadcast as $Recommend):?>
          <?php $wiki = $Recommend->getWiki()?>
          <?php if(!$wiki)  continue;?>
          <li><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>"><?php echo $wiki->getTitle()?></a>
              <?php if($wiki->getEpisodes()) :?>
              <span><?php echo $wiki->getEpisodes()?> 集全</span>
              <?php endif;?>
          </li>
          <?php endforeach;?>
        </ol>
      </div>
    </div>
    <?php endif;?>