/*锁定框的操作*/
// 锁定信息提示
function showLock(){
	isLock = true;
	$("lock").style.opacity = 1;
}
// 隐藏锁定信息提示
function hideLock(){
	isLock = false;
	lock_password = "";
	$("lock").style.opacity = 0;
	$("lock_password").innerText = " ";
	$("lock_tips").innerText = " ";
}
// 输入解锁密码
function addLockNum(val){
	if(lock_password.length < 6){
		lock_password += val;
		$("lock_password").innerText = lock_password.replace(/./g,"*");
	}
}
// 删除解锁密码输入
function delLockNum(){
	if(lock_password.length > 0){
		lock_password = lock_password.substring(0,lock_password.length - 1);
		$("lock_password").innerText = lock_password.replace(/./g,"*");
	}
}
// 解锁判断
function doLock(){
	if(lock_password == user.password){
		$("lock_tips").innerText = titleTxt[lang][4];
		clearTimeout(lockTimer);
		lockTimer = setTimeout(function(){
			hideLock();
			currRecord = currChannel.userChannel;
			var service = currChannel.getService();
			DVB.playAV(service.frequency,service.serviceId);
		},1000);
	}else{
		$("lock_tips").innerText = titleTxt[lang][5];
		lock_password = "";
		$("lock_password").innerText = "";	
		setTimeout("$('lock_tips').innerText = '';",2000);
	}
}