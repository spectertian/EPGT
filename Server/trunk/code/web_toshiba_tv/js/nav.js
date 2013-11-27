	/*$(document).ready(function(){
		$(".current").keydown(function(event){
			if(event.keyCode==37)
			{
				m.prvNav();
				$(".current").find('a').get(0).focus();	
				return false;
			}
			else if(event.keyCode==39)
			{
				m.nextNav();
				$(".current").find('a').get(0).focus();	
				return false;
			}
		});
	});*/

function nav(uri,txt)
{
        this.uri=uri;
        this.txt=txt;
}

function mainnav()
{
        this.navs=new Array();
        this.cops=0;
        this.maxsize=9;
        this.element;
        this.addNav=function(uri,txt)
        {
                this.navs[this.navs.length]=new nav(uri,txt);
        }
        this.prvNav = function(event){
                this.cops=this.cops-1;
                if(this.cops<0)
                        this.cops=this.cops+this.navs.length;
                var spans=this.element.find("li");
                var j=0;
                var len=(this.maxsize-1)/2;
                var pos=0;
                for(var i=this.cops-len;i<=this.cops+len;i++)
                {
                        pos=i;
                        if(pos<0)
                                pos=this.navs.length+pos;
                        else if(pos>this.navs.length-1)
                                pos=pos-this.navs.length;
                        if($(spans[j]).hasClass("action") ) {
                                $(spans[j]).text(this.navs[pos].txt);
                                $(spans[j]).attr('rel', this.navs[pos].uri);
                        }
                        else
                            $(spans[j]).html(this.navs[pos].txt);
                        j++;
                }
                $(".mainnav").find("a").attr("href",this.navs[this.cops].uri);
                //window.location = this.navs[this.cops].uri;
        }
        this.nextNav=function(event){
                this.cops=this.cops+1;
                if(this.cops>=this.navs.length)
                        this.cops=this.cops-this.navs.length;
                var spans=this.element.find("li");
                var j=0;
                var len=(this.maxsize-1)/2;
                var pos=0;
                for(var i=this.cops-len;i<=this.cops+len;i++)
                {
                        pos=i;
                        if(pos < 0)
                                pos=this.navs.length+pos;
                        else if(pos>this.navs.length-1)
                                pos=pos-this.navs.length;
                        if($(spans[j]).hasClass("action") ) {
                                $(spans[j]).text(this.navs[pos].txt);
                                $(spans[j]).attr('rel', this.navs[pos].uri);
                        }
                        else
                            $(spans[j]).html(this.navs[pos].txt);
                        j++;
                }
                //$(".mainnav").find("a").attr("href",this.navs[this.cops].uri);
                //window.location = this.navs[this.cops].uri;
        }
        this.writeNav=function()
        {
                var len=(this.maxsize-1)/2;
                var html="<ul>";
                var pos=0;
                for(var i=this.cops-len;i<=this.cops+len;i++)
                {
                        pos=i;
                        if(pos<0)
                                pos=this.navs.length+pos;
                        else if(pos>this.navs.length-1)
                                pos=pos-this.navs.length;
                        if(pos==this.cops)
                        {
                            html=html+"<li rel='"+this.navs[pos].uri+"' class='action actived'>"+this.navs[pos].txt+"</li>";
                        }
                        else
                        {
                                html=html+"<li rel='"+this.navs[pos].uri+"' class=''>"+this.navs[pos].txt+"</li>";
                        }
                }
                html=html+"</ul>";
                //alert(html);
                this.element.html(html);
        }

}