
function GetChannelsBySP() {
	$posturl = $('#env').val();
	$action = 'GetChannelsBySP';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+"}";
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetProgramsByChannel() {
	$posturl = $('#env').val();
	$action = 'GetProgramsByChannel';
	$param = '{"channel_code":"'+$('#GetProgramsByChannel_channel_code').val()+'","start_time":"'+$('#GetProgramsByChannel_start_time').val()+'","end_time":"'+$('#GetProgramsByChannel_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetLiveCategory(){
	$posturl = $('#env').val();
	$action = 'GetLiveCategory';
	$param = '';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetLiveProgrameByTag() {
	$posturl = $('#env').val();
	$action = 'GetLiveProgrameByTag';
	$param = '{"tag":"'+$('#GetLiveProgrameByTag_tag').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function SearchProgram() {
	$posturl = $('#env').val();
	$action = 'SearchProgram';
	$param = '{"key":"'+$('#SearchProgram_key').val()+'","start_time":"'+$('#SearchProgram_start_time').val()+'","end_time":"'+$('#SearchProgram_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function SearchWiki() {
	$posturl = $('#env').val();
	$action = 'SearchWiki';
	$param = '{"keyword":"'+$('#SearchWiki_keyword').val()+'","hasvideo":"'+$('#SearchWiki_hasvideo').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetWikiInfo() {
	$posturl = $('#env').val();
	$action = 'GetWikiInfo';
	$param = '{"wiki_id":"'+$('#GetWikiInfo_wiki_id').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetWikiInfoByAssetId() {
	$posturl = $('#env').val();
	$action = 'GetWikiInfoByAssetId';
	$param = '{"asset_id":"'+$('#GetWikiInfoByAssetId_asset_id').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetWikisByWiki() {
	$posturl = $('#env').val();
	$action = 'GetWikisByWiki';
	$param = '{"wiki_id":"'+$('#GetWikiInfo_wiki_id').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetWikiMetas() {
	$posturl = $('#env').val();
	$action = 'GetWikiMetas';
	$param = '{"wiki_id":"'+$('#GetWikiMetas_wiki_id').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetAttachments() {
	$posturl = $('#env').val();
	$action = 'GetAttachments';
	$param = '{"start_time":"'+$('#GetAttachments_start_time').val()+'","end_time":"'+$('#GetAttachments_end_time').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
    //$jsonstr = '{"action":"GetAttachments","device":{"dnum": "123"},"param":{"start_time" : "2012-12-15 0:0:0","end_time":"2012-12-15 23:0:0"}}';
    //$jsonstr = '{"action":"GetAttachments","developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"device":{"dnum": "123"},"user":{"userid":"123"},"param":{"start_time" : "2013-04-11 0:0:0","end_time":"2013-04-12 0:0:0"}}';
    $("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetRecommendByChannel() {
    $posturl = $('#env').val();
	$action = 'GetRecommendByChannel';
	$param = '{"channel_code":"'+$('#GetProgramsByChannel_channel_code').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetWikiUrlByPackageId() {
    $posturl = $('#env').val();
	$action = 'GetWikiUrlByPackageId';
	$param = '{"package_id":"'+$('#GetWikiUrlByPackageId_package_id').val()+'","sp_id":"'+$('#GetWikiUrlByPackageId_sp_id').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetMediaCategory() {
    $posturl = $('#env').val();
	$action = 'GetMediaCategory';
	$param = '{}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetMediasByCategory() {
    $posturl = $('#env').val();
	$action = 'GetMediasByCategory';
	$param = '{"cid":'+$('#GetMediasByCategory_cid').val()+',"order":2}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}

function GetSceneRecommend() {
    $posturl = $('#env').val();
	$action = 'GetSceneRecommend';
	$param = '{"scene":"'+$('#GetSceneRecommend_scene').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function GetRecommendMedias() {
    $posturl = $('#env').val();
	$action = 'GetRecommendMedias';
	$param = '{"size":"'+$('#GetRecommendMedias_size').val()+'","sort":"'+$('#GetRecommendMedias_sort').val()+'","detail":"'+$('#GetRecommendMedias_detail').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function GetFilterOption() {
    $posturl = $('#env').val();
	$action = 'GetFilterOption';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function GetThemes() {
    $posturl = $('#env').val();
	$action = 'GetThemes';
	$param = '{"size":"'+$('#GetThemes_size').val()+'","page":"'+$('#GetThemes_page').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function GetThemeById() {
    $posturl = $('#env').val();
	$action = 'GetThemeById';
	$param = '{"tid":"'+$('#GetThemeById_tid').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function GetWikisByWiki() {
    $posturl = $('#env').val();
	$action = 'GetWikisByWiki';
	$param = '{"wiki_id":"'+$('#GetWikisByWiki_wiki_id').val()+'","extendtype":"'+$('#GetWikisByWiki_extendtype').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function SearchSuggest() {
    $posturl = $('#env').val();
	$action = 'SearchSuggest';
	$param = '{"keyword":"'+$('#SearchSuggest_key').val()+'"}';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+",\"param\":"+$param+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function GetSystemCitys() {
    $posturl = $('#env').val();
	$action = 'GetSystemCitys';
	$jsonstr = "{\"action\":\""+$action+"\",\"developer\":"+$developer+",\"user\":"+$user+",\"device\":"+$device+"}";
	$("#result").html($jsonstr);
    $("#post-form").attr('action',$posturl);
	$("#jsonstr").val($jsonstr);
	$("#post-form").submit();
}
function GetDtvSPList() {
    $posturl = $('#env').val();
	$action = 'GetDtvSPList';
	$param = '{"province":"'+$('#GetDtvSPList_province').val()+'"}';
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
