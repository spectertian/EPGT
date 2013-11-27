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
                            <td><h2>分类管理</h2></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <ul class="menulist">
                                  <li>
                                    <a href="<?php echo url_for('@tv_station');?>">
                                      <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                      <span>电视台</span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="<?php echo url_for('@channel');?>">
                                      <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                      <span>电视频道</span>
                                    </a>
                                  </li>
                                </ul>

                                </div>
                            </td>
                        <tr>
                            <td><h2>内容管理</h2></td>
                        </tr>
                        <tr>
                            <td valign="top">
                              <ul class="menulist">
                                <li>
                                  <a href="<?php echo url_for('@program');?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>电视节目</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?php echo url_for('video/index')?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>视频</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?php echo url_for('wiki/index');?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>维基</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?php echo url_for('tag/index');?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>标签</span>
                                  </a>
                                </li>
                                <li>
                                  <a href="<?php echo url_for('recommend/index');?>">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>推荐列表</span>
                                  </a>
                                </li>
                              </ul>
                                <!-- <div id="cpanel">

                                    <div style="float:left;">
                                        <div class="icon">
                                            <a href="<?php echo url_for('@program');?>">
                                                <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                                <span>电视节目</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div style="float:left;">
                                        <div class="icon">
                                            <a href="<?php echo url_for('video/index')?>">
                                                <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                                <span>视频</span></a>
                                        </div>
                                    </div>

                                    <div style="float:left;">
                                        <div class="icon">
                                            <a href="<?php echo url_for('wiki/index');?>">
                                                <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                                <span>维基</span></a>
                                        </div>
                                    </div>
                                    <div style="float:left;">
                                        <div class="icon">
                                            <a href="<?php echo url_for('tag/index');?>">
                                                <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                                <span>标签</span></a>
                                        </div>
                                    </div>
                                    <div style="float:left;">
                                        <div class="icon">
                                            <a href="<?php echo url_for('recommend/index');?>">
                                                <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                                <span>推荐列表</span></a>
                                        </div>
                                    </div>

                                </div> -->
                            </td>
                            <!--<td width="45%" valign="top">
                                &nbsp;
                            </td>-->
                        </tr>
                        <tr>
                            <td><h2>小工具</h2></td>
                        </tr>
                        <tr>
                            <td valign="top">
                              <ul class="menulist">
                                <li>
                                  <a href="/tool/synFile">
                                    <img src="<?php echo image_path('header/icon-48-article.png')?>" alt="Article Manager"  />
                                    <span>单图片同步</span>
                                  </a>
                                </li>
                              </ul>
                            </td>
                        </tr>
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