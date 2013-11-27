  <div id="content">
    <div class="content_inner">
        <header>
          <h2 class="content"><?php echo $pageTitle;?></h2>
          <nav class="utility">
          <li class="app-add"> <a href="/count/programLog">统计查询</a></li>
          </nav>
        </header>
        <?php include_partial('weeks',array('action'=>$action,'date'=>$date)); ?>
        <div style="float:left; width:100%">
          <div class="widget">
            <h3><?php echo $pageTitle;?></h3>
    		<div class="widget-body">
    		  <ul class="wiki-meta">
                 <li><b>节目总数：</b><?php echo $programNum;?> &nbsp;<b>节目匹配wiki总数：</b><?php echo $programWikiNum;?>&nbsp;<b>匹配率：</b><?php echo $bili;?>%</li>
              </ul>
    		</div>
          </div>
        </div> 
        <div style="float:left; width:100%">
          <div class="widget">
            <h3>分频道统计</h3>
    		<div class="widget-body">
    		  <table>
                  <tr>
                    <td style="width: 50%;">频道名称</td>
                    <td style="width: 15%;">节目数</td>
                    <td style="width: 15%;">匹配数</td>
                    <td style="width: 20%;">匹配率</td>
                  </tr>
                  <?php foreach($channelPrograms as $rs):?>
                  <tr>
                    <td <?php if($rs['bili']<51):?>style="background-color: #ffdddd;"<?php endif;?>><?php echo $rs['name'];?></td>
                    <td <?php if($rs['bili']<51):?>style="background-color: #ffdddd;"<?php endif;?>><?php echo $rs['num'];?></td>
                    <td <?php if($rs['bili']<51):?>style="background-color: #ffdddd;"<?php endif;?>><?php echo $rs['wikiNum'];?></td>
                    <td <?php if($rs['bili']<51):?>style="background-color: #ffdddd;"<?php endif;?>><?php echo $rs['bili'];?>%</td>
                  </tr>
                  <?php endforeach;?>
              </table>
    		</div>
          </div>
        </div> 
    </div>
  </div>