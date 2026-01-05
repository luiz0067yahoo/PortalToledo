var loadDimension;
jQuery(document).ready(function() {	
    loadDimension=function(){
		$(".height-square").each(function(){
				$(this).height($(this).width());
		});
		$(".dimenssion-parent").each(function(){
			height=($(this).parent().height());
			width=($(this).parent().width());
			$(this).height(height);
			$(this).width(width);
		});
		$(".max-height-parent-first-child").each(function(){
			height=($(this).parent().children(":first").height());
			$(this).css("max-height",height+"px");
		});
		$(".min-height-first-child").each(function(){
			height=($(this).first().height());
			if($(this).css("max-height").replace('px', '')>height)
				$(this).css("min-height",$(this).css("max-height"));
			else
				$(this).css("min-height",height+"px");
		});
		$(".h-80").each(function(){
			$(this).height($(this).parent().height()*0.8);
		});

	}
	loadDimension
	setTimeout(loadDimension,1000);
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
	
});