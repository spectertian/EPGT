<div id="content">
    <div class="content_inner">
        <?php include_partial("toobal") ?>
        <?php include_partial('global/flashes') ?>
        <div class="table_nav">
        <p style="text-align: center; font-size:18px; font-weight: bold;">日期：<?php echo $date;?></p>
        <p>提示：也可在该网址后面加上?date=2013-01-17&tvid=114 参数说明：(date：日期 tvid: 电视台ID)</p>
        <table border="1" align="center">
            <tr>
            <td width="40%">频道名称</td>
            <td width="15%">00:00 - 09:00</td>
            <td width="15%">09:00 - 18:00</td>
            <td width="15%">18:00 - 24:00</td>
            <td width="15%">总计</td>
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