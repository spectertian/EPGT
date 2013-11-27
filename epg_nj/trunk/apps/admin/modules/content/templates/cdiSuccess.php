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
                    类型：
                      <select name='type'>
                        <option value='' <?php if ($type=='') echo 'selected'; ?>>不限</option>
                        <option value='ONLINE_TASK_DONE' <?php if ($type=='ONLINE_TASK_DONE') echo 'selected'; ?>>上线消息</option>
                        <option value='CONTENT_OFFLINE' <?php if ($type=='CONTENT_OFFLINE') echo 'selected'; ?>>下线消息</option>
                      </select>
subcontent_id：<input type="text" name="subid" value="<?php echo $subid?>"/>
page_id：<input type="text" name="pageid" value="<?php echo $pageid?>"/>
                            <input type="submit" value="查询">
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo "/content/cdi?page=".$pager->getFirstPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo "/content/cdi?page=".$pager->getPreviousPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid?>">上一页</a>
                  </span>
                  <span class="pages">
                    <?php $links = $pager->getLinks(5);?>
                    <?php foreach ($links as $key => $value):?>
                        <?php if ($value == $pager->getPage()):?>
                            <span class="present"><?php echo $value;?></span>
                        <?php else:?>
                            <a href="<?php echo "/content/cdi?page=".$value.'&type='.$type.'&subid='.$subid.'&pageid='.$pageid?>"><?php echo $value;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo "/content/cdi?page=".$pager->getNextPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo "/content/cdi?page=".$pager->getLastPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>
                <div class="clear"></div>
            </div>
            <form method="post" name="adminForm" action="#">
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" width="15%">类型</th>
                  <th scope="col" width="20%">ID/名称</th>
                  <th scope="col" width="25%">subcontent_id</th>
                  <th scope="col" width="15%">page_id</th>
                  <th scope="col" class="list_created_at" width="10%">创建时间</th>
                  <th scope="col" class="list_action" width="10%">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">类型</th>
                  <th scope="col">ID/名称</th>
                  <th scope="col">subcontent_id</th>
                  <th scope="col">page_id</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                <?php 
                      $k=0;
                      foreach ($pager->getResults() as $key => $cdi): 
                ?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $cdi->getId();?>" name="id[]"></td>
                  <td><?php echo $cdis[$k]['command'];?></td>
                  <td><?php echo $cdi->getId();?><br /><?php echo $cdis[$k]['title'];?></td>
                  <td><?php echo $cdis[$k]['subcontent_id'];?></td>
                  <td><?php echo $cdis[$k]['page_id'];?></td>
                  <td><?php echo $cdi->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><a target="_blank" href="<?php echo '/content/viewCdi?id='.$cdi->getId();?>">查看详细</a></td>
                </tr>
                <?php 
                      $k++;
                      endforeach;
                ?>
                <?php endif;?>
              </tbody>
            </table>
            <input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
            <input type="hidden" value="" name="batch_action">
            </form>
            <div class="table_nav">
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo "/content/cdi?page=".$pager->getFirstPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid;?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo "/content/cdi?page=".$pager->getPreviousPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid;?>">上一页</a>
                  </span>
                  <span class="pages">
                    <?php $links = $pager->getLinks(5);?>
                    <?php foreach ($links as $key => $value):?>
                        <?php if ($value == $pager->getPage()):?>
                            <span class="present"><?php echo $value;?></span>
                        <?php else:?>
                            <a href="<?php echo "/content/cdi?page=".$value.'&type='.$type.'&subid='.$subid.'&pageid='.$pageid ;?>"><?php echo $value;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo "/content/cdi?page=".$pager->getNextPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid;?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo "/content/cdi?page=".$pager->getLastPage().'&type='.$type.'&subid='.$subid.'&pageid='.$pageid;?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
      </div>
      