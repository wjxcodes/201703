function RecorderMarketing (){
	this.currentUrl = '';	//当前url地址
	this.refereUrl = '';	//前一个见面地址
	this.param = '';		//url参数
	this.timeStamp = '';
	this.utm_medium = '';
}
/**
 * 获得Referer
 */
RecorderMarketing.prototype.getRefereUrl = function(){
	/*if(document.referrer != ''){
		this.refereUrl = document.referrer;
	}else{
		if(this.getCookie('refereUrl')){
			this.refereUrl = this.getCookie('refereUrl');
		}else{
			this.refereUrl = '';
		}
	}*/
	var arr = ['regsourceid','utm_source','utm_medium','utm_campaign','utm_content','utm_term','utm_tag'];
	var isCover = document.referrer.indexOf('?')>-1 && document.referrer.indexOf(arr[0])>-1 && document.referrer.indexOf(arr[1])>-1 && document.referrer.indexOf(arr[2])>-1 && document.referrer.indexOf(arr[3])>-1 && document.referrer.indexOf(arr[4])>-1 && document.referrer.indexOf(arr[5])>-1 && document.referrer.indexOf(arr[6])>-1;
	
	if(document.referrer != '' && isCover){
		this.refereUrl = document.referrer;
	}else if(this.getCookie('fromUrl') != ''){
		this.refereUrl = this.getCookie('fromUrl');
	}else{
		this.refereUrl = document.referrer;
	}
	
}

/**
 * 获得当前Url地址
 */
RecorderMarketing.prototype.getCurrentUrl = function(){
	this.currentUrl = window.location.href;
}

/**
 * 获取cookie
 */
RecorderMarketing.prototype.getCookie = function(name){
	/*var arr = document.cookie.split('; ');
	for (var i = 0; i < arr.length; i++) {
		var arr2 = arr[i].split('=');
		if (arr2[0] == name) {
			return arr2[1];
		}
	}
	return '';*/
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
    if (arr != null) {
        var cookieValue=unescape(arr[2]);
            if(cookieValue!=undefined&& cookieValue.length>0)
            {
                return unescape(arr[2]); 
            } 
            return '';

        }
    return '';
}

/**
 * 设置cookie
 * @param name string cookie名
 * @param value string cookie值
 * @param day number cookie过期时间
 */
RecorderMarketing.prototype.setCookie = function(name,value,day){
	var oDate = new Date();
	oDate.setDate(oDate.getDate()+day);
	document.cookie = name+'='+value+';expires='+oDate+';domain=.ppdai.com;path=/';
}

RecorderMarketing.prototype.formatDate = function(){
	var d = new Date();
    var year = d.getFullYear();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var hours = d.getHours();
    var minutes = d.getMinutes();
    var seconds = d.getSeconds();
    this.timeStamp = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
}

/**
 * 删除cookie
 */
RecorderMarketing.prototype.removeCookie = function(name){
	this.setCookie(name,1,-1);
}

/**
 * 获取url参数
 */
RecorderMarketing.prototype.getParam = function(){
	var url = window.location.href;
	if(url.indexOf('?')>-1){
		var arr = url.split('?');
		this.param = arr[1];
	}else{
		this.param='';
	}	
}

/**
 * 解释参数
 */
RecorderMarketing.prototype.parseParam = function(){
	var isSeo = this.refereUrl.indexOf('baidu')>-1 || this.refereUrl.indexOf('google')>-1 || this.refereUrl.indexOf('sogou')>-1 || this.refereUrl.indexOf('haosou')>-1 || this.refereUrl.indexOf('bing')>-1;
	if(isSeo){
		this.utm_medium = 'SEO';
	}else if(this.refereUrl=='' || this.refereUrl.indexOf('ppdai')>-1){
		this.utm_medium = '直接';
	}else if(!isSeo && this.currentUrl.indexOf('?')<0 && this.refereUrl==''){
		this.utm_medium = '推荐';
	}
}


/**
 * 初始化方法
 */
RecorderMarketing.prototype.init = function(){
	this.getRefereUrl();
	this.getCurrentUrl();
	this.getParam();
	this.setCookie('fromUrl',this.refereUrl);
	this.setCookie('currentUrl',this.currentUrl);
	this.setCookie('param',this.param);
	this.setCookie("referDate", this.timeStamp);
	this.setCookie("utm_medium", this.utm_medium);
	this.setCookie("registerurl", this.currentUrl);
    this.setCookie("registersourceurl", this.refereUrl);
	//alert('currenturl:'+this.currentUrl+"\r\n"+'param:'+this.param+"\r\n"+'cookie:'+document.cookie);
}


//调用
var R = new RecorderMarketing();
R.init();

