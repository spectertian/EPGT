<script type="text/javascript">
    $(document).ready(function(){
         $('.program-recommended').find("div:eq(0) > UL > li").each(function(x,e){
             var index = x;
             $(this).click(function(){
                 $('.program-recommended').find("div:eq(0) > UL > li").removeClass('active');
                 $(this).addClass('active');
                 $('.program-recommended').find('DIV[class=bd]').css('display','none').each(function(x,e){
                     if(x == index){
                         $(this).css('display','block');
                     }
                 });
             });
         });
    });
</script>
<div class="module program-recommended">
    <div class="hd">
      <ul>
        <li class="active"><a href="#" onclick="return false;">电视剧</a></li>
        <li><a href="#" onclick="return false;">电影</a></li>
        <li><a href="#" onclick="return false;">体育</a></li>
        <li><a href="#" onclick="return false;">娱乐</a></li>
        <li><a href="#" onclick="return false;">少儿</a></li>
        <li><a href="#" onclick="return false;">科教</a></li>
        <li><a href="#" onclick="return false;">财经</a></li>
        <li class="last"><a href="#" onclick="return false;">综合</a></li>
      </ul>
    </div>
    <div id="drama" class="bd" style="display:block;">
      <ul>
         <?php if($tvplays): ?>
         <?php foreach($tvplays as $tvplay): ?>
          <?php if($tvplay->getWiki()): ?>
            <li>
              <?php if($tvplay->getWiki()->getCoverUrl()): ?>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$tvplay->getWiki()->getId() ?>"><img src="<?php echo $tvplay->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $tvplay->getWiki()->getTitle()?>"></a></div>
              <?php endif;?>
              <a href="<?php echo url_for('wiki/show?id=').$tvplay->getWiki()->getId() ?>"><?php echo $tvplay->getName() ?></a>
            </li>
        <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
    <div id="film" class="bd">
      <ul>
        <?php if($movies): ?>
        <?php foreach($movies as $movie): ?>
          <?php if($movie->getWiki()): ?>
            <li>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$movie->getWiki()->getId() ?>"><img src="<?php echo $movie->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $movie->getWiki()->getTitle()?>"></a></div>
              <a href="<?php echo url_for('wiki/show?id=').$movie->getWiki()->getId() ?>"><?php echo $movie->getName() ?></a>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
    <div id="sport" class="bd">
      <ul>
        <?php if($sports): ?>
        <?php foreach($sports as $sport): ?>
          <?php if($sport->getWiki()): ?>
            <li>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$sport->getWiki()->getId() ?>"><img src="<?php echo $sport->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $sport->getWiki()->getTitle()?>"></a></div>
              <a href="<?php echo url_for('wiki/show?id=').$sport->getWiki()->getId() ?>"><?php echo $sport->getName() ?></a>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
    <div id="ent" class="bd">
      <ul>
        <?php if($ents): ?>
        <?php foreach($ents as $ent): ?>
          <?php if($ent->getWiki()): ?>
            <li>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$ent->getWiki()->getId() ?>"><img src="<?php echo $ent->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $ent->getWiki()->getTitle()?>"></a></div>
              <a href="<?php echo url_for('wiki/show?id=').$ent->getWiki()->getId() ?>"><?php echo $ent->getName() ?></a>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
    <div id="children" class="bd">
      <ul>
        <?php if($childrens): ?>
        <?php foreach($childrens as $children): ?>
          <?php if($children->getWiki()): ?>
            <li>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$children->getWiki()->getId() ?>"><img src="<?php echo $children->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $children->getWiki()->getTitle()?>"></a></div>
              <a href="<?php echo url_for('wiki/show?id=').$children->getWiki()->getId() ?>"><?php echo $children->getName() ?></a>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
    <div id="education" class="bd">
      <ul>
        <?php if($edus): ?>
        <?php foreach($edus as $edu): ?>
          <?php if($edu->getWiki()): ?>
            <li>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$edu->getWiki()->getId() ?>"><img src="<?php echo $edu->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $edu->getWiki()->getTitle()?>"></a></div>
              <a href="<?php echo url_for('wiki/show?id=').$edu->getWiki()->getId() ?>"><?php echo $edu->getName() ?></a>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
    <div id="finance" class="bd">
      <ul>
        <?php if($finances): ?>
        <?php foreach($finances as $finance): ?>
          <?php if($finance->getWiki()): ?>
            <li>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$finance->getWiki()->getId() ?>"><img src="<?php echo $finance->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $finance->getWiki()->getTitle()?>"></a></div>
              <a href="<?php echo url_for('wiki/show?id=').$finance->getWiki()->getId() ?>"><?php echo $finance->getName() ?></a>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
    <div id="etc" class="bd">
      <ul>
        <?php if($generals): ?>
        <?php foreach($generals as $general): ?>
          <?php if($general->getWiki()): ?>
            <li>
              <div class="poster"><a href="<?php echo url_for('wiki/show?id=').$general->getWiki()->getId() ?>"><img src="<?php echo $general->getWiki()->getCoverUrl() ?>" width="75" height="110" alt="<?php echo $general->getWiki()->getTitle()?>"></a></div>
              <a href="<?php echo url_for('wiki/show?id=').$general->getWiki()->getId() ?>"><?php echo $general->getName() ?></a>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
        <?php endif ?>
      </ul>
    </div>
</div>