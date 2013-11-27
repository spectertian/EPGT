<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" id="minwidth" >
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
<!--[if IE 7]>
<link href="<?php echo javascript_path('ie7.css');?>" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lte IE 6]>
<link href="<?php echo javascript_path('ie6.css');?>" rel="stylesheet" type="text/css" />
<![endif]-->
<style type="text/css">
    HTML,BODY {
        margin: 0px;
        padding: 0px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $('#category_id').change(function(){
             $('#select_category_id').val($(this).val());
        });
    });

    function putData(parent)
    {
        parent.categoryChange($('#select_category_id').val());
    }

</script>
</head>
<body>
    <div style="width:350px;height:90px;border:1px #000 solid;">
        <form action="#" method="POST">
            <table>
                <tr>
                    <td>
                        <select id="category_id" name="category_id">
                            <?php foreach( $categorys as $category_id => $category_name ): ?>
                                <option value="<?php echo $category_id ?>" >
                                    <?php echo $category_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="select_category_id" value="0" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="button" value="保存" onclick="putData(self.parent);" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
