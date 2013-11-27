<br/>
<script type="text/javascript">
function fileChange(files)
{
    tb_remove();
    console.log(files);
}

function test(key,link)
{
    alert(key);
}

</script>
<form>
    <textarea rows="10" cols="50" id="insert_files" ></textarea>
</form>
<a href="<?php echo url_for('media/link');?>?height=600&width=1000&TB_iframe=false" class="thickbox" >
    上传文件
</a>

