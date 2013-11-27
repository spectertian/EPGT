
    <div id="content">
      <div class="content_inner">
        <header>
          <h2 class="content">用户行为列表</h2>
          <nav class="utility">        
		</nav>
        </header>
         
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
				<?php include_partial('select', array('userName'=>$userName,'access'=>$access))?>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('user_behavior/index?page='.$pager->getFirstPage().'&userName='.$userName.'&access='.$access);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('user_behavior/index?page='.$pager->getPreviousPage().'&userName='.$userName.'&access='.$access);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('user_behavior/index?page='.$page.'&userName='.$userName.'&access='.$access);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('user_behavior/index?page='.$pager->getNextPage().'&userName='.$userName.'&access='.$access);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('user_behavior/index?page='.$pager->getLastPage().'&userName='.$userName.'&access='.$access);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0"> 
             <thead>
                <tr>
                  <th scope="col" style="width: 5%;">用户id</th>
                  <th scope="col" style="width: 10%;">用户名称</th>
                  <th scope="col" style="width: 20%;">用户访问页面</th>
                  <th scope="col" style="width: 45%;">页面传递的值</th>
                  <th scope="col" style="width: 15%;">访问时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" style="width: 5%;">用户id</th>
                  <th scope="col" style="width: 10%;">用户名称</th>
                  <th scope="col" style="width: 20%;">用户访问页面</th>
                  <th scope="col" style="width: 45%;">页面传递的值</th>
                  <th scope="col" style="width: 15%;">访问时间</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  foreach ($pager->getResults() as $i => $rs): ?>
					<tr>
	                  <td><?php echo $rs->getUserId();?></td>
	                  <td><?php echo $rs->getUserName();?></td>
	                  <td><?php echo $rs->getAccess();?></td>
	                  <td><?php echo $rs->getValues();?></td>
	                  <td><?php echo $rs->getDate()->format("Y-m-d H:i:s");?></td>
                	</tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('user_behavior/index?page='.$pager->getFirstPage().'&userName='.$userName.'&access='.$access);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('user_behavior/index?page='.$pager->getPreviousPage().'&userName='.$userName.'&access='.$access);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('user_behavior/index?page='.$page.'&userName='.$userName.'&access='.$access);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('user_behavior/index?page='.$pager->getNextPage().'&userName='.$userName.'&access='.$access);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('user_behavior/index?page='.$pager->getLastPage().'&userName='.$userName.'&access='.$access);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>
            <div class="clear"></div>
        </div>
      </div>