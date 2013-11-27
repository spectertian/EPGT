<div id="content-box">
    <div class="border">
        <div class="padding">
            <div id="element-box">
                <div class="t">
                    <div class="t">
                        <div class="t"></div>
                    </div>
                </div>
                <div class="m" >
                    <table class="adminform">
                        <tr>
                            <td><h2>日常值班</h2></td>
                        </tr>
                        <tr>
                            <td valign="top">
                              <ul class="menulist">
                                <li>
                                  <a href="<?php echo url_for('check/oneKey');?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>值班监测</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="/attachments_pre">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>图片审核</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="/wordsLog">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>敏感词日志</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="/content/import">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>CMS内容处理</span>
                                  </a>
                                </li>
                              </ul>
                            </td>
                        </tr>

                        <?php if ($sf_user->hasCredential('video','channel_recommend','content_cdi','synfile')): ?>	
                        <tr>
                            <td><h2>内容管理</h2></td>
                        </tr>
                        <tr>
                            <td valign="top">
                              <ul class="menulist">
                                <?php if ($sf_user->hasCredential('video')): ?>
                                <li>
                                  <a href="<?php echo url_for('video/index')?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>视频</span>
                                  </a>
                                </li>
                                <?php endif;?>
                                <?php if ($sf_user->hasCredential('channel_recommend')): ?>
                                <li>
                                  <a href="<?php echo url_for('channel_recommend/index');?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>频道推荐</span>
                                  </a>
                                </li>
                                <?php endif;?>
                                <?php if ($sf_user->hasCredential('content_cdi')): ?>
                                <li>
                                  <a href="<?php echo url_for('content/cdi');?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>上下线内容查看</span>
                                  </a>
                                </li>
                                <?php endif;?>
                                <?php if ($sf_user->hasCredential('synfile')): ?>
                                <li>
                                  <a href="/tool/synFile">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>单图片同步</span>
                                  </a>
                                </li>
                                <?php endif;?>
                              </ul>
                            </td>
                        </tr>
                        <?php endif;?>
                        
                        <?php if ($sf_user->hasCredential('tv_station','channel','spservice')): ?>	
                        <tr>
                            <td><h2>分类管理</h2></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <ul class="menulist">
                                  <?php if ($sf_user->hasCredential('tv_station')): ?>
                                  <li>
                                    <a href="<?php echo url_for('@tv_station');?>">
                                      <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                      <span>电视台</span>
                                    </a>
                                  </li>
                                  <?php endif;?>
                                  <?php if ($sf_user->hasCredential('channel')): ?>
                                  <li>
                                    <a href="<?php echo url_for('@channel');?>">
                                      <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                      <span>电视频道</span>
                                    </a>
                                  </li>
                                  <?php endif;?>
                                  <?php if ($sf_user->hasCredential('spservice')): ?>
                                  <li>
                                    <a href="<?php echo url_for('spservice/index');?>">
                                      <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                      <span>NIT管理</span>
                                    </a>
                                  </li>
                                  <?php endif;?>
                                </ul>

                                </div>
                            </td>
                        </tr>
                        <?php endif;?>
                    </table>
                    <div class="clr"></div>
                </div>
                <div class="b">
                    <div class="b">
                        <div class="b"></div>
                    </div>
                </div>
            </div>
            <noscript>Warning! JavaScript must be enabled for proper operation of the Administrator Back-end</noscript>
            <div class="clr"></div>
        </div>
    </div>
</div>