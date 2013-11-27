  <div class="module upcoming">
                <h3>节目预告 <small style="display:none"><a target="_blank" href="#">更多&raquo;</a></small></h3>
                <?php if ( isset($advance_notices) && !empty($advance_notices) ):$i=0?>
                <ul>
                    <?php foreach($advance_notices as $advance_notice):$i++?>
                        <?php $wiki = $advance_notice->getWiki()?>
                          <li> <span class="num"><?php echo $i?></span>
                            <div class="poster">
                             <?php if(!is_null($wiki) > 0 ): ?>
                                <?php if($wiki->getCover()):?>
                                <a target="_blank" href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>">
                                    <img src="<?php echo thumb_url($wiki->getCover(), 75, 110)?>" width="75" height="110" alt="<?php echo $wiki->getTitle()?>">
                                </a>
                                <?php endif;?>
                            <?php endif;?>
                            </div>
                            <div class="details">
                              <div class="title"><a target="_blank" href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>"><?php echo $wiki->getTitle()?></a></div>
                              <div class="text-block tags">
                                  <?php if(!is_null($wiki->getTags())):?>
                                      <?php foreach($wiki->getTags() as $tag):?>
                                        <a target="_blank" href="<?php echo url_for('search/search?q=tag:'. $tag)?>"><?php echo $tag?></a>
                                      <?php endforeach;?>
                                  <?php endif;?>
                              </div>
                              <div class="text-block">主演：
                                  <?php if ($wiki->getStarring()) : $j=0?>
                                  <?php foreach ($wiki->getStarring() as $starring):$j++?>
                                  <?php if($j<=2):?>
                                  <a target="_blank" href="<?php echo url_for('search/index?q=' . urlencode($starring)) ?>"><?php echo $starring?></a>
                                  <?php else:?>
                                  <a target="_blank" href="<?php echo url_for('search/index?q=' . urlencode($starring)) ?>"><?php echo $starring?></a>...
                                  <?php endif;?>
                                  <?php if ($j>=3) break;?>
                                  <?php endforeach;?>
                                  <?php endif;?>
                              </div>
                            </div>
                            <div class="clear"></div>
                            <div class="play-time"><?php echo date("m月d日",strtotime($advance_notice->getDate()))?>  星期<?php echo weekdays_zh_cn(date("w",strtotime($advance_notice->getDate())))?> 播出</div>
                          </li>
                     <?php endforeach;?>
                </ul>
                <?php endif;;?>
              </div>
