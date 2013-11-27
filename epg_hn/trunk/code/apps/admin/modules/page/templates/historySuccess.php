<div id="content-box">
    <div class="border">
        <div class="padding">
            <div id="toolbar-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                    <div id="toolbar" class="toolbar">
                        <table class="toolbar">
                            <tbody>
                                <tr>
                                    <td class="button"><a href="javascript:history.go(-1)"><span class="icon-32-cancel"></span>返回</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="header icon-48-addedit"><?php echo $pageTitle;?></div>
                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
            <div id="element-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                    <form method="post" name="adminForm" action="<?php echo url_for('page/index');?>/batch">
                        <table cellspacing="1"class="adminlist">
                            <thead>
                                <tr>                   
                                    <th class="title sf_admin_text sf_admin_list_th_version">版本号</th>
                                    <th class="title sf_admin_text sf_admin_list_th_author">作者</th>
                                    <th class="title sf_admin_text sf_admin_list_th_created_at">创建时间</th>
                                    <th class="title sf_admin_text sf_admin_list_th_updated_at">更新时间</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="9">
                                    <del class="container">
                                        <div class="pagination">
                                            <div class="button2-right">
                                                <div class="start">
                                                    <a href="<?php echo url_for('page/history?pagename='.$pagename.'&page='.$pager->getFirstPage());?>">首页</a>
                                                </div>
                                            </div>
                                            <div class="button2-right">
                                                <div class="prev">
                                                    <a href="<?php echo url_for('page/history?pagename='.$pagename.'&page='.$pager->getPreviousPage());?>">上一页</a>
                                                </div>
                                            </div>
                                            <div class="button2-left">
                                                <div class="page">
                                                    <?php foreach ($pager->getLinks(5) as $i => $link):?>
                                                        <?php if ($link == $pager->getPage()):?>
                                                            <span><?php echo $link;?></span>
                                                        <?php else:?>
                                                            <a href="<?php echo url_for('page/history?pagename='.$pagename.'&page='.$link);?>"><?php echo $link;?></a>
                                                        <?php endif;?>
                                                    <?php endforeach;?>
                                                </div>
                                            </div>
                                            <div class="button2-left">
                                                <div class="next">
                                                    <a href="<?php echo url_for('page/history?pagename='.$pagename.'&page='.$pager->getNextPage());?>">下一页</a>
                                                </div>
                                            </div>
                                            <div class="button2-left">
                                                <div class="end">
                                                    <a href="<?php echo url_for('page/history?pagename='.$pagename.'&page='.$pager->getLastPage());?>">尾页</a>
                                                </div>
                                            </div>
                                            <div class="limit">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</div>
                                        </div>
                                    </del>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php if(isset ($pager)):?>
                                  <?php foreach ($pager->getResults() as $i => $rs): ?>
                                  <tr class="row<?php echo ($i % 2) == 0 ? "0" : "1" ?>">
                                      <td class="sf_admin_text sf_admin_list_td_version" align="center"><a href="<?php echo url_for('page/show?id='.$rs->getId())?>"><?php echo $rs->getVersion()?></a></td>
                                        <td class="sf_admin_text sf_admin_list_td_author" align="center"><?php echo $rs->getAuthor()?></td>
                                        <td class="sf_admin_text sf_admin_list_td_created_at" align="center"><?php echo $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                        <td class="sf_admin_text sf_admin_list_td_updated_at" align="center"><?php echo ($update_at = $rs->getUpdatedAt()) ? $update_at->format("Y-m-d H:i:s") :$rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                   </tr>
                                   <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                        <input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
                        <input type="hidden" value="" name="batch_action">
                    </form>

                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
</div>