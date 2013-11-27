
    <div id="content">
      <div class="content_inner">
        <header>
          <h2 class="content"><?php echo $pageTitle; ?></h2>
          <nav class="utility">
          	<li class="back"><a href="/videos_zhui/index" >返回列表</a></li>
            </nav>
        </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
              <div class="clear"></div>
            </div>
            <table cellspacing="0" id="yesterday_tables">
              <thead>
                <tr>
                  <th scope="col" class="list_tags list_tagsl">名称</th>
                  <th scope="col" class="list_wikiname">抓取地址</th>
                  <th scope="col" class="list_category">总集数</th>
                  <th scope="col" class="list_is_default">已抓集数</th>
                  <th scope="col" class="list_start_time">开始时间</th>
                  <th scope="col" class="list_start_time">结束时间</th>
                  <th scope="col" class="list_action">状态</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_tags list_tagsl">名称</th>
                  <th scope="col" class="list_wikiname">抓取地址</th>
                  <th scope="col" class="list_category">总集数</th>
                  <th scope="col" class="list_is_default">已抓集数</th>
                  <th scope="col" class="list_start_time">开始时间</th>
                  <th scope="col" class="list_start_time">结束时间</th>
                  <th scope="col" class="list_action">状态</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($source)):?>
                  <?php 
                  foreach ($source as $k => $rs): ?>
                            <tr>
                              <td><?php echo $videoClass[$k];?></td>
                              <td style="word-break:break-all;word-wrap:break-word;"><?php echo $rs['url'];?></td>
                              <td><?php echo $total; ?></td>
                              <td><?php echo $rs['local']?$rs['local']:'0';?></td>
                              <td><?php echo $createdAt;?></td>
                              <td><?php echo $rs['update_time'];?></td>
                              <td><?php echo $rs['success']?'抓取成功':'抓取失败';?></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
           
            <div class="clear"></div>
        </div>
      </div>