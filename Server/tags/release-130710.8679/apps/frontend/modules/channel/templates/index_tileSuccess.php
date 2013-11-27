<div class="container epg">
  <div class="container-inner">
    <div class="main-bd">
      <h2>电视节目指南</h2>
      <?php include_component("channel","province");?>
      <!-- epg start -->
      <?php include_partial('mian_nav', array('active' => $active, 'mode' => $mode)) ?>
      <?php include_partial('index_sub_nav', array('top_active' => $top_active, 'mode'=> $mode,'location' => $location, 'type' => $type))?>
      <div class="epg-tile">
        <ul>
          <?php $i = 0;?>
          <?php foreach($tags as $tag) : $i++ ?>
          <?php $programs = isset($programTile[$tag]) ? $programTile[$tag] : array()?>
          <?php if (empty($programs)) continue;?>
          <li class="row0<?php echo $i?>" <?php echo fmod($i, 2) ? '' : 'class="odd"';?>>
            <h4 class="tag"><?php echo $tag?></h4>
            <div class="covers clearfix">
                <ul>
                <?php foreach ($programs as $program) :?>
                <?php $wiki = $program->getWiki();?>
                <li>
                  <div class="poster">
                      <a href="<?php echo url_for('wiki/show?slug='. $wiki->getSlug())?>" slug='<?php echo $wiki->getSlug();?>'>
                          <img alt="<?php echo $wiki->getTitle()?>" src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>"/>
                      </a>
                  </div>
                  <h3><a href="<?php echo url_for('wiki/show?slug='. $wiki->getSlug())?>"><?php echo $wiki->getTitle()?></a></h3>
                </li>
                <?php endforeach;?>
              </ul>
              <div class="clear"></div>
            </div>
          </li>
          <?php endforeach;?>        
        </ul>
      </div>
      <!-- epg end -->
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
    toolTiper('.epg-tile li .covers li .poster a', 20, 100, 474);
});
</script>