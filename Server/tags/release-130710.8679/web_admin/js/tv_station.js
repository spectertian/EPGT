$(function(){
        var publish    = $(".sf_admin_list_td_publish");
        var sort    = $(".sf_admin_list_td_sort");

        publish.click(function(){
            if($("#publish").html() == null )
            {
                var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
                $(this).attr('id','publish');
                ajax_publish(id);
            }
        });

        sort.click(function(){
            if($("#sort").html() == null )
            {
                var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
                $(this).attr('id','sort');
                $("#sort").html('<input id="postValue" value="'+ $.trim($("#sort").text())+'" onblur="ajax_update('+id+',\'sort\');">');
                $("#postValue").focus();
            }
        });
});


function ajax_update(id ,key)
{
    var value   = $("#postValue").val();

    $.ajax({
        url:            "tv_station/ajax_program_update?id=" + id +"&key="+key+"&value="+value,
        dataType:       "json",
        success:function(data)
        {
            if(data.code == 1)
            {
                $("#postValue").parent().html(value);
            }
            else
            {
                alert(data.msg);
            }
            $("#sort").attr('id', '');
        },error:function()
        {
        }
    });
}


function ajax_publish(id)
{

    $.ajax({
        url:            "tv_station/ajax_program_publish?id=" + id,
        dataType:       "json",
        success:function(data)
        {
            //un_publish="/images/delete.png";
            //publish="/images/accept.png";            
            if(data.code == 1)
            {
                if(data.msg == 0)
                {
                    $("#publish").html('<img src="/images/delete.png" title="UnChecked" alt="Unhecked">');
                }
                else
                {
                    $("#publish").html('<img src="/images/accept.png" title="Checked" alt="Checked">');
                }
                noticeShow(data.content);
                $("#publish").attr('id', '');
            }
            else
            {
                alert(data.msg);
            }

        },error:function()
        {
        }
    });
}