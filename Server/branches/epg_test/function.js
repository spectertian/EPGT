$developer = '<developer apikey="FBCECY2E" secretkey="9ef2977d62f85681eae90788b13678af"/>';

function GetMediaCategory() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetMediaCategory' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /></parameter></request>");
	$("#post-form").submit();
}

function GetMediaListByCategory() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetMediaListByCategory' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data cid='"+
    $("#GetMediaListByCategory_cid").val()+"' order='2' page='1' size='10'></data></parameter></request>");
	$("#post-form").submit();
}

function GetRecommendMedia() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter language='zh-CN' type='GetRecommendMedia'><user token='0928ea7b3b114767965b5e4aed130bca' ver='2' huanid='120072304'/>"+$developer+"<device didtoken='6bb89078ffb9566c1ac08c62fd565725' devmodel='CH-LM38ICS-DTV-00-MG' ver='12.3.4' dnum='12345'/><data tag='' size='12' type='"+$("#GetRecommendMedia_type").val()+"'></data></parameter></request>");
	//$("#xmlString").val("<?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter language='zh-CN' type='GetRecommendMedia'><user token='c7924b52d80140bfb0dd22c20e42fb77' ver='2' huanid='130161754'/><device didtoken='e376bf9d9cae181cb2d522d52a7a6ce4' devmodel='CH-LM38-DTV-3D' ver='12.3.4' dnum='12345'/><data type='1' page='1' size='35'><filter/></data></parameter></request>");
    $("#post-form").submit();
}

function GetFilterOption() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetFilterOption' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /></parameter></request>");
	$("#post-form").submit();
}

function ReportUserMediaAction() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='ReportUserMediaAction' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data type='"+$("#ReportUserMediaAction_type").val()+"' mid='"+$("#ReportUserMediaAction_mid").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function ReportUserEpisodeAction() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='ReportUserEpisodeAction' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data type='"+$("#ReportUserEpisodeAction_type").val()+"' mid='"+$("#ReportUserEpisodeAction_mid").val()+"' eid='"+$("#ReportUserEpisodeAction_eid").val()+"' marktime='"+$("#ReportUserEpisodeAction_marktime").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function DeleteUserEpisodeAction() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='DeleteUserEpisodeAction' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data markid='"+$("#DeleteUserEpisodeAction_markid").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetMediaListByMedia() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetMediaListByMedia' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4'/><user huanid='12345' token='x' ver='2' /><data mid='"+$("#GetMediaListByMedia_mid").val()+"' page='1' size='10'/></parameter></request>");
	$("#post-form").submit();
}

function GetMediaListByUser() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetMediaListByUser' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data type='"+$("#GetMediaListByUser_type").val()+"' page='1' size='8'/></parameter></request>");
	$("#post-form").submit();
}

function GetEpisodeListByUser() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetEpisodeListByUser' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data type='"+$("#GetEpisodeListByUser_type").val()+"' page='1' size='10'/></parameter></request>");
	$("#post-form").submit();
}

function SearchMedia() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='SearchMedia' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4'/><user huanid='12345' token='x' ver='2'/><data keyword='"+$("#SearchMedia_keyword").val()+"' page='1' size='10'/></parameter></request>");
	$("#post-form").submit();
}

function SearchWiki() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='SearchWiki' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4'/><user huanid='12345' token='x' ver='2'/><data keyword='"+$("#SearchWiki_keyword").val()+"' page='1' size='10'/></parameter></request>");
	$("#post-form").submit();
}

function GetSpecifiedMedia() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetSpecifiedMedia' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data mid='"+$("#GetSpecifiedMedia_mid").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetThemeList() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetThemeList' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data scene='"+$("#GetThemeList_scene").val()+"' size='10' page='1'/></parameter></request>");
	$("#post-form").submit();
}

function GetThemeById() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetThemeById' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4'/><user huanid='12345' token='x' ver='2'/><data tid='"+$("#GetThemeById_tid").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetChannelList() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetChannelList' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data province='"+$("#GetChannelList_province").val()+"' type='"+$("#GetChannelList_type").val()+"' sort='"+$("#GetChannelList_sort").val()+"' showlive='1' page='1' pagesize='10'/></parameter></request>");
	$("#post-form").submit();
}

function GetProgramListByChannel() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetProgramListByChannel' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data channel_code='"+$("#GetProgramListByChannel_channelcode").val()+"' start_time='"+$("#GetProgramListByChannel_start_time").val()+"' end_time='"+$("#GetProgramListByChannel_end_time").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetRecommendByChannel() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetRecommendByChannel' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data channel_code='"+$("#GetRecommendByChannel_channel_code").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function GetLiveCategory() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetLiveCategory' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /></parameter></request>");
	$("#post-form").submit();
}

function GetLivePrograme() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetLivePrograme' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data province='"+$("#GetLivePrograme_province").val()+"' tag='"+$("#GetLivePrograme_tag").val()+"' start_time='"+$("#GetLivePrograme_start_time").val()+"' end_time='"+$("#GetLivePrograme_end_time").val()+"' pagesize='100'/></parameter></request>");
	$("#post-form").submit();
}

