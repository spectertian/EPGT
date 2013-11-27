            <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getFirstPage());?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getPreviousPage());?>">上一页</a>
                  </span>
                  <span class="pages">
                      <?php foreach ($pager->getLinks(5) as $page ):?>
                            <?php if ($page == $pager->getPage()):?>
                                <span class="present"><?php echo $page;?></span>
                            <?php else:?>
                                <a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$page);?>"><?php echo $page;?></a>
                            <?php endif;?>
                      <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getNextPage());?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getLastPage());?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>
                <div class="clear"></div>
            </div>

            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col" class="noEdit checkbox"><input id="sf_admin_list_batch_checkbox" name="toggle" type="checkbox" name="ids"></th>
                  <th scope="col" class="list_id" name="name">名称</th>
                  <th scope="col" class="title sf_admin_text sf_admin_list_th_channel noEdit channel" name="channel_id">频道</th>
                  <th scope="col" class="title sf_admin_boolean sf_admin_list_th_publish select img"
                            name="publish" rel='{ "options":[ {text:"是",value:1,selected:false},{text:"否",value:0,selected:"selected"} ] }'
                            style="width:5%;text-align: center;" name="publish">发布</th>
                  <th scope="col" class="time" name="time" style="width: 8%;">播放时间</th>
                  <th scope="col" class="wiki" name="wiki">维基</th>
                  <th scope="col" class="tags" name="tags" style="width: 20%">Tags</th>
                  <th scope="col" class="date" name="date" style="text-align: center;">创建日期</th>
                  <th scope="col" class="sort" name="sort" style="text-align: center;width:5%;">排序</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                 <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox_foot"></th>
                  <th scope="col">名称</th>
                  <th scope="col">频道</th>
                  <th scope="col" style="text-align: center;">发布</th>
                  <th scope="col">播放时间</th>
                  <th scope="col">维基</th>
                  <th scope="col">Tags</th>
                  <th scope="col" style="text-align: center;">创建日期</th>
                  <th scope="col" style="text-align: center;">排序</th>
                </tr>
              </tfoot>
              <tbody>
            <?php if(isset ($pager)):?>  
              <?php 
                   foreach ($pager->getResults() as $i => $program): 
              ?>
                <tr edit="0">
                    <td class="noEdit checkbox" name="ids[]">
                      <input name="ids[]" value="<?php echo $program->getId() ?>" class="sf_admin_batch_checkbox " type="checkbox">
                    </td>
                  <td class="sf_admin_text sf_admin_list_td_name" name="name" style="text-align:left;" >
                      <?php echo $program->getName() ?>
                  </td>
                  <td class="sf_admin_text sf_admin_list_td_channel noEdit channel" name="channel_id">
                      <?php echo $program->getChannelName() ?>
                  </td>
                      <?php
                        if($program->getPublish() == 1){
                            $publish_on = '"selected"';
                            $publish_off = 'false';
                        }else{
                            $publish_on = 'false';
                            $publish_off = '"selected"';
                        }
                    ?>
                    <td class="sf_admin_boolean sf_admin_list_td_publish img select" name="publish"
                        rel='{ "options":[ {text:"是",value:1,selected: <?php echo $publish_on; ?> },{text:"否",value:0,selected:<?php echo $publish_off; ?> } ] }'
                        style="text-align:center;">
                        <img alt="Checked" title="Checked" src="<?php echo $program->getPublishImgSrc(); ?>">
                    </td>
                    <td class="sf_admin_date sf_admin_list_td_time time" name="time">
                      <?php echo $program->getTime() ?>
                    </td>
                     <td class="sf_admin_text sf_admin_list_td_wiki wiki" name="wiki" rel="<?php echo ($program->getWiki()) ? $program->getWiki()->getId() : ''; ?>">
                        <?php echo $program->getWikiTitle() ?>
                     </td>
                    <td class="sf_admin_text sf_admin_list_td_tags tags" name="tags">
                        <?php foreach($program->getTags() as $tag): ?>
                            <?php echo $tag; ?>,
                        <?php endforeach; ?>
                    </td>
                   <td class="sf_admin_date sf_admin_list_td_date date" name="date" style="text-align: center;">
                       <?php echo $program->getDate() ?>
                   </td>
                   <td class="sf_admin_text sf_admin_list_td_sort sort" name="sort" style="text-align: center;">
                       <?php echo $program->getSort() ?>
                   </td>
                </tr>
              <?php endforeach; ?>
            <?php endif ?>
              </tbody>
            </table>





            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("program_live/index?channel=$channel&start_time=$start_time&end_time=$end_time&page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
           </div>
           <div class="clear"></div>