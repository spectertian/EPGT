<div id="content-box">
    <div class="border">
        <div class="padding">
            <div id="toolbar-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                    <div class="header icon-48-addedit"><?php echo $pageTitle;?></div>
                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
            <?php include_partial('global/flashes') ?>
            <div id="element-box">
                <div class="t"><div class="t"><div class="t"></div></div></div>
                <div class="m">
                    <table cellspacing="1"class="adminlist">
                        <thead>
                            <tr>
                                <th class="title sf_admin_text sf_admin_list_th_id">页面名称</th>
                                <th class="title sf_admin_text sf_admin_list_th_author">最后编辑作者</th>
                                <th class="title sf_admin_text sf_admin_list_th_version">当前版本</th>
                                <th class="title sf_admin_text sf_admin_list_th_created_at">创建时间</th>
                                <th class="title sf_admin_text sf_admin_list_th_updated_at">更新时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row0">
                                <td class="sf_admin_text sf_admin_list_td_id" align="center"><a href="<?php echo url_for('page/edit?pagename=首页');?>">首页</a></td>
                                <?php if (!is_null($indexPage)) :?>
                                <td class="sf_admin_text sf_admin_list_td_author" align="center"><?php echo $indexPage->getAuthor()?></td>
                                <td class="sf_admin_text sf_admin_list_td_version" align="center"><?php echo $indexPage->getVersion()?></td>
                                <td class="sf_admin_text sf_admin_list_td_created_at" align="center"><?php echo $indexPage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <td class="sf_admin_text sf_admin_list_td_updated_at" align="center"><?php echo ($update_at = $indexPage->getUpdatedAt()) ? $update_at->format("Y-m-d H:i:s") :$indexPage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <?php endif;?>
                            </tr>
                            <tr class="row1">
                                <td class="sf_admin_text sf_admin_list_td_id" align="center"><a href="<?php echo url_for('page/edit?pagename=影视');?>">影视</a></td>
                                <?php if (!is_null($yingshiPage)) :?>
                                <td class="sf_admin_text sf_admin_list_td_author" align="center"><?php echo $yingshiPage->getAuthor()?></td>
                                <td class="sf_admin_text sf_admin_list_td_version" align="center"><?php echo $yingshiPage->getVersion()?></td>
                                <td class="sf_admin_text sf_admin_list_td_created_at" align="center"><?php echo $yingshiPage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <td class="sf_admin_text sf_admin_list_td_updated_at" align="center"><?php echo ($update_at = $yingshiPage->getUpdatedAt()) ? $update_at->format("Y-m-d H:i:s") : $yingshiPage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <?php endif;?>
                            </tr>
                            <tr class="row0">
                                <td class="sf_admin_text sf_admin_list_td_id" align="center"><a href="<?php echo url_for('page/edit?pagename=综艺');?>">综艺</a></td>
                                <?php if (!is_null($zongyiPage)) :?>
                                <td class="sf_admin_text sf_admin_list_td_author" align="center"><?php echo $zongyiPage->getAuthor()?></td>
                                <td class="sf_admin_text sf_admin_list_td_version" align="center"><?php echo $zongyiPage->getVersion()?></td>
                                <td class="sf_admin_text sf_admin_list_td_created_at" align="center"><?php echo $zongyiPage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <td class="sf_admin_text sf_admin_list_td_updated_at" align="center"><?php echo ($update_at = $zongyiPage->getUpdatedAt()) ? $update_at->format("Y-m-d H:i:s") : $zongyiPage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <?php endif;?>
                            </tr>
                            <tr class="row1">
                                <td class="sf_admin_text sf_admin_list_td_id" align="center"><a href="<?php echo url_for('page/edit?pagename=社科');?>">社科</a></td>
                                <?php if (!is_null($shekePage)) :?>
                                <td class="sf_admin_text sf_admin_list_td_author" align="center"><?php echo $shekePage->getAuthor()?></td>
                                <td class="sf_admin_text sf_admin_list_td_version" align="center"><?php echo $shekePage->getVersion()?></td>
                                <td class="sf_admin_text sf_admin_list_td_created_at" align="center"><?php echo $shekePage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <td class="sf_admin_text sf_admin_list_td_updated_at" align="center"><?php echo ($update_at = $shekePage->getUpdatedAt()) ? $update_at->format("Y-m-d H:i:s") :$shekePage->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                                <?php endif;?>
                            </tr>
                        </tbody>
                    </table>
                    <div class="clr"></div>
                </div>
                <div class="b"><div class="b"><div class="b"></div></div></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
</div>