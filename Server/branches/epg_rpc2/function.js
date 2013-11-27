function getChannelList() 
{
    $env = $("#env").val()    window.open("method.php?env="+$env+"&method=getChannelList");
}

function getWeekByProvinceList()
{
    $env = $("#env").val();    window.open("method.php?env="+$env+"&method=getWeekByProvinceList&channel_code=" + $("#getWeekByProvinceList_channel_code").val());
}

function getNowPrograms()
{
    $env = $("#env").val();    window.open("method.php?env="+$env+"&method=getNowPrograms&channel_code=" + $("#getNowPrograms_channel_code").val());
}

function getLiveTags()
{
    $env = $("#env").val();    window.open("method.php?env="+$env+"&method=getLiveTags");
}

function getLiveList()
{
    $env = $("#env").val();
    $province = $("#getLiveList_province").val();
    $tag = $("#getLiveList_tag").val();    window.open("method.php?env="+$env+"&method=getLiveList&province="+$province+"&tag="+$tag);
}

function search()
{    
    $env = $("#env").val();    window.open("method.php?env="+$env+"&method=search");
}

function getWikiAllInfo()
{
    $env = $("#env").val();    window.open("method.php?env="+$env+"&method=getWikiAllInfo&wiki_id=" + $("#getWikiAllInfo_wiki_id").val());
}

function postUserLiving()
{
    $env = $("#env").val();    window.open("method.php?env="+$env+"&method=postUserLiving");
}

function getThemeList()
{
    $env = $("#env").val();
    window.open("method.php?env="+$env+"&method=getThemeList");
}

function getAllChannel()
{
    $env = $("#env").val();    window.open("method.php?env="+$env+"&method=getAllChannel");
}