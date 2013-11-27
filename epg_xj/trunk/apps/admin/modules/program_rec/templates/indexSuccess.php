      <div id="content">
        <div class="content_inner">
            <div class="table_nav">
            <header class="toolbar">
              <h2 class="content"><?php echo $pageTitle;?></h2>
              <nav class="utility">
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <?php include_partial('weeks');?>
            <?php include_partial('search', array('timeArea' => $timeArea, 'timeSon' => $timeSon,'name' => $name, 'date' => $date)) ?>
           <div class="clr">
            <div class="f_l68">
          
            <h3>节目列表:<span style="color:green" id="point"></span></h3>
            <table cellspacing="0" class="yesterday_table" id="yesterday_table">
			  <thead>
			    <tr>
			      <th scope="col" class="list_model">频道名称</th>
			      <th scope="col" class="list_tags	">节目名称</th>
                  <th scope="col" class="list_model">播放时间</th>
                  <th scope="col" class="list_tags">标签</th>
                  <th scope="col" class="list_action">操作</th>
			    </tr>
			  </thead>
			  <tbody>
			  <?php
			    if(isset ($pager)):
			    foreach($pager->getResults() as $i => $program ):
			  ?>
			    <tr>
					<td><font style="white-space: normal;"><?php echo $channelNmaes[$program->getChannelCode()] ?></font></td>
			        <td><?php echo $program->getName() ?><a name="<?php echo $i ?>"></a></td>
			        <td>
			          <?php $starttime = $program->getStartTime(); if($starttime) echo $program->getStartTime()->format("H:i")?>-
			          <?php $endtime = $program->getEndTime(); if($endtime) echo $program->getEndTime()->format("H:i")?>
			        <td>
			        <?php if($program->getTags()):?>
			            <?php foreach($program->getTags() as $tag): ?>
			                <?php echo $tag; ?>,
			            <?php endforeach; ?>
			        <?php endif;?>
			        </td>
			        <td>
			        	<?php if($program->getWikiId()):?>
				        	<a href="/wiki/edit/id/<?php echo $program->getWikiId();?>" target="_blank" >
				        		<img src="/images/calendar.png" title="查看维基" alt="查看维基">
							</a>
							<a class="add_ico" href="/program_rec/save?timeArea=<?php echo $timeArea ?>&id=<?php echo $program->getId();?>&anchor=<?php echo $i ?>" >
				        		<img src="/images/ico_add.png" title="添加" alt="添加">
							</a>
						<?php endif;?>
			        </td>
			    </tr>
			  <?php endforeach; ?>
			<?php endif ?>
			  </tbody>
			  <tfoot>
			    <tr>
			      <th scope="col">频道名称</th>
			      <th scope="col">节目名称</th>
			      <th scope="col">播放时间</th>
			      <th scope="col">标签</th>
			      <th scope="col" style="text-align: center;">操作</th>
			    </tr>
			  </tfoot>
			</table>          
            </div>
            
            
            
            
            <div class="f_r30">
            <h3>添加的节目:</h3>
            <table cellspacing="0" class="adminlist" id="admin_list_two">
              <thead>
                <tr>
				  <th scope="col"  name="channelName" style="width:20%">频道名称</th>
                  <th scope="col"  name="name" style="width:30%">名称</th>
                  <th scope="col"  name="time"  style="width:15%">时间</th>
                  <th scope="col"  name="time"  style="width:10%">操作</th>
                </tr>
              </thead>      
              <tbody id="tv">
              <?php 
              if(isset ($programRecs)):
              foreach($programRecs as $programRecs):
              $i++;
              ?>
                <tr>
                  <td><font style="white-space: normal;"><?php echo $channelNmaes[$programRecs->getChannelCode()]?></font></td>
				  <td><font style="white-space: normal;"><?php echo $programRecs->getName()?></font></td>
                  <td><?php $starttime = $programRecs->getStartTime(); if($starttime) echo $programRecs->getStartTime()->format("H:i")?>-
				      <?php $endtime = $programRecs->getEndTime(); if($endtime) echo $programRecs->getEndTime()->format("H:i")?>
					  <a name="<?php echo $i ?>"></a></td>
                  <td>
                  		<a href="/program_rec/delete?id=<?php echo $programRecs->getId();?>&anchor=<?php echo $i ?>" >
			        		<img src="/images/delete.png" title="删除" alt="删除">
						</a>
				  </td>
                </tr>
              <?php endforeach;?>
              <?php endif;?>
              </tbody>
              <tfoot>
                <tr>
                  <th scope="col">频道名称</th>
                  <th scope="col">名称</th>
                  <th scope="col">时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
            </table>            
            </div>
         	</div>
         	
         	<div class="paginator fy">
              <span class="first-page">
                  <a href="<?php echo url_for('program_rec/index?page='.$pager->getFirstPage().'&timeArea='.$timeArea.'&name='.$name.'&date='.$date.'&timeSon='.$timeSon);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('program_rec/index?page='.$pager->getPreviousPage().'&timeArea='.$timeArea.'&name='.$name.'&date='.$date.'&timeSon='.$timeSon);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('program_rec/index?page='.$page.'&timeArea='.$timeArea.'&name='.$name.'&date='.$date.'&timeSon='.$timeSon);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('program_rec/index?page='.$pager->getNextPage().'&timeArea='.$timeArea.'&name='.$name.'&date='.$date.'&timeSon='.$timeSon);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('program_rec/index?page='.$pager->getLastPage().'&timeArea='.$timeArea.'&name='.$name.'&date='.$date.'&timeSon='.$timeSon);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>
        </div>
        
        