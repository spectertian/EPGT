<div id="content">
    <div class="content_inner">
        <?php include_partial("toobal") ?>
        <?php include_partial('global/flashes') ?>
        <div class="table_nav">
        <p>提示：也可在该网址后面加上?tvid=114 参数说明：(tvid: 电视台ID)</p>
        <table border="1" align="center">
            <tr>
            <td width="30%">频道名称</td>
            <?php foreach($dates as $date):?>
            <td width="10%"><?php echo $date;?></td>
            <?php endforeach;?>
            </tr>
            <?php foreach($channelPrograms as $key=>$value):?>
            <tr>
                <td><?php echo $key;?></td>
                <?php foreach($value as $programNum):?>
                <td <?php if($programNum==0):?>style="background-color: #ff0000;"<?php endif;?>><?php echo $programNum;?></td>
                <?php endforeach;?>
            </tr>    
            <?php endforeach;?>
        </table>
        </div>
    </div>  
    <div style='padding:0 0 35px 0'><?php include_partial("foottoobal") ?></div>  
</div>