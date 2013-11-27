<script type="text/javascript">
$(document).ready(function() {
    $("#select_city").click(function() {
        $("#city_show").show();
        return false;
    });
    $("#city_show .close").click(function() {
        $("#city_show").hide();
        return false;
    });
    $("#city_show ul li a").click(function() {
        province = $(this).text();
        $.cookie("province", province, {path: "/"});
        document.location.href = "<?php echo url_for("channel/index"); ?>";
        return false;
    });
    $("h2 a.toggle_panel").click(function() {
        var title_h2 = $(this).parents("h2");
        var channel_list = title_h2.nextAll("ul.channel_list").eq(0);
        channel_list.toggle();
        return false;
    });
    $("a.channel_taggle").click(function() {
        var channel_li = $(this).parents("li.level2");
        var channel_list = channel_li.find("ul");
        channel_list.toggle();
        return false;
    });
});
</script>

<div id="narrow">
    <div class="module">
        <h2>本地（<?php echo $province; ?>）<small><a id="select_city" href="#">修改城市</a></small>
            <div class="toggle collapsed"><a href="#" class="toggle_panel">&ndash;</a></div>
        </h2>
        <div id="city_show" class="select-city" style="display:none">
            <div class="close"><a class="close" href="#">x</a></div>
            <ul>
                <li><a href="#">北京</a></li>
                <li><a href="#">上海</a></li>
                <li><a href="#">黑龙江</a></li>
                <li><a href="#">吉林</a></li>
                <li><a href="#">辽宁</a></li>
                <li><a href="#">天津</a></li>
                <li><a href="#">安徽</a></li>
                <li><a href="#">江苏</a></li>
                <li><a href="#">浙江</a></li>
                <li><a href="#">陕西</a></li>
                <li><a href="#">湖北</a></li>
                <li><a href="#">湖南</a></li>
                <li><a href="#">甘肃</a></li>
                <li><a href="#">四川</a></li>
                <li><a href="#">山东</a></li>
                <li><a href="#">福建</a></li>
                <li><a href="#">河南</a></li>
                <li><a href="#">重庆</a></li>
                <li><a href="#">云南</a></li>
                <li><a href="#">河北</a></li>
                <li><a href="#">江西</a></li>
                <li><a href="#">山西</a></li>
                <li><a href="#">贵州</a></li>
                <li><a href="#">广东</a></li>
                <li><a href="#">广西</a></li>
                <li><a href="#">内蒙古</a></li>
                <li><a href="#">宁夏</a></li>
                <li><a href="#">青海</a></li>
                <li><a href="#">新疆</a></li>
                <li><a href="#">海南</a></li>
                <li><a href="#">西藏</a></li>
<!--                <li><a href="#">港澳台</a></li>-->
            </ul>
        </div>
        <ul class="channel_list">
            <?php foreach ($local_station as $local): ?>
            <li class="level2">
                <div class="cat2">
                    <div class="toggle"><a href="#" class="channel_taggle">&ndash;</a></div>
                    <a href="#" class="channel_taggle"><?php echo $local->getName(); ?></a></div>
                <ul>
                    <?php foreach ($local->getChannels() as $channel): ?>
                    <li><a href="<?php echo url_for("channel/show?id=".$channel->getId())?>"><?php echo $channel->getName()?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
        <h2><a href="#" class="toggle_panel">央视卫视</a>
            <div class="toggle collapsed"><a href="#" class="toggle_panel">&ndash;</a></div>
        </h2>
        <ul class="channel_list">
            <li class="level2">
                <div class="cat2">
                    <div class="toggle"><a href="#" class="channel_taggle">&ndash;</a></div>
                    <a href="#" class="channel_taggle">中央电视台</a></div>
                <ul>
                    <?php foreach ($cctv_channels as $cctv_channel): ?>
                    <li><a href="<?php echo url_for("channel/show?id=".$cctv_channel->getId())?>"><?php echo $cctv_channel->getName()?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li class="level2">
                <div class="cat2">
                    <div class="toggle"><a href="#" class="channel_taggle">&ndash;</a></div>
                    <a href="#" class="channel_taggle">各省卫视</a></div>
                <ul>
                    <?php foreach ($tv_channels as $tv_channel): ?>
                    <li><a href="<?php echo url_for("channel/show?id=".$tv_channel->getId()); ?>"><?php echo $tv_channel->getName(); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </div>
</div>
