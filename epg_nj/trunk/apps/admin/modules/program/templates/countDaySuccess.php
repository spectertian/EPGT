<div id="content">
    <div class="content_inner">
        <?php include_partial("toobal") ?>
        <?php include_partial('global/flashes') ?>
        <div class="table_nav">
        <table border="1" align="center">
            <tr>
            <td style="width: 30%;">频道名称</td>
            <?php foreach($dates as $date):?>
            <td style="width: 10%;"><?php echo $date;?></td>
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
</div>