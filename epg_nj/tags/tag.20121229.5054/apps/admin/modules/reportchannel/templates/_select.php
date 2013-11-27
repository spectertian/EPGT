<form method="get" action="">
    dtvsp：
    <input name="d" type="text" id="d" size="10" value="<?php echo $d?>">
    频道名称：
    <input name="n" type="text" id="n" size="10" value="<?php echo $n?>">
    状态：
    <select name="s" id="s">
      <option value="">全部</option>
      <option value="1" <?php if($s==1){echo ' selected';}?>>已处理</option>
      <option value="0" <?php if($s==0&$s!=''){echo ' selected';}?>>未处理</option>
    </select>    
    &nbsp;
    <input type="submit" value="查询">
</form>