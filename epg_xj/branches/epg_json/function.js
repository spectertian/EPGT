
function GetChannels() {
	$posturl = $('#env').val();
	$action = 'GetChannels';
	$param = '{"type":"'+$('#GetChannels_type').val()+'","showlive":"'+$('#GetChannels_show').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetChannelsByRecommended() {
	$posturl = $('#env').val();
	$action = 'GetChannelsByRecommended';
	$param = '{"type":"'+$('#GetChannelsByRecommended_type').val()+'","showlive":"'+$('#GetChannelsByRecommended_show').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetLivePrograms() {
	$posturl = $('#env').val();
	$action = 'GetLivePrograms';
	$param = '{"tag":"'+$('#GetLivePrograms_tags').val()+'","type":"'+$('#GetLivePrograms_type').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}



function GetPrograms() {
	$posturl = $('#env').val();
	$action = 'GetPrograms';
	$param = '{"type":"'+$('#GetPrograms_type').val()+'","tag":"'+$('#GetPrograms_tags').val()+'","startTime":"'+$('#GetPrograms_st').val()+'","endTime":"'+$('#GetPrograms_et').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetProgramsByChannel() {
	$posturl = $('#env').val();
	$action = 'GetProgramsByChannel';
	$param = '{"channelId":"'+$('#GetProgramsByChannel_channelId').val()+'","startTime":"'+$('#GetProgramsByChannel_start_time').val()+'","endTime":"'+$('#GetProgramsByChannel_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}


function GetProgramsOfDateByChannel() {
	$posturl = $('#env').val();
	$action = 'GetProgramsOfDateByChannel';
	$param = '{"channelId":"'+$('#GetProgramsOfDateByChannel_channelId').val()+'","date":"'+$('#GetProgramsOfDateByChannel_date').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}



function GetProgramsByWiki() {
	$posturl = $('#env').val();
	$action = 'GetProgramsByWiki';
	$param = '{"wikiId":"'+$('#GetProgramsByWiki_wiki').val()+'","startTime":"'+$('#GetProgramsByWiki_start_time').val()+'","endTime":"'+$('#GetProgramsByWiki_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}


function SearchPrograms() {
	$posturl = $('#env').val();
	$action = 'SearchPrograms';
	$param = '{"keyword":"'+$('#SearchPrograms_keyword').val()+'","startTime":"'+$('#SearchPrograms_start_time').val()+'","endTime":"'+$('#SearchPrograms_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetProgramsByRecommended() {
	$posturl = $('#env').val();
	$action = 'GetProgramsByRecommended';
	$param = '{"type":"'+$('#GetProgramsByRecommended_type').val()+'","startTime":"'+$('#GetProgramsByRecommended_start_time').val()+'","endTime":"'+$('#GetProgramsByRecommended_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}



function SetLikeChannelByUser() {
	$posturl = $('#env').val();
	$action = 'SetLikeChannelByUser';
	$param = '{"channelId":"'+$('#SetLikeChannelByUser_channelId').val()+'","userId":"'+$('#SetLikeChannelByUser_userId').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetLikeChannelsByUser() {
	$posturl = $('#env').val();
	$action = 'GetLikeChannelsByUser';
	$param = '{"userId":"'+$('#GetLikeChannelsByUser_userid').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function SetLikeWikiByUser() {
	$posturl = $('#env').val();
	$action = 'SetLikeWikiByUser';
	$param = '{"wikiId":"'+$('#SetLikeWikiByUser_wikiId').val()+'","userId":"'+$('#SetLikeWikiByUser_userId').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}



function GetLikeProgramsByWiki() {
	$posturl = $('#env').val();
	$action = 'GetLikeProgramsByWiki';
	$param = '{"wikiId":"'+$('#GetLikeByWiki_userId').val()+'","startTime":"'+$('#GetLikeByWiki_start_time').val()+'","endTime":"'+$('#GetLikeByWiki_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}


function SearchWikis() {
	$posturl = $('#env').val();
	$action = 'SearchWikis';
	$param = '{"keyword":"'+$('#SearchWikis_keyword').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetWikiInfo() {
	$posturl = $('#env').val();
	$action = 'GetWikiInfo';
	$param = '{"wikiId":"'+$('#GetWikiInfo_wikiId').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}



function OrderProgramByUser() {
	$posturl = $('#env').val();
	$action = 'OrderProgramByUser';
	$param = '{"userId":"'+$('#OrderProgramByUser_userId').val()+'","channelId":"'+$('#OrderProgramByUser_channelId').val()+'","startTime":"'+$('#OrderProgramByUser_start_time').val()+'","endTime":"'+$('#OrderProgramByUser_end_time').val()+'","programName":"'+$('#OrderProgramByUser_programName').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}



function UnOrderProgramByUser() {
	$posturl = $('#env').val();
	$action = 'UnOrderProgramByUser';
	$param = '{"userId":"'+$('#UnOrderProgramByUser_userId').val()+'","orderID":"'+$('#UnOrderProgramByUser_orderID').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
 
 function GetProgramOrdersByUser() {
	$posturl = $('#env').val();
	$action = 'GetProgramOrdersByUser';
	$param = '{"userId":"'+$('#GetProgramOrdersByUser_userId').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
 

  function GetLikeWikisByUser() {
	$posturl = $('#env').val();
	$action = 'GetLikeWikisByUser';
	$param = '{"userId":"'+$('#GetLikeWikisByUser_userId').val()+'","pagesize":"'+$('#GetLikeWikisByUser_pagesize').val()+'","page":"'+$('#GetLikeWikisByUser_page').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetMessagesByUser() {
	$posturl = $('#env').val();
	$action = 'GetMessagesByUser';
	$param = '{"type":"'+$('#GetMessagesByUser_type').val()+'","userId":"'+$('#GetMessagesByUser_userId').val()+'","pagesize":"'+$('#GetMessagesByUser_pagesize').val()+'","page":"'+$('#GetMessagesByUser_page').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
 
 
Date.prototype.format = function (formatStr) {    
    var date = this;    
    /*   
    �������0�ַ�   
    ����value-��Ҫ�����ַ�, length-�ܳ���   
    ���أ�������ַ�   
    */   
    var zeroize = function (value, length) {    
        if (!length) {    
            length = 2;    
        }    
        value = new String(value);    
        for (var i = 0, zeros = ''; i < (length - value.length); i++) {    
            zeros += '0';    
        }    
            return zeros + value;    
    };    
    return formatStr.replace(/"[^"]*"|'[^']*'|\b(?:d{1,4}|M{1,4}|yy(?:yy)?|([hHmstT])\1?|[lLZ])\b/g, function($0) {    
        switch ($0) {    
            case 'd': return date.getDate();    
            case 'dd': return zeroize(date.getDate());    
            case 'ddd': return ['Sun', 'Mon', 'Tue', 'Wed', 'Thr', 'Fri', 'Sat'][date.getDay()];    
            case 'dddd': return ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][date.getDay()];    
            case 'M': return date.getMonth() + 1;    
            case 'MM': return zeroize(date.getMonth() + 1);    
            case 'MMM': return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][date.getMonth()];    
            case 'MMMM': return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'][date.getMonth()];    
            case 'yy': return new String(date.getFullYear()).substr(2);    
            case 'yyyy': return date.getFullYear();    
            case 'h': return date.getHours() % 12 || 12;    
            case 'hh': return zeroize(date.getHours() % 12 || 12);    
            case 'H': return date.getHours();    
            case 'HH': return zeroize(date.getHours());    
            case 'm': return date.getMinutes();    
            case 'mm': return zeroize(date.getMinutes());    
            case 's': return date.getSeconds();    
            case 'ss': return zeroize(date.getSeconds());    
            case 'l': return date.getMilliseconds();    
            case 'll': return zeroize(date.getMilliseconds());    
            case 'tt': return date.getHours() < 12 ? 'am' : 'pm';    
            case 'TT': return date.getHours() < 12 ? 'AM' : 'PM';    
        }    
    });    
} 
