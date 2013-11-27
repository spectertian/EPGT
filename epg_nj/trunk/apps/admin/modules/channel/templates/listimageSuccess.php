    <div id="content">
        <div class="content_inner">
            <header>
              <h2 class="content">电视频道台标查看</h2>
              <nav class="utility">
                <li class="add"><a href="<?php echo url_for("channel/getimage")?>"  class="toolbar publish">获取图片</a></li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <form method="get" action="">
                                频道名称:
                                <input type="text" value="<?php echo $mc?>" name="mc" id="mc">
                                <input type="submit" value="查询">
            </form><br />
            <div class="table_nav">


            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('channel/listimage?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('channel/listimage?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('channel/listimage?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('channel/listimage?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('channel/listimage?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>  
                  <th scope="col" width="50%" style="text-align: center;">频道名称</th>
                  <th scope="col" width="50%" style="text-align: center;">台标</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" style="text-align: center;">频道名称</th>
                  <th scope="col" style="text-align: center;">台标</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td style="text-align: center;"><?php echo $rs->getName();?></td>
                              <td style="text-align: center;"><img src="<?php echo '/uploads/'.$rs->getName().'.png';?>"/></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('channel/listimage?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('channel/listimage?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('channel/listimage?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('channel/listimage?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('channel/listimage?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>