      <div id="content">
        <div class="content_inner">            
            <header>
              <h2 class="content"><?php echo $pageTitle?></h2>
              <nav class="utility">
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
                <form method="get" action="">
                    关键词：<input type="text" name="keyword" value="<?php echo $keyword?>"/>
                            <input type="submit" value="查询">
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for("content/inject?page=".$injects->getFirstPage().'&keyword='.$keyword);?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for("content/inject?page=".$injects->getPreviousPage().'&keyword='.$keyword);?>">上一页</a>
                  </span>
                  <span class="pages">
                    <?php $links = $injects->getLinks(5);?>
                    <?php foreach ($links as $key => $value):?>
                        <?php if ($value == $injects->getPage()):?>
                            <span class="present"><?php echo $value;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("content/inject?page=".$value.'&keyword='.$keyword );?>"><?php echo $value;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for("content/inject?page=".$injects->getNextPage().'&keyword='.$keyword);?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for("content/inject?page=".$injects->getLastPage().'&keyword='.$keyword);?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $injects->getPage();?>/<?php echo $injects->getLastPage();?>)</span>
                </div>
                <div class="clear"></div>
            </div>
            <form method="post" name="adminForm" action="<?php echo url_for('@wiki');?>/batch">
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id">injectID</th>
                  <th scope="col" class="list_model">来源</th>
                  <th scope="col" class="list_model">状态</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">injectID</th>
                  <th scope="col">来源</th>
                  <th scope="col">状态</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
              <?php if(isset ($injects)):?>
                <?php foreach ($injects->getResults() as $key => $inject): ?>
                <?php
                    /*
                    $content = @simplexml_load_string(trim($inject->getContent())); 
                    if($content){
                        $ams=$content->ADI->Metadata->AMS->attributes();
                        $name=$ams['Asset_Name'];
                    }
                    */
                ?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $inject->getId();?>" name="id[]"></td>
                  <td><a target="_blank" href="<?php echo url_for('content/view?id='.$inject->getId());?>"><?php echo $inject->getID();?></a></td>
                  <td><?php echo $inject->getFrom();?></td>
                  <td><?php echo $inject->getState() ? "已处理" : "待处理";?></td>
                  <td><?php echo $inject->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><?php echo ($updated_at = $inject->getUpdatedAt()) ? $updated_at->format("Y-m-d H:i:s") : $inject->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><a target="_blank" href="<?php echo url_for('content/view?id='.$inject->getId());?>">查看详细</a></td>
                </tr>
                <?php endforeach;?>
              <?php endif;?>
              </tbody>
            </table>
            <input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
            <input type="hidden" value="" name="batch_action">
            </form>
            <div class="table_nav">
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for("content/inject?page=".$injects->getFirstPage().'&keyword='.$keyword);?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for("content/inject?page=".$injects->getPreviousPage().'&keyword='.$keyword);?>">上一页</a>
                  </span>
                  <span class="pages">
                    <?php $links = $injects->getLinks(5);?>
                    <?php foreach ($links as $key => $value):?>
                        <?php if ($value == $injects->getPage()):?>
                            <span class="present"><?php echo $value;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("content/inject?page=".$value.'&keyword='.$keyword );?>"><?php echo $value;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for("content/inject?page=".$injects->getNextPage().'&keyword='.$keyword);?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for("content/inject?page=".$injects->getLastPage().'&keyword='.$keyword);?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $injects->getPage();?>/<?php echo $injects->getLastPage();?>)</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
      </div>
      