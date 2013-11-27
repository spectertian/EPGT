<div class="container filter">
<div class="container-inner">
    <div class="main-bd clearfix">
      <section id="section">
        <h2>节目检索</h2>
        <div class="filter-tab clearfix">
          <ul>
			<?php foreach($types as $searcType):?>
            <?php if($searchCondition['type']==$searcType): ?>
            <li><a href="<?php echo url_for('list/index?type='.$searcType)?>" class="active" ><?php echo $searcType; ?></a></li>
            <?php else:?>
            <li><a href="<?php echo url_for('list/index?type='.$searcType)?>" ><?php echo $searcType; ?></a></li>
            <?php endif;?>
            <?php endforeach;?>
          </ul>
        </div>
        <div class="filter-option">
          <div class="filter-option-hd">
            <dl>
              <dt>筛选条件：</dt>
              <?php if($searchCondition['type'] !=="全部"): ?>
              <?php foreach($searchCondition as $key => $value): ?>
              <?php if($value && $value !=="全部" ):?>
              <dd>
              <?php if($key == "time"): ?>
              <?php if($value=="lt1980"):?>
              1980年以前
              <?php else:?>
              <?php echo showTimeRange($value) ?showTimeRange($value) :"全部"; ?>
              <?php endif;?>
              <?php else:?>
              <?php if($value!="电影" && $value!="电视剧" && $value!="体育" && $value!="娱乐" && $value!="少儿" && $value!="科教" && $value!="财经" && $value!="综合"):?>
			  <?php echo $value; ?>
              <?php else:?>
              <?php if(($sf_request->getParameter('tag')=="" || $sf_request->getParameter('tag')=="全部") && ($sf_request->getParameter('area')=="" || $sf_request->getParameter('area')=="全部") && ($sf_request->getParameter('time')=="" || $sf_request->getParameter('time')=="全部")):?>
              
              <dd>使用下面的过滤器完善您的选择。</dd>
              <?php endif;?>
              <?php endif;?>
              <?php endif;?>
              <?php if($key !=='type'):?>
              <a href="<?php echo url_for('list/index?'.getQueryStrFromArray(array('sort'=>$sort,'style'=>$style,'type'=>$type,'tag'=>$sf_request->getParameter('tag'),'area'=>$sf_request->getParameter('area'),'time'=>$sf_request->getParameter('time')),$key,'')) ?>" class="x">x</a></dd>
              <?php endif;?>
              <?php endif; ?> 
              <?php endforeach;?>
              <?php else:?>
              <dd>使用下面的过滤器完善您的选择。</dd>
              <?php endif;?>
            </dl>
          </div>
          <div class="filter-option-bd">
            <!-- <dl>
              <dt>按分类：</dt>
              <dd><a href="#">全部</a></dd>
              <dd><a href="#">电视剧</a></dd>
              <dd><a href="#" class="active">电影</a></dd>
              <dd><a href="#">体育</a></dd>
              <dd><a href="#">娱乐</a></dd>
              <dd><a href="#">少儿</a></dd>
              <dd><a href="#">科教</a></dd>
              <dd><a href="#">财经</a></dd>
              <dd><a href="#">综合</a></dd>
            </dl> -->
           <?php if($wikiTagsRepons): ?>
            <dl id="searchType">
              <dt>按类型：</dt>
              <?php foreach($wikiTagsRepons as $key => $tags) :?>
              <?php if($key==$searchCondition['tag']): ?>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"tag",$tags)); ?>" <?php  if($sf_request->getParameter('tag')=="全部" || $sf_request->getParameter('tag')=="" ):?>  class="active" <?php endif;?> ><?php echo $tags;?></a></dd>
              <?php else: ?>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"tag",$tags)); ?>" <?php  if($sf_request->getParameter('tag')==$tags):?> class="active" <?php endif;?>><?php echo $tags;?></a></dd>
              <?php endif; ?>
              <?php endforeach; ?>
            </dl>
            <?php endif; ?>
            
            <dl id="searchArea">
              <dt>按地区：</dt>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"area","全部")); ?>" <?php if($searchCondition['area']=='全部'): ?> class="active"<?php endif;?>>全部</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"area","华语")); ?>" <?php if($searchCondition['area']=='华语'): ?> class="active"<?php endif;?>>华语</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"area","美国")); ?>" <?php if($searchCondition['area']=='美国'): ?> class="active"<?php endif;?>>美国</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"area","欧洲")); ?>" <?php if($searchCondition['area']=='欧洲'): ?> class="active"<?php endif;?>>欧洲</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"area","日本")); ?>" <?php if($searchCondition['area']=='日本'): ?> class="active"<?php endif;?>>日本</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"area","韩国")); ?>" <?php if($searchCondition['area']=='韩国'): ?> class="active"<?php endif;?>>韩国</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"area","其它")); ?>" <?php if($searchCondition['area']=='其它'): ?> class="active"<?php endif;?>>其它</a></dd>
            </dl>
            <dl id="searchTime">
              <dt>按时间：</dt>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","全部")); ?>" <?php if($searchCondition['time']=='全部'): ?> class="active"<?php endif;?>>全部</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","2011-2011")); ?>" <?php if($searchCondition['time']=='2011-2011'): ?> class="active"<?php endif;?>>2011</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","2010-2010")); ?>" <?php if($searchCondition['time']=='2010-2010'): ?> class="active"<?php endif;?>>2010</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","2009-2009")); ?>" <?php if($searchCondition['time']=='2009-2009'): ?> class="active"<?php endif;?>>2009</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","2000-2008")); ?>" <?php if($searchCondition['time']=='2000-2008'): ?> class="active"<?php endif;?>>2000-2008</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","1990-1999")); ?>" <?php if($searchCondition['time']=='1990-1999'): ?> class="active"<?php endif;?>>1990-1999</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","1980-1989")); ?>" <?php if($searchCondition['time']=='1980-1989'): ?> class="active"<?php endif;?>>1980-1989</a></dd>
              <dd><a href="<?php echo url_for('list/index?'.getQueryStrFromArray($searchCondition,"time","lt1980")); ?>" <?php if($searchCondition['time']=='lt1980'): ?> class="active"<?php endif;?>>1980年以前</a></dd>
            </dl>
          </div>
        </div>        
        <div class="filter-result">
          <div class="filter-result-hd">
            <div class="sort-by"> <span class="label">排序方式：</span>
              <ul>
                <li><a href="<?php echo url_for('list/index?page='.$page.'&style='.$style."&sort=2&".getQueryStrFromArray($searchCondition));?>" <?php if($sort==2): ?>class="active"<?php endif;?> title="评价">评价</a></li>
                <li ><a href="<?php echo url_for('list/index?page='.$page.'&style='.$style."&sort=0&".getQueryStrFromArray($searchCondition));?>" <?php if($sort==0): ?>class="active"<?php endif;?> title="相关度">最新</a></li>
              </ul>
            </div>
            <div class="view-as"> <span class="label">浏览方式：</span>
              <ul>
                <li class="tile"><a href="<?php echo url_for('list/index?page='.$page.'&style=list&'.getQueryStrFromArray($searchCondition));?>" <?php if($style=='list'): ?>class="active popup-tip"<?php else: ?>class="popup-tip"<?php endif;?> title="海报">海报</a></li>
                <li class="list"><a href="<?php echo url_for('list/index?page='.$page.'&style=title&'.getQueryStrFromArray($searchCondition));?>" <?php if($style=='title'): ?>class="active popup-tip"<?php else: ?>class="popup-tip"<?php endif;?> title="列表">列表</a></li>
              </ul>
            </div>
          </div>
          
          
        <?php if($style=="list"):?>  
            <?php include_partial('filter_list',array('wiki_pager'=>$wiki_pager)); ?>
        <?php else:?>	
            <?php include_partial('filter_title',array('wiki_pager'=>$wiki_pager)); ?>
        <?php endif;?>
        <?php if($wiki_pager->count() !=0):?>
      <div class="pagination">
              <?php if ($wiki_pager->getPage() == $wiki_pager->getFirstPage()) :?>
              <span class="page-cur"><?php echo $wiki_pager->getPage()?></span>
              <?php else :?>
              <a href="<?php echo url_for("list/index")."?page=".$wiki_pager->getPreviousPage()."&style=".$style."&sort=".$sort."&".getQueryStrFromArray($searchCondition); ?>" title="跳转至18页" class="page-prev">上一页</a> 
              <?php endif ;?>
              <span class="dots">...</span>
              <?php foreach($wiki_pager->getLinks(6) as $page) :?>
              <?php if ($wiki_pager->getPage() == $page) :?>
              <span class="page-cur"><?php echo $wiki_pager->getPage()?></span>
              <?php else :?>
             <a href="<?php echo url_for("list/index")."?page=".$page."&style=".$style."&sort=".$sort."&".getQueryStrFromArray($searchCondition); ?>" title="跳转至第<?php echo $page; ?>页"><?php echo $page; ?></a>
              <?php endif;?>
              <?php endforeach;?>
              <span class="dots">...</span>
              <?php if ($wiki_pager->getPage() == $wiki_pager->getLastPage()) :?>
              <span class="page-cur"><?php echo $wiki_pager->getPage()?></span>
              <?php else :?>
              <a href="<?php echo url_for("list/index")."?page=".$wiki_pager->getLastPage()."&style=".$style."&sort=".$sort."&".getQueryStrFromArray($searchCondition); ?>" title="跳转至<?php echo $wiki_pager->getLastPage(); ?>页" class="page-next"><?php echo $wiki_pager->getLastPage()?></a>
              <?php endif ;?>
              <a href="<?php echo url_for("list/index")."?page=".$wiki_pager->getNextPage()."&style=".$style."&sort=".$sort."&".getQueryStrFromArray($searchCondition); ?>" title="跳转至<?php echo $wiki_pager->getNextPage(); ?>页" class="page-next">下一页</a>
          </div>
        <?php endif;?>
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
        <?php include_component('list','hot_boardvideo');?>
      </aside>
    </div>
  </div>
</div>