<div class="container people">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <section id="section">
        <div class="overview clearfix">
          <h1>
              <span class="title"><?php echo $wiki->getTitle() ?></span>
              <?php if($wiki->getEnglishName()): ?>
              / <span class="alt-title"><?php echo $wiki->getEnglishName() ?></span>
              <?php endif; ?>
              <?php if($wiki->getNickname()): ?>
              <span class="alt-title">(<?php echo $wiki->getNickname() ?>)</span>
              <?php endif; ?>
          </h1>
          <div class="poster"><img width="172" height="255" src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?>"></div>
          <div class="info">
            <div class="text-block info-meta">
              <span class="param"><?php echo $wiki->getSex() ?></span>
              <?php if($wiki->getBirthday()): ?>
              / <span class="param">生于<?php echo $wiki->getBirthday() ?></span>
              <?php endif; ?>
              <?php if($wiki->getBirthplace()): ?>
              / <span class="param"><?php echo $wiki->getBirthplace() ?></span>
              <?php endif; ?>
            </div>
            <?php if($wiki->getOccupation()): ?>
            <div class="text-block"><span class="label">职业：</span><span class="param"><?php echo $wiki->getOccupation() ?></span></div>
            <?php endif; ?>
            <?php if($wiki->getZodiac()): ?>
            <div class="text-block"><span class="label">星座：</span><span class="param"><?php echo $wiki->getZodiac() ?></span></div>
            <?php endif; ?>
            <?php if($wiki->getBloodType()): ?>
            <div class="text-block"><span class="label">血型：</span><span class="param"><?php echo $wiki->getBloodType() ?>型</span></div>
            <?php endif; ?>
            <?php if($wiki->getNationality()): ?>
            <div class="text-block"><span class="label">国籍：</span><span class="param"><?php echo $wiki->getNationality() ?></span></div>
            <?php endif; ?>
            <?php if($wiki->getRegion()): ?>
            <div class="text-block"><span class="label">地域：</span><span class="param"><?php echo $wiki->getRegion() ?></span></div>
            <?php endif; ?>
            <?php if($wiki->getHeight()): ?>
            <div class="text-block"><span class="label">身高：</span><span class="param"><?php echo $wiki->getHeight() ?>cm</span></div>
            <?php endif; ?>
            <?php if($wiki->getWeight()): ?>
            <div class="text-block"><span class="label">体重：</span><span class="param"><?php echo $wiki->getWeight() ?>kg</span></div>
            <?php endif; ?>
            <?php if($wiki->getDebut()): ?>
            <div class="text-block"><span class="label">出道日期：</span><span class="param"><?php echo $wiki->getDebut() ?></span></div>
            <?php endif; ?>
            <?php if($wiki->getDebut()): ?>
            <div class="text-block"><span class="label">宗教信仰：</span><span class="param"><?php echo $wiki->getDebut() ?></span></div>
            <?php endif; ?>
            <?php if($wiki->getHtmlCache()) :?>
            <div class="text-block">
            <p><span class="label">人物简介：</span><span class="param"><?php echo $wiki->getHtmlCache(280, ESC_RAW); ?><a href="#detail">详细&raquo;</a></span></p>
            </div>
            <?php endif;?>
          </div>
        </div>
        <?php if ($film0graphy) :?>
        <div class="mod" id="filmography">
          <div class="hd">
            <h3>作品年表</h3>
          </div>
          <div class="bd">
            <ul class="clearfix">
              <?php $Unpublished = array()?>
              <?php foreach($film0graphy as $film) :?>
              <?php if ($film->getReleased() && $film->getYear($film->getReleased() > date('Y', time())))
               {
                  $Unpublished[] = $film;
                  continue;
              }?>
              <li>
                <h4><?php echo $film->getYear($film->getReleased())?></h4>
                <div class="poster">
                    <a href="<?php echo url_for("wiki/show?slug=".$film->getSlug()) ?>" slug="<?php echo $film->getSlug()?>" ><img src="<?php echo thumb_url($film->getCover(), 80, 120)?>" width="80"  height="120" alt="<?php echo $film->getTitle()?>"></a>
                </div>
                <h5><a href="<?php echo url_for("wiki/show?slug=".$film->getSlug())?>"><?php echo $film->getTitle()?></a></h5>
              </li>
              <?php endforeach;?>
            </ul>
            <?php if(!empty($Unpublished)) :?>
            <div class="upcoming">未上映：
                <?php foreach($Unpublished as $film) :?>
                <span class="title">
                    <a href="<?php echo url_for("wiki/show?slug=".$film->getSlug()) ?>"> <?php echo $film->getTitle()?></a>
                    <?php echo ($year = $film->getYear($film->getReleased())) ? '( '.$year.' )': ''?>
                </span>
                <?php endforeach;?>
            </div>
            <?php endif;?>
          </div>
        </div>
        <?php endif;?>
        <div class="mod" id="detail">
          <div class="hd">
            <h3>人物介绍</h3>
          </div>
          <div class="bd">
            <div class="storyline"><?php echo $wiki->getHtmlCache(ESC_RAW); ?></div>
            <?php if($wiki->getScreenshots()): ?>
            <div class="stills">
              <ul>
                <?php $i = 0;?>
                <?php foreach($wiki->getScreenshots() as $screenshot) : $i++?>
                <li>
                    <a href="<?php echo file_url($screenshot) ?>" rel="stills" title="<?php printf('%s%d', $wiki->getTitle(), $i)?>">
                        <img width="150" height="84" src="<?php echo thumb_url($screenshot, 150, 84) ?>" alt="<?php printf('%s%d', $wiki->getTitle(), $i)?>">
                    </a>
                </li>
                <?php endforeach;?>
              </ul>
            </div>
            <?php endif;?>
          </div>
        </div>
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
      </aside>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(function(){
        toolTiper('#filmography .bd li .poster a', 20, 80, 474);
    });
</script>