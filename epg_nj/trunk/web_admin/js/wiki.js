$(function(){
    var td_msg    = $(".sf_admin_list_td_content");
    td_msg.click(function(){
        var value   = $(this).text();
        if($(this).find('input').html() == null)
        {
           var id      = $.trim($(this).parent().find('.sf_admin_list_td_id a').text());
           window.open('wiki/update');
        }
    });
});

