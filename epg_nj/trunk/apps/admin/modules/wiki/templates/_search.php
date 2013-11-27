<form method="get" action="">
<label>名称：</label>
<input name="q" id="q"  value="<?php echo $q?>" type="text">
<label>标签：</label>
<input name="tag" id="tag"  value="<?php echo $tag?>" type="text">
<label>创建者：</label>
<select name="c" id="c">创建者
<option value="">不限</option>
<?php foreach($users as $user):?>
<option value="<?php echo $user->getId()?>" <?php echo ($user->getId() == $c) ? 'selected=selected' : ''?>><?php echo $user->getName()?></option>
<?php endforeach;?>
</select>
模型：
<select name="m">
    <option value="all"<?php echo ('all' == $m) ? 'selected=selected' : ''?>>全部</option>
    <option value="film"<?php echo ('film' == $m) ? 'selected=selected' : ''?>>电影</option>
    <option value="teleplay"<?php echo ('teleplay' == $m) ? 'selected=selected' : ''?>>电视剧</option>
    <option value="television"<?php echo ('television' == $m) ? 'selected=selected' : ''?>>栏目</option>
    <option value="actor"<?php echo ('actor' == $m) ? 'selected=selected' : ''?>>艺人</option>
</select>
点播源：
<select name="video">
    <option value="">全部</option>
    <option value="1" <?php echo ('1' == $video) ? 'selected=selected' : ''?>>有视频</option>
    <option value="-1" <?php echo ('-1' == $video) ? 'selected=selected' : ''?>>无视频</option>
</select>
<input type="submit" value="查询" />