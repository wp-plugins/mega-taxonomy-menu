jQuery(document).ready(function($) {
	$.fn.megaTaxonomyMenu = function(options) {
		
		var defaults = { sec: 18000 };
		var obj = this;
		var options = $.extend(defaults, options);
		
		// Let's cache some elements
		var $main_header    = obj;	
 		var $primary_nav    = $main_header.find('.primary-nav');
		var $sub_menu       = $main_header.find('.sub-menu');
		var $sub_navigation = $main_header.find('.sub-navigation');
		var $loader = $main_header.find('.icon-spinner');
		var method = "mtm_nav_objects";
		var menu_objects;
		var wait_objects = true;
		var delay_hover = 200;
		var max_width = mtm_column.max_width;
		var column = mtm_column.num;

		
		function get_nav_objects(tag, tag_this) {
		
			var data = { action: method }		
			menu_objects = jQuery.jStorage.get("meganav_objects");
			if(!menu_objects && wait_objects == true) { 
				wait_objects = false;
			
				$loader.show();
				jQuery.ajax({
					type: "POST",
					url: mtm_data.ajaxurl,
					data: data,
					dataType: "json",
					cache: false, // Set false for IE to clear cache
					success: function(response) {	
						$loader.hide();
						wait_objects = true;
					
						jQuery.jStorage.set("meganav_objects", response, {TTL: 1000 * options.sec});	
			
						
						tagObjects(tag, tag_this, response);
						

					}
				});
			} else if(wait_objects == true) {
			
				tagObjects(tag, tag_this, menu_objects);
			}
		}	
		
		function tagObjects(tag, tag_this, ajc) {


			tagObjs = ajc[tag];
			
			var html = "";
			if(ajc) {
				if(mtm_column.width == "fixed") {
					var lwd = "fixed-";
				} else {
					var lwd = "";
				}

				for(var key in tagObjs) {
					html+="<article class='menu-post "+lwd+"menu-post-" + column + "'>";

					if(tagObjs[key].thumb != null) {
						html+= '<a href="' + tagObjs[key].url + '" class="nav-post-thumbnail"><div class="thumb" ><img class="mam-lazy" src="' + tagObjs[key].thumb + '" alt="' + tagObjs[key].title + '" /></div></a>';
					}

					html+='<a href="'+tagObjs[key].url+'" class="nav-post-title">'+tagObjs[key].title+'</a>';
				
					html+="</article>";
				}
			}
			
			$obj = jQuery(obj);
			var tag_this_length = tag_this.next(".sub-menu").find(".nav-posts").length;
			
			if(tag_this_length > 0) {
				
				tag_this.next(".sub-menu").find(".nav-posts").html(html);
				
				hide_menu_post(tag_this.next(".sub-menu").find(".nav-posts"));
			} else {
				
				var $cur = jQuery(tag_this).parents(".sub-menu");
				$cur.find(".nav-posts").html(html);
			
				hide_menu_post($cur.find(".nav-posts"));
			}
			
			
		}
		
		function hide_menu_post(obj) {		
			if(mtm_column.width != "fixed") {
				return false;
			}
			var nv = max_width;
			var mw = max_width / column;						
			var dw = $(window).width();
			
			for(i=(column -1); i >= 0; i--) {
				if(nv >= dw) {
					if(obj != null) {
						
						$(obj).find(".menu-post").eq(i).css("display", "none");
					} else {
						$(".menu-post").eq(i).css("display", "none");
					}
				}
				nv = nv - mw;
			}
		}

		this.each(function() {
			if($("#wpadminbar").length > 0) {
				
				
				mtm_admin_bar_adjust();
				
			}
	 		$primary_nav.find("a[data-tag]").on({
			
                mouseenter: function() {
					
                    var $this = $(this);
 
					var tag = $this.data("tag");
					
					
					get_nav_objects(tag, $this);
					var title = $this.text();
					var url = $this.attr("href");
					var sub_menu = $this.parents(".sub-menu")
					if(sub_menu.length > 0) {
						sub_menu.find(".see-all a").attr("href", url).children("span").text(title);
					} else {
						$this.next(".sub-menu").find(".see-all a").attr("href", url).children("span").text(title);
					}
                }
            });	 		
			var slide_once = false;
			
			$primary_nav.find("li").on({
			
                mouseenter: function() {
					
                    var $this = $(this);
					 setTimeout(function(){
						$primary_nav.addClass('hover');						
						$this.addClass('hover').siblings().removeClass('hover');
						if(slide_once == false) {
							$this.find(".sub-menu").slideDown(300);
							slide_once = true;
						} else {
							$this.find(".sub-menu").show();							
						}
						

					}, delay_hover);

                },
                mouseleave: function() {
					var $this = $(this);				
                     setTimeout(function(){
						$this.removeClass('hover').children().removeClass('hover');
						$this.find(".sub-menu").hide();
						
					}, delay_hover);
                }
            });
			
			$sub_menu.on({
				mouseleave: function() {
					slide_once = false;
				}
			})
            // Show first articles list for each sub menu
            $sub_menu.each(function() {
                $(this).find('.articles').first().show();
            });

            // Attach event handlers to sub navigation elements
            $sub_navigation.find('li').on({
                mouseenter: function() {
                    $( $(this).data('target') ).show().siblings('.articles').hide();
                },
                mouseleave: function() {

                }
            });
			var clear = GetURLParameter("clear_jstorage");
			if(clear == 'true') {
				
				$.jStorage.deleteKey("meganav_objects");
			}

		})
	}

	$('#mtm-main-header').megaTaxonomyMenu({sec: mtm_column.minutes * 60 });

	
	
	
	$mobile_menu_link = $(".mtm-main-header a.show-menu");
	$mobile_menu_con = $(".mtm-mobile-menu");
	$mobile_menu_parent = $('.mtm-mobile-menu .parent');
	
	var menuStatus;
	$mobile_menu_link.click(function(){
		if(menuStatus != true){
			$mobile_menu_con.addClass("fixed-display");
			$("body").animate({
				marginLeft: "165px",
			}, 300, function(){menuStatus = true});
			return false;
			
		 } else {
			$mobile_menu_con.removeClass("fixed-display");
			$("body").animate({
				marginLeft: "0px",
			}, 300, function(){menuStatus = false});
			return false;
			
		 }
	});
	var navhtml = $(".navbrand-nav-social").html();
	
	$mobile_menu_con.html(navhtml);
	

	
	$search_icon = $(".mtm-main-header .search.popover");  
	$search_box = $(".mtm-main-header .search-box");  
	
	$search_icon.on({
		mouseenter: function(e) {

			$search_box.fadeIn();
		}
		
	});
	
	$search_box.on({
		mouseleave: function(e) {			
			$(this).fadeOut();
			
		}
	})		
	$(".mtm-main-header").on({
		mouseleave: function(e) {				
			$search_box.fadeOut();				
		}
	})


})



jQuery(window).load(function() {
	jQuery('.mtm-mobile-menu .parent').on({
		click: function(event) {
			
			
			if ( !jQuery(this).parent().is('.active') )
			{
				event.preventDefault();

				// Toggle active class on parent
				jQuery(this).parent().toggleClass('active');

				// Show/hide inner menu
				jQuery(this).next().toggle();

				// Toggle active class on siblings
				jQuery(this).parent().siblings().removeClass('active');

				// Show/hide inner menu on siblings
				jQuery(this).parent().siblings().children('.dropdown-menu').hide();
			}
			else
			{
				window.location = jQuery(this).attr('href');
			}
		}
	});
})

jQuery(window).resize(function() {
	mtm_admin_bar_adjust();
})

function mtm_admin_bar_adjust() {
	if(mtm_column.position == "fixed") {
		var wpheight = jQuery("#wpadminbar").height();
		jQuery(".mtm-main-header").css("top", wpheight);
	}
}

function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}