var playvideo = function (params) {
	return PhoneGap.exec(null, null, 'VideoPlugin', '', [params]);
}