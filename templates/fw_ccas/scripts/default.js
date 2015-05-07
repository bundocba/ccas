/* Javscript Document  */
jQuery(document).ready(function() {
	
	jQuery(".topmenu").find("li:last").css({background: "none"});		
	jQuery(".topmenu ul li a").hover(function() {
		jQuery(this).parent().find("ul.subnav1").slideDown("slow");
		jQuery(this).parent().hover(function() {}, function(){
		jQuery(this).parent().find("ul.subnav1").slideUp("slow");
		});
	});
	jQuery(".topmenu ul.menu li").hover(function(){
		jQuery(this).find("ul:first").css({visibility: "visible",display: "none"}).fadeIn("slow");
		jQuery(this).parent().prev().addClass("active");

	},function(){
		jQuery(this).find("ul:first").css({visibility: "hidden"});
		jQuery(this).parent().prev().removeClass("active");
	});
	
	
	jQuery(".topmenu ul.menu li ul li a").each(function(){
		if(this.href == document.location.href){
			jQuery(this).parent().parent().prev().addClass("active");
		}
	});
	jQuery('.menu-login .login-greeting').hover(function(){
		jQuery('.menu-login .user-block').slideDown();
	},function(){
		//jQuery('.menu-login .user-block').slideUp();
	});
	jQuery('.menu-login .user-block').hover(function(){
		jQuery(this).show();
	},function(){
		jQuery(this).hide();
	});

});