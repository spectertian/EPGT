<script language="javascript">

$(document).ready(function(){
	
    $('.datepicker_s').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });

    $('.datepicker_e').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });
    
});

</script>
	<?php include_partial('global/flashes') ?> 
	<div id="warp">
    
      <div class="r">
            	<header>
                    <h2 class="content"><?php echo $PageTitle; ?></h2>
                    <nav class="utility">
                        <li class="back"><a href="<?php echo url_for("category_recommends/list");?>">返回列表</a></li>
                    </nav>
                </header>
				<?php include_partial('global/flashes') ?>
				<div id="stock">  
				</div>
		<div id="div_2" >
		<form name="" method="get" id="" class="listitem" action="">
        	<ul>
                <li>
                	<h2>数据保存</h2>
                </li>
				<li><label>类型:</label>
				<select id='out_category' ">
				<option value="defulit">请选择类型</option>
					<?php 
						foreach ($classesArray as $k=>$v)
						{
							echo "<option value=\"{$k}\">".$v."</option>";
						}
					?>
				</select>
                </li>
                <li id="out_list" ><label>名称:</label>
					<input name="out_name" id="out_name"  value="" type="out_name">(*必填*)
				</li>
				<li id="out_list" ><label>选择时间</label>
					<input type="button" name="start_time" value="起始日期" maxlengtjh="10"  class="datepicker_s">&nbsp;——&nbsp;
					<input type="button" name="end_time" value="结束日期" maxlengtjh="10"   class="datepicker_e">&nbsp;&nbsp;<a href='#' onclick='cleardate()'>重置</a>
				</li>
				<li><label>是否默认:</label>
				<select id='is_default' ">
					<option value="yes">是</option>
					<option value="no">否</option>
				</select>
                </li>
				
                <li id="list_button_2" ><input type="button" value="保存" class="btn" onclick = "dataout('<?php echo url_for("category_recommends/getdata");?>');" /><input type="reset" value="重置"  class="btn"/></li>
            </ul>
            
        </form>
		</div>
    </div>
    </div>