function GetWikiInfo() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetWikiInfo' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data wiki_id='"+$("#GetWikiInfo_wiki_id").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function GetChannelListBySP() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetChannelListBySP' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data spname='"+$("#GetChannelListBySP_spname").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function GetSPList() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetSPList' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data page='"+$("#GetSPList_page").val()+"' size='"+$("#GetSPList_size").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function SearchSuggest() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='SearchSuggest' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data keyword='"+$("#SearchSuggest_keyword").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetSPMediaListByCategory() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='GetSPMediaListByCategory' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data sp='"+$("#GetSPMediaListByCategory_sp").val()+"' cid='"+$("#GetSPMediaListByCategory_cid").val()+"' order='"+$("#GetSPMediaListByCategory_order").val()+"' page='"+$("#GetSPMediaListByCategory_page").val()+"' size='"+$("#GetSPMediaListByCategory_size").val()+"' type='"+$("#GetSPMediaListByCategory_type").val()+"' value='"+$("#GetSPMediaListByCategory_value").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function SearchProgram() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://www.epg.huan.tv'><parameter type='SearchProgram' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='12345' didtoken='x' ver='12.3.4' /><user huanid='12345' token='x' ver='2' /><data key='"+$("#SearchProgram_keyword").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function ReportUserProgramAction(){
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='ReportUserProgramAction' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data type='"+$("#ReportUserProgramAction_type").val()+"' channel_code='"+$("#ReportUserProgramAction_channel_code").val()+"' name='"+$("#ReportUserProgramAction_name").val()+"' start_time='"+$("#ReportUserProgramAction_start_time").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function GetProgramListByUser() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetProgramListByUser' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data type='"+$("#GetProgramListByUser_type").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function ReportUserLivingAction(){
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='ReportUserLivingAction' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data channel_code='"+$("#ReportUserLivingAction_channel_code").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function GetSystemCitys() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetSystemCitys' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /></parameter></request>");
	$("#post-form").submit();
}

function GetDtvSPList() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetDtvSPList' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data province='"+$("#GetDtvSPList_province").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function SetUserConfig() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='SetUserConfig' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data province='"+$("#SetUserConfig_province").val()+"' city='"+$("#SetUserConfig_city").val()+"' dtvsp='"+$("#SetUserConfig_dtvsp").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function GetUserConfig() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetUserConfig' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /></parameter></request>");
	$("#post-form").submit();
}

function GetAllChannelProgram() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetAllChannelProgram' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data province='"+$("#GetAllChannelProgram_province").val()+"' date='"+$("#GetAllChannelProgram_date").val()+"'/></parameter></request>");
	$("#post-form").submit();
}

function ReportChannelName() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='ReportChannelName' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data dtvsp='"+$("#ReportChannelName_dtvsp").val()+"' name='"+$("#ReportChannelName_name").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetHotRecommendList() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetHotRecommendList' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data scene='"+$("#GetHotRecommendList_scene").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetChannelListByUser() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetChannelListByUser' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data page='"+$("#GetChannelListByUser_page").val()+"' size='"+$("#GetChannelListByUser_size").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function ReportUserChannelAction() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='ReportUserChannelAction' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data type='"+$("#ReportUserChannelAction_type").val()+"' channel_code='"+$("#ReportUserChannelAction_channel_code").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetLiveProgrameByUser() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetLiveProgrameByUser' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data start_time='"+$("#GetLiveProgrameByUsern_start_time").val()+"' end_time='"+$("#GetLiveProgrameByUserl_end_time").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetProgramListByMedia() {
	$("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetProgramListByMedia' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data wiki_id='"+$("#GetProgramListByMedia_wiki_id").val()+"' start_time='"+$("#GetProgramListByMedia_start_time").val()+"' end_time='"+$("#GetProgramListByMedia_end_time").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetWikiPackage() {
    $("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetWikiPackage' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data scene='"+$("#GetWikiPackage_scene").val()+"' page='1' size='10' /></parameter></request>");
	$("#post-form").submit();
}

function GetCategoryRecommend() {
    $("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetCategoryRecommend' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data tag='"+$("#GetCategoryRecommend_tag").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetShortMoviePackageInfoById() {
    $("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetShortMoviePackageInfoById' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data packageid='"+$("#GetShortMoviePackageInfoById_packageid").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetYesterdayProgramByDate() {
    $("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetYesterdayProgramByDate' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data date='"+$("#GetYesterdayProgramByDate_date").val()+"' /></parameter></request>");
	$("#post-form").submit();
}

function GetProgramRec(){
    $("#post-form").attr('action',$('#env').val());
	$("#xmlString").val("<\?xml version='1.0' encoding='utf-8'?><request website='http://iptv.cedock.com'><parameter type='GetProgramRec' language='zh-CN'>"+$developer+"<device devmodel='hs16' dnum='1234' didtoken='x' ver='12.3.4' /><user huanid='1234' token='x' ver='2' /><data /></parameter></request>");
	$("#post-form").submit();
}

Date.prototype.format = function (formatStr) {    
    var date = this;    
    /*   
    函数：填充0字符   
    参数：value-需要填充的字符串, length-总长度   
    返回：填充后的字符串   
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
