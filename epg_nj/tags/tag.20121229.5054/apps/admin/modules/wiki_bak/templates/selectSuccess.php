<?php
include_javascripts();
?>
<div id="show">
    <table width="80%" align="center" style="margin:3px 0;" >
        <tr>
            <td style="color: red;">影视模型:</td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=movie';?>">电影</a></td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=tv';?>">电视剧</a></td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td style="color: red;">NBA模型:</td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=nba_coalition';?>">NBA联盟</a></td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=nba_partition';?>">NBA分区</a></td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=nba_team';?>">NBA球队</a></td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=nba_palyer';?>">NBA球员</a></td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td style="color: red;">足球模型:</td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=football';?>">足球分类</a></td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=fb_international';?>">足球球队</a></td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=fb_player';?>">足球球员</a></td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td style="color: red;">其他模型:</td>
            <td><a href="<?php echo url_for('@wiki').'/new?style=lanmu';?>">栏目</a></td>
        </tr>
    </table>
</div>
