<form method="get" action="">
    <label>wiki：</label>
		<select name='wiki'>
			<option value='' <?php if ($wiki=='0') echo 'selected'; ?>>请选择</option>
			<option value='1' <?php if ($wiki=='1') echo 'selected'; ?>>有WIKI</option>
			<option value='2' <?php if ($wiki=='2') echo 'selected'; ?>>无WIKI</option>
		</select>
    <label>查询字段：</label>

	<select name='field'>
		<option value='1' <?php if ($field=='1') echo 'selected'; ?>>TITLE</option>
		<option value='2' <?php if ($field=='2') echo 'selected'; ?>>contentID</option>
	</select>
	<input name='text' value="<?php  echo $text; ?>"   type="text">
    <label>模型：</label>

	<select name='model'>
		<option value='all' <?php if ($model=='all') echo 'selected'; ?>>请选择</option>
		<option value='film' <?php if ($model=='film') echo 'selected'; ?>>电影</option>
		<option value='teleplay' <?php if ($model=='teleplay') echo 'selected'; ?>>电视剧</option>
		<option value='television' <?php if ($model=='television') echo 'selected'; ?>>栏目</option>
	</select>
	<label>状态：</label>
	<select name='state'>
		<option value='all' <?php if ($state=='all') echo 'selected'; ?>>请选择</option>
		<option value='0' <?php if ($state=='0') echo 'selected'; ?>>未发布</option>
		<option value='1' <?php if ($state=='1') echo 'selected'; ?>>已发布</option>
	</select>	
 <input type="submit" value="查询"> &nbsp;
 </form>
