<div id="dm_postWrite_tag" class="pub_dropmenu" style="display:none;" onblur="$('#dm_postWrite_tag').slideUp('fast');">
<span style="font-weight:bold">电视剧</span>
<span style="font-weight:bold">电影</span>
<span style="font-weight:bold">体育</span>
<span style="font-weight:bold">娱乐</span>
<span style="font-weight:bold">少儿</span>
<span style="font-weight:bold">科教</span>
<span style="font-weight:bold">财经</span>
<span style="font-weight:bold">综合</span>
<hr />
<?php foreach($data[$cate] as $tag): ?>
<span><?php echo $tag; ?></span>
<?php endforeach; ?>
<a href="#" class="pub_dm_close" onclick="$('#dm_postWrite_tag').slideUp('fast'); return false;"> X </a>
</div>

<script type="text/javascript">
$('#wiki_tags').click(function(){$('#dm_postWrite_tag').slideDown('fast');return false;});
$('#dm_postWrite_tag').find('span').hover(function(){
    $(this).css({'color': 'red'});
    $(this).click(function() {
        var tags = $('#wiki_tags').val().split(/[,]+/g);
        var t = [];
        var new_tag =$(this).text();
        for (var i=0; i< tags.length; i++) {
            if (tags[i]=='') {
                continue;
            }
            if (tags[i] == new_tag) {
                return;
            }
            t.push(tags[i]);
        }
        if (t.length >= 20) {
            alert('最多可以填入20个标签');
            return;
        }
        tags = t;
        tags.push(new_tag);

        $('#wiki_tags').val(tags.join(',')).change();
    })
},
function() {
    $(this).css({'color': '#333333'});
}
);

</script>