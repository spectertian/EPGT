<form action="<?php echo url_for('program_live/index')?>" method="get">
  频道:
  <select name="channel" id="channel">
<option value="" >不限</option>
        <option value="1" <?php if($channel=='1'){echo 'selected';}?>>所有央视和卫视</option>
        <option value="2" <?php if($channel=='2'){echo 'selected';}?>>所有央视</option>
        <option value="3" <?php if($channel=='3'){echo 'selected';}?>>所有卫视</option>
    </select>
    时间:
    <input name="start_time" value="<?php echo $start_time; ?>" id="start_time" type="text" style="width: 35px;"/>到
    <input name="end_time" value="<?php echo $end_time; ?>" id="end_time" type="text" style="width: 35px;"/>
    例：10:00
  <input type="submit" name="button" id="button" value="查询">
</form>