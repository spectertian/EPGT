<?php use_helper('Date') ?>
<script>
function Publish(publish)
{
    $("#publish_off").val(publish);
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('channel/publish')?>";
    admin_form.submit();
}
</script>
<div id="content">
        <div class="content_inner">
<header>
  <h2 class="content">统计列表</h2>
</header>
<div class="table_nav">              
  <div class="clear"></div>
</div>
<?php include_partial('global/flashes')?>
<?php include_partial("weeks",array('startdate'=>$startdate,'enddate'=>$enddate)); ?>

<table>
  <th width='65%'>日期</th>
  <th width='35%'>数量</th>
  <?php foreach ( $statcount as $date => $count ) : ?>
  <tr>
    <td><?php echo $date; ?></td>
    <td><span style="<?php if($count) echo ('color:red') ?>"><?php echo $count; ?><span></td>
  </tr>
  <?php endforeach ?>
  <tr><th>总计</th><th><?php echo $stattotal; ?></th></tr>
</table>